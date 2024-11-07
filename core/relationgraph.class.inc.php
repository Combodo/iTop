<?php
// Copyright (C) 2015-2024 Combodo SAS
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
 * @copyright   Copyright (C) 2015-2024 Combodo SARL
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

	/**
	 * Make a normalized ID to ensure the uniqueness of such a node
	 *
	 * @param string $oObject
	 *
	 * @return string
	 */
	public static function MakeId($oObject)
	{
		return get_class($oObject).'::'.$oObject->GetKey();
	}

	/**
	 * Formatting for GraphViz
	 *
	 * @param bool $bNoLabel
	 *
	 * @return string
	 */
	public function GetDotAttributes($bNoLabel = false)
	{
		$sDot = parent::GetDotAttributes();
		if ($this->GetProperty('developped', false))
		{
			$sDot .= ',fontcolor=black';
		}
		else
		{
			$sDot .= ',fontcolor=lightgrey';
		}
		if ($this->GetProperty('source', false) || $this->GetProperty('sink', false))
		{
			$sDot .= ',shape=rectangle';
		}
		if ($this->GetProperty('is_reached', false))
		{
			$sDot .= ',fillcolor="#ffdddd"';
		}
		else
		{
			$sDot .= ',fillcolor=white';
		}
		return $sDot;
	}

	/**
	 * Recursively mark the objects nodes as reached, unless we get stopped by a redundancy node or a 'not allowed' node
	 *
	 * @param string $sProperty
	 * @param $value
	 */
	public function ReachDown($sProperty, $value)
	{
		if (is_null($this->GetProperty($sProperty)) && ($this->GetProperty($sProperty.'_allowed') !== false))
		{
			$this->SetProperty($sProperty, $value);
			foreach ($this->GetOutgoingEdges() as $oOutgoingEdge)
			{
				// Recurse
				$oOutgoingEdge->GetSinkNode()->ReachDown($sProperty, $value);
			}
		}
	}
}

/**
 * An redundancy Node inside a RelationGraph
 */
class RelationRedundancyNode extends GraphNode
{
	public function __construct($oGraph, $sId, $iMinUp, $fThreshold)
	{
		parent::__construct($oGraph, $sId);
		$this->SetProperty('min_up', $iMinUp);
		$this->SetProperty('threshold', $fThreshold);
	}

	/**
	 * Make a normalized ID to ensure the uniqueness of such a node
	 *
	 * @param string $sRelCode
	 * @param string $sNeighbourId
	 * @param $oSourceObject
	 * @param \DBObject $oSinkObject
	 *
	 * @return string
	 */
	public static function MakeId($sRelCode, $sNeighbourId, $oSourceObject, $oSinkObject)
	{
		return 'redundancy-'.$sRelCode.'-'.$sNeighbourId.'-'.get_class($oSinkObject).'::'.$oSinkObject->GetKey();
	}

	/**
	 * Formatting for GraphViz
	 *
	 * @param bool $bNoLabel
	 *
	 * @return string
	 */
	public function GetDotAttributes($bNoLabel = false)
	{
		$sDisplayThreshold = sprintf('%.1f', $this->GetProperty('threshold'));
		$sDot = 'shape=doublecircle,fillcolor=indianred,fontcolor=papayawhip,label="'.$sDisplayThreshold.'"';
		return $sDot;
	}

	/**
	 * Recursively mark the objects nodes as reached, unless we get stopped by a redundancy node
	 *
	 * @param string $sProperty
	 * @param $value
	 */
	public function ReachDown($sProperty, $value)
	{
		$this->SetProperty($sProperty.'_count', $this->GetProperty($sProperty.'_count', 0) + 1);
		if ($this->GetProperty($sProperty.'_count') > $this->GetProperty('threshold'))
		{
			// Looping... though there should be only ONE SINGLE outgoing edge
			foreach ($this->GetOutgoingEdges() as $oOutgoingEdge)
			{
				// Recurse
				$oOutgoingEdge->GetSinkNode()->ReachDown($sProperty, $value);
			}
		}
	}
}


/**
 * Helper to name the edges in a unique way
 */ 
class RelationEdge extends GraphEdge
{
	/**
	 * RelationEdge constructor.
	 *
	 * @param \SimpleGraph $oGraph
	 * @param \GraphNode $oSourceNode
	 * @param \GraphNode $oSinkNode
	 * @param bool $bMustBeUnique
	 *
	 * @throws \SimpleGraphException
	 */
	public function __construct(SimpleGraph $oGraph, GraphNode $oSourceNode, GraphNode $oSinkNode, $bMustBeUnique = false)
	{
		$sId = $oSourceNode->GetId().'-to-'.$oSinkNode->GetId();
		parent::__construct($oGraph, $sId, $oSourceNode, $oSinkNode, $bMustBeUnique);
	}
}

