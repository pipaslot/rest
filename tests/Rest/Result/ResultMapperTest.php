<?php

namespace Test\Libs\Rest\Result;

use Mockery;
use Nette;
use Pipas\Rest\IReadOnlyService;
use Pipas\Rest\RestException;
use Pipas\Rest\Result\DataArray;
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

	public function test_staticConstructor()
	{
		$ins1 = ResultMapper::create();
		Assert::same($ins1, ResultMapper::create());
	}

	/************** mapData **************/
	public function test_object_mapData()
	{
		$res = $this->mapper->mapData($this->data);
		$this->assertData($res);
	}

	public function test_array_mapData()
	{
		$res = $this->mapper->mapData($this->dataSet);
		$first = $res[0];
		Assert::true($first instanceof DataHash);
	}

	public function test_array_mapData_FirstDataHash()
	{
		$res = $this->mapper->mapData($this->dataSet);
		$first = $res[0];
		Assert::true($first instanceof DataHash);
	}

	/************** mapDataSet **************/
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
		Assert::equal($count, $res->getTotalCount());
		$first = $res->getFirst();
		Assert::true($first instanceof DataHash);
	}

	/************** mapEntity **************/
	public function test_null_mapEntity_null()
	{
		Assert::null($this->mapper->mapEntity(null, Mockery::mock(IReadOnlyService::class)));
	}

	public function test_array_mapEntity()
	{
		$repository = Mockery::mock(IReadOnlyService::class);
		$res = $this->mapper->mapEntity($this->data, $repository);
		$this->assertData($res);
	}

	public function test_dataHash_mapEntity()
	{
		$hash = $this->mapper->mapData($this->data);

		$repository = Mockery::mock(IReadOnlyService::class);
		$res = $this->mapper->mapEntity($hash, $repository);
		$this->assertData($res);
	}

	function test_nesteadEntityInitialization()
	{
		$repository = Mockery::mock(IReadOnlyService::class);
		$expected = "expected value";
		$data = array(
			'subEntity' => array(
				'data' => $expected
			)
		);
		$fake = $this->mapper->mapEntity($data, $repository);
		Assert::true($fake->subEntity != null);
		Assert::equal($expected, $fake->subEntity->data);
	}

	/************** Annotations **************/

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

	/**************  **************/
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