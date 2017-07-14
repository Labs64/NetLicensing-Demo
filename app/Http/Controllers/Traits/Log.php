<?php

namespace App\Http\Controllers\Traits;

use Curl\CaseInsensitiveArray;
use Illuminate\Support\Collection;
use NetLicensing\NetLicensingService;

trait Log
{
    protected function createLog($curlInfo = null, $attributes = [])
    {
        $log = dot_collect();

        $curlInfo = is_null($curlInfo) ? NetLicensingService::getInstance()->lastCurlInfo() : $curlInfo;
        $curlInfo = ($curlInfo instanceof Collection) ? $curlInfo->toArray() : $curlInfo;
        $curlInfo = is_array($curlInfo) ? (object)$curlInfo : $curlInfo;

        $attributes = ($attributes instanceof Collection) ? $attributes->toArray() : $attributes;

        foreach ($attributes as $key => $value) {
            $log->put($key, $value);
        }

        $log->put('error', $curlInfo->error);
        $log->put('errorCode', $curlInfo->errorCode);
        $log->put('errorMessage', $curlInfo->errorMessage);

        switch ($curlInfo->httpStatusCode) {
            case 400:
                $log->put('warning', true);
                $log->put('error', false);
                break;
            default:
                $log->put('warning', false);
                break;
        }

        $log->put('baseUrl', $curlInfo->baseUrl);
        $log->put('url', $curlInfo->url);
        $log->put('effectiveUrl', $curlInfo->effectiveUrl);
        $log->put('httpStatusCode', $curlInfo->httpStatusCode);

        //set data and query
        $log->put('data', $curlInfo->data);
        $log->put('query', $curlInfo->query);

        /**
         * set request headers and parse method, version and url part
         * @var  $requestHeaders CaseInsensitiveArray
         */
        $requestHeaders = $curlInfo->requestHeaders;

        $requestLine = $requestHeaders['request-line'];
        $requestLineParts = collect(explode(' ', $requestLine));

        $log->put('method', $requestLineParts->get(0, 'Unknown'));
        $log->put('urlPart', $requestLineParts->get(1, 'Unknown'));
        $log->put('version', $requestLineParts->get(2, 'Unknown'));

        $requestHeadersCount = $requestHeaders->count();

        $tmpRequestHeaders = [];

        while ($requestHeadersCount) {
            $tmpRequestHeaders[$requestHeaders->key()] = $requestHeaders->current();
            $requestHeaders->next();
            $requestHeadersCount--;
        }

        $log->put('requestHeaders', $tmpRequestHeaders);

        /**
         * set response headers
         * @var  $responseHeaders CaseInsensitiveArray
         */
        $responseHeaders = $curlInfo->responseHeaders;

        $responseHeadersCount = $responseHeaders->count();

        $tmpResponseHeaders = [];

        while ($responseHeadersCount) {
            $tmpResponseHeaders[$responseHeaders->key()] = $responseHeaders->current();
            $responseHeaders->next();
            $responseHeadersCount--;
        }

        $log->put('responseHeaders', $tmpResponseHeaders);
        $log->put('rawResponseHeaders', $curlInfo->rawResponseHeaders);

        //set response
        switch ($curlInfo->requestHeaders['accept']) {
            case 'application/xml':
                $dom = new \DOMDocument();
                $dom->preserveWhiteSpace = FALSE;
                $dom->loadXML($curlInfo->rawResponse);
                $dom->formatOutput = TRUE;

                $log->put('response', $dom->saveXml());
                $log->put('rawResponse', $curlInfo->rawResponse);

                break;
            default:
                $log->put('response', $curlInfo->response);
                $log->put('rawResponse', $curlInfo->rawResponse);
                break;
        }

        return $log;
    }
}