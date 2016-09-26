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

if (!function_exists('apc_store') && function_exists('apcu_store'))
{
	// Emulate the API of APC, over APCU
	// Note: for PHP < 7, this compatibility used to be provided by APCU itself (if compiled with some options)
	//       for PHP 7+, it can be provided by the mean of apcu_bc, which is not so simple to install
	//       The current emulation aims at skipping this complexity
	function apc_store($key, $var, $ttl = 0)
	{
		return apcu_store($key, $var, $ttl);
	}
	function apc_fetch($key)
	{
		return apcu_fetch($key);
	}
	function apc_delete($key)
	{
		return apcu_delete($key);
	}
}
