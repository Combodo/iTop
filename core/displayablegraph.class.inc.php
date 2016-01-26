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
 * Special kind of Graph for producing some nice output
 *
 * @copyright   Copyright (C) 2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class DisplayableNode extends GraphNode
{
	public $x;
	public $y;
	
	/**
	 * Create a new node inside a graph
	 * @param SimpleGraph $oGraph
	 * @param string $sId The unique identifier of this node inside the graph
	 * @param number $x Horizontal position
	 * @param number $y Vertical position
	 */
	public function __construct(SimpleGraph $oGraph, $sId, $x = null, $y = null)
	{
		parent::__construct($oGraph, $sId);
		$this->x = $x;
		$this->y = $y;
		$this->bFiltered = false;
	}

	public function GetIconURL()
	{
		return $this->GetProperty('icon_url', '');
	}
	
	public function GetLabel()
	{
		return $this->GetProperty('label', $this->sId);
	}
	
	public function GetWidth()
	{
		return max(32, 5*strlen($this->GetProperty('label'))); // approximation of the text's bounding box
	}
	
	public function GetHeight()
	{
		return 32;
	}
	
	public function Distance2(DisplayableNode $oNode)
	{
		$dx = $this->x - $oNode->x;
		$dy = $this->y - $oNode->y;
		
		$d2 = $dx*$dx + $dy*$dy - $this->GetHeight()*$this->GetHeight();
		if ($d2 < 40)
		{
			$d2 = 40;
		}
		return $d2;
	}
	
	public function Distance(DisplayableNode $oNode)
	{
		return sqrt($this->Distance2($oNode));
	}
	
	public function GetForRaphael($aContextDefs)
	{
		$aNode = array();
		$aNode['shape'] = 'icon';
		$aNode['icon_url'] = $this->GetIconURL();
		$aNode['width'] = 32;
		$aNode['source'] = ($this->GetProperty('source') == true);
		$aNode['obj_class'] = get_class($this->GetProperty('object'));
		$aNode['obj_key'] = $this->GetProperty('object')->GetKey();
		$aNode['sink'] = ($this->GetProperty('sink') == true);
		$aNode['x'] = $this->x;
		$aNode['y']= $this->y;
		$aNode['label'] = $this->GetLabel();
		$aNode['id'] = $this->GetId();
		$fOpacity = ($this->GetProperty('is_reached') ? 1 : 0.4);
		$aNode['icon_attr'] = array('opacity' => $fOpacity);		
		$aNode['text_attr'] = array('opacity' => $fOpacity);
		$aNode['tooltip'] = $this->GetTooltip($aContextDefs);
		$aNode['context_icons'] = array();
		$aContextRootCauses = $this->GetProperty('context_root_causes');
		if (!is_null($aContextRootCauses))
		{
			foreach($aContextRootCauses as $key => $aObjects)
			{
				$aNode['context_icons'][] = utils::GetAbsoluteUrlModulesRoot().$aContextDefs[$key]['icon'];
			}
		}
		return $aNode;
	}
	
	public function RenderAsPDF(TCPDF $oPdf, DisplayableGraph $oGraph, $fScale, $aContextDefs)
	{
		$Alpha = 1.0;
		$oPdf->SetFillColor(200, 200, 200);
		$oPdf->setAlpha(1);
		
		$sIconUrl = $this->GetProperty('icon_url');
		$sIconPath = str_replace(utils::GetAbsoluteUrlModulesRoot(), APPROOT.'env-'.utils::GetCurrentEnvironment().'/', $sIconUrl);
		
		if ($this->GetProperty('source'))
		{
			$oPdf->SetLineStyle(array('width' => 2*$fScale, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(204, 51, 51)));
			$oPdf->Circle($this->x * $fScale, $this->y * $fScale, 16 * 1.25 * $fScale, 0, 360, 'D');
		}
		else if ($this->GetProperty('sink'))
		{
			$oPdf->SetLineStyle(array('width' => 2*$fScale, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(51, 51, 204)));
			$oPdf->Circle($this->x * $fScale, $this->y * $fScale, 16 * 1.25 * $fScale, 0, 360, 'D');
		}
		
		if (!$this->GetProperty('is_reached'))
		{
			$sTempImageName = $this->CreateWhiteIcon($oGraph, $sIconPath);
			if ($sTempImageName != null)
			{
				$oPdf->Image($sTempImageName, ($this->x - 16)*$fScale, ($this->y - 16)*$fScale, 32*$fScale, 32*$fScale, 'PNG');
			}
			$Alpha = 0.4;
			$oPdf->setAlpha($Alpha);
		}
		
		$oPdf->Image($sIconPath, ($this->x - 16)*$fScale, ($this->y - 16)*$fScale, 32*$fScale, 32*$fScale);
		
		$aContextRootCauses = $this->GetProperty('context_root_causes');
		if (!is_null($aContextRootCauses))
		{
			$idx = 0;
			foreach($aContextRootCauses as $key => $aObjects)
			{
				$sgn = 2*($idx %2) -1;
				$coef = floor((1+$idx)/2) * $sgn;
				$alpha = $coef*pi()/4 - pi()/2;						
				$x = $this->x * $fScale + cos($alpha) * 16*1.25 * $fScale;
				$y = $this->y * $fScale + sin($alpha) * 16*1.25 * $fScale;
				$l = 32 * $fScale / 3;
				$sIconPath = APPROOT.'env-'.utils::GetCurrentEnvironment().'/'.$aContextDefs[$key]['icon'];
				$oPdf->Image($sIconPath, $x - $l/2, $y - $l/2, $l, $l);
				$idx++;
			}
		}
				
		$oPdf->SetFont('dejavusans', '', 24 * $fScale, '', true);
		$width = $oPdf->GetStringWidth($this->GetProperty('label'));
		$height = $oPdf->GetStringHeight(1000, $this->GetProperty('label'));
		$oPdf->setAlpha(0.6 * $Alpha);
		$oPdf->SetFillColor(255, 255, 255);
		$oPdf->SetDrawColor(255, 255, 255);
		$oPdf->Rect($this->x*$fScale - $width/2, ($this->y + 18)*$fScale, $width, $height, 'DF');
		$oPdf->setAlpha($Alpha);
		$oPdf->SetTextColor(0, 0, 0);
		$oPdf->Text($this->x*$fScale - $width/2, ($this->y + 18)*$fScale, $this->GetProperty('label'));
	}
	
	/**
	 * Create a "whitened" version of the icon (retaining the transparency) to be used a background for masking the underlying lines
	 * @param string $sIconFile The path to the file containing the icon
	 * @return NULL|string The path to a temporary file containing the white version of the icon
	 */
	protected function CreateWhiteIcon(DisplayableGraph $oGraph, $sIconFile)
	{
		$aInfo = getimagesize($sIconFile);
		
		$im = null;
		switch($aInfo['mime'])
		{
			case 'image/png':
			if (function_exists('imagecreatefrompng'))
			{
				$im = imagecreatefrompng($sIconFile);
			}
			break;
			
			case 'image/gif':
			if (function_exists('imagecreatefromgif'))
			{
				$im = imagecreatefromgif($sIconFile);
			}
			break;
			
			case 'image/jpeg':
			case 'image/jpg':
			if (function_exists('imagecreatefromjpeg'))
			{
				$im = imagecreatefromjpeg($sIconFile);
			}
			break;
			
			default:
			return null;
			
		}
		if($im && imagefilter($im, IMG_FILTER_COLORIZE, 255, 255, 255))
		{
			$sTempImageName = $oGraph->GetTempImageName();
			imagesavealpha($im, true);
			imagepng($im, $sTempImageName);
			imagedestroy($im);
			return $sTempImageName;
		}
		else
		{
			return null;
		}
	}
	
	public function GetObjectCount()
	{
		return 1;
	}
	
	public function GetObjectClass()
	{
		return is_object($this->GetProperty('object', null)) ? get_class($this->GetProperty('object', null)) : null;
	}
	
	protected function AddToStats($oNode, &$aNodesPerClass)
	{
		$sClass = $oNode->GetObjectClass();
		if (!array_key_exists($sClass, $aNodesPerClass))
		{
			$aNodesPerClass[$sClass] = array(
				'reached' => array(
					'count' => 0,
					'nodes' => array(),
					'icon_url' => $oNode->GetProperty('icon_url'),
				),
				'not_reached' => array(
					'count' => 0,
					'nodes' => array(),
					'icon_url' => $oNode->GetProperty('icon_url'),
				)
			);
		}
		$sKey = $oNode->GetProperty('is_reached') ? 'reached' : 'not_reached';
		if (!array_key_exists($oNode->GetId(), $aNodesPerClass[$sClass][$sKey]['nodes']))
		{
			$aNodesPerClass[$sClass][$sKey]['nodes'][$oNode->GetId()] = $oNode;
			$aNodesPerClass[$sClass][$sKey]['count'] += $oNode->GetObjectCount();
		}		
	}
	
	/**
	 * Retrieves the list of neighbour nodes, in the given direction: 'up' or 'down'
	 * @param bool $bDirectionDown
	 * @return multitype:NULL
	 */
	protected function GetNextNodes($bDirectionDown = true)
	{
		$aNextNodes = array();
		if ($bDirectionDown)
		{
			foreach($this->GetOutgoingEdges() as $oEdge)
			{
				$aNextNodes[] = $oEdge->GetSinkNode();
			}
		}
		else
		{
			foreach($this->GetIncomingEdges() as $oEdge)
			{
				$aNextNodes[] = $oEdge->GetSourceNode();
			}	
		}
		return $aNextNodes;
	}
	
	/**
	 * Replaces the next neighbour node (in the given direction: 'up' or 'down') by the supplied group node
	 * preserving the connectivity of the graph
	 * @param DisplayableGraph $oGraph
	 * @param DisplayableNode $oNextNode
	 * @param DisplayableGroupNode $oNewNode
	 * @param bool $bDirectionDown
	 */
	protected function ReplaceNextNodeBy(DisplayableGraph $oGraph, DisplayableNode $oNextNode, DisplayableGroupNode $oNewNode, $bDirectionDown = true)
	{
		$sClass = $oNewNode->GetProperty('class');
		if ($bDirectionDown)
		{
			foreach($oNextNode->GetIncomingEdges() as $oEdge)
			{
				if ($oEdge->GetSourceNode()->GetId() !== $this->GetId())
				{
					try
					{
						$oNewEdge = new DisplayableEdge($oGraph, $oEdge->GetId().'::'.$sClass, $oEdge->GetSourceNode(), $oNewNode);
					}
					catch(Exception $e)
					{
						// ignore this edge
					}
				}
			}
			foreach($oNextNode->GetOutgoingEdges() as $oEdge)
			{
				try
				{
					$oNewEdge = new DisplayableEdge($oGraph, $oEdge->GetId().'::'.$sClass, $oNewNode, $oEdge->GetSinkNode());
				}
				catch(Exception $e)
				{
					// ignore this edge
				}
			}
		}
		else
		{
			foreach($oNextNode->GetOutgoingEdges() as $oEdge)
			{
				if ($oEdge->GetSinkNode()->GetId() !== $this->GetId())
				{
					try
					{
						$oNewEdge = new DisplayableEdge($oGraph, $oEdge->GetId().'::'.$sClass, $oNewNode, $oEdge->GetSinkNode());
					}
					catch(Exception $e)
					{
						// ignore this edge
					}
				}
			}
			foreach($oNextNode->GetIncomingEdges() as $oEdge)
			{
				try
				{
					$oNewEdge = new DisplayableEdge($oGraph, $oEdge->GetId().'::'.$sClass, $oEdge->GetSourceNode(), $oNewNode);
				}
				catch(Exception $e)
				{
					// ignore this edge
				}
			}
		}
		
		if ($oGraph->GetNode($oNextNode->GetId()))
		{
			$oGraph->_RemoveNode($oNextNode);
			if ($oNextNode instanceof DisplayableGroupNode)
			{
				// Copy all the objects of the previous group into the new group
				foreach($oNextNode->GetObjects() as $oObj)
				{
					$oNewNode->AddObject($oObj);
				}
			}
			else
			{
				$oNewNode->AddObject($oNextNode->GetProperty('object'));
			}
		}			
	}
	
	/**
	 * Group together (as a special kind of nodes) all the similar neighbours of the current node
	 * @param DisplayableGraph $oGraph
	 * @param int $iThresholdCount
	 * @param boolean $bDirectionUp
	 * @param boolean $bDirectionDown
	 */
	public function GroupSimilarNeighbours(DisplayableGraph $oGraph, $iThresholdCount, $bDirectionUp = false, $bDirectionDown = true)
	{
		if ($this->GetProperty('grouped') === true) return;
		$this->SetProperty('grouped', true);
		
		$aNodesPerClass = array();
		foreach($this->GetNextNodes($bDirectionDown) as $oNode)
		{
			$sClass = $oNode->GetObjectClass();
			if ($sClass !== null)
			{
				$this->AddToStats($oNode, $aNodesPerClass);
			}
			else
			{
				$oNode->GroupSimilarNeighbours($oGraph, $iThresholdCount, $bDirectionUp, $bDirectionDown);
			}
		}
		foreach($aNodesPerClass as $sClass => $aDefs)
		{
			foreach($aDefs as $sStatus => $aGroupProps)
			{
				if (count($aGroupProps['nodes']) >= $iThresholdCount)
				{
					$sNewId = $this->GetId().'::'.$sClass.'/'.(($sStatus == 'reached') ? '_reached': '');
					$oNewNode = $oGraph->GetNode($sNewId);
					if ($oNewNode == null)
					{
						$oNewNode = new DisplayableGroupNode($oGraph, $sNewId);
						$oNewNode->SetProperty('label', 'x'.$aGroupProps['count']);
						$oNewNode->SetProperty('icon_url', $aGroupProps['icon_url']);
						$oNewNode->SetProperty('class', $sClass);
						$oNewNode->SetProperty('is_reached', ($sStatus == 'reached'));
						$oNewNode->SetProperty('count', $aGroupProps['count']);
					}
					
					try
					{
						if ($bDirectionDown)
						{
							$oIncomingEdge = new DisplayableEdge($oGraph, $this->GetId().'-'.$oNewNode->GetId(), $this, $oNewNode);
						}
						else
						{
							$oOutgoingEdge = new DisplayableEdge($oGraph, $this->GetId().'-'.$oNewNode->GetId(), $oNewNode, $this);
						}
					}
					catch(Exception $e)
					{
						// Ignore this redundant egde
					}				
					
					foreach($aGroupProps['nodes'] as $oNextNode)
					{
						$this->ReplaceNextNodeBy($oGraph, $oNextNode, $oNewNode, $bDirectionDown);
					}
					$oNewNode->GroupSimilarNeighbours($oGraph, $iThresholdCount, $bDirectionUp, $bDirectionDown);
				}
				else
				{
					foreach($aGroupProps['nodes'] as $oNode)
					{
						$oNode->GroupSimilarNeighbours($oGraph, $iThresholdCount, $bDirectionUp, $bDirectionDown);
					}
				}
			}
		}
	}
	
	public function GetTooltip($aContextDefs)
	{
		$sHtml = '';
		$oCurrObj = $this->GetProperty('object');
		$sSubClass = get_class($oCurrObj);
		$sHtml .= $oCurrObj->GetHyperlink()."<hr/>";
		$aContextRootCauses = $this->GetProperty('context_root_causes');
		if (!is_null($aContextRootCauses))
		{
			foreach($aContextRootCauses as $key => $aObjects)
			{
				$aContext = $aContextDefs[$key];
				$aRootCauses = array();
				foreach($aObjects as $oRootCause)
				{
					$aRootCauses[] = $oRootCause->GetHyperlink();
				}
				$sHtml .= '<p><img style="max-height: 24px; vertical-align:bottom;" src="'.utils::GetAbsoluteUrlModulesRoot().$aContext['icon'].'" title="'.htmlentities(Dict::S($aContext['dict'])).'">&nbsp;'.implode(', ', $aRootCauses).'</p>';
			}
			$sHtml .= '<hr/>';
		}
		$sHtml .= '<table><tbody>';
		foreach(MetaModel::GetZListItems($sSubClass, 'list') as $sAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef($sSubClass, $sAttCode);
			$sHtml .= '<tr><td>'.$oAttDef->GetLabel().':&nbsp;</td><td>'.$oCurrObj->GetAsHtml($sAttCode).'</td></tr>';
		}
		$sHtml .= '</tbody></table>';
		return $sHtml;		
	}
	
	/**
	 * Get the description of the node in "dot" language
	 * Used to generate the positions in the graph, but we'd better use fake label
	 * just to retain the space used by the node, without compromising the parsing
	 * of the result which may occur when using the real labels (with possible weird characters in the middle)
	 */
	public function GetDotAttributes($bNoLabel = false)
	{
		$sDot = '';
		if ($bNoLabel)
		{
			// simulate a fake label with the approximate same size as the true label
			$sLabel = str_repeat('x',strlen($this->GetProperty('label', $this->GetId())));
			$sDot = 'label="'.$sLabel.'"';
		}
		else
		{
			// actual label
			$sLabel = addslashes($this->GetProperty('label', $this->GetId()));
			$sDot = 'label="'.$sLabel.'"';
		}
		return $sDot;
	}
}

