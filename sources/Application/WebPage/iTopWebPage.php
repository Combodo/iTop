<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\WebPage;

use ApplicationContext;
use appUserPreferences;
use AttributeDate;
use AttributeDateTime;
use Combodo\iTop\Application\TwigBase\Twig\TwigHelper;
use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Breadcrumbs\Breadcrumbs;
use Combodo\iTop\Application\UI\Base\Component\Modal\DoNotShowAgainOptionBlock;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Spinner\SpinnerUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Template\TemplateUIBlockFactory;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\Layout\iUIContentBlock;
use Combodo\iTop\Application\UI\Base\Layout\NavigationMenu\NavigationMenu;
use Combodo\iTop\Application\UI\Base\Layout\NavigationMenu\NavigationMenuFactory;
use Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContent;
use Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContentFactory;
use Combodo\iTop\Application\UI\Base\Layout\TopBar\TopBar;
use Combodo\iTop\Application\UI\Base\Layout\TopBar\TopBarFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Base\UIBlock;
use Combodo\iTop\Application\UI\Printable\BlockPrintHeader\BlockPrintHeader;
use Combodo\iTop\Renderer\BlockRenderer;
use Combodo\iTop\Renderer\Console\ConsoleBlockRenderer;
use ContextTag;
use DateTimeFormat;
use DBSearch;
use DeprecatedCallsLog;
use Dict;
use ExecutionKPI;
use InlineImage;
use iPageUIBlockExtension;
use iPageUIExtension;
use MetaModel;
use UserRights;
use utils;

/**
 * Web page with some associated CSS and scripts (jquery) for a fancier display
 */
class iTopWebPage extends NiceWebPage implements iTabbedPage
{
	/** @var string ENUM_BREADCRUMB_ENTRY_ICON_TYPE_IMAGE */
	const ENUM_BREADCRUMB_ENTRY_ICON_TYPE_IMAGE = 'image';
	/** @var string ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES */
	const ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES = 'css_classes';
	/** @var string DEFAULT_BREADCRUMB_ENTRY_ICON_TYPE */
	const DEFAULT_BREADCRUMB_ENTRY_ICON_TYPE = self::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_IMAGE;

	/** @inheritDoc */
	protected const COMPATIBILITY_MOVED_LINKED_SCRIPTS_REL_PATH = [
		// - TabContainer
		'js/jquery.ba-bbq.min.js',
		// - DashletGroupBy & other specific places
		'js/d3.js', // 3.2.0 N°5261 moved to NPM
		'js/c3.js', // 3.2.0 N°5261 moved to NPM
		// - DisplayableGraph, impact analysis
		'js/raphael-min.js',
		'js/jquery.mousewheel.js',
		/** - links widgets moved in links folder @since 3.1.0 * */
		'js/links/links_direct_widget.js',
		'js/links/links_widget.js',
	];
	/** @inheritDoc */
	protected const COMPATIBILITY_DEPRECATED_LINKED_SCRIPTS_REL_PATH = [
		'js/date.js',
		'js/jquery.layout.min.js',
		/** @deprecated 3.0.0 N°3748 qTip will be removed in 3.x, use Tippy.js instead */
		'js/jquery.qtip-1.0.min.js',
	];
	/** @inheritDoc */
	protected const COMPATIBILITY_MOVED_LINKED_STYLESHEETS_REL_PATH = [
		// Moved files
		// - DashletGroupBy & other specific places
		'node_modules/c3/c3.min.css',
	];

	/** @var string DEFAULT_PAGE_TEMPLATE_REL_PATH The relative path (from <ITOP>/templates/) to the default page template */
	const DEFAULT_PAGE_TEMPLATE_REL_PATH = 'pages/backoffice/itopwebpage/layout';

	private $m_aMessages;

	/** @var TabManager */
	protected $m_oTabs;
	/**
	 * Navigation menu layout (menu groups, user menu, ...)
	 *
	 * @var \Combodo\iTop\Application\UI\Base\Layout\NavigationMenu\NavigationMenu
	 * @since 3.0.0
	 */
	protected $oNavigationMenuLayout;
	/**
	 * Top bar layout (quick create, global search, ...)
	 *
	 * @var \Combodo\iTop\Application\UI\Base\Layout\TopBar\TopBar
	 * @since 3.0.0
	 */
	protected $oTopBarLayout;
	protected $bBreadCrumbEnabled;
	protected $sBreadCrumbEntryId;
	protected $sBreadCrumbEntryLabel;
	protected $sBreadCrumbEntryDescription;
	protected $sBreadCrumbEntryUrl;
	protected $sBreadCrumbEntryIcon;
	protected $sBreadCrumbEntryIconType;
	/** @var \ContextTag $oCtx */
	protected $oCtx;

	/**
	 * iTopWebPage constructor.
	 *
	 * @param string $sTitle
	 * @param bool $bPrintable
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 */
	public function __construct($sTitle, $bPrintable = false)
	{
		$oKpi = new ExecutionKPI();
		parent::__construct($sTitle, $bPrintable);
		$this->m_oTabs = new TabManager();
		$this->oCtx = new ContextTag(ContextTag::TAG_CONSOLE);

		// By default, content layout is empty, only manually added content will be displayed (eg. $this->add(xxx))
		$this->SetContentLayout(PageContentFactory::MakeStandardEmpty());

		ApplicationContext::SetUrlMakerClass('iTopStandardURLMaker');

		if ((count($_POST) == 0) || (array_key_exists('loginop', $_POST))) {
			// Create a breadcrumb entry for the current page, but get its title as late as possible (page title could be changed later)
			$this->bBreadCrumbEnabled = true;
		} else {
			$this->bBreadCrumbEnabled = false;
		}

		$this->SetNavigationMenuLayout(NavigationMenuFactory::MakeStandard());
		$this->SetTopBarLayout(TopBarFactory::MakeStandard($this->GetBreadCrumbsNewEntry()));

		utils::InitArchiveMode();

		$this->m_aMessages = array();
		$this->SetRootUrl(utils::GetAbsoluteUrlAppRoot());
		$this->add_header("Content-type: text/html; charset=".self::PAGES_CHARSET);
		$this->no_cache();
		$this->add_http_headers();
		$this->PrepareLayout();
		if ($this->IsPrintableVersion()) {
			$oPrintHeader = $this->OutputPrintable();
			$this->AddUiBlock($oPrintHeader);
		}
		$oKpi->ComputeStats(get_class($this).' creation', 'iTopWebPage');
	}

