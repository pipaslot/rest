<?php

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
class ResultMapper_mapDataTest extends TestCase
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

	public function test_object()
	{
		$res = $this->mapper->mapData($this->data);

		Assert::true($res instanceof DataHash);
		Assert::equal($this->data['id'], $res->id);
		Assert::equal($this->data['name'], $res->name);
		Assert::true($res->sequential instanceof DataArray);
		Assert::equal($this->data['sequential'][0]['id'], $res->sequential->getFirst()->id);
		Assert::true($res->assoc instanceof DataHash);
		Assert::equal($this->data['assoc']['karel']['id'], $res->assoc->karel->id);
	}

	public function test_array()
	{
		$res = $this->mapper->mapData($this->dataSet);
		Assert::true($res instanceof DataArray, get_class($res));
		$first = $res[0];
		Assert::true($first instanceof DataHash);
	}

	public function test_emptyArray_EmptyObject1()
	{
		$res1 = $this->mapper->mapData(array());
		Assert::true($res1 instanceof DataHash, get_class($res1));
		$res2 = $this->mapper->mapData(array(), FakedDataHash::class);
		Assert::true($res2 instanceof FakedDataHash, get_class($res2));
	}

	public function test_nullTypes_defaultObjects()
	{
		$res1 = $this->mapper->mapData($this->data, null, null);
		Assert::true($res1 instanceof DataHash, get_class($res1));
		$res2 = $this->mapper->mapData($this->dataSet, null, null);
		Assert::true($res2 instanceof DataArray, get_class($res2));
	}

	public function test_array_mappingToDataSet()
	{
		/** @var DataSet $res */
		$res = $this->mapper->mapData($this->dataSet);
		Assert::true($res instanceof DataArray, get_class($res));
		$first = $res[0];
		Assert::true($first instanceof DataHash);
		//Data from DataSet is stored as DataHash
		Assert::true($res->getData() instanceof \ArrayObject);
	}

	public function test_arrayAndClass_mapData_ObjectExtendingOfDataHash()
	{
		/** @var DataSet $res */
		$res = $this->mapper->mapData($this->data, FakedDataHash::class);
		Assert::true($res instanceof FakedDataHash, get_class($res));
	}

	public function test_listOfArraysAndClass_mapData_DataSetOfObjectsExtendingOfDataHash()
	{
		/** @var DataSet $res */
		$res = $this->mapper->mapData($this->dataSet, FakedDataHash::class);
		Assert::true($res instanceof DataArray, get_class($res));
		$first = $res[0];
		Assert::true($first instanceof FakedDataHash);
		//Data from DataSet is stored as DataHash
		Assert::true($res->getData() instanceof \ArrayObject);
	}


}

class FakedDataHash extends DataHash
{

}


$test = new ResultMapper_mapDataTest();
$test->run();