class DisplayableRedundancyNode extends DisplayableNode
{
	public function GetWidth()
	{
		return 24;
	}
	
	public function GetForRaphael($aContextDefs)
	{
		$aNode = array();
		$aNode['shape'] = 'disc';
		$aNode['icon_url'] = $this->GetIconURL();
		$aNode['source'] = ($this->GetProperty('source') == true);
		$aNode['width'] = $this->GetWidth();
		$aNode['x'] = $this->x;
		$aNode['y']= $this->y;
		$aNode['label'] = $this->GetLabel();
		$aNode['id'] = $this->GetId();	
		$fDiscOpacity = ($this->GetProperty('is_reached') ? 1 : 0.2);
		$sColor = ($this->GetProperty('is_reached_count') > $this->GetProperty('threshold')) ? '#c33' : '#999';
		$aNode['disc_attr'] = array('stroke-width' => 2, 'stroke' => '#000', 'fill' => $sColor, 'opacity' => $fDiscOpacity);
		$fTextOpacity = ($this->GetProperty('is_reached') ? 1 : 0.4);
		$aNode['text_attr'] = array('fill' => '#fff', 'opacity' => $fTextOpacity);
		$aNode['tooltip'] = $this->GetTooltip($aContextDefs);
		return $aNode;
	}

