Language Detection API PHP Client 
========

Detects language of given text. Returns detected language codes and scores.

[![Build Status](https://secure.travis-ci.org/detectlanguage/detectlanguage-php.png)](http://travis-ci.org/detectlanguage/detectlanguage-php)

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

### Language detection

    $results = DetectLanguage::detect("Buenos dias señor");

#### Results

    Array
    (
        [0] => stdClass Object
            (
                [language] => es
                [isReliable] => 
                [confidence] => 0.32710280373832
            )

        [1] => stdClass Object
            (
                [language] => pt
                [isReliable] => 
                [confidence] => 0.083565459610028
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
                        [isReliable] => 
                        [confidence] => 0.14018691588785
                    )

                [1] => stdClass Object
                    (
                        [language] => pt
                        [isReliable] => 
                        [confidence] => 0.083565459610028
                    )

            )

        [1] => Array
            (
                [0] => stdClass Object
                    (
                        [language] => en
                        [isReliable] => 
                        [confidence] => 0.17017828200972
                    )

                [1] => stdClass Object
                    (
                        [language] => vi
                        [isReliable] => 
                        [confidence] => 0.13673655423883
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

### Secure mode (SSL)

If you are passing sensitive information to the Detect Language API you can enable SSL.

SSL usage adds data and processing overhead. Please use only if encryption is really necessary.

    DetectLanguage::setSecure(true);

## License

Detect Language API Client is free software, and may be redistributed under the terms specified in the MIT-LICENSE file.
