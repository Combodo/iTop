<?php
// Copyright (C) 2016 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

// Emulate the API of APC, over APCU
// Note: for PHP < 7, this compatibility used to be provided by APCU itself (if compiled with some options)
//       for PHP 7+, it can be provided by the mean of apcu_bc, which is not so simple to install
//       The current emulation aims at skipping this complexity
if (!function_exists('apc_store') && function_exists('apcu_store'))
{
	function apc_add($key, $var, $ttl = 0)
	{
		return apcu_add($key, $var, $ttl);
	}
	function apc_cache_info($cache_type = '', $limited = false)
	{
		return apcu_cache_info($limited);
	}
	function apc_cas($key, $old, $new)
	{
		return apcu_cas($key, $old, $new);
	}
	function apc_clear_cache($cache_type = '')
	{
		return apcu_clear_cache();
	}
	function apc_dec($key, $step = 1, &$success = null)
	{
		apcu_dec($key, $step, $success);
	}
	function apc_delete($key)
	{
		return apcu_delete($key);
	}
	function apc_exists($keys)
	{
		return apcu_exists($keys);
	}
	function apc_fetch($key)
	{
		return apcu_fetch($key);
	}
	function apc_inc($key, $step = 1, &$success = null)
	{
		apcu_inc($key, $step, $success);
	}
	function apc_sma_info($limited = false)
	{
		return apcu_sma_info($limited);
	}
	function apc_store($key, $var, $ttl = 0)
	{
		return apcu_store($key, $var, $ttl);
	}
}