	public function RenderAsPDF(TCPDF $oPdf, DisplayableGraph $oGraph, $fScale, $aContextDefs)
	{
		$oPdf->SetAlpha(1);
		if($this->GetProperty('is_reached_count') > $this->GetProperty('threshold'))
		{
			$oPdf->SetFillColor(200, 0, 0);
		}
		else
		{
			$oPdf->SetFillColor(144, 144, 144);
		}
		$oPdf->SetDrawColor(0, 0, 0);
		$oPdf->Circle($this->x*$fScale, $this->y*$fScale, 16*$fScale, 0, 360, 'DF');

		$oPdf->SetTextColor(255, 255, 255);
		$oPdf->SetFont('dejavusans', '', 28 * $fScale, '', true);
		$sLabel  = (string)$this->GetProperty('label');
		$width = $oPdf->GetStringWidth($sLabel, 'dejavusans', 'B', 24*$fScale);
		$height = $oPdf->GetStringHeight(1000, $sLabel);
		$xPos = (float)$this->x*$fScale - $width/2;
		$yPos = (float)$this->y*$fScale - $height/2;
		
		$oPdf->SetXY(($this->x - 16)*$fScale, ($this->y - 16)*$fScale);
		
		$oPdf->Cell(32*$fScale, 32*$fScale, $sLabel, 0, 0, 'C', 0, '', 0, false, 'T', 'C');
	}
	
	/**
	 * @see DisplayableNode::GroupSimilarNeighbours()
	 */
	public function GroupSimilarNeighbours(DisplayableGraph $oGraph, $iThresholdCount, $bDirectionUp = false, $bDirectionDown = true)
	{
		parent::GroupSimilarNeighbours($oGraph, $iThresholdCount, $bDirectionUp, $bDirectionDown);
				
		if ($bDirectionUp)
		{
			$aNodesPerClass = array();
			foreach($this->GetIncomingEdges() as $oEdge)
			{
				$oNode = $oEdge->GetSourceNode();
				
				if (($oNode->GetObjectClass() !== null) && (!$oNode->GetProperty('is_reached')))
				{			
					$this->AddToStats($oNode, $aNodesPerClass);
				}
				else
				{
					//$oNode->GroupSimilarNeighbours($oGraph, $iThresholdCount, $bDirectionUp, $bDirectionDown);
				}
			}		
			foreach($aNodesPerClass as $sClass => $aDefs)
			{
				foreach($aDefs as $sStatus => $aGroupProps)
				{
					if (count($aGroupProps['nodes']) >= $iThresholdCount)
					{
						$oNewNode = new DisplayableGroupNode($oGraph, '-'.$this->GetId().'::'.$sClass.'/'.$sStatus);
						$oNewNode->SetProperty('label', 'x'.count($aGroupProps['nodes']));
						$oNewNode->SetProperty('icon_url', $aGroupProps['icon_url']);
						$oNewNode->SetProperty('is_reached', ($sStatus == 'is_reached'));
						$oNewNode->SetProperty('class', $sClass);
						$oNewNode->SetProperty('count', count($aGroupProps['nodes']));
													
						
						$sNewId = $this->GetId().'::'.$sClass.'/'.(($sStatus == 'reached') ? '_reached': '');
						$oNewNode = $oGraph->GetNode($sNewId);
						if ($oNewNode == null)
						{
							$oNewNode = new DisplayableGroupNode($oGraph, $sNewId);
							$oNewNode->SetProperty('label', 'x'.$aGroupProps['count']);
							$oNewNode->SetProperty('icon_url', $aGroupProps['icon_url']);
							$oNewNode->SetProperty('class', $sClass);
							$oNewNode->SetProperty('is_reached', ($sStatus == 'reached'));
							$oNewNode->SetProperty('count', $aGroupProps['count']);
						}
							
						try
						{
							$oOutgoingEdge = new DisplayableEdge($oGraph, '-'.$this->GetId().'-'.$oNewNode->GetId().'/'.$sStatus, $oNewNode, $this);
						}
						catch(Exception $e)
						{
							// Ignore this redundant egde
						}
							
						foreach($aGroupProps['nodes'] as $oNextNode)
						{
							$this->ReplaceNextNodeBy($oGraph, $oNextNode, $oNewNode, !$bDirectionUp);
						}
						//$oNewNode->GroupSimilarNeighbours($oGraph, $iThresholdCount, $bDirectionUp, $bDirectionDown);
					}
					else
					{
						foreach($aGroupProps['nodes'] as $oNode)
						{
							//$oNode->GroupSimilarNeighbours($oGraph, $iThresholdCount, $bDirectionUp, $bDirectionDown);
						}
					}
				}
			}
		}
	}
	
	public function GetTooltip($aContextDefs)
	{
		$sHtml = '';
		$sHtml .= Dict::S('UI:RelationTooltip:Redundancy')."<hr>";
		$sHtml .= '<table><tbody>';
		$sHtml .= "<tr><td>".Dict::Format('UI:RelationTooltip:ImpactedItems_N_of_M' , $this->GetProperty('is_reached_count'), $this->GetProperty('min_up') + $this->GetProperty('threshold'))."</td></tr>";
		$sHtml .= "<tr><td>".Dict::Format('UI:RelationTooltip:CriticalThreshold_N_of_M' , $this->GetProperty('threshold'), $this->GetProperty('min_up') + $this->GetProperty('threshold'))."</td></tr>";
		$sHtml .= '</tbody></table>';
		return $sHtml;		
	}
	

