<?php

use Nette\Caching\IStorage;
use Pipas\Rest\AContext;
use Pipas\Rest\AService;
use Pipas\Rest\IConnection;
use Pipas\Rest\IContext;
use Pipas\Rest\RestException;
use Pipas\Rest\Result\Contract;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

class FakeContext extends AContext
{

	function __construct(IConnection $driver, IStorage $cacheStorage)
	{
		parent::__construct($driver, $cacheStorage);
		$this->addServiceMapping("*Service");
	}

	public function find($serviceName, $id)
	{

	}

	public function findAll($serviceName)
	{

	}

	public function findBy($serviceName, array $query)
	{

	}

	public function findOneBy($serviceName, array $query = array())
	{

	}

	public function validateToken($token)
	{

	}

	public function create($serviceName, array $entity)
	{

	}

	public function delete($uri)
	{

	}

	public function update($serviceName, array $entity)
	{

	}

}

class FakeService extends AService
{

	const SERVICE_NAME = "person";

	public function getName()
	{
		return self::SERVICE_NAME;
	}

	protected function getContractName()
	{
		return Contract::class;
	}
}

class FakeCompanyService extends AService
{
	protected function getContractName()
	{
		return Contract::class;
	}
}

class AServiceTest extends TestCase
{
	/** @var Mockery\MockInterface|IContext */
	private $context;
	/** @var Mockery\MockInterface|IDriver */
	private $driver;

	function setUp()
	{
		parent::setUp();
		$this->driver = Mockery::mock(IDriver::class);
		$this->context = Mockery::mock(FakeContext::class);
		$this->context->shouldReceive("getDriver")->andReturn($this->driver);
	}

	private function createService()
	{
		return new FakeService($this->context);
	}

	function test_create()
	{
		$returnId = 1100;
		$this->driver->shouldReceive("create")->andReturn($returnId)->once();
		$rep = $this->createService();
		$entity = new FakeContract();
		$entity->id = 1;
		$arr = $entity->toArrayForCreate();
		$rep->create($entity);
		unset($arr['id']);

		$this->driver->shouldHaveReceived("create", array($rep->getName(), $arr));
		Assert::equal($returnId, $entity->getId());
	}

	function test_missingId_update_exception()
	{
		$this->driver->shouldReceive("update")->once();
		$rep = $this->createService();
		$entity = new FakeContract();

		Assert::exception(function () use ($rep, $entity) {
			$rep->update($entity);
		}, RestException::class);
	}

	function test_update()
	{
		$this->driver->shouldReceive("update")->once();
		$rep = $this->createService();
		$entity = new FakeContract();
		$entity->id = 1;
		$rep->update($entity);
		$arr = $entity->toArrayForUpdate();
		unset($arr['id']);
		$this->driver->shouldHaveReceived("update", array($rep->getName() . '/' . $entity->id, $arr));
		Assert::true(true);
	}

	function test_delete()
	{
		$this->driver->shouldReceive("delete")->once();
		$rep = $this->createService();
		$entity = new FakeContract();
		$entity->id = 1;
		$rep->delete($entity);

		$this->driver->shouldHaveReceived("delete", array($rep->getName() . '/' . $entity->id));
		Assert::true(true);
	}

	function test_missingId_delete_exception()
	{
		$this->driver->shouldReceive("delete")->once();
		$rep = $this->createService();
		$entity = new FakeContract();

		Assert::exception(function () use ($rep, $entity) {
			$rep->delete($entity);
		}, RestException::class);
	}

}

class FakeContract extends Contract
{

}

$test = new AServiceTest();
$test->run();
