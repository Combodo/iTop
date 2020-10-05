<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/itopwebpage.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');

require_once(APPROOT.'/application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(); // Check user rights and prompt if needed
ApplicationMenu::CheckMenuIdEnabled('DataModelMenu');

/**
 * Helper for this page -> link to a class
 */
function MakeClassHLink($sClass, $sContext)
{
	return "<a href=\"schema.php?operation=details_class&class=$sClass{$sContext}\" title=\"".html_entity_decode(MetaModel::GetClassDescription($sClass),
			ENT_QUOTES,
			'UTF-8')."\"><span class=\"attrLabel\">".MetaModel::GetName($sClass)."</span> <span class=\"parenthesis\">(</span><span class=\"attrCode\">".$sClass."</span><span class=\"parenthesis\">)</span></a>";
}

/**
 * Helper for this page -> link to a class
 */
function MakeRelationHLink($sRelCode, $sContext)
{
	$sDesc = MetaModel::GetRelationDescription($sRelCode);

	return "<a href=\"schema.php?operation=details_relation&relcode=$sRelCode{$sContext}\" title=\"$sDesc\">".$sRelCode."</a>";
}

/**
 * Helper for the global list and the details of a given class
 */
function DisplaySubclasses($oPage, $sClass, $sContext)
{
	$aChildClasses = MetaModel::EnumChildClasses($sClass);
	if (count($aChildClasses) != 0)
	{
		$oPage->add("<ul>\n");
		$aOrderedClasses = array();
		foreach ($aChildClasses as $sClassName)
		{
			// Skip indirect childs, they will be handled somewhere else
			if (MetaModel::GetParentPersistentClass($sClassName) == $sClass)
			{
				$aOrderedClasses[$sClassName] = MetaModel::GetName($sClassName);
			}
		}
		// Sort on the display name
		asort($aOrderedClasses);
		foreach ($aOrderedClasses as $sClassName => $sDisplayName)
		{
			// Skip indirect childs, they will be handled somewhere else
			if (MetaModel::GetParentPersistentClass($sClassName) == $sClass)
			{
				$oPage->add("<li class=\"open\">".MakeClassHLink($sClassName, $sContext)."\n");
				DisplaySubclasses($oPage, $sClassName, $sContext);
				$oPage->add("</li>\n");
			}
		}
		$oPage->add("</ul>\n");
	}
}

/**
 * Helper for the lifecycle details of a given class
 */
function DisplayLifecycle($oPage, $sClass)
{
	$sStateAttCode = MetaModel::GetStateAttributeCode($sClass);
	if (empty($sStateAttCode))
	{
		$oPage->p(Dict::S('UI:Schema:NoLifeCyle'));
	}
	else
	{
		$aStates = MetaModel::EnumStates($sClass);
		$aStimuli = MetaModel::EnumStimuli($sClass);
		$oPage->add("<img id=\"img-lifecycle\" attr=\"$sClass lifecycle graph\" src=\"".utils::GetAbsoluteUrlAppRoot()."pages/graphviz.php?class=$sClass\">\n");
		$oPage->add_ready_script(
			<<<EOF
			$("#img-lifecycle").attr('href',$("#img-lifecycle").attr('src'));
			$("#img-lifecycle").magnificPopup({type: 'image', closeOnContentClick: true});
EOF

		);
		$oPage->add("<button id=\"lifecycleOpenAll\">Open All</button> <button id=\"lifecycleCloseAll\">Close All</button>");
		$oPage->add_ready_script(
			<<<EOF
			$("#lifecycleOpenAll").on('click',function(){
				$('#LifeCycleList').find('.expandable-hitarea').click();
				$('#LifeCycleAttrOptList').find('.expandable-hitarea').click();
			});	
				
			$("#lifecycleCloseAll").on('click',function(){
				$('#LifeCycleList').find('.collapsable-hitarea').click();
				$('#LifeCycleAttrOptList').find('.collapsable-hitarea').click();
			});	
EOF
		);
		$oPage->add("<h3>".Dict::S('UI:Schema:LifeCycleTransitions')."</h3>\n");
		$oPage->add("<ul id=\"LifeCycleList\" >\n");
		foreach ($aStates as $sStateCode => $aStateDef)
		{
			$sStateLabel = MetaModel::GetStateLabel($sClass, $sStateCode);
			$sStateDescription = MetaModel::GetStateDescription($sClass, $sStateCode);
			$oPage->add("<li class=\"closed\"><span class=\"attrLabel\">$sStateLabel </span><span style=\"color:grey;\"><span class=\"parenthesis\">(</span><span class=\"attrCode\">$sStateCode</span><span class=\"parenthesis\">) </span>$sStateDescription</span>\n");
			$oPage->add("<ul class=\"closed\">\n");
			foreach (MetaModel::EnumTransitions($sClass, $sStateCode) as $sStimulusCode => $aTransitionDef)
			{
				$sStimulusLabel = $aStimuli[$sStimulusCode]->GetLabel();
				$sTargetState = $aTransitionDef['target_state'];
				$sTargetStateLabel = MetaModel::GetStateLabel($sClass, $sTargetState);
				if (count($aTransitionDef['actions']) > 0)
				{
					$aActionsDesc = array();
					foreach ($aTransitionDef['actions'] as $actionHandler)
					{
						if (is_string($actionHandler))
						{
							$aActionsDesc[] = $actionHandler;
						}
						else
						{
							$aParamsDesc = array();
							foreach ($actionHandler['params'] as $aParamData)
							{
								$aParamsDesc[] = $aParamData['type'].':'.$aParamData['value'];
							}
							$aActionsDesc[] = $actionHandler['verb'].'('.implode(', ', $aParamsDesc).')';
						}
					}
					$sActions = " <em>(".implode(', ', $aActionsDesc).")</em>";
				}
				else
				{
					$sActions = "";
				}

				$oPage->add("<li class=\"closed\"><span class=\"attrLabel\" title=\"code: $sStimulusCode\" style=\"color:red;font-weight:bold;\">$sStimulusLabel</span>
								<span style=\"color:grey;\"><span class=\"parenthesis\">(</span><span class=\"attrCode\">$sStimulusCode</span><span class=\"parenthesis\">)</span> </span>
								=&gt;
								<span class=\"attrLabel\">$sTargetStateLabel </span><span style=\"color:grey;\"><span class=\"parenthesis\">(</span> <span class=\"attrCode\">$sTargetState</span> <span class=\"parenthesis\">)</span></span> $sActions</li>\n");
			}
			$oPage->add("</ul></li>\n");
		}
		$oPage->add("</ul>\n");
		$oPage->add("<h3>".Dict::S('UI:Schema:LifeCyleAttributeOptions')."</h3>\n");
		$oPage->add("<ul id=\"LifeCycleAttrOptList\">\n");
		foreach ($aStates as $sStateCode => $aStateDef)
		{
			$sStateLabel = MetaModel::GetStateLabel($sClass, $sStateCode);
			$sStateDescription = MetaModel::GetStateDescription($sClass, $sStateCode);
			$oPage->add("<li class=\"closed\"><span class=\"attrLabel\">$sStateLabel </span><span style=\"color:grey;\"><span class=\"parenthesis\">(</span><span class=\"attrCode\">$sStateCode</span><span class=\"parenthesis\">) </span>$sStateDescription</span>\n");
			if (count($aStates[$sStateCode]['attribute_list']) > 0)
			{
				$oPage->add("<ul>\n");
				foreach ($aStates[$sStateCode]['attribute_list'] as $sAttCode => $iOptions)
				{
					$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
					$sAttLabel = $oAttDef->GetLabel();

					$aOptions = array();
					if ($iOptions & OPT_ATT_HIDDEN)
					{
						$aOptions[] = Dict::S('UI:Schema:LifeCycleHiddenAttribute');
					}
					if ($iOptions & OPT_ATT_READONLY)
					{
						$aOptions[] = Dict::S('UI:Schema:LifeCycleReadOnlyAttribute');
					}
					if ($iOptions & OPT_ATT_MANDATORY)
					{
						$aOptions[] = Dict::S('UI:Schema:LifeCycleMandatoryAttribute');
					}
					if ($iOptions & OPT_ATT_MUSTCHANGE)
					{
						$aOptions[] = Dict::S('UI:Schema:LifeCycleAttributeMustChange');
					}
					if ($iOptions & OPT_ATT_MUSTPROMPT)
					{
						$aOptions[] = Dict::S('UI:Schema:LifeCycleAttributeMustPrompt');
					}
					if (count($aOptions))
					{
						$sOptions = implode(', ', $aOptions);
					}
					else
					{
						$sOptions = "";
					}

					$oPage->add("<li class=\"closed\"><span style=\"color:purple;font-weight=bold;\">$sAttLabel</span> $sOptions</li>\n");
				}
				$oPage->add("</ul></li>\n");
			}
			else
			{
				$oPage->p("<em>".Dict::S('UI:Schema:LifeCycleEmptyList')."</em>");
			}
		}
		$oPage->add("</ul>\n");
		$oPage->add_ready_script('$("#LifeCycleList").treeview({collapsed: true,});');
		$oPage->add_ready_script('$("#LifeCycleAttrOptList").treeview({collapsed: true,});');
	}
}


/**
 * Helper for the trigger
 */
function DisplayTriggers($oPage, $sClass)
{
	$sClassList = implode("', '", MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_ALL));
	$oSet = new CMDBObjectSet(DBObjectSearch::FromOQL("SELECT TriggerOnObject WHERE target_class IN ('$sClassList')"));
	cmdbAbstractObject::DisplaySet($oPage, $oSet, array('block_id' => 'triggers'));
}


