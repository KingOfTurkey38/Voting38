<?php

declare(strict_types=1);

namespace kingofturkey38\voting38\http;

class GetRequest extends BaseRequest{


	public function execute(){
		$req = curl_init($this->url);

		curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($req, CURLOPT_FORBID_REUSE, true);
		curl_setopt($req, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($req, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($req);
		curl_close($req);

		return $response;
	}
}