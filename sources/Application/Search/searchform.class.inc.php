<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\Search;


use ApplicationContext;
use AttributeDefinition;
use AttributeEnumSet;
use AttributeExternalField;
use AttributeFriendlyName;
use AttributeTagSet;
use CMDBObjectSet;
use Combodo\iTop\Application\Search\CriterionConversion\CriterionToSearchForm;
use Combodo\iTop\Application\UI\Base\Component\Form\Form;
use Combodo\iTop\Application\UI\Base\Component\Form\FormUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use CoreException;
use DBObjectSearch;
use DBObjectSet;
use Dict;
use Exception;
use Expression;
use FieldExpression;
use IssueLog;
use MetaModel;
use MissingQueryArgument;
use TrueExpression;
use UserRights;
use utils;
use Combodo\iTop\Application\WebPage\WebPage;

class SearchForm
{
	/**
	 * @param WebPage $oPage
	 * @param \CMDBObjectSet $oSet
	 * @param array $aExtraParams
	 *
	 * @return string
	 */
	public function GetSearchForm(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		$oPage->AddUiBlock($this->GetSearchFormUIBlock($oPage, $oSet, $aExtraParams));
		return '';
	}

	public function GetSearchFormUIBlock(WebPage $oPage, DBObjectSet $oSet, $aExtraParams = array())
	{
		$oUiBlock =  new UIContentBlock();
		$oUiBlock->AddMultipleJsFilesRelPaths([
			'node_modules/scrollmagic/scrollmagic/minified/ScrollMagic.min.js',
			'js/searchformforeignkeys.js',
			'js/search/search_form_handler.js',
			'js/search/search_form_handler_history.js',
			'js/search/search_form_criteria.js',
			'js/search/search_form_criteria_raw.js',
			'js/search/search_form_criteria_string.js',
			'js/search/search_form_criteria_external_field.js',
			'js/search/search_form_criteria_numeric.js',
			'js/search/search_form_criteria_enum.js',
			'js/search/search_form_criteria_tag_set.js',
			'js/search/search_form_criteria_external_key.js',
			'js/search/search_form_criteria_hierarchical_key.js',
			'js/search/search_form_criteria_date_abstract.js',
			'js/search/search_form_criteria_date.js',
			'js/search/search_form_criteria_date_time.js',
		]);

		$oAppContext = new ApplicationContext();
		$sClassName = $oSet->GetFilter()->GetClass();
		$aListParams = array();

		foreach($aExtraParams as $key => $value)
		{
			$aListParams[$key] = $value;
		}

		// Simple search form
		if (isset($aExtraParams['currentId'])) {
			$sSearchFormId = 'sf_'.$aExtraParams['currentId'];
		} else {
			$iSearchFormId = utils::GetUniqueId();
			$sSearchFormId = 'SimpleSearchForm'.$iSearchFormId;
			$oUiBlock->AddHtml("<div id=\"ds_$sSearchFormId\" class=\"mini_tab{$iSearchFormId}\">");
			$aListParams['currentId'] = "$iSearchFormId";
		}
		// Check if the current class has some sub-classes
		if (isset($aExtraParams['baseClass'])) {
			$sRootClass = $aExtraParams['baseClass'];
		} else {
			$sRootClass = $sClassName;
		}
		//should the search be opened on load?
		if (isset($aExtraParams['open'])) {
			$bOpen = $aExtraParams['open'];
		} else {
			$bOpen = true;
		}

		$sJson = utils::ReadParam('json', '', false, 'raw_data');
		if (!empty($sJson)) {
			$aListParams['json'] = json_decode($sJson, true);
		}

		if (!isset($aExtraParams['result_list_outer_selector'])) {
			if (isset($aExtraParams['table_id'])) {
				$aExtraParams['result_list_outer_selector'] = $aExtraParams['table_id'];
			} else {
				$aExtraParams['result_list_outer_selector'] = "search_form_result_{$sSearchFormId}";
			}
		}

		$sContext = $oAppContext->GetForLink();
		$sJsonExtraParams = utils::EscapeHtml(json_encode($aListParams));
		$sOuterSelector = $aExtraParams['result_list_outer_selector'];

		if (isset($aExtraParams['search_header_force_dropdown'])) {
			$sClassesCombo = $aExtraParams['search_header_force_dropdown'];
		} else {
			$aSubClasses = MetaModel::GetSubclasses($sRootClass);
			if (count($aSubClasses) > 0) {
				$aOptions = array();
				$aOptions[MetaModel::GetName($sRootClass)] = "<option value=\"$sRootClass\">".MetaModel::GetName($sRootClass)."</options>\n";
				foreach ($aSubClasses as $sSubclassName) {
					if (UserRights::IsActionAllowed($sSubclassName, UR_ACTION_READ)) {
						$aOptions[MetaModel::GetName($sSubclassName)] = "<option value=\"$sSubclassName\">".MetaModel::GetName($sSubclassName)."</options>\n";
					}
				}
				$aOptions[MetaModel::GetName($sClassName)] = "<option selected value=\"$sClassName\">".MetaModel::GetName($sClassName)."</options>\n";
				ksort($aOptions);

				$sClassesCombo = "<select name=\"class\" onChange=\"ReloadSearchForm('$sSearchFormId', this.value, '$sRootClass', '$sContext', '$sOuterSelector', $sJsonExtraParams)\">\n".implode('',
						$aOptions)."</select>\n";
			}
			else
			{
				$sClassesCombo = MetaModel::GetName($sClassName);
			}
		}

		$bAutoSubmit = true;
		$mSubmitParam = utils::GetConfig()->Get('search_manual_submit');
		if ($mSubmitParam !== false)
		{
			$bAutoSubmit = false;
		}
		else
		{
			$mSubmitParam = utils::GetConfig()->Get('high_cardinality_classes');
			if (is_array($mSubmitParam)) {
				if (in_array($sClassName, $mSubmitParam)) {
					$bAutoSubmit = false;
				}
			}
		}
		$bShowObsoleteData = \appUserPreferences::GetPref('show_obsolete_data', MetaModel::GetConfig()->Get('obsolescence.show_obsolete_data'));// ? What to do when true == utils::IsArchiveMode()


		$sAction = (isset($aExtraParams['action'])) ? $aExtraParams['action'] : utils::GetAbsoluteUrlAppRoot().'pages/UI.php';
		$aCSSClasses = ["ibo-search-form"];
		if ($bOpen != 'true') {
			$aCSSClasses[] = 'closed';
		}
		if ($bAutoSubmit != true) {
			$aCSSClasses[] = 'no_auto_submit';
		}
		$oForm = FormUIBlockFactory::MakeStandard();
		$oForm->SetAction($sAction);
		$oForm->AddSubBlock(new Html(Dict::Format('UI:SearchFor_Class_Objects', $sClassesCombo)));

		$oUiSearchBlock = new Panel('', [], Panel::ENUM_COLOR_SCHEME_CYAN, $sSearchFormId);
		$oUiSearchBlock->SetCSSClasses(["ibo-search-form-panel", "display_block"])
			->AddTitleBlock($oForm);
		$oUiBlock->AddSubBlock($oUiSearchBlock);
		$sHtml = "";
		if (!$bShowObsoleteData) {
			$sShowObsoleteLabel = Dict::S('UI:Search:Obsolescence:DisabledHint');
			$sHtml .= "<span class=\"pull-right\">";
			$sHtml .= "<span class=\"sfobs_hint pull-right\"><span class=\"fas fa-eye-slash fa-1x\" aria-label=\"$sShowObsoleteLabel\" data-tooltip-content=\"$sShowObsoleteLabel\"></span></span>";
		}
		if ($bAutoSubmit === false) {
			$sAutoSubmit = Dict::S('UI:Search:AutoSubmit:DisabledHint');
		$sHtml .= "<span class=\"sft_hint pull-right\"><span class=\"fa-stack fa-fw\" aria-label=\"$sAutoSubmit\" data-tooltip-content=\"$sAutoSubmit\"><span class=\"fas fa-sync-alt fa-stack-1x\"></span><span class=\"fas fa-slash fa-stack-1x\"></span
></span></span>";
			$sHtml .= "</span>";
		}
		$sHtml .= "<br class='clearboth' />";
		$oUiSearchBlock->AddToolbarBlock(new Html($sHtml));


		$oFormSearch = new Form("fs_".$sSearchFormId);
		$oFormSearch->SetAction($sAction)
			->AddCSSClasses($aCSSClasses);
		$oUiSearchBlock->AddSubBlock($oFormSearch);
		$oFormSearch->AddSubBlock(InputUIBlockFactory::MakeForHidden("class", $sClassName));
		$oFormSearch->AddHtml("<div id=\"fs_{$sSearchFormId}_message\" class=\"sf_message header_message\"></div>");//class sf_message header_message

		$oCriterionBlock = new UIContentBlock("fs_{$sSearchFormId}_criterion_outer", ["sf_criterion_area ibo-criterion-area"]);
		$oFormSearch->AddSubBlock($oCriterionBlock);

		if (isset($aExtraParams['query_params'])) {
			$aArgs = $aExtraParams['query_params'];
		} else
		{
			$aArgs = array();
		}

		$bIsRemovable = true;
		if (isset($aExtraParams['selection_type']) && ($aExtraParams['selection_type'] == 'single'))
		{
			// Mark all criterion as read-only and non-removable for external keys only
			$bIsRemovable = false;
		}

		$bUseApplicationContext = true;
		if (isset($aExtraParams['selection_mode']) && ($aExtraParams['selection_mode'])) {
			// Don't use application context for selections
			$bUseApplicationContext = false;
		}

		$aFields = $this->GetFields($oSet);
		$oSearch = $oSet->GetFilter();
		$aCriterion = $this->GetCriterion($oSearch, $aFields, $aArgs, $bIsRemovable, $bUseApplicationContext);
		$aClasses = $oSearch->GetSelectedClasses();
		$sClassAlias = '';
		foreach ($aClasses as $sAlias => $sClass) {
			$sClassAlias = $sAlias;
		}

		$oBaseSearch = $oSearch->DeepClone();
		if ($oSearch instanceof DBObjectSearch) {
			$oBaseSearch->ResetCondition();
		}
		$sBaseOQL = str_replace(' WHERE 1', '', $oBaseSearch->ToOQL());

		if (!isset($aExtraParams['table_inner_id'])) {
			$aListParams['table_inner_id'] = "table_inner_id_{$sSearchFormId}";
		}
		$bSubmitOnLoad = (isset($aExtraParams['submit_on_load'])) ? $aExtraParams['submit_on_load'] : true;

		$sDebug = utils::ReadParam('debug', 'false', false, 'parameter');
		if ($sDebug == 'true') {
			$aListParams['debug'] = 'true';
		}

		$aDaysMin = array(
			Dict::S('DayOfWeek-Sunday-Min'),
			Dict::S('DayOfWeek-Monday-Min'),
			Dict::S('DayOfWeek-Tuesday-Min'),
			Dict::S('DayOfWeek-Wednesday-Min'),
			Dict::S('DayOfWeek-Thursday-Min'),
			Dict::S('DayOfWeek-Friday-Min'),
			Dict::S('DayOfWeek-Saturday-Min'),
		);
		$aMonthsShort = array(
			Dict::S('Month-01-Short'),
			Dict::S('Month-02-Short'),
			Dict::S('Month-03-Short'),
			Dict::S('Month-04-Short'),
			Dict::S('Month-05-Short'),
			Dict::S('Month-06-Short'),
			Dict::S('Month-07-Short'),
			Dict::S('Month-08-Short'),
			Dict::S('Month-09-Short'),
			Dict::S('Month-10-Short'),
			Dict::S('Month-11-Short'),
			Dict::S('Month-12-Short'),
		);

		$sDateTimeFormat = \AttributeDateTime::GetFormat()->ToDatePicker();
		$iDateTimeSeparatorPos = strpos($sDateTimeFormat, ' ');
		$sDateFormat = substr($sDateTimeFormat, 0, $iDateTimeSeparatorPos);
		$sTimeFormat = substr($sDateTimeFormat, $iDateTimeSeparatorPos + 1);

		$aSearchParams = array(
			'criterion_outer_selector'   => "#fs_{$sSearchFormId}_criterion_outer",
			'result_list_outer_selector' => "#{$aExtraParams['result_list_outer_selector']}",
			'data_config_list_selector'  => "#{$aExtraParams['result_list_outer_selector']}",
			'endpoint'                   => utils::GetAbsoluteUrlAppRoot().'pages/ajax.searchform.php?'.$sContext,
			'init_opened'                => $bOpen,
			'submit_on_load'             => $bSubmitOnLoad,
			'auto_submit'                => $bAutoSubmit,
			'list_params'                => $aListParams,
			'show_obsolete_data'         => $bShowObsoleteData,
			'search'                     => array(
				'has_hidden_criteria' => (array_key_exists('hidden_criteria', $aListParams) && !empty($aListParams['hidden_criteria'])),
				'fields' => $aFields,
				'criterion' => $aCriterion,
				'class_name' => $sClassName,
				'class_alias' => $sClassAlias,
				'base_oql' => $sBaseOQL,
			),
			'conf_parameters' => array(
				'min_autocomplete_chars' => MetaModel::GetConfig()->Get('min_autocomplete_chars'),
				'datepicker' => array(
					'dayNamesMin' => $aDaysMin,
					'monthNamesShort' => $aMonthsShort,
					'firstDay' => (int) Dict::S('Calendar-FirstDayOfWeek'),
					'dateFormat' => $sDateFormat,
					'timeFormat' => $sTimeFormat,
				),
			),
		);

		$oPage->add_ready_script('$("#fs_'.$sSearchFormId.'").search_form_handler('.json_encode($aSearchParams).');');

		return $oUiBlock;
	}
    /**
     * @param \DBObjectSet $oSet
     *
     * @return array
     *
     * @throws \CoreException
     */
	public function GetFields($oSet)
	{
		$oSearch = $oSet->GetFilter();
		$aAllClasses = $oSearch->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach($aAllClasses as $sAlias => $sClassName)
		{
			if (\UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $oSet) != UR_ALLOWED_NO)
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		$aAllFields = array('zlist' => array(), 'others' => array());
		try
		{
			foreach($aAuthorizedClasses as $sAlias => $sClass)
			{
				$aZList = array();
				$aOthers = array();

				$this->PopulateFieldList($sClass, $sAlias, $aZList, $aOthers);

				$aAllFields[$sAlias.'_zlist'] = $aZList;
				$aAllFields[$sAlias.'_others'] = $aOthers;
			}
		}
		catch (CoreException $e)
		{
			IssueLog::Error($e->getMessage());
		}
		$aSelectedClasses = $oSearch->GetSelectedClasses();
		foreach($aSelectedClasses as $sAlias => $sClassName)
		{
			if(array_key_exists($sAlias.'_zlist', $aAllFields))
			{
				$aAllFields['zlist'] = array_merge($aAllFields['zlist'], $aAllFields[$sAlias.'_zlist']);
				unset($aAllFields[$sAlias.'_zlist']);
			}
			if(array_key_exists($sAlias.'_others', $aAllFields))
			{
				$aAllFields['others'] = array_merge($aAllFields['others'], $aAllFields[$sAlias.'_others']);
				unset($aAllFields[$sAlias.'_others']);
			}
		}

		return $aAllFields;
	}

