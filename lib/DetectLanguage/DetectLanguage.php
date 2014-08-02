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
  public static $apiVersion = '0.2';

  /**
   * Enable secure mode (SSL).
   *
   * @static
   * @var boolean
   */
  public static $secure;

  /**
   * API Client Version.
   */
  const VERSION = '2.0.1';

  /**
   * Set API key
   *
   * @static
   * @var string
   */
  public static function setApiKey($apiKey) {
    self::$apiKey = $apiKey;
  }

  /**
   * Set secure mode
   *
   * @static
   * @var boolean
   */
  public static function setSecure($secure) {
    self::$secure = $secure;
  }

  /**
   * Detect text language.
   *
   * @static
   * @param string @text The text for language detection
   * @return array detected languages information
   */
  public static function detect($text) {
    $result = Client::request('detect', array('q' => $text));

    return $result->data->detections;
  }

  /**
   * Simple detection. If you need just the language code.
   *
   * @static
   * @param string @text The text for language detection
   * @return string detected language code
   */
  public static function simpleDetect($text) {
    $detections = self::detect($text);

    if (count($detections) > 0)
      return $detections[0]->language;
    else
      return null;
  }

  public static function getStatus() {
    return Client::request('user/status', array());
  }
}