	/**
	 * @inheritDoc
	 * @since 3.0.0
	 */
	protected function InitializeLinkedScripts(): void
	{
		parent::InitializeLinkedScripts();

		// Used by forms
		$this->LinkScriptFromAppRoot('js/leave_handler.js');

		// Used by external keys, DM viewer
		$this->LinkScriptFromAppRoot('js/jquery.treeview.min.js');

		// Used by advanced search, date(time) attributes. Coupled to the PrepareWidgets() JS function.
		$this->LinkScriptFromAppRoot('js/jquery-ui-timepicker-addon.min.js');
		$this->LinkScriptFromAppRoot('js/jquery-ui-timepicker-addon-i18n.min.js');

		// Used by external keys and other drop down lists
		$this->LinkScriptFromAppRoot('js/selectize.min.js');
		$this->LinkScriptFromAppRoot('node_modules/selectize-plugin-a11y/selectize-plugin-a11y.js');
		$this->LinkScriptFromAppRoot('js/jquery.multiselect.js');

		// Used by inline image, CKEditor and other places
		$this->LinkScriptFromAppRoot('node_modules/magnific-popup/dist/jquery.magnific-popup.min.js');

		// Used by date(time) attributes, activity panel, ...
		$this->LinkScriptFromAppRoot('node_modules/moment/min/moment-with-locales.min.js');

		// Used by the newsroom
		$this->LinkScriptFromAppRoot('node_modules/showdown/dist/showdown.min.js');

		// Tooltips
		$this->LinkScriptFromAppRoot('node_modules/@popperjs/core/dist/umd/popper.min.js');
		$this->LinkScriptFromAppRoot('node_modules/tippy.js/dist/tippy-bundle.umd.min.js');
		
		// Toasts
		$this->LinkScriptFromAppRoot('node_modules/toastify-js/src/toastify.js');

		// Keyboard shortcuts
		$this->LinkScriptFromAppRoot('node_modules/mousetrap/mousetrap.min.js');
		$this->LinkScriptFromAppRoot('node_modules/mousetrap/plugins/record/mousetrap-record.min.js');
		$this->LinkScriptFromAppRoot('js/pages/backoffice/keyboard-shortcuts.js');

		// Used throughout the app.
		$this->LinkScriptFromAppRoot('js/pages/backoffice/toolbox.js');
		$this->LinkScriptFromAppRoot('js/pages/backoffice/on-ready.js');

		// Used by dashboard editor
		$this->LinkScriptFromAppRoot('js/property_field.js');
		$this->LinkScriptFromAppRoot('js/icon_select.js');
	}

	/**
	 * @inheritDoc
	 * @since 3.0.0
	 */
	protected function InitializeDictEntries(): void
	{
		parent::InitializeDictEntries();

		$this->add_dict_entry('UI:FillAllMandatoryFields');

		$this->add_dict_entries('Error:');
		$this->add_dict_entries('UI:Button:');
		$this->add_dict_entries('UI:Search:');
		$this->add_dict_entry('UI:UndefinedObject');
		$this->add_dict_entries('Enum:Undefined');
		$this->add_dict_entry('UI:Datatables:Language:Processing');
		$this->add_dict_entries('UI:Newsroom');
		$this->add_dict_entry('UI:NavigateAwayConfirmationMessage');

		// User not logged in dialog
		$this->add_dict_entry('UI:DisconnectedDlgTitle');
		$this->add_dict_entry('UI:LoginAgain');
		$this->add_dict_entry('UI:StayOnThePage');

		// Modals
		$this->add_dict_entries('UI:Modal:');
		$this->add_dict_entries('UI:Links:');
		$this->add_dict_entries('UI:Object:');
		$this->add_dict_entry('UI:Layout:ObjectDetails:New:Modal:Title');
	}

	/**
	 * @inheritDoc
	 * @since 3.0.0
	 */
	protected function InitializeLinkedStylesheets(): void
	{
		parent::InitializeLinkedStylesheets();

		// Used by advanced search, date(time) attributes. Coupled to the PrepareWidgets() JS function.
		$this->LinkStylesheetFromAppRoot('css/jquery-ui-timepicker-addon.css');

		// Used by inline image, CKEditor and other places
		$this->LinkStylesheetFromAppRoot('node_modules/magnific-popup/dist/magnific-popup.css');

		// Tooltips
		$this->LinkStylesheetFromAppRoot('node_modules/tippy.js/dist/tippy.css');
		$this->LinkStylesheetFromAppRoot('node_modules/tippy.js/animations/shift-away-subtle.css');

		// Icons
		$this->LinkStylesheetFromAppRoot('css/font-awesome/css/all.min.css');
		$this->LinkStylesheetFromAppRoot('css/font-combodo/font-combodo.css');

		// Used by external keys and other drop down lists
		$this->LinkStylesheetFromAppRoot('css/selectize.default.css');
	}

	/**
	 * @since 3.0.0
	 */
	protected function InitializeKeyboardShortcuts(): void
	{
		$aShortcuts = utils::GetAllKeyboardShortcutsPrefs();
		$sShortcuts = json_encode($aShortcuts);
		$this->add_script("aKeyboardShortcuts = $sShortcuts;");
	}

