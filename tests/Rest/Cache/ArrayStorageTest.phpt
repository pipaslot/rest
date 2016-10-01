<?php

use Pipas\Rest\Cache\ArrayStorage;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @author Petr Štipek <p.stipek@email.cz>
 */
class ArrayStorageTest extends TestCase
{
	const KEY_1 = 'key1';
	const KEY_2 = 'key2';

	public function setUp()
	{
		parent::setUp();
	}

	public function test()
	{
		$value1 = 5;
		$value2 = 10;
		$storage = new ArrayStorage();

		Assert::null($storage->read(self::KEY_1));
		Assert::null($storage->read(self::KEY_2));

		$storage->write(self::KEY_1, $value1);
		Assert::equal($value1, $storage->read(self::KEY_1));
		Assert::null($storage->read(self::KEY_2));

		$storage->write(self::KEY_2, $value2);
		Assert::equal($value1, $storage->read(self::KEY_1));
		Assert::equal($value2, $storage->read(self::KEY_2));

		$storage->clean([]);
		Assert::null($storage->read(self::KEY_1));
		Assert::null($storage->read(self::KEY_2));
	}
}

$test = new ArrayStorageTest();
$test->run();