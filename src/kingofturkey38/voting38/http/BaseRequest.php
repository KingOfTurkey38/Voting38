<?php

declare(strict_types=1);

namespace kingofturkey38\voting38\http;

abstract class BaseRequest{

	protected string $url;

	public function __construct(string $url){
		$this->url = str_replace(" ", "%20", $url);
	}

	abstract public function execute();
}