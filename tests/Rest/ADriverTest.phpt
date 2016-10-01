<?php

use Mockery\MockInterface;
use Nette\Caching\IStorage;
use Pipas\Rest\ADriver;
use Pipas\Rest\IConnection;
use Pipas\Rest\Result\DataHash;
use Pipas\Rest\Result\DataSet;
use Pipas\Rest\Result\IResultMapper;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

class FakeDriver extends ADriver
{



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

	/**
	 * Find one by ID
	 * @param string $serviceName
	 * @param int $id
	 * @param string $mapToType Target object type used for mapping result data
	 * @return null|DataHash
	 */
	function find($serviceName, $id, $mapToType = DataHash::class)
	{
	}

	/**
	 * Returns all available records
	 * @param string $serviceName
	 * @param string $mapToType Target object type used for mapping result data items to DataSet
	 * @return DataSet
	 */
	function findAll($serviceName, $mapToType = DataHash::class)
	{
	}

	/**
	 * Returns all available records filtered by query
	 * @param string $serviceName
	 * @param array $query
	 * @param string $mapToType Target object type used for mapping result data items to DataSet
	 * @return DataSet
	 */
	function findBy($serviceName, array $query, $mapToType = DataHash::class)
	{
	}

	/**
	 * Returns the first from available records filtered by query
	 * @param string $serviceName
	 * @param array $query
	 * @param string $mapToType Target object type used for mapping result data
	 * @return DataHash|null
	 */
	function findOneBy($serviceName, array $query = array(), $mapToType = DataHash::class)
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

	$driver = new FakeDriver($connection, $storage, $resultMapper);
	Assert::same($connection, $driver->getConnection());
});

