<?php

declare(strict_types=1);

namespace kingofturkey38\voting38\storage;

use Closure;

class ClosureStorage{

	/** @var array<string, Closure> */
	private static array $closures = [];

	public static function executeClosure(string $identifier, $data) : bool{
		if(isset(self::$closures[$identifier])){
			(self::$closures[$identifier])($data);

			return true;
		}

		return false;
	}

	public static function addClosure(string $identifier, Closure $closure) : void{
		self::$closures[$identifier] = $closure;
	}
}