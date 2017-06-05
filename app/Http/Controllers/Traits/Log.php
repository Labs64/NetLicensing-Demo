<?php

namespace App\Http\Controllers\Traits;

use Curl\CaseInsensitiveArray;

trait Log
{
    protected function createLog($curlInfo, $hidden = false)
    {

        $log = dot_collect();

        $log->put('hidden', $hidden);

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