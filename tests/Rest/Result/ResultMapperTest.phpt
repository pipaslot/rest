<?php

namespace Test\Libs\Rest\Result;

use Pipas\Rest\Result\ResultMapper;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Petr Štipek <p.stipek@email.cz>
 */
class ResultMapperTest extends TestCase
{
	public function test_staticConstructor()
	{
		$ins1 = ResultMapper::create();
		Assert::same($ins1, ResultMapper::create());
	}

	public function test_publicConstructor()
	{
		new ResultMapper();
	}
}

$test = new ResultMapperTest();
$test->run();