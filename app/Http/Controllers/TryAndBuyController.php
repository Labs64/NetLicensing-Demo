<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\History;
use App\Http\Controllers\Traits\Log;
use Faker\Factory;
use Illuminate\Http\Request;
use Cache;
use Illuminate\Support\Collection;
use NetLicensing\Context;
use NetLicensing\Licensee;
use NetLicensing\LicenseeService;
use NetLicensing\LicenseTemplate;
use NetLicensing\LicenseTemplateService;
use NetLicensing\NetLicensingCurl;
use NetLicensing\NetLicensingService;
use NetLicensing\Product;
use NetLicensing\ProductModule;
use NetLicensing\ProductModuleService;
use NetLicensing\ProductService;
use NetLicensing\Token;
use NetLicensing\TokenService;
use NetLicensing\ValidationParameters;
use Validator;

class TryAndBuyController extends Controller
{
    use History,
        Log;

    protected $storage = 'nlic.try_and_buy';
    protected $logs;
    protected $validationLog;

    public function __construct()
    {
        $this->logs = dot_collect();
        $this->validationLog = dot_collect();
    }

    public function index(Request $request)
    {
        $history = $this->getHistory($request->get('history'), $this->storage);

        $errors = $history->get('errors');
        $setup = dot_collect($history->get('setup', $this->createSetup()));
        $logs = dot_collect($history->get('logs'));
        $validationLog = dot_collect($history->get('validationLog'));
        $histories = dot_collect($this->getHistories($this->storage));

        $view = view('pages.try_and_buy.index');

        if ($errors) {
            $view->withErrors(($errors instanceof Collection) ? $errors->toArray() : $errors);
        }

        return $view
            ->with('setup', $setup)
            ->with('logs', $logs)
            ->with('validationLog', $validationLog)
            ->with('histories', $histories);
    }

    public function regenerate(Request $request)
    {
        $keys = $request->get('keys', null);

        if ($request->expectsJson()) {
            return response()->json($this->generate($keys));
        }

        return redirect()->route('try_and_buy');
    }