	/**
	 * @param $sClass
	 * @param $sAlias
	 * @param $aZList
	 * @param $aOthers
	 *
	 * @throws \CoreException
	 */
	protected function PopulateFieldList($sClass, $sAlias, &$aZList, &$aOthers)
	{
		$aDBIndexes = self::DBGetIndexes($sClass);
		$aIndexes = array();
		foreach($aDBIndexes as $aIndexGroup)
		{
			foreach($aIndexGroup as $sIndex)
			{
				$aIndexes[$sIndex] = true;
			}
		}
		$aAttributeDefs = MetaModel::ListAttributeDefs($sClass);
		$aList = MetaModel::GetZListItems($sClass, 'standard_search');
		$bHasFriendlyname = false;
		foreach($aList as $sAttCode)
		{
			if (array_key_exists($sAttCode, $aAttributeDefs))
			{
				$bHasIndex = isset($aIndexes[$sAttCode]);
				$oAttDef = $aAttributeDefs[$sAttCode];
				$aZList = $this->AppendField($sClass, $sAlias, $sAttCode, $oAttDef, $aZList, $bHasIndex);
				unset($aAttributeDefs[$sAttCode]);
			}
			if ($sAttCode == 'friendlyname')
			{
				$bHasFriendlyname = true;
			}
		}
		if (!$bHasFriendlyname)
		{
			// Add friendlyname to the most popular
			$sAttCode = 'friendlyname';
            $bHasIndex =  isset($aIndexes[$sAttCode]);
			$oAttDef = $aAttributeDefs[$sAttCode];
			$aZList = $this->AppendField($sClass, $sAlias, $sAttCode, $oAttDef, $aZList, $bHasIndex);
			unset($aAttributeDefs[$sAttCode]);
		}
		$aZList = $this->AppendId($sClass, $sAlias, $aZList);
		uasort($aZList, function ($aItem1, $aItem2) {
			return strcmp($aItem1['label'], $aItem2['label']);
		});

		foreach($aAttributeDefs as $sAttCode => $oAttDef)
		{
			if ($oAttDef instanceof AttributeFriendlyName) continue; //it was already forced into $aZList in the code above

            $bHasIndex =  isset($aIndexes[$sAttCode]);
			$aOthers = $this->AppendField($sClass, $sAlias, $sAttCode, $oAttDef, $aOthers, $bHasIndex);
		}
		uasort($aOthers, function ($aItem1, $aItem2) {
			return strcmp($aItem1['label'], $aItem2['label']);
		});
	}

