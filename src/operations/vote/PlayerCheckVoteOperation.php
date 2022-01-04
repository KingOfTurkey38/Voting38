<?php

declare(strict_types=1);

namespace kingofturkey38\voting38\operations\vote;

use Closure;
use kingofturkey38\voting38\http\GetRequest;
use kingofturkey38\voting38\operations\BaseThreadedPlayerOperation;

class PlayerCheckVoteOperation extends BaseThreadedPlayerOperation{

	public function __construct(protected string $username, Closure $closure, protected string $key){
		parent::__construct($this->username, $closure);
	}

	public function run(){
		$request = new GetRequest("https://minecraftpocket-servers.com/api/?object=votes&element=claim&key={$this->key}&username={$this->username}");
		return $request->execute();
	}
}