<?php

use Mockery\MockInterface;
use Nette\Caching\IStorage;
use Pipas\Rest\ADriver;
use Pipas\Rest\IConnection;
use Pipas\Rest\Result\IResultMapper;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

class FakeDriver extends ADriver
{

	function find($serviceName, $id)
	{
	}

	function findAll($serviceName)
	{
	}

	function findBy($serviceName, array $query)
	{
	}

	function findOneBy($serviceName, array $query = array())
	{
	}

	function create($serviceName, array $entity)
	{
	}

	function update($serviceName, array $entity)
	{
	}

	function delete($serviceName, array $query = array())
	{
	}

	function buildUrl($serviceName, $query = array())
	{
	}
}

//Description
test(function () {
	/** @var MockInterface|IConnection $connection */
	$connection = Mockery::mock(IConnection::class);
	/** @var MockInterface|IResultMapper $resultMapper */
	$resultMapper = Mockery::mock(IResultMapper::class);
	/** @var MockInterface|IStorage $storage */
	$storage = Mockery::mock(IStorage::class);

	$driver = new FakeDriver($connection, $resultMapper, $storage);
	Assert::same($connection, $driver->getConnection());
});

