<?php

namespace Test\Libs\Rest;

use Pipas\Rest\Result\Contract;
use Pipas\Rest\Result\DataHash;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';

class ContractTest extends TestCase
{

	function test_primaryKey()
	{
		$id = 1234;
		$entity = new FakeContract();
		$entity->setId($id);
		Assert::equal($id, $entity->getId());
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

		$fake = FakeContract::fromDataHash($hash);
		Assert::equal($hash->toArray(), $fake->toArray());
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
