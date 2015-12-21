<?php

namespace Test\Libs\Rest;

use Pipas\Rest\RestException;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

class RestExceptionTest extends TestCase
{
	function test_constructs()
	{
		$this->test(RestException::noApiResult(), RestException::CODE_NO_API_RESULT);
		$this->test(RestException::notImplemented(), RestException::CODE_NOT_IMPLEMENTED);
		$this->test(RestException::nullValue("name"), RestException::CODE_NULL_VALUE);
		$this->test(RestException::notInheritedForm("class", "parent"), RestException::CODE_NOT_INHERITED_FROM);
		$this->test(RestException::resultSetNotSuccessfullyLoaded("url"), RestException::CODE_RESULT_SET_NOT_SUCCESSFULLY_LOADED);
		$this->test(RestException::recordNotFound(), RestException::CODE_RECORD_NOT_FOUND);
		$this->test(RestException::notProtectedProperty("class", "property"), RestException::CODE_PROPERTY_MUST_BE_PROTECTED);
	}

	private function test(RestException $e, $code)
	{
		Assert::true($e instanceof RestException);
		Assert::equal($code, $e->getCode());
	}
}

$test = new RestExceptionTest();
$test->run();