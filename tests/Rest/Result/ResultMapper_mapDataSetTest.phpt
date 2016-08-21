<?php

namespace Test\Libs\Rest\Result;

use Pipas\Rest\Result\DataArray;
use Pipas\Rest\Result\DataHash;
use Pipas\Rest\Result\DataSet;
use Pipas\Rest\Result\ResultMapper;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Petr Štipek <p.stipek@email.cz>
 */
class ResultMapper_mapDataSetTest extends TestCase
{
	private $data = array(
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
	private $dataSet = array(
		array(
			'id' => 1,
			'name' => "Karel",
			'surname' => "Voprsalek",
			'company' => array(
				'id' => 5
			),
			'nullable' => null
		),
		array(
			'id' => 3,
			'name' => "Mirek",
			'surname' => "Sobek",
			'company' => array(
				'id' => 7
			),
			'nullable' => null
		)
	);

	/** @var  ResultMapper */
	private $mapper;

	public function setUp()
	{
		$this->mapper = new ResultMapper();
	}

	function test_duplicit_mapDataSet_letOnce()
	{
		$coll = $this->mapper->mapDataSet(array(
			array(
				'id' => 1
			),
			array(
				'id' => 1
			)), 10);
		Assert::true($coll instanceof DataArray);
		Assert::equal(1, $coll->count());
	}

	public function test_mapResultSet()
	{
		$count = 100;
		$res = $this->mapper->mapDataSet($this->dataSet, $count);
		Assert::true($res instanceof DataSet);
		Assert::equal($count, $res->getTotalCount());
		$first = $res->getFirst();
		Assert::true($first instanceof DataHash);
	}

	public function test_nullTypes_defaultObject()
	{
		$res = $this->mapper->mapDataSet($this->dataSet, 0, null);
		Assert::true($res instanceof DataSet, get_class($res));
	}

	public function test_nestedDataSet()
	{
		$data = array(
			"Contract" => array(
				array(
					'id' => 1,
					'name' => "Karel",
					'surname' => "Voprsalek",
					'company' => array(
						'id' => 5
					),
					'nullable' => null
				)),
			"Person" => array()
		);
		$res = $this->mapper->mapDataSet($data, 1, null);
		Assert::true($res instanceof DataSet, get_class($res));
		$first = $res['Contract'];
		Assert::true($first instanceof DataArray, get_class($first));
		$second = $res['Person'];
		Assert::true($second instanceof DataArray, get_class($second));
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

$test = new ResultMapper_mapDataSetTest();
$test->run();