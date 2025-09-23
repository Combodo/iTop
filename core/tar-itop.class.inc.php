<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

/**
 * Class ITopArchiveTar
 * Custom Combodo code added to the {@link Archive_Tar} class
 */
class ITopArchiveTar extends Archive_Tar
{
	const READ_BUFFER_SIZE = 1024*1024;

	public function __construct($p_tarname, $p_compress = null)
	{
		parent::__construct($p_tarname, $p_compress, self::READ_BUFFER_SIZE);
	}

	/**
	 * @param string $p_message
	 */
	public function _error($p_message)
	{
		IssueLog::Error($p_message);
	}

	/**
	 * @param string $p_message
	 */
	public function _warning($p_message)
	{
		IssueLog::Warning($p_message);
	}
}
