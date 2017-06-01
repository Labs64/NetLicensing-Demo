<?php

namespace App\Http\Controllers\Traits;

use Curl\CaseInsensitiveArray;
use NetLicensing\NetLicensingCurl;

trait Log
{
    protected function createLog(NetLicensingCurl $curl, $hidden = false)
    {
        $log = dot_collect();

        $log->put('hidden', $hidden);

        $log->put('error', $curl->error);
        $log->put('errorCode', $curl->errorCode);
        $log->put('errorMessage', $curl->errorMessage);

        switch ($curl->httpStatusCode) {
            case 400:
                $log->put('warning', true);
                $log->put('error', false);
                break;
            default:
                $log->put('warning', false);
                break;
        }


        $log->put('baseUrl', $curl->baseUrl);
        $log->put('url', $curl->url);
        $log->put('effectiveUrl', $curl->effectiveUrl);
        $log->put('httpStatusCode', $curl->httpStatusCode);

        //set data and query
        $log->put('data', $curl->data);
        $log->put('query', $curl->query);

        /**
         * set request headers and parse method, version and url part
         * @var  $requestHeaders CaseInsensitiveArray
         */
        $requestHeaders = $curl->requestHeaders;

        $requestLine = $requestHeaders['request-line'];
        $requestLineParts = explode(' ', $requestLine);

        $log->put('method', $requestLineParts[0]);
        $log->put('urlPart', $requestLineParts[1]);
        $log->put('version', $requestLineParts[2]);

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
        $responseHeaders = $curl->responseHeaders;

        $responseHeadersCount = $responseHeaders->count();

        $tmpResponseHeaders = [];

        while ($responseHeadersCount) {
            $tmpResponseHeaders[$responseHeaders->key()] = $responseHeaders->current();
            $responseHeaders->next();
            $responseHeadersCount--;
        }

        $log->put('responseHeaders', $tmpResponseHeaders);
        $log->put('rawResponseHeaders', $curl->rawResponseHeaders);

        //set response
        switch ($curl->requestHeaders['accept']) {
            case 'application/xml':
                $dom = new \DOMDocument();
                $dom->preserveWhiteSpace = FALSE;
                $dom->loadXML($curl->rawResponse);
                $dom->formatOutput = TRUE;

                $log->put('response', $dom->saveXml());
                $log->put('rawResponse', $curl->rawResponse);

                break;
            default:
                $log->put('response', $curl->response);
                $log->put('rawResponse', $curl->rawResponse);
                break;
        }


        return $log;
    }
}