/**
 * A graph representing the relations between objects
 * The graph is made of two types of nodes. Here is a list of the meaningful node properties
 * 1) RelationObjectNode
 *    source: boolean, that node was added as a source node
 *    sink: boolean, that node was added as a sink node
 *    reached: boolean, that node has been marked as reached (impacted by the source nodes)
 *    developped: boolean, that node has been visited to search for related objects    
 * 1) RelationRedundancyNode
 *    reached_count: int, the number of source nodes having reached=true
 *    threshold: float, if reached_count > threshold, the sink nodes become reachable    
 */ 
class RelationGraph extends SimpleGraph
{
	protected $aSourceNodes; // Index of source nodes (for a quicker access)
	protected $aSinkNodes; // Index of sink nodes (for a quicker access)
	protected $aRedundancySettings; // Cache of user settings
	protected $aContextSearches; // Context ("knowing that") stored as a hash array 'class' => DBObjectSearch

	public function __construct()
	{
		parent::__construct();
		$this->aSourceNodes = array();
		$this->aSinkNodes = array();
		$this->aRedundancySettings = array();
		$this->aContextSearches = array();
	}

	/**
	 * Add an object that will be the starting point for building the relations downstream
	 *
	 * @param \DBObject $oObject
	 */
	public function AddSourceObject(DBObject $oObject)
	{
		$oSourceNode = new RelationObjectNode($this, $oObject);
		$oSourceNode->SetProperty('source', true);
		$this->aSourceNodes[$oSourceNode->GetId()] = $oSourceNode;
	}

	/**
	 * Add an object that will be the starting point for building the relations uptream
	 *
	 * @param \DBObject $oObject
	 */
	public function AddSinkObject(DBObject$oObject)
	{
		$oSinkNode = new RelationObjectNode($this, $oObject);
		$oSinkNode->SetProperty('sink', true);
		$this->aSinkNodes[$oSinkNode->GetId()] = $oSinkNode;
	}

	/**
	 * Add a 'context' OQL query, specifying extra objects to be marked as 'is_reached'
	 * even though they are not part of the sources.
	 *
	 * @param string $key
	 * @param string $sOQL The OQL query defining the context objects
	 *
	 * @throws \Exception
	 */
	public function AddContextQuery($key, $sOQL)
	{
		if ($sOQL === '') { return;}
		
		$oSearch = static::MakeSearch($sOQL);
		$aAliases = $oSearch->GetSelectedClasses();
		if (count($aAliases) < 2 )
		{
			IssueLog::Error("Invalid context query '$sOQL'. A context query must contain at least two columns.");
			throw new Exception("Invalid context query '$sOQL'. A context query must contain at least two columns. Columns: ".implode(', ', $aAliases).'. ');
		}
		$aAliasNames = array_keys($aAliases);
		$oCondition = new BinaryExpression(new FieldExpression('id', $aAliasNames[0]), '=', new VariableExpression('id'));
		$oSearch->AddConditionExpression($oCondition);
		
		$sClass = $oSearch->GetClass();
		if (!array_key_exists($sClass, $this->aContextSearches))
		{
			$this->aContextSearches[$sClass] = array();
		}
		$this->aContextSearches[$sClass][] = array('key' => $key, 'search' => $oSearch);
	}

	/**
	 * Determines if the given DBObject is part of a 'context'
	 *
	 * @param DBObject $oObj
	 *
	 * @return boolean
	 * @throws \CoreException
	 */
	public function IsPartOfContext(DBObject $oObj, &$aRootCauses)
	{
		$bRet = false;
		$sFinalClass = get_class($oObj);
		$aParentClasses = MetaModel::EnumParentClasses($sFinalClass, ENUM_PARENT_CLASSES_ALL);
		
		foreach($aParentClasses as $sClass)
		{
			if (array_key_exists($sClass, $this->aContextSearches))
			{
				foreach($this->aContextSearches[$sClass] as $aContextQuery)
				{
					$aAliases = $aContextQuery['search']->GetSelectedClasses();
					$aAliasNames = array_keys($aAliases);
					$sRootCauseAlias = $aAliasNames[1]; // 1st column (=0) = object, second column = root cause
					$oSet = new DBObjectSet($aContextQuery['search'], array(), array('id' => $oObj->GetKey()));
					$oSet->OptimizeColumnLoad(array($aAliasNames[0] => array(), $aAliasNames[1] => array())); // Do not load any column... better do a reload than many joins 
					while($aRow = $oSet->FetchAssoc())
					{
						if (!is_null($aRow[$sRootCauseAlias]))
						{
							if (!array_key_exists($aContextQuery['key'], $aRootCauses))
							{
								$aRootCauses[$aContextQuery['key']] = array();
							}
							$aRootCauses[$aContextQuery['key']][] = $aRow[$sRootCauseAlias];
							$bRet = true;
						}
					}
				}
			}
		}
		return $bRet;
	}