	/**
	 *
	 */
	protected function PrepareLayout()
	{
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
		$sTimeFormat = AttributeDateTime::GetFormat()->ToTimeFormat();
		$oTimeFormat = new DateTimeFormat($sTimeFormat);

		// Date picker options
		$aPickerOptions = array(
			'showOn' => 'button',
			'buttonText' => '', // N°6455 class will be added after JQuery UI widget
			'dateFormat' => AttributeDate::GetFormat()->ToDatePicker(),
			'constrainInput' => false,
			'changeMonth' => true,
			'changeYear' => true,
			'dayNamesMin' => $aDaysMin,
			'monthNamesShort' => $aMonthsShort,
			'firstDay' => (int)Dict::S('Calendar-FirstDayOfWeek'),
		);
		$sJSDatePickerOptions = json_encode($aPickerOptions);

		// Time picker additional options
		$sUserLang = Dict::GetUserLanguage();
		$sUserLangShort = strtolower(
			substr($sUserLang, 0, 2)
		);
		// PR #40 :  we are picking correct values for specific cases in dict files
		// some languages are using codes like zh-CN or pt-BR
		$sTimePickerLang = json_encode(
			Dict::S('INTERNAL:JQuery-DatePicker:LangCode', $sUserLangShort)
		);
		$aPickerOptions['showOn'] = '';
		$aPickerOptions['buttonImage'] = null;
		$aPickerOptions['timeFormat'] = $oTimeFormat->ToDatePicker();
		$aPickerOptions['controlType'] = 'select';
		$aPickerOptions['closeText'] = Dict::S('UI:Button:Ok');
		$sJSDateTimePickerOptions = json_encode($aPickerOptions);
		if ($sTimePickerLang != '"en"')
		{
			// More options that cannot be passed via json_encode since they must be evaluated client-side
			$aMoreJSOptions = ",
				'timeText': $.timepicker.regional[$sTimePickerLang].timeText,
				'hourText': $.timepicker.regional[$sTimePickerLang].hourText,
				'minuteText': $.timepicker.regional[$sTimePickerLang].minuteText,
				'secondText': $.timepicker.regional[$sTimePickerLang].secondText,
				'currentText': $.timepicker.regional[$sTimePickerLang].currentText
			}";
			$sJSDateTimePickerOptions = substr($sJSDateTimePickerOptions, 0, -1).$aMoreJSOptions;
		}
		$this->add_script(
			<<< JS
	function GetUserLanguage()
	{
		return $sTimePickerLang;
	}
	function PrepareWidgets()
	{
		// note: each action implemented here must be idempotent,
		//       because this helper function might be called several times on a given page 
	
	    // Note: Trigger image is wrapped in a span so we can display it we want 
		$(".date-pick").datepicker($sJSDatePickerOptions)
		    .next("img").wrap("<span>");
		$("button.ui-datepicker-trigger").addClass('fas fa-calendar-alt');
	
		// Hack for the date and time picker addon issue on Chrome (see #1305)
		// The workaround is to instantiate the widget on demand
		// It relies on the same markup, thus reverting to the original implementation should be straightforward
		$(".datetime-pick:not(.is-widget-ready)").each(function(){
			var oInput = this;
			$(oInput).addClass('is-widget-ready');
			$('<div class="ibo-input-datetime--action-button"><i class="fas fa-calendar-alt"></i></i>')
				.insertAfter($(this))
				.on('click', function(){
					$(oInput)
						.datetimepicker($sJSDateTimePickerOptions)
						.datetimepicker('show')
						.datetimepicker('option', 'onClose', function(dateText,inst){
							$(oInput).datetimepicker('destroy');
						})
						.on('click keypress', function(){
							$(oInput).datetimepicker('hide');
						});
				});
		});
	}
JS
		);
		if (!$this->IsPrintableVersion()) {
			// TODO 3.0.0: Change CSS class and extract this in backoffice/toolbox.js
			// Make image attributes zoomable
			$this->add_ready_script(
				<<<JS
		$('.ibo-input-image--image-view img').each(function(){
			$(this).attr('href', $(this).attr('src'))
		})
		.magnificPopup({type: 'image', closeOnContentClick: true });
JS
			);

			// TODO 3.0.0: What is this for?
			$this->add_ready_script(
				<<< JS
	PrepareWidgets();

	// Make sortable, everything that claims to be sortable
	$('.sortable').sortable( {axis: 'y', cursor: 'move', handle: '.drag_handle', stop: function()
		{
			if ($(this).hasClass('persistent'))
			{
				// remember the sort order for next time the page is loaded...
				sSerialized = $(this).sortable('serialize', {key: 'menu'});
				var sTemp = sSerialized.replace(/menu=/g, '');
				SetUserPreference(this.id+'_order', sTemp.replace(/&/g, ','), true); // true => persistent !
			}
		}
	});
	docWidth = $(document).width();
	// $('#ModalDlg').dialog({ autoOpen: false, modal: true, width: 0.8*docWidth, height: 'auto', maxHeight: $(window).height() - 50 }); // JQuery UI dialogs
	ShowDebug();
	
	// Default values for blockui
	$.blockUI.defaults.css = {}; 
	$.blockUI.defaults.message= '<i class="fas fa-fw fa-spin fa-sync-alt"></i>'; 
	$.blockUI.defaults.overlayCSS = {} 
JS
		);

		// TODO 3.0.0: To preserve
		$this->add_ready_script(InlineImage::FixImagesWidth());

		// user pref for client side
		// see GetUserPreference() in utils.js
		$sUserPrefs = appUserPreferences::GetAsJSON();
		$this->add_script("var oUserPreferences = $sUserPrefs;");
		}
	}


	/**
	 * @see static::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_IMAGE, static::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES
	 *
	 * @param string $sId Identifies the item, to search after it in the current breadcrumb
	 * @param string $sLabel Label of the breadcrumb item
	 * @param string $sDescription More information, displayed as a tooltip
	 * @param string $sUrl Specify a URL if the current URL as perceived on the browser side is not relevant
	 * @param string $sIcon Image URL (relative or absolute) or CSS classes (eg. "fas fa-wrench") of the icon that will be displayed next
	 *     to the label
	 * @param string $sIconType Type of the icon, must be set according to the $sIcon value. See class constants
	 *     ENUM_BREADCRUMB_ENTRY_ICON_TYPE_XXX
	 */
	public function SetBreadCrumbEntry($sId, $sLabel, $sDescription, $sUrl = '', $sIcon = '', $sIconType = self::DEFAULT_BREADCRUMB_ENTRY_ICON_TYPE)
	{
		$this->bBreadCrumbEnabled = true;
		$this->sBreadCrumbEntryId = $sId;
		$this->sBreadCrumbEntryLabel = $sLabel;
		$this->sBreadCrumbEntryDescription = $sDescription;
		$this->sBreadCrumbEntryUrl = $sUrl;
		$this->sBreadCrumbEntryIcon = $sIcon;
		$this->sBreadCrumbEntryIconType = $sIconType;

		$this->GetTopBarLayout()->SetBreadcrumbs(new Breadcrumbs($this->GetBreadCrumbsNewEntry(), Breadcrumbs::BLOCK_CODE));
	}

	/**
	 * State that there will be no breadcrumb item for the current page
	 */
	public function DisableBreadCrumb()
	{
		$this->bBreadCrumbEnabled = false;
		$this->sBreadCrumbEntryId = null;
		$this->sBreadCrumbEntryLabel = null;
		$this->sBreadCrumbEntryDescription = null;
		$this->sBreadCrumbEntryUrl = null;
		$this->sBreadCrumbEntryIcon = null;

		$this->GetTopBarLayout()->SetBreadcrumbs(new Breadcrumbs($this->GetBreadCrumbsNewEntry(), Breadcrumbs::BLOCK_CODE));
	}


	/**
	 * @internal
	 * @return \Combodo\iTop\Application\UI\Base\Layout\NavigationMenu\NavigationMenu
	 * @uses static::$oNavigationMenuLayout
	 * @since 3.0.0
	 */
	protected function GetNavigationMenuLayout()
	{
		return $this->oNavigationMenuLayout;
	}

	/**
	 * @internal
	 *
	 * @param \Combodo\iTop\Application\UI\Base\Layout\NavigationMenu\NavigationMenu $oNavigationMenuLayout
	 *
	 * @return $this
	 * @uses static::$oNavigationMenuLayout
	 * @since 3.0.0
	 */
	protected function SetNavigationMenuLayout(NavigationMenu $oNavigationMenuLayout)
	{
		$this->oNavigationMenuLayout = $oNavigationMenuLayout;

		return $this;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Base\Layout\NavigationMenu\NavigationMenu $oNavigationMenuLayout
	 *
	 * @return $this
	 * @uses static::$oNavigationMenuLayout
	 * @since 3.0.0
	 */
	public function ResetNavigationMenuLayout()
	{
		$this->SetNavigationMenuLayout(NavigationMenuFactory::MakeStandard());
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\Layout\TopBar\TopBar
	 * @uses static::$oTopBarLayout
	 * @since 3.0.0
	 */
	public function GetTopBarLayout()
	{
		return $this->oTopBarLayout;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Base\Layout\TopBar\TopBar $oTopBarLayout
	 *
	 * @return $this
	 * @uses static::$oTopBarLayout
	 * @since 3.0.0
	 */
	public function SetTopBarLayout(TopBar $oTopBarLayout)
	{
		$this->oTopBarLayout = $oTopBarLayout;
		return $this;
	}

	/**
	 * Set the content layout (main content, [side content,] manually added content, ...)
	 * This function is public as the developer needs to be able to set how the content will be displayed.
	 *
	 * @internal
	 *
	 * @param \Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContent $oLayout
	 *
	 * @return $this
	 * @since 3.0.0
	 */
	public function SetContentLayout(PageContent $oLayout)
	{
		$oPrevContentLayout = $this->oContentLayout;
		$this->oContentLayout = $oLayout;
		foreach ($oPrevContentLayout->GetSubBlocks() as $oBlock) {
			$this->AddUiBlock($oBlock);
		}

		return $this;
	}

	/**
	 * Return the content layout (main content, [side content,] manually added content, ...)
	 *
	 * @internal
	 * @return \Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContent
	 * @since 3.0.0
	 */
	public function GetContentLayout()
	{
		/** @var PageContent $oPageContent */
		$oPageContent = $this->oContentLayout;

		return $oPageContent;
	}

	/**
	 * Return the new breadcrumbs entry or null if we don't create a new entry for the current page
	 *
	 * @internal
	 * @return array|null
	 * @since 3.0.0
	 */
	protected function GetBreadCrumbsNewEntry()
	{
		$aNewEntry = null;

		if ($this->bBreadCrumbEnabled)
		{
			// Default entry values
			if (is_null($this->sBreadCrumbEntryId))
			{
				$this->sBreadCrumbEntryId = $this->s_title;
				$this->sBreadCrumbEntryLabel = $this->s_title;
				$this->sBreadCrumbEntryDescription = $this->s_title;
				$this->sBreadCrumbEntryUrl = '';
				$this->sBreadCrumbEntryIcon = 'fas fa-wrench';
				$this->sBreadCrumbEntryIconType = static::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES;
			}

			$aNewEntry = [
				'id' => $this->sBreadCrumbEntryId,
				'url' => $this->sBreadCrumbEntryUrl,
				'label' => utils::EscapeHtml($this->sBreadCrumbEntryLabel),
				'description' => utils::EscapeHtml($this->sBreadCrumbEntryDescription),
				'icon' => $this->sBreadCrumbEntryIcon,
				'icon_type' => $this->sBreadCrumbEntryIconType,
			];
		}

		return $aNewEntry;
	}

	/**
	 * Render the banner HTML which can come from both iTop itself and from extensions
	 *
	 * @see \iPageUIExtension::GetBannerHtml()
	 * @internal
	 *
	 * @return string
	 * @since 3.0.0
	 */
	protected function RenderBannerHtml()
	{
		$sBannerHtml = '';

		// Call the extensions to add content to the page, warning they can also add styles or scripts through as they have access to the iTopWebPage
		$sAPIClassName = iPageUIExtension::class;
		/** @var \iPageUIExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins($sAPIClassName) as $oExtensionInstance) {
			DeprecatedCallsLog::NotifyDeprecatedPhpApi(get_class($oExtensionInstance), $sAPIClassName, "GetBannerHtml", "use " . iPageUIBlockExtension::class . "::GetBannerBlock() instead");
			$sBannerHtml .= $oExtensionInstance->GetBannerHtml($this);
		}

		return $sBannerHtml;
	}

	/**
	 * Render the banner UIBlock which can come from both iTop itself and from extensions
	 *
	 * @see \iPageUIExtension::GetBannerHtml()
	 * @internal
	 *
	 * @return iUIBlock
	 * @since 3.0.0
	 */
	protected function RenderBannerBlock()
	{
		$oBanner = new UIContentBlock();

		// Call the extensions to add content to the page, warning they can also add styles or scripts through as they have access to the iTopWebPage
		/** @var \iPageUIBlockExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins('iPageUIBlockExtension') as $oExtensionInstance)
		{
			$oBlock =  $oExtensionInstance->GetBannerBlock();
			if ($oBlock) {
				$oBanner->AddSubBlock($oBlock);
			}
		}

		return $oBanner;
	}

	/**
	 * Render the header HTML which can come from both iTop itself and from extensions
	 *
	 * @see \iPageUIExtension::GetNorthPaneHtml()
	 * @internal
	 *
	 * @return string
	 * @since 3.0.0
	 */
	protected function RenderHeaderHtml()
	{
		$sHeaderHtml = '';

		// Call the extensions to add content to the page, warning they can also add styles or scripts through as they have access to the iTopWebPage
		$sAPIClassName = iPageUIExtension::class;
		/** @var \iPageUIExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins($sAPIClassName) as $oExtensionInstance) {
			DeprecatedCallsLog::NotifyDeprecatedPhpApi(get_class($oExtensionInstance), $sAPIClassName, "GetNorthPaneHtml", "use " . iPageUIBlockExtension::class . "::GetHeaderBlock() instead");
			$sHeaderHtml .= $oExtensionInstance->GetNorthPaneHtml($this);
		}

		return $sHeaderHtml;
	}

	/**
	 * Render the header UIBlock which can come from both iTop itself and from extensions
	 *
	 * @see \iPageUIExtension::GetHeaderHtml()
	 * @internal
	 *
	 * @return iUIBlock
	 * @since 3.0.0
	 */
	protected function RenderHeaderBlock()
	{
		$oHeader = new UIContentBlock();

		// Log KPIs
		if (UserRights::IsAdministrator() && ExecutionKPI::IsEnabled()) {
			$oKPIAlert = AlertUIBlockFactory::MakeForInformation('KPIs', ExecutionKPI::GetDescription())
				->SetIsClosable(false)
				->SetIsCollapsible(false);
			$oHeader->AddSubBlock($oKPIAlert);
		}

		// Archive mode
		if (utils::IsArchiveMode()) {
			$oArchiveAlert = AlertUIBlockFactory::MakeForInformation(Dict::S('UI:ArchiveMode:Banner'), '')
				->SetIsClosable(false)
				->SetIsCollapsible(false);
			$oHeader->AddSubBlock($oArchiveAlert);
		}

		// Access mode
		$sRestrictionMessage ='';
		if (!MetaModel::DBHasAccess(ACCESS_ADMIN_WRITE)) {
			$sRestrictionMessage = Dict::S('UI:AccessRO-All');
		}
		elseif (!MetaModel::DBHasAccess(ACCESS_USER_WRITE)) {
			$sRestrictionMessage = Dict::S('UI:AccessRO-Users');
		}

		if (!empty($sRestrictionMessage)) {
			$sAdminMessage = trim(MetaModel::GetConfig()->Get('access_message'));
			$sRestrictionTitle = empty($sAdminMessage) ? '' : $sAdminMessage;

			$oRestrictionAlert = AlertUIBlockFactory::MakeForWarning($sRestrictionTitle, $sRestrictionMessage, 'ibo-access-readonly-alert')
				->SetIsClosable(false)
				->SetIsCollapsible(false);
			$oHeader->AddSubBlock($oRestrictionAlert);
		}

		// Misc. app. messages
		foreach ($this->m_aMessages as $aMessage) {
			$sMessageForHtml = $aMessage['message'];
			if ($aMessage['tip']) {
				$sTooltipForHtml = utils::HtmlEntities($aMessage['tip']);
				$sMessageForHtml = <<<HTML
<div data-tooltip-content="$sTooltipForHtml">$sMessageForHtml</div>
HTML;
			}
			// Note: Message icon has been ignored during 3.0 migration. If we want them back, we should find a proper way to integrate them, not just putting an <img /> tag
			$oAppMessageAlert = AlertUIBlockFactory::MakeForInformation('', $sMessageForHtml);
			$oHeader->AddSubBlock($oAppMessageAlert);
		}

		// Call the extensions to add content to the page, warning they can also add styles or scripts through as they have access to the iTopWebPage
		/** @var \iPageUIBlockExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins('iPageUIBlockExtension') as $oExtensionInstance)
		{
			$oBlock = $oExtensionInstance->GetHeaderBlock();
			if ($oBlock) {
				$oHeader->AddSubBlock($oBlock);
			}
		}

		return $oHeader;
	}

	/**
	 * Render the footer HTML which can come from both iTop itself and from extensions
	 *
	 * @see \iPageUIExtension::GetSouthPaneHtml()
	 * @internal
	 *
	 * @return string
	 * @since 3.0.0
	 */
	protected function RenderFooterHtml()
	{
		$sFooterHtml = '';

		// Call the extensions to add content to the page, warning they can also add styles or scripts through as they have access to the iTopWebPage
		$sAPIClassName = iPageUIExtension::class;
		/** @var \iPageUIExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins($sAPIClassName) as $oExtensionInstance) {
			DeprecatedCallsLog::NotifyDeprecatedPhpApi(get_class($oExtensionInstance), $sAPIClassName, "GetSouthPaneHtml", "use " . iPageUIBlockExtension::class . "::GetFooterBlock() instead");
			$sFooterHtml .= $oExtensionInstance->GetSouthPaneHtml($this);
		}

		return $sFooterHtml;
	}

	/**
	 * Render the footer UIBlock which can come from both iTop itself and from extensions
	 *
	 * @see \iPageUIExtension::GetSouthPaneHtml()
	 * @internal
	 *
	 * @return iUIBlock
	 * @since 3.0.0
	 */
	protected function RenderFooterBlock()
	{
		$oFooter = new UIContentBlock();

		// Call the extensions to add content to the page, warning they can also add styles or scripts through as they have access to the iTopWebPage
		/** @var \iPageUIBlockExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins('iPageUIBlockExtension') as $oExtensionInstance) {
			$oBlock = $oExtensionInstance->GetFooterBlock();
			if ($oBlock) {
				$oFooter->AddSubBlock($oBlock);
			}
		}

		return $oFooter;
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function output()
	{
		// Send headers
		if ($this->GetOutputFormat() === 'html') {
			foreach ($this->a_headers as $sHeader) {
				header($sHeader);
			}
		}

		// Render HTKL content
		$sHtml = $this->RenderContent();

		// Echo global HTML
		$oKpi = new ExecutionKPI();
		echo $sHtml;
		$oKpi->ComputeAndReport('Echoing ('.round(strlen($sHtml) / 1024).' Kb)');

		DBSearch::RecordQueryTrace();
		ExecutionKPI::ReportStats();
	}

	/**
	 * @inheritDoc
	 * @since 3.2.0 N°6935
	 */
	protected function RenderContent(): string
	{
		$oKpi = new ExecutionKPI();

		// Data to be passed to the view
		$aData = [];

		// Prepare page metadata
		$sAbsoluteUrlAppRoot = addslashes($this->m_sRootUrl);
		$sFaviconUrl = $this->GetFaviconAbsoluteUrl();
		$sMetadataLanguage = $this->GetLanguageForMetadata();
		$oPrintHeader = null;

		// Prepare internal parts (js files, css files, js snippets, css snippets, ...)
		// - API: External script files
		/** @var \iBackofficeLinkedScriptsExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins('iBackofficeLinkedScriptsExtension') as $oExtensionInstance) {
			foreach ($oExtensionInstance->GetLinkedScriptsAbsUrls() as $sScriptUrl) {
				$this->LinkScriptFromURI($sScriptUrl);
			}
		}
		// - API: Early inline scripts
		/** @var \iBackofficeEarlyScriptExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins('iBackofficeEarlyScriptExtension') as $oExtensionInstance) {
			$this->add_early_script($oExtensionInstance->GetEarlyScript());
		}
		// - API: Inline scripts
		/** @var \iBackofficeScriptExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins('iBackofficeScriptExtension') as $oExtensionInstance) {
			$this->add_script($oExtensionInstance->GetScript());
		}
		// - API: Init. scripts
		/** @var \iBackofficeInitScriptExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins('iBackofficeInitScriptExtension') as $oExtensionInstance) {
			$this->add_init_script($oExtensionInstance->GetInitScript());
		}
		// - API: Ready scripts
		/** @var \iBackofficeReadyScriptExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins('iBackofficeReadyScriptExtension') as $oExtensionInstance) {
			$this->add_ready_script($oExtensionInstance->GetReadyScript());
		}
		// - API: External stylesheet files
		/** @var \iBackofficeLinkedStylesheetsExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins('iBackofficeLinkedStylesheetsExtension') as $oExtensionInstance) {
			foreach ($oExtensionInstance->GetLinkedStylesheetsAbsUrls() as $sStylesheetUrl) {
				$this->LinkStylesheetFromURI($sStylesheetUrl);
			}
		}
		// - API: Inline style
		/** @var \iBackofficeStyleExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins('iBackofficeStyleExtension') as $oExtensionInstance) {
			$this->add_style($oExtensionInstance->GetStyle());
		}

		// TODO 3.0.0 not displayed ?
		$this->GetContentLayout()->SetExtraHtmlContent(utils::FilterXSS($this->s_content));

		// TODO 3.0.0 : to be removed
		$this->outputCollapsibleSectionInit();

		// Base structure of data to pass to the TWIG template
		$aData['aPage'] = [
			'sAbsoluteUrlAppRoot' => $sAbsoluteUrlAppRoot,
			'sTitle'              => $this->s_title,
			'sFaviconUrl'         => $sFaviconUrl,
			'aMetadata'           => [
				'sCharset' => static::PAGES_CHARSET,
				'sLang'    => $sMetadataLanguage,
			],
			'oPrintHeader'        => $oPrintHeader,
			'isPrintable'         => $this->IsPrintableVersion(),
		];

		$aData['aBlockParams'] = $this->GetBlockParams();

		// Base tag
		// Note: We might consider to put the app_root_url parameter here, but that would need a BIG rework on iTop AND the extensions to replace all the "../images|js|css/xxx.yyy"...
		if (!empty($this->a_base['href'])) {
			$aData['aPage']['aMetadata']['sBaseUrl'] = $this->a_base['href'];
		}

		if ($this->a_base['target'] != '') {
			$aData['aPage']['aMetadata']['sBaseTarget'] = $this->a_base['target'];
		}

		// Layouts
		$aData['aLayouts'] = [
			'sBanner' => $this->RenderBannerHtml(),
			'oBanner' => $this->RenderBannerBlock(),
			'sHeader' => $this->RenderHeaderHtml(),
			'oHeader' => $this->RenderHeaderBlock(),
			'sFooter' => $this->RenderFooterHtml(),
			'oFooter' => $this->RenderFooterBlock(),
		];
		// - Prepare navigation menu
		$aData['aLayouts']['oNavigationMenu'] = $this->GetNavigationMenuLayout();
		$aData['aDeferredBlocks']['oNavigationMenu'] = $this->GetDeferredBlocks($this->GetNavigationMenuLayout());
		// - Prepare top bar
		$aData['aLayouts']['oTopBar'] = $this->GetTopBarLayout();
		$aData['aDeferredBlocks']['oTopBar'] = $this->GetDeferredBlocks($this->GetTopBarLayout());
		// - Prepare content
		$aData['aLayouts']['oPageContent'] = $this->GetContentLayout();
		$aData['aDeferredBlocks']['oPageContent'] = $this->GetDeferredBlocks($this->GetContentLayout());
		// - Prepare generic templates
		$aData['aTemplates'] = array();

		// TODO 3.1 Replace hardcoded 'Please wait' with dict entries

		// - Modal template with loader
		$oModalTemplateContentBlock = new UIContentBlock();
		$oModalTemplateContentBlock->AddCSSClass('ibo-modal')
			->AddDataAttribute('role', 'ibo-modal')
			->AddSubBlock(SpinnerUIBlockFactory::MakeMedium(null, 'Please wait'));
		$aData['aTemplates'][] = TemplateUIBlockFactory::MakeForBlock('ibo-modal-template', $oModalTemplateContentBlock);

		// - Small loader template
		$oSmallLoaderTemplateContentBlock = new UIContentBlock();
		$oSmallLoaderTemplateContentBlock->AddSubBlock(SpinnerUIBlockFactory::MakeSmall(null , 'Please wait'));
		$aData['aTemplates'][] = TemplateUIBlockFactory::MakeForBlock('ibo-small-loading-placeholder-template', $oSmallLoaderTemplateContentBlock);

		// - Large loader template
		$oLargeLoaderTemplateContentBlock = new UIContentBlock();
		$oLargeLoaderTemplateContentBlock->AddSubBlock(SpinnerUIBlockFactory::MakeLarge(null , 'Please wait'));
		$aData['aTemplates'][] = TemplateUIBlockFactory::MakeForBlock('ibo-large-loading-placeholder-template', $oLargeLoaderTemplateContentBlock);

		// - Do not show again template
		$aData['aTemplates'][] = TemplateUIBlockFactory::MakeForBlock('ibo-modal-option--do-not-show-again-template', new DoNotShowAgainOptionBlock());

		// - Retrieve layouts linked files
		//   Note: Adding them now instead of in the template allow us to remove duplicates and lower the browser parsing time
		/** @var \Combodo\iTop\Application\UI\Base\UIBlock|string $oLayout */
		foreach ($aData['aLayouts'] as $oLayout) {
			if (!$oLayout instanceof UIBlock) {
				continue;
			}

			ConsoleBlockRenderer::AddCssJsToPage($this, $oLayout, $aData);
		}

		// Components
		// Note: For now all components are either included in the layouts above or put in page through the AddUiBlock() API, so there is no need to do anything more.
		$this->InitializeKeyboardShortcuts();

		// - Generate necessary dict. files
		if ($this->bAddJSDict) {
			$this->output_dict_entries();
		}

		// Variable content of the page
		$aData['aPage'] = array_merge(
			$aData['aPage'],
			[
				'aPreloadedFonts' => $this->aPreloadedFonts,
				'aCssFiles' => $this->a_linked_stylesheets,
				'aCssInline' => $this->a_styles,
				'aJsInlineEarly' => $this->a_early_scripts,
				'aJsFiles' => $this->a_linked_scripts,
				'aJsInlineOnInit' => $this->a_init_scripts,
				'aJsInlineOnDomReady' => $this->GetReadyScripts(),
				'aJsInlineLive' => $this->a_scripts,
				// TODO 3.0.0: TEMP, used while developping, remove it.
				'sSanitizedContent' => utils::FilterXSS($this->s_content),
				'sDeferredContent' => utils::FilterXSS($this->s_deferred_content),
				'sCapturedOutput' => utils::FilterXSS(trim($this->ob_get_clean_safe())),
			]
		);

		$oTwigEnv = TwigHelper::GetTwigEnvironment(BlockRenderer::TWIG_BASE_PATH, BlockRenderer::TWIG_ADDITIONAL_PATHS);

		// Render final TWIG into global HTML
		$sHtml = TwigHelper::RenderTemplate($oTwigEnv, $aData, $this->GetTemplateRelPath());

		$oKpi->ComputeAndReport("Rendering content (".static::class.")");

		return $sHtml;
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function AddTabContainer($sTabContainer, $sPrefix = '', iUIContentBlock $oParentBlock = null)
	{
		if(is_null($oParentBlock)) {
			$oParentBlock = PanelUIBlockFactory::MakeNeutral('');
			$this->AddUiBlock($oParentBlock);
		}

		$oParentBlock->AddSubBlock($this->m_oTabs->AddTabContainer($sTabContainer, $sPrefix));
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function AddToTab($sTabContainer, $sTabCode, $sHtml)
	{
		$this->add($this->m_oTabs->AddToTab($sTabContainer, $sTabCode, $sHtml));
	}

	/**
	 * @inheritDoc
	 */
	public function SetCurrentTabContainer($sTabContainer = '')
	{
		return $this->m_oTabs->SetCurrentTabContainer($sTabContainer);
	}

	/**
	 * @inheritDoc
	 *
	 * @param string|null $sTabDescription {@see \Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\Tab::$sDescription}
	 * @since 3.1.0 N°5920 Add $sTabDescription argument
	 */
	public function SetCurrentTab($sTabCode = '', $sTabTitle = null, ?string $sTabDescription = null)
	{
		return $this->m_oTabs->SetCurrentTab($sTabCode, $sTabTitle, $sTabDescription);
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 * @since 2.0.3
	 */
	public function AddAjaxTab($sTabCode, $sUrl, $bCache = true, $sTabTitle = null, $sPlaceholder = null)
	{
		$this->add($this->m_oTabs->AddAjaxTab($sTabCode, $sUrl, $bCache, $sTabTitle, $sPlaceholder));
	}

	/**
	 * @inheritDoc
	 */
	public function GetCurrentTab()
	{
		return $this->m_oTabs->GetCurrentTab();
	}

	/**
	 * @inheritDoc
	 */
	public function RemoveTab($sTabCode, $sTabContainer = null)
	{
		$this->m_oTabs->RemoveTab($sTabCode, $sTabContainer);
	}

	/**
	 * @inheritDoc
	 */
	public function FindTab($sPattern, $sTabContainer = null)
	{
		return $this->m_oTabs->FindTab($sPattern, $sTabContainer);
	}

	/**
	 * Make the given tab the active one, as if it were clicked
	 * DOES NOT WORK: apparently in the *old* version of jquery
	 * that we are using this is not supported... TO DO upgrade
	 * the whole jquery bundle...
	 *
	 * @param string $sTabContainer
	 * @param string $sTabCode
	 *
	 * @deprecated 3.0.0
	 */
	public function SelectTab($sTabContainer, $sTabCode)
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod();
		$this->add_ready_script($this->m_oTabs->SelectTab($sTabContainer, $sTabCode));
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function add($sHtml)
	{
		if (($this->m_oTabs->GetCurrentTabContainer() != '') && ($this->m_oTabs->GetCurrentTab() != '')) {
			$this->m_oTabs->AddToCurrentTab($sHtml);
		} else {
			parent::add($sHtml);
		}
	}

	public function AddUiBlock(?iUIBlock $oBlock): ?iUIBlock
	{
		if (is_null($oBlock)) {
			return null;
		}
		if (($this->m_oTabs->GetCurrentTabContainer() != '') && ($this->m_oTabs->GetCurrentTab() != '')) {
			return $this->m_oTabs->AddUIBlockToCurrentTab($oBlock);
		}
		return parent::AddUiBlock($oBlock);
	}

	/**
	 * @inheritDoc
	 */
	public function start_capture()
	{
		$sCurrentTabContainer = $this->m_oTabs->GetCurrentTabContainer();
		$sCurrentTab = $this->m_oTabs->GetCurrentTab();

		if (!empty($sCurrentTabContainer) && !empty($sCurrentTab))
		{
			$iOffset = $this->m_oTabs->GetCurrentTabLength();

			return array('tc' => $sCurrentTabContainer, 'tab' => $sCurrentTab, 'offset' => $iOffset);
		}
		else
		{
			return parent::start_capture();
		}
	}

	/**
	 * @inheritDoc
	 */
	public function end_capture($offset)
	{
		if (is_array($offset))
		{
			if ($this->m_oTabs->TabExists($offset['tc'], $offset['tab']))
			{
				$sCaptured = $this->m_oTabs->TruncateTab($offset['tc'], $offset['tab'], $offset['offset']);
			}
			else
			{
				$sCaptured = '';
			}
		}
		else
		{
			$sCaptured = parent::end_capture($offset);
		}

		return $sCaptured;
	}

	/**
	 * Set the message to be displayed in the 'app-banner' section at the top of the page
	 *
	 * @param string $sHtmlMessage
	 */
	public function SetMessage($sHtmlMessage)
	{
		$sHtmlIcon = '<span class="fas fa-comment fa-1x"></span>';
		$this->AddApplicationMessage($sHtmlMessage, $sHtmlIcon);
	}

	/**
	 * Add message to be displayed in the 'app-banner' section at the top of the page
	 *
	 * @param string $sHtmlMessage
	 * @param string|null $sHtmlIcon
	 * @param string|null $sTip
	 */
	public function AddApplicationMessage($sHtmlMessage, $sHtmlIcon = null, $sTip = null)
	{
		if (strlen($sHtmlMessage))
		{
			$this->m_aMessages[] = array(
				'icon' => $sHtmlIcon,
				'message' => $sHtmlMessage,
				'tip' => $sTip,
			);
		}
	}

	/**
	 * Adds in the page a container with the header_message CSS class
	 *
	 * @param string $sContent
	 * @param string $sCssClasses CSS classes to add to the container
	 *
	 * @since 2.6.0
	 */
	public function AddHeaderMessage(string $sContent, string $sCssClasses = 'message_info')
	{
		switch ($sCssClasses) {
			case 'message_ok':
				$oAlert = AlertUIBlockFactory::MakeForSuccess('', $sContent);
				break;
			case 'message_warning':
				$oAlert = AlertUIBlockFactory::MakeForWarning('', $sContent);
				break;
			case 'message_error':
				$oAlert = AlertUIBlockFactory::MakeForDanger('', $sContent);
				break;
			case 'message_info':
			default:
				$oAlert = AlertUIBlockFactory::MakeForInformation('', $sContent);
				break;

		}
		$oAlert->AddCSSClass($sCssClasses);
		$this->AddUiBlock($oAlert);
	}

	/**
	 *
	 * @return BlockPrintHeader
	 */
	protected function OutputPrintable(): BlockPrintHeader
	{
		$oBlock = new BlockPrintHeader();

		return $oBlock;
	}

	/**
	 * @param string $sKey
	 * @param $value
	 *
	 * @return iTopWebPage
	 * @since 3.0.0
	 */
	public function SetBlockParam(string $sKey, $value)
	{
		$oGlobalSearch = $this->GetTopBarLayout()->GetGlobalSearch();
		$sGlobalSearchId = $oGlobalSearch->GetId();
		switch ($sKey) {
			case "$sGlobalSearchId.sQuery":
				$oGlobalSearch->SetQuery($value);
				break;
		}

		return parent::SetBlockParam($sKey, $value);
	}

}
