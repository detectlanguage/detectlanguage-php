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
     * @param string $method Method name
     * @param array $params The parameters to use for the POST body
     *
     * @return object
     */
    public static function request($method, $params = null)
    {
        $url = self::getUrl($method);

        $request_method = self::getRequestMethodName();
        $response_body = self::$request_method($url, $params);
        $response = json_decode($response_body);

        if (!is_object($response))
            throw new Error("Invalid server response: $response_body");

        if (isset($response->error))
            throw new Error($response->error->message);

        return $response;
    }

    /**
     * Get request method name.
     *
     * @return string
     */
    protected static function getRequestMethodName()
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
     * @param string $url Request URL
     * @param array $params The parameters to use for the POST body
     *
     * @return string Response body
     */
    protected static function requestStream($url, $params)
    {
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => implode("\n", self::getHeaders()),
                'content' => json_encode($params),
                'timeout' => self::$requestTimeout,
                'ignore_errors' => true,
            )
        );

        $context = stream_context_create($opts);

        return file_get_contents($url, false, $context);
    }

    /**
     * Perform request using CURL extension.
     *
     * @param string $url Request URL
     * @param array $params The parameters to use for the POST body
     *
     * @return string Response body
     */
    protected static function requestCurl($url, $params)
    {
        $ch = curl_init();

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => self::getHeaders(),
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_CONNECTTIMEOUT => self::$connectTimeout,
            CURLOPT_TIMEOUT => self::$requestTimeout,
            CURLOPT_USERAGENT => self::getUserAgent(),
            CURLOPT_RETURNTRANSFER => true
        );

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
     * @return string
     */
    protected static function getUrl($method)
    {
        return self::getProtocol() . '://' . DetectLanguage::$host . '/' . DetectLanguage::$apiVersion . '/' . $method;
    }

    protected static function getProtocol()
    {
        return DetectLanguage::$secure ? 'https' : 'http';
    }

    /**
     * Build request headers.
     *
     * @return string
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

    protected static function getUserAgent()
    {
        return 'detectlanguage-php-' . DetectLanguage::VERSION;
    }
}
