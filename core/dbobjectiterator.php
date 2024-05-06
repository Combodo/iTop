<?php
// Copyright (C) 2010-2024 Combodo SAS
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


/**
 * A set of persistent objects, could be heterogeneous as long as the objects in the set have a common ancestor class 
 *
 * @package     iTopORM
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
interface iDBObjectSetIterator extends Countable
{
	/**
	 * The class of the objects of the collection (at least a common ancestor)
	 *
	 * @return string
	 */
	public function GetClass();

	/**
	 * The total number of objects in the collection
	 *
	 * @return int
	 */
	public function Count(): int;

	/**
	 * Reset the cursor to the first item in the collection. Equivalent to Seek(0)
	 *
	 * @return DBObject The fetched object or null when at the end
	 */
	public function Rewind();

	/**
	 * Position the cursor to the given 0-based position
	 *
	 * @param int $iRow
	 */
	public function Seek($iPosition): void;

	/**
	 * Fetch the object at the current position in the collection and move the cursor to the next position.
	 *
	 * @return DBObject The fetched object or null when at the end
	 */
	public function Fetch();
}
