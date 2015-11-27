<?php

namespace Test\Libs\Rest;

use Mockery;
use Nette;
use Pipas\Rest\IReadOnlyService;
use Pipas\Rest\Result\Contract;
use Pipas\Rest\Result\DataHash;
use Tester;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';

class ContractTest extends TestCase
{

	function test_repository()
	{
		$repository = Mockery::mock(IReadOnlyService::class);
		$entity = new FakeContract($repository);

		Assert::same($repository, $entity->getService());
		$entity->setService(null);
		Assert::null($entity->getService());
	}

	function test_serialization()
	{
		$repository = Mockery::mock(IReadOnlyService::class);
		$entity = new FakeContract($repository);
		$ser = serialize($entity);
		/** @var FakeContract $des */
		$des = unserialize($ser);
		Assert::null($des->getService());
	}

	function test_toString()
	{
		$id = 15;
		$entity = new FakeContract();
		$entity->id = $id;
		Assert::equal(FakeContract::class . ':' . $id, (string)$entity);
	}

	function test_fromDataHash()
	{
		$repository = Mockery::mock(IReadOnlyService::class);
		$data = array(
			'id' => 1,
			'name' => "Karel",
			'surname' => "Voprsalek",
			'subEntity' => null
		);
		$hash = new DataHash();
		foreach ($data as $key => $val) {
			$hash->$key = $val;
		}

		$fake = FakeContract::fromDataHash($hash, $repository);
		Assert::equal($hash->toArray(), $fake->toArray());
		Assert::same($repository, $fake->getService());
	}

	function test_null_fromDataHash_null()
	{
		$fake = FakeContract::fromDataHash(null);
		Assert::null($fake);
	}

}

class FakeContract extends Contract
{
}

$test = new ContractTest();
$test->run();