	public function GetObjectCount()
	{
		return 0;
	}

	public function GetObjectClass()
	{
		return null;
	}
}

class DisplayableEdge extends GraphEdge
{
	public function RenderAsPDF(TCPDF $oPdf, DisplayableGraph $oGraph, $fScale, $aContextDefs)
	{
		$oSourceNode = $this->GetSourceNode();
		if (($oSourceNode->x == null) || ($oSourceNode->y == null))
		{
			return;
		}
		$xStart = $oSourceNode->x * $fScale;
		$yStart = $oSourceNode->y * $fScale;
		
		$oSinkNode = $this->GetSinkNode();
		if (($oSinkNode->x == null) || ($oSinkNode->y == null))
		{
			return;
		}
		$xEnd = $oSinkNode->x * $fScale;
		$yEnd = $oSinkNode->y * $fScale;
		
		$bReached = ($this->GetSourceNode()->GetProperty('is_reached') && $this->GetSinkNode()->GetProperty('is_reached'));
		
		$oPdf->setAlpha(1);
		if ($bReached)
		{
			$aColor = array(100, 100, 100);
		}
		else
		{
			$aColor = array(200, 200, 200);
		}
		$oPdf->SetLineStyle(array('width' => 2*$fScale, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => $aColor));
		$oPdf->Line($xStart, $yStart, $xEnd, $yEnd);
		
		
		$vx = $xEnd - $xStart;
		$vy = $yEnd - $yStart;
		$l = sqrt($vx*$vx + $vy*$vy);
		$vx = $vx / $l;
		$vy = $vy / $l;
		$ux = -$vy;
		$uy = $vx;
		$lPos = max($l/2, $l - 40*$fScale);
		$iArrowSize = 5*$fScale;
		
		$x = $xStart  + $lPos * $vx;
		$y = $yStart + $lPos * $vy;
		$oPdf->Line($x, $y, $x + $iArrowSize * ($ux-$vx), $y + $iArrowSize * ($uy-$vy));
		$oPdf->Line($x, $y, $x - $iArrowSize * ($ux+$vx), $y - $iArrowSize * ($uy+$vy));		
	}
}

class DisplayableGroupNode extends DisplayableNode
{
	protected $aObjects;
	
	public function __construct(SimpleGraph $oGraph, $sId, $x = 0, $y = 0)
	{
		parent::__construct($oGraph, $sId, $x, $y);
		$this->aObjects = array();
	}
	
	public function AddObject(DBObject $oObj = null)
	{
		if (is_object($oObj))
		{
			$sPrevClass = $this->GetObjectClass();
			if (($sPrevClass !== null) && (get_class($oObj) !== $sPrevClass))
			{
				throw new Exception("Error: adding an object of class '".get_class($oObj)."' to a group of '$sPrevClass' objects.");
			}
			$this->aObjects[$oObj->GetKey()] = $oObj;
		}
	}
	
	public function GetObjects()
	{
		return $this->aObjects;
	}
	
	public function GetWidth()
	{
		return 50;
	}

	public function GetForRaphael($aContextDefs)
	{
		$aNode = array();
		$aNode['shape'] = 'group';
		$aNode['icon_url'] = $this->GetIconURL();
		$aNode['source'] = ($this->GetProperty('source') == true);
		$aNode['width'] = $this->GetWidth();
		$aNode['x'] = $this->x;
		$aNode['y']= $this->y;
		$aNode['label'] = $this->GetLabel();
		$aNode['id'] = $this->GetId();
		$aNode['group_index'] = $this->GetProperty('group_index'); // if supplied
		$fDiscOpacity = ($this->GetProperty('is_reached') ? 1 : 0.2);
		$fTextOpacity = ($this->GetProperty('is_reached') ? 1 : 0.4);
		$aNode['icon_attr'] = array('opacity' => $fTextOpacity);
		$aNode['disc_attr'] = array('stroke-width' => 2, 'stroke' => '#000', 'fill' => '#fff', 'opacity' => $fDiscOpacity);
		$aNode['text_attr'] = array('fill' => '#000', 'opacity' => $fTextOpacity);
		$aNode['tooltip'] = $this->GetTooltip($aContextDefs);
		return $aNode;
	}
	
	public function RenderAsPDF(TCPDF $oPdf, DisplayableGraph $oGraph, $fScale, $aContextDefs)
	{
		$bReached = $this->GetProperty('is_reached');
		$oPdf->SetFillColor(255, 255, 255);
		if ($bReached)
		{
			$aBorderColor = array(100, 100, 100);
		}
		else
		{
			$aBorderColor = array(200, 200, 200);
		}
		$oPdf->SetLineStyle(array('width' => 2*$fScale, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => $aBorderColor));
		
		$sIconUrl = $this->GetProperty('icon_url');
		$sIconPath = str_replace(utils::GetAbsoluteUrlModulesRoot(), APPROOT.'env-'.utils::GetCurrentEnvironment().'/', $sIconUrl);
		$oPdf->SetAlpha(1);
		$oPdf->Circle($this->x*$fScale, $this->y*$fScale, $this->GetWidth() / 2 * $fScale, 0, 360, 'DF');
		
		if ($bReached)
		{
			$oPdf->SetAlpha(1);
		}
		else
		{
			$oPdf->SetAlpha(0.4);
		}
		$oPdf->Image($sIconPath, ($this->x - 17)*$fScale, ($this->y - 17)*$fScale, 16*$fScale, 16*$fScale);
		$oPdf->Image($sIconPath, ($this->x + 1)*$fScale, ($this->y - 17)*$fScale, 16*$fScale, 16*$fScale);
		$oPdf->Image($sIconPath, ($this->x -8)*$fScale, ($this->y +1)*$fScale, 16*$fScale, 16*$fScale);
		$oPdf->SetFont('dejavusans', '', 24 * $fScale, '', true);
		$width = $oPdf->GetStringWidth($this->GetProperty('label'));
		$oPdf->SetTextColor(0, 0, 0);
		$oPdf->Text($this->x*$fScale - $width/2, ($this->y + 25)*$fScale, $this->GetProperty('label'));
	}
	
	public function GetTooltip($aContextDefs)
	{
		$sHtml = '';
		$iGroupIdx = $this->GetProperty('group_index');
		$sHtml .= '<a href="#" onclick="$(\'.itop-simple-graph\').simple_graph(\'show_group\', \'relation_group_'.$iGroupIdx.'\');">'.Dict::Format('UI:RelationGroupNumber_N', (1+$iGroupIdx))."</a>";
		$sHtml .= '<hr/>';
		$sHtml .= '<table><tbody><tr>';
		$sHtml .= '<td style="vertical-align:top;padding-right: 0.5em;"><img src="'.$this->GetProperty('icon_url').'"></td><td style="vertical-align:top">'.MetaModel::GetName($this->GetObjectClass()).'<br/>';
		$sHtml .= Dict::Format('UI_CountOfObjectsShort', $this->GetObjectCount()).'</td>';
		$sHtml .= '</tr></tbody></table>';
		return $sHtml;
	}

	public function GetObjectCount()
	{
		return count($this->aObjects);
	}

	public function GetObjectClass()
	{
		return ($this->GetObjectCount() > 0) ? get_class(reset($this->aObjects)) : null;
	}
}

/**
 * A Graph that can be displayed interactively using Raphael JS or saved as a PDF document
 */
class DisplayableGraph extends SimpleGraph
{
	protected $bDirectionDown;
	protected $aTempImages;
	protected $aSourceObjects;
	protected $aSinkObjects;
	
	public function __construct()
	{
		parent::__construct();
		$this->aTempImages = array();
		$this->aSourceObjects = array();
		$this->aSinkObjects = array();
	}
	
	public function GetTempImageName()
	{
		$sNewTempName = tempnam(APPROOT.'data', 'img-');
		$this->aTempImages[] = $sNewTempName;
		return $sNewTempName;
	}
	
	public function __destruct()
	{
		foreach($this->aTempImages as $sTempFile)
		{
			@unlink($sTempFile);
		}
	}
	