	/**
	 * Search indexes for class and parents
	 * @param $sClass
	 *
	 * @return array
	 * @throws \CoreException
	 */
	protected static function DBGetIndexes($sClass)
	{
		$aDBIndexes = MetaModel::DBGetIndexes($sClass);
		while ($sClass = MetaModel::GetParentClass($sClass))
		{
			$aDBIndexes = array_merge($aDBIndexes, MetaModel::DBGetIndexes($sClass));
		}
		return $aDBIndexes;
	}


    /**
     * @param \AttributeDefinition $oAttrDef
     *
     * @return array
     *
     * @throws \CoreException
     * @throws \MissingQueryArgument
     * @throws \MySQLException
     * @throws \MySQLHasGoneAwayException
     */
	public static function GetFieldAllowedValues($oAttrDef)
	{
		$iMaxComboLength = MetaModel::GetConfig()->Get('max_combo_length');
		if ($oAttrDef->IsExternalKey(EXTKEY_ABSOLUTE))
		{
			if ($oAttrDef instanceof AttributeExternalField)
			{
				$sTargetClass = $oAttrDef->GetFinalAttDef()->GetTargetClass();
			}
			else
			{
				/** @var \AttributeExternalKey $oAttrDef */
				$sTargetClass = $oAttrDef->GetTargetClass();
			}
			try
			{
				$oSearch = new DBObjectSearch($sTargetClass);
			} catch (Exception $e)
			{
				IssueLog::Error($e->getMessage());

				return array('values' => array());
			}
			$oSearch->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', true);
			$oSet = new DBObjectSet($oSearch);
			if ($oSet->CountExceeds($iMaxComboLength))
			{
				return array('autocomplete' => true);
			}
			if ($oAttrDef instanceof AttributeExternalField)
			{
				$aAllowedValues = array();
				while ($oObject = $oSet->Fetch())
				{
					$aAllowedValues[$oObject->GetKey()] = $oObject->GetName();
				}
				return array('values' => $aAllowedValues);
			}
		}
		elseif ($oAttrDef instanceof AttributeTagSet)
		{
			$aAllowedValues = array();
			foreach($oAttrDef->GetAllowedValues() as $sCode => $sRawValue)
			{
				$aAllowedValues[$sCode] = utils::HtmlEntities($sRawValue);
			}

			return array('values' => $aAllowedValues);
		}
		elseif ($oAttrDef instanceof AttributeEnumSet)
		{
			$aAllowedValues = array();
			foreach($oAttrDef->GetPossibleValues() as $sCode => $sRawValue)
			{
				$aAllowedValues[$sCode] = utils::HtmlEntities($sRawValue);
			}

			return array('values' => $aAllowedValues);
		}
		else
		{
			if (method_exists($oAttrDef, 'GetAllowedValuesAsObjectSet'))
			{
				/** @var DBObjectSet $oSet */
				$oSet = $oAttrDef->GetAllowedValuesAsObjectSet();
				if ($oSet->CountExceeds($iMaxComboLength))
				{
					return array('autocomplete' => true);
				}
			}
		}
		$aAllowedValues = $oAttrDef->GetAllowedValuesForSelect();

		return array('values' => $aAllowedValues);
	}

