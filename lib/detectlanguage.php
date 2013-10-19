<?php

// Require this file if you're not using composer's vendor/autoload

// Required PHP extensions
if (!function_exists('json_decode')) {
  throw new Exception('DetectLanguage needs the JSON PHP extension.');
}

// Library files
require(dirname(__FILE__) . '/DetectLanguage/Error.php');
require(dirname(__FILE__) . '/DetectLanguage/DetectLanguage.php');
require(dirname(__FILE__) . '/DetectLanguage/Client.php');
