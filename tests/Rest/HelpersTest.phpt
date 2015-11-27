<?php

namespace Test\Libs\Rest;

use Nette;
use OutOfRangeException;
use Pipas\Rest\Helpers;
use Tester;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

class HelpersTest extends TestCase
{

	function test_toBoolean()
	{
		//boolean
		Assert::true(Helpers::toBoolean(true));
		Assert::false(Helpers::toBoolean(false));

		//number
		Assert::true(Helpers::toBoolean(1));
		Assert::true(Helpers::toBoolean(50));
		Assert::false(Helpers::toBoolean(0));
		Assert::true(Helpers::toBoolean(1.0));
		Assert::false(Helpers::toBoolean(00));

		//null
		Assert::false(Helpers::toBoolean(null));

		//text
		Assert::true(Helpers::toBoolean("True"));
		Assert::false(Helpers::toBoolean("False"));
		Assert::exception(function () {
			Helpers::toBoolean("nesmysl");
		}, \OutOfRangeException::class);
	}

}

$test = new HelpersTest();
$test->run();