	/**
	 * Build the graph downstream, and mark the nodes that can be reached from the source node
	 *
	 * @param string $sRelCode
	 * @param int $iMaxDepth
	 * @param bool $bEnableRedundancy
	 * @param array $aUnreachableObjects
	 *
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public function ComputeRelatedObjectsDown($sRelCode, $iMaxDepth, $bEnableRedundancy, $aUnreachableObjects = array())
	{
		//echo "<h5>Sources only...</h5>\n".$this->DumpAsHtmlImage()."<br/>\n";
		// Build the graph out of the sources
		foreach ($this->aSourceNodes as $oSourceNode)
		{
			$this->AddRelatedObjects($sRelCode, true, $oSourceNode, $iMaxDepth, $bEnableRedundancy);
			//echo "<h5>After processing of {$oSourceNode->GetId()}</h5>\n".$this->DumpAsHtmlImage()."<br/>\n";
		}
		
		// Mark the unreachable nodes
		foreach ($aUnreachableObjects as $oObj)
		{
			$sNodeId = RelationObjectNode::MakeId($oObj);
			$oNode = $this->GetNode($sNodeId);
			if($oNode)
			{
				$oNode->SetProperty('is_reached_allowed', false);
			}
		}
		
		// Determine the reached nodes
		foreach ($this->aSourceNodes as $oSourceNode)
		{
			$oSourceNode->ReachDown('is_reached', true);
			//echo "<h5>After reaching from {$oSourceNode->GetId()}</h5>\n".$this->DumpAsHtmlImage()."<br/>\n";
		}
		
		// Mark also the "context" nodes as reached and record the "root causes" for each node
		$oIterator = new RelationTypeIterator($this, 'Node');
		foreach($oIterator as $oNode)
		{
			$oObj = $oNode->GetProperty('object');
			$aRootCauses = array();
			if (!is_null($oObj) && $this->IsPartOfContext($oObj, $aRootCauses))
			{
				$oNode->SetProperty('context_root_causes', $aRootCauses);
				$oNode->ReachDown('is_reached', true);
			}	
		}
		if ( MetaModel::GetConfig()->Get('relations.complete_analysis')) {
			$this->ApplyUserRightsOnGraph();
		}
	}

	/**
	 * Build the graph upstream
	 *
	 * @param string $sRelCode
	 * @param int $iMaxDepth
	 * @param bool $bEnableRedundancy
	 *
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public function ComputeRelatedObjectsUp($sRelCode, $iMaxDepth, $bEnableRedundancy)
	{
		//echo "<h5>Sinks only...</h5>\n".$this->DumpAsHtmlImage()."<br/>\n";
		// Build the graph out of the sinks
		foreach ($this->aSinkNodes as $oSinkNode)
		{
			$this->AddRelatedObjects($sRelCode, false, $oSinkNode, $iMaxDepth, $bEnableRedundancy);
			//echo "<h5>After processing of {$oSinkNode->GetId()}</h5>\n".$this->DumpAsHtmlImage()."<br/>\n";
		}

		// Mark also the "context" nodes as reached and record the "root causes" for each node
		$oIterator = new RelationTypeIterator($this, 'Node');
		foreach($oIterator as $oNode)
		{
			$oObj = $oNode->GetProperty('object');
			$aRootCauses = array();
			if (!is_null($oObj) && $this->IsPartOfContext($oObj, $aRootCauses))
			{
				$oNode->SetProperty('context_root_causes', $aRootCauses);
				$oNode->ReachDown('is_reached', true);
			}	
		}
		if ( MetaModel::GetConfig()->Get('relations.complete_analysis')) {
			$this->ApplyUserRightsOnGraph();
		}
	}


	/**
	 * Recursively find related objects, and add them into the graph
	 *
	 * @param string $sRelCode The code of the relation to use for the computation
	 * @param boolean $bDown The direction: downstream or upstream
	 * @param \GraphElement $oObjectNode The node from which to compute the neighbours
	 * @param int $iMaxDepth
	 * @param boolean $bEnableRedundancy
	 *
	 * @throws \Exception
	 */
	protected function AddRelatedObjects($sRelCode, $bDown, $oObjectNode, $iMaxDepth, $bEnableRedundancy)
	{
		if ($iMaxDepth > 0)
		{
			if ($oObjectNode instanceof RelationRedundancyNode)
			{
				// Note: this happens when recursing on an existing part of the graph
				// Skip that redundancy node
				$aRelatedEdges = $bDown ? $oObjectNode->GetOutgoingEdges() : $oObjectNode->GetIncomingEdges();
				foreach ($aRelatedEdges as $oRelatedEdge)
				{
					$oRelatedNode = $bDown ? $oRelatedEdge->GetSinkNode() : $oRelatedEdge->GetSourceNode();
					// Recurse (same depth)
					$this->AddRelatedObjects($sRelCode, $bDown, $oRelatedNode, $iMaxDepth, $bEnableRedundancy);
				}
			}
			elseif ($oObjectNode->GetProperty('developped', false))
			{
				// No need to explore the underlying graph at all. We can stop here since the node has already been developped.
				// Otherwise in case of "loops" in the graph we would recurse up to the max depth limit
				// without producing any difference in the resulting graph... but potentially taking a LOOOONG time.
				return;
				
				// Former code was
				//$aRelatedEdges = $bDown ? $oObjectNode->GetOutgoingEdges() : $oObjectNode->GetIncomingEdges();
				//foreach ($aRelatedEdges as $oRelatedEdge)
				//{
				//	$oRelatedNode = $bDown ? $oRelatedEdge->GetSinkNode() : $oRelatedEdge->GetSourceNode();
				//	// Recurse (decrement the depth)
				//	$this->AddRelatedObjects($sRelCode, $bDown, $oRelatedNode, $iMaxDepth - 1, $bEnableRedundancy);
				//}
			}
			else
			{
				$oObjectNode->SetProperty('developped', true);
	
				$oObject = $oObjectNode->GetProperty('object');
				$iPreviousTimeLimit = ini_get('max_execution_time');
				$iLoopTimeLimit = MetaModel::GetConfig()->Get('max_execution_time_per_loop');
				foreach (MetaModel::EnumRelationQueries(get_class($oObject), $sRelCode, $bDown) as $sDummy => $aQueryInfo)
				{
	 				$sQuery = $bDown ? $aQueryInfo['sQueryDown'] : $aQueryInfo['sQueryUp'];
					try
					{
						$oFlt = static::MakeSearch($sQuery);
						if ( MetaModel::GetConfig()->Get('relations.complete_analysis')) {
							//no filter to find all impacts
							$oFlt->AllowAllData(true);
						}
						$oObjSet = new DBObjectSet($oFlt, array(), $oObject->ToArgsForQuery());
						$oRelatedObj = $oObjSet->Fetch();
					}
					catch (Exception $e)
					{
						$sDirection = $bDown ? 'downstream' : 'upstream';
						throw new Exception("Wrong query ($sDirection) for the relation $sRelCode/{$aQueryInfo['sDefinedInClass']}/{$aQueryInfo['sNeighbour']}: ".$e->getMessage());
					}
					if ($oRelatedObj)
					{
						do
						{
							set_time_limit(intval($iLoopTimeLimit));

								$sObjectRef = RelationObjectNode::MakeId($oRelatedObj);
								$oRelatedNode = $this->GetNode($sObjectRef);
								if (is_null($oRelatedNode)) {
									$oRelatedNode = new RelationObjectNode($this, $oRelatedObj);
								}
								$oSourceNode = $bDown ? $oObjectNode : $oRelatedNode;
								$oSinkNode = $bDown ? $oRelatedNode : $oObjectNode;
							if ($bEnableRedundancy)
							{
									$oRedundancyNode = $this->ComputeRedundancy($sRelCode, $aQueryInfo, $oSourceNode, $oSinkNode);
								} else {
									$oRedundancyNode = null;
								}
								if (!$oRedundancyNode) {
									// Direct link (otherwise handled by ComputeRedundancy)
									new RelationEdge($this, $oSourceNode, $oSinkNode);
								}

							// Recurse
							$this->AddRelatedObjects($sRelCode, $bDown, $oRelatedNode, $iMaxDepth - 1, $bEnableRedundancy);
						}
						while ($oRelatedObj = $oObjSet->Fetch());
					}
				}
				set_time_limit(intval($iPreviousTimeLimit));
			}
		}
	}

