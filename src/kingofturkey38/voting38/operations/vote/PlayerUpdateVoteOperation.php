<?php

declare(strict_types=1);

namespace kingofturkey38\voting38\operations\vote;

use Closure;
use kingofturkey38\voting38\http\PostRequest;
use kingofturkey38\voting38\operations\BaseThreadedPlayerOperation;

class PlayerUpdateVoteOperation extends BaseThreadedPlayerOperation{

	public function __construct(protected string $username, Closure $closure, protected string $key){
		parent::__construct($this->username, $closure);
	}

	public function run(){
		$request = new PostRequest("https://minecraftpocket-servers.com/api/?action=post&object=votes&element=claim&key={$this->key}&username={$this->username}");
		return $request->execute();
	}
}