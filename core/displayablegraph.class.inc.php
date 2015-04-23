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
	public function __construct(SimpleGraph $oGraph, $sId, $x = 0, $y = 0)
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
	
	public function GetForRaphael()
	{
		$aNode = array();
		$aNode['shape'] = 'icon';
		$aNode['icon_url'] = $this->GetIconURL();
		$aNode['width'] = 32;
		$aNode['source'] = ($this->GetProperty('source') == true);
		$aNode['sink'] = ($this->GetProperty('sink') == true);
		$aNode['x'] = $this->x;
		$aNode['y']= $this->y;
		$aNode['label'] = $this->GetLabel();
		$aNode['id'] = $this->GetId();
		$fOpacity = ($this->GetProperty('is_reached') ? 1 : 0.4);
		$aNode['icon_attr'] = array('opacity' => $fOpacity);		
		$aNode['text_attr'] = array('opacity' => $fOpacity);		
		return $aNode;
	}
	
	public function RenderAsPDF(TCPDF $oPdf, $fScale)
	{
		$Alpha = 1.0;
		$oPdf->SetFillColor(200, 200, 200);
		$oPdf->setAlpha(1);
		
		$sIconUrl = $this->GetProperty('icon_url');
		$sIconPath = str_replace(utils::GetAbsoluteUrlModulesRoot(), APPROOT.'env-production/', $sIconUrl);
		
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
			if (function_exists('imagecreatefrompng'))
			{
				$im = imagecreatefrompng($sIconPath);
				
				if($im && imagefilter($im, IMG_FILTER_COLORIZE, 255, 255, 255))
				{
					$sTempImageName = APPROOT.'data/tmp-'.basename($sIconPath);
					imagesavealpha($im, true);
					imagepng($im, $sTempImageName);
					imagedestroy($im);
					$oPdf->Image($sTempImageName, ($this->x - 16)*$fScale, ($this->y - 16)*$fScale, 32*$fScale, 32*$fScale);
				}
			}
			$Alpha = 0.4;
			$oPdf->setAlpha($Alpha);
		}
		
		$oPdf->Image($sIconPath, ($this->x - 16)*$fScale, ($this->y - 16)*$fScale, 32*$fScale, 32*$fScale);
		//$oPdf->Image(APPROOT.'images/blank-100x100.png', ($this->x - 16)*$fScale, ($this->y - 16)*$fScale, 32*$fScale, 32*$fScale, '', '', '', false, 300, '', false, $mask);
		//Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array())
		
		$oPdf->SetFont('Helvetica', '', 24 * $fScale, '', true);
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
	
	public function GroupSimilarNeighbours($oGraph, $iThresholdCount, $bDirectionUp = false, $bDirectionDown = true)
	{
//echo "<p>".$this->GetProperty('label').":</p>";
		
		if ($this->GetProperty('grouped') === true) return;
		$this->SetProperty('grouped', true);
			
		if ($bDirectionDown)
		{
			$aNodesPerClass = array();
			foreach($this->GetOutgoingEdges() as $oEdge)
			{
				$oNode = $oEdge->GetSinkNode();
				
				if ($oNode->GetProperty('class') !== null)
				{
					$sClass = $oNode->GetProperty('class');
					if (($sClass!== null) && (!array_key_exists($sClass, $aNodesPerClass)))
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
						$aNodesPerClass[$sClass][$sKey]['count'] += (int)$oNode->GetProperty('count', 1);
//echo "<p>New count: ".$aNodesPerClass[$sClass][$sKey]['count']."</p>";
					}
						
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
//echo "<p>$sClass/$sStatus: {$aGroupProps['count']} object(s), actually: ".count($aGroupProps['nodes'])."</p>";
					if (count($aGroupProps['nodes']) >= $iThresholdCount)
					{
						$oNewNode = new DisplayableGroupNode($oGraph, $this->GetId().'::'.$sClass);
						$oNewNode->SetProperty('label', 'x'.$aGroupProps['count']);
						$oNewNode->SetProperty('icon_url', $aGroupProps['icon_url']);
						$oNewNode->SetProperty('class', $sClass);
						$oNewNode->SetProperty('is_reached', ($sStatus == 'reached'));
						$oNewNode->SetProperty('count', $aGroupProps['count']);
						//$oNewNode->SetProperty('grouped', true);
						
						$oIncomingEdge = new DisplayableEdge($oGraph, $this->GetId().'-'.$oNewNode->GetId(), $this, $oNewNode);
										
						foreach($aGroupProps['nodes'] as $oNode)
						{
							foreach($oNode->GetIncomingEdges() as $oEdge)
							{
								if ($oEdge->GetSourceNode()->GetId() !== $this->GetId())
								{
									$oNewEdge = new DisplayableEdge($oGraph, $oEdge->GetId().'::'.$sClass, $oEdge->GetSourceNode(), $oNewNode);
								}
							}
							foreach($oNode->GetOutgoingEdges() as $oEdge)
							{
								$aOutgoing[] = $oEdge->GetSinkNode();
								try
								{
									$oNewEdge = new DisplayableEdge($oGraph, $oEdge->GetId().'::'.$sClass, $oNewNode, $oEdge->GetSinkNode());
								}
								catch(Exception $e)
								{
									// ignore this edge
								}
							}
							if ($oGraph->GetNode($oNode->GetId()))
							{
								$oGraph->_RemoveNode($oNode);
							}
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
	}
}

class DisplayableRedundancyNode extends DisplayableNode
{
	public function GetWidth()
	{
		return 24;
	}
	
	public function GetForRaphael()
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
		$aNode['disc_attr'] = array('stroke-width' => 3, 'stroke' => '#000', 'fill' => '#c33', 'opacity' => $fDiscOpacity);
		$fTextOpacity = ($this->GetProperty('is_reached') ? 1 : 0.4);
		$aNode['text_attr'] = array('fill' => '#fff', 'opacity' => $fTextOpacity);		
		return $aNode;
	}

	public function RenderAsPDF(TCPDF $oPdf, $fScale)
	{
		$oPdf->SetAlpha(1);
		$oPdf->SetFillColor(200, 0, 0);
		$oPdf->SetDrawColor(0, 0, 0);
		$oPdf->Circle($this->x*$fScale, $this->y*$fScale, 16*$fScale, 0, 360, 'DF');

		$oPdf->SetTextColor(255, 255, 255);
		$oPdf->SetFont('Helvetica', '', 28 * $fScale, '', true);
		$sLabel  = (string)$this->GetProperty('label');
		$width = $oPdf->GetStringWidth($sLabel, 'Helvetica', 'B', 24*$fScale);
		$height = $oPdf->GetStringHeight(1000, $sLabel);
		$xPos = (float)$this->x*$fScale - $width/2;
		$yPos = (float)$this->y*$fScale - $height/2;
//		$oPdf->Rect($xPos, $yPos, $width, $height, 'D');
//		$oPdf->Text($xPos, $yPos, $sLabel);
		
		$oPdf->SetXY(($this->x - 16)*$fScale, ($this->y - 16)*$fScale);
		
		// text on center
		$oPdf->Cell(32*$fScale, 32*$fScale, $sLabel, 0, 0, 'C', 0, '', 0, false, 'T', 'C');
	}
	
	public function GroupSimilarNeighbours($oGraph, $iThresholdCount, $bDirectionUp = false, $bDirectionDown = true)
	{
		parent::GroupSimilarNeighbours($oGraph, $iThresholdCount, $bDirectionUp, $bDirectionDown);
		
		if ($bDirectionUp)
		{
			$aNodesPerClass = array();
			foreach($this->GetIncomingEdges() as $oEdge)
			{
				$oNode = $oEdge->GetSourceNode();
		
				if (($oNode->GetProperty('class') !== null) && (!$oNode->GetProperty('is_reached')))
				{
					$sClass = $oNode->GetProperty('class');
					if (!array_key_exists($sClass, $aNodesPerClass))
					{
						$aNodesPerClass[$sClass] = array('reached' => array(), 'not_reached' => array());
					}
					$aNodesPerClass[$sClass][$oNode->GetProperty('is_reached') ? 'reached' : 'not_reached'][] = $oNode;
				}
				else
				{
					//$oNode->GroupSimilarNeighbours($oGraph, $iThresholdCount, $bDirectionUp, $bDirectionDown);
				}
			}

			foreach($aNodesPerClass as $sClass => $aDefs)
			{
				foreach($aDefs as $sStatus => $aNodes)
				{
//echo "<p>".$this->GetId().' has '.count($aNodes)." neighbours of class $sClass in status $sStatus\n";
					if (count($aNodes) >= $iThresholdCount)
					{
						$oNewNode = new DisplayableGroupNode($oGraph, '-'.$this->GetId().'::'.$sClass.'/'.$sStatus);
						$oNewNode->SetProperty('label', 'x'.count($aNodes));
						$oNewNode->SetProperty('icon_url', $aNodes[0]->GetProperty('icon_url'));
						$oNewNode->SetProperty('is_reached', $aNodes[0]->GetProperty('is_reached'));
							
						$oOutgoingEdge = new DisplayableEdge($oGraph, '-'.$this->GetId().'-'.$oNewNode->GetId().'/'.$sStatus, $oNewNode, $this);
		
						foreach($aNodes as $oNode)
						{
							foreach($oNode->GetIncomingEdges() as $oEdge)
							{
								$oNewEdge = new DisplayableEdge($oGraph, '-'.$oEdge->GetId().'::'.$sClass, $oEdge->GetSourceNode(), $oNewNode);
							}
							foreach($oNode->GetOutgoingEdges() as $oEdge)
							{
								if ($oEdge->GetSinkNode()->GetId() !== $this->GetId())
								{
									$aOutgoing[] = $oEdge->GetSinkNode();
									$oNewEdge = new DisplayableEdge($oGraph, '-'.$oEdge->GetId().'::'.$sClass.'/'.$sStatus, $oNewNode, $oEdge->GetSinkNode());
								}
							}
//echo "<p>Replacing ".$oNode->GetId().' by '.$oNewNode->GetId()."\n";
							$oGraph->_RemoveNode($oNode);
						}
						//$oNewNode->GroupSimilarNeighbours($oGraph, $iThresholdCount, $bDirectionUp, $bDirectionDown);
					}
					else
					{
						foreach($aNodes as $oNode)
						{
							//$oNode->GroupSimilarNeighbours($oGraph, $iThresholdCount, $bDirectionUp, $bDirectionDown);
						}
					}
				}
			}
		}
	}
}

class DisplayableEdge extends GraphEdge
{
	public function RenderAsPDF(TCPDF $oPdf, $fScale)
	{
		$xStart = $this->GetSourceNode()->x * $fScale;
		$yStart = $this->GetSourceNode()->y * $fScale;
		$xEnd = $this->GetSinkNode()->x * $fScale;
		$yEnd = $this->GetSinkNode()->y * $fScale;
		
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
	public function GetWidth()
	{
		return 50;
	}

	public function GetForRaphael()
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
		$fDiscOpacity = ($this->GetProperty('is_reached') ? 1 : 0.2);
		$fTextOpacity = ($this->GetProperty('is_reached') ? 1 : 0.4);
		$aNode['icon_attr'] = array('opacity' => $fTextOpacity);
		$aNode['disc_attr'] = array('stroke-width' => 3, 'stroke' => '#000', 'fill' => '#fff', 'opacity' => $fDiscOpacity);
		$aNode['text_attr'] = array('fill' => '#000', 'opacity' => $fTextOpacity);
		return $aNode;
	}
	
	public function RenderAsPDF(TCPDF $oPdf, $fScale)
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
		$sIconPath = str_replace(utils::GetAbsoluteUrlModulesRoot(), APPROOT.'env-production/', $sIconUrl);
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
		$oPdf->SetFont('Helvetica', '', 24 * $fScale, '', true);
		$width = $oPdf->GetStringWidth($this->GetProperty('label'));
		$oPdf->SetTextColor(0, 0, 0);
		$oPdf->Text($this->x*$fScale - $width/2, ($this->y + 25)*$fScale, $this->GetProperty('label'));
	}
}


class DisplayableGraph extends SimpleGraph
{
	protected $sDirection;
	
	public static function FromRelationGraph(RelationGraph $oGraph, $iGroupingThreshold = 20, $bDirectionDown = true)
	{
		$oNewGraph = new DisplayableGraph();
		
		$oNodesIter = new RelationTypeIterator($oGraph, 'Node');
		foreach($oNodesIter as $oNode)
		{
			switch(get_class($oNode))
			{
				case 'RelationObjectNode':				
				$oNewNode = new DisplayableNode($oNewGraph, $oNode->GetId(), 0, 0);
				
				if ($oNode->GetProperty('source'))
				{
					$oNewNode->SetProperty('source', true);
				}
				if ($oNode->GetProperty('sink'))
				{
					$oNewNode->SetProperty('sink', true);
				}
				$oObj = $oNode->GetProperty('object');
				$oNewNode->SetProperty('class', get_class($oObj));
				$oNewNode->SetProperty('icon_url', $oObj->GetIcon(false));
				$oNewNode->SetProperty('label', $oObj->GetRawName());
				$oNewNode->SetProperty('is_reached', $bDirectionDown ? $oNode->GetProperty('is_reached') : true); // When going "up" is_reached does not matter
				$oNewNode->SetProperty('developped', $oNode->GetProperty('developped'));
				break;
				
				default:
				$oNewNode = new DisplayableRedundancyNode($oNewGraph, $oNode->GetId(), 0, 0);
				$oNewNode->SetProperty('label', $oNode->GetProperty('min_up'));
				$oNewNode->SetProperty('is_reached', true);
			}
		}
		$oEdgesIter = new RelationTypeIterator($oGraph, 'Edge');
		foreach($oEdgesIter as $oEdge)
		{
			$oSourceNode = $oNewGraph->GetNode($oEdge->GetSourceNode()->GetId());
			$oSinkNode = $oNewGraph->GetNode($oEdge->GetSinkNode()->GetId());
			$oNewEdge = new DisplayableEdge($oNewGraph, $oEdge->GetId(), $oSourceNode, $oSinkNode);
		}
		
		// Remove duplicate edges between two nodes
		$oEdgesIter = new RelationTypeIterator($oNewGraph, 'Edge');
		$aEdgeKeys = array();
		foreach($oEdgesIter as $oEdge)
		{
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
		
		$iNbGrouping = 1;
		//for($iter=0; $iter<$iNbGrouping; $iter++)
		{
			$oNodesIter = new RelationTypeIterator($oNewGraph, 'Node');
			foreach($oNodesIter as $oNode)
			{
				if ($oNode->GetProperty('source'))
				{
					$oNode->GroupSimilarNeighbours($oNewGraph, $iGroupingThreshold, true, true);
				}
			}
		}
		
		// Remove duplicate edges between two nodes
		$oEdgesIter = new RelationTypeIterator($oNewGraph, 'Edge');
		$aEdgeKeys = array();
		foreach($oEdgesIter as $oEdge)
		{
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

		return $oNewGraph;
	}
	
	public function InitOnGrid()
	{
		$iDist = 125;
		$aAllNodes = $this->_GetNodes();
		$iSide = ceil(sqrt(count($aAllNodes)));
		$xPos = 0;
		$yPos = 0;
		$idx = 0;
		foreach($aAllNodes as $oNode)
		{
			$xPos += $iDist;
			if (($idx % $iSide) == 0)
			{
				$xPos = 0;
				$yPos += $iDist;
			}
			
			$oNode->x = $xPos;
			$oNode->y = $yPos;
			
			$idx++;
		}
		
	}
	
	public function InitFromGraphviz()
	{
		$sDot = $this->DumpAsXDot();
		$sDot = preg_replace('/.*label=.*,/', '', $sDot); // Get rid of label lines since they may contain weird characters than can break the split and pattern matching below
		
		$aChunks = explode(";", $sDot);
		foreach($aChunks as $sChunk)
		{
			//echo "<p>$sChunk</p>";
			if(preg_match('/"([^"]+)".+pos="([0-9\\.]+),([0-9\\.]+)"/ms', $sChunk, $aMatches))
			{
				$sId = $aMatches[1];
				$xPos = $aMatches[2];
				$yPos = $aMatches[3];
				
				$oNode = $this->GetNode($sId);
				$oNode->x = (float)$xPos;
				$oNode->y = (float)$yPos;
				
				//echo "<p>$sId at $xPos,$yPos</p>";
			}
			else
			{
				//echo "<p>No match</p>";
			}
		}
	}
	
	public function BruteForceLayout($iNbTicks, $sDirection = 'horizontal')
	{
		$iLoopTimeLimit = MetaModel::GetConfig()->Get('max_execution_time_per_loop');
		$this->sDirection = $sDirection;
		$this->InitForces();
		for($i=0; $i<$iNbTicks; $i++)
		{
			set_time_limit($iLoopTimeLimit);
			$this->Tick();
		}
	}
	
	protected function InitForces()
	{
		$oIterator = new RelationTypeIterator($this, 'Node');
		$i = 0;
		foreach($oIterator as $sId => $oNode)
		{
			$oNode->SetProperty('ax', 0);
			$oNode->SetProperty('ay', 0);
			$oNode->SetProperty('vx', 0);
			$oNode->SetProperty('vy', 0);
			$i++;
		}
	}
	
	protected function ComputeAcceleration()
	{
		$oIterator = new RelationTypeIterator($this, 'Node');
		foreach($oIterator as  $idx => $oNode)
		{
			$sNodeId = $oNode->GetId();
			
			$fx = 0;
			$fy = 0;
			$K = 0.6;
			$Q = 0.3;
			
			if ($oNode->GetProperty('source'))
			{
				switch($this->sDirection)
				{
					case 'horizontal':
						$fx -= 30;
						break;
							
					case 'vertical':
						$fy -= 30;
						break;
							
					default:
						// No gravity
				}				
			}
			else
			{
				switch($this->sDirection)
				{
					case 'horizontal':
					$fx += 30;
					break;
					
					case 'vertical':
					$fy += 30;
					break;
					
					default:
					// No gravity
				}
			}
			
//echo "<p>ComputeAcceleration - $sNodeId</p>\n";
				
			$oIter2 = new RelationTypeIterator($this, 'Edge');
			foreach($oIter2 as $sEdgeId => $oEdge)
			{
				$oSource = $oEdge->GetSourceNode();
				$oSink = $oEdge->GetSinkNode();
				
//echo "<p>$sEdgeId ".$oSource->GetId()." -> ".$oSink->GetId()."</p>\n";
				
				if ($oSource->GetId() === $sNodeId)
				{
					$fx += -$K * ($oSource->x - $oSink->x);
					$fy += -$K * ($oSource->y - $oSink->y);
//echo "<p>$sEdgeId Sink - F($fx, $fy)</p>\n";
				}
				else if ($oSink->GetId() === $sNodeId)
				{
					$fx += -$K * ($oSink->x - $oSource->x);
					$fy += -$K * ($oSink->y - $oSource->y);
//echo "<p>$sEdgeId Source - F($fx, $fy)</p>\n";
				}
				// Else do nothing for this node, it's not connected via this edge
			}
			$oIter3 = new RelationTypeIterator($this, 'Node');
			foreach($oIter3 as $idx2 => $oOtherNode)
			{
				$sOtherId = $oOtherNode->GetId();
				if ($sOtherId !== $sNodeId)
				{
					$d2 = $oOtherNode->Distance2($oNode) / (60*60);
					if ($d2 < 15)
					{
						$dfx = 	-$Q * ($oOtherNode->x - $oNode->x) / $d2;
						$dfy = 	-$Q * ($oOtherNode->y - $oNode->y) / $d2;
						
						$fx += $dfx;
						$fy += $dfy;
					}
					
//echo "<p>Electrostatic: $sOtherId d2: $d2 F($dfx, $dfy)</p>\n";

				}
			}
//echo "<p>total forces: $sNodeId d2: $d2 F($fx, $fy)</p>\n";
			$oNode->SetProperty('ax', $fx);
			$oNode->SetProperty('ay', $fy);
		}		
	}
	
	protected function Tick()
	{
		$dt = 0.1;
		$attenuation = 0.8;
		$M = 1;
		
		$this->ComputeAcceleration();
		
		$oIterator = new RelationTypeIterator($this, 'Node');
		foreach($oIterator as $sId => $oNode)
		{
			$vx = $attenuation * $oNode->GetProperty('vx') + $M * $oNode->GetProperty('ax');
			$vy = $attenuation * $oNode->GetProperty('vy') + $M * $oNode->GetProperty('ay');
			
			$oNode->x += $dt * $vx;
			$oNode->y += $dt * $vy;

			$oNode->SetProperty('vx', $vx);
			$oNode->SetProperty('vy', $vy);
//echo "<p>$sId - V($vx, $vy)</p>\n";
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
	
	function RenderAsRaphael(WebPage $oP, $sId = null, $bContinue = false)
	{
		if ($sId == null)
		{
			$sId = 'graph';
		}
		$aBB = $this->GetBoundingBox();
		$oP->add('<div id="'.$sId.'" class="simple-graph"></div>');
		$oP->add_ready_script("var oGraph = $('#$sId').simple_graph({xmin: {$aBB['xmin']}, xmax: {$aBB['xmax']}, ymin: {$aBB['ymin']}, ymax: {$aBB['ymax']} });");
		
		$oIterator = new RelationTypeIterator($this, 'Node');
		foreach($oIterator as $sId => $oNode)
		{
			$aNode = $oNode->GetForRaphael();
			$sJSNode = json_encode($aNode);
			$oP->add_ready_script("oGraph.simple_graph('add_node', $sJSNode);");
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
			$sJSEdge = json_encode($aEdge);
			$oP->add_ready_script("oGraph.simple_graph('add_edge', $sJSEdge);");
		}
		
		$oP->add_ready_script("oGraph.simple_graph('draw');");
	}

	function RenderAsPDF(WebPage $oP, $sTitle = 'Untitled', $sPageFormat = 'A4', $sPageOrientation = 'P')
	{
		require_once(APPROOT.'lib/tcpdf/tcpdf.php');
		$oPdf = new TCPDF($sPageOrientation, 'mm', $sPageFormat, true, 'UTF-8', false);
		
		// set document information
		$oPdf->SetCreator(PDF_CREATOR);
		$oPdf->SetAuthor('iTop');
		$oPdf->SetTitle($sTitle);
		
		$oPdf->setFontSubsetting(true);
		
		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.
		$oPdf->SetFont('dejavusans', '', 14, '', true);
		
		// set auto page breaks
		$oPdf->SetAutoPageBreak(false);
		
		// Add a page
		// This method has several options, check the source code documentation for more information.
		$oPdf->AddPage();
		
		$aBB = $this->GetBoundingBox();
		//$this->Translate(-$aBB['xmin'], -$aBB['ymin']);
		if ($sPageOrientation == 'P')
		{
			// Portrait mode
			$fHMargin = 10; // mm
			$fVMargin = 15; // mm
		}
		else
		{
			// Landscape mode
			$fHMargin = 15; // mm
			$fVMargin = 10; // mm
		}
		
		$fPageW = $oPdf->getPageWidth() - 2 * $fHMargin;
		$fPageH = $oPdf->getPageHeight() - 2 * $fVMargin;
		
		$w = $aBB['xmax'] - $aBB['xmin']; 
		$h = $aBB['ymax'] - $aBB['ymin'] + 10; // Extra space for the labels which may appear "below" the icons
		
		$fScale = min($fPageW / $w, $fPageH / $h);
		$dx = ($fPageW - $fScale * $w) / 2;
		$dy = ($fPageH - $fScale * $h) / 2;
		
		$this->Translate(($fHMargin + $dx)/$fScale, ($fVMargin + $dy)/$fScale);
		
		$oIterator = new RelationTypeIterator($this, 'Edge');
		foreach($oIterator as $sId => $oEdge)
		{
			$oEdge->RenderAsPDF($oPdf, $fScale);
		}

		$oIterator = new RelationTypeIterator($this, 'Node');
		foreach($oIterator as $sId => $oNode)
		{
			$oNode->RenderAsPDF($oPdf, $fScale);
		}
		
		$oP->add($oPdf->Output('iTop.pdf', 'S'));	
	}
	
}