	/**
	 * Determine if there is a redundancy (or use the existing one) and add the corresponding nodes/edges
	 *
	 * @param string $sRelCode
	 * @param array $aQueryInfo
	 * @param GraphElement $oFromNode
	 * @param GraphElement $oToNode
	 *
	 * @return \GraphNode|NULL|\RelationRedundancyNode
	 * @throws \Exception
	 */
	protected function ComputeRedundancy($sRelCode, $aQueryInfo, $oFromNode, $oToNode)
	{
		$oRedundancyNode = null;
		$oObject = $oToNode->GetProperty('object');
		if ($this->IsRedundancyEnabled($sRelCode, $aQueryInfo, $oToNode))
		{
			$sUniqueNeighbourId = $aQueryInfo['sDefinedInClass'].'-'.$aQueryInfo['sNeighbour'];
			$sId = RelationRedundancyNode::MakeId($sRelCode, $sUniqueNeighbourId, $oFromNode->GetProperty('object'), $oToNode->GetProperty('object'));

			$oRedundancyNode = $this->GetNode($sId);
			if (is_null($oRedundancyNode))
			{
				// Get the upper neighbours
				$sQuery = $aQueryInfo['sQueryUp'];
				if (!$sQuery)
				{
					throw new Exception("Redundancy cannot be enabled on the relation $sRelCode/{$aQueryInfo['sDefinedInClass']}/{$aQueryInfo['sNeighbour']}: its direction is \"{$aQueryInfo['sDirection']}\"");
				}
				try
				{
					$oFlt = static::MakeSearch($sQuery);
					if ( MetaModel::GetConfig()->Get('relations.complete_analysis')) {
						//no filter to find all impacts
						$oFlt->AllowAllData(true);
					}
					$oObjSet = new DBObjectSet($oFlt, array(), $oObject->ToArgsForQuery());
					$iCount = $oObjSet->Count();
				}
				catch (Exception $e)
				{
					throw new Exception("Wrong query (upstream) for the relation $sRelCode/{$aQueryInfo['sDefinedInClass']}/{$aQueryInfo['sNeighbour']}: ".$e->getMessage());
				}
	
				$iMinUp = $this->GetRedundancyMinUp($sRelCode, $aQueryInfo, $oToNode, $iCount);
				$fThreshold = max(0, $iCount - $iMinUp);
				$oRedundancyNode = new RelationRedundancyNode($this, $sId, $iMinUp, $fThreshold);
				new RelationEdge($this, $oRedundancyNode, $oToNode);
	
				while ($oUpperObj = $oObjSet->Fetch())
				{
						$sObjectRef = RelationObjectNode::MakeId($oUpperObj);
						$oUpperNode = $this->GetNode($sObjectRef);
						if (is_null($oUpperNode)) {
							$oUpperNode = new RelationObjectNode($this, $oUpperObj);
						}
						new RelationEdge($this, $oUpperNode, $oRedundancyNode);
				}
			}
		}
		return $oRedundancyNode;
	}

