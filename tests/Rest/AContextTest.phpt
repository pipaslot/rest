<?php

use Pipas\Rest\AContext;
use Pipas\Rest\AService;
use Pipas\Rest\IDriver;
use Pipas\Rest\Result\Contract;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

class FakeContext extends AContext
{
	public function validateToken($token)
	{

	}
}

class FakeCompanyService extends AService
{
	protected function getContractName()
	{
		return Contract::class;
	}
}

class AContextTest extends TestCase
{

	/** @var Mockery\MockInterface|IDriver */
	private $driver;

	function setUp()
	{
		parent::setUp();
		$this->driver = Mockery::mock(IDriver::class);
	}

	function test_getDriver()
	{
		$context = new FakeContext($this->driver);
		Assert::equal($this->driver, $context->getDriver());
	}

	function test_addMapping()
	{
		$context = new FakeContext($this->driver);
		Assert::exception(function () use ($context) {
			$context->addServiceMapping("WithougCross");
		}, \OutOfRangeException::class);

		Assert::exception(function () use ($context) {
			$context->addServiceMapping("Too*Much*Crosses");
		}, \OutOfRangeException::class);

		//Pass
		$context->addServiceMapping("Only\\*One");
		$context->addServiceMapping("*One");
	}

	function test_getService()
	{
		$context = new FakeContext($this->driver);

		//Mapping is not setup
		Assert::exception(function () use ($context) {
			$context->getService("fakeCompany");
		}, \OutOfRangeException::class);

		$context->addServiceMapping("*Service");

		$rep = $context->getService("fakeCompany");
		Assert::true($rep instanceof FakeCompanyService);
		//Check if are returned singletons
		$rep2 = $context->getService("fakeCompany");
		Assert::same($rep, $rep2);
		//Access via magic property
		$rep3 = $context->fakeCompany;
		Assert::same($rep, $rep3);

	}

}

$test = new AContextTest();
$test->run();
