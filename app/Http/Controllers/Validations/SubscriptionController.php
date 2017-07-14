<?php

namespace App\Http\Controllers\Validations;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use NetLicensing\Licensee;
use NetLicensing\LicenseTemplate;
use NetLicensing\Product;
use NetLicensing\ProductModule;
use NetLicensing\ValidationParameters;
use Validator;

class SubscriptionController extends ValidationController
{
    public function index(Request $request)
    {
        //get history
        $history = $this->getHistory($request->get('history'));

        $setup = dot_collect($history->get('setup', $this->setup()));
        $errors = $history->get('errors');
        $logs = dot_collect($history->get('logs'));
        $validation = dot_collect($history->get('validation'));
        $shop = dot_collect($history->get('shop'));
        $histories = dot_collect($this->getHistories($this->storage));

        $view = view('pages.subscription.index');

        if ($errors) $view->withErrors(($errors instanceof Collection) ? $errors->toArray() : $errors);

        return $view
            ->with('setup', $setup)
            ->with('logs', $logs)
            ->with('validation', $validation)
            ->with('shop', $shop)
            ->with('histories', $histories);
    }

    /**
     * Regenerate setup data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function regenerate(Request $request)
    {
        $keys = $request->get('keys', null);

        if ($request->expectsJson()) return response()->json($this->generate($keys));

        return redirect()->route('subscription');
    }

    /**
     * Nlic demo validate
     *
     * @param Request $request
     * @return mixed
     */
    public function nlicValidate(Request $request)
    {
        \Log::info('Request params', $request->all());

        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'use_api_key' => 'boolean',
            'use_agent' => 'sometimes|boolean',
            'product_module_number' => 'required|string',
            'licensee_number' => 'required|string',
        ]);

        $validator->sometimes('api_key', 'required|string', function ($input) {
            return ($input['use_api_key']);
        });

        $validator->sometimes('product_number', 'required|string', function ($input) {
            return empty($input['use_agent']);
        });

        $validator->sometimes('one_day_license_template_number', 'required|string', function ($input) {
            return empty($input['use_agent']);
        });

        $validator->sometimes('ten_days_license_template_number', 'required|string', function ($input) {
            return empty($input['use_agent']);
        });

        //validate $request inputs
        if ($validator->fails()) {

            $validator->errors()->add('setup', true);

            if ($validator->errors()->hasAny(['username', 'password', 'api_key'])) {
                $validator->errors()->add('setup.connection', true);
            } else {
                $validator->errors()->add('setup.additional', true);
            }

            if ($request->expectsJson()) return response()->json($validator->errors(), 422);

            \Log::error('Validator status - Error', $validator->errors()->toArray());

            return redirect()->route('subscription')
                ->withInput($request->all())
                ->withErrors($validator->errors()->toArray());
        }

        //save auth
        $this->saveNlicAuth([
            'username' => $request->get('username'),
            'password' => $request->get('password'),
            'api_key' => $request->get('api_key', config('nlic.auth.api_key'))
        ]);

        //create history
        $history = $this->createHistory(['setup' => $request->all()]);

        try {

            /**
             * PRE VALIDATION
             * Skip this step if need use agent
             */

            if (!$request->get('use_agent')) {

                //get pre validation context
                $preValidationBaseUrl = config('nlic.connections.netlicensing.base_url');
                $preValidationContext = $this->getBasicContext($request->get('username'), $request->get('password'), $preValidationBaseUrl);

                //get product
                $product = $this->getProduct($preValidationContext, $request->get('product_number'));

                //create product
                if (!$product) {

                    $product = new Product();
                    $product->setNumber($request->get('product_number'));
                    $product->setName($request->get('product_name'));
                    $product->setActive(true);
                    $product->setVersion(1.0);

                    $product = $this->createProduct($preValidationContext, $product);
                }

                //validate product
                if (!$product->getActive()) {
                    throw new \Exception('Product has inactive state');
                }

                //get product module
                $productModule = $this->getProductModule($preValidationContext, $request->get('product_module_number'));

                //create product module
                if (!$productModule) {

                    $productModule = new ProductModule();
                    $productModule->setNumber($request->get('product_module_number'));
                    $productModule->setName($request->get('product_module_name'));
                    $productModule->setActive(true);
                    $productModule->setLicensingModel('Subscription');

                    $productModule = $this->createProductModule($preValidationContext, $product->getNumber(), $productModule);
                }

                //validate product module
                if (!$productModule->getActive()) {
                    throw new \Exception('Product Module has inactive state');
                }

                if ($productModule->productNumber != $product->getNumber()) {
                    throw new \Exception('Product Module has wrong product number');
                }

                if ($productModule->getLicensingModel() != 'Subscription') {
                    throw new \Exception('Product Module has wrong licensing model');
                }


                //get try license template
                $oneDayLicenseTemplate = $this->getLicenseTemplate($preValidationContext, $request->get('one_day_license_template_number'));

                //create license template
                if (!$oneDayLicenseTemplate) {

                    $oneDayLicenseTemplate = new LicenseTemplate();
                    $oneDayLicenseTemplate->setNumber($request->get('one_day_license_template_number'));
                    $oneDayLicenseTemplate->setName($request->get('one_day_license_template_name'));
                    $oneDayLicenseTemplate->setActive(true);
                    $oneDayLicenseTemplate->setLicenseType('TIMEVOLUME');
                    $oneDayLicenseTemplate->setTimeVolume(1);
                    $oneDayLicenseTemplate->setCurrency('EUR');
                    $oneDayLicenseTemplate->setPrice(1);

                    $oneDayLicenseTemplate = $this->createLicenseTemplate($preValidationContext, $productModule->getNumber(), $oneDayLicenseTemplate);
                }

                //validate license template
                if (!$oneDayLicenseTemplate->getActive()) {
                    throw new \Exception('License Template (1 day) has inactive state');
                }

                if ($oneDayLicenseTemplate->productModuleNumber != $productModule->getNumber()) {
                    throw new \Exception('License Template (1 day) has wrong product module number');
                }

                //get buy license template
                $tenDaysLicenseTemplate = $this->getLicenseTemplate($preValidationContext, $request->get('ten_days_license_template_number'));

                //create license template
                if (!$tenDaysLicenseTemplate) {

                    $tenDaysLicenseTemplate = new LicenseTemplate();
                    $tenDaysLicenseTemplate->setNumber($request->get('ten_days_license_template_number'));
                    $tenDaysLicenseTemplate->setName($request->get('ten_days_license_template_name'));
                    $tenDaysLicenseTemplate->setActive(true);
                    $tenDaysLicenseTemplate->setLicenseType('TIMEVOLUME');
                    $tenDaysLicenseTemplate->setTimeVolume(10);
                    $tenDaysLicenseTemplate->setCurrency('EUR');
                    $tenDaysLicenseTemplate->setPrice(10);

                    $tenDaysLicenseTemplate = $this->createLicenseTemplate($preValidationContext, $productModule->getNumber(), $tenDaysLicenseTemplate);
                }

                //validate license template
                if (!$tenDaysLicenseTemplate->getActive()) {
                    throw new \Exception('License Template (10 days) has inactive state');
                }

                if ($tenDaysLicenseTemplate->productModuleNumber != $productModule->getNumber()) {
                    throw new \Exception('License Template (10 days) has wrong product module number');
                }

                //get licensee
                $licensee = $this->getLicensee($preValidationContext, $request->get('licensee_number'));

                if (!$licensee) {

                    $licensee = new Licensee();
                    $licensee->setNumber($request->get('licensee_number'));
                    $licensee->setName($request->get('licensee_name'));
                    $licensee->setActive(true);

                    $licensee = $this->createLicensee($preValidationContext, $product->getNumber(), $licensee);
                }

                //validate licensee
                if (!$licensee->getActive()) {
                    throw new \Exception('Licensee has inactive state');
                }

                if ($licensee->productNumber != $product->getNumber()) {
                    throw new \Exception('Licensee has wrong product number');
                }
            }

            /**
             * VALIDATION
             */

            //if agent base url does not set
            if ($request->get('use_agent') && is_null(config('nlic.connections.agent.base_url'))) {
                throw new \Exception('Agent base url does not set. Check .env file and nlic config settings.');
            }

            //get validate context
            $validationBaseUrl = $request->get('use_agent') ? config('nlic.connections.agent.base_url') : config('nlic.connections.netlicensing.base_url');
            $validationContext = $request->get('use_api_key')
                ? $this->getApiContext($request->get('api_key'), $validationBaseUrl)
                : $this->getBasicContext($request->get('username'), $request->get('password'), $validationBaseUrl);

            $validationParameters = new ValidationParameters();

            if (!$request->get('use_agent')) {
                $validationParameters->setLicenseeName($request->get('licensee_name'));
                $validationParameters->setProductNumber($request->get('product_number'));
            }

            $this->runValidate($validationContext, $request->get('licensee_number'), $request->get('product_module_number'), $validationParameters);

            //save validation log
            $validation = $this->logs->last();

            /**
             * POST VALIDATION
             * Skip this step if need use agent
             */

            if (!$request->get('use_agent')) {

                //get post validation context
                $postValidationBaseUrl = config('nlic.connections.netlicensing.base_url');
                $postValidationContext = $request->get('use_api_key')
                    ? $this->getApiContext($request->get('api_key'), $postValidationBaseUrl)
                    : $this->getBasicContext($request->get('username'), $request->get('password'), $postValidationBaseUrl);

                //create token
                $token = $this->createShopToken($postValidationContext, $request->get('licensee_number'), [
                    'successURL' => route('subscription.shop_success', ['history' => $history->get('id')]),
                    'successURLTitle' => 'Return to ' . config('app.name'),
                    'cancelURL' => route('subscription.shop_cancel', ['history' => $history->get('id')]),
                    'cancelURLTitle' => 'Cancel and return to ' . config('app.name'),
                ]);

                $history->put('shop', $token->toArray());
            }

        } catch (\Exception $exception) {

            \Log::critical('Last Curl Info', ['curlInfo' => (array)$this->getLastCurlInfo()]);
            \Log::error($exception);

            //save history
            $history->put('logs', $this->logs);
            $history->put('validation', $this->logs->last());
            $history->put('errors', ['validation' => $exception->getMessage()]);

            $this->saveHistory($history, $this->storage);

            return redirect()->route('subscription', ['history' => $history->get('id')]);
        }

        //save history
        $history->put('logs', $this->logs);
        $history->put('validation', $validation);

        $this->saveHistory($history);

        return redirect(route('subscription', ['history' => $history->get('id')]));
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
            return redirect()->route('subscription')->withErrors(['common' => $exception->getMessage()]);
        }
    }

    public function shopCancel(Request $request)
    {
        return redirect()->route('subscription', ['history' => $request->get('history')]);
    }

    /**
     * Get data for setup form
     *
     * @return Collection
     */
    protected function setup()
    {
        $setup = $this->generate()->merge($this->getNlicAuth());

        //default connection type
        $setup->put('use_api_key', config('nlic.defaults.use_api_key'));
        $setup->put('use_agent', config('nlic.defaults.use_agent'));

        return $setup;
    }

    /**
     * Generate default data for setup form
     *
     * @param null $keys
     * @return Collection
     */
    protected function generate($keys = null)
    {
        $generated = collect([
            'username' => config('nlic.auth.username'),
            'password' => config('nlic.auth.password'),
            'api_key' => config('nlic.auth.api_key'),
            'product_number' => $this->faker->bothify('P-########'),
            'product_name' => 'Subscription demo product',
            'product_module_number' => $this->faker->bothify('PM-########'),
            'product_module_name' => 'Module licensed under Subscription licensing model',
            'one_day_license_template_number' => $this->faker->bothify('LT-########'),
            'one_day_license_template_name' => '1-day subscription',
            'ten_days_license_template_number' => $this->faker->bothify('LT-########'),
            'ten_days_license_template_name' => '10-days subscription',
            'licensee_number' => $this->faker->bothify('L-########'),
            'licensee_name' => 'Licensee: ' . $this->faker->words(2, true),
        ]);

        return $generated->only($keys);
    }
}