	/**
	 * Build a DisplayableGraph from a RelationGraph
	 * @param RelationGraph $oGraph
	 * @param number $iGroupingThreshold
	 * @param string $bDirectionDown
	 * @return DisplayableGraph
	 */
	public static function FromRelationGraph(RelationGraph $oGraph, $iGroupingThreshold = 20, $bDirectionDown = true)
	{
		$oNewGraph = new DisplayableGraph();
		$oNewGraph->bDirectionDown = $bDirectionDown;
		$iPreviousTimeLimit = ini_get('max_execution_time');
		$iLoopTimeLimit = MetaModel::GetConfig()->Get('max_execution_time_per_loop');
		
		$oNodesIter = new RelationTypeIterator($oGraph, 'Node');
		foreach($oNodesIter as $oNode)
		{
			set_time_limit($iLoopTimeLimit);
			switch(get_class($oNode))
			{
				case 'RelationObjectNode':				
				$oNewNode = new DisplayableNode($oNewGraph, $oNode->GetId(), 0, 0);
				
				$oObj = $oNode->GetProperty('object');
				$sClass = get_class($oObj);
				if ($oNode->GetProperty('source'))
				{
					if (!array_key_exists($sClass, $oNewGraph->aSourceObjects))
					{
						$oNewGraph->aSourceObjects[$sClass] = array();
					}
					$oNewGraph->aSourceObjects[$sClass][] = $oObj->GetKey();
					$oNewNode->SetProperty('source', true);
				}
				if ($oNode->GetProperty('sink'))
				{
					if (!array_key_exists($sClass, $oNewGraph->aSinkObjects))
					{
						$oNewGraph->aSinkObjects[$sClass] = array();
					}
					$oNewGraph->aSinkObjects[$sClass][] = $oObj->GetKey();
					$oNewNode->SetProperty('sink', true);
				}
				$oNewNode->SetProperty('object', $oObj);
				$oNewNode->SetProperty('icon_url', $oObj->GetIcon(false));
				$oNewNode->SetProperty('label', $oObj->GetRawName());
				$oNewNode->SetProperty('is_reached', $bDirectionDown ? $oNode->GetProperty('is_reached') : true); // When going "up" is_reached does not matter
				$oNewNode->SetProperty('is_reached_allowed', $oNode->GetProperty('is_reached_allowed'));
				$oNewNode->SetProperty('context_root_causes', $oNode->GetProperty('context_root_causes'));
				break;
				
				default:
				$oNewNode = new DisplayableRedundancyNode($oNewGraph, $oNode->GetId(), 0, 0);
				$iNbReached = (is_null($oNode->GetProperty('is_reached_count'))) ? 0 : $oNode->GetProperty('is_reached_count');
				$oNewNode->SetProperty('label', $iNbReached."/".($oNode->GetProperty('min_up') + $oNode->GetProperty('threshold')));
				$oNewNode->SetProperty('min_up', $oNode->GetProperty('min_up'));
				$oNewNode->SetProperty('threshold', $oNode->GetProperty('threshold'));
				$oNewNode->SetProperty('is_reached_count', $iNbReached);
				$oNewNode->SetProperty('is_reached', true);
			}
		}
		$oEdgesIter = new RelationTypeIterator($oGraph, 'Edge');
		foreach($oEdgesIter as $oEdge)
		{
			set_time_limit($iLoopTimeLimit);
			$oSourceNode = $oNewGraph->GetNode($oEdge->GetSourceNode()->GetId());
			$oSinkNode = $oNewGraph->GetNode($oEdge->GetSinkNode()->GetId());
			$oNewEdge = new DisplayableEdge($oNewGraph, $oEdge->GetId(), $oSourceNode, $oSinkNode);
		}
		
		// Remove duplicate edges between two nodes
		$oEdgesIter = new RelationTypeIterator($oNewGraph, 'Edge');
		$aEdgeKeys = array();
		foreach($oEdgesIter as $oEdge)
		{
			set_time_limit($iLoopTimeLimit);
			$sSourceId =  $oEdge->GetSourceNode()->GetId();
			$sSinkId = $oEdge->GetSinkNode()->GetId();
			if ($sSourceId == $sSinkId)
			{
				// Remove self referring edges
				$oNewGraph->_RemoveEdge($oEdge);
			}
			else
			{
				$sKey = $sSourceId.'//'.$sSinkId;
				if (array_key_exists($sKey, $aEdgeKeys))
				{
					// Remove duplicate edges
					$oNewGraph->_RemoveEdge($oEdge);
				}
				else
				{
					$aEdgeKeys[$sKey] = true;
				}
			}
		}
		
		$oNodesIter = new RelationTypeIterator($oNewGraph, 'Node');
		foreach($oNodesIter as $oNode)
		{
			set_time_limit($iLoopTimeLimit);
			if ($bDirectionDown && $oNode->GetProperty('source'))
			{
				$oNode->GroupSimilarNeighbours($oNewGraph, $iGroupingThreshold, true, $bDirectionDown);
			}
			else if (!$bDirectionDown && $oNode->GetProperty('sink'))
			{
				$oNode->GroupSimilarNeighbours($oNewGraph, $iGroupingThreshold, true, $bDirectionDown);
			}
		}
		// Groups numbering
		$oIterator = new RelationTypeIterator($oNewGraph, 'Node');
		$iGroupIdx = 0;
		foreach($oIterator as $oNode)
		{
			set_time_limit($iLoopTimeLimit);
			if ($oNode instanceof DisplayableGroupNode)
			{
				if ($oNode->GetObjectCount() == 0)
				{
					// Remove empty groups
					$oNewGraph->_RemoveNode($oNode);
				}
				else
				{
					$aGroups[] = $oNode->GetObjects();
					$oNode->SetProperty('group_index', $iGroupIdx);
					$iGroupIdx++;
				}
			}
		}
		
		// Remove duplicate edges between two nodes
		$oEdgesIter = new RelationTypeIterator($oNewGraph, 'Edge');
		$aEdgeKeys = array();
		foreach($oEdgesIter as $oEdge)
		{
			set_time_limit($iLoopTimeLimit);
			$sSourceId =  $oEdge->GetSourceNode()->GetId();
			$sSinkId = $oEdge->GetSinkNode()->GetId();
			if ($sSourceId == $sSinkId)
			{
				// Remove self referring edges
				$oNewGraph->_RemoveEdge($oEdge);
			}
			else
			{
				$sKey = $sSourceId.'//'.$sSinkId;
				if (array_key_exists($sKey, $aEdgeKeys))
				{
					// Remove duplicate edges
					$oNewGraph->_RemoveEdge($oEdge);
				}
				else
				{
					$aEdgeKeys[$sKey] = true;
				}
			}
		}
		set_time_limit($iPreviousTimeLimit);
		
		return $oNewGraph;
	}
	
	/**
	 * Initializes the positions by rendering using Graphviz in xdot format
	 * and parsing the output.
	 * @throws Exception
	 */
	public function InitFromGraphviz()
	{
		$sDot = $this->DumpAsXDot();
		if (strpos($sDot, 'digraph') === false)
		{
			throw new Exception($sDot);
		}
		
		$aChunks = explode(";", $sDot);
		foreach($aChunks as $sChunk)
		{
			if(preg_match('/"([^"]+)".+pos="([0-9\\.]+),([0-9\\.]+)"/ms', $sChunk, $aMatches))
			{
				$sId = $aMatches[1];
				$xPos = $aMatches[2];
				$yPos = $aMatches[3];
				
				$oNode = $this->GetNode($sId);
				if ($oNode !== null)
				{
					$oNode->x = (float)$xPos;
					$oNode->y = (float)$yPos;
				}
				else
				{
					IssueLog::Warning("??? Position of the non-existing node '$sId', x=$xPos, y=$yPos");
				}
			}
		}
	}
	
