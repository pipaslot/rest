<?php

namespace Test\Libs\Rest\Result;

use Pipas\Rest\Result\Contract;
use Pipas\Rest\Result\DataArray;
use Pipas\Rest\Result\DataHash;
use Pipas\Rest\Result\ResultMapper;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Petr Štipek <p.stipek@email.cz>
 */
class ResultMapper_mapEntityTest extends TestCase
{
	protected $data = array(
		'id' => 1,
		'name' => "Karel",
		'bad.name' => "value",
		'nullable' => null,
		'sequential' => array(
			array('id' => 1),
			array('id' => 1)
		),
		'assoc' => array(
			"karel" => array('id' => 1),
			'mirek' => array('id' => 1)
		)
	);

	/** @var  ResultMapper */
	private $mapper;

	public function setUp()
	{
		$this->mapper = new ResultMapper();
	}


	public function test_null_mapEntity_null()
	{
		Assert::null($this->mapper->mapEntity(null));
	}

	public function test_array_mapEntity()
	{
		$res = $this->mapper->mapEntity($this->data);
		$this->assertData($res);
	}

	public function test_dataHash_mapEntity()
	{
		$hash = $this->mapper->mapData($this->data);

		$res = $this->mapper->mapEntity($hash);
		$this->assertData($res);
	}

	function test_nestedEntityInitialization()
	{
		$expected = "expected value";
		$data = array(
			'subEntity' => array(
				'data' => $expected
			)
		);
		$fake = $this->mapper->mapEntity($data);
		Assert::true($fake->subEntity != null);
		Assert::equal($expected, $fake->subEntity->data);
	}

	public function test_nullTypes_defaultObject()
	{
		$res = $this->mapper->mapEntity($this->data,  null);
		Assert::true($res instanceof Contract, get_class($res));
	}
	/*************************************************/
	/**
	 * @param $res
	 */
	public function assertData($res)
	{
		Assert::true($res instanceof DataHash);
		Assert::equal($this->data['id'], $res->id);
		Assert::equal($this->data['name'], $res->name);
		Assert::true($res->sequential instanceof DataArray);
		Assert::equal($this->data['sequential'][0]['id'], $res->sequential->getFirst()->id);
		Assert::true($res->assoc instanceof DataHash);
		Assert::equal($this->data['assoc']['karel']['id'], $res->assoc->karel->id);
	}
}

$test = new ResultMapper_mapEntityTest();
$test->run();