	/**
	 * Helper to determine the redundancy setting on a given relation
	 *
	 * @param string $sRelCode
	 * @param array $aQueryInfo
	 * @param GraphElement $oToNode
	 *
	 * @return bool
	 */
	protected function IsRedundancyEnabled($sRelCode, $aQueryInfo, $oToNode)
	{
		$bRet = false;
		$oToObject = $oToNode->GetProperty('object');
		$oRedundancyAttDef = $this->FindRedundancyAttribute($sRelCode, $aQueryInfo, get_class($oToObject));
		if ($oRedundancyAttDef)
		{
			$sValue = $oToObject->Get($oRedundancyAttDef->GetCode());
			$bRet = $oRedundancyAttDef->IsEnabled($sValue);
		}
		return $bRet;
	}

	/**
	 * Helper to determine the redundancy threshold, given the count of objects upstream
	 *
	 * @param string $sRelCode
	 * @param array $aQueryInfo
	 * @param GraphElement $oToNode
	 * @param int $iUpstreamObjects
	 *
	 * @return int
	 */
	protected function GetRedundancyMinUp($sRelCode, $aQueryInfo, $oToNode, $iUpstreamObjects)
	{
		$iMinUp = 0;

		$oToObject = $oToNode->GetProperty('object');
		$oRedundancyAttDef = $this->FindRedundancyAttribute($sRelCode, $aQueryInfo, get_class($oToObject));
		if ($oRedundancyAttDef)
		{
			$sValue = $oToObject->Get($oRedundancyAttDef->GetCode());
			if ($oRedundancyAttDef->GetMinUpType($sValue) == 'count')
			{
				$iMinUp = $oRedundancyAttDef->GetMinUpValue($sValue);
			}
			else
			{
				$iMinUp = $iUpstreamObjects * $oRedundancyAttDef->GetMinUpValue($sValue) / 100;
			}
		}
		return $iMinUp;
	}