	public function GetBoundingBox()
	{
		$xMin = null;
		$xMax = null;
		$yMin = null;
		$yMax = null;
		$oIterator = new RelationTypeIterator($this, 'Node');
		foreach($oIterator as $sId => $oNode)
		{
			if ($xMin === null) // First element in the loop
			{
				$xMin = $oNode->x - $oNode->GetWidth();
				$xMax = $oNode->x + $oNode->GetWidth();
				$yMin = $oNode->y - $oNode->GetHeight();
				$yMax = $oNode->y + $oNode->GetHeight();
			}
			else
			{
				$xMin = min($xMin, $oNode->x - $oNode->GetWidth() / 2);
				$xMax = max($xMax, $oNode->x + $oNode->GetWidth() / 2);
				$yMin = min($yMin, $oNode->y - $oNode->GetHeight() / 2);
				$yMax = max($yMax, $oNode->y + $oNode->GetHeight() / 2);
			}
		}
		
		return array('xmin' => $xMin, 'xmax' => $xMax, 'ymin' => $yMin, 'ymax' => $yMax);
	}
	
	function Translate($dx, $dy)
	{
		$oIterator = new RelationTypeIterator($this, 'Node');
		foreach($oIterator as $sId => $oNode)
		{
			$oNode->x += $dx;
			$oNode->y += $dy;
		}		
	}
	
	public function UpdatePositions($aPositions)
	{
		foreach($aPositions as $sNodeId => $aPos)
		{
			$oNode = $this->GetNode($sNodeId);
			if ($oNode != null)
			{
				$oNode->x = $aPos['x'];
				$oNode->y = $aPos['y'];
			}
		}
	}

	/**
	 * Renders as JSON string suitable for loading into the simple_graph widget
	 */
	function GetAsJSON($sContextKey)
	{
		$aContextDefs = static::GetContextDefinitions($sContextKey, false);
		
		$aData = array('nodes' => array(), 'edges' => array(), 'groups' => array());
		$iGroupIdx = 0;
		$oIterator = new RelationTypeIterator($this, 'Node');
		foreach($oIterator as $sId => $oNode)
		{
			if ($oNode instanceof DisplayableGroupNode)
			{
				// The contents of the "Groups" tab will be rendered
				// using a separate ajax call, since the content of
				// the page is made of a mix of HTML / CSS / JS which
				// cannot be conveyed easily in the JSON structure
				// So we just pass a list of groups, each being defined by a class and a list of keys
				// in order to avoid redoing the impact computation which is expensive
				$aObjects = $oNode->GetObjects();
				$aKeys = array();
				foreach($aObjects as $oObj)
				{
					$sClass = get_class($oObj);
					$aKeys[] = $oObj->GetKey();
				}
				$aData['groups'][$iGroupIdx] = array('class' => $sClass, 'keys' => $aKeys);
				$oNode->SetProperty('group_index', $iGroupIdx);
				$iGroupIdx++;
			}
			$aData['nodes'][] = $oNode->GetForRaphael($aContextDefs);
		}
		
		$oIterator = new RelationTypeIterator($this, 'Edge');
		foreach($oIterator as $sId => $oEdge)
		{
			$aEdge = array();
			$aEdge['id'] = $oEdge->GetId();
			$aEdge['source_node_id'] = $oEdge->GetSourceNode()->GetId();
			$aEdge['sink_node_id'] = $oEdge->GetSinkNode()->GetId();
			$fOpacity = ($oEdge->GetSinkNode()->GetProperty('is_reached') && $oEdge->GetSourceNode()->GetProperty('is_reached') ? 1 : 0.2);
			$aEdge['attr'] = array('opacity' => $fOpacity, 'stroke' => '#000');
			$aData['edges'][] = $aEdge;
		}
	
		return json_encode($aData);
	}
	
	/**
	 * Renders the graph in a PDF document: centered in the current page
	 * @param PDFPage $oPage The PDFPage representing the PDF document to draw into
	 * @param string $sComments An optional comment to  display next to the graph (HTML entities will be escaped, \n replaced by <br/>)
	 * @param string $sContextKey The key to fetch the queries in the configuration. Example: itop-tickets/relation_context/UserRequest/impacts/down 
	 * @param float $xMin Left coordinate of the bounding box to display the graph
	 * @param float $xMax Right coordinate of the bounding box to display the graph
	 * @param float $yMin Top coordinate of the bounding box to display the graph
	 * @param float $yMax Bottom coordinate of the bounding box to display the graph
	 */
	function RenderAsPDF(PDFPage $oPage, $sComments = '', $sContextKey, $xMin = -1, $xMax = -1, $yMin = -1, $yMax = -1)
	{
		$aContextDefs = static::GetContextDefinitions($sContextKey, false); // No need to develop the parameters
		$oPdf = $oPage->get_tcpdf();
				
		$aBB = $this->GetBoundingBox();
		$this->Translate(-$aBB['xmin'], -$aBB['ymin']);
		
		$aMargins = $oPdf->getMargins();
		
		if ($xMin == -1)
		{
			$xMin = $aMargins['left'];
		}
		if ($xMax == -1)
		{
			$xMax =  $oPdf->getPageWidth() - $aMargins['right'];
		}
		if ($yMin == -1)
		{
			$yMin = $aMargins['top'];
		}
		if ($yMax == -1)
		{
			$yMax = $oPdf->getPageHeight() - $aMargins['bottom'];
		}
		
		$fBreakMargin = $oPdf->getBreakMargin();
		$oPdf->SetAutoPageBreak(false);
		$aRemainingArea = $this->RenderKey($oPdf, $sComments, $xMin, $yMin, $xMax, $yMax, $aContextDefs);
		$xMin = $aRemainingArea['xmin'];
		$xMax = $aRemainingArea['xmax'];
		$yMin = $aRemainingArea['ymin'];
		$yMax = $aRemainingArea['ymax'];
		
		//$oPdf->Rect($xMin, $yMin, $xMax - $xMin, $yMax - $yMin, 'D', array(), array(225, 50, 50));
		
		$fPageW = $xMax - $xMin;
		$fPageH = $yMax - $yMin;
		
		$w = $aBB['xmax'] - $aBB['xmin']; 
		$h = $aBB['ymax'] - $aBB['ymin'] + 10; // Extra space for the labels which may appear "below" the icons
		
		$fScale = min($fPageW / $w, $fPageH / $h);
		$dx = ($fPageW - $fScale * $w) / 2;
		$dy = ($fPageH - $fScale * $h) / 2;
		
		$this->Translate(($xMin + $dx)/$fScale, ($yMin + $dy)/$fScale);

		$oIterator = new RelationTypeIterator($this, 'Edge');
		$iLoopTimeLimit = MetaModel::GetConfig()->Get('max_execution_time_per_loop');
		foreach($oIterator as $sId => $oEdge)
		{
			set_time_limit($iLoopTimeLimit);
			$oEdge->RenderAsPDF($oPdf, $this, $fScale, $aContextDefs);
		}

		$oIterator = new RelationTypeIterator($this, 'Node');
		foreach($oIterator as $sId => $oNode)
		{
			set_time_limit($iLoopTimeLimit);
			$oNode->RenderAsPDF($oPdf, $this, $fScale, $aContextDefs);
		}

		$oPdf->SetAutoPageBreak(true, $fBreakMargin);
		$oPdf->SetAlpha(1);
	}
	
