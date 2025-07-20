Detect Language API PHP Client
========

[![PHP version](https://badge.fury.io/ph/detectlanguage%2Fdetectlanguage.svg)](https://badge.fury.io/ph/detectlanguage%2Fdetectlanguage)
[![Build Status](https://github.com/detectlanguage/detectlanguage-php/actions/workflows/main.yml/badge.svg)](https://github.com/detectlanguage/detectlanguage-php/actions)

Detects language of the given text. Returns detected language codes and scores.


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

## Upgrading

When upgrading please check [changelog](CHANGELOG.md) for breaking changes.

## Usage

### Configuration

Before using Detect Language API client you have to setup your personal API key.
You can get it by signing up at https://detectlanguage.com

```php
use \DetectLanguage\DetectLanguage;

DetectLanguage::setApiKey("YOUR API KEY");
```

### Language detection

```php
$results = DetectLanguage::detect("Dolce far niente");
```

#### Results

```php
Array
(
    [0] => stdClass Object
        (
            [language] => it
            [score] => 0.5074
        )

)
```

### Simple language detection

If you need just a language code you can use `simpleDetect`. It returns just the language code.

```php
$languageCode = DetectLanguage::detectCode("Dolce far niente");
```

#### Result

```php
"it"
```

### Batch detection

It is possible to detect language of several texts with one request.
This method is faster than doing one request per text.
To use batch detection just pass array of texts to `detect` method.

```php
$results = DetectLanguage::detectBatch(array("Dolce far niente", "Hello world"));
```

#### Results

Result is array of detections in the same order as the texts were passed.

```php
Array
(
    [0] => Array
        (
            [0] => stdClass Object
                (
                    [language] => it
                    [score] => 0.5074
                )

        )

    [1] => Array
        (
            [0] => stdClass Object
                (
                    [language] => en
                    [score] => 0.9098
                )

        )

)
```

### Get your account status

```php
$results = DetectLanguage::getStatus();
```

#### Result

```php
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
```

### Get supported languages

```php
$results = DetectLanguage::getLanguages();
```

#### Result

```php
Array
(
    [0] => stdClass Object
        (
            [code] => aa
            [name] => Afar
        )

    [1] => stdClass Object
        (
            [code] => ab
            [name] => Abkhazian
        )
    ...
```

## License

Detect Language API Client is free software, and may be redistributed under the terms specified in the MIT-LICENSE file.
