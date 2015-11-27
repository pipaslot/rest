<?php

namespace Test\Libs\Rest;

use Pipas\Rest\CurlException;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

class CurlExceptionTest extends TestCase
{
	const MESSAGE = 'message';
	const URL = 'someUrl';
	private $curlinfo = array('url' => self::URL);

	function test_optionsNameConversion()
	{
		$data = array("value");
		$jsonData = json_encode($data);
		$options = array(
			CURLOPT_POSTFIELDS => $jsonData,
			99999999 => 1234);
		$optionsExpected = array(
			'CURLOPT_POSTFIELDS' => $data,
			99999999 => 1234);

		$e = new CurlException(self::MESSAGE, $options, $this->curlinfo);

		Assert::equal($optionsExpected, $e->getOptions());
	}

	function test_getters()
	{
		$e = new CurlException(self::MESSAGE, array(), $this->curlinfo);
		Assert::equal(self::MESSAGE, $e->getMessage());
		Assert::equal($this->curlinfo, $e->getInfo());
		Assert::equal(self::URL, $e->getUrl());
	}

	function test_response()
	{
		$data = array("value");
		$jsonData = json_encode($data);
		$response = array('data' => $jsonData);
		$responseExpected = array('data' => $data);
		$e = new CurlException(self::MESSAGE, array(), $this->curlinfo, $response);

		Assert::equal($responseExpected, $e->getResponse());
	}
}

$test = new CurlExceptionTest();
$test->run();