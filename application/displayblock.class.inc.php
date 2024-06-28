<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\Search\SearchForm;
use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Dashlet\DashletFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Pill\PillFactory;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItemFactory;
use Combodo\iTop\Application\UI\Base\Component\Toolbar\Separator\ToolbarSeparatorUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarUIBlockFactory;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockWithJSRefreshCallback;
use Combodo\iTop\Application\UI\DisplayBlock\BlockChart\BlockChart;
use Combodo\iTop\Application\UI\DisplayBlock\BlockChartAjaxBars\BlockChartAjaxBars;
use Combodo\iTop\Application\UI\DisplayBlock\BlockChartAjaxPie\BlockChartAjaxPie;
use Combodo\iTop\Application\UI\DisplayBlock\BlockCsv\BlockCsv;
use Combodo\iTop\Application\UI\DisplayBlock\BlockList\BlockList;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Service\Router\Router;

require_once(APPROOT.'/application/utils.inc.php');

/**
 * Helper class to manage 'blocks' of HTML pieces that are parts of a page and contain some list of cmdb objects
 *
 * Each block is actually rendered as a <div></div> tag that can be rendered synchronously
 * or as a piece of Javascript/JQuery/Ajax that will get its content from another page (ajax.render.php).
 */
class DisplayBlock
{
	/**
	 * @var string
	 * @since 3.1.0
	 */
	public const ENUM_STYLE_COUNT = 'count';

	/**
	 * @var string
	 * @since 3.1.0
	 */
	public const ENUM_STYLE_JOIN = 'join';

	/**
	 * @var string For regular list (objects or static data)
	 * @since 3.1.0
	 */
	public const ENUM_STYLE_LIST = 'list';

	/**
	 * @var string For search results
	 * @since 3.1.0
	 */
	public const ENUM_STYLE_LIST_SEARCH = 'list_search';

	/**
	 * @var string For objects list in other objects (direct / indirect linksets)
	 * @since 3.1.0
	 */
	public const ENUM_STYLE_LIST_IN_OBJECT = 'listInObject';

	/**
	 * @var string
	 * @since 3.1.0
	 */
	public const ENUM_STYLE_ACTIONS = 'actions';

	/**
	 * @var string
	 * @since 3.1.0
	 */
	public const ENUM_STYLE_SUMMARY = 'summary';

	/**
	 * @var string
	 * @since 3.1.0
	 */
	public const ENUM_STYLE_CSV = 'csv';

	/**
	 * @var string
	 * @since 3.1.0
	 */
	public const ENUM_STYLE_SEARCH = 'search';

	/**
	 * @var string
	 * @since 3.1.0
	 */
	public const ENUM_STYLE_CHART = 'chart';

	/**
	 * @var string
	 * @since 3.1.0
	 */
	public const ENUM_STYLE_CHART_AJAX = 'chart_ajax';

	const TAG_BLOCK = 'itopblock';
	/** @var \DBSearch */
	protected $m_oFilter;
	protected $m_aConditions; // Conditions added to the filter -> avoid duplicate conditions
	protected $m_sStyle;
	protected $m_bAsynchronous;
	protected $m_aParams;
	/** @var \DBObjectSet|null  */
	protected $m_oSet;
	protected $m_bShowObsoleteData = null;

	/**
	 * @param \DBSearch $oFilter list of cmdbObjects to be displayed into the block
	 * @param string $sStyle one of :
	 *        <ul>
	 *           <li>actions : </li>
	 *           <li>chart : </li>
	 *           <li>chart_ajax : </li>
	 *           <li>count : produces a paragraphs with a sentence saying 'cont' objects found</li>
	 *           <li>csv : displays a textarea with the CSV export of the list of objects</li>
	 *           <li>join : </li>
	 *           <li>links : </li>
	 *           <li>list : produces a table listing the objects</li>
	 *           <li>list_search : </li>
	 *           <li>search : displays a search form with the criteria of the filter set</li>
	 *           <li>summary : </li>
	 *        </ul>
	 * @param bool $bAsynchronous
	 * @param array $aParams
	 * @param \DBObjectSet $oSet
	 *
	 * @throws \ApplicationException
	 */
	public function __construct(DBSearch $oFilter, $sStyle = self::ENUM_STYLE_LIST, $bAsynchronous = false, $aParams = array(), $oSet = null)
	{
		$this->m_oFilter = $oFilter->DeepClone();
		$this->m_aConditions = array();
		$this->m_sStyle = $sStyle;
		$this->m_bAsynchronous = $bAsynchronous;
		$this->m_aParams = $aParams;
		$this->m_oSet = $oSet;
		if (array_key_exists('show_obsolete_data', $aParams))
		{
			$this->m_bShowObsoleteData = $aParams['show_obsolete_data'];
		}
		if ($this->m_bShowObsoleteData === null)
		{
			// User defined
			$this->m_bShowObsoleteData = utils::ShowObsoleteData();
		}
	}

	/**
	 * @param string $sStyle
	 *
	 * @return string[]
	 */
	protected function GetAllowedParams(string $sStyle): array
	{
		$aAllowedParams = [
			static::ENUM_STYLE_ACTIONS => [
				'context_filter',
				/** int if != 0 filter with user context */
				'display_limit',
				/** for dashlet*/
			],
			static::ENUM_STYLE_CHART => [
				'chart_type',
				/** string 'pie' or 'bars'  */
				'group_by',
				/** string group by att code */
				'group_by_expr',
				/** string group by expression */
				'group_by_label',
				/** string aggregation column name */
				'aggregation_function',
				/** string aggregation function ('count', 'sum', 'avg', 'min', 'max', ...) */
				'aggregation_attribute',
				/** string att code used for aggregation */
				'limit',
				/** int limit the chart results */
				'order_by',
				/** string either 'attribute' group_by attcode or 'function' aggregation_function value */
				'order_direction',
				/** string order direction 'asc' or 'desc' */
				'chart_title',
				/** string title */
				'display_limit',
			],
			static::ENUM_STYLE_CHART_AJAX => [
				'chart_type',       /** string 'pie' or 'bars'  */
				'group_by',         /** string group by att code */
				'group_by_expr',    /** string group by expression */
				'group_by_label',   /** string aggregation column name */
				'aggregation_function',     /** string aggregation function ('count', 'sum', 'avg', 'min', 'max', ...) */
				'aggregation_attribute',    /** string att code used for aggregation */
				'limit',            /** int limit the chart results */
				'order_by',         /** string either 'attribute' group_by attcode or 'function' aggregation_function value */
				'order_direction',  /** string order direction 'asc' or 'desc' */
			],
			static::ENUM_STYLE_COUNT => [
				'group_by',         /** string group by att code */
				'group_by_expr',    /** string group by expression */
				'group_by_label',   /** string aggregation column name */
				'aggregation_function',
				/** string aggregation function ('count', 'sum', 'avg', 'min', 'max', ...) */
				'aggregation_attribute',
				/** string att code used for aggregation */
				'limit',
				/** int limit the chart results */
				'order_by',
				/** string either 'attribute' group_by attcode or 'function' aggregation_function value */
				'order_direction',
				/** string order direction 'asc' or 'desc' */
				'display_limit',
			],
			static::ENUM_STYLE_CSV => [],
			static::ENUM_STYLE_JOIN => array_merge([
				'display_aliases',
				/** string comma separated list of class aliases to display */
				'group_by',
				/** string group by att code */
			], DataTableUIBlockFactory::GetAllowedParams()),
			'links' => DataTableUIBlockFactory::GetAllowedParams(),
			static::ENUM_STYLE_LIST => array_merge([
				'update_history',
				/** bool add breadcrumb entry */
				'default',
				/** array of default attribute values */
				'menu_actions_target',
				/** string html link target */
				'toolkit_menu',
				/** bool add toolkit menu */
				'selectionMode',
				/**positive or negative*/
				'max_height',
				/** string Max. height of the list, if not specified will occupy all the available height no matter the pagination */
				'localize_values',
				/** param for export.php */
				'refresh_action',
				/**to add refresh button in datatable*/
			], DataTableUIBlockFactory::GetAllowedParams()),
			static::ENUM_STYLE_LIST_IN_OBJECT => array_merge([
				'update_history',
				/** bool add breadcrumb entry */
				'default',
				/** array of default attribute values */
				'menu_actions_target',
				/** string html link target */
				'toolkit_menu',
				/** bool add toolkit menu */
				'selectionMode',
				/**positive or negative*/
				'max_height',
				/** string Max. height of the list, if not specified will occupy all the available height no matter the pagination */
				'localize_values',
				/** param for export.php */
				'refresh_action',
				/**to add refresh button in datatable*/
			], DataTableUIBlockFactory::GetAllowedParams()),
			static::ENUM_STYLE_LIST_SEARCH => array_merge([
				'update_history',
				/** bool add breadcrumb entry */
				'result_list_outer_selector',
				/** string js selector of the search result display */
				'table_inner_id',
				/** string html id of the results table */
				'json',
				/** string  */
				'hidden_criteria',
				/** string search criteria not visible */
				'baseClass',
				/** string base class */
				'action',
				/** string */
				'open',
				/** bool open by default the search */
				'submit_on_load',
				/** bool submit the search on loading page */
				'class', /** class name */
				'search_header_force_dropdown', /** Html for <select> to choose the class to search  */
				'this',
			], DataTableUIBlockFactory::GetAllowedParams()),
			static::ENUM_STYLE_SEARCH => array_merge([
				'baseClass',
				/** string search root class */
				'open',
				/** bool open the search panel by default */
				'submit_on_load',
				/** bool submit the search on loading page */
				'result_list_outer_selector',
				/** string js selector of the search result display */
				'search_header_force_dropdown',
				/** string Search class selection dropdown html code */
				'action',
				/** string search URL */
				'table_inner_id',
				/** string html id of the results table */
				'json',
				/** string  */
				'hidden_criteria',
				/** string search criteria not visible */
				'class',
				/** string class searched */
			], DataTableUIBlockFactory::GetAllowedParams()),
			static::ENUM_STYLE_SUMMARY => [
				'status[block]',
				/** string object 'status' att code */
				'status_codes[block]',
				/** string comma separated list of object states */
				'title[block]',
				/** string title */
				'label[block]',
				/** string label */
				'context_filter',
				/** int if != 0 filter with user context */
				'org_id',
			],
		];

		$aAllowedGeneralParams = [
			/** bool display obsolete data */
			'show_obsolete_data',
			/** string current block id overridden by $sId argument */
			'currentId',
			/** array query parameters */
			'query_params',
			/** int Id of the current object */
			'this->id',
			/** string class of the current object */
			'this->class',
			/** string comma separated list of attCodes */
			'order_by',
			/** bool|string|numeric 'fast' (reload faster) or 'standard' (= true or 'true') (reload standard) or reload interval value (numeric) */
			'auto_reload',
			/** string current navigation menu */
			'c[menu]',
			/** int current filtered organization */
			'c[org_id]',
			/** string workaround due to extraparams in menunode */
			'c[menu',
			/** int workaround due to extraparams in menunode */
			'c[org_id',
			/** string dashboard html div id */
			'dashboard_div_id',
			/** param true if block is in a dashboard*/
			'withJSRefreshCallBack',
			/** true if dashboard page */
			'from_dashboard_page',
			/** bool true if list may be render in panel block */
			'surround_with_panel',
			/** string title of panel block */
			'panel_title',
			/** string true if panel title should be displayed as html */
			'panel_title_is_html',
			/** string Description of the panel content, displayed as a hint on the title */
			'panel_title_tooltip',
			/** string class for panel block style */
			'panel_class',
			/** string class for panel block style */
			'panel_icon',
		];

		if (isset($aAllowedParams[$sStyle])) {
			return array_merge($aAllowedGeneralParams, $aAllowedParams[$sStyle]);
		}

		return $aAllowedGeneralParams;
	}

	/**
	 * @param string $sStyle
	 * @param array $aParams
	 *
	 * @throws \ApplicationException
	 */
	protected function CheckParams(string $sStyle, array $aParams)
	{
		if (!utils::IsDevelopmentEnvironment()) {
			return;
		}
		$aAllowedParams = $this->GetAllowedParams($sStyle);

		foreach (array_keys($aParams) as $sParamName) {
			if (!in_array($sParamName, $aAllowedParams)) {
				throw new ApplicationException("Unknown parameter $sParamName for DisplayBlock $sStyle");
			}
		}
	}

	public function GetFilter()
	{
		return $this->m_oFilter;
	}