	/**
	 * Renders (in PDF) the key (legend) of the graphics vertically to the left of the specified zone (xmin,ymin, xmax,ymax),
	 * and the comment (if any) at the bottom of the page. Returns the position of remaining area.
	 * @param TCPDF $oPdf
	 * @param string $sComments
	 * @param float $xMin
	 * @param float $yMin
	 * @param float $xMax
	 * @param float $yMax
	 * @param hash $aContextDefs
	 * @return hash An array ('xmin' => , 'xmax' => ,'ymin' => , 'ymax' => ) of the remaining available area to paint the graph
	 */
	protected function RenderKey(TCPDF $oPdf, $sComments, $xMin, $yMin, $xMax, $yMax, $aContextDefs)
	{
		$fFontSize = 7; // in mm
		$fIconSize = 6; // in mm
		$fPadding = 1;	// in mm
		$oIterator = new RelationTypeIterator($this, 'Node');
		$fMaxWidth = max($oPdf->GetStringWidth(Dict::S('UI:Relation:Key')) - $fIconSize, $oPdf->GetStringWidth(Dict::S('UI:Relation:Comments')) - $fIconSize);
		$aClasses = array();
		$aIcons = array();
		$aContexts = array();
		$aContextIcons = array();
		$oPdf->SetFont('dejavusans', '', $fFontSize, '', true);
		foreach($oIterator as $sId => $oNode)
		{
			if ($sClass = $oNode->GetObjectClass())
			{
				if (!array_key_exists($sClass, $aClasses))
				{
					$sClassLabel = MetaModel::GetName($sClass);
					$width = $oPdf->GetStringWidth($sClassLabel);
					$fMaxWidth = max($width, $fMaxWidth);
					$aClasses[$sClass] = $sClassLabel;
					$sIconUrl = $oNode->GetProperty('icon_url');
					$sIconPath = str_replace(utils::GetAbsoluteUrlModulesRoot(), APPROOT.'env-'.utils::GetCurrentEnvironment().'/', $sIconUrl);
					$aIcons[$sClass] = $sIconPath;
				}
			}
			$aContextRootCauses = $oNode->GetProperty('context_root_causes');
			if (!is_null($aContextRootCauses))
			{
				foreach($aContextRootCauses as $key => $aObjects)
				{
					$aContexts[$key] = Dict::S($aContextDefs[$key]['dict']);
					$aContextIcons[$key] = APPROOT.'env-'.utils::GetCurrentEnvironment().'/'.$aContextDefs[$key]['icon'];
				}
			}
		}
		$oPdf->SetXY($xMin + $fPadding, $yMin + $fPadding);
		$yPos = $yMin + $fPadding;
		$oPdf->SetFillColor(225, 225, 225);
		$oPdf->Cell($fIconSize + $fPadding + $fMaxWidth, $fIconSize + $fPadding, Dict::S('UI:Relation:Key'), 0 /* border */, 1 /* ln */, 'C', true /* fill */);
		$yPos += $fIconSize + 2*$fPadding;
		foreach($aClasses as $sClass => $sLabel)
		{
			$oPdf->SetX($xMin + $fIconSize + $fPadding);
			$oPdf->Cell(0, $fIconSize + 2*$fPadding, $sLabel, 0 /* border */, 1 /* ln */);
			$oPdf->Image($aIcons[$sClass], $xMin+1, $yPos, $fIconSize, $fIconSize);
			$yPos += $fIconSize + 2*$fPadding; 
		}
		foreach($aContexts as $key => $sLabel)
		{
			$oPdf->SetX($xMin + $fIconSize + $fPadding);
			$oPdf->Cell(0, $fIconSize + 2*$fPadding, $sLabel, 0 /* border */, 1 /* ln */);
			$oPdf->Image($aContextIcons[$key], $xMin+1+$fIconSize*0.125, $yPos+$fIconSize*0.125, $fIconSize*0.75, $fIconSize*0.75);
			$yPos += $fIconSize + 2*$fPadding;
		}
		$oPdf->Rect($xMin, $yMin, $fMaxWidth + $fIconSize + 3*$fPadding, $yMax - $yMin, 'D');
		
		if ($sComments != '')
		{
			// Draw the comment text (surrounded by a rectangle)
			$xPos = $xMin + $fMaxWidth + $fIconSize + 4*$fPadding;
			$w = $xMax - $xPos - 2*$fPadding;
			$iNbLines = 1;
			$sText = '<p>'.str_replace("\n", '<br/>', htmlentities($sComments, ENT_QUOTES, 'UTF-8'), $iNbLines).'</p>';
			$fLineHeight = $oPdf->getStringHeight($w, $sText);
			$h = (1+$iNbLines) * $fLineHeight;
			$yPos = $yMax - 2*$fPadding - $h;
			$oPdf->writeHTMLCell($w, $h, $xPos + $fPadding, $yPos + $fPadding, $sText, 0 /* border */, 1 /* ln */);
			$oPdf->Rect($xPos, $yPos, $w + 2*$fPadding, $h + 2*$fPadding, 'D');
			$yMax = $yPos - $fPadding;
		}
		
		return array('xmin' => $xMin + $fMaxWidth + $fIconSize + 4*$fPadding, 'xmax' => $xMax, 'ymin' => $yMin, 'ymax' => $yMax);
	}
	
	/**
	 * Get the context definitions from the parameters / configuration. The format of the "key" string is:
	 * <module>/relation_context/<class>/<relation>/<direction>
	 * The values will be retrieved for the given class and all its parents and merged together as a single array.
	 * Entries with an invalid query are removed from the list.
	 * @param string $sContextKey The key to fetch the queries in the configuration. Example: itop-tickets/relation_context/UserRequest/impacts/down
	 * @param bool $bDevelopParams Whether or not to substitute the parameters inside the queries with the supplied "context params"
	 * @param array $aContextParams Arguments for the queries (via ToArgs()) if $bDevelopParams == true
	 * @return multitype:multitype:string
	 */
	public static function GetContextDefinitions($sContextKey, $bDevelopParams = true, $aContextParams = array())
	{
		$aContextDefs = array();
		$aLevels = explode('/', $sContextKey);
		if (count($aLevels) < 5)
		{
			IssueLog::Warning("GetContextDefinitions: invalid 'sContextKey' = '$sContextKey'. 5 levels of / are expected !");
		}
		else
		{
			$sLeafClass = $aLevels[2];
			
			if (!MetaModel::IsValidClass($sLeafClass))
			{
				IssueLog::Warning("GetContextDefinitions: invalid 'sLeafClass' = '$sLeafClass'. A valid class name is expected in 3rd position inside '$sContextKey' !");
			}
			else
			{
				$aRelationContext = MetaModel::GetConfig()->GetModuleSetting($aLevels[0], $aLevels[1], array());
				foreach(MetaModel::EnumParentClasses($sLeafClass, ENUM_PARENT_CLASSES_ALL) as $sClass)
				{
					if (isset($aRelationContext[$sClass][$aLevels[3]][$aLevels[4]]['items']))
					{
						$aContextDefs = array_merge($aContextDefs, $aRelationContext[$sClass][$aLevels[3]][$aLevels[4]]['items']);
					}
				}
				
				// Check if the queries are valid
				foreach($aContextDefs as $sKey => $sDefs)
				{
					$sOQL = $aContextDefs[$sKey]['oql'];
					try
					{
						// Expand the parameters. If anything goes wrong, then the query is considered as invalid and removed from the list
						$oSearch = DBObjectSearch::FromOQL($sOQL);
						$aContextDefs[$sKey]['oql'] = $oSearch->ToOQL($bDevelopParams, $aContextParams);
					}
					catch(Exception $e)
					{
						IssueLog::Warning('Invalid OQL query: '.$sOQL.' in the parameter '.$sContextKey);
						unset($aContextDefs[$sKey]);
					}
				}
			}
		}
		return $aContextDefs;
	}
	
