<?php
// Copyright (C) 2015 Combodo SARL
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
 * Data structures (i.e. PHP classes) to build and use relation graphs
 *
 * @copyright   Copyright (C) 2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 * 
 */

require_once(APPROOT.'core/simplegraph.class.inc.php');

/**
 * An object Node inside a RelationGraph
 */
class RelationObjectNode extends GraphNode
{
	public function __construct($oGraph, $oObject)
	{
		parent::__construct($oGraph, self::MakeId($oObject));
		$this->SetProperty('object', $oObject);
		$this->SetProperty('label', get_class($oObject).'::'.$oObject->GetKey().' ('.$oObject->Get('friendlyname').')');
	}

	public static function MakeId($oObject)
	{
		return get_class($oObject).'::'.$oObject->GetKey();
	}

}

/**
 * An redundancy Node inside a RelationGraph
 */
class RelationRedundancyNode extends GraphNode
{
	public function GetDotAttributes()
	{
		$sDot = 'shape=point,label="'.$this->GetProperty('threshold').'"';
		return $sDot;
// shape=point
	}
}


class RelationGraph extends SimpleGraph
{
	/**
	 * Recursively find related objects, and add them into the graph
	 * 
	 * @param string $sRelCode The code of the relation to use for the computation
	 * @param array $oObjectNode The node from which to compute the neighbours
	 * @param int $iMaxDepth
	 * @param boolean $bEnableReduncancy
	 * 
	 * @return void
	 */
	public function AddRelatedObjectsDown($sRelCode, $oObjectNode, $iMaxDepth, $bEnableRedundancy)
	{
		if ($iMaxDepth > 0)
		{
			$oObject = $oObjectNode->GetProperty('object');
			foreach (MetaModel::EnumRelationQueries(get_class($oObject), $sRelCode, true) as $sDummy => $aQueryInfo)
			{
				$sQuery = $aQueryInfo['sQueryDown'];
				try
				{
					$oFlt = DBObjectSearch::FromOQL($sQuery);
					$oObjSet = new DBObjectSet($oFlt, array(), $oObject->ToArgsForQuery());
					$oRelatedObj = $oObjSet->Fetch();
				}
				catch (Exception $e)
				{
					throw new Exception("Wrong query (downstream) for the relation $sRelCode/{$aQueryInfo['sDefinedInClass']}/{$aQueryInfo['sNeighbour']}: ".$e->getMessage());
				}
				if ($oRelatedObj)
				{
					do
					{
						$sObjectRef = 	RelationObjectNode::MakeId($oRelatedObj);
						$oRelatedNode = $this->GetNode($sObjectRef);
						if (is_null($oRelatedNode))
						{	
							$oRelatedNode = new RelationObjectNode($this, $oRelatedObj);
	
							// Recurse
							$this->AddRelatedObjectsDown($sRelCode, $oRelatedNode, $iMaxDepth - 1, $bEnableRedundancy);
						}
						$oEdge = new GraphEdge($this, $oObjectNode->GetId().' to '.$oRelatedNode->GetId(), $oObjectNode, $oRelatedNode);
					}
					while ($oRelatedObj = $oObjSet->Fetch());
				}
			}
		}
	}
}
