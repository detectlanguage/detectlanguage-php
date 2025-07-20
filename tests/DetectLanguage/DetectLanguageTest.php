<?php

namespace DetectLanguage\Test;

use \DetectLanguage\DetectLanguage;
use \Yoast\PHPUnitPolyfills\TestCases\TestCase;

class DetectLanguageTest extends TestCase
{
    public function set_up()
    {
        parent::set_up();

        DetectLanguage::$apiKey = getenv('DETECTLANGUAGE_API_KEY');
    }

    public function testConstructor()
    {
        DetectLanguage::$apiKey = null;
        DetectLanguage::setApiKey('123');

        $this->assertEquals(DetectLanguage::$apiKey, '123',
            'Expect the API key to be set.');
    }

    public function testDetect()
    {
        $result = DetectLanguage::detect('Hello world');

        $this->assertEquals('en', $result[0]->language,
            'To detect English language.');

        $result = DetectLanguage::detect('Jau saulelė vėl atkopdama budino svietą');

        $this->assertEquals('lt', $result[0]->language,
            'To detect Lithuanian language.');
    }

    public function testDetectWithArray()
    {
        $this->expectException('\DetectLanguage\Error');
        $result = DetectLanguage::detect(array('Hello world'));
    }

    public function testDetectCode()
    {
        $result = DetectLanguage::detectCode('Hello world');

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

    public function testInvalidApiKey()
    {
        $this->expectException('\DetectLanguage\Error');

        DetectLanguage::setApiKey('invalid');

        $result = DetectLanguage::detectCode('Hello world');
    }

    public function testErrorBackwardsCompatibility()
    {
        $this->expectException('\DetectLanguage\DetectLanguageError');

        DetectLanguage::setApiKey('invalid');

        $result = DetectLanguage::detectCode('Hello world');
    }

    public function testInvalidResponse()
    {
        $this->expectException('\DetectLanguage\Error');

        DetectLanguage::detect('');
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

    public function testGetLanguages()
    {
        $response = DetectLanguage::getLanguages();
        $this->assertIsArray($response);
        $this->assertIsString($response[0]->code);
    }

    private function setRequestEngine($engine)
    {
        \DetectLanguage\Client::$requestEngine = $engine;
    }

    private function __request()
    {
        $result = DetectLanguage::detectCode('Hello world');

        $this->assertEquals('en', $result, 'To detect English language.');
    }

    private function __batchDetection()
    {
        $result = DetectLanguage::detectBatch(array('Hello world', 'Jau saulelė vėl atkopdama budino svietą'));

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

        $result = DetectLanguage::detectBatch($request);

        foreach ($request as $i => $phrase) {
            $language = $phrase == 'hello' ? 'en' : 'ru';
            $this->assertEquals($language, $result[$i][0]->language);
        }
    }
}
