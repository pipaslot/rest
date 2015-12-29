<?php

namespace Test\Libs\Rest\Result;

use Mockery;
use Nette;
use Pipas\Rest\RestException;
use Pipas\Rest\Result\DataHash;
use Pipas\Rest\Result\ResultMapper;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Petr Štipek <p.stipek@email.cz>
 */
class ResultMapperTestTest extends TestCase
{
	/** @var  ResultMapper */
	private $mapper;

	public function setUp()
	{
		$this->mapper = new ResultMapper();
	}


	function test_FakeMappingExample_getAnnotatedProperties_arrayOfProperties()
	{
		$expected = array(
			'entity' => FakeMappingExample5::class
		);
		$res = $this->mapper->getAnnotatedProperties(FakeMappingExample1::class);

		Assert::equal($expected, $res);
		Assert::equal(array(), $this->mapper->getAnnotatedProperties(FakeMappingExample5::class));
	}

	function test_badDefinedClass_getAnnotatedProperties_exception()
	{
		Assert::exception(function () {
			$this->mapper->getAnnotatedProperties(FakeMappingExample2::class);
		}, RestException::class, null, RestException::CODE_PROPERTY_MUST_BE_PROTECTED);

		Assert::exception(function () {
			$this->mapper->getAnnotatedProperties(FakeMappingExample3::class);
		}, RestException::class, null, RestException::CODE_PROPERTY_MUST_BE_PROTECTED);

		$this->mapper->getAnnotatedProperties(FakeMappingExample4::class);

		Assert::exception(function () {
			$this->mapper->getAnnotatedProperties(FakeMappingExample6::class);
		}, RestException::class);

		Assert::exception(function () {
			$this->mapper->getAnnotatedProperties(FakeMappingExample7::class);
		}, RestException::class);
	}

	function test_classNotInheritedFromDataHash_getAnnotatedProperties_exception()
	{
		Assert::exception(function () {
			$this->mapper->getAnnotatedProperties(FakeMappingExample8::class);
		}, RestException::class, null, RestException::CODE_NOT_INHERITED_FROM);

		Assert::exception(function () {
			$this->mapper->getAnnotatedProperties(FakeMappingExample9::class);
		}, RestException::class, null, RestException::CODE_NOT_INHERITED_FROM);
	}
}

/**
 * @property FakeMappingExample5 $entity
 */
class FakeMappingExample1 extends DataHash
{
	protected $entity;
}

/**
 * @property FakeMappingExample5 $entity
 */
class FakeMappingExample2 extends DataHash
{
	public $entity;
}

/**
 * @property FakeMappingExample5 $entity
 */
class FakeMappingExample3 extends DataHash
{
	private $entity;
}

/**
 * @property FakeMappingExample5 $entity
 */
class FakeMappingExample4 extends DataHash
{

}

/**
 * @property Nonsense $nonsense
 */
class FakeMappingExample5 extends DataHash
{

}

/**
 * @property FakeMappingExample5 $entity
 */
abstract class FakeMappingExample6 extends DataHash
{

}

/**
 * @property FakeMappingExample5 $entity
 */
interface FakeMappingExample7
{

}

/**
 * @property FakeMappingExample7 $entity
 */
class FakeMappingExample8
{

}

/**
 * @property FakeMappingExample8 $entity
 */
class FakeMappingExample9
{

}

$test = new ResultMapperTestTest();
$test->run();