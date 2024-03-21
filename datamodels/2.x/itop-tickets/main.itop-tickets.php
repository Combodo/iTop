<?php
// Copyright (C) 2010-2023 Combodo SARL
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
use Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\AjaxTab;
use Combodo\iTop\Application\WebPage\WebPage;


/**
 * Base class for computing TTO or TTR on a ticket
 */
class ResponseTicketSLT
{
	/**
	 * Determines the shortest SLT, for this ticket, for the given metric. Returns null is no SLT was found
	 * @param string $sMetric Type of metric 'TTO', 'TTR', etc as defined in the SLT class
	 * @return hash Array with 'SLT' => name of the SLT selected, 'value' => duration in seconds of the SLT metric, null if no SLT applies to this ticket
	 */
	protected static function ComputeSLT($oTicket, $sMetric = 'TTO')
	{
		$iDeadline = null;
		if (MetaModel::IsValidClass('SLT'))
		{
			$sType=get_class($oTicket);
			if ($sType == 'Incident')
			{
				$sRequestType = 'incident';
			}
			else
			{
				$sRequestType = $oTicket->Get('request_type');
			}

			$aArgs = $oTicket->ToArgs();
			$aArgs['metric'] = $sMetric;
			$aArgs['request_type'] = $sRequestType;

			//echo "<p>Managing:".$sMetric."-".$this->Get('request_type')."-".$this->Get('importance')."</p>\n";
			$oSLTSet = new DBObjectSet(DBObjectSearch::FromOQL(RESPONSE_TICKET_SLT_QUERY),
						array(),
						$aArgs
						);

			$iMinDuration = PHP_INT_MAX;
			$sSLTName = '';

			while($oSLT = $oSLTSet->Fetch())
			{
				$iDuration = (int)$oSLT->Get('value');
				$sUnit = $oSLT->Get('unit');
				switch($sUnit)
				{
					case 'days':
					$iDuration = $iDuration * 24; // 24 hours in 1 days
					// Fall though

					case 'hours':
					$iDuration = $iDuration * 60; // 60 minutes in 1 hour
					// Fall though

					case 'minutes':
					$iDuration = $iDuration * 60;
				}
				if ($iDuration < $iMinDuration)
				{
					$iMinDuration = $iDuration;
					$sSLTName = $oSLT->GetName();
				}
			}
			if ($iMinDuration == PHP_INT_MAX)
			{
				$iDeadline = null;
			}
			else
			{
				// Store $sSLTName to keep track of which SLT has been used
				$iDeadline = $iMinDuration;
			}
		}
		return $iDeadline;

	}
}

/**
 * Compute the TTO of a ticket - null if the class 'SLT' does not exist
 */
class ResponseTicketTTO extends ResponseTicketSLT implements iMetricComputer
{
	public static function GetDescription()
	{
		return "Time to own a ticket";
	}

	public function ComputeMetric($oObject)
	{
		$iRes = $this->ComputeSLT($oObject, 'TTO');
		return $iRes;
	}
}

/**
 * Compute the TTR of a ticket - null if the class 'SLT' does not exist
 */
class ResponseTicketTTR extends ResponseTicketSLT implements iMetricComputer
{
	public static function GetDescription()
	{
		return "Time to resolve a ticket";
	}

	public function ComputeMetric($oObject)
	{
		$iRes = $this->ComputeSLT($oObject, 'TTR');
		return $iRes;
	}
}


class _Ticket extends cmdbAbstractObject
{

	/**
	 * @param $iMaxDepth maximum depth of impact analysis
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public function UpdateImpactedItems($iMaxDepth=10)
	{
		require_once(APPROOT.'core/displayablegraph.class.inc.php');
		/** @var ormLinkSet $oContactsSet */
		$oContactsSet = $this->Get('contacts_list');
		$aCIsToImpactCode = array();
		$aSources = array();
		$aExcluded = array();
		if (MetaModel::IsValidClass('FunctionalCI'))
		{
			/** @var ormLinkSet $oCIsSet */
			$oCIsSet = $this->Get('functionalcis_list');
			foreach ($oCIsSet as $oLink)
			{
				$iKey = $oLink->Get('functionalci_id');
				$aCIsToImpactCode[$iKey] = array('link' => $oLink->GetKey(), 'code' => $oLink->Get('impact_code'));
				if ($oLink->Get('impact_code') == 'manual')
				{
					$oObj = MetaModel::GetObject('FunctionalCI', $iKey);
					$aSources[$iKey] = $oObj;
				}
				else if ($oLink->Get('impact_code') == 'not_impacted')
				{
					$oObj = MetaModel::GetObject('FunctionalCI', $iKey);
					$aExcluded[] = $oObj;
				}
			}

		}

		$aContactsToRoleCode = array();
		foreach ($oContactsSet as $oLink)
		{
			$iKey = $oLink->Get('contact_id');
            $aContactsToRoleCode[$iKey] = array('link' => $oLink->GetKey(), 'code' => $oLink->Get('role_code'));
			if ($oLink->Get('role_code') == 'do_not_notify')
			{
				$oObj = MetaModel::GetObject('Contact', $iKey);
				$aExcluded[] = $oObj;
			}
		}
		
