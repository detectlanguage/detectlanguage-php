Language Detection API PHP Client
========

[![PHP version](https://badge.fury.io/ph/detectlanguage%2Fdetectlanguage.svg)](https://badge.fury.io/ph/detectlanguage%2Fdetectlanguage)
[![Build Status](https://secure.travis-ci.org/detectlanguage/detectlanguage-php.svg)](http://travis-ci.org/detectlanguage/detectlanguage-php)

Detects language of given text. Returns detected language codes and scores.


## Installation

There are two ways to install:

### Require Library

```php
require_once("/path/to/lib/detectlanguage.php");
```

### Or via [Composer](http://getcomposer.org/):

Create or add the following to composer.json in your project root:
```javascript
{
    "require": {
        "detectlanguage/detectlanguage": "*"
    }
}
```

## Usage

### Configuration

Before using Detect Language API client you have to setup your personal API key.
You can get it by signing up at http://detectlanguage.com

    use \DetectLanguage\DetectLanguage;

    DetectLanguage::setApiKey("YOUR API KEY");

    // Enable secure mode if passing sensitive information
    // DetectLanguage::setSecure(true);

### Language detection

    $results = DetectLanguage::detect("Buenos dias señor");

#### Results

    Array
    (
        [0] => stdClass Object
            (
                [language] => es
                [isReliable] => 1
                [confidence] => 10.24
            )

    )

### Simple language detection

If you need just a language code you can use `simpleDetect`. It returns just the language code.

    $languageCode = DetectLanguage::simpleDetect("Buenos dias señor");

#### Result

    "es"

### Batch detection

It is possible to detect language of several texts with one request.
This method is faster than doing one request per text.
To use batch detection just pass array of texts to `detect` method.

    $results = DetectLanguage::detect(array("Buenos dias señor", "Hello world"));

#### Results

Result is array of detections in the same order as the texts were passed.

    Array
    (
        [0] => Array
            (
                [0] => stdClass Object
                    (
                        [language] => es
                        [isReliable] => 1
                        [confidence] => 10.24
                    )

            )

        [1] => Array
            (
                [0] => stdClass Object
                    (
                        [language] => en
                        [isReliable] => 1
                        [confidence] => 11.94
                    )

            )

    )

### Get your account status

    $results = DetectLanguage::getStatus();

#### Result

    stdClass Object
    (
        [date] => 2013-10-19
        [requests] => 1680
        [bytes] => 21800
        [plan] => FREE
        [plan_expires] =>
        [daily_requests_limit] => 5000
        [daily_bytes_limit] => 1048576
        [status] => ACTIVE
    )

## License

Detect Language API Client is free software, and may be redistributed under the terms specified in the MIT-LICENSE file.