/**
 * Display the list of classes from the business model
 */
function DisplayClassesList($oPage, $sContext)
{
	$oPage->add("<h1>".Dict::S('UI:Schema:Title')."</h1>\n");

	$oPage->add("<label for='search-model'>".Dict::S('UI:Schema:ClassFilter')."</label><br><input type=\"text\" id=\"search-model\" autofocus=\"autofocus\"/>");
	$oPage->add("<div id=\"delDataModelSearch\"> <i class=\"fas fa-times-circle\"></i></div>");
	$oPage->add("<ul id=\"ClassesList\" class=\"treeview fileview\">\n");
	$oPage->add_ready_script(
		<<<EOF
        $("#search-model").result(function(e,f,g,h){
		   	//$(this).trigger(jQuery.Event('input'));
		   	var preUrl = "?operation=details_class&class=";
			var sufUrl = "&c[menu]=DataModelMenu";
			var code = '';
		   	switch($("#displaySelector").val()){
				case 'labelandcode':
					var id = autocompleteClassLabelAndCode.indexOf(g);
					if(id != undefined)
						code = autocompleteClassCode[id];
				break;
				case 'label':
					var id = autocompleteClassLabel.indexOf(g);
					if(id != undefined)
						code = autocompleteClassCode[id];
				break;
				case 'code':
					var id = autocompleteClassCode.indexOf(g);
					if(id != undefined)
						code = autocompleteClassCode[id];
				break;
			}
			if(code != '')
				window.location = preUrl + code + sufUrl;
		});
		$("#search-model").on('input', function() {
			var search_result = [];
			$("#ClassesList").find("li").each(function(){
				if( ! ~$(this).children("a").text().toLowerCase().indexOf($("#search-model").val().toLowerCase())){
					$(this).hide();
				}
				else{
					search_result.push($(this));
				}
			});
			search_result.forEach(function(e){
				e.show();
				e.find('ul > li').show();
				e.parents().show();
			});
		});
		$("#delDataModelSearch").on ('click', function(){
			$("#search-model").val("");
			$("#search-model").trigger('input');
		});
EOF

	);
	// Get all the "root" classes for display
	$aRootClasses = array();
	foreach (MetaModel::GetClasses() as $sClassName)
	{
		if (MetaModel::IsRootClass($sClassName))
		{
			$aRootClasses[$sClassName] = MetaModel::GetName($sClassName);
		}
		elseif (MetaModel::IsStandaloneClass($sClassName))
		{
			$aRootClasses[$sClassName] = MetaModel::GetName($sClassName);
		}
		$sLabelClassName = MetaModel::GetName($sClassName);

		//Fetch classes names for autocomplete purpose
		// - Encode as JSON to escape quotes and other characters
		$sClassLabelAndCodeAsJSON = json_encode("$sLabelClassName ($sClassName)");
		$sClassLabelAsJSON = json_encode($sLabelClassName);
		$sClassCodeAsJSON = json_encode($sClassName);
		// - Push to autocomplete
		$oPage->add_script(
			<<<EOF
	autocompleteClassLabelAndCode.push($sClassLabelAndCodeAsJSON);
	autocompleteClassLabel.push($sClassLabelAsJSON);
	autocompleteClassCode.push($sClassCodeAsJSON);
EOF
		);
	}
	// Sort them alphabetically on their display name
	asort($aRootClasses);
	foreach ($aRootClasses as $sClassName => $sDisplayName)
	{
		if (MetaModel::IsRootClass($sClassName))
		{
			$oPage->add("<li class=\"open\">".MakeClassHLink($sClassName, $sContext)."\n");
			DisplaySubclasses($oPage, $sClassName, $sContext);
			$oPage->add("</li>\n");
		}
		elseif (MetaModel::IsStandaloneClass($sClassName))
		{
			$oPage->add("<li>".MakeClassHLink($sClassName, $sContext)."</li>\n");
		}
	}
	$oPage->add("</ul>\n");
	$oPage->add_ready_script('$("#ClassesList").treeview();');
}


