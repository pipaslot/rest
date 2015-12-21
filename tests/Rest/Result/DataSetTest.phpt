<?php

namespace Test\Libs\Rest\Result;

use Nette;
use Pipas\Rest\Result\DataSet;
use Pipas\Rest\Result\ResultMapper;
use Tester;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';

class DataSetTest extends TestCase
{

	const TOTAL_COUNT = 100;
	/** @var DataSet */
	private $set;
	private $data = array(
		array(
			'id' => 1,
			'name' => "Karel",
			'surname' => "Voprsalek"
		),
		array(
			'id' => 2,
			'name' => "Mirek",
			'surname' => "Sobek"
		),
		array(
			'id' => 3,
			'name' => "Honza",
			'surname' => "Kramolis"
		)
	);

	function setUp()
	{
		$mapper = ResultMapper::create();
		$this->set = $mapper->mapDataSet($this->data, self::TOTAL_COUNT);
	}

	function test_Getters()
	{
		Assert::equal(self::TOTAL_COUNT, $this->set->getTotalCount());
	}

}

$test = new DataSetTest();
$test->run();
