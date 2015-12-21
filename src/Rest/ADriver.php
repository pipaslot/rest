<?php


namespace Pipas\Rest;

use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Pipas\Rest\Result\IResultMapper;
use Pipas\Rest\Result\ResultMapper;

/**
 * @author Petr Štipek <p.stipek@email.cz>
 */
abstract class ADriver implements IDriver
{
	/** @var IConnection */
	private $connection;
	/** @var Cache */
	protected $cache;
	/** @var ResultMapper */
	protected $resultMapper;

	/**
	 * ADriver constructor.
	 * @param IConnection $connection
	 * @param IResultMapper $resultMapper
	 * @param IStorage $storage
	 */
	public function __construct(IConnection $connection, IResultMapper $resultMapper, IStorage $storage)
	{
		$this->connection = $connection;
		$this->cache = new Cache($storage, get_called_class());
		$this->resultMapper = $resultMapper;
	}

	/**
	 * REST connection
	 * @return IConnection
	 */
	function getConnection()
	{
		return $this->connection;
	}

}