	/**
	 * Constructs a DisplayBlock object from a DBObjectSet already in memory
	 *
	 * @param DBObjectSet $oSet
	 * @param string $sStyle
	 * @param array $aParams
	 *
	 * @return DisplayBlock The DisplayBlock object, or null if the creation failed
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function FromObjectSet(DBObjectSet $oSet, $sStyle, $aParams = array())
	{
		$oDummyFilter = new DBObjectSearch($oSet->GetClass());
		$aKeys = array();
		$oSet->OptimizeColumnLoad(array($oSet->GetClassAlias() => array())); // No need to load all the columns just to get the id
		while($oObject = $oSet->Fetch())
		{
			$aKeys[] = $oObject->GetKey();	
		}
		$oSet->Rewind();
		if (count($aKeys) > 0)
		{
			$oDummyFilter->AddCondition('id', $aKeys, 'IN');
		}
		else
		{
			$oDummyFilter->AddCondition('id', 0, '=');
		}
		$oBlock = new DisplayBlock($oDummyFilter, $sStyle, false, $aParams); // DisplayBlocks built this way are synchronous
		return $oBlock;
	}

	/**
	 * Constructs a DisplayBlock object from an XML template
	 *
	 * @param $sTemplate string The XML template
	 *
	 * @return DisplayBlock The DisplayBlock object, or null if the template is invalid
	 * @throws \ApplicationException
	 * @throws \OQLException
	 */
	public static function FromTemplate($sTemplate)
	{
		$iStartPos = stripos($sTemplate, '<'.self::TAG_BLOCK.' ', 0);
		$iEndPos = stripos($sTemplate, '</'.self::TAG_BLOCK.'>', $iStartPos);
		$iEndTag = stripos($sTemplate, '>', $iStartPos);
		$aParams = array();

		if (($iStartPos === false) || ($iEndPos === false)) {
			return null;
		} // invalid template
		$sITopData = substr($sTemplate, 1 + $iEndTag, $iEndPos - $iEndTag - 1);
		$sITopTag = substr($sTemplate, $iStartPos + strlen('<'.self::TAG_BLOCK), $iEndTag - $iStartPos - strlen('<'.self::TAG_BLOCK));

		$aMatches = array();
		$sBlockClass = "DisplayBlock";
		$bAsynchronous = false;
		$sBlockType = 'list';
		$sEncoding = 'text/serialize';
		if (preg_match('/ type="(.*)"/U', $sITopTag, $aMatches)) {
			$sBlockType = strtolower($aMatches[1]);
		}
		if (preg_match('/ asynchronous="(.*)"/U', $sITopTag, $aMatches)) {
			$bAsynchronous = (strtolower($aMatches[1]) == 'true');
		}
		if (preg_match('/ blockclass="(.*)"/U', $sITopTag, $aMatches)) {
			$sBlockClass = $aMatches[1];
		}
		if (preg_match('/ encoding="(.*)"/U', $sITopTag, $aMatches)) {
			$sEncoding = strtolower($aMatches[1]);
		}
		if (preg_match('/ link_attr="(.*)"/U', $sITopTag, $aMatches)) {
			// The list to display is a list of links to the specified object
			$aParams['link_attr'] = $aMatches[1]; // Name of the Ext. Key that makes this linkage
		}
		if (preg_match('/ target_attr="(.*)"/U', $sITopTag, $aMatches)) {
			// The list to display is a list of links to the specified object
			$aParams['target_attr'] = $aMatches[1]; // Name of the Ext. Key that make this linkage
		}
		if (preg_match('/ object_id="(.*)"/U', $sITopTag, $aMatches)) {
			// The list to display is a list of links to the specified object
			$aParams['object_id'] = $aMatches[1]; // Id of the object to be linked to
		}
		// Parameters contains a list of extra parameters for the block
		// the syntax is param_name1:value1;param_name2:value2;...
		if (preg_match('/ parameters="(.*)"/U', $sITopTag, $aMatches)) {
			$sParameters = $aMatches[1];
			$aPairs = explode(';', $sParameters);
			foreach ($aPairs as $sPair) {
				if (preg_match('/(.*)\:(.*)/', $sPair, $aMatches)) {
					$aParams[trim($aMatches[1])] = trim($aMatches[2]);
				}
			}
		}
		if (!empty($aParams['link_attr'])) {
			// Check that all mandatory parameters are present:
			if (empty($aParams['object_id'])) {
				// if 'links' mode is requested the d of the object to link to must be specified
				throw new ApplicationException(Dict::S('UI:Error:MandatoryTemplateParameter_object_id'));
			}
			if (empty($aParams['target_attr'])) {
				// if 'links' mode is requested the id of the object to link to must be specified
				throw new ApplicationException(Dict::S('UI:Error:MandatoryTemplateParameter_target_attr'));
			}

		}
		$oFilter = null;
		switch ($sEncoding) {
			case 'text/serialize':
				$oFilter = DBSearch::unserialize($sITopData);
				break;

			case 'text/oql':
				$oFilter = DBSearch::FromOQL($sITopData);
				break;
		}

		return new $sBlockClass($oFilter, $sBlockType, $bAsynchronous, $aParams);
	}

	public function DisplayIntoContentBlock(UIContentBlock $oContentBlock, WebPage $oPage, $sId, $aExtraParams = array())
	{
		$oContentBlock->AddSubBlock($this->GetDisplay($oPage, $sId, $aExtraParams));
	}

	public function Display(WebPage $oPage, $sId, $aExtraParams = array())
	{
		$oPage->AddUiBlock($this->GetDisplay($oPage, $sId, $aExtraParams));
	}

