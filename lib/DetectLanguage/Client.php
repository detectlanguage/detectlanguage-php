<?php

namespace DetectLanguage;

class Client
{
    /**
     * Request engine.
     *
     * @var string Request engine (curl or stream).
     */
    public static $requestEngine = 'curl';

    /**
     * Request timeout.
     *
     * @var int
     */
    public static $requestTimeout = 60;

    /**
     * Connect timeout.
     *
     * @var int
     */
    public static $connectTimeout = 10;

    /**
     * Perform a request
     *
     * @param string $method HTTP method name (GET, POST, etc.)
     * @param string $path API endpoint path
     * @param array|null $payload Request payload data
     *
     * @return array Response data
     * @throws \DetectLanguage\Error When API request fails, invalid response received, or authentication fails
     */
    public static function request($method, $path, $payload = null)
    {
        $url = self::getUrl($path);

        if ($payload !== null)
            $body = json_encode($payload);
        else
            $body = null;

        $engine_method = self::getEngineMethodName();
        $response_body = self::$engine_method($method,$url, $body);
        $response = json_decode($response_body);

        if (!is_object($response) && !is_array($response))
            throw new Error("Invalid server response: $response_body");

        if (isset($response->error))
            throw new Error($response->error->message);

        return $response;
    }

    protected static function getEngineMethodName()
    {
        $request_engine = self::$requestEngine;

        if ($request_engine == 'curl' && !function_exists('curl_init')) {
            trigger_error("DetectLanguage::Client - CURL not found, switching to stream");
            $request_engine = self::$requestEngine = 'stream';
        }

        switch ($request_engine) {
            case 'curl':
                return 'requestCurl';

            case 'stream':
                return 'requestStream';

            default:
                throw new Error("Invalid request engine: " . $request_engine);
        }
    }

    /**
     * Perform request using native PHP streams
     *
     * @param string $method HTTP method name
     * @param string $url Request URL
     * @param string|null $body Request body
     *
     * @return string Response body
     */
    protected static function requestStream($method, $url, $body)
    {
        $opts = array('http' =>
            array(
                'method' => $method,
                'header' => implode("\n", self::getHeaders()),
                'timeout' => self::$requestTimeout,
                'ignore_errors' => true,
            )
        );

        if ($body !== null) {
            $opts['http']['content'] = $body;
        }

        $context = stream_context_create($opts);

        return file_get_contents($url, false, $context);
    }

    /**
     * Perform request using CURL extension.
     *
     * @param string $method HTTP method name
     * @param string $url Request URL
     * @param string|null $body Request body
     *
     * @return string Response body
     * @throws \DetectLanguage\Error When CURL request fails, times out, or connection fails
     */
    protected static function requestCurl($method, $url, $body)
    {
        $ch = curl_init();

        $options = array(
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => self::getHeaders(),
            CURLOPT_CONNECTTIMEOUT => self::$connectTimeout,
            CURLOPT_TIMEOUT => self::$requestTimeout,
            CURLOPT_USERAGENT => self::getUserAgent(),
            CURLOPT_RETURNTRANSFER => true
        );

        if ($body !== null) {
            $options[CURLOPT_POSTFIELDS] = $body;
        }

        curl_setopt_array($ch, $options);

        $result = curl_exec($ch);

        if ($result === false) {
            $e = new Error(curl_error($ch));
            curl_close($ch);
            throw $e;
        }

        curl_close($ch);

        return $result;
    }

    /**
     * Build URL for given method
     *
     * @param string $method Method name
     * @return string Complete API URL
     */
    protected static function getUrl($method)
    {
        return 'https://' . DetectLanguage::$host . '/' . DetectLanguage::$apiVersion . '/' . $method;
    }

    /**
     * Build request headers.
     *
     * @return array Array of HTTP headers
     */
    protected static function getHeaders()
    {
        return array(
            "Content-Type: application/json",
            "Accept-Encoding: gzip, deflate",
            "User-Agent: " . self::getUserAgent(),
            "Authorization: Bearer " . DetectLanguage::$apiKey
        );
    }

    /**
     * Get User-Agent for the request.
     *
     * @return string User-Agent string
     */
    protected static function getUserAgent()
    {
        return 'detectlanguage-php-' . DetectLanguage::VERSION;
    }
}
