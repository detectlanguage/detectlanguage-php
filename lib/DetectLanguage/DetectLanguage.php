<?php

namespace DetectLanguage;


if (!function_exists('json_decode')) {
    throw new Error('DetectLanguage needs the JSON PHP extension.');
}

class DetectLanguage
{
    /**
     * The API key.
     *
     * @static
     * @var string
     */
    public static $apiKey;

    /**
     * API host.
     *
     * @static
     * @var string
     */
    public static $host = 'ws.detectlanguage.com';

    /**
     * API version.
     *
     * @static
     * @var string
     */
    public static $apiVersion = 'v3';

    /**
     * API Client Version.
     */
    const VERSION = '3.0.0';

    /**
     * Set API key
     *
     * @static
     * @param string $apiKey The API key for authentication
     * @return void
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    /**
     * Detect text language.
     *
     * @static
     * @param string $text The text for language detection
     * @return array Detected languages information
     * @throws \DetectLanguage\Error When API request fails, invalid response received, or authentication fails
     */
    public static function detect($text)
    {
        if (is_array($text)) {
            trigger_error('detect method does not accept arrays, use detectBatch instead', E_USER_DEPRECATED);
            return self::detectBatch($text);
        }

        return Client::request('POST', 'detect', array('q' => $text));
    }

    /**
     * Simple detection. If you need just the language code.
     *
     * @static
     * @param string $text The text for language detection
     * @return string|null Detected language code
     * @throws \DetectLanguage\Error When API request fails, invalid response received, or authentication fails
     */
    public static function detectCode($text)
    {
        $detections = self::detect($text);

        if (count($detections) > 0)
            return $detections[0]->language;
        else
            return null;
    }

    /**
     * Detect text language in batch.
     *
     * @static
     * @param array $texts The texts for language detection
     * @return array Detected languages information
     * @throws \DetectLanguage\Error When API request fails, invalid response received, or authentication fails
     */
    public static function detectBatch($texts)
    {
        return Client::request('POST', 'detect-batch', array('q' => $texts));
    }

    /**
     * Get account status.
     *
     * @static
     * @return array account status information
     * @throws \DetectLanguage\Error When API request fails, invalid response received, or authentication fails
     */
    public static function getStatus()
    {
        return Client::request('GET', 'account/status');
    }

    /**
     * Get supported languages.
     *
     * @static
     * @return array Supported languages information
     * @throws \DetectLanguage\Error When API request fails, invalid response received, or authentication fails
     */
    public static function getLanguages()
    {
        return Client::request('GET', 'languages');
    }

    // DEPRECATED METHODS

    /**
     * @deprecated use self::detectCode instead
     * @param string $text The text for language detection
     * @return string|null Detected language code
     * @throws \DetectLanguage\Error When API request fails, invalid response received, or authentication fails
     */
    public static function simpleDetect($text)
    {
        trigger_error('simpleDetect method is deprecated, use detectCode instead', E_USER_DEPRECATED);
        return self::detectCode($text);
    }
}