	/**
	 * Display the graph inside the given page, with the "filter" drawer above it
	 * @param WebPage $oP
	 * @param hash $aResults
	 * @param string $sRelation
	 * @param ApplicationContext $oAppContext
	 * @param array $aExcludedObjects
	 */
	function Display(WebPage $oP, $aResults, $sRelation, ApplicationContext $oAppContext, $aExcludedObjects = array(), $sObjClass = null, $iObjKey = null, $sContextKey, $aContextParams = array())
	{	
		$aContextDefs = static::GetContextDefinitions($sContextKey, true, $aContextParams);
		$aExcludedByClass = array();
		foreach($aExcludedObjects as $oObj)
		{
			if (!array_key_exists(get_class($oObj), $aExcludedByClass))
			{
				$aExcludedByClass[get_class($oObj)] = array();
			}
			$aExcludedByClass[get_class($oObj)][] = $oObj->GetKey();
		}
		$oP->add("<div class=\"not-printable\">\n");
		$oP->add("<div id=\"ds_flash\" class=\"SearchDrawer\" style=\"display:none;\">\n");
		if (!$oP->IsPrintableVersion())
		{
			$oP->add_ready_script(
<<<EOF
	$( "#tabbedContent_0" ).tabs({ heightStyle: "fill" });
EOF
			);
		}
		
		$oP->add_ready_script(
<<<EOF
	$("#dh_flash").click( function() {
		$("#ds_flash").slideToggle('normal', function() { $("#ds_flash").parent().resize(); $("#dh_flash").trigger('toggle_complete'); } );
		$("#dh_flash").toggleClass('open');
	});
    $('#ReloadMovieBtn').button().button('disable');
EOF
		);
		$aSortedElements = array();
		foreach($aResults as $sClassIdx => $aObjects)
		{
			foreach($aObjects as $oCurrObj)
			{
				$sSubClass = get_class($oCurrObj);
				$aSortedElements[$sSubClass] = MetaModel::GetName($sSubClass);
			}
		}
				
		asort($aSortedElements);
		$idx = 0;
		foreach($aSortedElements as $sSubClass => $sClassName)
		{
			$oP->add("<span style=\"padding-right:2em; white-space:nowrap;\"><input type=\"checkbox\" id=\"exclude_$idx\" name=\"excluded[]\" value=\"$sSubClass\" checked onChange=\"$('#ReloadMovieBtn').button('enable')\"><label for=\"exclude_$idx\">&nbsp;".MetaModel::GetClassIcon($sSubClass)."&nbsp;$sClassName</label></span> ");
			$idx++;
		}
		$oP->add("<p style=\"text-align:right\"><button type=\"button\" id=\"ReloadMovieBtn\" onClick=\"DoReload()\">".Dict::S('UI:Button:Refresh')."</button></p>");
		$oP->add("</div>\n");
		$oP->add("<div class=\"HRDrawer\"></div>\n");
		$oP->add("<div id=\"dh_flash\" class=\"DrawerHandle\">".Dict::S('UI:ElementsDisplayed')."</div>\n");
	 	$oP->add("</div>\n"); // class="not-printable"

		$aAdditionalContexts = array();
		foreach($aContextDefs as $sKey => $aDefinition)
		{
			$aAdditionalContexts[] = array('key' => $sKey, 'label' => Dict::S($aDefinition['dict']), 'oql' => $aDefinition['oql'], 'default' => (array_key_exists('default', $aDefinition)  && ($aDefinition['default'] == 'yes')));
		}
		
		$sDirection = utils::ReadParam('d', 'horizontal');
		$iGroupingThreshold = utils::ReadParam('g', 5);
	
		$oP->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/fraphael.js');
		$oP->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/jquery.contextMenu.css');
		$oP->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.contextMenu.js');
		$oP->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/simple_graph.js');
		try
		{
			$this->InitFromGraphviz();
			$sExportAsPdfURL = '';
			$sExportAsPdfURL = utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php?operation=relation_pdf&relation='.$sRelation.'&direction='.($this->bDirectionDown ? 'down' : 'up');
			$oAppcontext = new ApplicationContext();
			$sContext = $oAppContext->GetForLink();
			$sDrillDownURL = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=details&class=%1$s&id=%2$s&'.$sContext;
			$sExportAsDocumentURL = utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php?operation=relation_attachment&relation='.$sRelation.'&direction='.($this->bDirectionDown ? 'down' : 'up');
			$sLoadFromURL = utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php?operation=relation_json&relation='.$sRelation.'&direction='.($this->bDirectionDown ? 'down' : 'up');
			$sAttachmentExportTitle = '';
			if (($sObjClass != null) && ($iObjKey != null))
			{
				$oTargetObj = MetaModel::GetObject($sObjClass, $iObjKey, false);
				if ($oTargetObj)
				{
					$sAttachmentExportTitle = Dict::Format('UI:Relation:AttachmentExportOptions_Name', $oTargetObj->GetName());
				}
			}
	
			$sId = 'graph';
			$sStyle = '';
			if ($oP->IsPrintableVersion())
			{
				// Optimize for printing on A4/Letter vertically
				$sStyle = 'margin-left:auto; margin-right:auto;';
				$oP->add_ready_script("$('.simple-graph').width(18/2.54*96).resizable({ stop: function() { $(window).trigger('resized'); }});"); // Default width about 18 cm, since most browsers assume 96 dpi
			}
			$oP->add('<div id="'.$sId.'" class="simple-graph" style="'.$sStyle.'"></div>');
			$aParams = array(
				'source_url' => $sLoadFromURL,
				'sources' => ($this->bDirectionDown ? $this->aSourceObjects : $this->aSinkObjects),
				'excluded' => $aExcludedByClass,
				'grouping_threshold' => $iGroupingThreshold,
				'export_as_pdf' => array('url' => $sExportAsPdfURL, 'label' => Dict::S('UI:Relation:ExportAsPDF')),
				'export_as_attachment' => array('url' => $sExportAsDocumentURL, 'label' => Dict::S('UI:Relation:ExportAsAttachment'), 'obj_class' => $sObjClass, 'obj_key' => $iObjKey),
				'drill_down' => array('url' => $sDrillDownURL, 'label' => Dict::S('UI:Relation:DrillDown')),
				'labels' => array(
					'export_pdf_title' => Dict::S('UI:Relation:PDFExportOptions'),
					'export_as_attachment_title' => $sAttachmentExportTitle,
					'export' => Dict::S('UI:Button:Export'),
					'cancel' => Dict::S('UI:Button:Cancel'),
					'title' => Dict::S('UI:RelationOption:Title'),
					'untitled' => Dict::S('UI:RelationOption:Untitled'),
					'include_list' => Dict::S('UI:RelationOption:IncludeList'),
					'comments' => Dict::S('UI:RelationOption:Comments'),
					'grouping_threshold' => Dict::S('UI:RelationOption:GroupingThreshold'),
					'refresh' => Dict::S('UI:Button:Refresh'),
					'check_all' => Dict::S('UI:SearchValue:CheckAll'),
					'uncheck_all' => Dict::S('UI:SearchValue:UncheckAll'),
					'none_selected' => Dict::S('UI:Relation:NoneSelected'),
					'nb_selected' => Dict::S('UI:SearchValue:NbSelected'),
					'additional_context_info' => Dict::S('UI:Relation:AdditionalContextInfo'),
					'zoom' => Dict::S('UI:Relation:Zoom'),
					'loading' => Dict::S('UI:Loading'),
				),
				'page_format' => array(
					'label' => Dict::S('UI:Relation:PDFExportPageFormat'),
					'values' => array(
						'A3' => Dict::S('UI:PageFormat_A3'),
						'A4' => Dict::S('UI:PageFormat_A4'),
						'Letter' => Dict::S('UI:PageFormat_Letter'),
					),
				),
				'page_orientation' => array(
					'label' => Dict::S('UI:Relation:PDFExportPageOrientation'),
					'values' => array(
						'P' => Dict::S('UI:PageOrientation_Portrait'),
						'L' => Dict::S('UI:PageOrientation_Landscape'),
					),
				),
				'additional_contexts' => $aAdditionalContexts,
				'context_key' => $sContextKey,
			);
			if (!extension_loaded('gd'))
			{
				// PDF export requires GD
				unset($aParams['export_as_pdf']);
			}
			if (!extension_loaded('gd') || is_null($sObjClass) || is_null($iObjKey))
			{
				// Export as Attachment requires GD (for building the PDF) AND a valid objclass/objkey couple
				unset($aParams['export_as_attachment']);
			}
			$oP->add_ready_script("$('#$sId').simple_graph(".json_encode($aParams).");");
		}
		catch(Exception $e)
		{
			$oP->add('<div>'.$e->getMessage().'</div>');
		}
		$oP->add_script(
<<<EOF
		
	function DoReload()
	{
		$('#ReloadMovieBtn').button('disable');
		try
		{
			var aExcluded = [];
			$('input[name^=excluded]').each( function() {
				if (!$(this).prop('checked'))
				{
					aExcluded.push($(this).val());
				}
			} );
			$('#graph').simple_graph('option', {excluded_classes: aExcluded});
			$('#graph').simple_graph('reload');
		}
		catch(err)
		{
			alert(err);
		}
	}
EOF
		);
	}
	
}