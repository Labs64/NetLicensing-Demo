<?php

namespace App\Http\Controllers\Validations;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\History;
use App\Http\Controllers\Traits\Log;
use Faker\Factory;
use Illuminate\Support\Collection;
use NetLicensing\Context;
use NetLicensing\Licensee;
use NetLicensing\LicenseeService;
use NetLicensing\LicenseTemplate;
use NetLicensing\LicenseTemplateService;
use NetLicensing\NetLicensingService;
use NetLicensing\Product;
use NetLicensing\ProductModule;
use NetLicensing\ProductModuleService;
use NetLicensing\ProductService;
use NetLicensing\Token;
use NetLicensing\TokenService;
use NetLicensing\ValidationParameters;

abstract class ValidationController extends Controller
{
    use History,
        Log;

    protected $logs;
    protected $storage;
    protected $faker;

    protected $nlicAuthCacheKey = 'nlic.auth';

    public function __construct()
    {
        $this->logs = dot_collect();
        $this->faker = Factory::create();
    }

    protected function getNlicAuth($default = null)
    {
        return \Cache::get($this->nlicAuthCacheKey, $default);
    }

    protected function saveNlicAuth($auth)
    {
        $auth = ($auth instanceof Collection) ? $auth : collect($auth);

        \Cache::put($this->nlicAuthCacheKey, [
            'username' => $auth->get('username'),
            'password' => $auth->get('password'),
            'api_key' => $auth->get('api_key', config('nlic.auth.api_key'))
        ], config('nlic.cache.lifetime'));
    }

    protected function getApiContext($apiKey, $baseUrl = null)
    {
        $context = new Context();

        if ($baseUrl) {
            if (!filter_var($baseUrl, FILTER_VALIDATE_URL)) throw new \Exception('Base url is incorrect url.');

            $context->setBaseUrl($baseUrl);
        }

        $context->setSecurityMode(Context::APIKEY_IDENTIFICATION);
        $context->setApiKey($apiKey);

        return $context;
    }

    protected function getBasicContext($username, $password, $baseUrl = null)
    {
        $context = new Context();

        if ($baseUrl) {
            if (!filter_var($baseUrl, FILTER_VALIDATE_URL)) throw new \Exception('Base url is incorrect url.');

            $context->setBaseUrl($baseUrl);
        }

        $context->setSecurityMode(Context::BASIC_AUTHENTICATION);
        $context->setUsername($username);
        $context->setPassword($password);

        return $context;
    }

    protected function getProduct(Context $context, $number)
    {
        try {
            $product = ProductService::get($context, $number);

            //save to log product get
            $this->storeLog(null, ['hidden' => true]);

            return $product;

        } catch (\Exception $exception) {

            if ($this->getLastCurlInfo()->httpStatusCode == 400) {
                //set error to log but mark as hidden
                $this->storeLog(null, ['hidden' => true]);
                return null;
            }

            //set error to log
            $this->storeLog();

            throw $exception;
        }
    }

    protected function createProduct(Context $context, Product $product)
    {
        try {

            $product = ProductService::create($context, $product);

            //save to log product create
            $this->storeLog(null, ['hidden' => true]);

            return $product;

        } catch (\Exception $exception) {

            //set error to log
            $this->storeLog(null, ['hidden' => true]);

            throw $exception;
        }
    }

    protected function getProductModule(Context $context, $number)
    {
        try {
            $productModule = ProductModuleService::get($context, $number);

            //save to log product get
            $this->storeLog(null, ['hidden' => true]);

            return $productModule;

        } catch (\Exception $exception) {

            if ($this->getLastCurlInfo()->httpStatusCode == 400) {
                //set error to log but mark as hidden
                $this->storeLog(null, ['hidden' => true]);
                return null;
            }

            //set error to log
            $this->storeLog();

            throw $exception;
        }
    }

    protected function createProductModule(Context $context, $productNumber, ProductModule $productModule)
    {
        try {
            $productModule = ProductModuleService::create($context, $productNumber, $productModule);

            //save to log product create
            $this->storeLog(null, ['hidden' => true]);

            return $productModule;

        } catch (\Exception $exception) {

            //set error to log
            $this->storeLog();

            throw $exception;
        }
    }

    protected function getLicenseTemplate(Context $context, $number)
    {
        try {
            $licenseTemplate = LicenseTemplateService::get($context, $number);

            //save to log product get
            $this->storeLog(null, ['hidden' => true]);

            return $licenseTemplate;

        } catch (\Exception $exception) {

            if ($this->getLastCurlInfo()->httpStatusCode == 400) {
                //set error to log but mark as hidden
                $this->storeLog(null, ['hidden' => true]);
                return null;
            }

            //set error to log
            $this->storeLog();

            throw $exception;
        }
    }

    protected function createLicenseTemplate(Context $context, $productModuleNumber, LicenseTemplate $licenseTemplate)
    {
        try {
            $licenseTemplate = LicenseTemplateService::create($context, $productModuleNumber, $licenseTemplate);

            //save to log product create
            $this->storeLog(null, ['hidden' => true]);

            return $licenseTemplate;

        } catch (\Exception $exception) {
            //set error to log
            $this->storeLog();

            throw $exception;
        }
    }

    protected function getLicensee(Context $context, $number)
    {
        try {
            $licensee = LicenseeService::get($context, $number);

            //save to log product get
            $this->storeLog(null, ['hidden' => true]);

            return $licensee;

        } catch (\Exception $exception) {

            if ($this->getLastCurlInfo()->httpStatusCode == 400) {
                //set error to log but mark as hidden
                $this->storeLog(null, ['hidden' => true]);
                return null;
            }

            //set error to log
            $this->storeLog();

            throw $exception;
        }
    }

    protected function createLicensee(Context $context, $productNumber, Licensee $licensee)
    {
        try {
            $licensee = LicenseeService::create($context, $productNumber, $licensee);

            //save to log product create
            $this->storeLog(null, ['hidden' => true]);

            return $licensee;

        } catch (\Exception $exception) {
            //set error to log
            $this->storeLog();

            throw $exception;
        }
    }

    protected function runValidate(Context $context, $licenseeNumber, $productModuleNumber, ValidationParameters $parameters)
    {
        try {
            $results = LicenseeService::validate($context, $licenseeNumber, $parameters);

            $validations = collect($results->getValidations());
            $result = collect($validations->get($productModuleNumber, []));

            //save to log token create
            $this->storeLog(null, [
                'valid' => $result->get('valid') == 'true' ? true : false,
                'result' => $result->toArray(),
            ]);

            return $results;

        } catch (\Exception $exception) {
            //set error to log
            $this->storeLog();

            throw $exception;
        }
    }

    protected function createShopToken(Context $context, $licenseeNumber, $parameters = [])
    {
        try {
            $token = new Token();
            $token->setTokenType('SHOP');
            $token->setLicenseeNumber($licenseeNumber);

            if ($parameters) {
                foreach ($parameters as $key => $value) {
                    $token->setProperty($key, $value);
                }
            }

            $token = TokenService::create($context, $token);

            //save to log token create
            $this->storeLog(null, ['hidden' => true]);

            return $token;

        } catch (\Exception $exception) {
            //set error to log
            $this->storeLog(null, ['hidden' => true]);

            throw $exception;
        }
    }

    protected function getLastCurlInfo()
    {
        return NetLicensingService::getInstance()->lastCurlInfo();
    }

    protected function storeLog($curlInfo = null, $attributes = [])
    {
        return $this->logs->push($this->createLog($curlInfo, $attributes))->last();
    }
}