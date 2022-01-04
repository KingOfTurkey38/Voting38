<?php

declare(strict_types=1);

namespace kingofturkey38\voting38\operations;

use Closure;
use kingofturkey38\voting38\storage\ClosureStorage;

abstract class BaseThreadedPlayerOperation{

	protected string $identifier;

	public function __construct(protected string $username, Closure $closure){
		ClosureStorage::addClosure($this->identifier = uniqid(), $closure);
	}

	public function getIdentifier() : string{
		return $this->identifier;
	}

	abstract public function run();
}