	public function GetDisplay(WebPage $oPage, $sId, $aExtraParams = array()): UIContentBlock
	{
		$oHtml = new UIContentBlock($sId);

		$oHtml->AddCSSClass("display_block");
		$aExtraParams = array_merge($aExtraParams, $this->m_aParams);
		$aExtraParams['currentId'] = $sId;
		$sExtraParams = addslashes(str_replace('"', "'",
			json_encode($aExtraParams))); // JSON encode, change the style of the quotes and escape them

		if (isset($aExtraParams['query_params'])) {
			$aQueryParams = $aExtraParams['query_params'];
		} else {
			if (isset($aExtraParams['this->id']) && isset($aExtraParams['this->class'])) {
				$sClass = $aExtraParams['this->class'];
				$iKey = $aExtraParams['this->id'];
				$oObj = MetaModel::GetObject($sClass, $iKey);
				$aQueryParams = array('this->object()' => $oObj);
			} else {
				$aQueryParams = array();
			}
		}

		$sFilter = addslashes($this->m_oFilter->serialize(false, $aQueryParams)); // Used either for asynchronous or auto_reload
		if (!$this->m_bAsynchronous) {
			// render now
			try {
				$oHtml->AddSubBlock($this->GetRenderContent($oPage, $aExtraParams, $sId));
			} catch (Exception $e) {
				if (UserRights::IsAdministrator()) {
					$sExceptionContent = 'Exception thrown:<br><code>'.utils::Sanitize($e->getMessage(), '', utils::ENUM_SANITIZATION_FILTER_STRING).'</code>';

					$oExceptionAlert = AlertUIBlockFactory::MakeForFailure('Cannot display results', $sExceptionContent);
					$oHtml->AddSubBlock($oExceptionAlert);
				}
				ExceptionLog::LogException($e);
			}
		} else {
			// render it as an Ajax (asynchronous) call
			$oHtml->AddCSSClass("loading");
			$oHtml->AddHtml("<p><img src=\"".utils::GetAbsoluteUrlAppRoot()."images/indicator_arrows.gif\"> ".Dict::S('UI:Loading').'</p>');
			$oPage->add_script('
			$.post("ajax.render.php?style='.$this->m_sStyle.'",
			   { operation: "ajax", filter: "'.$sFilter.'", extra_params: "'.$sExtraParams.'" },
			   function(data){
				 $("#'.$sId.'")
				    .empty()
				    .append(data)
				    .removeClass("loading")
                 ;
				}
			 );
			 ');
		}

		if ($this->m_sStyle == static::ENUM_STYLE_LIST) // Search form need to extract result list extra data, the simplest way is to expose this configuration
		{
			$listJsonExtraParams = json_encode(json_encode($aExtraParams));
			$oPage->add_ready_script("
            $('#$sId').data('sExtraParams', ".$listJsonExtraParams.");
            ");
		}

		return $oHtml;
	}

	/**
	 * @param WebPage $oPage
	 * @param array $aExtraParams
	 *
	 * @throws \ApplicationException
	 * @throws \CoreException
	 * @throws \CoreWarning
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 */
	public function RenderContent(WebPage $oPage, $aExtraParams = array())
	{
		if (!isset($aExtraParams['currentId'])) {
			$sId = utils::GetUniqueId(); // Works only if the page is not an Ajax one !
		} else {
			$sId = $aExtraParams['currentId'];
		}
		$oPage->AddUiBlock($this->GetRenderContent($oPage, $aExtraParams, $sId));
	}

	/**
	 * @param WebPage $oPage
	 * @param array $aExtraParams
	 * @param $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock
	 * @throws ApplicationException
	 * @throws CoreException
	 * @throws CoreWarning
	 * @throws DictExceptionMissingString
	 * @throws MySQLException
	 * @throws Exception
	 *
	 * @since 2.7.7 3.0.1 3.1.0 N°3129 add type hinting to $aExtraParams
	 */
	public function GetRenderContent(WebPage $oPage, array $aExtraParams, string $sId)
	{
		$sHtml = '';
		$oBlock = null;
		$this->CheckParams($this->m_sStyle, $aExtraParams);
		// Add the extra params into the filter if they make sense for such a filter
		$bDoSearch = utils::ReadParam('dosearch', false);
		$aQueryParams = array();
		if (isset($aExtraParams['query_params'])) {
			$aQueryParams = $aExtraParams['query_params'];
		} else {
			if (isset($aExtraParams['this->id']) && isset($aExtraParams['this->class'])) {
				$sClass = $aExtraParams['this->class'];
				$iKey = $aExtraParams['this->id'];
				$oObj = MetaModel::GetObject($sClass, $iKey);
				$aQueryParams = array('this->object()' => $oObj);
			}
		}
		if ($this->m_oSet == null) {

			// In case of search, the context filtering is done by the search itself
			if (($this->m_sStyle != 'links') && ($this->m_sStyle != static::ENUM_STYLE_SEARCH) && ($this->m_sStyle != static::ENUM_STYLE_LIST_SEARCH)) {
				$oAppContext = new ApplicationContext();
				$sClass = $this->m_oFilter->GetClass();
				$aFilterCodes = MetaModel::GetFiltersList($sClass);
				$aCallSpec = array($sClass, 'MapContextParam');
				if (is_callable($aCallSpec)) {
					foreach ($oAppContext->GetNames() as $sContextParam) {
						$sParamCode = call_user_func($aCallSpec, $sContextParam); //Map context parameter to the value/filter code depending on the class
						if (!is_null($sParamCode)) {
							$sParamValue = $oAppContext->GetCurrentValue($sContextParam, null);
							if (!is_null($sParamValue)) {
								$aExtraParams[$sParamCode] = $sParamValue;
							}
						}
					}
				}
				foreach ($aFilterCodes as $sFilterCode) {
					$externalFilterValue = utils::ReadParam($sFilterCode, '', false, 'raw_data');
					$condition = null;
					$bParseSearchString = true;
					if (isset($aExtraParams[$sFilterCode])) {
						$bParseSearchString = false;
						$condition = $aExtraParams[$sFilterCode];
					}
					if ($bDoSearch && $externalFilterValue != "") {
						// Search takes precedence over context params...
						$bParseSearchString = true;
						unset($aExtraParams[$sFilterCode]);
						if (!is_array($externalFilterValue)) {
							$condition = trim($externalFilterValue);
						} else {
							if (count($externalFilterValue) == 1) {
								$condition = trim($externalFilterValue[0]);
							} else {
								$condition = $externalFilterValue;
							}
						}
					}

					if (!is_null($condition))
					{
						$sOpCode = null; // default operator
						if (is_array($condition))
						{
							// Multiple values, add them as AND X IN (v1, v2, v3...)
							$sOpCode = 'IN';
						}

						$this->AddCondition($sFilterCode, $condition, $sOpCode, $bParseSearchString);
					}
				}
				if ($bDoSearch)
				{
					// Keep the table_id identifying this table if we're performing a search
					$sTableId = utils::ReadParam('_table_id_', null, false, 'raw_data');
					if ($sTableId != null)
					{
						$aExtraParams['table_id'] = $sTableId;
					}
				}
			}
			$aOrderBy = array();
			if (isset($aExtraParams['order_by']))
			{
				// Convert the string describing the order_by parameter into an array
				// The syntax is +attCode1,-attCode2
				// attCode1 => ascending, attCode2 => descending
				$aTemp = explode(',', $aExtraParams['order_by']);
				foreach($aTemp as $sTemp)
				{
					$aMatches = array();
					if (preg_match('/^([+-])?(.+)$/', $sTemp, $aMatches)) {
						$bAscending = true;
						if ($aMatches[1] == '-') {
							$bAscending = false;
						}
						$aOrderBy[$aMatches[2]] = $bAscending;
					}
				}
			}

			$aExtraParams['query_params'] = $this->m_oFilter->GetInternalParams();
			$this->m_oSet = new CMDBObjectSet($this->m_oFilter, $aOrderBy, $aQueryParams);
		}
		$this->m_oSet->SetShowObsoleteData($this->m_bShowObsoleteData);

		switch($this->m_sStyle) {
			case static::ENUM_STYLE_LIST_SEARCH:
			case static::ENUM_STYLE_LIST:
				break;
			default:
				// N°3473: except for 'list_search' and 'list' (which have more granularity, see the other switch below),
				// refuse to render if the user is not allowed to see the class.
				if (! UserRights::IsActionAllowed($this->m_oSet->GetClass(), UR_ACTION_READ, $this->m_oSet) == UR_ALLOWED_YES) {
					$sHtml .= $oPage->GetP(Dict::Format('UI:Error:ReadNotAllowedOn_Class', $this->m_oSet->GetClass()));

					return new Html($sHtml);
				}
		}

		switch ($this->m_sStyle) {
			case static::ENUM_STYLE_COUNT:
				$oBlock = $this->RenderCount($aExtraParams);
				break;

			case static::ENUM_STYLE_JOIN:
				$oBlock = $this->RenderJoin($aExtraParams, $oPage);
				break;

			case static::ENUM_STYLE_LIST_SEARCH:
				$oBlock = $this->RenderListSearch($aExtraParams, $oPage);
				break;

			case static::ENUM_STYLE_LIST:
			case static::ENUM_STYLE_LIST_IN_OBJECT:
				$oBlock = $this->RenderList($aExtraParams, $oPage);
			break;

			case 'links':
				$oBlock = $this->RenderLinks($oPage, $aExtraParams);
				break;

			case static::ENUM_STYLE_ACTIONS:
				$oBlock = $this->RenderActions($aExtraParams);
				break;

			case static::ENUM_STYLE_SUMMARY:
				$oBlock = $this->RenderSummary($aExtraParams);
				break;

			case static::ENUM_STYLE_CSV:
				$oBlock = $this->RenderCsv($oAppContext);
				break;

			case static::ENUM_STYLE_SEARCH:
				$oBlock = $this->RenderSearch($oPage, $aExtraParams);
				break;

			case static::ENUM_STYLE_CHART:
				$oBlock = $this->RenderChart($sId, $aQueryParams, $aExtraParams);
				break;
			
			case static::ENUM_STYLE_CHART_AJAX:
				$oBlock = $this->RenderChartAjax($aExtraParams);
				break;
			
			default:
			// Unsupported style, do nothing.
			$sHtml .= Dict::format('UI:Error:UnsupportedStyleOfBlock', $this->m_sStyle);
		}


		$bAutoReload = false;
		if (isset($aExtraParams['auto_reload']))
		{
			if ($aExtraParams['auto_reload'] === true)
			{
				// Note: does not work in the switch (case true) because a positive number evaluates to true!!!
				$aExtraParams['auto_reload'] = 'standard';
			}
			switch($aExtraParams['auto_reload'])
			{
				case 'fast':
					$bAutoReload = true;
					$iReloadInterval = MetaModel::GetConfig()->GetFastReloadInterval()*1000;
					break;

				case 'standard':
				case 'true':
					$bAutoReload = true;
					$iReloadInterval = MetaModel::GetConfig()->GetStandardReloadInterval()*1000;
					break;

				default:
					if (is_numeric($aExtraParams['auto_reload']) && ($aExtraParams['auto_reload'] > 0))
					{
						$bAutoReload = true;
						$iReloadInterval = max(MetaModel::GetConfig()->Get('min_reload_interval'), $aExtraParams['auto_reload'])*1000;
					}
					else
					{
						// incorrect config, ignore it
						$bAutoReload = false;
					}
			}
		}
		if (($bAutoReload) && ($this->m_sStyle != static::ENUM_STYLE_SEARCH)) // Search form do NOT auto-reload
		{
			// Used either for asynchronous or auto_reload
			// does a json_encode twice to get a string usable as function parameter
			$sFilterBefore = $this->m_oFilter->serialize();
			$sFilter = json_encode($sFilterBefore);
			$sExtraParams = json_encode(json_encode($aExtraParams));

			$oPage->add_script(
				<<<JS
if (typeof window.oAutoReloadBlock == "undefined") {
    window.oAutoReloadBlock = {};
}
if (typeof window.oAutoReloadBlock['$sId'] != "undefined") {
    clearInterval(window.oAutoReloadBlock['$sId']);
}

window.oAutoReloadBlock['$sId'] = setInterval(function() {
	ReloadBlock('$sId', '{$this->m_sStyle}', $sFilter, $sExtraParams);
}, '$iReloadInterval');
JS
			);
		}

		if (!empty($oBlock)) {
			return $oBlock;
		}

		return new Html($sHtml);
	}

	/**
	 * Add a condition (restriction) to the current DBSearch on which the display block is based
	 * taking into account the hierarchical keys for which the condition is based on the 'below' operator
	 *
	 * @param string $sFilterCode
	 * @param array $condition
	 * @param string $sOpCode
	 * @param bool $bParseSearchString
	 *
	 * @throws \CoreException
	 * @throws \CoreWarning
	 * @throws \Exception
	 */
	protected function AddCondition($sFilterCode, $condition, $sOpCode = null, $bParseSearchString = false)
	{
		// Workaround to an issue revealed whenever a condition on org_id is applied twice (with a hierarchy of organizations)
		// Moreover, it keeps the query as simple as possible
		if (isset($this->m_aConditions[$sFilterCode]) && $condition == $this->m_aConditions[$sFilterCode])
		{
			// Skip
			return;
		}
		$this->m_aConditions[$sFilterCode] = $condition;

		$sClass = $this->m_oFilter->GetClass();
		$bConditionAdded = false;
		
		// If the condition is an external key with a class having a hierarchy, use a "below" criteria
		if (MetaModel::IsValidAttCode($sClass, $sFilterCode))
		{
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sFilterCode);

			if ($oAttDef->IsExternalKey())
			{
				$sHierarchicalKeyCode = MetaModel::IsHierarchicalClass($oAttDef->GetTargetClass());
				
				if ($sHierarchicalKeyCode !== false)
				{
					$oFilter = new DBObjectSearch($oAttDef->GetTargetClass());
					if (($sOpCode == 'IN') && is_array($condition))
					{
						$oFilter->AddConditionExpression(self::GetConditionIN($oFilter, 'id', $condition));						
					}
					else
					{
						$oFilter->AddCondition('id', $condition);
					}
					$oHKFilter = new DBObjectSearch($oAttDef->GetTargetClass());
					$oHKFilter->AddCondition_PointingTo($oFilter, $sHierarchicalKeyCode, TREE_OPERATOR_BELOW); // Use the 'below' operator by default
					$this->m_oFilter->AddCondition_PointingTo($oHKFilter, $sFilterCode);
					$bConditionAdded = true;
				}
				else if (($sOpCode == 'IN') && is_array($condition))
				{
					$this->m_oFilter->AddConditionExpression(self::GetConditionIN($this->m_oFilter, $sFilterCode, $condition));
					$bConditionAdded = true;
				}
			}
			else if (($sOpCode == 'IN') && is_array($condition))
			{
				$this->m_oFilter->AddConditionExpression(self::GetConditionIN($this->m_oFilter, $sFilterCode, $condition));
				$bConditionAdded = true;
			}
		}
		
		// In all other cases, just add the condition directly
		if (!$bConditionAdded)
		{
			$this->m_oFilter->AddCondition($sFilterCode, $condition, null); // Use the default 'loose' operator
		}
	}
	
	static protected function GetConditionIN($oFilter, $sFilterCode, $condition)
	{
		$oField = new FieldExpression($sFilterCode,  $oFilter->GetClassAlias());
		$sListExpr = '('.implode(', ', CMDBSource::Quote($condition)).')';
		$sOQLCondition = $oField->RenderExpression()." IN $sListExpr";
		$oNewCondition = Expression::FromOQL($sOQLCondition);
		return $oNewCondition;		
	}

	/**
	 * For the result to be meaningful, this function must be called AFTER GetRenderContent() (or Display())
	 * @return int
	 */
	public function GetDisplayedCount()
	{
		return $this->m_oSet->Count();
	}

	/**
	 * @param $aExtraParams
	 * @param $oGroupByExp
	 * @param $sGroupByLabel
	 * @param $aGroupBy
	 * @param $sAggregationFunction
	 * @param $sFctVar
	 * @param $sAggregationAttr
	 * @param $sSql
	 *
	 * @throws \Exception
	 */
	protected function MakeGroupByQuery(&$aExtraParams, &$oGroupByExp, &$sGroupByLabel, &$aGroupBy, &$sAggregationFunction, &$sFctVar, &$sAggregationAttr, &$sSql)
	{
		$sAlias = $this->m_oFilter->GetClassAlias();
		if (isset($aExtraParams['group_by_label']))
		{
			$oGroupByExp = Expression::FromOQL($aExtraParams['group_by']);
			$sGroupByLabel = $aExtraParams['group_by_label'];
		}
		else
		{
			// Backward compatibility: group_by is simply a field id
			$oGroupByExp = new FieldExpression($aExtraParams['group_by'], $sAlias);
			$sGroupByLabel = MetaModel::GetLabel($this->m_oFilter->GetClass(), $aExtraParams['group_by']);
		}

		// Security filtering
		$aFields = $oGroupByExp->ListRequiredFields();
		foreach($aFields as $sFieldAlias)
		{
			$aMatches = array();
			if (preg_match('/^([^.]+)\\.([^.]+)$/', $sFieldAlias, $aMatches))
			{
				$sFieldClass = $this->m_oFilter->GetClassName($aMatches[1]);
				$oAttDef = MetaModel::GetAttributeDef($sFieldClass, $aMatches[2]);
				if ($oAttDef instanceof AttributeOneWayPassword)
				{
					throw new Exception('Grouping on password fields is not supported.');
				}
			}
		}

		$aGroupBy = array();
		$aGroupBy['grouped_by_1'] = $oGroupByExp;
		$aQueryParams = array();
		if (isset($aExtraParams['query_params']))
		{
			$aQueryParams = $aExtraParams['query_params'];
		}
		$aFunctions = array();
		$sAggregationFunction = 'count';
		$sFctVar = '_itop_count_';
		$sAggregationAttr = '';
		if (isset($aExtraParams['aggregation_function']) && !empty($aExtraParams['aggregation_attribute']))
		{
			$sAggregationFunction = $aExtraParams['aggregation_function'];
			$sAggregationAttr = $aExtraParams['aggregation_attribute'];
			$oAttrExpr = Expression::FromOQL('`'.$sAlias.'`.`'.$sAggregationAttr.'`');
			$oFctExpr = new FunctionExpression(strtoupper($sAggregationFunction), array($oAttrExpr));
			$sFctVar = '_itop_'.$sAggregationFunction.'_';
			$aFunctions = array($sFctVar => $oFctExpr);
		}

		if (!empty($sAggregationAttr))
		{
			$sClass = $this->m_oFilter->GetClass();
			$sAggregationAttr = MetaModel::GetLabel($sClass, $sAggregationAttr);
		}
		$iLimit = 0;
		if (isset($aExtraParams['limit']))
		{
			$iLimit = intval($aExtraParams['limit']);
		}
		$aOrderBy = array();
		if (isset($aExtraParams['order_direction']) && isset($aExtraParams['order_by']))
		{
			switch ($aExtraParams['order_by'])
			{
				case 'attribute':
					$aOrderBy = array('grouped_by_1' => ($aExtraParams['order_direction'] === 'asc'));
					break;
				case 'function':
					$aOrderBy = array($sFctVar => ($aExtraParams['order_direction'] === 'asc'));
					break;
			}
		}

		$sSql = $this->m_oFilter->MakeGroupByQuery($aQueryParams, $aGroupBy, true, $aFunctions, $aOrderBy, $iLimit);
	}

	/**
	 * @param array $aExtraParams
	 *
	 * @return iUIBlock
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreWarning
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	protected function RenderSummary(array $aExtraParams): iUIBlock
	{
		$sClass = $this->m_oFilter->GetClass();
		$oAppContext = new ApplicationContext();
		$sStateAttrCode = isset($aExtraParams['status[block]']) ? $aExtraParams['status[block]'] : 'status';
		$sStatesList = isset($aExtraParams['status_codes[block]']) ? $aExtraParams['status_codes[block]'] : '';

		$bContextFilter = isset($aExtraParams['context_filter']) ? isset($aExtraParams['context_filter']) != 0 : false;
		if ($bContextFilter) {
			foreach ($oAppContext->GetNames() as $sFilterCode) {
				$sContextParamValue = $oAppContext->GetCurrentValue($sFilterCode, null);
				if (!is_null($sContextParamValue) && !empty($sContextParamValue) && MetaModel::IsValidFilterCode($sClass, $sFilterCode)) {
					$this->AddCondition($sFilterCode, $sContextParamValue);
				}
			}
			$aQueryParams = array();
			if (isset($aExtraParams['query_params'])) {
				$aQueryParams = $aExtraParams['query_params'];
			}
			$this->m_oSet = new CMDBObjectSet($this->m_oFilter, array(), $aQueryParams);
			$this->m_oSet->SetShowObsoleteData($this->m_bShowObsoleteData);
		}

		// Summary details
		$aCounts = array();
		$aStateLabels = array();
		if (!empty($sStateAttrCode) && !empty($sStatesList)) {
			$aStates = explode(',', $sStatesList);

			// Generate one count + group by query [#1330]
			$sClassAlias = $this->m_oFilter->GetClassAlias();
			$oGroupByExpr = Expression::FromOQL($sClassAlias.'.'.$sStateAttrCode);
			$aGroupBy = array('group1' => $oGroupByExpr);
			$oGroupBySearch = $this->m_oFilter->DeepClone();
			if (isset($this->m_bShowObsoleteData)) {
				$oGroupBySearch->SetShowObsoleteData($this->m_bShowObsoleteData);
			}
			$sCountGroupByQuery = $oGroupBySearch->MakeGroupByQuery($aQueryParams, $aGroupBy, false);
			$aCountGroupByResults = CMDBSource::QueryToArray($sCountGroupByQuery);
			$aCountsQueryResults = array();
			foreach ($aCountGroupByResults as $aCountGroupBySingleResult) {
				$aCountsQueryResults[$aCountGroupBySingleResult[0]] = $aCountGroupBySingleResult[1];
			}

			$oAttDef = MetaModel::GetAttributeDef($sClass, $sStateAttrCode);
			$aValues = $oAttDef->GetAllowedValues();
			foreach ($aStates as $sStateValue) {
				$aStateLabels[$sStateValue] = $aValues[$sStateValue] ?? '';
				$aCounts[$sStateValue] = (array_key_exists($sStateValue, $aCountsQueryResults))
					? $aCountsQueryResults[$sStateValue]
					: 0;

				if ($aCounts[$sStateValue] == 0) {
					$aCounts[$sStateValue] = ['link' => '-', 'label' => $aCounts[$sStateValue]];;
				} else {
					$oSingleGroupByValueFilter = $this->m_oFilter->DeepClone();
					$oSingleGroupByValueFilter->AddCondition($sStateAttrCode, $sStateValue, '=');
					if (isset($this->m_bShowObsoleteData)) {
						$oSingleGroupByValueFilter->SetShowObsoleteData($this->m_bShowObsoleteData);
					}
					$sHyperlink = utils::GetAbsoluteUrlAppRoot()
						.'pages/UI.php?operation=search&'.$oAppContext->GetForLink()
						.'&filter='.rawurlencode($oSingleGroupByValueFilter->serialize());
					$aCounts[$sStateValue] = ['link' => $sHyperlink, 'label' => $aCounts[$sStateValue]];
				}
			}
		}

		$oBlock = new UIContentBlockWithJSRefreshCallback(null, ["ibo-dashlet-header-dynamic--container"]);

		// N°6394 Make sure to sort elements as defined in the datamodel
		if (utils::IsNotNullOrEmptyString($sStateAttrCode)) {
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sStateAttrCode);
			$aAllowedValues = $oAttDef->GetAllowedValues() ?? [];
			$AllowedValuesKeys = array_keys($aAllowedValues);

			uksort($aStateLabels, function ($sKey1, $sKey2) use ($AllowedValuesKeys) {
				return array_search($sKey1, $AllowedValuesKeys) > array_search($sKey2, $AllowedValuesKeys) ? 1 : -1;
			});
		}

		foreach ($aStateLabels as $sStateValue => $sStateLabel) {
			$aCount = $aCounts[$sStateValue];
			$sHyperlink = $aCount['link'];
			$sCountLabel = $aCount['label'];

			$oPill = PillFactory::MakeForState($sClass, $sStateValue);
			// N°5849 - Unencode label for ExternalKey attribute because friendlyname is already html encoded thanks to DBObject::GetName() in AttributeExternalKey::GetAllowedValues(). (A fix in this function may have too much impact).
			if ($oAttDef instanceof AttributeExternalKey) {
				$sPillTooltip = utils::EscapedHtmlDecode($sStateLabel);
				$sPillLabel = $sStateLabel;
			} else {
				$sPillTooltip = $sStateLabel;
				$sPillLabel = utils::HtmlEntities($sStateLabel);
			}
			$oPill->SetTooltip($sPillTooltip)
				->AddHtml("<span class=\"ibo-dashlet-header-dynamic--count\">$sCountLabel</span><span class=\"ibo-dashlet-header-dynamic--label ibo-text-truncated-with-ellipsis\">".$sPillLabel."</span>");
			if ($sHyperlink != '-') {
				$oPill->SetUrl($sHyperlink);
			}
			$oBlock->AddSubBlock($oPill);
		}
		$aExtraParams['query_params'] = $this->m_oFilter->GetInternalParams();
		if(isset($aExtraParams['query_params']['this->object()'])){
			$aExtraParams['query_params']['this->class'] = get_class($aExtraParams['query_params']['this->object()']);
			$aExtraParams['query_params']['this->id'] = 	$aExtraParams['query_params']['this->object()']->GetKey();
			unset($aExtraParams['query_params']['this->object()']);
		}
		$aRefreshParams = ['filter' => $this->m_oFilter->ToOQL(), "extra_params" => json_encode($aExtraParams)];
		$oBlock->SetJSRefresh(
			"$('#".$oBlock->GetId()."').block();
				$.post('ajax.render.php?operation=refreshDashletSummary',
				   ".json_encode($aRefreshParams).",
				   function(data){
					 $('#".$oBlock->GetId()."').html(data);
					 $('#".$oBlock->GetId()."').unblock();
					});
				$('#".$oBlock->GetId()."').unblock();");

		return $oBlock;
	}

	/**
	 * @param array $aExtraParams
	 *
	 * @return string[]
	 */
	protected function  GetAllowedActionsParams(array $aExtraParams)
	{
		return [
			'context_filter', /** int if != 0 filter with user context */
			'query_params', /** array query parameters */
		];
	}

	/**
	 * @param array $aExtraParams
	 *
	 * @return iUIBlock
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreWarning
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	protected function RenderActions(array $aExtraParams): iUIBlock
	{
		$sClass = $this->m_oFilter->GetClass();
		$oAppContext = new ApplicationContext();
		$bContextFilter = isset($aExtraParams['context_filter']) ? isset($aExtraParams['context_filter']) != 0 : false;
		if ($bContextFilter && is_null($this->m_oSet)) {
			foreach ($oAppContext->GetNames() as $sFilterCode) {
				$sContextParamValue = $oAppContext->GetCurrentValue($sFilterCode, null);
				if (!is_null($sContextParamValue) && !empty($sContextParamValue) && MetaModel::IsValidFilterCode($sClass, $sFilterCode)) {
					$this->AddCondition($sFilterCode, $sContextParamValue);
				}
			}
			$aQueryParams = array();
			if (isset($aExtraParams['query_params'])) {
				$aQueryParams = $aExtraParams['query_params'];
			}
			$this->m_oSet = new CMDBObjectSet($this->m_oFilter, array(), $aQueryParams);
			$this->m_oSet->SetShowObsoleteData($this->m_bShowObsoleteData);
		}
		$iCount = $this->m_oSet->Count();
		$sClassLabel = MetaModel::GetName($sClass);
		$sClassIconUrl = MetaModel::GetClassIcon($sClass, false);
		$sHyperlink = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=search&'.$oAppContext->GetForLink().'&filter='.rawurlencode($this->m_oFilter->serialize());

		$aExtraParams['query_params'] = $this->m_oFilter->GetInternalParams();
		$aRefreshParams = [
			"filter" => $this->m_oFilter->ToOQL(),
			"extra_params" => $aExtraParams,
		];

		if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY)) {
			$sCreateActionUrl = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=new&class='.$sClass.'&'.$oAppContext->GetForLink();
			$sCreateActionLabel = Dict::Format('UI:Button:Create');
			$oBlock = DashletFactory::MakeForDashletBadge($sClassIconUrl, $sHyperlink, $iCount, $sClassLabel, $sCreateActionUrl,
				$sCreateActionLabel, $aRefreshParams);
		} else {
			$oBlock = DashletFactory::MakeForDashletBadge($sClassIconUrl, $sHyperlink, $iCount, $sClassLabel, null, null, $aRefreshParams);
		}
		$sClassDescription = MetaModel::GetClassDescription($sClass);
		if (utils::IsNotNullOrEmptyString($sClassDescription)) {
			$oBlock->SetClassDescription($sClassDescription);
		}

		return $oBlock;
	}

	/**
	 * @param array $aExtraParams
	 *
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \Exception
	 */
	protected function RenderCount(array $aExtraParams): iUIBlock
	{
		if (isset($aExtraParams['group_by'])) {
			$this->MakeGroupByQuery($aExtraParams, $oGroupByExp, $sGroupByLabel, $aGroupBy, $sAggregationFunction, $sFctVar, $sAggregationAttr, $sSql);

			$aRes = CMDBSource::QueryToArray($sSql);

			$aGroupBy = array();
			$aLabels = array();
			$aValues = array();
			$iTotalCount = 0;
			foreach ($aRes as $iRow => $aRow) {
				$sValue = $aRow['grouped_by_1'];
				$aValues[$iRow] = $sValue;
				$sHtmlValue = $oGroupByExp->MakeValueLabel($this->m_oFilter, $sValue, $sValue);
				$aLabels[$iRow] = $sHtmlValue;
				$aGroupBy[$iRow] = (float)$aRow[$sFctVar];
				$iTotalCount += $aRow['_itop_count_'];
			}

			$aData = array();
			$oAppContext = new ApplicationContext();
			$sParams = $oAppContext->GetForLink();
			foreach ($aGroupBy as $iRow => $iCount) {
				// Build the search for this subset
				$oSubsetSearch = $this->m_oFilter->DeepClone();
				$oCondition = new BinaryExpression($oGroupByExp, '=', new ScalarExpression($aValues[$iRow]));
				$oSubsetSearch->AddConditionExpression($oCondition);
				if (isset($aExtraParams['query_params'])) {
					$aQueryParams = $aExtraParams['query_params'];
				} else {
					$aQueryParams = array();
				}
				$sFilter = rawurlencode($oSubsetSearch->serialize(false, $aQueryParams));

				$aData[] = array(
					'group' => $aLabels[$iRow],
					'value' => "<a href=\"".utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=search&dosearch=1&$sParams&filter=$sFilter\">$iCount</a>"
				); // TO DO: add the context information
			}
			$aAttribs = array(
				'group' => array('label' => $sGroupByLabel, 'description' => ''),
				'value' => array(
					'label' => Dict::S('UI:GroupBy:'.$sAggregationFunction),
					'description' => Dict::Format('UI:GroupBy:'.$sAggregationFunction.'+', $sAggregationAttr),
				),
			);
			$sFormat = isset($aExtraParams['format']) ? $aExtraParams['format'] : 'UI:Pagination:HeaderNoSelection';

			$aExtraParams['query_params'] = $this->m_oFilter->GetInternalParams();
			$aOption['dom'] = 'pl';

			if (isset($aExtraParams["surround_with_panel"]) && $aExtraParams["surround_with_panel"]) {
				$sTitle = Dict::Format($sFormat, $iTotalCount);
				$oBlock = PanelUIBlockFactory::MakeForClass($aExtraParams["panel_class"], $aExtraParams["panel_title"]);
				$oBlock->AddSubTitleBlock(new Html($sTitle));
				$oBlock->AddCSSClass('ibo-datatable-panel');
				if(isset($aExtraParams["panel_icon"]) && strlen($aExtraParams["panel_icon"]) > 0){
					$oBlock->SetIcon($aExtraParams["panel_icon"]);
				}
				$oDataTable = DataTableUIBlockFactory::MakeForStaticData("", $aAttribs, $aData, null, $aExtraParams, $this->m_oFilter->ToOQL(), $aOption);
				$oBlock->AddSubBlock($oDataTable);
			} else {
				$sTitle = Dict::Format($sFormat, $iTotalCount);
				$oBlock = DataTableUIBlockFactory::MakeForStaticData($sTitle, $aAttribs, $aData, null, $aExtraParams, $this->m_oFilter->ToOQL(), $aOption);
			}

		} else {
			// Simply count the number of elements in the set
			$iCount = $this->m_oSet->Count();
			$sFormat = 'UI:CountOfObjects';
			if (isset($aExtraParams['format'])) {
				$sFormat = $aExtraParams['format'];
			}
			if (isset($aExtraParams["surround_with_panel"]) && $aExtraParams["surround_with_panel"]) {
				$oBlock = PanelUIBlockFactory::MakeForClass($aExtraParams["panel_class"], $aExtraParams["panel_title"]);
				if(isset($aExtraParams["panel_icon"]) && strlen($aExtraParams["panel_icon"]) > 0){
					$oBlock->SetIcon($aExtraParams["panel_icon"]);
				}
				$oBlock->AddSubBlock(new Html('<p>'.Dict::Format($sFormat, $iCount).'</p>'));
			} else {
				$oBlock = new Html('<p>'.Dict::Format($sFormat, $iCount).'</p>');
			}
		}

		return $oBlock;
}

	/**
	 * @param WebPage $oPage
	 * @param array $aExtraParams
	 *
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock
	 */
	protected function RenderSearch(WebPage $oPage, array $aExtraParams): iUIBlock
	{
		$oBlock = null;

		if (!$oPage->IsPrintableVersion()) {
			$oSearchForm = new SearchForm();
			$oBlock = $oSearchForm->GetSearchFormUIBlock($oPage, $this->m_oSet, $aExtraParams);
		}

		return $oBlock;
	}

	protected function RenderListSearch(array $aExtraParams, WebPage $oPage)
	{
		return $this->RenderList($aExtraParams, $oPage);
	}

	/**
	 * @param array $aExtraParams
	 * @param WebPage $oPage
	 *
	 * @throws \ArchivedObjectException
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \ReflectionException
	 */
	protected function RenderList(array $aExtraParams, WebPage $oPage)
	{
		$oBlock = new BlockList();
		$aClasses = $this->m_oSet->GetSelectedClasses();
		$aAuthorizedClasses = [];
		$oBlock->bEmptySet = false;
		$oBlock->bNotAuthorized = false;
		$oBlock->bCreateNew = false;
		$oBlock->sLinkTarget = '';
		$oBlock->sClass = '';
		$oBlock->sClassLabel = '';
		$oBlock->sParams = '';
		$oBlock->sDefault = '';
		$oBlock->sEventAttachedData = '';
		$oBlock->sAbsoluteUrlAppRoot = utils::GetAbsoluteUrlAppRoot();
		$oBlock->aExtraParams = $aExtraParams;
		$oBlock->sFilter = $this->m_oFilter->ToOQL();


		// Check the classes that can be read (i.e authorized) by this user...
		foreach ($aClasses as $sAlias => $sClassName) {
			if (UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $this->m_oSet) != UR_ALLOWED_NO) {
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}

		if (count($aAuthorizedClasses) === 0) {
			// Not authorized
			$oBlock->bNotAuthorized = true;
			$oBlock->bEmptySet = true;
			$oBlock->sClass = $this->m_oFilter->GetClass();
			$oBlock->sClassLabel = MetaModel::GetName($oBlock->sClass);

			return $oBlock;
		}

		if (isset($aExtraParams['update_history']) && true == $aExtraParams['update_history']) {
			$sSearchFilter = $this->m_oSet->GetFilter()->serialize();
			// Limit the size of the URL (N°1585 - request uri too long)
			if (strlen($sSearchFilter) < SERVER_MAX_URL_LENGTH) {
				$oBlock->sEventAttachedData = json_encode(array(
					'filter' => $sSearchFilter,
					'breadcrumb_id' => "ui-search-".$this->m_oSet->GetClass(),
					'breadcrumb_label' => MetaModel::GetName($this->m_oSet->GetClass()),
					'breadcrumb_max_count' => utils::GetConfig()->Get('breadcrumb.max_count'),
					'breadcrumb_instance_id' => MetaModel::GetConfig()->GetItopInstanceid(),
					'breadcrumb_icon' => 'fas fa-search',
					'breadcrumb_icon_type' => iTopWebPage::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES,
				));
			}
		}

		$iListId = utils::IsNullOrEmptyString($aExtraParams['currentId']) ? utils::GetUniqueId() /* Works only if not in an Ajax page !! */ : $aExtraParams['currentId'];
		// Note: Method calls not factorized into a single call with method name as a variable to keep track of DataTableUIBlockFactory calls as it is a critical / unstable component.
		$oBlock->AddSubBlock(
			$this->m_sStyle === static::ENUM_STYLE_LIST_IN_OBJECT ?
			DataTableUIBlockFactory::MakeForObject($oPage, $iListId, $this->m_oSet, $aExtraParams) :
			DataTableUIBlockFactory::MakeForResult($oPage, $iListId, $this->m_oSet, $aExtraParams)
		);

		return $oBlock;
	}

	/**
	 * @param array $aExtraParams
	 * @param WebPage $oPage
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\UIContentBlock
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	protected function RenderJoin(array $aExtraParams, WebPage $oPage)
	{
		$oContentBlock = new UIContentBlock();
		$oHtml = new Html();
		$oContentBlock->AddSubBlock($oHtml);
		$aDisplayAliases = isset($aExtraParams['display_aliases']) ? explode(',', $aExtraParams['display_aliases']) : array();
		if (!isset($aExtraParams['group_by'])) {
			$oHtml->AddHtml('<p>'.Dict::S('UI:Error:MandatoryTemplateParameter_group_by').'</p>');
		} else {
			$aGroupByFields = array();
			$aGroupBy = explode(',', $aExtraParams['group_by']);
			foreach ($aGroupBy as $sGroupBy) {
				$aMatches = array();
				if (preg_match('/^(.+)\.(.+)$/', $sGroupBy, $aMatches) > 0) {
					$aGroupByFields[] = array('alias' => $aMatches[1], 'att_code' => $aMatches[2]);
				}
			}
			if (count($aGroupByFields) == 0) {
				$oHtml->AddHtml('<p>'.Dict::Format('UI:Error:InvalidGroupByFields', $aExtraParams['group_by']).'</p>');
			} else {
				$aResults = array();
				$aCriteria = array();
				while ($aObjects = $this->m_oSet->FetchAssoc()) {
					$aKeys = array();
					foreach ($aGroupByFields as $aField) {
						$sAlias = $aField['alias'];
						if (is_null($aObjects[$sAlias])) {
							$aKeys[$sAlias.'.'.$aField['att_code']] = '';
						} else {
							$aKeys[$sAlias.'.'.$aField['att_code']] = $aObjects[$sAlias]->Get($aField['att_code']);
						}
					}
					$sCategory = implode($aKeys, ' ');
					$aResults[$sCategory][] = $aObjects;
					$aCriteria[$sCategory] = $aKeys;
				}

				$oHtml->AddHtml("<table>\n");
				// Construct a new (parametric) query that will return the content of this block
				$oBlockFilter = $this->m_oFilter->DeepClone();
				$aExpressions = array();
				$index = 0;
				foreach ($aGroupByFields as $aField) {
					$aExpressions[] = '`'.$aField['alias'].'`.`'.$aField['att_code'].'` = :param'.$index++;
				}
				$sExpression = implode(' AND ', $aExpressions);
				$oExpression = Expression::FromOQL($sExpression);
				$oBlockFilter->AddConditionExpression($oExpression);
				$aExtraParams['menu'] = false;
				foreach ($aResults as $sCategory => $aObjects) {
					$oHtml->AddHtml("<tr><td><h1>$sCategory</h1></td></tr>\n");
					if (count($aDisplayAliases) == 1) {
						$aSimpleArray = array();
						foreach ($aObjects as $aRow) {
							$oObj = $aRow[$aDisplayAliases[0]];
							if (!is_null($oObj)) {
								$aSimpleArray[] = $oObj;
							}
						}
						$oSet = CMDBObjectSet::FromArray($this->m_oFilter->GetClass(), $aSimpleArray);
						$oHtml->AddHtml("<tr><td>");
						$oBlock = cmdbAbstractObject::GetDisplaySetBlock($oPage, $oSet, $aExtraParams);
						$oContentBlock->AddSubBlock($oBlock);
						$oHtml = new Html();
						$oContentBlock->AddSubBlock($oHtml);
						$oHtml->AddHtml("</td></tr>\n");
					} else {
						$index = 0;
						$aArgs = array();
						foreach ($aGroupByFields as $aField) {
							$aArgs['param'.$index] = $aCriteria[$sCategory][$aField['alias'].'.'.$aField['att_code']];
							$index++;
						}
						$oSet = new CMDBObjectSet($oBlockFilter, array(), $aArgs);
						if (empty($aExtraParams['currentId'])) {
							$iListId = utils::GetUniqueId(); // Works only if not in an Ajax page !!
						} else {
							$iListId = $aExtraParams['currentId'];
						}

						$oBlock = DataTableUIBlockFactory::MakeForRendering($iListId, $oSet, $aExtraParams);
						$oHtml->AddHtml("<tr><td>");
						$oContentBlock->AddSubBlock($oBlock);
						$oHtml = new Html();
						$oContentBlock->AddSubBlock($oHtml);
						$oHtml->AddHtml("</td></tr>\n");
					}
				}
				$oHtml->AddHtml("</table>\n");
			}
		}
		return $oContentBlock;
	}

	/**
	 * @deprecated 3.1.0 N°5957
	 *
	 * @param WebPage $oPage
	 * @param array $aExtraParams
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Html\Html|\Combodo\iTop\Application\UI\Base\Layout\UIContentBlock|string
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \ReflectionException
	 */
	protected function RenderLinks(WebPage $oPage, array $aExtraParams)
	{
		// Note: No deprecation ticket yet as we want to wait and see if people / code actually use this method, in which case we might keep it.
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('This method is most likely not used throughout the application and will be removed soon. If you ever see this message, please inform us.');

		$oBlock = null;
		if (($this->m_oSet->CountWithLimit(1) > 0) && (UserRights::IsActionAllowed($this->m_oSet->GetClass(), UR_ACTION_READ, $this->m_oSet) == UR_ALLOWED_YES)) {
			$oBlock = cmdbAbstractObject::GetDisplaySetBlock($oPage, $this->m_oSet, $aExtraParams);
		} else {
			$sClass = $this->m_oFilter->GetClass();
			$oAttDef = MetaModel::GetAttributeDef($sClass, $this->m_aParams['target_attr']);
			$sTargetClass = $oAttDef->GetTargetClass();
			$oBlock = new Html('<p>'.Dict::Format('UI:NoObject_Class_ToDisplay', MetaModel::GetName($sTargetClass)).'</p>');
			$bDisplayMenu = isset($this->m_aParams['menu']) ? $this->m_aParams['menu'] == true : true;
			if ($bDisplayMenu) {
				if ((UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES)) {
					$sDefaults = '';
					if (isset($this->m_aParams['default'])) {
						foreach ($this->m_aParams['default'] as $sName => $sValue) {
							$sDefaults .= '&'.urlencode($sName).'='.urlencode($sValue);
						}
					}
					$oBlock->AddHtml("<p><a href=\"".utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=modify_links&class=$sClass&sParams&link_attr=".$aExtraParams['link_attr']."&id=".$aExtraParams['object_id']."&target_class=$sTargetClass&addObjects=true$sDefaults\">".Dict::Format('UI:ClickToCreateNew',
							Metamodel::GetName($sClass))."</a></p>\n");
				}
			}
		}
		return $oBlock;
	}

	/**
	 * @param string|null $sChartId
	 * @param array $aQueryParams
	 *
	 * @param array $aExtraParams
	 *
	 * @return \Combodo\iTop\Application\UI\DisplayBlock\BlockChart\BlockChart
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	protected function RenderChart(?string $sChartId, array $aQueryParams, array $aExtraParams)
	{
		static $iChartCounter = 0;
		$iChartCounter++;

		$oBlock = new BlockChart();

		$oBlock->iChartCounter = $iChartCounter;
		$oBlock->sChartId = $sChartId;

		$sChartType = isset($aExtraParams['chart_type']) ? $aExtraParams['chart_type'] : 'pie';
		$sGroupBy = isset($aExtraParams['group_by']) ? $aExtraParams['group_by'] : '';
		$sGroupByExpr = isset($aExtraParams['group_by_expr']) ? '&params[group_by_expr]='.$aExtraParams['group_by_expr'] : '';
		$sFilter = $this->m_oFilter->serialize(false, $aQueryParams);
		$oContext = new ApplicationContext();
		$sContextParam = $oContext->GetForLink();
		$sAggregationFunction = isset($aExtraParams['aggregation_function']) ? $aExtraParams['aggregation_function'] : '';
		$sAggregationAttr = isset($aExtraParams['aggregation_attribute']) ? $aExtraParams['aggregation_attribute'] : '';
		$sLimit = isset($aExtraParams['limit']) ? $aExtraParams['limit'] : '';
		$sOrderBy = isset($aExtraParams['order_by']) ? $aExtraParams['order_by'] : '';
		$sOrderDirection = isset($aExtraParams['order_direction']) ? $aExtraParams['order_direction'] : '';

		if (isset($aExtraParams['group_by_label'])) {
			$sUrl = utils::GetAbsoluteUrlAppRoot()."pages/ajax.render.php?operation=chart&params[group_by]=$sGroupBy{$sGroupByExpr}&params[group_by_label]={$aExtraParams['group_by_label']}&params[chart_type]=$sChartType&params[currentId]=$sChartId{$iChartCounter}&params[order_direction]=$sOrderDirection&params[order_by]=$sOrderBy&params[limit]=$sLimit&params[aggregation_function]=$sAggregationFunction&params[aggregation_attribute]=$sAggregationAttr&id=$sChartId{$iChartCounter}&filter=".rawurlencode($sFilter).'&'.$sContextParam;
		} else {
			$sUrl = utils::GetAbsoluteUrlAppRoot()."pages/ajax.render.php?operation=chart&params[group_by]=$sGroupBy{$sGroupByExpr}&params[chart_type]=$sChartType&params[currentId]=$sChartId{$iChartCounter}&params[order_direction]=$sOrderDirection&params[order_by]=$sOrderBy&params[limit]=$sLimit&params[aggregation_function]=$sAggregationFunction&params[aggregation_attribute]=$sAggregationAttr&id=$sChartId{$iChartCounter}&filter=".rawurlencode($sFilter).'&'.$sContextParam;
		}

		$oBlock->sUrl = $sUrl;

		if (isset($aExtraParams["surround_with_panel"]) && $aExtraParams["surround_with_panel"]) {
			$oPanel = PanelUIBlockFactory::MakeForClass($aExtraParams["panel_class"], $aExtraParams["panel_title"]);
			if(isset($aExtraParams["panel_icon"]) && strlen($aExtraParams["panel_icon"]) > 0){
				$oPanel->SetIcon($aExtraParams["panel_icon"]);
			}
			$oPanel->AddSubBlock($oBlock);

			return $oPanel;
		}

		return $oBlock;
	}

	/**
	 * @param array $aExtraParams
	 * @param WebPage $oPage
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	protected function RenderChartAjax(array $aExtraParams)
	{
		$sChartType = isset($aExtraParams['chart_type']) ? $aExtraParams['chart_type'] : 'pie';
		$sId = utils::ReadParam('id', '');
		$aValues = array();
		$oBlock = null;
		$sJSURLs = '';

		if (isset($aExtraParams['group_by'])) {
			$this->MakeGroupByQuery($aExtraParams, $oGroupByExp, $sGroupByLabel, $aGroupBy, $sAggregationFunction, $sFctVar, $sAggregationAttr, $sSql);
			$aRes = CMDBSource::QueryToArray($sSql);
			$oContext = new ApplicationContext();
			$sContextParam = $oContext->GetForLink();

			$iTotalCount = 0;
			$aURLs = array();

			foreach ($aRes as $iRow => $aRow) {
				$sValue = $aRow['grouped_by_1'];
				$sHtmlValue = $oGroupByExp->MakeValueLabel($this->m_oFilter, $sValue, $sValue);
				$iTotalCount += $aRow['_itop_count_'];
				$aValues[] = array(
					'label' => html_entity_decode(strip_tags($sHtmlValue), ENT_QUOTES, 'UTF-8'),
					'label_html' => $sHtmlValue,
					'value' => (float)$aRow[$sFctVar],
				);


				// Build the search for this subset
				$oSubsetSearch = $this->m_oFilter->DeepClone();
				$oCondition = new BinaryExpression($oGroupByExp, '=', new ScalarExpression($sValue));
				$oSubsetSearch->AddConditionExpression($oCondition);
				$aURLs[] = utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=search&format=html&filter=".rawurlencode($oSubsetSearch->serialize()).'&'.$sContextParam;
			}
			$sJSURLs = json_encode($aURLs);
		}
		if (isset($aExtraParams['group_by_label'])) {
			$sUrl = utils::GetAbsoluteUrlAppRoot()."pages/ajax.render.php?operation=chart&params[group_by]=$aExtraParams[group_by]&params[group_by_label]={$aExtraParams['group_by_label']}&params[chart_type]=$sChartType&params[currentId]=$aExtraParams[currentId]&params[order_direction]=$aExtraParams[order_direction]&params[order_by]=$aExtraParams[order_by]&params[limit]=$aExtraParams[limit]&params[aggregation_function]=$sAggregationFunction&params[aggregation_attribute]=$sAggregationAttr&id=$sId&filter=".rawurlencode($this->m_oFilter->ToOQL()).'&'.$sContextParam;
		} else {
			$sUrl = utils::GetAbsoluteUrlAppRoot()."pages/ajax.render.php?operation=chart&params[group_by]=$aExtraParams[group_by]&params[chart_type]=$sChartType&params[currentId]=$aExtraParams[currentId]&params[order_direction]=$aExtraParams[order_direction]&params[order_by]=$aExtraParams[order_by]&params[limit]=$aExtraParams[limit]&params[aggregation_function]=$sAggregationFunction&params[aggregation_attribute]=$sAggregationAttr&id=$sId&filter=".rawurlencode($this->m_oFilter->ToOQL()).'&'.$sContextParam;
		}

		switch ($sChartType) {
			case 'bars':
				$iMaxNbCharsInLabel = 0;
				$aNames = [];
				foreach ($aValues as $idx => $aValue) {
					$aNames[$idx] = $aValue['label'];
					if ($iMaxNbCharsInLabel < mb_strlen($aValue['label'])) {
						$iMaxNbCharsInLabel = mb_strlen($aValue['label']);
					}
				}
				$oBlock = new BlockChartAjaxBars();
				$oBlock->sJSNames = json_encode($aNames);
				$oBlock->sJson = json_encode($aValues);
				$oBlock->sId = $sId;
				$oBlock->sJSURLs = $sJSURLs;
				$oBlock->sURLForRefresh = str_replace("'", "\'", $sUrl);
				$oBlock->iMaxNbCharsInLabel = $iMaxNbCharsInLabel;
				break;

			case 'pie':
				$aColumns = [];
				$aNames = [];
				foreach ($aValues as $idx => $aValue) {
					$aColumns[] = array('series_'.$idx, (float)$aValue['value']);
					$aNames['series_'.$idx] = $aValue['label'];
				}

				$iNbLinesToAddForName = 0;
				if (count($aNames) > 50) {
					// Calculation of the number of legends line add to the height of the graph to have a maximum of 5 legend columns
					$iNbLinesIncludedInChartHeight = 10;
					$iNbLinesToAddForName = ceil(count($aNames) / 5) - $iNbLinesIncludedInChartHeight;
				}

				$oBlock = new BlockChartAjaxPie();
				$oBlock->sJSColumns = json_encode($aColumns);
				$oBlock->sJSNames = json_encode($aNames);
				$oBlock->sId = $sId;
				$oBlock->sJSURLs = $sJSURLs;
				$oBlock->sURLForRefresh = str_replace("'", "\'", $sUrl);
				$oBlock->iNbLinesToAddForName = $iNbLinesToAddForName;
				break;
		}
		if (isset($aExtraParams["surround_with_panel"]) && $aExtraParams["surround_with_panel"]) {
			$oPanel = PanelUIBlockFactory::MakeForClass($aExtraParams["panel_class"], $aExtraParams["panel_title"]);
			if(isset($aExtraParams["panel_icon"]) && strlen($aExtraParams["panel_icon"]) > 0){
				$oPanel->SetIcon($aExtraParams["panel_icon"]);
			}
			$oPanel->AddSubBlock($oBlock);

			return $oPanel;
		}

		return $oBlock;
	}

	/**
	 * @param \ApplicationContext $oAppContext
	 *
	 * @return iUIBlock
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	protected function RenderCsv(ApplicationContext $oAppContext)
	{
		$oBlock = new BlockCsv();
		$oBlock->bAdvancedMode = utils::ReadParam('advanced', false);

		$oBlock->sCsvFile = strtolower($this->m_oFilter->GetClass()).'.csv';
		$oBlock->sDownloadLink = utils::GetAbsoluteUrlAppRoot().'webservices/export.php?expression='.urlencode($this->m_oFilter->ToOQL(true)).'&format=csv&filename='.urlencode($oBlock->sCsvFile);
		$oBlock->sLinkToToggle = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=search&'.$oAppContext->GetForLink().'&filter='.rawurlencode($this->m_oFilter->serialize()).'&format=csv';
		// Pass the parameters via POST, since expression may be very long
		$aParamsToPost = array(
			'expression' => $this->m_oFilter->ToOQL(true),
			'format' => 'csv',
			'filename' => $oBlock->sCsvFile,
			'charset' => 'UTF-8',
		);
		if ($oBlock->bAdvancedMode) {
			$oBlock->sDownloadLink .= '&fields_advanced=1';
			$aParamsToPost['fields_advanced'] = 1;
			$oBlock->sChecked = 'CHECKED';
		} else {
			$oBlock->sLinkToToggle = $oBlock->sLinkToToggle.'&advanced=1';
			$oBlock->sChecked = '';
		}
		$oBlock->sAjaxLink = utils::GetAbsoluteUrlAppRoot().'webservices/export.php';

		$oBlock->sCharsetNotice = false;
		$oBlock->sJsonParams = json_encode($aParamsToPost);
		return $oBlock;
	}
}

/**
 * Displays the 'Actions' menu for a given (list of) object(s)
 * The 'style' of the list (see constructor of DisplayBlock) can be either 'list' or 'details'
 * For backward compatibility 'popup' is equivalent to 'list'...
 */
class MenuBlock extends DisplayBlock
{
	/**
	 * @var string Prefix to use for the ID of the actions toolbar
	 * @used-by static::GetRenderContent
	 * @since 3.0.0
	 */
	public const ACTIONS_TOOLBAR_ID_PREFIX = 'ibo-actions-toolbar-';

	/**
	 * Renders the "Actions" popup menu for the given set of objects
	 *
	 * Note that the menu links containing (or ending) with a hash (#) will have their fragment
	 * part (whatever is after the hash) dynamically replaced (by javascript) when the menu is
	 * displayed, to correspond to the current hash/fragment in the page. This allows modifying
	 * an object in with the same tab active by default as the tab that was active when selecting
	 * the "Modify..." action.
	 *
	 * @param WebPage $oPage
	 * @param array $aExtraParams
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 *
	 * @since 2.7.7 3.0.1 3.1.0 N°3129 Remove default value and add type hinting on $aExtraParams for PHP 8.0 compatibility
	 */
	public function GetRenderContent(WebPage $oPage, array $aExtraParams, string $sId)
	{
		$oRouter = Router::GetInstance();
		$oRenderBlock = new UIContentBlock();

		if ($this->m_sStyle == 'popup') // popup is a synonym of 'list' for backward compatibility
		{
			$this->m_sStyle = static::ENUM_STYLE_LIST;
		}

		$sClass = $this->GetFilter()->GetClass();
		$aSelectedClasses = $this->GetFilter()->GetSelectedClasses();
		$bIsForLinkset = isset($aExtraParams['target_attr']);
		$oSet = new CMDBObjectSet($this->GetFilter());
		$iSetCount = $oSet->Count();
		/** @var string $sRefreshAction JS snippet to run when clicking on the refresh button of the menu */
		$sRefreshAction = $aExtraParams['refresh_action'] ?? '';
		$bIsCreationInModal = isset($aExtraParams['creation_in_modal']) && $aExtraParams['creation_in_modal'] === true;
		$bIsCreationDisallowed = isset($aExtraParams['creation_disallowed']) && $aExtraParams['creation_disallowed'] === true;

		// Check rights
		$oReflectionClass = new ReflectionClass($sClass);
		$bIsCreationAllowed = (!$bIsCreationDisallowed && UserRights::IsActionAllowed($sClass, UR_ACTION_CREATE) === UR_ALLOWED_YES) && ($oReflectionClass->IsSubclassOf('cmdbAbstractObject'));

		/** @var array $aRegularActions Any action other than a transition */
		$aRegularActions = [];
		/** @var array $aTransitionActions Only transitions */
		$aTransitionActions = [];
		/** @var array $aToolkitActions Any "legacy" toolkit menu item, which are now displayed in the same menu as the $aRegularActions, after them */
		$aToolkitActions = [];

		// Display menu actions only if...
		if (
			// ... NOT in a selection mode
			(!isset($aExtraParams['selection_mode']) || ($aExtraParams['selection_mode'] == ""))
			// ... "menu" parameter is NOT EXPLICITLY disabled
			&& (!isset($aExtraParams['menu']) || $aExtraParams['menu'] === "1" || $aExtraParams['menu'] === true)
		) {
			$oAppContext = new ApplicationContext();
			$sContext = $oAppContext->GetForLink();
			if (utils::IsNotNullOrEmptyString($sContext)) {
				$sContext = '&'.$sContext;
			}


			$sFilter = $this->GetFilter()->serialize();
			$sUIPage = cmdbAbstractObject::ComputeStandardUIPage($sClass);
			$sRootUrl = utils::GetAbsoluteUrlAppRoot();

			// Common params that will be applied to actions
			$aActionParams = $this->GetDefaultParamsForMenuAction();

			// 1:n links, populate the target object as a default value when creating a new linked object
			if ($bIsForLinkset) {
				$aExtraParams['default'][$aExtraParams['target_attr']] = $aExtraParams['object_id'];
			}
			/** @var string $sDefaultValuesAsUrlParams Default values for the object to create, already formatted as URL params (eg. "&default[org_id]=3&default[title]=Foo") */
			$sDefaultValuesAsUrlParams = '';
			if (!empty($aExtraParams['default'])) {
				foreach ($aExtraParams['default'] as $sKey => $sValue) {
					$sDefaultValuesAsUrlParams .= "&default[$sKey]=$sValue";
				}
			}

			// Check concurrent lock (can only be lock if we are handling a single object
			$bLocked = false;
			if ($iSetCount === 1) {
				$oObj = $oSet->Fetch();
				if (false === is_null($oObj)) {
					if (MetaModel::GetConfig()->Get('concurrent_lock_enabled')) {
						$aLockInfo = iTopOwnershipLock::IsLocked(get_class($oObj), $oObj->GetKey());
						if ($aLockInfo['locked']) {
							$bLocked = true;
						}
					}
				}
				unset($oObj);
				$oSet->Rewind();
			}

			//--------------------------------------------
			// Any object count, any style. Common actions
			//--------------------------------------------

			// Create in new tab
			if ($bIsCreationAllowed && !$bIsCreationInModal) {
				$this->AddNewObjectMenuAction($aRegularActions, $sClass, $sDefaultValuesAsUrlParams);
			}

			// Filter this list (provided by extra params has it need filter param)
			if (array_key_exists('filter_this_list_url', $aExtraParams)) {
				$aToolkitActions[] = [
					'label' => Dict::S('UI:Menu:FilterList'),
					'url'   => $aExtraParams['filter_this_list_url'],
				];
			}

			// Transitions
			// - Do not perform time-consuming computations if there are too many objects in the list
			$iLimit = MetaModel::GetConfig()->Get('complex_actions_limit');
			if (
				($iSetCount > 0) && (false === $bLocked) && MetaModel::HasLifecycle($sClass) &&
				( ($iLimit == 0) || ($iSetCount < $iLimit) )
			) {
				$aTransitions = [];
				// Processing (optimizations) and endpoints are not exactly the same depending on if there is only 1 object or a set
				if ($iSetCount === 1) {
					$oObj = $oSet->Fetch();
					if (false === is_null($oObj)) {
						$sLifecycleClass = get_class($oObj);
						$aTransitions = $oObj->EnumTransitions();
						$aUrlParams = [
							'operation' => 'stimulus',
							'id' => $oObj->GetKey(),
						];
					}
					unset($oObj);
					$oSet->Rewind();
				} else {
					// Life cycle actions may be available... if all objects are in the same state
					// Group by <state>
					$oGroupByExp = new FieldExpression(MetaModel::GetStateAttributeCode($sClass), $this->m_oFilter->GetClassAlias());
					$aGroupBy = array('__state__' => $oGroupByExp);
					$aQueryParams = array();
					if (isset($aExtraParams['query_params'])) {
						$aQueryParams = $aExtraParams['query_params'];
					}

					$sSql = $this->m_oFilter->MakeGroupByQuery($aQueryParams, $aGroupBy);
					$aRes = CMDBSource::QueryToArray($sSql);
					if (count($aRes) === 1) {
						$sLifecycleClass = $sClass;
						// All objects are in the same state...
						$sState = $aRes[0]['__state__'];
						$aTransitions = Metamodel::EnumTransitions($sLifecycleClass, $sState);
						$aUrlParams = [
							'operation' => 'select_bulk_stimulus',
							'state' => $sState,
							'filter' => $sFilter,
						];
					}
				}

				if (count($aTransitions)) {
					$sUrlQueryString = http_build_query($aUrlParams).$sContext;
					$aStimuli = Metamodel::EnumStimuli($sLifecycleClass);
					foreach ($aTransitions as $sStimulusCode => $aTransitionDef) {
						$oSet->Rewind();
						// As soon as the user rights implementation will browse the object set,
						// then we might consider using OptimizeColumnLoad() here
						$iActionAllowed = UserRights::IsStimulusAllowed($sLifecycleClass, $sStimulusCode, $oSet);
						$iActionAllowed = (get_class($aStimuli[$sStimulusCode]) === 'StimulusUserAction') ? $iActionAllowed : UR_ALLOWED_NO;
						switch ($iActionAllowed) {
							case UR_ALLOWED_YES:
							case UR_ALLOWED_DEPENDS:
								$aTransitionActions[$sStimulusCode] = array(
										'label' => $aStimuli[$sStimulusCode]->GetLabel(),
										'url' => "{$sRootUrl}pages/UI.php?stimulus=$sStimulusCode&class=$sLifecycleClass&{$sUrlQueryString}",
									) + $aActionParams;
								break;

							default:
								// Do nothing
						}
					}
					$oSet->Rewind();
				}
			}

			//------------------------------------------
			// Several objects (bulk), any style actions
			//------------------------------------------
			if ($iSetCount > 1) {
				// Bulk actions for each selected classes (eg. "link" and "remote" on n:n relations)
				foreach ($aSelectedClasses as $sSelectedAlias => $sSelectedClass) {
					$sSelectedClassName = MetaModel::GetName($sSelectedClass);

					// Check rights on class
					$bIsBulkModifyAllowed = (!MetaModel::IsAbstract($sSelectedClass)) && UserRights::IsActionAllowed($sSelectedClass, UR_ACTION_BULK_MODIFY, $oSet) && ($oReflectionClass->IsSubclassOf('cmdbAbstractObject'));
					$bIsBulkDeleteAllowed = (bool) UserRights::IsActionAllowed($sSelectedClass, UR_ACTION_BULK_DELETE, $sSelectedClass);

					// Refine filter on selected class so bullk actions occur on the right class
					$oSelectedClassFilter = $this->GetFilter()->DeepClone();
					$oSelectedClassFilter->SetSelectedClasses([$sSelectedAlias]);

					// Action identifier is using the alias on purpose so they can be used as "shortcut actions" easily for "Link" or "Remote" aliases on linksets.
					// Action label dict code has a specific suffix for "Link" / "Remote" aliases to allow dedicated labels in linksets.
					$sActionLabelCodeSuffix = in_array($sSelectedAlias, ['Link', 'Remote']) ? $sSelectedAlias : 'Class';
					if ($bIsBulkModifyAllowed) {
						$this->AddBulkModifyObjectsMenuAction($aRegularActions, $sSelectedClass, $oSelectedClassFilter->serialize(), 'UI:Menu:ModifyAll:'.$sSelectedAlias, Dict::Format('UI:Menu:ModifyAll_'.$sActionLabelCodeSuffix, $sSelectedClassName));
					}
					if ($bIsBulkDeleteAllowed) {
						$this->AddBulkDeleteObjectsMenuAction($aRegularActions, $sSelectedClass, $oSelectedClassFilter->serialize(), 'UI:Menu:BulkDelete:'.$sSelectedAlias, Dict::Format('UI:Menu:BulkDelete_'.$sActionLabelCodeSuffix, $sSelectedClassName));
					}
				}
			}

			//----------------------------------------------------
			// Any style but NOT \DisplayBlock::ENUM_STYLE_LIST_IN_OBJECT (linksets) actions
			//----------------------------------------------------
			if ($this->m_sStyle !== static::ENUM_STYLE_LIST_IN_OBJECT) {
				switch ($iSetCount) {
					case 1:
						$oObj = $oSet->Fetch();
						if (false === is_null($oObj)) {
							$id = $oObj->GetKey();
							if (empty($sRefreshAction) && utils::ReadParam('operation') == 'details') {
								if ($_SERVER['REQUEST_METHOD'] == 'GET') {
									$sRefreshAction = "window.location.reload();";
								} else {
									$sRefreshAction = "window.location.href='".ApplicationContext::MakeObjectUrl(get_class($oObj), $id)."';";
								}
							}

							$bRawModifiedAllowed = (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet) == UR_ALLOWED_YES) && ($oReflectionClass->IsSubclassOf('cmdbAbstractObject'));
							$bIsModifyAllowed = !$bLocked && $bRawModifiedAllowed;
							$bIsDeleteAllowed = !$bLocked && UserRights::IsActionAllowed($sClass, UR_ACTION_DELETE, $oSet);
							// Just one object in the set, possible actions are "new / clone / modify and delete"
							if (!isset($aExtraParams['link_attr'])) {
								if ($bIsModifyAllowed) {
									$aRegularActions['UI:Menu:Modify'] = array(
											'label' => Dict::S('UI:Menu:Modify'),
											'url'   => $oRouter->GenerateUrl('object.modify', ['class' => $sClass, 'id' => $id]) . "{$sContext}#",
										) + $aActionParams;
								}
								if ($bIsDeleteAllowed) {
									$aRegularActions['UI:Menu:Delete'] = array(
											'label' => Dict::S('UI:Menu:Delete'),
											'url' => "{$sRootUrl}pages/$sUIPage?operation=delete&class=$sClass&id=$id{$sContext}",
										) + $aActionParams;
								}

								// Relations...
								$aRelations = MetaModel::EnumRelationsEx($sClass);
								if (count($aRelations)) {
									$this->AddMenuSeparator($aRegularActions);
									foreach ($aRelations as $sRelationCode => $aRelationInfo) {
										if (array_key_exists('down', $aRelationInfo)) {
											$aRegularActions[$sRelationCode.'_down'] = array(
													'label' => $aRelationInfo['down'],
													'url' => "{$sRootUrl}pages/$sUIPage?operation=view_relations&relation=$sRelationCode&direction=down&class=$sClass&id=$id{$sContext}",
												) + $aActionParams;
										}
										if (array_key_exists('up', $aRelationInfo)) {
											$aRegularActions[$sRelationCode.'_up'] = array(
													'label' => $aRelationInfo['up'],
													'url' => "{$sRootUrl}pages/$sUIPage?operation=view_relations&relation=$sRelationCode&direction=up&class=$sClass&id=$id{$sContext}",
												) + $aActionParams;
										}
									}
								}

								// Add a special menu to kill the lock, but only to allowed users who can also modify this object
								if ($bLocked && $bRawModifiedAllowed) {
									/** @var array $aAllowedProfiles */
									$aAllowedProfiles = MetaModel::GetConfig()->Get('concurrent_lock_override_profiles');
									$bCanKill = false;

									$oUser = UserRights::GetUserObject();
									$aUserProfiles = array();
									if (!is_null($oUser)) {
										$oProfileSet = $oUser->Get('profile_list');
										while ($oProfile = $oProfileSet->Fetch()) {
											$aUserProfiles[$oProfile->Get('profile')] = true;
										}
									}

									foreach ($aAllowedProfiles as $sProfile) {
										if (array_key_exists($sProfile, $aUserProfiles)) {
											$bCanKill = true;
											break;
										}
									}

									// Lock removal button is not supported in modals yet
									if (utils::IsXmlHttpRequest()) {
										$bCanKill = false;
									}

									if ($bCanKill) {
										$this->AddMenuSeparator($aRegularActions);
										$aRegularActions['concurrent_lock_unlock'] = array(
											'label' => Dict::S('UI:Menu:KillConcurrentLock'),
											'url' => "{$sRootUrl}pages/$sUIPage?operation=kill_lock&class=$sClass&id=$id{$sContext}",
										);
									}
								}
							}

							$this->AddMenuSeparator($aRegularActions);

							$this->GetEnumAllowedActions($oSet, function ($sLabel, $data) use (&$aRegularActions, $aActionParams) {
								$aRegularActions[$sLabel] = array('label' => $sLabel, 'url' => $data) + $aActionParams;
							});
						}
						break;
				}
			}

			$this->AddMenuSeparator($aRegularActions);

			$this->GetEnumAllowedActions($oSet, function ($sLabel, $data) use (&$aRegularActions, $aActionParams) {
				if (is_array($data)) {
					// New plugins can provide javascript handlers via the 'onclick' property
					//TODO: enable extension of different menus by checking the 'target' property ??
					$aRegularActions[$sLabel] = [
						'label' => $sLabel,
						'url' => isset($data['url']) ? $data['url'] : '#',
						'onclick' => isset($data['onclick']) ? $data['onclick'] : '',
					];
				} else {
					// Backward compatibility with old plugins
					$aRegularActions[$sLabel] = ['label' => $sLabel, 'url' => $data] + $aActionParams;
				}
			});

			if (empty($sRefreshAction) && $this->m_sStyle == static::ENUM_STYLE_LIST) {
				//for the detail page this var is defined way beyond this line
				$sRefreshAction = "window.location.reload();";
			}
		} else {
			// It's easier just display configure this list and MENU_OBJLIST_TOOLKIT
		}

		$param = null;
		if (is_null($sId)) {
			$sId = uniqid();
		}

		// New extensions based on iPopupMenuItem interface
		$oPopupMenuItemsBlock = new UIContentBlock();
		switch ($this->m_sStyle) {
			case static::ENUM_STYLE_LIST:
			case static::ENUM_STYLE_LIST_IN_OBJECT:
				$oSet->Rewind();
				$param = $oSet;

				utils::GetPopupMenuItemsBlock($oPopupMenuItemsBlock, iPopupMenuExtension::MENU_OBJLIST_ACTIONS, $param, $aRegularActions, $sId);
				utils::GetPopupMenuItemsBlock($oPopupMenuItemsBlock, iPopupMenuExtension::MENU_OBJLIST_TOOLKIT, $param, $aToolkitActions, $sId);
				break;

			case 'details':
				$oSet->Rewind();
				$param = $oSet->Fetch();
				utils::GetPopupMenuItemsBlock($oPopupMenuItemsBlock, iPopupMenuExtension::MENU_OBJDETAILS_ACTIONS, $param, $aRegularActions, $sId);
				break;

		}

		if ($oPopupMenuItemsBlock->HasSubBlocks()) {
			$oRenderBlock->AddSubBlock($oPopupMenuItemsBlock);
		} else {
			foreach ($oPopupMenuItemsBlock->GetJsFilesRelPaths() as $sJsPath) {
				$oRenderBlock->AddJsFileRelPath($sJsPath);
			}
			foreach ($oPopupMenuItemsBlock->GetCssFilesRelPaths() as $sCssPath) {
				$oRenderBlock->AddCssFileRelPath($sCssPath);
			}
		}

		// Extract favorite actions from their menus
		$aFavoriteRegularActions = [];
		$aFavoriteTransitionActions = [];
		if (is_callable([$sClass, 'GetShortcutActions'])) {
			/** @var cmdbAbstractObject $sClass */
			$aShortcutActions = $sClass::GetShortcutActions($sClass);
			foreach ($aShortcutActions as $key) {
				// Regular actions
				if (isset($aRegularActions[$key])) {
					$aFavoriteRegularActions[$key] = $aRegularActions[$key];
					unset($aRegularActions[$key]);
				}

				// Transitions
				if (isset($aTransitionActions[$key])) {
					$aFavoriteTransitionActions[$key] = $aTransitionActions[$key];
					unset($aTransitionActions[$key]);
				}
			}
		}

		$oActionsToolbar = ToolbarUIBlockFactory::MakeForAction(static::ACTIONS_TOOLBAR_ID_PREFIX.$sId);
		$oRenderBlock->AddSubBlock($oActionsToolbar);
		$sRegularActionsMenuTogglerId = "ibo-regular-actions-menu-toggler-{$sId}";
		$sRegularActionsPopoverMenuId = "ibo-regular-actions-popover-{$sId}";
		$sTransitionActionsMenuTogglerId = "ibo-transition-actions-menu-toggler-{$sId}";
		$sTransitionActionsPopoverMenuId = "ibo-transition-actions-popover-{$sId}";

		if (!$oPage->IsPrintableVersion()) {

			// Transitions actions
			// - Favorites
			foreach ($aFavoriteTransitionActions as $sActionId => $aAction) {
				$sIconClass = '';
				$sLabel = $aAction['label'];
				$sUrl = $aAction['url'];

				$sTarget = isset($aAction['target']) ? $aAction['target'] : '';
				$oActionButton = ButtonUIBlockFactory::MakeLinkNeutral($sUrl, $sLabel, $sIconClass, $sTarget, $sActionId);
				$oActionButton->AddCSSClasses(['ibo-action-button', 'ibo-transition-action-button']);

				if (empty($sLabel)) {
					$oActionButton->SetTooltip(Dict::S($sActionId));
				}

				$oActionsToolbar->AddSubBlock($oActionButton);
			}

			// - Others
			if (!empty($aTransitionActions)) {
				if (count($aFavoriteTransitionActions) > 0) {
					$sName = 'UI:Menu:OtherTransitions';
				} else {
					$sName = 'UI:Menu:Transitions';
				}
				$oActionButton = ButtonUIBlockFactory::MakeIconAction('fas fa-map-signs', Dict::S($sName), $sName, '', false, $sTransitionActionsMenuTogglerId)
					->AddCSSClasses(['ibo-action-button', 'ibo-transition-action-button']);

				$oTransitionActionsMenu = $oPage->GetPopoverMenu($sTransitionActionsPopoverMenuId, $aTransitionActions)
					->SetTogglerJSSelector("#$sTransitionActionsMenuTogglerId")
					->AddVisualHintToToggler();

				$oActionsToolbar->AddSubBlock($oActionButton)
					->AddSubBlock($oTransitionActionsMenu);
			}

			// Separator between transitions and regulars
			if ((!empty($aFavoriteTransitionActions) || !empty($aTransitionActions)) &&
				(!empty($aFavoriteRegularActions) || !empty($aRegularActions))) {
				$oActionsToolbar->AddSubBlock(ToolbarSeparatorUIBlockFactory::MakeVertical());
			}

			// Regular actions
			// - Favorites
			foreach ($aFavoriteRegularActions as $sActionId => $aAction) {
				$sIconClass = '';
				$sLabel = $aAction['label'];
				$sUrl = $aAction['url'];
				switch ($sActionId) {
					case 'UI:Menu:New':
						$sIconClass = 'fas fa-plus';
						$sLabel = '';
						break;

					case 'UI:Menu:ModifyAll':
					case 'UI:Menu:ModifyAll:Link':      // Link class on linkset
					case 'UI:Menu:ModifyAll:Remote':    // Remote class on linkset
					case 'UI:Menu:Modify':
						$sIconClass = 'fas fa-pen';
						$sLabel = '';
						break;

					case 'UI:Menu:BulkDelete':
					case 'UI:Menu:BulkDelete:Link':     // Link class on linkset
					case 'UI:Menu:BulkDelete:Remote':   // Remote class on linkset
					case 'UI:Menu:Delete':
						$sIconClass = 'fas fa-trash';
						$sLabel = '';
						break;

					case 'UI:Menu:EMail':
						$sIconClass = 'fas fa-share-alt';
						$sLabel = '';
						break;

					default:
						if (isset($aAction['icon_class']) && (strlen($aAction['icon_class']) > 0)) {
							$sIconClass = $aAction['icon_class'];
							$sLabel = '';
						}
				}

				$sTarget = isset($aAction['target']) ? $aAction['target'] : '';
				$oActionButton = ButtonUIBlockFactory::MakeLinkNeutral($sUrl, $sLabel, $sIconClass, $sTarget, utils::Sanitize($sActionId, '', utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER));
				// ResourceId should not be sanitized
				$oActionButton->AddDataAttribute('resource-id', $sActionId);
				$oActionButton->AddCSSClasses(['ibo-action-button', 'ibo-regular-action-button']);
				if (empty($sLabel)) {
					if (empty($aAction['tooltip'])) {
						$oActionButton->SetTooltip(Dict::S($sActionId));
					} else {
						$oActionButton->SetTooltip($aAction['tooltip']);
					}
				}
				$oActionsToolbar->AddSubBlock($oActionButton);
			}

			// - Creation in modal
			if ($bIsCreationAllowed && $bIsCreationInModal) {
				$oAddLinkActionButton = ButtonUIBlockFactory::MakeIconAction(
					'fas fa-plus',
					// Allow button tooltip customization
					array_key_exists('creation_in_modal_tooltip', $aExtraParams) ?
						$aExtraParams['creation_in_modal_tooltip'] :
						Dict::Format('UI:ClickToCreateNew', MetaModel::GetName($sClass)),
					'UI:Links:New',
					'',
					false
				);

				// creation form title
				if (array_key_exists('creation_in_modal_form_title', $aExtraParams) && $aExtraParams['creation_in_modal_form_title'] !== null) {
					$oAddLinkActionButton->AddDataAttribute('modal-title', $aExtraParams['creation_in_modal_form_title']);
				}

				// - If we are used in a Datatable, 'datatable_' will be prefixed to our $sId, so we do the same here
				$sRealId = $sId;
				if (in_array($this->m_sStyle, [static::ENUM_STYLE_LIST, 'links', static::ENUM_STYLE_LIST_IN_OBJECT])) {
					$sRealId = 'datatable_'.$sId;
				}
				$oAddLinkActionButton->AddCSSClasses(['ibo-action-button', 'ibo-regular-action-button'])
					->SetOnClickJsCode("$('#$sRealId').trigger('open_creation_modal.object.itop');");
				$oActionsToolbar->AddSubBlock($oAddLinkActionButton);
			}

			// - Refresh
			if (utils::IsNotNullOrEmptyString($sRefreshAction)) {
				$oActionButton = ButtonUIBlockFactory::MakeAlternativeNeutral('', 'UI:Button:Refresh');
				$oActionButton->SetIconClass('fas fa-sync-alt')
					->SetOnClickJsCode($sRefreshAction)
					->SetTooltip(Dict::S('UI:Button:Refresh'))
					->AddCSSClasses(['ibo-action-button', 'ibo-regular-action-button']);
				$oActionsToolbar->AddSubBlock($oActionButton);
			}

			// - Search
			if ($this->m_sStyle === 'details') {
				$oActionButton = ButtonUIBlockFactory::MakeIconLink('fas fa-search', Dict::Format('UI:SearchFor_Class', MetaModel::GetName($sClass)), "{$sRootUrl}pages/UI.php?operation=search_form&do_search=0&class=$sClass{$sContext}", '', 'UI:SearchFor_Class');
				$oActionButton->AddCSSClasses(['ibo-action-button', 'ibo-regular-action-button']);
				$oActionsToolbar->AddSubBlock($oActionButton);
			}

			// - Others
			if (!empty($aRegularActions) || !empty($aToolkitActions)) {
				if (count($aFavoriteRegularActions) > 0) {
					$sName = 'UI:Menu:OtherActions';
				} else {
					$sName = 'UI:Menu:Actions';
				}
				$oActionButton = ButtonUIBlockFactory::MakeIconAction('fas fa-ellipsis-v', Dict::S($sName), $sName, '', false, $sRegularActionsMenuTogglerId)
					->AddCSSClasses(['ibo-action-button', 'ibo-regular-action-button']);

				$oRegularActionsMenu = $oPage->GetPopoverMenu($sRegularActionsPopoverMenuId, $aRegularActions)
					->SetTogglerJSSelector("#$sRegularActionsMenuTogglerId")
					->SetContainer(PopoverMenu::ENUM_CONTAINER_BODY);

				$oActionsToolbar->AddSubBlock($oActionButton)
					->AddSubBlock($oRegularActionsMenu);

				// Toolkit actions
				if (!empty($aToolkitActions)) {
					// Add separator if necessary
					if (count($aRegularActions) > 0) {
						$oRegularActionsMenu->AddItem('separator-regular-actions-toolkit-actions', PopoverMenuItemFactory::MakeSeparator());
					}

					// Add actions
					foreach ($aToolkitActions as $sActionId => $aActionData) {
						$oRegularActionsMenu->AddItem('toolkit-actions', PopoverMenuItemFactory::MakeFromApplicationPopupMenuItemData($sActionId, $aActionData));
					}
				}
			}
		}

		return $oRenderBlock;
	}

	/**
	 * If an extension doesn't return an array as expected :
	 * - calls IssueLog:Warning
	 * - if is dev env, then throw CoreUnexpectedValue exception
	 *
	 * @param \DBObjectSet $oSet
	 * @param callable $callback EnumAllowedActions returns an array, we will call this anonymous function on each of its value
	 *               with two parameters : label (array index), data (array value)
	 *
	 * @throws \CoreUnexpectedValue
	 *
	 * @uses \MetaModel::EnumPlugins()
	 * @uses \iApplicationUIExtension::EnumAllowedActions()
	 * @uses \utils::IsDevelopmentEnvironment()
	 *
	 * @since 3.0.0
	 */
	private function GetEnumAllowedActions(DBObjectSet $oSet, callable $callback): void
	{
		$aInvalidExtensions = [];

		/** @var \iApplicationUIExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance) {
			$oSet->Rewind();
			$aExtEnumAllowedActions = $oExtensionInstance->EnumAllowedActions($oSet);

			if (!is_array($aExtEnumAllowedActions)) {
				$aInvalidExtensions[] = get_class($oExtensionInstance);
				continue;
			}

			foreach ($aExtEnumAllowedActions as $sLabel => $data) {
				$callback($sLabel, $data);
			}
		}

		if (!empty($aInvalidExtensions)) {
			$sMessage = 'Some extensions returned non array value for EnumAllowedActions() method impl';

			IssueLog::Warning(
				$sMessage,
				null,
				['extensions' => $aInvalidExtensions]
			);

			if (utils::IsDevelopmentEnvironment()) {
				throw new CoreUnexpectedValue($sMessage, $aInvalidExtensions);
			}
		}
	}

	/**
	 * Appends a menu separator to the current list of actions
	 *
	 * @param array $aActions The current actions list
	 *
	 * @return void
	 */
	protected function AddMenuSeparator(&$aActions)
	{
		$sSeparator = '<hr class="menu-separator"/>';
		if (count($aActions) > 0) // Make sure that the separator is not the first item in the menu
		{
			$aKeys = array_keys($aActions);
			$sLastKey = array_pop($aKeys);
			if ($aActions[$sLastKey]['label'] != $sSeparator) // Make sure there are no 2 consecutive separators
			{
				$aActions['sep_'.(count($aActions)-1)] = array('label' => $sSeparator, 'url' => '');
			}
		}
	}

	/**
	 * Add a create action (to $aActions) for an $sClass object
	 *
	 * @param array &$aActions Pointer to the array in which the action will be added
	 * @param string $sClass Datamodel class concerned by the action
	 * @param string $sDefaultValuesAsUrlParams Default values for the new object form,
	 *
	 * @return void
	 * @since 3.1.0
	 * @internal
	 */
	protected function AddNewObjectMenuAction(array &$aActions, string $sClass, string $sDefaultValuesAsUrlParams = ''): void
	{
		$aActions['UI:Menu:New'] = [
				'label' => Dict::Format('UI:ClickToCreateNew', MetaModel::GetName($sClass)),
				'url'   => $this->PrepareUrlForStandardMenuAction($sClass, "operation=new&class=$sClass{$sDefaultValuesAsUrlParams}"),
			] + $this->GetDefaultParamsForMenuAction();
	}

	/**
	 * Add a bulk modify action (to $aActions) on objects defined by $sFilter
	 *
	 * @param array &$aActions Pointer to the array in which the action will be added
	 * @param string $sClass Datamodel class concerned by the action
	 * @param string $sFilter OQL of the objects to propose for bulk modify
	 * @param string $sActionIdentifier Unique identifier for the action. Default if 'UI:Menu:ModifyAll'
	 * @param string $sActionLabel Label for the action, can be either a dict code to translate or an hardcoded label. Default is 'UI:Menu:ModifyAll'.
	 *
	 * @return void
	 * @since 3.1.0
	 * @internal
	 */
	protected function AddBulkModifyObjectsMenuAction(array &$aActions, string $sClass, string $sFilter, string $sActionIdentifier = 'UI:Menu:ModifyAll', $sActionLabel = 'UI:Menu:ModifyAll'): void
	{
		$aActions[$sActionIdentifier] = [
			'label' => Dict::S($sActionLabel),
			'url' => $this->PrepareUrlForStandardMenuAction($sClass, "operation=select_for_modify_all&class=$sClass&filter=".urlencode($sFilter)),
		] + $this->GetDefaultParamsForMenuAction();
	}

	/**
	 * Add a bulk delete action (to $aActions) on objects defined by $sFilter
	 *
	 * @param array &$aActions Pointer to the array in which the action will be added
	 * @param string $sClass Datamodel class concerned by the action
	 * @param string $sFilter OQL of the objects to propose for bulk deletion
	 * @param string $sActionIdentifier Unique identifier for the action. Default if 'UI:Menu:BulkDelete'
	 * @param string $sActionLabel Label for the action, can be either a dict code to translate or an hardcoded label. Default is 'UI:Menu:BulkDelete'.
	 *
	 * @return void
	 * @since 3.1.0
	 * @internal
	 */
	protected function AddBulkDeleteObjectsMenuAction(array &$aActions, string $sClass, string $sFilter, string $sActionIdentifier = 'UI:Menu:BulkDelete', $sActionLabel = 'UI:Menu:BulkDelete')
	{
		$aActions[$sActionIdentifier] = array(
			'label' => Dict::S($sActionLabel),
			'url' => $this->PrepareUrlForStandardMenuAction($sClass, "operation=select_for_deletion&filter=".urlencode($sFilter)),
		) + $this->GetDefaultParamsForMenuAction();
	}

	/**
	 * @return array Default parameters of a menu action
	 * @since 3.1.0
	 * @internal
	 */
	private function GetDefaultParamsForMenuAction(): array
	{
		$aDefaultParams = [];

		if (isset($aExtraParams['menu_actions_target'])) {
			$aDefaultParams['target'] = $aExtraParams['menu_actions_target'];
		}

		return $aDefaultParams;
	}

	/**
	 * @param string $sClass Datamodel class for which the URL is prepared
	 * @param string $sUrlParams URL parameters to add to the URL, must already be concatenated as a string (eg. "foo=bar&some=thing&third=param")
	 *
	 * @return string An absolute URL for a menu action on the $sClass class with $sUrlParams
	 * @throws \Exception
	 * @internal
	 */
	private function PrepareUrlForStandardMenuAction(string $sClass, string $sUrlParams)
	{
		$sRootUrl = utils::GetAbsoluteUrlAppRoot();
		$sUIPage = cmdbAbstractObject::ComputeStandardUIPage($sClass);

		$sUrl = "{$sRootUrl}pages/{$sUIPage}?{$sUrlParams}";

		$oAppContext = new ApplicationContext();
		$sContext = $oAppContext->GetForLink();
		if (utils::IsNotNullOrEmptyString($sContext)) {
			$sUrl .= '&'.$sContext;
		}

		return $sUrl;
	}
}
