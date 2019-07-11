<?php
// Copyright (C) 2010-2018 Combodo SARL
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

require_once(APPROOT.'lib/archivetar/tar.php');

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

	/**
	 * @param string $p_filename
	 * @return bool
	 */
	public function _writeLongHeader($p_filename)
	{
		$v_uid = sprintf("%07s", 0);
		$v_gid = sprintf("%07s", 0);
		$v_perms = sprintf("%07s", 0);
		$v_size = sprintf("%'011s", DecOct(strlen($p_filename)));
		$v_mtime = sprintf("%011s", 0);
		$v_typeflag = 'L';
		$v_linkname = '';
		$v_magic = 'ustar ';
		$v_version = ' ';
		$v_uname = '';
		$v_gname = '';
		$v_devmajor = '';
		$v_devminor = '';
		$v_prefix = '';
		$v_binary_data_first = pack(
			"a100a8a8a8a12a12",
			'././@LongLink',
			$v_perms,
			$v_uid,
			$v_gid,
			$v_size,
			$v_mtime
		);
		$v_binary_data_last = pack(
			"a1a100a6a2a32a32a8a8a155a12",
			$v_typeflag,
			$v_linkname,
			$v_magic,
			$v_version,
			$v_uname,
			$v_gname,
			$v_devmajor,
			$v_devminor,
			$v_prefix,
			''
		);

		// ----- Calculate the checksum
		$v_checksum = 0;
		// ..... First part of the header
		for ($i = 0; $i < 148; $i++) {
			$v_checksum += ord(substr($v_binary_data_first, $i, 1));
		}
		// ..... Ignore the checksum value and replace it by ' ' (space)
		for ($i = 148; $i < 156; $i++) {
			$v_checksum += ord(' ');
		}
		// ..... Last part of the header
		for ($i = 156, $j = 0; $i < 512; $i++, $j++) {
			$v_checksum += ord(substr($v_binary_data_last, $j, 1));
		}

		// ----- Write the first 148 bytes of the header in the archive
		$this->_writeBlock($v_binary_data_first, 148);

		// ----- Write the calculated checksum
		$v_checksum = sprintf("%06s ", DecOct($v_checksum));
		$v_binary_data = pack("a8", $v_checksum);
		$this->_writeBlock($v_binary_data, 8);

		// ----- Write the last 356 bytes of the header in the archive
		$this->_writeBlock($v_binary_data_last, 356);

		// ----- Write the filename as content of the block
		$i = 0;
		while (($v_buffer = substr($p_filename, (($i++) * 512), 512)) != '') {
			$v_binary_data = pack("a512", "$v_buffer");
			$this->_writeBlock($v_binary_data);
		}

		return true;
	}

	// This is overloaded too but private...
	// private function _maliciousFilename($file)

	public function _addFile($p_filename, &$p_header, $p_add_dir, $p_remove_dir, $v_stored_filename = null)
	{
		// This method is modified, but cannot overload it here as it calls the \ArchiveTar::_pathReduction private method
		return parent::_addFile($p_filename, $p_header, $p_add_dir, $p_remove_dir, $v_stored_filename);
	}
}