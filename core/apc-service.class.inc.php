<?php

class ApcService {
	public function __construct() {
	}

	public function function_exists($function_name) {
		return function_exists($function_name);
	}

	/**
	 * @param $key string|array
	 * @return mixed
	 */
	function apc_fetch($key)
	{
		return apc_fetch($key);
	}

	/**
	 * @param array|string $key
	 * @param $var
	 * @param int $ttl
	 * @return array|bool
	 */
	function apc_store($key, $var = NULL, $ttl = 0)
	{
		return apc_store($key, $var, $ttl);
	}
}