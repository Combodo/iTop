<?php

/**
 * Class ApcService
 * @since 2.7.6 N°4125
 */
class ApcService {
	public function __construct() {
	}

	/**
	 * @param string $function_name
	 * @return bool
	 * @see function_exists()
	 */
	public function function_exists($function_name) {
		return function_exists($function_name);
	}

	/**
	 * @param string|array $key
	 * @return mixed
	 * @see apc_fetch()
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
	 * @see apc_store()
	 */
	function apc_store($key, $var = NULL, $ttl = 0)
	{
		return apc_store($key, $var, $ttl);
	}
}
?>