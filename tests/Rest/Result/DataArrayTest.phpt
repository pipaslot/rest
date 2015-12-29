<?php

namespace Test\Libs\Rest\Result;

use Nette;
use Pipas\Rest\Result\DataArray;
use Pipas\Rest\Result\ResultMapper;
use Tester;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';

class DataArrayTest extends TestCase
{

	/** @var DataArray */
	private $array;
	private $data = array(
		array(
			'id' => 1,
			'name' => "Karel",
			'surname' => "Voprsalek"
		),
		array(
			'id' => 3,
			'name' => "Mirek",
			'surname' => "Sobek"
		),
		array(
			'id' => 2,
			'name' => "Honza",
			'surname' => "Kramolis"
		)
	);
	/** @var ResultMapper */
	private $mapper;

	function setUp()
	{
		$this->mapper = new ResultMapper();
		$this->array = $this->mapper->mapData($this->data);
	}

	function testSortSame()
	{
		$array = array(
			0 => array(
				'id' => 1
			),
			1 => array(
				'id' => 1
			));
		$coll = new DataArray(new \ArrayObject($array));
		Assert::true($coll->sortBy("id"));
		Assert::equal(1, $coll[0]['id']);
		Assert::equal(1, $coll[1]['id']);
	}

	function testSortAsc()
	{
		Assert::true($this->array->sortBy("id"));

		Assert::equal(1, $this->array[0]->id);
		Assert::equal(2, $this->array[1]->id);
		Assert::equal(3, $this->array[2]->id);
	}

	function testSortDesc()
	{
		Assert::true($this->array->sortBy("id", false));
		Assert::equal(3, $this->array[0]->id);
		Assert::equal(2, $this->array[1]->id);
		Assert::equal(1, $this->array[2]->id);
	}

	function testSortByUnknownColumn()
	{
		Assert::false($this->array->sortBy("unknown"));
	}

	function test_Getters()
	{
		Assert::equal($this->data[0]["id"], $this->array->getFirst()->id);
		Assert::equal($this->data[0]["id"], $this->array->getFirst()->id);
	}

	function test_Count()
	{
		Assert::equal(count($this->array->getData()), $this->array->count());
	}

	function testForeach()
	{
		$i = 0;
		foreach ($this->array as $o) {
			$i++;
		}

		Assert::same(count($this->data), $i);
	}

	function testDataAccess()
	{
		Assert::same(count($this->data), count($this->array->getData()));
	}

	function test_find_Mirek()
	{
		$id = 2;
		$res = $this->array->find($id);
		Assert::same($id, $res->id);
	}

	function test_select()
	{
		$list = $this->array->toList();

		foreach ($this->data as $row) {
			Assert::same($row['name'], $list[$row['id']]);
		}
	}

	function test_twoPropsWithoutFormat_select_ignoredOneProperty()
	{
		$list = $this->array->toList(array('name', 'surname'));

		foreach ($this->data as $row) {
			Assert::same($row['name'], $list[$row['id']]);
		}
	}

	function test_twoPropsWithFormat_select_useBothgProps()
	{
		$list = $this->array->toList(array('name', 'surname'), "%s-%s");

		foreach ($this->data as $row) {
			Assert::same($row['name'] . "-" . $row['surname'], $list[$row['id']]);
		}
	}


	function test_arrayAccess()
	{
		$new = 2;
		$this->array->offsetSet(1, $new);
		Assert::equal($new, $this->array->offsetGet(1));
		Assert::true($this->array->offsetExists(1));
		$this->array->offsetUnset(1);
		Assert::false($this->array->offsetExists(1));
	}
}

$test = new DataArrayTest();
$test->run();