	/**
	 * @param \DBObjectSearch $oSearch
	 * @param array $aFields
	 * @param array $aArgs
	 * @param bool $bIsRemovable
	 * @param bool $bUseApplicationContext
	 *
	 * @return array
	 *
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 */
	public function GetCriterion($oSearch, $aFields, $aArgs = array(), $bIsRemovable = true, $bUseApplicationContext = true)
	{
		$aOrCriterion = array();
		$bIsEmptyExpression = true;
		$aArgs = MetaModel::PrepareQueryArguments($aArgs, $oSearch->GetInternalParams(), $oSearch->GetExpectedArguments());

		if ($oSearch instanceof DBObjectSearch)
		{
			$oExpression = $oSearch->GetCriteria();

			if (!empty($aArgs))
			{
				try
				{
					$sOQL = $oExpression->RenderExpression(false, $aArgs);
					$oExpression = Expression::FromOQL($sOQL);
				}
				catch (MissingQueryArgument $e)
				{
					IssueLog::Error("Search form disabled: \"".$oSearch->ToOQL()."\" Error: ".$e->getMessage());
					throw $e;
				}
			}

			$aORExpressions = Expression::Split($oExpression, 'OR');
			foreach($aORExpressions as $oORSubExpr)
			{
				$aAndCriterion = array();
				$aAndExpressions = Expression::Split($oORSubExpr, 'AND');
				foreach($aAndExpressions as $oAndSubExpr)
				{
					/** @var Expression $oAndSubExpr */
					if (($oAndSubExpr instanceof TrueExpression) || ($oAndSubExpr->RenderExpression(false) == 1))
					{
						continue;
					}
					$aAndCriterion[] = $oAndSubExpr->GetCriterion($oSearch);
					$bIsEmptyExpression = false;
				}
				$aAndCriterion = CriterionToSearchForm::Convert($aAndCriterion, $aFields, $oSearch->GetJoinedClasses(), $bIsRemovable);
				$aOrCriterion[] = array('and' => $aAndCriterion);
			}
		}

		if ($bIsEmptyExpression)
		{
			$aOrCriterion = array(array('and' => array()));
		}
		$aTempCriteria = array();
		foreach ($aOrCriterion as $aExistingCriteria)
		{
			// Add default criteria to all the OR criteria
			$aTempCriteria[] = $this->GetDefaultCriteria($oSearch, $aExistingCriteria, $aContextParams);
		}
		$aOrCriterion = $aTempCriteria;

		if ($bUseApplicationContext)
		{
			// Context induced criteria are read-only
			$oAppContext = new ApplicationContext();
			$sClass = $oSearch->GetClass();
			$aCallSpec = array($sClass, 'MapContextParam');
			$aContextParams = array();
			if (is_callable($aCallSpec))
			{
				foreach ($oAppContext->GetNames() as $sContextParam)
				{
					$sParamCode = call_user_func($aCallSpec, $sContextParam); //Map context parameter to the value/filter code depending on the class
					if (!is_null($sParamCode))
					{
						$sParamValue = $oAppContext->GetCurrentValue($sContextParam, null);
						if (!is_null($sParamValue))
						{
							$aContextParams[$sParamCode] = $sParamValue;
						}
					}
				}
			}

			foreach ($aContextParams as $sParamCode => $sParamValue)
			{
				// Check that the code exists in the concerned class
				if (!MetaModel::IsValidAttCode($oSearch->GetClass(), $sParamCode))
				{
					continue;
				}

				// Add Context criteria in read only mode
				$sAlias = $oSearch->GetClassAlias();
				$oFieldExpression = new FieldExpression($sParamCode, $sAlias);
				$oScalarExpression = new \ScalarExpression($sParamValue);
				$oExpression = new \BinaryExpression($oFieldExpression, '=', $oScalarExpression);
				$aCriterion = $oExpression->GetCriterion($oSearch, $aArgs);
				$aCriterion['is_removable'] = false;
				foreach ($aOrCriterion as &$aAndExpression)
				{
					$aAndExpression['and'][] = $aCriterion;
				}
			}
		}

		return array('or' => $aOrCriterion);
	}

