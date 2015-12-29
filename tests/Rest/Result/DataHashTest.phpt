<?php

namespace Test\Libs\Rest\Result;

use Mockery;
use Nette;
use Pipas\Rest\Result\Contract;
use Pipas\Rest\Result\DataHash;
use Tester;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';

class DataHashTest extends TestCase
{

	private $data = array(
		'id' => 1,
		'name' => "Karel",
		'surname' => "Voprsalek"
	);

	function test_constructor()
	{
		$hash = new DataHash();
		$hash->neco = 12;
		$hash->jine = "hod";
		Assert::equal(2, $hash->count());
	}

	function test_to_string()
	{
		$hash = new DataHash();
		$hash->neco = 12;
		$hash->jine = "hod";
		Assert::equal(json_encode(['neco' => 12, 'jine' => 'hod']), (string)$hash);
	}

	function test_getIterator()
	{
		$hash = new DataHash();
		Assert::true($hash->getIterator() instanceof \RecursiveArrayIterator);
	}

	function test_arrayAccess()
	{
		$id = 5;
		$hash = new DataHash();
		$hash->id = $id;
		Assert::equal($id, $hash->offsetGet("id"));

		$new = 2;
		$hash->offsetSet("id", $new);
		Assert::equal($new, $hash->offsetGet("id"));
		Assert::true($hash->offsetExists("id"));
		$hash->offsetUnset("id");
		Assert::false($hash->offsetExists("id"));
	}

	function test_defaultConversion_toArray()
	{
		$hash = new DataHash();
		foreach ($this->data as $key => $val) {
			$hash->$key = $val;
		}
		Assert::equal($this->data, $hash->toArray());
	}

	function test_setAndGetMagicMethod()
	{
		$value = "extra Value";
		$entity = new FakeMagicDataHash();

		Assert::null($entity->magicValue);
		$entity->magicValue = $value;
		Assert::equal($value, $entity->magicValue);

		Assert::null($entity->withoutSetter);
		$entity->withoutSetter = $value;
		Assert::equal($value, $entity->withoutSetter);

		Assert::null($entity->withoutGetter);
		$entity->withoutGetter = $value;
		Assert::equal($value, $entity->withoutGetter);
	}

	function text_initialization_onlyOnce()
	{
		$val = 100;
		$key = "key";
		$hash = new DataHash();
		Assert::false($hash->isPropertyInitialized($key));
		Assert::true($hash->initializeProperty($key, $val));
		Assert::true($hash->isPropertyInitialized($key));
		Assert::equal($val, $hash->$key);
		Assert::false($hash->initializeProperty($key, 5));
		Assert::equal($val, $hash->$key);
	}

	function test_toArray()
	{
		$hash = new DataHash();
		$hash->id = 2;
		$sub = $hash->subhash = new DataHash();
		$sub->id = 5;

		Assert::equal(array('id' => 2, 'subhash' => ['id' => 5]), $hash->toArray());
	}

	function test_mixedProperties()
	{
		$key = "bad key";
		$value = 100;
		$hash = new DataHash();
		Assert::false($hash->offsetExists($key));
		$hash[$key] = $value;
		Assert::true($hash->offsetExists($key));
		unset($hash[$key]);
		Assert::false($hash->offsetExists($key));

		$hash->$key = $value;
		Assert::true($hash->offsetExists($key));
		unset($hash->$key);
		Assert::false($hash->offsetExists($key));
	}

	function test_lazyInitialization()
	{
		$fake = new FakeContract();
		$fake->subEntity;
		Assert::true($fake->subEntity instanceof FakeSubContract, get_class($fake->subEntity));
		Assert::true($fake->nesmysl instanceof FakeSubContract, get_class($fake->nesmysl));
	}
}

/**
 * @property string $magicValue
 * @property string $withoutGetter
 * @property string $withoutSetter
 */
class FakeMagicDataHash extends DataHash
{

	protected $magicValue;
	protected $withoutGetter;
	protected $withoutSetter;

	public function getMagicValue()
	{
		return $this->magicValue;
	}

	public function setMagicValue($maxicValue)
	{
		$this->magicValue = $maxicValue;
		return $this;
	}

	public function setWithoutGetter($withoutGetter)
	{
		$this->withoutGetter = $withoutGetter;
		return $this;
	}

	public function getWithoutSetter()
	{
		return $this->withoutSetter;
	}

}

/**
 * Class FakeContract
 *
 * @property-read FakeSubContract $subEntity
 * @property-read FakeSubContract $nesmysl S popiskem
 */
class FakeContract extends Contract
{

	/** @var FakeSubContract */
	protected $subEntity = array("id" => 5);

}

class FakeSubContract extends Contract
{
	public $data;
}

$test = new DataHashTest();
$test->run();