/**
 * Helper for the list of classes related to the given class in a graphical way
 */
function DisplayRelatedClassesGraph($oPage, $sClass)
{
	try
	{
		$bOnTheLeft = true;
		$bSkipLinkingClasses = false;
		// 1) Fetching referencing classes data
		//
		$aData = array();
		$aOrigins = array('_' => true);
		$aRefs = MetaModel::EnumReferencingClasses($sClass, $bSkipLinkingClasses);
		$sSelfReference = "false";
		if (count($aRefs) != 0)
		{
			foreach ($aRefs as $sRemoteClass => $aRemoteKeys)
			{
				foreach ($aRemoteKeys as $sExtKeyAttCode => $oExtKeyAttDef)
				{
					if ($sRemoteClass != $sClass)
					{
						// ref_prefix to avoid collision between attributes labels that refer to this class and local attributes label that references other classes
						$aAttribute = array('label' => 'ref_'.$sExtKeyAttCode);
						// Test if a distant attribut exists and if it uses a link class
						if (!($oExtKeyAttDef->GetMirrorLinkAttribute() == null ? false : $oExtKeyAttDef->GetMirrorLinkAttribute() instanceof AttributeLinkedSetIndirect))
						{
							$aAttribute['related'] = $sRemoteClass;
							$aAttribute['related_icon'] = MetaModel::GetClassIcon($aAttribute['related'], false);
							$aAttribute['related_position'] = $bOnTheLeft ? -1 : 1;
							$aAttribute['relation_type'] = 0;
							$bOnTheLeft = !$bOnTheLeft; // Toggle the side
							$sOrigin = MetaModel::GetAttributeOrigin($sRemoteClass, $sExtKeyAttCode);
							$aAttribute['origin'] = $sOrigin;
							$aOrigins[$sOrigin] = true;
							$aData[$sExtKeyAttCode.$sRemoteClass] = $aAttribute;
						}
					}
				}
			}
		}

		// 2) Fetching referenced classes data
		//
		$aDataRef = array(
			array(
				'label' => $sClass,
				'icon' => MetaModel::GetClassIcon($sClass, false),
				'origin_index' => 0,
				'alphabetical_index' => 0,
				'origin' => '_',
			),
		);
		$bOnTheLeft = true;
		$aOriginsRef = array('_' => true);
		foreach (MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
		{
			$aAttribute = array('label' => $sAttCode);
			if ($oAttDef->IsLinkSet())
			{
				if ($oAttDef->IsIndirect())
				{
					$sRemoteAttDef = $oAttDef->GetExtKeyToRemote();
					$aAttribute['related'] = MetaModel::GetAttributeDef($oAttDef->GetLinkedClass(), $sRemoteAttDef)->GetTargetClass();
					$aAttribute['related_icon'] = MetaModel::GetClassIcon($aAttribute['related'], false);
					$aAttribute['related_position'] = $bOnTheLeft ? 1 : -1;
					$aAttribute['relation_type'] = 0; //
					$aAttribute['tooltip_data']['class'] = $oAttDef->GetLinkedClass();
					$aAttribute['tooltip_data']['to_remote'] = $sRemoteAttDef;
					$aAttribute['tooltip_data']['to_me'] = $oAttDef->GetExtKeyToMe();

					$bOnTheLeft = !$bOnTheLeft; // Toggle the side
				}
				else
				{
					$aAttribute['related'] = $oAttDef->GetLinkedClass();
					$aAttribute['related_icon'] = MetaModel::GetClassIcon($aAttribute['related'], false);
					$aAttribute['related_position'] = $bOnTheLeft ? 1 : -1;
					$aAttribute['relation_type'] = 1;
					$bOnTheLeft = !$bOnTheLeft; // Toggle the side
				}

			}
			else
			{
				if ($oAttDef->IsHierarchicalKey())
				{
					$aAttribute['related'] = $sClass;
					$aAttribute['related_icon'] = MetaModel::GetClassIcon($aAttribute['related'], false);
					$aAttribute['related_position'] = $bOnTheLeft ? 1 : -1;
					$aAttribute['relation_type'] = 2;
					$bOnTheLeft = !$bOnTheLeft; // Toggle the side
					$sSelfReference = "true";
				}
				else
				{
					if ($oAttDef->IsExternalKey())
					{
						$aAttribute['related'] = $oAttDef->GetTargetClass();
						$aAttribute['related_icon'] = MetaModel::GetClassIcon($aAttribute['related'], false);
						$aAttribute['related_position'] = $bOnTheLeft ? 1 : -1;
						$aAttribute['relation_type'] = 3;

						$bOnTheLeft = !$bOnTheLeft; // Toggle the side
					}
				}
			}
			if ($oAttDef->IsLinkSet() || $oAttDef->IsHierarchicalKey() || $oAttDef->IsExternalKey())
			{
				$sOrigin = MetaModel::GetAttributeOrigin($sClass, $sAttCode);

				$aAttribute['origin'] = $sOrigin;
				$aOriginsRef[$sOrigin] = true;
				$aDataRef[$sAttCode] = $aAttribute;

			}

		}


		//sort referencing data

		$aOrigins = array_keys($aOrigins);
		$idx = 0;

		$bOnTheLeft = true;
		foreach ($aData as $sAttCode => $aAttribute)
		{
			$is_also_referenced = false;
			foreach ($aDataRef as $sAttCodeRef => $aAttributeRef)
			{
				if (!empty($aDataRef[$sAttCodeRef]['related']) && ($aData[$sAttCode]['related'] == $aDataRef[$sAttCodeRef]['related']))
				{
					$is_also_referenced = true;
				}
			}
			if (!$is_also_referenced)
			{
				$aData[$sAttCode]['related_position'] = ($bOnTheLeft) ? -1 : 1;
				$bOnTheLeft = !$bOnTheLeft;
				$aData[$sAttCode]['origin_index'] = ($aData[$sAttCode]['related_position'] == -1) ? ++$idx : $idx;
			}
			else
			{
				unset($aData[$sAttCode]);
			}
		}
		ksort($aData);
		$idx = 0;
		$aFinalDataReferencing = array();
		foreach ($aData as $sAttCode => $aAttribute)
		{
			$aData[$sAttCode]['alphabetical_index'] = $aAttribute['related_position'] == 1 ? ++$idx : $idx;
			$aFinalDataReferencing[] = $aData[$sAttCode];
		}
		$sDataReferencing = json_encode($aFinalDataReferencing);
		$sOriginsReferencing = json_encode(array_keys($aOrigins));

		//sort referenced data

		$idx = 1;
		foreach ($aDataRef as $sAttCode => $aAttribute)
		{
			$aDataRef[$sAttCode]['origin_index'] = $idx++;
		}

		$idx = 1;
		$aFinalData = array();
		foreach ($aDataRef as $sAttCode => $aAttribute)
		{
			$aDataRef[$sAttCode]['alphabetical_index'] = $idx++;
			$aFinalData[] = $aDataRef[$sAttCode];
		}

		$sData = json_encode($aFinalData);

		// 3) Processing data and building graph
		//
		$oPage->add(
			<<<EOF
<div id="dataModelGraph">
<svg class="dataModelSchema" width="100%" height="800">
</svg>
</div>
EOF
		);
		$oPage->add_ready_script(
			<<<JS

var data = $sData;
var dataref = $sDataReferencing;
/**
 * sDataReferencing's data size ceil'd to the next even number
 * in order to keep the same display if classes nb is even/not even
 */
var datareflen = Math.ceil(dataref.length/2)*2;
var isSelfReferencing = $sSelfReference;

//Link that will be opened when a class is clicked
var refClassLinkpre = "?operation=details_class&class=";
var refClassLinksuf = "&c[menu]=DataModelMenu#tabbedContent_0=1";
			
var aOriginsref = $sOriginsReferencing;
		
var margins = {top: 50, left: 100 };
var cellHeight = 24;
var cellWidth =  Math.max(3*48, 8*d3.max(data, function(d) { return d.label.length; }));
var relatedCellWidth = Math.max(3*48, Math.max(8*d3.max(data, function(d) { return d.related ? d.related.length : 0; }) || 0,8*d3.max(dataref, function(d) { return d.related ? d.related.length : 0; }) ) || 0);
var gap = 70;
var schema = d3.select(".dataModelSchema")
    .attr("height", cellHeight * (data.length + dataref.length + (1 + dataref.length%2)*1.5) + 2*margins.top);

var divD3 = d3.select("#dataModelGraph").append("div")
	.attr("class","tooltipD3")
	.style("opacity",0);

// 1) Horn construction (top lines used to display referencing classes which doesn't have a linkset attribute)
//
if(dataref.length > 1)
{
schema.append("path")
		.attr("d", "M"+(margins.left + relatedCellWidth + gap + cellWidth*0.75)+" "+ (margins.top + cellHeight + cellHeight*(datareflen+1.5)) +" l 0 "+ cellHeight*-datareflen+"")
		.attr("fill", "transparent")
		.attr("stroke", "black")
		.attr("stroke-linecap", "round")
		.attr("stroke-width", 2);
}
if(dataref.length > 0)
{
schema.append("path")
		.attr("d", "M"+(margins.left + relatedCellWidth + gap + cellWidth*0.25)+" "+ (margins.top + cellHeight + cellHeight*(datareflen+1.5)) +" l 0 "+ cellHeight*-datareflen+"")
		.attr("fill", "transparent")
		.attr("stroke", "black")
		.attr("stroke-linecap", "round")
		.attr("stroke-width", 2);
}

//loop + arrow to show that a class has a hierarchical attribute
if(isSelfReferencing == true)
{
	schema.append("path")
			.attr("d", "M"+(margins.left + relatedCellWidth + gap + cellWidth/1.9)+" "+ (margins.top  + cellHeight*(datareflen+1.5))+" a 20 20 0 1 0 20 0  m-10 0l-5 3 m5 -3 l-5 -3")
			.attr("id", "selfreferencing")
			.attr("fill", "transparent")
			.attr("stroke", "black")
			.attr("stroke-linecap", "round")
			.attr("stroke-width", 2)
			.attr("transform", "rotate(95, "+ (margins.left + relatedCellWidth + gap + cellWidth/1.9) + ", " + ((margins.top  + cellHeight*(datareflen+1.5))+10)+")");
}

// 2) Classes linked to horns (classes referencing us)
//
var fieldref = schema.selectAll("g")
    .data(dataref, function(d) { return d.label + d.related } )
    .enter().append("g")
    .attr("transform", function(d, i) { return "translate(" + (margins.left + relatedCellWidth + gap + cellWidth/2) + "," + (margins.top + d.origin_index*cellHeight*2) + ")"; });


fieldref.filter(function(d) {
	return (d.related != null);
}).append("a")
	.attr("xlink:href",function(d){ return refClassLinkpre + d.related + refClassLinksuf})
	.append("rect")
		.attr("x", -relatedCellWidth/2)
		.attr("width", relatedCellWidth)
		.attr("height", cellHeight)
		.attr("fill", "#fff")
		.attr("stroke", "#000")
		.attr("stroke-width", 1)
		.attr("transform", function(d, i) { return "translate("+ d.related_position*(relatedCellWidth/2+cellWidth/2+gap) +", 0)"; });

fieldref.filter(function(d) {
	return (d.related != null);
}).append("a")
	.attr("xlink:href",function(d){ return refClassLinkpre + d.related + refClassLinksuf })
	.append("text")
		.attr("x", 0)
		.attr("y", cellHeight / 2)
		.attr("dy", ".35em")
		.text(function(d) { return d.related ? d.related : ''; })
		.attr("transform", function(d, i) { return "translate("+ (d.related_position*(relatedCellWidth/2+cellWidth/2+gap)) +", 0)"; });
	
fieldref.filter(function(d) {
	return (d.related != null);
}).append("path")
    .attr("d", "M"+(cellWidth/2 - cellWidth*0.25)+" "+cellHeight/2+" h"+(gap-2 + cellWidth*0.25))
	.attr("fill", "transparent")
	.attr("stroke", "black")
	.attr("stroke-linecap", "round")
	.attr("stroke-width", 2)
	.attr("transform", function(d, i) { return (d.related_position < 0) ? "rotate(180, 0, "+(cellHeight/2)+")" : ""});
		
fieldref.filter(function(d) {
	return (d.related != null);
}).append("path")
    .attr("d", "M"+cellWidth/1.9*-1+" "+cellHeight/2+" m-10 0l-5 3 m5 -3 l-5 -3")
	.attr("fill", "transparent")
	.attr("stroke", "black")
	.attr("stroke-linecap", "round")
	.attr("stroke-width", 2)
	.attr("transform", function(d, i) { return (d.related_position < 0) ? "rotate(360, 0, "+(cellHeight/2)+")" : "rotate(180, 0, "+(cellHeight/2)+")"});
		
fieldref.filter(function(d) {
	return (d.related != null);
}).append("svg:image")
   .attr("x", -relatedCellWidth/2)
   .attr("width", cellHeight)
	.attr("height", cellHeight)
    .attr("xlink:href", function(d, i) { return d.related_icon })
	.attr("transform", function(d, i) { return "translate("+ (d.related_position*(relatedCellWidth/2+cellWidth/2+gap) - 12)+", -" + cellHeight/2+" )"; });	


// 3) Main class rectangle and attributes rectangles
//
var field = schema.selectAll("g")
    .data(data, function(d) { return d.label } )
    .enter().append("g")
    .attr("transform", function(d, i) { return "translate(" + (margins.left + relatedCellWidth + gap + cellWidth/2) + "," + (margins.top + (datareflen+1.5)*cellHeight + d.origin_index*cellHeight) + ")"; });

field.append("rect")
    .attr("x", -cellWidth/2)
    .attr("width", cellWidth)
    .attr("class", function(d, i){return (d.relation_type == 2 ? "selfattr" : "extattr");}) 
	.attr("height", cellHeight)
	.attr("fill", "#fff")
	.attr("stroke", "#000")
	.attr("stroke-width", 1);
			
field.append("text")
    .attr("x", 0)
    .attr("y", cellHeight / 2)
    .attr("dy", ".35em")
    .text(function(d) { return d.label; });
    
field.append("text")
    .attr("x", function(d){ return 7*d.label.length/2 })
    .attr("y", cellHeight / 2)
    .attr("dy", ".35em")
    .attr("class", function(d, i){return (d.relation_type == 2 ? "selfattrtxt" : "");}) 
    .text(function(d) { return ((d.relation_type == 2) ? '\uf01e' : '' ); });


// 4) Classes that our main class is refering to
//
field.filter(function(d) {
	return (d.related != null) && (d.relation_type != 2);
}).append("a")
	.attr("xlink:href",function(d){ return refClassLinkpre + d.related + refClassLinksuf})
	.append("rect")
		.attr("x", -relatedCellWidth/2)
		.attr("width", relatedCellWidth)
		.attr("height", cellHeight)
		.attr("fill", "#fff")
		.attr("stroke", "#000")
		.attr("stroke-width", 1)
		.attr("transform", function(d, i) { return "translate("+ d.related_position*(relatedCellWidth/2+cellWidth/2+gap) +", 0)"; });

field.filter(function(d) {
	return (d.related != null) && (d.relation_type != 2);
}).append("a")
	.attr("xlink:href",function(d){ return refClassLinkpre + d.related + refClassLinksuf})
	.append("text")
		.attr("x", 0)
		.attr("y", cellHeight / 2)
		.attr("dy", ".35em")
		.text(function(d) { return d.related ? d.related : ''; })
		.attr("transform", function(d, i) { return "translate("+ (d.related_position*(relatedCellWidth/2+cellWidth/2+gap)) +", 0)"; });

field.filter(function(d) {
	return (d.related != null) && (d.relation_type != 2);
}).append("path")
    .attr("d", "M"+cellWidth/2+" "+cellHeight/2+" h"+(gap-2))
	.attr("fill", "transparent")
	.attr("stroke", "black")
	.attr("stroke-linecap", "round")
	.attr("stroke-width", 2)
	.attr("transform", function(d, i) { return (d.related_position < 0) ? "rotate(180, 0, "+(cellHeight/2)+")" : ""});
		
		
field.filter(function(d) {
	return (d.related != null) && (d.relation_type == 3 || d.relation_type == 0);
}).append("path")
    .attr("d","M"+ (gap - 2 + cellWidth/2) +" "+cellHeight/2+" m-10 0l-5 3 m5 -3 l-5 -3")
	.attr("fill", "transparent")
	.attr("stroke", "black")
	.attr("stroke-linecap", "round")
	.attr("stroke-width", 2)
	.attr("transform", function(d, i) { return (d.related_position < 0) ? "rotate(180, 0, "+(cellHeight/2)+")" : ""});
		
field.filter(function(d) {
	return (d.related != null) && (d.relation_type == 1 || d.relation_type == 0);
}).append("path")
    .attr("d", "M"+cellWidth/1.9*-1+" "+cellHeight/2+" m-10 0l-5 3 m5 -3 l-5 -3")
	.attr("fill", "transparent")
	.attr("stroke", "black")
	.attr("stroke-linecap", "round")
	.attr("stroke-width", 2)
	.attr("transform", function(d, i) { return (d.related_position < 0) ? "rotate(360, 0, "+(cellHeight/2)+")" : "rotate(180, 0, "+(cellHeight/2)+")"});				
			
field.filter(function(d) {
	return (d.related != null) && (d.relation_type == 0);
}).append("circle")
    .attr("r", 5)
    .attr("cy", cellHeight/2)
	.on('mouseover',function(d){
		divD3.transition()
			.duration(200)
			.style("opacity","1");
		divD3.style("left", (d3.event.pageX - 7*d['tooltip_data']['class'].length/2) + "px");
		divD3.style("top", (d3.event.pageY - 65) + "px");
		divD3.html( '<div id="tooltipD3_top">' + d['tooltip_data']['class'] + '</div><span id="tooltipD3_left"> <i class="fas fa-caret-left"></i> '
		 			+  ( (d.related_position < 0) ? d['tooltip_data']['to_remote'] : d['tooltip_data']['to_me'] ) +  '</span><span id="tooltipD3_right"> <br>'
		 			+ ( (d.related_position < 0) ? d['tooltip_data']['to_me'] : d['tooltip_data']['to_remote'] ) + ' <i class="fas fa-caret-right"></i></span>');
	})
	.on('mouseout',function(d){
		divD3.transition()
			.duration(500)
			.style("opacity","0");
	})
	.attr("transform", function(d, i) { return "translate("+ d.related_position*(cellWidth+gap+2)/2 +", 0)"; });
			

			
field.filter(function(d) {
	return (d.related != null) && (d.relation_type != 2);
}).append("svg:image")
   .attr("x", -relatedCellWidth/2)
   .attr("width", cellHeight)
	.attr("height", cellHeight)
    .attr("xlink:href", function(d, i) { return d.related_icon })
	.attr("transform", function(d, i) { return "translate("+ (d.related_position*(relatedCellWidth/2+cellWidth/2+gap) - 12)+", -" + cellHeight/2+" )"; });
						
field.append("rect")
    .attr("x", -cellWidth/2 - 5)
    .attr("width", 5)
	.attr("height", cellHeight)
	.attr("fill", function(d) { return aColors(aOrigins.indexOf(d.origin)); } )
	.attr("stroke-width", 0)
	.attr("class","liseret");
			
field.filter(function(d) {
	return (d.icon != null);
}).append("svg:image")
    .attr("x", -cellWidth/2)
    .attr("width", 36)
	.attr("height", 36)
    .attr("xlink:href", function(d, i) { return d.icon })
	.attr("transform", "translate(-12, -24)");
			
JS
		);
	}
	catch (Exception $e)
	{
		$oPage->p('<b>'.Dict::Format('UI:RunQuery:Error', $e->getMessage()).'</b>');
	}
}

/**
 * Display the details of a given class of objects
 *
 * @param \iTopWebPage $oPage
 * @param string $sClass
 * @param string $sContext
 *
 * @throws \CoreException
 */
function DisplayClassDetails($oPage, $sClass, $sContext)
{
	DisplayClassHeader($oPage, $sClass);
	$aParentClasses = array();
	foreach (MetaModel::EnumParentClasses($sClass) as $sParentClass)
	{
		$aParentClasses[] = MakeClassHLink($sParentClass, $sContext);
	}
	if (count($aParentClasses) > 0)
	{
		$sParents = implode(' &gt;&gt; ', $aParentClasses)." &gt;&gt; <b>$sClass</b>";
	}
	else
	{
		$sParents = '';
	}
	$oPage->p("[<a href=\"schema.php?operation=list{$sContext}\">".Dict::S('UI:Schema:AllClasses')."</a>] $sParents");

	if (MetaModel::HasChildrenClasses($sClass))
	{
		$oPage->add("<ul id=\"ClassHierarchy\">");
		$oPage->add("<li class=\"closed\">".$sClass."\n");
		DisplaySubclasses($oPage, $sClass, $sContext);
		$oPage->add("</li>\n");
		$oPage->add("</ul>\n");
		$oPage->add_ready_script('$("#ClassHierarchy").treeview();');
	}
	$oPage->p('');
	$oPage->add("</div>");

	$oPage->AddTabContainer('details');
	$oPage->SetCurrentTabContainer('details');
	// List the attributes of the object
	$aForwardChangeTracking = MetaModel::GetTrackForwardExternalKeys($sClass);
	$aDetails = array();

	$aOrigins = array();
	foreach (MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
	{
		if ($oAttDef->IsExternalKey())
		{
			$sValue = Dict::Format('UI:Schema:ExternalKey_To', MakeClassHLink($oAttDef->GetTargetClass(), $sContext));
			if (array_key_exists($sAttCode, $aForwardChangeTracking))
			{
				$oLinkSet = $aForwardChangeTracking[$sAttCode];
				$sRemoteClass = $oLinkSet->GetHostClass();
				$sValue = $sValue."<span title=\"Forward changes to $sRemoteClass\">*</span>";
			}
		}
		elseif ($oAttDef->IsLinkSet())
		{
			$sValue = MakeClassHLink($oAttDef->GetLinkedClass(), $sContext);
		}
		else
		{
			$sValue = $oAttDef->GetDescription();
		}
		$sType = get_class($oAttDef);
		$sTypeDict = $oAttDef->GetType();
		$sTypeDesc = $oAttDef->GetTypeDesc();

		$sOrigin = MetaModel::GetAttributeOrigin($sClass, $sAttCode);
		$aOrigins[$sOrigin] = true;
		$sAllowedValues = "";
		$sMoreInfo = "";
		$sDefaultNullValue = '""';
		$aCols = array();
		foreach ($oAttDef->GetSQLColumns() as $sCol => $sFieldDesc)
		{
			$aCols[] = "$sCol: $sFieldDesc";
		}
		if (count($aCols) > 0)
		{

			$aMoreInfo = array();
			if ($oAttDef->IsNullAllowed())
			{
				$aMoreInfo[] = Dict::S('UI:Schema:NullAllowed');
				$sDefaultNullValue = (!is_null($oAttDef->GetNullValue()) ? $oAttDef->GetNullValue() : null);
				if (!is_null($sDefaultNullValue) && !is_string($sDefaultNullValue))
				{
					$sDefaultNullValue = json_encode($sDefaultNullValue);
				}
				$sDefaultNullValue = (!is_null($sDefaultNullValue) ? json_encode(Dict::Format('UI:Schema:DefaultNullValue',
					$sDefaultNullValue)) : '""');
			}
			else
			{
				$aMoreInfo[] = Dict::S('UI:Schema:NullNotAllowed');
			}
			if ($oAttDef->GetDefaultValue())
			{
				$sDefaultValue = $oAttDef->GetDefaultValue();
				if (!is_string($sDefaultValue))
				{
					$sDefaultValue = json_encode($sDefaultValue);
				}
				$aMoreInfo[] = Dict::Format("UI:Schema:Default_Description", $sDefaultValue);
			}
			$sMoreInfo .= implode(', ', $aMoreInfo);
		}
		$sAttrCode = $oAttDef->GetCode();
		$sIsEnumValues = 'false';
		$sAllowedValuesEscpd = '""';
		if ($oAttDef instanceof AttributeEnum)
		{
			// Display localized values for the enum (which depend on the localization provided by the class)
			$aLocalizedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode, array());
			$aDescription = array();
			foreach ($aLocalizedValues as $val => $sDisplay)
			{
				$aDescription[] = "<span class=\"attrLabel\">".$sDisplay."</span>  <span class=\"parenthesis\">(</span><span class=\"attrCode\">".$val."</span><span class=\"parenthesis\">)</span>";
			}
			$sAllowedValues = implode(', ', $aDescription);
			$sIsEnumValues = 'true';
		}
		elseif (is_object($oAllowedValuesDef = $oAttDef->GetValuesDef()))
		{
			$sAllowedValues = str_replace("Filter: ", "", $oAllowedValuesDef->GetValuesDescription());
			$sAllowedValuesEscpd = json_encode($sAllowedValues);

			$sFilterURL = urlencode($sAllowedValues);
			$sAllowedValues = "<span id=\"values".$sAttrCode."\"><a href=\"run_query.php?expression=".$sFilterURL."\">⚵</a>".Dict::S('UI:Schema:Attribute/Filter')."</span>";
		}
		else
		{
			$sAllowedValues = '';
		}
		$sAttrValueEscpd = json_encode($sValue);
		$sAttrTypeDescEscpd = json_encode($sTypeDesc);
		$sAttrOriginEscpd = json_encode($sOrigin);

		$aDetails[] = array(
			'code' => "<span id=\"attr".$sAttrCode."\"><span class=\"attrLabel\">".$oAttDef->GetLabel()."</span> <span class=\"parenthesis\">(</span><span class=\"attrCode\">".$oAttDef->GetCode()."</span><span class=\"parenthesis\">)</span></span>",
			'type' => "<span id=\"type".$sAttrCode."\"><span class=\"attrLabel\">".$sTypeDict."</span> <span class=\"parenthesis\">(</span><span class=\"attrCode\">".$sType."</span><span class=\"parenthesis\">)</span></span>",
			'origincolor' => "<span class=\"originColor".$sOrigin."\"></span>",
			'origin' => "<span id=\"origin".$sAttrCode."\">$sOrigin</span>",
			'values' => $sAllowedValues,
			'moreinfo' => "<span id=\"moreinfo".$sAttrCode."\"> $sMoreInfo</span>",
		);
		//tooltip construction
		$oPage->add_ready_script(
			<<<EOF
            	if($sAttrValueEscpd != ''){
		       		$('#attr$sAttrCode').qtip( { content: $sAttrValueEscpd, show: 'mouseover', hide: {fixed : true, delay : 500}, style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );
		       	}
		       	if($sAttrTypeDescEscpd != ''){
		      	  $('#type$sAttrCode').qtip( { content: $sAttrTypeDescEscpd, show: 'mouseover', hide: {fixed : true, delay : 500}, style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );
				}
				if($sAttrOriginEscpd != ''){
					$('#originColor$sAttrCode').parent().qtip( { content: $sAttrOriginEscpd, show: 'mouseover', hide: {fixed : true, delay : 500}, style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );
				}
				if( !$sIsEnumValues && $sAllowedValuesEscpd != ''){
					$('#values$sAttrCode').qtip( { content: $sAllowedValuesEscpd, show: 'mouseover', hide: {fixed : true, delay : 500}, style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );
				}
				if($sDefaultNullValue != ''){
					$('#moreinfo$sAttrCode').qtip( { content: $sDefaultNullValue, show: 'mouseover', hide: {fixed : true, delay : 500}, style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );
				}
EOF

		);

	}
	$oPage->SetCurrentTab('UI:Schema:Attributes');
	$aConfig = array(
		'origincolor' => array('label' => "", 'description' => ""),
		'code' => array('label' => Dict::S('UI:Schema:AttributeCode'), 'description' => Dict::S('UI:Schema:AttributeCode+')),
		'type' => array('label' => Dict::S('UI:Schema:Type'), 'description' => Dict::S('UI:Schema:Type+')),
		'values' => array('label' => Dict::S('UI:Schema:AllowedValues'), 'description' => Dict::S('UI:Schema:AllowedValues+')),
		'moreinfo' => array('label' => Dict::S('UI:Schema:MoreInfo'), 'description' => Dict::S('UI:Schema:MoreInfo+')),
		'origin' => array('label' => Dict::S('UI:Schema:Origin'), 'description' => Dict::S('UI:Schema:Origin+')),
	);
	$oPage->table($aConfig, $aDetails);
	$sOrigins = json_encode(array_keys($aOrigins));

	//color calculation in order to keep 1 color for 1 extended class. Colors are interpolated and will be used for
	// graph scheme color too
	$oPage->add_ready_script(
		<<< EOF
				var aOrigins = $sOrigins;
				var aColors = d3.scale.linear().domain([1,aOrigins.length])
				  .interpolate(d3.interpolateHcl)
				  .range([d3.rgb("#007AFF"), d3.rgb('#FFF500')]);		
				$.each(aOrigins,function(idx, origin){
					$('.originColor'+origin).parent().css('background-color',aColors(aOrigins.indexOf(origin)));
				});
				Array.prototype.forEach.call($(".listResults").find('td:nth-child(1),th:nth-child(1)'), function(e){
					$(e).removeClass("header").addClass("originColor");
				});

EOF
	);

	$oPage->SetCurrentTab('UI:Schema:RelatedClasses');
	DisplayRelatedClassesGraph($oPage, $sClass);
	$oPage->SetCurrentTab('UI:Schema:ChildClasses');

	DisplaySubclasses($oPage, $sClass, $sContext);

	$oPage->SetCurrentTab('UI:Schema:LifeCycle');
	DisplayLifecycle($oPage, $sClass);

	$oPage->SetCurrentTab('UI:Schema:Triggers');
	DisplayTriggers($oPage, $sClass);

	$oPage->SetCurrentTab();
	$oPage->SetCurrentTabContainer();
}


/**
 * Display the dropdown that allow to select the attributes/class display granularity
 */
function DisplayGranularityDisplayer($oPage)
{

	$oPage->add("
		<label id=\"displaySelectorLabel\"> <h1> ".Dict::S('UI:Schema:DisplayLabel').
		"<select id=\"displaySelector\">
			<option value=\"labelandcode\">".Dict::S('UI:Schema:DisplaySelector/LabelAndCode')."</option>
			<option value=\"label\">".Dict::S('UI:Schema:DisplaySelector/Label')."</option>
			<option value=\"code\">".Dict::S('UI:Schema:DisplaySelector/Code')."</option>
		</select> </h1></label>
		 <br>");
	$sDisplayDropDownValue = htmlentities(appUserPreferences::GetPref('datamodel_viewer_display_granularity', 'labelandcode'), ENT_QUOTES,
		"UTF-8");

//granularity displayer listener
	$oPage->add_ready_script(
		<<<EOF
        var parenthesisHider = function(){
        	if ( ($('#labelEnabler').is(':checked') && !$('#codeEnabler').is(':checked')) || ($('#codeEnabler').is(':checked') && !$('#labelEnabler').is(':checked')))
        	{
        		$('.parenthesis').toggle();
        	}
        };
		$('#displaySelector').on(
		'change', function(){
			switch($(this).val()){
				case 'labelandcode':
					$('.attrCode').show();
					$('.attrLabel').show();
					$('.parenthesis').show();
					$("#search-model").autocomplete(autocompleteClassLabelAndCode, {scroll:true, matchContains:true});
				break;
				case 'label':
					$('.attrCode').hide();
					$('.attrLabel').show();
					$('.parenthesis').hide();
					$("#search-model").autocomplete(autocompleteClassLabel, {scroll:true, matchContains:true});
				break;
				case 'code':
					$('.attrCode').show();
					$('.attrLabel').hide();
					$('.parenthesis').hide();
					$("#search-model").autocomplete(autocompleteClassCode, {scroll:true, matchContains:true});
				break;
			}
			SetUserPreference("datamodel_viewer_display_granularity", $('#displaySelector').val(), true);
		});
		$('#displaySelector').val("$sDisplayDropDownValue").trigger("change");
EOF
	);
}

/**
 * Display the header of the class details page
 */
function DisplayClassHeader($oPage, $sClass)
{
	//scrollable class name / icon
	$oPage->add("<div id=\"dataModelScrollableDiv\">");
	$oPage->add("<div id=\"dataModelScrollableClassIcon\">".MetaModel::GetClassIcon($sClass)."</div>");
	$oPage->add("<h2 id=\"dataModelScrollableClassName\"><span class=\"attrLabel\">".MetaModel::GetName($sClass)."</span> <span class=\"parenthesis\">(</span><span class=\"attrCode\">".$sClass."</span><span class=\"parenthesis\">)</span></h2>");
	$oPage->add("</div>");

	//content header
	$oPage->add("<div id=\"dataModelHeader\">");
	DisplayGranularityDisplayer($oPage);
	$oPage->add("<div id=\"dataModelClassIcon\">".MetaModel::GetClassIcon($sClass)."</div>");
	$sClassDescritpion = MetaModel::GetClassDescription($sClass);

	$oPage->add("<h2 id=\"classDetailsClassName\"><span class=\"attrLabel\">".MetaModel::GetName($sClass)."</span> <span class=\"parenthesis\">(</span><span class=\"attrCode\">".$sClass."</span><span class=\"parenthesis\">)</span>".($sClassDescritpion == "" ? "" : " - ".$sClassDescritpion)."</h2>\n");
	if (MetaModel::IsAbstract($sClass))
	{
		$oPage->p(Dict::S('UI:Schema:AbstractClass'));
	}
	//ajuste the size of title
	$oPage->add_ready_script("$('#dataModelScrollableDiv').width($('#dataModelScrollableDiv').parent().width());");

}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                             MAIN BLOCK                                                             //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Display the menu on the left
$oAppContext = new ApplicationContext();
$sContext = $oAppContext->GetForLink();
if (!empty($sContext))
{
	$sContext = '&'.$sContext;
}
$operation = utils::ReadParam('operation', '');

$oPage = new iTopWebPage(Dict::S('UI:Schema:Title'));
$oPage->no_cache();

$oPage->SetBreadCrumbEntry('ui-tool-datamodel', Dict::S('Menu:DataModelMenu'), Dict::S('Menu:DataModelMenu+'), '',
	utils::GetAbsoluteUrlAppRoot().'images/wrench.png');
$oPage->add_script(
	<<<EOF
	var autocompleteClassLabelAndCode = [];
	var autocompleteClassLabel = [];
	var autocompleteClassCode = [];
EOF
);

$oPage->add(" <div class='ui-widget'> </div><div id='dataModelSplitPane'>");
$oPage->add("<div class='ui-layout-west data-model-viewer'> ");
DisplayClassesList($oPage, $sContext);
$oPage->add("</div>");
$oPage->add("<div class='ui-layout-center data-model-viewer'>");

switch ($operation)
{
	case 'details_class':
		$sClass = utils::ReadParam('class', '', false, 'class');
		//if we want to see class details & class is given then display it, otherwise act default (just show the class list)
		if ($sClass != '')
		{
			$oPage->add_ready_script(
				<<<EOF
$('#search-model').val('$sClass');
$('#search-model').trigger("input");

EOF
			);
			DisplayClassDetails($oPage, $sClass, $sContext);
			break;
		}
	default:
		DisplayGranularityDisplayer($oPage);
}
$oPage->add("</div>");
$oPage->add("</div>");
//split the page in 2 panels
$oPage->add_init_script(
	<<<EOF
		$('#dataModelSplitPane').layout({
			west : {size: "20%", minSize : 200,paneSize : 600},
			center : {
				onresize_end : function(){
					$("#dataModelScrollableDiv").width($(".ui-layout-center.data-model-viewer").width());
				}
			}
		});
		// Layout
EOF

);

$oPage->output();
?>