	/**
	 * @param $sClass
	 * @param $sClassAlias
	 * @param $aFields
	 *
	 * @return mixed
	 */
	private function AppendId($sClass, $sClassAlias, $aFields)
	{
		$aField = array();
		$aField['code'] = 'id';
		$aField['class'] = $sClass;
		$aField['class_alias'] = $sClassAlias;
		$aField['label'] = 'Id';
		$aField['widget'] = AttributeDefinition::SEARCH_WIDGET_TYPE_NUMERIC;
		$aField['is_null_allowed'] = false;
		$aNewFields = array($sClassAlias.'.id' => $aField);
		$aFields = array_merge($aNewFields, $aFields);
		return $aFields;
	}

	/**
	 * @param string $sClass
	 * @param string $sClassAlias
	 * @param string $sAttCode
	 * @param \AttributeDefinition $oAttDef
	 * @param array $aFields
	 * @param bool $bHasIndex
	 *
	 * @return mixed
	 *
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \Exception
	 */
	private function AppendField($sClass, $sClassAlias, $sAttCode, $oAttDef, $aFields, $bHasIndex = false)
	{
		if (!is_null($oAttDef) && ($oAttDef->GetSearchType() != AttributeDefinition::SEARCH_WIDGET_TYPE_RAW))
		{
			if (method_exists($oAttDef, 'GetLabelForSearchField'))
			{
				$sLabel = $oAttDef->GetLabelForSearchField();
			}
			else
			{
				if ($sAttCode == 'friendlyname')
				{
					try
					{
						$sLabel = MetaModel::GetName($sClass);
					}
					catch (Exception $e)
					{
						$sLabel = $oAttDef->GetLabel();
					}
				}
				else
				{
					$sLabel = $oAttDef->GetLabel();
				}
			}

			if ($oAttDef instanceof AttributeExternalField)
			{
				$oTargetAttDef = $oAttDef->GetFinalAttDef();
			}
			else
			{
				$oTargetAttDef = $oAttDef;
			}

			if (method_exists($oTargetAttDef, 'GetTargetClass'))
			{
				$sTargetClass = $oTargetAttDef->GetTargetClass();
			}
			else
			{
				$sTargetClass = $oTargetAttDef->GetHostClass();
			}

			$aField = array();
			$aField['code'] = $sAttCode;
			$aField['class'] = $sClass;
			$aField['class_alias'] = $sClassAlias;
			$aField['target_class'] = $sTargetClass;
			$aField['label'] = $sLabel;
			$aField['widget'] = $oAttDef->GetSearchType();
			$aField['allowed_values'] = self::GetFieldAllowedValues($oAttDef);
			$aField['is_null_allowed'] = $oAttDef->IsNullAllowed();
			$aField['has_index'] = $bHasIndex;
			$aFields[$sClassAlias.'.'.$sAttCode] = $aField;

			// Sub items
			//
			//			if ($oAttDef->IsSearchable())
			//			{
			//				$sShortLabel = $oAttDef->GetLabel();
			//				$sLabel = $sShortAlias.$oAttDef->GetLabel();
			//				$aSubAttr = $this->GetSubAttributes($sClass, $sFilterCode, $oAttDef);
			//				$aValidSubAttr = array();
			//				foreach($aSubAttr as $aSubAttDef)
			//				{
			//					$aValidSubAttr[] = array('attcodeex' => $aSubAttDef['code'], 'code' => $sShortAlias.$aSubAttDef['code'], 'label' => $aSubAttDef['label'], 'unique_label' => $sShortAlias.$aSubAttDef['unique_label']);
			//				}
			//				$aAllFields[] = array('attcodeex' => $sFilterCode, 'code' => $sShortAlias.$sFilterCode, 'label' => $sShortLabel, 'unique_label' => $sLabel, 'subattr' => $aValidSubAttr);
			//			}

		}

		return $aFields;
	}

