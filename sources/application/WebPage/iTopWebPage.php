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


use Combodo\iTop\Application\TwigBase\Twig\TwigHelper;
use Combodo\iTop\Application\UI\Component\Breadcrumbs\Breadcrumbs;
use Combodo\iTop\Application\UI\Component\Panel\PanelFactory;
use Combodo\iTop\Application\UI\iUIBlock;
use Combodo\iTop\Application\UI\Layout\iUIContentBlock;
use Combodo\iTop\Application\UI\Layout\NavigationMenu\NavigationMenuFactory;
use Combodo\iTop\Application\UI\Layout\PageContent\PageContent;
use Combodo\iTop\Application\UI\Layout\PageContent\PageContentFactory;
use Combodo\iTop\Application\UI\Layout\TopBar\TopBar;
use Combodo\iTop\Application\UI\Layout\TopBar\TopBarFactory;
use Combodo\iTop\Application\UI\UIBlock;
use Combodo\iTop\Renderer\BlockRenderer;

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

	/** @var string DEFAULT_PAGE_TEMPLATE_REL_PATH The relative path (from <ITOP>/templates/) to the default page template */
	const DEFAULT_PAGE_TEMPLATE_REL_PATH = 'pages/backoffice/itopwebpage/layout';

	private $m_aMessages;
	private $m_aInitScript = array();

	/** @var \TabManager */
	protected $m_oTabs;
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
	 * @throws \Exception
	 */
	public function __construct($sTitle, $bPrintable = false)
	{
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

		$this->SetTopBarLayout(TopBarFactory::MakeStandard($this->GetBreadCrumbsNewEntry()));

		utils::InitArchiveMode();

		$this->m_aMessages = array();
		$this->SetRootUrl(utils::GetAbsoluteUrlAppRoot());
		$this->add_header("Content-type: text/html; charset=".self::PAGES_CHARSET);
		$this->add_header('Cache-control: no-cache, no-store, must-revalidate');
		$this->add_header('Pragma: no-cache');
		$this->add_header('Expires: 0');
		$this->add_header('X-Frame-Options: deny');
		// TODO 3.0.0: Add only what's necessary
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/jquery.treeview.css');
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/jquery-ui-timepicker-addon.css');
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/jquery.multiselect.css');
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/magnific-popup.css');
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/c3.min.css');
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'node_modules/tippy.js/dist/tippy.css');
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'node_modules/tippy.js/animations/shift-away-subtle.css');
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/font-awesome/css/all.min.css');
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/font-combodo/font-combodo.css');
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'js/ckeditor/plugins/codesnippet/lib/highlight/styles/obsidian.css');
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/selectize.default.css');

		// TODO 3.0.0: Add only what's necessary
		// jquery.layout : not used anymore in the whole console but only in some pages (datamodel viewer, dashboard edit, ...)
		// TODO : remove adding jquery.layout in iTopWebPage, and only add it when necessary (component level)
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.layout.min.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.ba-bbq.min.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.treeview.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/date.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery-ui-timepicker-addon.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery-ui-timepicker-addon-i18n.min.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.blockUI.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/utils.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/ckeditor/ckeditor.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/ckeditor/adapters/jquery.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/ckeditor/plugins/codesnippet/lib/highlight/highlight.pack.js');
		/** @deprecated qTip will be removed in 3.1.0, use Tippy.js instead */
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.qtip-1.0.min.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'node_modules/@popperjs/core/dist/umd/popper.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'node_modules/tippy.js/dist/tippy-bundle.umd.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/property_field.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/icon_select.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/raphael-min.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/selectize.min.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/d3.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/c3.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.multiselect.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/ajaxfileupload.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.mousewheel.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.magnific-popup.min.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/moment-with-locales.min.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/showdown.min.js');
		$this->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/pages/backoffice/toolbox.js');

		$this->add_dict_entry('UI:FillAllMandatoryFields');

		$this->add_dict_entries('Error:');
		$this->add_dict_entries('UI:Button:');
		$this->add_dict_entries('UI:Search:');
		$this->add_dict_entry('UI:UndefinedObject');
		$this->add_dict_entries('Enum:Undefined');


		if (!$this->IsPrintableVersion())
		{
			$this->PrepareLayout();
		}
	}

	/**
	 *
	 */
	protected function PrepareLayout()
	{
		$sJSDisconnectedMessage = json_encode(Dict::S('UI:DisconnectedDlgMessage'));
		$sJSTitle = json_encode(Dict::S('UI:DisconnectedDlgTitle'));
		$sJSLoginAgain = json_encode(Dict::S('UI:LoginAgain'));
		$sJSStayOnThePage = json_encode(Dict::S('UI:StayOnThePage'));
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
			'buttonText' => '<i class="fas fa-calendar-alt"></i>',
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

		// TODO 3.0.0: This is for tag sets, refactor the attribute markup so it contains the necessary
		// TODO 3.0.0: data-tooltip-* attributes to activate the tooltips automatically (see /js/pages/backoffice/toolbox.js)
		// Attribute set tooltip on items
		$this->add_ready_script(
			<<<JS
	$('.attribute-set-item').each(function(){
		// Encoding only title as the content is already sanitized by the HTML attribute.
        var sLabel = $('<div/>').text($(this).attr('data-label')).html();
		var sDescription = $(this).attr('data-description');
		
		var oContent = {};
		
		// Make nice tooltip if item has a description, otherwise just make a title attribute so the truncated label can be read.
		if(sDescription !== '')
		{
			oContent.title = { text: sLabel };
			oContent.text = sDescription;
	    }
	    else
	    {
	    	oContent.text = sLabel;
	    }
	    
	    $(this).qtip({
	       content: oContent,
	       show: { delay: 300, when: 'mouseover' },
	       hide: { delay: 140, when: 'mouseout', fixed: true },
	       style: { name: 'dark', tip: 'bottomLeft' },
	       position: { corner: { target: 'topMiddle', tooltip: 'bottomLeft' }}
	    });
	});
JS
		);

		// TODO 3.0.0: Change CSS class and extract this in backoffice/toolbox.js
		// Make image attributes zoomable
		$this->add_ready_script(
			<<<JS
		$('.view-image img').each(function(){
			$(this).attr('href', $(this).attr('src'))
		})
		.magnificPopup({type: 'image', closeOnContentClick: true });
JS
		);

		// TODO 3.0.0: Change CSS class and extract this in backoffice/toolbox.js
		// Highlight code content created with CKEditor
		$this->add_ready_script(
			<<<JS
		// Highlight code content for HTML AttributeText
        $("[data-attribute-type='AttributeText'] .HTML pre").each(function(i, block) {
            hljs.highlightBlock(block);
        });        
		// Highlight code content for CaseLogs
		$("[data-attribute-type='AttributeCaseLog'] .caselog_entry_html pre").each(function(i, block) {
            hljs.highlightBlock(block);
        });
JS
		);

		// TODO 3.0.0: What is this for?
		$this->add_ready_script(
			<<< JS
	
	// Adjust initial size
	$('.v-resizable').each( function()
		{
			var parent_id = $(this).parent().id;
			// Restore the saved height
			var iHeight = GetUserPreference(parent_id+'_'+this.id+'_height', undefined);
			if (iHeight != undefined)
			{
				$(this).height(parseInt(iHeight, 10)); // Parse in base 10 !);
			}
			// Adjust the child 'item''s height and width to fit
			var container = $(this);
			var fixedWidth = container.parent().innerWidth() - 6;
			// Set the width to fit the parent
			$(this).width(fixedWidth);
			var headerHeight = $(this).find('.drag_handle').height();
			// Now adjust the width and height of the child 'item'
			container.find('.item').height(container.innerHeight() - headerHeight - 12).width(fixedWidth - 10);
		}
	);
	// Make resizable, vertically only everything that claims to be v-resizable !
	$('.v-resizable').resizable( { handles: 's', minHeight: $(this).find('.drag_handle').height(), minWidth: $(this).parent().innerWidth() - 6, maxWidth: $(this).parent().innerWidth() - 6, stop: function()
		{
			// Adjust the content
			var container = $(this);
			var headerHeight = $(this).find('.drag_handle').height();
			container.find('.item').height(container.innerHeight() - headerHeight - 12);//.width(container.innerWidth());
			var parent_id = $(this).parent().id;
			SetUserPreference(parent_id+'_'+this.id+'_height', $(this).height(), true); // true => persistent
		}
	} );
	
	// Shortcut menu actions
	$('.actions_button a').click( function() {
		aMatches = /#(.*)$/.exec(window.location.href);
		if (aMatches != null)
		{
			currentHash = aMatches[1];
			if ( /#(.*)$/.test(this.href))
			{
				this.href = this.href.replace(/#(.*)$/, '#'+currentHash);
			}
		}
	});

	// End of Tabs handling

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
	$('#ModalDlg').dialog({ autoOpen: false, modal: true, width: 0.8*docWidth, height: 'auto', maxHeight: $(window).height() - 50 }); // JQuery UI dialogs
	ShowDebug();
	$('#logOffBtn>ul').popupmenu();
	
	$('.caselog_header').click( function () { $(this).toggleClass('open').next('.caselog_entry,.caselog_entry_html').toggle(); });
	
	$(document).ajaxSend(function(event, jqxhr, options) {
		jqxhr.setRequestHeader('X-Combodo-Ajax', 'true');
	});
	$(document).ajaxError(function(event, jqxhr, options) {
		if (jqxhr.status == 401)
		{
			$('<div>'+$sJSDisconnectedMessage+'</div>').dialog({
				modal:true,
				title: $sJSTitle,
				close: function() { $(this).remove(); },
				minWidth: 400,
				buttons: [
					{ text: $sJSLoginAgain, click: function() { window.location.href= GetAbsoluteUrlAppRoot()+'pages/UI.php' } },
					{ text: $sJSStayOnThePage, click: function() { $(this).dialog('close'); } }
				]
			});
		}
	});
JS
		);

		// TODO 3.0.0: To preserve
		$this->add_ready_script(InlineImage::FixImagesWidth());

		/*
		 * Not used since the sorting of the tables is always performed server-side
		AttributeDateTime::InitTableSorter($this, 'custom_date_time');
		AttributeDate::InitTableSorter($this, 'custom_date');
		*/

		// TODO 3.0.0: What is this for?
		$sUserPrefs = appUserPreferences::GetAsJSON();
		$this->add_script(
			<<<JS
//		// for JQuery history
//		function history_callback(hash)
//		{
//			// do stuff that loads page content based on hash variable
//			var aMatches = /^tab_(.*)$/.exec(hash);
//			if (aMatches != null)
//			{
//				var tab = $('#'+hash);
//				tab.parents('div[id^=tabbedContent]:first').tabs('select', aMatches[1]);
//			}
//		}

		function goBack()
		{
			window.history.back();
		}
		
		function BackToDetails(sClass, id, sDefaultUrl, sOwnershipToken)
		{
			window.bInCancel = true;
			if (id > 0)
			{
				sToken = '';
				if (sOwnershipToken != undefined)
				{
					sToken = '&token='+sOwnershipToken;
				}
				window.location.href = AddAppContext(GetAbsoluteUrlAppRoot()+'pages/UI.php?operation=release_lock_and_details&class='+sClass+'&id='+id+sToken);
			}
			else
			{
				window.location.href = sDefaultUrl; // Already contains the context...				
			}
		}

		function BackToList(sClass)
		{
			window.location.href = AddAppContext(GetAbsoluteUrlAppRoot()+'pages/UI.php?operation=search_oql&oql_class='+sClass+'&oql_clause=WHERE id=0');
		}
		
		function ShowDebug()
		{
			if ($('#rawOutput > div').html() != '')
			{
				$('#rawOutput').dialog( {autoOpen: true, modal:false, width: '80%'});
			}
		}
		
		var oUserPreferences = $sUserPrefs;

		// For disabling the CKEditor at init time when the corresponding textarea is disabled !
		CKEDITOR.plugins.add( 'disabler',
		{
			init : function( editor )
			{
				editor.on( 'instanceReady', function(e)
				{
					e.removeListener();
					$('#'+ editor.name).trigger('update');
				});
			}
			
		});

		
		function FixPaneVis()
		{
			$('.ui-layout-center, .ui-layout-north, .ui-layout-south').css({display: 'block'});
		}
JS
		);
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
	}

	/**
	 * @return string
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function GetSiloSelectionForm()
	{
		// List of visible Organizations
		$iCount = 0;
		$oSet = null;
		if (MetaModel::IsValidClass('Organization'))
		{
			// Display the list of *favorite* organizations... but keeping in mind what is the real number of organizations
			$aFavoriteOrgs = appUserPreferences::GetPref('favorite_orgs', null);
			$oSearchFilter = new DBObjectSearch('Organization');
			$oSearchFilter->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', true);
			$oSet = new CMDBObjectSet($oSearchFilter);
			$iCount = $oSet->Count(); // total number of existing Orgs

			// Now get the list of Orgs to be displayed in the menu
			$oSearchFilter = DBObjectSearch::FromOQL(ApplicationMenu::GetFavoriteSiloQuery());
			$oSearchFilter->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', true);
			if (!empty($aFavoriteOrgs))
			{
				$oSearchFilter->AddCondition('id', $aFavoriteOrgs, 'IN');
			}
			$oSet = new CMDBObjectSet($oSearchFilter); // List of favorite orgs
		}
		switch ($iCount)
		{
			case 0:
			case 1:
				// No such dimension/silo or only one possible choice => nothing to select
				$sHtml = '<div id="SiloSelection"><!-- nothing to select --></div>';
				break;

			default:
				$oAppContext = new ApplicationContext();
				$iCurrentOrganization = $oAppContext->GetCurrentValue('org_id');
				$sHtml = '<div id="SiloSelection">';
				$sHtml .= '<form style="display:inline" action="'.utils::GetAbsoluteUrlAppRoot().'pages/UI.php">'; //<select class="org_combo" name="c[org_id]" title="Pick an organization" onChange="this.form.submit();">';

				$oWidget = new UIExtKeyWidget('Organization', 'org_id', '', true /* search mode */);
				$sHtml .= $oWidget->DisplaySelect($this, 50, false, '', $oSet, $iCurrentOrganization, false, 'c[org_id]', '',
					array(
						'iFieldSize' => 20,
						'iMinChars' => MetaModel::GetConfig()->Get('min_autocomplete_chars'),
						'sDefaultValue' => Dict::S('UI:AllOrganizations'),
					));
				$this->add_ready_script('$("#org_id").bind("extkeychange", function() { $("#SiloSelection form").submit(); } )');
				$this->add_ready_script("$('#label_org_id').click( function() { if ($('#org_id').val() == '') { $(this).val(''); } } );\n");
				// Add other dimensions/context information to this form
				$oAppContext->Reset('org_id'); // org_id is handled above and we want to be able to change it here !
				$oAppContext->Reset('menu'); // don't pass the menu, since a menu may expect more parameters
				$sHtml .= $oAppContext->GetForForm(); // Pass what remains, if anything...
				$sHtml .= '</form>';
				$sHtml .= '</div>';
		}

		return $sHtml;
	}


	/**
	 * Return the navigation menu layout (id, menu groups, ...)
	 *
	 * @internal
	 * @return \Combodo\iTop\Application\UI\Layout\NavigationMenu\NavigationMenu
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 * @since 3.0.0
	 */
	protected function GetNavigationMenuLayout()
	{
		return NavigationMenuFactory::MakeStandard();
	}

	/**
	 * Set the content layout (main content, [side content,] manually added content, ...)
	 * This function is public as the developer needs to be able to set how the content will be displayed.
	 *
	 * @internal
	 *
	 * @param \Combodo\iTop\Application\UI\Layout\PageContent\PageContent $oLayout
	 *
	 * @return $this
	 * @since 3.0.0
	 */
	public function SetContentLayout(PageContent $oLayout)
	{
		$this->oContentLayout = $oLayout;

		return $this;
	}

	/**
	 * Return the content layout (main content, [side content,] manually added content, ...)
	 *
	 * @internal
	 * @return \Combodo\iTop\Application\UI\Layout\PageContent\PageContent
	 * @since 3.0.0
	 */
	protected function GetContentLayout()
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
				'label' => utils::HtmlEntities($this->sBreadCrumbEntryLabel),
				'description' => utils::HtmlEntities($this->sBreadCrumbEntryDescription),
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

		// Call the extensions to add content to the page, warning they can also add styles or scripts through as they have access to the \iTopWebPage
		foreach (MetaModel::EnumPlugins('iPageUIExtension') as $oExtensionInstance)
		{
			$sBannerHtml .= $oExtensionInstance->GetBannerHtml($this);
		}

		return $sBannerHtml;
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

		//TODO: NB the whole section needs to be refactored

		if (UserRights::IsAdministrator() && ExecutionKPI::IsEnabled())
		{
			// TODO 3.0.0: Don't forget this dude!
			$sHeaderHtml .= '<div class="app-message"><span style="padding:5px;">'.ExecutionKPI::GetDescription().'<span></div>';
		}

		// TODO 3.0.0: Don't forget this!
		if (utils::IsArchiveMode())
		{
			$sIcon = '<span class="fas fa-lock fa-1x"></span>';
			$this->AddApplicationMessage(Dict::S('UI:ArchiveMode:Banner'), $sIcon, Dict::S('UI:ArchiveMode:Banner+'));
		}

		// TODO 3.0.0: Move this in the Header method
		$sRestrictions = '';
		if (!MetaModel::DBHasAccess(ACCESS_ADMIN_WRITE))
		{
			if (!MetaModel::DBHasAccess(ACCESS_ADMIN_WRITE))
			{
				$sRestrictions = Dict::S('UI:AccessRO-All');
			}
		}
		elseif (!MetaModel::DBHasAccess(ACCESS_USER_WRITE))
		{
			$sRestrictions = Dict::S('UI:AccessRO-Users');
		}
		if (strlen($sRestrictions) > 0)
		{
			$sIcon =
				<<<EOF
<span class="fa-stack fa-sm">
  <i class="fas fa-pencil-alt fa-flip-horizontal fa-stack-1x"></i>
  <i class="fas fa-ban fa-stack-2x text-danger"></i>
</span>
EOF;

			$sAdminMessage = trim(MetaModel::GetConfig()->Get('access_message'));
			if (strlen($sAdminMessage) > 0)
			{
				$sRestrictions .= '&nbsp;'.$sAdminMessage;
			}
			$this->AddApplicationMessage($sRestrictions, $sIcon);
		}

		// TODO 3.0.0: Move this in the header method
		$sApplicationMessages = '';
		foreach ($this->m_aMessages as $aMessage)
		{
			$sHtmlIcon = $aMessage['icon'] ? $aMessage['icon'] : '';
			$sHtmlMessage = $aMessage['message'];
			$sTitleAttr = $aMessage['tip'] ? 'title="'.htmlentities($aMessage['tip'], ENT_QUOTES, self::PAGES_CHARSET).'"' : '';
			$sApplicationMessages .= '<div class="app-message" '.$sTitleAttr.'><span class="app-message-icon">'.$sHtmlIcon.'</span><span class="app-message-body">'.$sHtmlMessage.'</div></span>';
		}

		// Call the extensions to add content to the page, warning they can also add styles or scripts through as they have access to the \iTopWebPage
		foreach (MetaModel::EnumPlugins('iPageUIExtension') as $oExtensionInstance)
		{
			$sHeaderHtml .= $oExtensionInstance->GetNorthPaneHtml($this);
		}

		return $sHeaderHtml;
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

		// Call the extensions to add content to the page, warning they can also add styles or scripts through as they have access to the \iTopWebPage
		foreach (MetaModel::EnumPlugins('iPageUIExtension') as $oExtensionInstance) {
			$sFooterHtml .= $oExtensionInstance->GetSouthPaneHtml($this);
		}

		return $sFooterHtml;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\iUIBlock $oBlock
	 *
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function RenderInlineTemplatesRecursively(iUIBlock $oBlock): void
	{
		$oBlockRenderer = new BlockRenderer($oBlock);
		$this->add_init_script($oBlockRenderer->RenderJsInline());
		$this->add_style($oBlockRenderer->RenderCssInline());

		foreach ($oBlock->GetSubBlocks() as $oSubBlock) {
			$this->RenderInlineTemplatesRecursively($oSubBlock);
		}
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function output()
	{
		// Data to be passed to the view
		$aData = [];

		$s_captured_output = $this->ob_get_clean_safe();

		// Prepare page metadata
		$sAbsoluteUrlAppRoot = addslashes($this->m_sRootUrl);
		$sFaviconUrl = $this->GetFaviconAbsoluteUrl();
		$sMetadataLanguage = $this->GetLanguageForMetadata();

		// Prepare internal parts (js files, css files, js snippets, css snippets, ...)
		// - Generate necessary dict. files
		$this->output_dict_entries();
		// TODO 3.0.0 not displayed ?
		$this->GetContentLayout()->SetExtraHtmlContent(utils::FilterXSS($this->s_content));

		// TODO 3.0.0 : to be removed
		$this->outputCollapsibleSectionInit();

		// Base structure of data to pass to the TWIG template
		$aData['aPage'] = [
			'sAbsoluteUrlAppRoot' => $sAbsoluteUrlAppRoot,
			'sTitle' => $this->s_title,
			'sFaviconUrl' => $sFaviconUrl,
			'aMetadata' => [
				'sCharset' => static::PAGES_CHARSET,
				'sLang' => $sMetadataLanguage,
			],
		];

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
			'sHeader' => $this->RenderHeaderHtml(),
			'sFooter' => $this->RenderFooterHtml(),
		];
		// - Prepare navigation menu
		$aData['aLayouts']['oNavigationMenu'] = $this->GetNavigationMenuLayout();
		// - Prepare top bar
		$aData['aLayouts']['oTopBar'] = $this->GetTopBarLayout();
		// - Prepare content
		$aData['aLayouts']['oPageContent'] = $this->GetContentLayout();
		// - Retrieve layouts linked files
		//   Note: Adding them now instead of in the template allow us to remove duplicates and lower the browser parsing time
		/** @var \Combodo\iTop\Application\UI\UIBlock|string $oLayout */
		foreach ($aData['aLayouts'] as $oLayout) {
			if (!$oLayout instanceof UIBlock) {
				continue;
			}

			// CSS files
			foreach ($oLayout->GetCssFilesUrlRecursively(true) as $sFileAbsUrl) {
				$this->add_linked_stylesheet($sFileAbsUrl);
			}
			// JS files
			foreach ($oLayout->GetJsFilesUrlRecursively(true) as $sFileAbsUrl) {
				$this->add_linked_script($sFileAbsUrl);
			}

			$this->RenderInlineTemplatesRecursively($oLayout);
		}

		// Components
		// Note: For now all components are either included in the layouts above or put in page through the AddUiBlock() API, so there is no need to do anything more.

		// Variable content of the page
		$aData['aPage'] = array_merge(
			$aData['aPage'],
			[
				'aCssFiles' => $this->a_linked_stylesheets,
				'aCssInline' => $this->a_styles,
				'aJsFiles' => $this->a_linked_scripts,
				'aJsInlineOnInit' => $this->m_aInitScript,
				'aJsInlineOnDomReady' => $this->m_aReadyScripts,
				'aJsInlineLive' => $this->a_scripts,
				// TODO 3.0.0: TEMP, used while developping, remove it.
				'sSanitizedContent' => utils::FilterXSS($this->s_content),
				'sDeferredContent' => utils::FilterXSS($this->s_deferred_content),
				'sCapturedOutput' => utils::FilterXSS($s_captured_output),
			]
		);

		$oTwigEnv = TwigHelper::GetTwigEnvironment(BlockRenderer::TWIG_BASE_PATH, BlockRenderer::TWIG_ADDITIONAL_PATHS);

		// Send headers
		if ($this->GetOutputFormat() === 'html') {
			foreach ($this->a_headers as $sHeader) {
				header($sHeader);
			}
		}

		// Render final TWIG into global HTML
		$oKpi = new ExecutionKPI();
		$sHtml = TwigHelper::RenderTemplate($oTwigEnv, $aData, $this->GetTemplateRelPath());
		$oKpi->ComputeAndReport('TWIG rendering');

		// Echo global HTML
		$oKpi = new ExecutionKPI();
		echo $sHtml;
		$oKpi->ComputeAndReport('Echoing ('.round(strlen($sHtml) / 1024).' Kb)');

		DBSearch::RecordQueryTrace();
		ExecutionKPI::ReportStats();

		return;

		/////////////////////////////////////////////////////////
		////////////////// ☢ DANGER ZONE ☢ /////////////////////
		/////////////////////////////////////////////////////////

		$sForm = $this->GetSiloSelectionForm();

		// Render the tabs in the page (if any)
//		$this->s_content = $this->m_oTabs->RenderIntoContent($this->s_content, $this);

		// Put here the 'ready scripts' that must be executed after all others
		$aMultiselectOptions = array(
			'header' => true,
			'checkAllText' => Dict::S('UI:SearchValue:CheckAll'),
			'uncheckAllText' => Dict::S('UI:SearchValue:UncheckAll'),
			'noneSelectedText' => Dict::S('UI:SearchValue:Any'),
			'selectedText' => Dict::S('UI:SearchValue:NbSelected'),
			'selectedList' => 1,
		);
		$sJSMultiselectOptions = json_encode($aMultiselectOptions);
		$this->add_ready_script(
			<<<EOF
		// Since the event is only triggered when the hash changes, we need to trigger
		// the event now, to handle the hash the page may have loaded with.
		$(window).trigger( 'hashchange' );
		
		// Some table are sort-able, some are not, let's fix this
		$('table.listResults').each( function() { FixTableSorter($(this)); } );
		
		$('.multiselect').multiselect($sJSMultiselectOptions);
EOF
		);

		$this->outputCollapsibleSectionInit();

		// TODO 3.0.0: Is this for the "Debug" popup? We should do a helper to display a popup in various cases (welcome message for example)
		$s_captured_output = $this->ob_get_clean_safe();

		// TODO 3.0.0: Stylesheet for printing instead of having all those "IsPrintableVersion()" ifs
		// TODO 3.0.0: Careful! In the print view, we can actually choose which part to print or not, so it's not just a print stylesheet...
		// special stylesheet for printing, hides the navigation gadgets
		$sHtml .= "<link rel=\"stylesheet\" media=\"print\" type=\"text/css\" href=\"../css/print.css?t=".utils::GetCacheBusterTimestamp()."\" />\n";

		if ($this->GetOutputFormat() == 'html')
		{
//			$sHtml .= $this->output_dict_entries(true); // before any script so that they can benefit from the translations

//			if (!$this->IsPrintableVersion())
//			{
//				$this->add_script("var iPaneVisWatchDog  = window.setTimeout('FixPaneVis()',5000);");
//			}


			// TODO 3.0.0: Should we still do this init vs ready separation?
//			$this->add_script("\$(document).ready(function() {\n{$sInitScripts};\nwindow.setTimeout('onDelayedReady()',10)\n});");
			if ($this->IsPrintableVersion())
			{
				$this->add_ready_script(
					<<<EOF
var sHiddeableChapters = '<div class="light ui-tabs ui-widget ui-widget-content ui-corner-all">';
sHiddeableChapters += '<ul role="tablist" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">';
for (sId in oHiddeableChapters)
{
	sHiddeableChapters += '<li tabindex="-1" role="tab" class="ui-state-default ui-corner-top hideable-chapter" chapter-id="'+sId+'"><span class="tab ui-tabs-anchor">' + oHiddeableChapters[sId] + '</span></li>';
	//alert(oHiddeableChapters[sId]);
}
sHiddeableChapters += '</ul></div>';
$('#hiddeable_chapters').html(sHiddeableChapters);
$('.hideable-chapter').click(function(){
	var sChapterId = $(this).attr('chapter-id');
	$('#'+sChapterId).toggle();
	$(this).toggleClass('strikethrough');
});
$('fieldset').each(function() {
	var jLegend = $(this).find('legend');
	jLegend.remove();
	$(this).wrapInner('<span></span>').prepend(jLegend);
});
$('legend').css('cursor', 'pointer').click(function(){
		$(this).parent('fieldset').toggleClass('not-printable strikethrough');
	});
EOF
				);
			}

		}

		$sBodyClass = "";
		if ($this->IsPrintableVersion())
		{
			$sBodyClass = 'printable-version';
		}
//		$sHtml .= "<body class=\"$sBodyClass\" data-gui-type=\"backoffice\">\n";
		if ($this->IsPrintableVersion())
		{
			$sHtml .= "<div class=\"explain-printable not-printable\">";
			$sHtml .= '<p>'.Dict::Format('UI:ExplainPrintable',
					'<img src="../../../images/eye-open-555.png" style="vertical-align:middle">').'</p>';
			$sHtml .= "<div id=\"hiddeable_chapters\"></div>";
			$sHtml .= '<button onclick="window.print()">'.htmlentities(Dict::S('UI:Button:GoPrint'), ENT_QUOTES,
					self::PAGES_CHARSET).'</button>';
			$sHtml .= '&nbsp;';
			$sHtml .= '<button onclick="window.close()">'.htmlentities(Dict::S('UI:Button:Cancel'), ENT_QUOTES,
					self::PAGES_CHARSET).'</button>';
			$sHtml .= '&nbsp;';

			$sDefaultResolution = '27.7cm';
			$aResolutionChoices = array(
				'100%' => Dict::S('UI:PrintResolution:FullSize'),
				'19cm' => Dict::S('UI:PrintResolution:A4Portrait'),
				'27.7cm' => Dict::S('UI:PrintResolution:A4Landscape'),
				'19.6cm' => Dict::S('UI:PrintResolution:LetterPortrait'),
				'25.9cm' => Dict::S('UI:PrintResolution:LetterLandscape'),
			);
			$sHtml .=
				<<<EOF
<select name="text" onchange='$(".printable-content").width(this.value); $(charts).each(function(i, chart) { $(chart).trigger("resize"); });'>
EOF;
			foreach ($aResolutionChoices as $sValue => $sText)
			{
				$sHtml .= '<option value="'.$sValue.'" '.(($sValue === $sDefaultResolution) ? 'selected' : '').'>'.$sText.'</option>';
			}
			$sHtml .= "</select>";

			$sHtml .= "</div>";
			$sHtml .= "<div class=\"printable-content\" style=\"width: $sDefaultResolution;\">";
		}

		// TODO 3.0.0
//		// Render the text of the global search form
//		$sText = htmlentities(utils::ReadParam('text', '', false, 'raw_data'), ENT_QUOTES, self::PAGES_CHARSET);
//		$sOnClick = " onclick=\"if ($('#global-search-input').val() != '') { $('#global-search form').submit();  } \"";
//		$sDefaultPlaceHolder = Dict::S("UI:YourSearch");

		if ($this->IsPrintableVersion()) {
			$sHtml .= ' <!-- Beginning of page content -->';
			$sHtml .= utils::FilterXSS($this->s_content);
			$sHtml .= ' <!-- End of page content -->';
		} elseif ($this->GetOutputFormat() == 'html') {

			// Add the captured output
			if (trim($s_captured_output) != "") {
				$sHtml .= "<div id=\"rawOutput\" title=\"Debug Output\"><div style=\"height:500px; overflow-y:auto;\">".utils::FilterXSS($s_captured_output)."</div></div>\n";
			}
//			$sHtml .= "<div id=\"at_the_end\">".utils::FilterXSS($this->s_deferred_content)."</div>";
//			$sHtml .= "<div style=\"display:none\" title=\"ex2\" id=\"ex2\">Please wait...</div>\n"; // jqModal Window
//			$sHtml .= "<div style=\"display:none\" title=\"dialog\" id=\"ModalDlg\"></div>";
//			$sHtml .= "<div style=\"display:none\" id=\"ajax_content\"></div>";
		} else {
			$sHtml .= utils::FilterXSS($this->s_content);
		}

		if ($this->IsPrintableVersion())
		{
			$sHtml .= '</div>';
		}


		if ($this->GetOutputFormat() == 'html')
		{
//			$oKpi = new ExecutionKPI();
//			echo $sHtml;
//			$oKpi->ComputeAndReport('Echoing ('.round(strlen($sHtml) / 1024).' Kb)');
		}
		else
		{
			// TODO 3.0.0: Check with ITOMIG if we can remove this
			if ($this->GetOutputFormat() == 'pdf' && $this->IsOutputFormatAvailable('pdf'))
			{
				// Note: Apparently this was a demand from ITOMIG a while back, so it's not "dead code" per say.
				// The last trace we got is in R-007989. Do not remove this without checking before with the concerned parties if it is still used!
				if (@is_readable(APPROOT.'lib/MPDF/mpdf.php'))
				{
					require_once(APPROOT.'lib/MPDF/mpdf.php');
					/** @noinspection PhpUndefinedClassInspection Check above comment */
					$oMPDF = new mPDF('c');
					$oMPDF->mirroMargins = false;
					if ($this->a_base['href'] != '')
					{
						$oMPDF->setBasePath($this->a_base['href']); // Seems that the <BASE> tag is not recognized by mPDF...
					}
					$oMPDF->showWatermarkText = true;
					if ($this->GetOutputOption('pdf', 'template_path'))
					{
						$oMPDF->setImportUse(); // Allow templates
						$oMPDF->SetDocTemplate($this->GetOutputOption('pdf', 'template_path'), 1);
					}
					$oMPDF->WriteHTML($sHtml);
					$sOutputName = $this->s_title.'.pdf';
					if ($this->GetOutputOption('pdf', 'output_name'))
					{
						$sOutputName = $this->GetOutputOption('pdf', 'output_name');
					}
					$oMPDF->Output($sOutputName, 'I');
				}
			}
		}
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function AddTabContainer($sTabContainer, $sPrefix = '', iUIContentBlock $oParentBlock = null)
	{
		if(is_null($oParentBlock)) {
			$oParentBlock = PanelFactory::MakeNeutral('');
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
	 */
	public function SetCurrentTab($sTabCode = '', $sTabTitle = null)
	{
		return $this->m_oTabs->SetCurrentTab($sTabCode, $sTabTitle);
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 * @since 2.0.3
	 */
	public function AddAjaxTab($sTabCode, $sUrl, $bCache = true, $sTabTitle = null)
	{
		$this->add($this->m_oTabs->AddAjaxTab($sTabCode, $sUrl, $bCache, $sTabTitle));
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
		$this->add_ready_script($this->m_oTabs->SelectTab($sTabContainer, $sTabCode));
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function add($sHtml): ?iUIBlock
	{
		if (($this->m_oTabs->GetCurrentTabContainer() != '') && ($this->m_oTabs->GetCurrentTab() != '')) {
			$this->m_oTabs->AddToCurrentTab($sHtml);
		} else {
			return parent::add($sHtml);
		}
		return null;
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
	 * @throws \Exception
	 * @since 2.6.0
	 */
	public function AddHeaderMessage($sContent, $sCssClasses = 'message_info')
	{
		$this->add(<<<EOF
<div class="header_message $sCssClasses">$sContent</div>
EOF
		);
	}

	/**
	 * Adds a script to be executed when the DOM is ready (typical JQuery use), right before add_ready_script
	 *
	 * @param string $sScript
	 *
	 * @return void
	 */
	public function add_init_script($sScript)
	{
		if (!empty(trim($sScript))) {
			$this->m_aInitScript[] = $sScript;
		}
	}

	/**
	 * @return TopBar
	 */
	public function GetTopBarLayout(): TopBar
	{
		return $this->oTopBarLayout;
	}

	/**
	 * @param TopBar $oTopBarLayout
	 *
	 * @return iTopWebPage
	 */
	public function SetTopBarLayout(TopBar $oTopBarLayout): iTopWebPage
	{
		$this->oTopBarLayout = $oTopBarLayout;
		return $this;
	}


}
