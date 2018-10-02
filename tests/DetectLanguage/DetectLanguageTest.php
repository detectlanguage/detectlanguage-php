<?php

namespace DetectLanguage\Test;

use \DetectLanguage\DetectLanguage;

class DetectLanguageTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        DetectLanguage::$apiKey = getenv('DETECTLANGUAGE_API_KEY');
        DetectLanguage::$apiVersion = '0.2';
    }

    public function testConstructor()
    {
        DetectLanguage::$apiKey = null;
        DetectLanguage::setApiKey('123');

        $this->assertEquals(DetectLanguage::$apiKey, '123',
            'Expect the API key to be set.');
    }

    public function testDetection()
    {
        $result = DetectLanguage::detect('Hello world');

        $this->assertEquals('en', $result[0]->language,
            'To detect English language.');

        $result = DetectLanguage::detect('Jau saulelė vėl atkopdama budino svietą');

        $this->assertEquals('lt', $result[0]->language,
            'To detect Lithuanian language.');
    }

    public function testSimpleDetection()
    {
        $result = DetectLanguage::simpleDetect('Hello world');

        $this->assertEquals('en', $result,
            'To detect English language.');
    }

    public function testCurlRequest()
    {
        $this->setRequestEngine('curl');
        $this->__request();
    }

    public function testStreamRequest()
    {
        $this->setRequestEngine('stream');
        $this->__request();
    }

    public function testSecureRequest()
    {
        DetectLanguage::setSecure(true);
        $this->__request();
        DetectLanguage::setSecure(false);
    }

    public function testInvalidApiKey()
    {
        $this->setExpectedException('\DetectLanguage\Error');

        DetectLanguage::setApiKey('invalid');

        $result = DetectLanguage::simpleDetect('Hello world');
    }

    public function testErrorBackwardsCompatibility()
    {
        $this->setExpectedException('\DetectLanguage\DetectLanguageError');

        DetectLanguage::setApiKey('invalid');

        $result = DetectLanguage::simpleDetect('Hello world');
    }

    public function testInvalidResponse()
    {
        $this->setExpectedException('\DetectLanguage\Error');

        DetectLanguage::$apiVersion = '0.0';
        DetectLanguage::simpleDetect('Hello world');
    }

    public function testBatchDetectionWithCurl()
    {
        $this->setRequestEngine('curl');
        $this->__batchDetection();
    }

    public function testBatchDetectionWithStream()
    {
        $this->setRequestEngine('stream');
        $this->__batchDetection();
    }

    public function testBatchDetectionOrderWithCurl()
    {
        $this->setRequestEngine('curl');
        $this->__batchDetectionOrder();
    }

    public function testBatchDetectionOrderWithStream()
    {
        $this->setRequestEngine('stream');
        $this->__batchDetectionOrder();
    }

    public function testGetStatus()
    {
        $response = DetectLanguage::getStatus();
        $this->assertEquals($response->status, 'ACTIVE');
    }

    private function setRequestEngine($engine)
    {
        \DetectLanguage\Client::$requestEngine = $engine;
    }

    private function __request()
    {
        $result = DetectLanguage::simpleDetect('Hello world');

        $this->assertEquals('en', $result, 'To detect English language.');
    }

    private function __batchDetection()
    {
        $result = DetectLanguage::detect(array('Hello world', 'Jau saulelė vėl atkopdama budino svietą'));

        $this->assertEquals('en', $result[0][0]->language,
            'To detect English language.');

        $this->assertEquals('lt', $result[1][0]->language,
            'To detect Lithuanian language.');
    }

    private function __batchDetectionOrder()
    {
        $request = array(
            'привет',
            'hello',
            'привет',
            'hello',
            'привет',
            'hello',
            'привет',
            'hello',
            'привет',
            'hello',
            'привет',
            'hello',
            'привет',
            'hello',
            'привет',
            'hello',
            'привет',
            'hello',
            'привет',
            'hello',
            'привет',
            'hello',
        );

        $result = DetectLanguage::detect($request);

        foreach ($request as $i => $phrase) {
            $language = $phrase == 'hello' ? 'en' : 'ru';
            $this->assertEquals($language, $result[$i][0]->language);
        }
    }
}