	/**
	 * Helper to search for the redundancy attribute
	 *
	 * @param string $sRelCode
	 * @param array $aQueryInfo
	 * @param string $sClass
	 *
	 * @return \AttributeDefinition|\AttributeRedundancySettings|null
	 */
	protected function FindRedundancyAttribute($sRelCode, $aQueryInfo, $sClass)
	{
		$oRet = null;
		foreach (MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
		{
			if ($oAttDef instanceof AttributeRedundancySettings)
			{
				if ($oAttDef->Get('relation_code') == $sRelCode)
				{
					if ($oAttDef->Get('from_class') == $aQueryInfo['sFromClass'])
					{
						if ($oAttDef->Get('neighbour_id') == $aQueryInfo['sNeighbour'])
						{
							$oRet = $oAttDef;
							break;
						}
					}
				}
			}
		}
		return $oRet;
	}
	
	/**
	 * Get the objects referenced by the graph as a hash array: 'class' => array of objects
	 * @return array Ambigous <multitype:multitype: , unknown>
	 */
	public function GetObjectsByClass()
	{
		$aResults = array();
		$oIterator = new RelationTypeIterator($this, 'Node');
		foreach($oIterator as $oNode)
		{
			$oObj = $oNode->GetProperty('object'); // Some nodes (Redundancy Nodes and Group) do not contain an object
			if ($oObj)
			{
				$sObjClass  = get_class($oObj);
				if (!array_key_exists($sObjClass, $aResults))
				{
					$aResults[$sObjClass] = array();
				}
				$aResults[$sObjClass][] = $oObj;
			}
		}
		return $aResults;		
	}

	/**
	 * @param string $sOQL
	 *
	 * @return \DBSearch
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	protected static function MakeSearch($sOQL)
	{
		$oSearch = DBSearch::FromOQL($sOQL);
		if (MetaModel::IsObsoletable($oSearch->GetClass()))
		{
			// Exclude obsolete objects anytime
			$oSearch->AddCondition('obsolescence_flag', 0);
		}
		// Exclude archived objects anytime
		$oSearch->SetArchiveMode(false);
		return $oSearch;
	}


	/**
	 * @return void
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \SimpleGraphException
	 */
	private function ApplyUserRightsOnGraph()
	{
		//The chart is complete. Now we need to control which objects are allowed to the current user.
		if (!UserRights::IsAdministrator()) {
			//First we get all the objects presents in chart in $aArrayTest
			$oIterator = new RelationTypeIterator($this, 'Node');
			$aArrayTest = [];
			foreach ($oIterator as $oNode) {
				$oObj = $oNode->GetProperty('object');
				if ($oObj) {
					$aArrayTest[get_class($oObj)][$oObj->GetKey()] = $oObj->GetKey();
				}
			}
			//Then for each class, we made a request to control access rights
			// visible objects are removed from $aArrayTest
			foreach ($aArrayTest as $sClass => $aKeys) {
				$sOQL = "SELECT ".$sClass.' WHERE id IN ('.implode(',', $aKeys).')';
				$oSearch = DBObjectSearch::FromOQL($sOQL);
				$aListId = $oSearch->SelectAttributeToArray('id');
				foreach($aListId as$aItem ) {
					unset($aArrayTest[$sClass][$aItem['id']]);
				}
			}
			//then removes from the graph all objects still present in $aArrayTest
			foreach ($oIterator as $oNode) {
				$oObj = $oNode->GetProperty('object');
				if ($oObj && isset($aArrayTest[get_class($oObj)]) && in_array($oObj->GetKey(), $aArrayTest[get_class($oObj)])) {
					$this->FilterNode($oNode);
				}
			}
		}
	}

}