	/**
	 * @param \DBObjectSearch $oSearch
	 * @param array $aExistingCriteria
	 * @param array $aContextParams
	 *
	 * @return array Current AND criteria list
	 *
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 */
	protected function GetDefaultCriteria($oSearch, $aExistingCriteria, &$aContextParams = array())
	{
		$sClass = $oSearch->GetClass();
		$aList = MetaModel::GetZListItems($sClass, 'default_search');
		while (empty($aList))
		{
			// search in parent class if default criteria are defined
			$sClass = MetaModel::GetParentClass($sClass);
			if (is_null($sClass))
			{
				return $aExistingCriteria;
			}
			$aList = MetaModel::GetZListItems($sClass, 'default_search');
		}
		$sAlias = $oSearch->GetClassAlias();
		$aAndCriteria = $aExistingCriteria['and'];
		foreach($aList as $sAttCode)
		{
			$oExpression = new FieldExpression($sAttCode, $sAlias);
			$bIsRemovable = true;
			if (isset($aContextParams[$sAttCode]))
			{
				// When a context parameter exists, use it with the default search criteria
				$oFieldExpression = $oExpression;
				$oScalarExpression = new \ScalarExpression($aContextParams[$sAttCode]);
				$oExpression = new \BinaryExpression($oFieldExpression, '=', $oScalarExpression);
				unset($aContextParams[$sAttCode]);
				// Read only mode for search criteria from context
				$bIsRemovable = false;
			}
			$aCriterion = $oExpression->GetCriterion($oSearch);
			$aCriterion['is_removable'] = $bIsRemovable;
			$this->AddCriterion($aCriterion, $aAndCriteria);
		}
		// Overwrite with default criterion
		$aCriteria = array('and' => $aAndCriteria);
		return $aCriteria;
	}

	/** Add the criterion to the existing list of criteria avoiding duplicates
	 * @param array $aCriterion
	 * @param array $aAndCriterion
	 *
	 */
	private function AddCriterion($aCriterion, &$aAndCriterion)
	{
		if ($aCriterion['is_removable'])
		{
			// Check if the criterion is already present
			// Non-removable criteria are mandatory
			$ref = $aCriterion['ref'];
			foreach ($aAndCriterion as $aExistingCriterion)
			{
				if (isset($aExistingCriterion['ref']) && ($ref === $aExistingCriterion['ref']))
				{
					// Already present do nothing
					return;
				}
			}
		}
		if (isset($aCriterion['widget']) && ($aCriterion['widget'] != AttributeDefinition::SEARCH_WIDGET_TYPE_RAW))
		{
			$aAndCriterion[] = $aCriterion;
		}
	}

}