    public function nlicValidate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'product_number' => 'required|string',
            'product_module_number' => 'required|string',
            'try_license_template_number' => 'required|string',
            'buy_license_template_number' => 'required|string',
            'licensee_number' => 'required|string',
        ]);

        //validate $request inputs
        if ($validator->fails()) {
            if ($request->expectsJson()) return response()->json($validator->errors(), 422);

            $errors = $validator->errors()->toArray();
            $errors['setup'] = true;

            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($errors);
        }

        //save auth
        Cache::put('nlic.auth', ['username' => $request->get('username'), 'password' => $request->get('password')], config('nlic.cache.lifetime'));

        //create history
        $history = $this->createHistory(['setup' => $request->all()]);

        //setup context
        $context = new Context();
        $context->setUsername($request->get('username'));
        $context->setPassword($request->get('password'));
        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);

        //get or create Product
        $lastRequestMethod = null;

        try {

            //get product
            $product = $this->getProduct($context, $request->get('product_number'));

            //create product
            if (!$product) {

                $product = new Product();
                $product->setNumber($request->get('product_number'));
                $product->setName($request->get('product_name'));
                $product->setActive(true);
                $product->setVersion(1.0);

                $product = $this->createProduct($context, $product);
            }

            //validate product
            if (!$product->getActive()) {
                throw new \Exception('Product have inactive state');
            }

            //get product module
            $productModule = $this->getProductModule($context, $request->get('product_module_number'));

            //create product module
            if (!$productModule) {

                $productModule = new ProductModule();
                $productModule->setNumber($request->get('product_module_number'));
                $productModule->setName($request->get('product_module_name'));
                $productModule->setActive(true);
                $productModule->setLicensingModel('TryAndBuy');
                $productModule->setLicenseTemplate('TIMEVOLUME');

                $productModule = $this->createProductModule($context, $product->getNumber(), $productModule);
            }

            //validate product module
            if (!$productModule->getActive()) {
                throw new \Exception('Product Module have inactive state');
            }

            if ($productModule->productNumber != $product->getNumber()) {
                throw new \Exception('Product Module have wrong product number');
            }

            if ($productModule->getLicensingModel() != 'TryAndBuy') {
                throw new \Exception('Product Module have wrong licensing model');
            }


            //get try license template
            $tryLicenseTemplate = $this->getLicenseTemplate($context, $request->get('try_license_template_number'));

            //create license template
            if (!$tryLicenseTemplate) {

                $tryLicenseTemplate = new LicenseTemplate();
                $tryLicenseTemplate->setNumber($request->get('try_license_template_number'));
                $tryLicenseTemplate->setName($request->get('try_license_template_name'));
                $tryLicenseTemplate->setActive(true);
                $tryLicenseTemplate->setLicenseType('TIMEVOLUME');
                $tryLicenseTemplate->setTimeVolume(1);
                $tryLicenseTemplate->setAutomatic(true);
                $tryLicenseTemplate->setHidden(true);
                $tryLicenseTemplate->setCurrency('EUR');
                $tryLicenseTemplate->setPrice(0);

                $tryLicenseTemplate = $this->createLicenseTemplate($context, $productModule->getNumber(), $tryLicenseTemplate);
            }

            //validate license template
            if (!$tryLicenseTemplate->getActive()) {
                throw new \Exception('License Template (try) have inactive state');
            }

            if ($tryLicenseTemplate->productModuleNumber != $productModule->getNumber()) {
                throw new \Exception('License Template (try) have wrong product module number');
            }

            //get buy license template
            $buyLicenseTemplate = $this->getLicenseTemplate($context, $request->get('buy_license_template_number'));

            //create license template
            if (!$buyLicenseTemplate) {

                $buyLicenseTemplate = new LicenseTemplate();
                $buyLicenseTemplate->setNumber($request->get('buy_license_template_number'));
                $buyLicenseTemplate->setName($request->get('buy_license_template_name'));
                $buyLicenseTemplate->setActive(true);
                $buyLicenseTemplate->setLicenseType('FEATURE');
                $buyLicenseTemplate->setCurrency('EUR');
                $buyLicenseTemplate->setPrice(10);

                $buyLicenseTemplate = $this->createLicenseTemplate($context, $productModule->getNumber(), $buyLicenseTemplate);
            }

            //validate license template
            if (!$buyLicenseTemplate->getActive()) {
                throw new \Exception('License Template (buy) have inactive state');
            }

            if ($buyLicenseTemplate->productModuleNumber != $productModule->getNumber()) {
                throw new \Exception('License Template (buy) have wrong product module number');
            }

            //get licensee
            $licensee = $this->getLicensee($context, $request->get('licensee_number'));

            if (!$licensee) {

                $licensee = new Licensee();
                $licensee->setNumber($request->get('licensee_number'));
                $licensee->setName($request->get('licensee_name'));
                $licensee->setActive(true);

                $licensee = $this->createLicensee($context, $product->getNumber(), $licensee);
            }

            //validate licensee
            if (!$licensee->getActive()) {
                throw new \Exception('Licensee have inactive state');
            }

            if ($licensee->productNumber != $product->getNumber()) {
                throw new \Exception('Licensee have wrong product number');
            }

            //validate
            $validationParameters = new ValidationParameters();
            $validationParameters->setLicenseeName($licensee->getName());
            $validationParameters->setProductNumber($product->getNumber());

            $validationResults = LicenseeService::validate($context, $licensee->getNumber(), $validationParameters);
            $validations = collect($validationResults->getValidations());
            $validationResult = collect($validations->get($productModule->getNumber(), []));

            $this->validationLog = $this->log(NetLicensingService::getInstance()->lastCurlInfo());
            $this->validationLog->put('valid', $validationResult->get('valid'));

            $validation = collect();
            $validation->put('result', $validationResult->toArray());

            //create token
            $token = new Token();
            $token->setTokenType('SHOP');
            $token->setLicenseeNumber($licensee->getNumber());
            $token->setSuccessURL(route('try_and_buy.shop_success', ['history' => $history->get('id')]));
            $token->setCancelURL(route('try_and_buy.shop_cancel', ['history' => $history->get('id')]));

            $token = TokenService::create($context, $token);

            $this->log(NetLicensingService::getInstance()->lastCurlInfo(), true);

            $validation->put('shop', $token->toArray());

            $this->validationLog->put('validation', $validation);

        } catch (\Exception $exception) {
            //save history
            $history->put('logs', $this->logs);
            $history->put('validationLog', $this->logs->last());
            $history->put('errors', ['validation' => $exception->getMessage()]);

            $this->saveHistory($history, $this->storage);

            return redirect()->route('try_and_buy', ['history' => $history->get('id')]);
        }

        //save history
        $history->put('logs', $this->logs);
        $history->put('validationLog', $this->validationLog);

        $this->saveHistory($history, $this->storage);

        return redirect(route('try_and_buy', ['history' => $history->get('id')]));
    }

    public function shopSuccess(Request $request)
    {
        try {

            $history = $this->getHistory($request->get('history'), $this->storage);

            if ($history->isEmpty()) throw new \Exception('History not found');

            $request->request->add($history->get('setup'));
            $request->request->remove('history');

            return $this->nlicValidate($request);

        } catch (\Exception $exception) {
            return redirect()->route('try_and_buy')->withErrors(['common' => $exception->getMessage()]);
        }
    }

    public function shopCancel(Request $request)
    {
        return redirect()->route('try_and_buy', ['history' => $request->get('history')]);
    }

    protected function getProduct(Context $context, $number)
    {
        try {
            $product = ProductService::get($context, $number);

            //save to log product get
            $this->log(NetLicensingService::getInstance()->lastCurlInfo(), true);

            return $product;

        } catch (\Exception $exception) {

            $this->log(NetLicensingService::getInstance()->lastCurlInfo(), true);

            switch (NetLicensingService::getInstance()->lastCurlInfo()->httpStatusCode) {
                case '400':
                    return null;
                    break;
                default:
                    throw $exception;
                    break;
            }
        }
    }

    protected function createProduct(Context $context, Product $product)
    {
        try {
            $product = ProductService::create($context, $product);

            //save to log product create
            $this->log(NetLicensingService::getInstance()->lastCurlInfo(), true);

            return $product;

        } catch (\Exception $exception) {
            //set error to log
            $this->log(NetLicensingService::getInstance()->lastCurlInfo());

            throw $exception;
        }
    }

    protected function getProductModule(Context $context, $number)
    {
        try {
            $productModule = ProductModuleService::get($context, $number);

            //save to log product get
            $this->log(NetLicensingService::getInstance()->lastCurlInfo(), true);

            return $productModule;

        } catch (\Exception $exception) {

            $this->log(NetLicensingService::getInstance()->lastCurlInfo(), true);

            switch (NetLicensingService::getInstance()->lastCurlInfo()->httpStatusCode) {
                case '400':
                    return null;
                    break;
                default:
                    throw $exception;
                    break;
            }
        }
    }

    protected function createProductModule(Context $context, $productNumber, ProductModule $productModule)
    {
        try {
            $productModule = ProductModuleService::create($context, $productNumber, $productModule);

            //save to log product create
            $this->log(NetLicensingService::getInstance()->lastCurlInfo(), true);

            return $productModule;

        } catch (\Exception $exception) {
            //set error to log
            $this->log(NetLicensingService::getInstance()->lastCurlInfo());

            throw $exception;
        }
    }

    protected function getLicenseTemplate(Context $context, $number)
    {
        try {
            $licenseTemplate = LicenseTemplateService::get($context, $number);

            //save to log product get
            $this->log(NetLicensingService::getInstance()->lastCurlInfo(), true);

            return $licenseTemplate;

        } catch (\Exception $exception) {

            $this->log(NetLicensingService::getInstance()->lastCurlInfo(), true);

            switch (NetLicensingService::getInstance()->lastCurlInfo()->httpStatusCode) {
                case '400':
                    return null;
                    break;
                default:
                    throw $exception;
                    break;
            }
        }
    }

    protected function createLicenseTemplate(Context $context, $productModuleNumber, LicenseTemplate $licenseTemplate)
    {
        try {
            $licenseTemplate = LicenseTemplateService::create($context, $productModuleNumber, $licenseTemplate);

            //save to log product create
            $this->log(NetLicensingService::getInstance()->lastCurlInfo(), true);

            return $licenseTemplate;

        } catch (\Exception $exception) {
            //set error to log
            $this->log(NetLicensingService::getInstance()->lastCurlInfo());

            throw $exception;
        }
    }

    protected function getLicensee(Context $context, $number)
    {
        try {
            $licensee = LicenseeService::get($context, $number);

            //save to log product get
            $this->log(NetLicensingService::getInstance()->lastCurlInfo(), true);

            return $licensee;

        } catch (\Exception $exception) {

            $this->log(NetLicensingService::getInstance()->lastCurlInfo(), true);

            switch (NetLicensingService::getInstance()->lastCurlInfo()->httpStatusCode) {
                case '400':
                    return null;
                    break;
                default:
                    throw $exception;
                    break;
            }
        }
    }


    protected function createLicensee(Context $context, $productNumber, Licensee $licensee)
    {
        try {
            $licensee = LicenseeService::create($context, $productNumber, $licensee);

            //save to log product create
            $this->log(NetLicensingService::getInstance()->lastCurlInfo(), true);

            return $licensee;

        } catch (\Exception $exception) {
            //set error to log
            $this->log(NetLicensingService::getInstance()->lastCurlInfo());

            throw $exception;
        }
    }

    protected function createSetup()
    {
        $auth = Cache::get('nlic.auth', $this->generate()->only(['username', 'password'])->toArray());
        $setup = $this->generate()->except(['username', 'password'])->toArray();

        return $auth + $setup;
    }

    protected function generate($keys = null)
    {
        $faker = Factory::create();

        $generated = collect([
            'username' => config('nlic.auth.username'),
            'password' => config('nlic.auth.password'),
            'product_number' => $faker->bothify('P-########'),
            'product_name' => $faker->words(2, true),
            'product_module_number' => $faker->bothify('PM-########'),
            'product_module_name' => $faker->words(2, true),
            'try_license_template_number' => $faker->bothify('LT-########'),
            'try_license_template_name' => $faker->words(2, true),
            'buy_license_template_number' => $faker->bothify('LT-########'),
            'buy_license_template_name' => $faker->words(2, true),
            'licensee_number' => $faker->bothify('L-########'),
            'licensee_name' => $faker->words(2, true),
        ]);

        return $generated->only($keys);
    }

    protected function log($curlInfo, $hidden = false)
    {
        $log = $this->createLog($curlInfo, $hidden);

        $this->logs->push($log);

        return $log;
    }
}