		$sContextKey = 'itop-tickets/relation_context/'.get_class($this).'/impacts/down';
		$aContextDefs = DisplayableGraph::GetContextDefinitions($sContextKey, true, array('this' => $this));
		$aDefaultContexts = array();
		foreach($aContextDefs as $sKey => $aDefinition)
		{
			// Add the default context queries to the computation
			if (array_key_exists('default', $aDefinition) && ($aDefinition['default'] == 'yes'))
			{
				$aDefaultContexts[] = $aDefinition['oql'];
			}
		}
		// Merge the directly impacted items with the "new" ones added by the "context" queries
        $aGraphObjects = array();
        $oRawGraph = MetaModel::GetRelatedObjectsDown('impacts', $aSources, $iMaxDepth, true /* bEnableRedundancy */, $aExcluded);
        $oIterator = new RelationTypeIterator($oRawGraph, 'Node');
        foreach ($oIterator as $oNode)
        {
            // Any object node reached AND different from a source will do
            if ( ($oNode instanceof RelationObjectNode) && ($oNode->GetProperty('is_reached')) && (!$oNode->GetProperty('source')) )
            {
                $this->StoreComputedObject($aGraphObjects, $oNode->GetProperty('object'));
            }
        }
        if (count($aDefaultContexts) > 0)
		{
			$oAnnotatedGraph = MetaModel::GetRelatedObjectsDown('impacts', $aSources, $iMaxDepth, true /* bEnableRedundancy */, $aExcluded, $aDefaultContexts);
			$oIterator = new RelationTypeIterator($oAnnotatedGraph, 'Node');
			foreach ($oIterator as $oNode)
			{
                // Only pick the nodes which are NOT impacted by a context root cause, and merge them in the list
                if (($oNode instanceof RelationObjectNode) && ($oNode->GetProperty('is_reached')) && (!$oNode->GetProperty('source')) && ($oNode->GetProperty('context_root_causes', null) == null))
                {
                    $this->StoreComputedObject($aGraphObjects, $oNode->GetProperty('object'));
                }
			}
		}

		// Remove unnecessary "computed" CIs and Contacts
        foreach($aCIsToImpactCode as $iKey => $aCode)
        {
            if (($aCode['code'] == 'computed') && (!isset($aGraphObjects['FunctionalCI']) || (!array_key_exists($iKey, $aGraphObjects['FunctionalCI']))))
            {
                $oCIsSet->RemoveItem($aCode['link']);
            }
        }
        foreach($aContactsToRoleCode as $iKey => $aCode)
        {
            if (($aCode['code'] == 'computed') && (!isset($aGraphObjects['Contact']) || (!array_key_exists($iKey, $aGraphObjects['Contact']))))
            {
                $oContactsSet->RemoveItem($aCode['link']);
            }
        }

        // Add new nodes
		foreach ($aGraphObjects as $sRootClass => $aObjects)
		{
			switch ($sRootClass)
			{
				case 'FunctionalCI':
				// Only FunctionalCIs which are not already linked to the ticket
                foreach($aObjects as $iKey => $oObj)
                {
                    if (!array_key_exists($iKey, $aCIsToImpactCode))
                    {
                        $oNewLink = new lnkFunctionalCIToTicket();
                        $oNewLink->Set('functionalci_id', $iKey);
                        $oNewLink->Set('impact_code', 'computed');
                        $oCIsSet->AddItem($oNewLink);
                    }
                }
				break;

				case 'Contact':
				// Only link Contacts which are not already linked to the ticket
                foreach($aObjects as $iKey => $oObj)
                {
                    if (!array_key_exists($iKey, $aContactsToRoleCode))
                    {
                        $oNewLink = new lnkContactToTicket();
                        $oNewLink->Set('contact_id', $iKey);
                        $oNewLink->Set('role_code', 'computed');
                        $oContactsSet->AddItem($oNewLink);
                    }
                }
				break;
			}
		}
		if (MetaModel::IsValidClass('FunctionalCI'))
		{
			$this->Set('functionalcis_list', $oCIsSet);
		}
		$this->Set('contacts_list', $oContactsSet);
	}

	private function StoreComputedObject(&$aGraphObjects, $oObj)
    {
        $iKey = $oObj->GetKey();
        $sRootClass = MetaModel::GetRootClass(get_class($oObj));
        $aGraphObjects[$sRootClass][$iKey] = $oObj;
    }

	public function DisplayBareRelations(WebPage $oPage, $bEditMode = false)
	{
		parent::DisplayBareRelations($oPage, $bEditMode);
		// Display the impact analysis for tickets not in 'closed' or 'resolved' status... and not in edition
		if ((!$bEditMode) && (!in_array($this->Get('status'), array('resolved', 'closed'))))
		{
			$oPage->AddAjaxTab('Ticket:ImpactAnalysis',
				utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php?operation=ticket_impact&class='.get_class($this).'&id='.$this->GetKey(),
				true, null, AjaxTab::ENUM_TAB_PLACEHOLDER_MISC);
		}
	}
}
