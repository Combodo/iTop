<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\Helper\CKEditorHelper;
use Combodo\iTop\Application\Helper\FormHelper;
use Combodo\iTop\Application\Helper\Session;
use Combodo\iTop\Application\Helper\WebResourcesHelper;
use Combodo\iTop\Application\Search\SearchForm;
use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\Button;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroupUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSection;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableSettings;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\StaticTable\StaticTable;
use Combodo\iTop\Application\UI\Base\Component\Field\Field;
use Combodo\iTop\Application\UI\Base\Component\Field\FieldUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSet;
use Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSetUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Form\Form;
use Combodo\iTop\Application\UI\Base\Component\Form\FormUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Html\HtmlFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOptionUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\JsPopoverMenuItem;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanel;
use Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column\Column;
use Combodo\iTop\Application\UI\Base\Layout\MultiColumn\MultiColumn;
use Combodo\iTop\Application\UI\Base\Layout\Object\ObjectFactory;
use Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\AjaxTab;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;
use Combodo\iTop\Application\UI\Links\Direct\BlockDirectLinkSetViewTable;
use Combodo\iTop\Application\UI\Links\Indirect\BlockIndirectLinkSetViewTable;
use Combodo\iTop\Application\UI\Links\Set\LinkSetUIBlockFactory;
use Combodo\iTop\Application\WebPage\AjaxPage;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Renderer\BlockRenderer;
use Combodo\iTop\Renderer\Console\ConsoleBlockRenderer;
use Combodo\iTop\Renderer\Console\ConsoleFormRenderer;
use Combodo\iTop\Service\Links\LinkSetDataTransformer;
use Combodo\iTop\Service\Links\LinkSetModel;
use Combodo\iTop\Service\TemporaryObjects\TemporaryObjectHelper;


define('OBJECT_PROPERTIES_TAB', 'ObjectProperties');

define('HILIGHT_CLASS_CRITICAL', 'red');
define('HILIGHT_CLASS_WARNING', 'orange');
define('HILIGHT_CLASS_OK', 'green');
define('HILIGHT_CLASS_NONE', '');

define('MIN_WATCHDOG_INTERVAL', 15); // Minimum interval for the watchdog: 15s

require_once(APPROOT.'core/cmdbobject.class.inc.php');
require_once(APPROOT.'application/applicationextension.inc.php');
require_once(APPROOT.'application/utils.inc.php');
require_once(APPROOT.'application/applicationcontext.class.inc.php');
require_once(APPROOT.'application/ui.linkswidget.class.inc.php');
require_once(APPROOT.'application/ui.linksdirectwidget.class.inc.php');
require_once(APPROOT.'application/ui.passwordwidget.class.inc.php');
require_once(APPROOT.'application/ui.extkeywidget.class.inc.php');
require_once(APPROOT.'application/ui.htmleditorwidget.class.inc.php');
require_once(APPROOT.'sources/Application/Search/searchform.class.inc.php');
require_once(APPROOT.'sources/Application/Search/criterionparser.class.inc.php');
require_once(APPROOT.'sources/Application/Search/criterionconversionabstract.class.inc.php');
require_once(APPROOT.'sources/Application/Search/CriterionConversion/criteriontooql.class.inc.php');
require_once(APPROOT.'sources/Application/Search/CriterionConversion/criteriontosearchform.class.inc.php');

/**
 * Class cmdbAbstractObject
 */
abstract class cmdbAbstractObject extends CMDBObject implements iDisplay
{
	/**
	 * @var string
	 * @see static::$sDisplayMode
	 * @since 3.0.0
	 */
	public const ENUM_DISPLAY_MODE_VIEW = 'view';
	/**
	 * @var string
	 * @see static::$sDisplayMode
	 * @since 3.0.0
	 */
	public const ENUM_DISPLAY_MODE_EDIT = 'edit';
	/**
	 * @var string
	 * @see static::$sDisplayMode
	 * @since 3.0.0
	 */
	public const ENUM_DISPLAY_MODE_CREATE = 'create';
	/**
	 * @var string
	 * @see static::$sDisplayMode
	 * @since 3.0.0
	 */
	public const ENUM_DISPLAY_MODE_STIMULUS = 'stimulus';
	/**
	 * @var string
	 * @see static::$sDisplayMode
	 * @since 3.0.0
	 */
	public const ENUM_DISPLAY_MODE_PRINT = 'print';
	/**
	 * @var string
	 * @see static::$sDisplayMode
	 * @since 3.0.0
	 */
	public const ENUM_DISPLAY_MODE_BULK_EDIT = self::ENUM_DISPLAY_MODE_EDIT;

	// N°3750 rendering used
	/** @var string */
	public const ENUM_INPUT_TYPE_SINGLE_INPUT = 'single_input';
	/** @var string */
	public const ENUM_INPUT_TYPE_MULTIPLE_INPUTS = 'multiple_inputs';
	/** @var string */
	public const ENUM_INPUT_TYPE_TEXTAREA = 'textarea';
	/** @var string */
	public const ENUM_INPUT_TYPE_HTML_EDITOR = 'html_editor';
	/** @var string */
	public const ENUM_INPUT_TYPE_DOCUMENT = 'document';
	/** @var string */
	public const ENUM_INPUT_TYPE_IMAGE = 'image';
	/** @var string */
	public const ENUM_INPUT_TYPE_PASSWORD = 'password';
	/** @var string */
	public const ENUM_INPUT_TYPE_TAGSET = 'tagset';
	/** @var string */
	public const ENUM_INPUT_TYPE_TAGSET_LINKEDSET = 'tagset_linkedset';
	/** @var string */
	public const ENUM_INPUT_TYPE_RADIO = 'radio';
	/** @var string */
	public const ENUM_INPUT_TYPE_CHECKBOX = 'checkbox';
	/** @var string */
	public const ENUM_INPUT_TYPE_DROPDOWN_RAW = 'dropdown_raw';
	/** @var string */
	public const ENUM_INPUT_TYPE_DROPDOWN_DECORATED = 'dropdown_decorated'; // now with the JQuery Selectize plugin
	/** @var string */
	public const ENUM_INPUT_TYPE_DROPDOWN_MULTIPLE_CHOICES = 'dropdown_multiple_choices';
	/** @var string */
	public const ENUM_INPUT_TYPE_AUTOCOMPLETE = 'autocomplete';
	/** @var string */
	public const ENUM_INPUT_TYPE_LINKEDSET = 'linkedset';

	/**
	 * @var string DEFAULT_DISPLAY_MODE
	 * @see static::$sDisplayMode
	 * @since 3.0.0
	 */
	public const DEFAULT_DISPLAY_MODE = self::ENUM_DISPLAY_MODE_VIEW;

	/**
	 * @var string Prefix for tags in the subtitle, allows to identify them more easily
	 * @used-by static::DisplayBareHeader
	 * @since 3.0.0
	 */
	public const HEADER_BLOCKS_SUBTITLE_TAG_PREFIX = 'tag-';

	protected $m_iFormId; // The ID of the form used to edit the object (when in edition mode !)
	protected static $iGlobalFormId = 1;
	/**
	 * @var string Mode in which the object is displayed {@see static::ENUM_DISPLAY_MODE_VIEW}, ...)
	 * @since 3.0.0
	 */
	protected $sDisplayMode;
	protected $aFieldsMap;

	/**
	 * If true, bypass IsActionAllowedOnAttribute when writing this object
	 *
	 * @var bool
	 */
	protected $bAllowWrite;
	/**
	 * @var bool
	 */
	protected $bAllowDelete;


	/** @var array attributes flags cache [target_state][attcode]['flags'] */
	protected $aAttributesFlags;
	/** @var array initial attributes flags cache [attcode]['flags'] */
	protected $aInitialAttributesFlags;


	/**
	 * @var array First level classname, second level id, value number of calls done
	 * @used-by static::RegisterObjectAwaitingEventDbLinksChanged()
	 * @used-by static::RemoveObjectAwaitingEventDbLinksChanged()
	 *
	 */
	protected static array $aObjectsAwaitingEventDbLinksChanged = [];

	/**
	 * @var bool Flag to allow/block the Event when DBLink are changed
	 * This is used to avoid sending too many events when doing mass-update
	 *
	 * When this flag is set to true, the object registration for links modification is done
	 * but the event is not fired.
	 *
	 * @since 3.1.0 N°5906
	 */
	protected static bool $bBlockEventDBLinksChanged = false;


	/**
	 * Constructor from a row of data (as a hash 'attcode' => value)
	 *
	 * @param array $aRow
	 * @param string $sClassAlias
	 * @param array $aAttToLoad
	 * @param array $aExtendedDataSpec
	 *
	 * @throws \CoreException
	 */
	public function __construct($aRow = null, $sClassAlias = '', $aAttToLoad = null, $aExtendedDataSpec = null)
	{
		parent::__construct($aRow, $sClassAlias, $aAttToLoad, $aExtendedDataSpec);
		$this->sDisplayMode = static::DEFAULT_DISPLAY_MODE;
		$this->bAllowWrite = false;
		$this->bAllowDelete = false;
	}

	/**
	 * Return the allowed display modes
	 *
	 * @see static::ENUM_DISPLAY_MODE_XXX
	 *
	 * @return string[]
	 * @since 3.0.0
	 */
	public static function EnumDisplayModes(): array
	{
		return [
			static::ENUM_DISPLAY_MODE_VIEW,
			static::ENUM_DISPLAY_MODE_EDIT,
			static::ENUM_DISPLAY_MODE_CREATE,
			static::ENUM_DISPLAY_MODE_STIMULUS,
			static::ENUM_DISPLAY_MODE_PRINT,
			static::ENUM_DISPLAY_MODE_BULK_EDIT,
		];
	}

	/**
	 * @see static::$sDisplayMode
	 * @return string
	 * @since 3.0.0
	 */
	public function GetDisplayMode(): string
	{
		return $this->sDisplayMode;
	}

	/**
	 * @param string $sMode
	 *
	 * @see static::$sDisplayMode
	 * @return $this
	 * @since 3.0.0
	 */
	public function SetDisplayMode(string $sMode)
	{
		$this->sDisplayMode = $sMode;

		return $this;
	}

	/**
	 * returns what will be the next ID for the forms
	 */
	public static function GetNextFormId()
	{
		return 1 + self::$iGlobalFormId;
	}

	public static function GetUIPage()
	{
		return 'UI.php';
	}

	/**
	 * @param WebPage $oPage
	 * @param \cmdbAbstractObject $oObj
	 * @param array $aParams
	 *
	 * @throws \Exception
	 */
	public static function ReloadAndDisplay($oPage, $oObj, $aParams)
	{
		$oAppContext = new ApplicationContext();
		// Reload the page to let the "calling" page execute its 'onunload' method.
		// Note 1: The redirection MUST NOT be made via an HTTP "header" since onunload is only called when the actual content of the DOM
		// is replaced by some other content. So the "bouncing" page must provide some content (in our case a script making the redirection).
		// Note 2: make sure that the URL below is different from the one of the "Modify" button, otherwise the button will have no effect. This is why we add "&a=1" at the end !!!
		// Note 3: we use the toggle of a flag in the sessionStorage object to prevent an infinite loop of reloads in case the object is actually locked by another window
		$sSessionStorageKey = get_class($oObj).'_'.$oObj->GetKey();
		$sParams = '';
		foreach($aParams as $sName => $value)
		{
			$sParams .= $sName.'='.urlencode($value).'&'; // Always add a trailing &
		}
		$sUrl = utils::GetAbsoluteUrlAppRoot().'pages/'.$oObj->GetUIPage().'?'.$sParams.'class='.get_class($oObj).'&id='.$oObj->getKey().'&'.$oAppContext->GetForLink().'&a=1';
		$oPage->add_early_script(<<<JS
	if (!sessionStorage.getItem('$sSessionStorageKey'))
	{
		sessionStorage.setItem('$sSessionStorageKey', 1);
		window.location.href= "$sUrl";
	}
	else
	{
		sessionStorage.removeItem('$sSessionStorageKey');
	}
JS
		);

		$oObj->Reload();
		$oObj->SetDisplayMode(static::ENUM_DISPLAY_MODE_VIEW);
		$oObj->DisplayDetails($oPage, false);
	}

	/**
	 * @param $sMessageId
	 * @param $sMessage
	 * @param $sSeverity
	 * @param $fRank
	 * @param bool $bMustNotExist
	 *
	 * @see SetSessionMessage()
	 * @since 2.6.0
	 */
	protected function SetSessionMessageFromInstance($sMessageId, $sMessage, $sSeverity, $fRank, $bMustNotExist = false)
	{
		$sObjectClass = get_class($this);
		$iObjectId = $this->GetKey();

		self::SetSessionMessage($sObjectClass, $iObjectId, $sMessageId, $sMessage, $sSeverity, $fRank);
	}

	/**
	 * Set a message displayed to the end-user next time this object will be displayed
	 * Messages are uniquely identified so that plugins can override standard messages (the final work is given to the
	 * last plugin to set the message for a given message id) In practice, standard messages are recorded at the end
	 * but they will not overwrite existing messages
	 *
	 * @see SetSessionMessageFromInstance() to call from within an instance
	 *
	 * @param string $sClass The class of the object (must be the final class)
	 * @param int $iKey The identifier of the object
	 * @param string $sMessageId Your id or one of the well-known ids: 'create', 'update' and 'apply_stimulus'
	 * @param string $sMessage The HTML message (must be correctly escaped)
	 * @param string $sSeverity Any of the WebPage::ENUM_SESSION_MESSAGE_SEVERITY_XXX constants
	 * @param float $fRank Ordering of the message: smallest displayed first (can be negative)
	 * @param bool $bMustNotExist Do not alter any existing message (considering the id)
	 *
	 * @return void
	 */
	public static function SetSessionMessage($sClass, $iKey, $sMessageId, $sMessage, $sSeverity, $fRank, $bMustNotExist = false)
	{
		$sMessageKey = $sClass.'::'.$iKey;
		if (!Session::IsSet(['obj_messages', $sMessageKey])) {
			Session::Set(['obj_messages', $sMessageKey], []);
		}
		if (!$bMustNotExist || !Session::IsSet(['obj_messages', $sMessageKey, $sMessageId])) {
			Session::Set(['obj_messages', $sMessageKey, $sMessageId], [
				'rank' => $fRank,
				'severity' => $sSeverity,
				'message' => $sMessage,
			]);
		}
	}

	/**
	 * @param WebPage $oPage Warning, since 3.0.0 this parameter was kept for compatibility reason. You shouldn't write directly on the page!
	 *   When writing to the page, markup will be put above the real header of the panel.
	 *   To insert something IN the panel, we now need to add UIBlocks in either the "subtitle" or "toolbar" sections of the array that will be returned.
	 * @param bool $bEditMode Deprecated parameter in iTop 3.0.0, use {@see GetDisplayMode()} and ENUM_DISPLAY_MODE_* constants instead
	 *
	 * @return array{
	 *       subtitle: \Combodo\iTop\Application\UI\Base\UIBlock[],
	 *       toolbar: \Combodo\iTop\Application\UI\Base\UIBlock[]
	 *    }
	 *    blocks to be inserted in the "subtitle" and the "toolbar" sections of the ObjectDetails block.
	 *    eg. ['subtitle' => [<BLOCK1>, <BLOCK2>], 'toolbar' => [<BLOCK3>]]
	 *
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 *
	 * @since 3.0.0 $bEditMode is deprecated, see param documentation above
	 * @since 3.0.0 Changed signature: Method must return header content in an array (no more writing directly to the $oPage)
	 *
	 * @noinspection PhpUnusedParameterInspection
	 */
	public function DisplayBareHeader(WebPage $oPage, $bEditMode = false)
	{
		$aHeaderBlocks = [
			'subtitle' => [],
			'toolbar' => [],
		];

		// Standard Header with name, actions menu and history block

		if (!$oPage->IsPrintableVersion()) {
			// Is there a message for this object ??
			$aMessages = [];
			$aRanks = [];
			if (MetaModel::GetConfig()->Get('concurrent_lock_enabled')) {
				$aLockInfo = iTopOwnershipLock::IsLocked(get_class($this), $this->GetKey());
				if ($aLockInfo['locked']) {
					$aRanks[] = 0;
					$sName = $aLockInfo['owner']->GetName();
					if ($aLockInfo['owner']->Get('contactid') != 0) {
						$sName .= ' ('.$aLockInfo['owner']->Get('contactid_friendlyname').')';
					}
					$aMessages[] = AlertUIBlockFactory::MakeForDanger('', Dict::Format('UI:CurrentObjectIsLockedBy_User', $sName));
				}
			}
			$sMessageKey = get_class($this).'::'.$this->GetKey();
			$oPage->AddSessionMessages($sMessageKey, $aRanks, $aMessages);
		}

		if (!$oPage->IsPrintableVersion() && ($this->GetDisplayMode() === static::ENUM_DISPLAY_MODE_VIEW)) {
			// action menu
			$oSingletonFilter = new DBObjectSearch(get_class($this));
			$oSingletonFilter->AddCondition('id', $this->GetKey(), '=');
			$oBlock = new MenuBlock($oSingletonFilter, 'details', false);
			$sActionMenuId = utils::Sanitize(uniqid('', true), '', utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER);
			$oActionMenuBlock = $oBlock->GetRenderContent($oPage, [], $sActionMenuId);
			$aHeaderBlocks['toolbar'][$oActionMenuBlock->GetId()] = $oActionMenuBlock;
		}

		$aTags = array();

		// Master data sources
		if (!$oPage->IsPrintableVersion()) {
			$oCreatorTask = null;
			$bCanBeDeletedByTask = false;
			$bCanBeDeletedByUser = true;
			$aMasterSources = array();
			$aSyncData = $this->GetSynchroData(MetaModel::GetConfig()->Get('synchro_obsolete_replica_locks_object'));
			if (count($aSyncData) > 0) {
				foreach ($aSyncData as $iSourceId => $aSourceData) {
					$oDataSource = $aSourceData['source'];
					$oReplica = reset($aSourceData['replica']); // Take the first one!

					$sApplicationURL = $oDataSource->GetApplicationUrl($this, $oReplica);
					$sLink = $oDataSource->GetName();
					if (!empty($sApplicationURL))
					{
						$sLink = "<a href=\"$sApplicationURL\" target=\"_blank\">".$oDataSource->GetName()."</a>";
					}
					if ($oReplica->Get('status_dest_creator') == 1)
					{
						$oCreatorTask = $oDataSource;
						$bCreatedByTask = true;
					}
					else
					{
						$bCreatedByTask = false;
					}
					if ($bCreatedByTask)
					{
						$sDeletePolicy = $oDataSource->Get('delete_policy');
						if (($sDeletePolicy == 'delete') || ($sDeletePolicy == 'update_then_delete'))
						{
							$bCanBeDeletedByTask = true;
						}
						$sUserDeletePolicy = $oDataSource->Get('user_delete_policy');
						if ($sUserDeletePolicy == 'nobody')
						{
							$bCanBeDeletedByUser = false;
						}
						elseif (($sUserDeletePolicy == 'administrators') && !UserRights::IsAdministrator())
						{
							$bCanBeDeletedByUser = false;
						}
					}
					$aMasterSources[$iSourceId]['datasource'] = $oDataSource;
					$aMasterSources[$iSourceId]['url'] = $sLink;
					$aMasterSources[$iSourceId]['last_synchro'] = $oReplica->Get('status_last_seen');
				}

				if (is_object($oCreatorTask))
				{
					$sTaskUrl = $aMasterSources[$oCreatorTask->GetKey()]['url'];
					if (!$bCanBeDeletedByUser)
					{
						$sTip = "<div>".Dict::Format('Core:Synchro:TheObjectCannotBeDeletedByUser_Source',
								$sTaskUrl)."</div>";
					}
					else
					{
						$sTip = "<div>".Dict::Format('Core:Synchro:TheObjectWasCreatedBy_Source', $sTaskUrl)."</div>";
					}
					if ($bCanBeDeletedByTask)
					{
						$sTip .= "<div>".Dict::Format('Core:Synchro:TheObjectCanBeDeletedBy_Source', $sTaskUrl)."</div>";
					}
				}
				else
				{
					$sTip = "<div>".Dict::S('Core:Synchro:ThisObjectIsSynchronized')."</div>";
				}

				$sTip .= "<div><b>".Dict::S('Core:Synchro:ListOfDataSources')."</b></div>";
				foreach($aMasterSources as $aStruct)
				{
					// Formatting last synchro date
					$oDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $aStruct['last_synchro']);
					$oDateTimeFormat = AttributeDateTime::GetFormat();
					$sLastSynchro = $oDateTimeFormat->Format($oDateTime);

					$oDataSource = $aStruct['datasource'];
					$sLink = $aStruct['url'];
					$sTip .= "<div>".$oDataSource->GetIcon(true, '')."$sLink</div>";
					$sTip .= Dict::S('Core:Synchro:LastSynchro').'<div>'.$sLastSynchro."</div>";
				}
				$sLabel = Dict::S('Tag:Synchronized');
				$sSynchroTagId = 'synchro_icon-'.$this->GetKey();
				$aTags[$sSynchroTagId] = ['title' => $sTip, 'css_classes' => 'ibo-object-details--tag--synchronized', 'decoration_classes' => 'fas fa-lock', 'label' => $sLabel];
			}
		}

		if ($this->IsArchived()) {
			$sLabel = Dict::S('Tag:Archived');
			$sTitle = Dict::S('Tag:Archived+');
			$aTags['archived'] = ['title' => $sTitle, 'css_classes' => 'ibo-object-details--tag--archived', 'decoration_classes' => 'fas fa-archive', 'label' => $sLabel];
		} elseif ($this->IsObsolete()) {
			$sLabel = Dict::S('Tag:Obsolete');
			$sTitle = Dict::S('Tag:Obsolete+');
			$aTags['obsolete'] = ['title' => $sTitle, 'css_classes' => 'ibo-object-details--tag--obsolete', 'decoration_classes' => 'fas fa-eye-slash', 'label' => $sLabel];
		}

		foreach ($aTags as $sIconId => $aIconData) {
			$sTagTooltipContent = utils::EscapeHtml($aIconData['title']);
			$aHeaderBlocks['subtitle'][static::HEADER_BLOCKS_SUBTITLE_TAG_PREFIX.$sIconId] = new Html(<<<HTML
<span id="{$sIconId}" class="ibo-object-details--tag {$aIconData['css_classes']}" data-tooltip-content="{$sTagTooltipContent}" data-tooltip-html-enabled="true"><span class="ibo-object-details--tag-icon"><span class="{$aIconData['decoration_classes']}"></span></span>{$aIconData['label']}</span>
HTML
			);
		}

		return $aHeaderBlocks;
	}

	/**
	 * Display properties tab of an object
	 *
	 * @param WebPage $oPage
	 * @param bool $bEditMode Note that this parameter is no longer used in this method. Use {@see static::$sDisplayMode} instead
	 * @param string $sPrefix
	 * @param array $aExtraParams
	 *
	 * @return array
	 * @throws \CoreException
	 *
	 * @since 3.0.0 $bEditMode is deprecated and no longer used
	 */
	public function DisplayBareProperties(WebPage $oPage, $bEditMode = false, $sPrefix = '', $aExtraParams = array())
	{
		$aFieldsMap = $this->GetBareProperties($oPage, $bEditMode, $sPrefix, $aExtraParams);


		if (!isset($aExtraParams['disable_plugins']) || !$aExtraParams['disable_plugins'])
		{
			/** @var iApplicationUIExtension $oExtensionInstance */
			foreach (MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
			{
				$oExtensionInstance->OnDisplayProperties($this, $oPage, $bEditMode);
			}
		}

		return $aFieldsMap;
	}

	/**
	 * Add a field to the map: attcode => id used when building a form
	 *
	 * @param string $sAttCode The attribute code of the field being edited
	 * @param string $sInputId The unique ID of the control/widget in the page
	 */
	protected function AddToFieldsMap($sAttCode, $sInputId)
	{
		$this->aFieldsMap[$sAttCode] = $sInputId;
	}

	/**
	 * @param WebPage $oPage
	 * @param $sAttCode
	 *
	 * @throws \Exception
	 */
	public function DisplayDashboard($oPage, $sAttCode)
	{
		// Retrieve parameters
		/** @var bool $bIsContainerInEdition True if the container of the dashboard is currently in edition; meaning that the dashboard could not be up-to-date with data changed in the container (eg. when editing an object and adding linkedset items) */
		$bIsContainerInEdition = (utils::ReadParam('host_container_in_edition', 'false') === 'true');

		$sClass = get_class($this);
		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);

		// Consistency checks
		if (!$oAttDef instanceof AttributeDashboard) {
			throw new CoreException(Dict::S('UI:Error:InvalidDashboard'));
		}

		// Load the dashboard
		$oDashboard = $oAttDef->GetDashboard();
		if (is_null($oDashboard)) {
			throw new CoreException(Dict::S('UI:Error:InvalidDashboard'));
		}

		$bCanEdit = UserRights::IsAdministrator() || $oAttDef->IsUserEditable();
		$sDivId = $oDashboard->GetId();

		if ($bIsContainerInEdition) {
			$oPage->AddUiBlock(AlertUIBlockFactory::MakeForInformation(Dict::S('UI:Dashboard:NotUpToDateUntilContainerSaved')));
		}
		$oPage->add('<div id="'.$sDivId.'" class="ibo-dashboard" data-role="ibo-dashboard">');
		$aExtraParams = array(
			'query_params' => $this->ToArgsForQuery(),
			'dashboard_div_id' => $sDivId,
		);
		$oDashboard->Render($oPage, false, $aExtraParams, $bCanEdit);
		$oPage->add('</div>');
	}

	/**
	 * @param WebPage $oPage
	 * @param bool $bEditMode Note that this parameter is no longer used in this method. Use {@see static::$sDisplayMode} instead
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \Exception
	 *
	 * @since 3.0.0 $bEditMode is deprecated and no longer used
	 */
	public function DisplayBareRelations(WebPage $oPage, $bEditMode = false)
	{
		$aRedundancySettings = $this->FindVisibleRedundancySettings();

		// Related objects: display all the linkset attributes, each as a separate tab
		// In the order described by the 'display' ZList
		$aList = $this->FlattenZList(MetaModel::GetZListItems(get_class($this), 'details'));
		if (count($aList) == 0)
		{
			// Empty ZList defined, display all the linkedset attributes defined
			$aList = array_keys(MetaModel::ListAttributeDefs(get_class($this)));
		}
		$sClass = get_class($this);
		foreach($aList as $sAttCode) {
			$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
			if ($oAttDef instanceof AttributeDashboard) {
				if (!$this->IsNew()) {
					$sHostContainerInEditionUrlParam = ($bEditMode) ? '&host_container_in_edition=true' : '';
					$oPage->AddAjaxTab(
						'Class:'.$sClass.'/Attribute:'.$sAttCode,
						utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php?operation=dashboard&class='
							.get_class($this)
							.'&id='.$this->GetKey()
							.'&attcode='.$oAttDef->GetCode()
							.$sHostContainerInEditionUrlParam,
						true,
						$oAttDef->GetLabel(),
						AjaxTab::ENUM_TAB_PLACEHOLDER_DASHBOARD
					);
					// Add graphs dependencies
					WebResourcesHelper::EnableC3JSToWebPage($oPage);
				}
				continue;
			}

			// Process only link set attributes with tab display style
			$bIsLinkSetWithDisplayStyleTab = is_a($oAttDef, AttributeLinkedSet::class) && $oAttDef->GetDisplayStyle() === LINKSET_DISPLAY_STYLE_TAB;
			if (!$oAttDef->IsLinkset() || !$bIsLinkSetWithDisplayStyleTab) {
				continue;
			}

			$sLinkedClass = $oAttDef->GetLinkedClass();

			// Filter out links pointing to obsolete objects (if relevant)
			$oOrmLinkSet = $this->Get($sAttCode);
			$oLinkSet = $oOrmLinkSet->ToDBObjectSet(utils::ShowObsoleteData());

			$iCount = $oLinkSet->Count();
			if ($this->IsNew()) {
				$iFlags = $this->GetInitialStateAttributeFlags($sAttCode);
			} else {
				$iFlags = $this->GetAttributeFlags($sAttCode);
			}
			// Adjust the flags according to user rights
			if ($oAttDef->IsIndirect())
			{
				$oLinkingAttDef = MetaModel::GetAttributeDef($sLinkedClass, $oAttDef->GetExtKeyToRemote());
				$sTargetClass = $oLinkingAttDef->GetTargetClass();
				// n:n links => must be allowed to modify the linking class AND  read the target class in order to edit the linkedset
				if (!UserRights::IsActionAllowed($sLinkedClass,
						UR_ACTION_MODIFY) || !UserRights::IsActionAllowed($sTargetClass, UR_ACTION_READ))
				{
					$iFlags |= OPT_ATT_READONLY;
				}
				// n:n links => must be allowed to read the linking class AND  the target class in order to display the linkedset
				if (!UserRights::IsActionAllowed($sLinkedClass,
						UR_ACTION_READ) || !UserRights::IsActionAllowed($sTargetClass, UR_ACTION_READ))
				{
					$iFlags |= OPT_ATT_HIDDEN;
				}
			}
			else
			{
				// 1:n links => must be allowed to modify the linked class in order to edit the linkedset
				if (!UserRights::IsActionAllowed($sLinkedClass, UR_ACTION_MODIFY))
				{
					$iFlags |= OPT_ATT_READONLY;
				}
				// 1:n links => must be allowed to read the linked class in order to display the linkedset
				if (!UserRights::IsActionAllowed($sLinkedClass, UR_ACTION_READ))
				{
					$iFlags |= OPT_ATT_HIDDEN;
				}
			}
			// Non-readable/hidden linkedset... don't display anything
			if ($iFlags & OPT_ATT_HIDDEN)
			{
				continue;
			}

			$sTabCode = 'Class:'.$sClass.'/Attribute:'.$sAttCode;
			$sTabDescription = utils::IsNotNullOrEmptyString($oAttDef->GetDescription()) ? $oAttDef->GetDescription() : null;
			$sCount = ($iCount != 0) ? " ($iCount)" : "";
			$oPage->SetCurrentTab($sTabCode, $oAttDef->GetLabel().$sCount, $sTabDescription);

			$aArgs = array('this' => $this);
			
			$sEditWhen = $oAttDef->GetEditWhen();
			// Calculate if edit_when allows to edit based on current $bEditMode
			$bIsEditableBasedOnEditWhen =  ($sEditWhen === LINKSET_EDITWHEN_ALWAYS) || 
				($bEditMode ? $sEditWhen === LINKSET_EDITWHEN_ON_HOST_EDITION : $sEditWhen === LINKSET_EDITWHEN_ON_HOST_DISPLAY);

			$bReadOnly = ($iFlags & (OPT_ATT_READONLY | OPT_ATT_SLAVE)) || !$bIsEditableBasedOnEditWhen;
			if ($bEditMode && (!$bReadOnly)) {
				$sInputId = $this->m_iFormId.'_'.$sAttCode;
				$sDisplayValue = ''; // not used
				$sHTMLValue = "<span id=\"field_{$sInputId}\">".self::GetFormElementForField($oPage, $sClass, $sAttCode,
						$oAttDef, $oLinkSet, $sDisplayValue, $sInputId, '', $iFlags, $aArgs).'</span>';
				$this->AddToFieldsMap($sAttCode, $sInputId);
				$oPage->add($sHTMLValue);
			} else {
				if ($oAttDef->IsIndirect()) {
					$oBlockLinkSetViewTable = new BlockIndirectLinkSetViewTable($oPage, $this, $sClass, $sAttCode, $oAttDef, $bReadOnly);
				} else {
					$oBlockLinkSetViewTable = new BlockDirectLinkSetViewTable($oPage, $this, $sClass, $sAttCode, $oAttDef, $bReadOnly);
				}
				$oPage->AddUiBlock($oBlockLinkSetViewTable);
			}
			if (array_key_exists($sAttCode, $aRedundancySettings)) {
				foreach ($aRedundancySettings[$sAttCode] as $oRedundancyAttDef) {
					$sRedundancyAttCode = $oRedundancyAttDef->GetCode();
					$sValue = $this->Get($sRedundancyAttCode);
					$iRedundancyFlags = $this->GetFormAttributeFlags($sRedundancyAttCode);
					$bRedundancyReadOnly = ($iRedundancyFlags & (OPT_ATT_READONLY | OPT_ATT_SLAVE));

					$oFieldSet = FieldSetUIBlockFactory::MakeStandard($oRedundancyAttDef->GetLabel());
					$oFieldSet->AddCSSClass('mt-5');
					$oPage->AddSubBlock($oFieldSet);

					if ($bEditMode && (!$bRedundancyReadOnly)) {
						$sInputId = $this->m_iFormId.'_'.$sRedundancyAttCode;
						$oFieldSet->AddSubBlock(new Html("<span id=\"field_{$sInputId}\">".self::GetFormElementForField($oPage, $sClass,
								$sRedundancyAttCode, $oRedundancyAttDef, $sValue, '', $sInputId, '', $iFlags,
								$aArgs).'</span>'));
					} else {
						$oFieldSet->AddSubBlock(new Html($oRedundancyAttDef->GetDisplayForm($sValue, $oPage, false, $this->m_iFormId)));
					}
				}
			}
		}

		/** @var \iApplicationUIExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance) {
			$oExtensionInstance->OnDisplayRelations($this, $oPage, $bEditMode);
		}

		$oPage->SetCurrentTab('');

		if (!$this->IsNew()) {
			// Look for any trigger that considers this object as "In Scope"
			// If any trigger has been found then display a tab with notifications
			//
			$aTriggers = $this->GetRelatedTriggersIDs();
			if (count($aTriggers) > 0) {
				$iId = $this->GetKey();
				$aParams = array('triggers' => $aTriggers, 'id' => $iId);
				$aNotifSearches = array();
				$iNotifsCount = 0;
				$aNotificationClasses = MetaModel::EnumChildClasses('EventNotification');
				foreach ($aNotificationClasses as $sNotifClass) {
					$aNotifSearches[$sNotifClass] = DBObjectSearch::FromOQL("SELECT $sNotifClass AS Ev JOIN Trigger AS T ON Ev.trigger_id = T.id WHERE T.id IN (:triggers) AND Ev.object_id = :id");
					$aNotifSearches[$sNotifClass]->SetInternalParams($aParams);
					$oNotifSet = new DBObjectSet($aNotifSearches[$sNotifClass], array());
					$iNotifsCount += $oNotifSet->Count();
				}
				// Display notifications regarding the object: on block per subclass to have the interesting columns
				$sCount = ($iNotifsCount > 0) ? ' ('.$iNotifsCount.')' : '';
				$oPage->SetCurrentTab('UI:NotificationsTab', Dict::S('UI:NotificationsTab').$sCount);

				foreach ($aNotificationClasses as $sNotifClass) {
					$oBlock = new DisplayBlock($aNotifSearches[$sNotifClass], 'list', false);
					$oBlock->Display($oPage, 'notifications_'.$sNotifClass, [
						'menu' => false,
						'panel_title' => MetaModel::GetName($sNotifClass),
						'panel_icon' => MetaModel::GetClassIcon($sNotifClass, false),
					]);
				}
			}
		}

		// add hidden input for linkset transactions
		$oInputHidden = InputUIBlockFactory::MakeForHidden('linkset_transactions_id', utils::GetNewTransactionId(), 'linkset_transactions_id');
		$oPage->AddUiBlock($oInputHidden);
	}

	/**
	 * @return string[] IDs of the triggers that consider this object as "In Scope"
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @since 3.0.0
	 */
	public function GetRelatedTriggersIDs(): array
	{
		$aTriggers = [];
		// Request only "leaf" classes to avoid reloads
		$aTriggerClasses = MetaModel::EnumChildClasses('Trigger');
		foreach ($aTriggerClasses as $sTriggerClass) {
			$oReflectionClass = new ReflectionClass($sTriggerClass);
			if (false === $oReflectionClass->isAbstract()) {
				$oTriggerSet = new CMDBObjectSet(new DBObjectSearch($sTriggerClass));
				while ($oTrigger = $oTriggerSet->Fetch()) {
					if ($oTrigger->IsInScope($this)) {
						$aTriggers[] = $oTrigger->GetKey();
					}
				}
			}
		}

		return $aTriggers;
	}

	/**
	 * @param WebPage $oPage
	 * @param bool $bEditMode Note that this parameter is no longer used in this method. Use {@see static::$sDisplayMode} instead
	 * @param string $sPrefix
	 * @param array $aExtraParams
	 *
	 * @return array
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \Exception
	 *
	 * @since 3.0.0 $bEditMode is deprecated and no longer used
	 */
	public function GetBareProperties(WebPage $oPage, $bEditMode, $sPrefix, $aExtraParams = array())
	{
		$sStateAttCode = MetaModel::GetStateAttributeCode(get_class($this));
		$sClass = get_class($this);
		$aDetailsList = MetaModel::GetZListItems($sClass, 'details');
		$aDetailsStruct = self::ProcessZlist($aDetailsList, array('UI:PropertiesTab' => array()), 'UI:PropertiesTab', 'col1', '');
		$aFieldsMap = array();
		$aFieldsComments = (isset($aExtraParams['fieldsComments'])) ? $aExtraParams['fieldsComments'] : array();
		$aExtraFlags = (isset($aExtraParams['fieldsFlags'])) ? $aExtraParams['fieldsFlags'] : array();

		$bHasFieldsWithRichTextEditor = false;
		foreach ($aDetailsStruct as $sTab => $aCols) {
			ksort($aCols);
			$oPage->SetCurrentTab($sTab);
			$oMultiColumn = new MultiColumn();
			$oPage->AddUiBlock($oMultiColumn);

			foreach ($aCols as $sColIndex => $aFieldsets) {
				$oColumn = new Column();
				$oMultiColumn->AddColumn($oColumn);

				foreach ($aFieldsets as $sFieldsetName => $aFields) {
					if ($sFieldsetName[0] != '_') {
						$oFieldSet = new FieldSet(Dict::S($sFieldsetName));
						$oColumn->AddSubBlock($oFieldSet);
					}
					$aDetails = [];
					foreach ($aFields as $sAttCode) {
						$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);

						// Skip case logs as they will be handled by the activity panel
						if ($oAttDef instanceof AttributeCaseLog) {
							continue;
						}

						if (($oAttDef instanceof AttributeText) && ($oAttDef->GetFormat() === 'html')) {
							$bHasFieldsWithRichTextEditor = true;
						}

						$sAttDefClass = get_class($oAttDef);
						$sAttLabel = MetaModel::GetLabel($sClass, $sAttCode);

						if ($bEditMode) {
							$sComments = isset($aFieldsComments[$sAttCode]) ? $aFieldsComments[$sAttCode] : '';
							$sInfos = '';
							$iFlags = FormHelper::GetAttributeFlagsForObject($this, $sAttCode, $aExtraFlags);
							$bIsLinkSetWithDisplayStyleTab = is_a($oAttDef, AttributeLinkedSet::class) && $oAttDef->GetDisplayStyle() === LINKSET_DISPLAY_STYLE_TAB;
							if ((($iFlags & OPT_ATT_HIDDEN) == 0) && !($oAttDef instanceof AttributeDashboard) && !$bIsLinkSetWithDisplayStyleTab) {
								$sInputId = $this->m_iFormId.'_'.$sAttCode;
								if ($oAttDef->IsWritable()) {
									$sInputType = '';
									if (($sStateAttCode === $sAttCode) && (MetaModel::HasLifecycle($sClass))) {
										// State attribute is always read-only from the UI
										$sHTMLValue = $this->GetAsHTML($sAttCode);
										$val = array(
											'label'    => '<label>'.$oAttDef->GetLabel().'</label>',
											'value'    => $sHTMLValue,
											'input_id' => $sInputId,
											'comments' => $sComments,
											'infos' => $sInfos,
										);
									} else {
										if ($iFlags & (OPT_ATT_READONLY | OPT_ATT_SLAVE)) {
											// Check if the attribute is not read-only because of a synchro...
											if ($iFlags & OPT_ATT_SLAVE) {
												$aReasons = array();
												$this->GetSynchroReplicaFlags($sAttCode, $aReasons);
												$sTip = '';
												foreach ($aReasons as $aRow) {
													$sDescription = utils::EscapeHtml($aRow['description']);
													$sDescription = str_replace(array("\r\n", "\n"), "<br/>", $sDescription);
													$sTip .= "<div class='synchro-source'>";
													$sTip .= "<div class='synchro-source-title'>Synchronized with {$aRow['name']}</div>";
													$sTip .= "<div class='synchro-source-description'>$sDescription</div>";
												}
												$sTip = utils::HtmlEntities($sTip);
												$sSynchroIcon = '<div id="synchro_'.$sInputId.'" class="ibo-field--comments--synchro ibo-pill ibo-is-frozen" data-tooltip-content="'.$sTip.'" data-tooltip-html-enabled="true"><i class="fas fa-lock"></i></div>';
												$sComments = $sSynchroIcon;
											}

											// Attribute is read-only
											$sHTMLValue = "<span id=\"field_{$sInputId}\">".$this->GetAsHTML($sAttCode).'</span>';
										} else {
											$sValue = $this->Get($sAttCode);
											$sDisplayValue = $this->GetEditValue($sAttCode);
											// transfer bulk context to components as it can be needed (linked set)
											$aArgs = array('this' => $this, 'formPrefix' => $sPrefix);
											if (array_key_exists('bulk_context', $aExtraParams)) {
												$aArgs['bulk_context'] = $aExtraParams['bulk_context'];
											}
											$sHTMLValue = "".self::GetFormElementForField(
													$oPage, $sClass, $sAttCode, $oAttDef, $sValue,
													$sDisplayValue, $sInputId, '', $iFlags, $aArgs,
													true, $sInputType
												).'';
										}
										$aFieldsMap[$sAttCode] = $sInputId;

										// Attribute description
										$sDescription = $oAttDef->GetDescription();
										$sDescriptionForHTMLTag = utils::HtmlEntities($sDescription);
										$sDescriptionHTMLTag = (empty($sDescriptionForHTMLTag) || $sDescription === $oAttDef->GetLabel()) ? '' : 'class="ibo-has-description" data-tooltip-content="'.$sDescriptionForHTMLTag.'" data-tooltip-max-width="600px"';

										$val = array(
											'label' => '<span '.$sDescriptionHTMLTag.' >'.$oAttDef->GetLabel().'</span>',
											'value' => $sHTMLValue,
											'input_id' => $sInputId,
											'input_type' => $sInputType,
											'comments' => $sComments,
											'infos' => $sInfos,
										);
									}
								}
								else
								{
									// Attribute description
									$sDescription = $oAttDef->GetDescription();
									$sDescriptionForHTMLTag = utils::HtmlEntities($sDescription);
									$sDescriptionHTMLTag = (empty($sDescriptionForHTMLTag) || $sDescription === $oAttDef->GetLabel()) ? '' : 'class="ibo-has-description" data-tooltip-content="'.$sDescriptionForHTMLTag.' "data-tooltip-max-width="600px"';

									$val = array(
										'label' => '<span '.$sDescriptionHTMLTag.' >'.$oAttDef->GetLabel().'</span>',
										'value' => "<span id=\"field_{$sInputId}\">".$this->GetAsHTML($sAttCode)."</span>",
										'comments' => $sComments,
										'infos' => $sInfos,
									);
									$aFieldsMap[$sAttCode] = $sInputId;
								}
							}
							else
							{
								$val = null; // Skip this field
							}
						}
						else
						{
							// !bEditMode
							$val = $this->GetFieldAsHtml($sClass, $sAttCode, $sStateAttCode);
						}

						if ($val != null)
						{
							// Add extra data for markup generation
							// - Attribute code and AttributeDef. class
							$val['attcode'] = $sAttCode;
							$val['atttype'] = $sAttDefClass;
							$val['attlabel'] = $sAttLabel;
							$val['attflags'] = ($bEditMode) ? $this->GetFormAttributeFlags($sAttCode) : OPT_ATT_READONLY;

							// - How the field should be rendered
							$val['layout'] =
								(in_array($oAttDef->GetEditClass(), static::GetAttEditClassesToRenderAsLargeField()))
									? Field::ENUM_FIELD_LAYOUT_LARGE
									: Field::ENUM_FIELD_LAYOUT_SMALL;

							// - For simple fields, we get the raw (stored) value as well
							$bExcludeRawValue = false;
							foreach (static::GetAttDefClassesToExcludeFromMarkupMetadataRawValue() as $sAttDefClassToExclude) {
								if (is_a($sAttDefClass, $sAttDefClassToExclude, true)) {
									$bExcludeRawValue = true;
									break;
								}
							}
							$val['value_raw'] = ($bExcludeRawValue === false) ? $this->Get($sAttCode) : '';

							// The field is visible, add it to the current column
							$oField = FieldUIBlockFactory::MakeFromParams($val);
							if ($sFieldsetName[0] != '_') {
								$oFieldSet->AddSubBlock($oField);
							} else {
								$oColumn->AddSubBlock($oField);
							}
						}
					}
				}
			}
		}

		// Fields with CKEditor need to have the highlight.js lib loaded even if they are in read-only, as it is needed to format code snippets
		if ($bHasFieldsWithRichTextEditor) {
			WebResourcesHelper::EnableCKEditorToWebPage($oPage);
		}

		return $aFieldsMap;
	}


	/**
	 * @param WebPage $oPage
	 * @param bool $bEditMode Note that this parameter is no longer used in this method, {@see static::$sDisplayMode} is used instead, but we cannot remove it as it part of the base interface (iDisplay)...
	 *
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 *
	 * @since 3.0.0 $bEditMode is deprecated and no longer used
	 */
	public function DisplayDetails(WebPage $oPage, $bEditMode = false)
	{
		// N°3786: As this can now be call recursively from the self::ReloadAndDisplay(), we need to make sure we don't fall into an infinite loop
		static $bBlockReentrance = false;

		$sClass = get_class($this);
		$iKey = $this->GetKey();

		if ($this->GetDisplayMode() === static::ENUM_DISPLAY_MODE_VIEW) {
			// The concurrent access lock makes sense only for already existing objects
			$LockEnabled = MetaModel::GetConfig()->Get('concurrent_lock_enabled');
			if ($LockEnabled) {
				$aLockInfo = iTopOwnershipLock::IsLocked($sClass, $iKey);
				if ($aLockInfo['locked'] === true && $aLockInfo['owner']->GetKey() == UserRights::GetUserId() && $bBlockReentrance === false) {
					// If the object is locked by the current user, it's worth trying again, since
					// the lock may be released by 'onunload' which is called AFTER loading the current page.
					//$bTryAgain = $oOwner->GetKey() == UserRights::GetUserId();
					$bBlockReentrance = true;
					self::ReloadAndDisplay($oPage, $this, array('operation' => 'details'));

					return;
				}
			}
		}

		// Object's details
		$oObjectDetails = ObjectFactory::MakeDetails($this, $this->GetDisplayMode());
		if ($oPage->IsPrintableVersion()) {
			$oObjectDetails->SetIsHeaderVisibleOnScroll(false);
		}

		// Note: DisplayBareHeader is called before adding $oObjectDetails to the page, so it can inject HTML before it through $oPage.
		/** @var iTopWebPage $oPage */
		$aHeadersBlocks = $this->DisplayBareHeader($oPage, $bEditMode);
		if (false === empty($aHeadersBlocks['subtitle'])) {
			$oObjectDetails->AddSubTitleBlocks($aHeadersBlocks['subtitle']);
		}
		if (false === empty($aHeadersBlocks['toolbar'])) {
			$oObjectDetails->AddToolbarBlocks($aHeadersBlocks['toolbar']);
		}

		$oPage->AddUiBlock($oObjectDetails);

		$oPage->AddTabContainer(OBJECT_PROPERTIES_TAB, '', $oObjectDetails);
		$oPage->SetCurrentTabContainer(OBJECT_PROPERTIES_TAB);
		$oPage->SetCurrentTab('UI:PropertiesTab');
		$this->DisplayBareProperties($oPage, $bEditMode);
		$this->DisplayBareRelations($oPage, $bEditMode);


		// Note: Adding the JS snippet which enables the image upload should have been done directly by the ActivityPanel which would have kept the independance principle
		// of the UIBlock. For now we keep it this way in order to move on and trace this known limitation in N°3736.
		//
		// Note: Don't do it in modals as we don't display the activity panel
		if (false === ($oPage instanceof AjaxPage)) {
			/** @var ActivityPanel $oActivityPanel */
			$oActivityPanel = $oPage->GetContentLayout()->GetSubBlock(ActivityPanel::BLOCK_CODE);
			// Note: Testing if block exists is necessary as during the 'release_lock_and_details' operation we don't have an activity panel
			if (!is_null($oActivityPanel) && $oActivityPanel->HasTransactionId()) {
				$iTransactionId = $oActivityPanel->GetTransactionId();
				$sTempId = utils::GetUploadTempId($iTransactionId);
				$oPage->add_ready_script(InlineImage::EnableCKEditorImageUpload($this, $sTempId));
			}
		}
	}

	/**
	 * @param WebPage $oPage
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
	 */
	public function DisplayPreview(WebPage $oPage)
	{
		$aDetails = array();
		$sClass = get_class($this);
		$aList = MetaModel::GetZListItems($sClass, 'preview');
		foreach($aList as $sAttCode)
		{
			$aDetails[] = array(
				'label' => MetaModel::GetLabel($sClass, $sAttCode),
				'value' => $this->GetAsHTML($sAttCode),
			);
		}
		$oPage->details($aDetails);
	}

	/**
	 * @param WebPage $oPage
	 * @param \CMDBObjectSet $oSet
	 * @param array $aExtraParams See possible values in {@see DataTableUIBlockFactory::RenderDataTable()}
	 *
	 * @throws \ApplicationException
	 * @throws \CoreException
	 */
	public static function DisplaySet(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		$oPage->AddUiBlock(self::GetDisplaySetBlock($oPage, $oSet, $aExtraParams));
	}

	/**
	 * Simplified version of GetDisplaySet() with less "decoration" around the table (and no paging)
	 * that fits better into a printed document (like a PDF or a printable view)
	 *
	 * @param WebPage $oPage
	 * @param DBObjectSet $oSet
	 * @param array $aExtraParams
	 *
	 * @return string The HTML representation of the table
	 * @throws \CoreException
	 */
	public static function GetDisplaySetForPrinting(WebPage $oPage, DBObjectSet $oSet, $aExtraParams = array())
	{
		$sTableId = isset($aExtraParams['table_id']) ? $aExtraParams['table_id'] : utils::GetUniqueId();;
		$aExtraParams['view_link'] = true;
		$aExtraParams['select_mode'] = 'none';

		return DataTableUIBlockFactory::MakeForObject($oPage, $sTableId, $oSet, $aExtraParams);
	}

	/**
	 * Get the HTML fragment corresponding to the display of a table representing a set of objects
	 *
	 * @param WebPage $oPage The page object is used for out-of-band information (mostly scripts) output
	 * @param \DBObjectSet $oSet The set of objects to display
	 * @param array $aExtraParams key used :
	 *      <ul>
	 *          <li>view_link : if true then for extkey will display links with friendly name and make column sortable, default true
	 *          <li>menu : if true prints DisplayBlock menu, default true
	 *          <li>display_aliases : list of query aliases that will be printed, defaults to [] (displays all)
	 *          <li>zlist : name of the zlist to use, false to disable zlist lookup, default to 'list'
	 *          <li>extra_fields : list of <alias>.<attcode> to add to the result, separator ',', defaults to empty string
	 *      </ul>
	 *
	 * @return String The HTML fragment representing the table of objects. <b>Warning</b> : no JS added to handled
	 *     pagination or table sorting !
	 *
	 * @see DisplayBlock to get a similar table but with the JS for pagination & sorting
	 *
	 * @deprecated 3.0.0 use GetDisplaySetBlock
	 */
	public static function GetDisplaySet(WebPage $oPage, DBObjectSet $oSet, $aExtraParams = array())
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('use GetDisplaySetBlock');
		$oPage->AddUiBlock(static::GetDisplaySetBlock($oPage, $oSet, $aExtraParams));

		return "";
	}

	/**
	 * @param WebPage $oPage
	 * @param \DBObjectSet $oSet
	 * @param array $aExtraParams
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\UIContentBlock|string
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
	 *
	 * @since 3.0.0
	 */
	public static function GetDisplaySetBlock(WebPage $oPage, DBObjectSet $oSet, $aExtraParams = array())
	{
		if ($oPage->IsPrintableVersion() || $oPage->is_pdf()) {
			return self::GetDisplaySetForPrinting($oPage, $oSet, $aExtraParams);
		}
		if (empty($aExtraParams['currentId'])) {
			$iListId = utils::GetUniqueId(); // Works only if not in an Ajax page !!
		} else {
			$iListId = $aExtraParams['currentId'];
		}

		return DataTableUIBlockFactory::MakeForResult($oPage, $iListId, $oSet, $aExtraParams);
	}

	public static function GetDataTableFromDBObjectSet(DBObjectSet $oSet, $aParams = array())
	{
		$aFields = null;
		if (isset($aParams['fields']) && (strlen($aParams['fields']) > 0)) {
			$aFields = explode(',', $aParams['fields']);
		}

		$bFieldsAdvanced = false;
		if (isset($aParams['fields_advanced'])) {
			$bFieldsAdvanced = (bool)$aParams['fields_advanced'];
		}

		$bLocalize = true;
		if (isset($aParams['localize_values'])) {
			$bLocalize = (bool)$aParams['localize_values'];
		}

		$aList = array();

		$aClasses = $oSet->GetFilter()->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach ($aClasses as $sAlias => $sClassName) {
			if (UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $oSet) != UR_ALLOWED_NO) {
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		$aHeader = array();
		foreach ($aAuthorizedClasses as $sAlias => $sClassName) {
			$aList[$sAlias] = array();

			foreach (MetaModel::GetZListItems($sClassName, 'list') as $sAttCode) {
				$oAttDef = Metamodel::GetAttributeDef($sClassName, $sAttCode);
				if (is_null($aFields) || (count($aFields) == 0)) {
					// Standard list of attributes (no link sets)
					if ($oAttDef->IsScalar() && ($oAttDef->IsWritable() || $oAttDef->IsExternalField())) {
						$sAttCodeEx = $oAttDef->IsExternalField() ? $oAttDef->GetKeyAttCode().'->'.$oAttDef->GetExtAttCode() : $sAttCode;

						$aList[$sAlias][$sAttCodeEx] = $oAttDef;

						if ($bFieldsAdvanced && $oAttDef->IsExternalKey(EXTKEY_RELATIVE)) {
							$sRemoteClass = $oAttDef->GetTargetClass();
							foreach (MetaModel::GetReconcKeys($sRemoteClass) as $sRemoteAttCode) {
								$aList[$sAlias][$sAttCode.'->'.$sRemoteAttCode] = MetaModel::GetAttributeDef($sRemoteClass,
									$sRemoteAttCode);
							}
						}
					}
				} else {
					// User defined list of attributes
					if (in_array($sAttCode, $aFields) || in_array($sAlias.'.'.$sAttCode, $aFields)) {
						$aList[$sAlias][$sAttCode] = $oAttDef;
					}
				}
			}
			// Replace external key by the corresponding friendly name (if not already in the list)
			foreach ($aList[$sAlias] as $sAttCode => $oAttDef) {
				if ($oAttDef->IsExternalKey()) {
					unset($aList[$sAlias][$sAttCode]);
					$sFriendlyNameAttCode = $sAttCode.'_friendlyname';
					if (!array_key_exists($sFriendlyNameAttCode,
							$aList[$sAlias]) && MetaModel::IsValidAttCode($sClassName, $sFriendlyNameAttCode)) {
						$oFriendlyNameAtt = MetaModel::GetAttributeDef($sClassName, $sFriendlyNameAttCode);
						$aList[$sAlias][$sFriendlyNameAttCode] = $oFriendlyNameAtt;
					}
				}
			}

			foreach ($aList[$sAlias] as $sAttCodeEx => $oAttDef) {
				$sColLabel = $bLocalize ? MetaModel::GetLabel($sClassName, $sAttCodeEx) : $sAttCodeEx;

				$oFinalAttDef = $oAttDef->GetFinalAttDef();
				if (get_class($oFinalAttDef) == 'AttributeDateTime') {
					$aHeader[$oAttDef->GetCode().'/D'] = ['label' => $sColLabel.' ('.Dict::S('UI:SplitDateTime-Date').')'];
					$aHeader[$oAttDef->GetCode().'/T'] = ['label' => $sColLabel.' ('.Dict::S('UI:SplitDateTime-Time').')'];
				} else {
					$aHeader[$oAttDef->GetCode()] = ['label' => $sColLabel];
				}
			}
		}


		$oSet->Seek(0);
		$aRows = [];
		while ($aObjects = $oSet->FetchAssoc()) {
			$aRow = [];
			foreach ($aAuthorizedClasses as $sAlias => $sClassName) {
				$oObj = $aObjects[$sAlias];
				foreach ($aList[$sAlias] as $sAttCodeEx => $oAttDef) {
					if (is_null($oObj)) {
						$aRow[$oAttDef->GetCode()] = '';
					} else {
						$oFinalAttDef = $oAttDef->GetFinalAttDef();
						if (get_class($oFinalAttDef) == 'AttributeDateTime') {
							$sDate = $oObj->Get($sAttCodeEx);
							if ($sDate === null) {
								$aRow[$oAttDef->GetCode().'/D'] = '';
								$aRow[$oAttDef->GetCode().'/T'] = '';
							} else {
								$iDate = AttributeDateTime::GetAsUnixSeconds($sDate);
								$aRow[$oAttDef->GetCode().'/D'] = date('Y-m-d', $iDate); // Format kept as-is for 100% backward compatibility of the exports
								$aRow[$oAttDef->GetCode().'/T'] = date('H:i:s', $iDate); // Format kept as-is for 100% backward compatibility of the exports
							}
						} else {
							if ($oAttDef instanceof AttributeCaseLog) {
								$rawValue = $oObj->Get($sAttCodeEx);
								$outputValue = str_replace("\n", "<br/>", utils::EscapeHtml($rawValue->__toString()));
								// Trick for Excel: treat the content as text even if it begins with an equal sign
								$aRow[$oAttDef->GetCode()] = $outputValue;
							} else {
								$rawValue = $oObj->Get($sAttCodeEx);
								// Due to custom formatting rules, empty friendlynames may be rendered as non-empty strings
								// let's fix this and make sure we render an empty string if the key == 0
								if ($oAttDef instanceof AttributeExternalField && $oAttDef->IsFriendlyName()) {
									$sKeyAttCode = $oAttDef->GetKeyAttCode();
									if ($oObj->Get($sKeyAttCode) == 0) {
										$rawValue = '';
									}
								}
								if ($bLocalize) {
									$outputValue = utils::EscapeHtml($oFinalAttDef->GetEditValue($rawValue));
								} else {
									$outputValue = utils::EscapeHtml($rawValue);
								}
								$aRow[$oAttDef->GetCode()] = $outputValue;
							}
						}
					}
				}
			}
			$aRows[] = $aRow;
		}
		$oTable = new StaticTable();
		$oTable->SetColumns($aHeader);
		$oTable->SetData($aRows);

		return $oTable;
		//DataTableUIBlockFactory::MakeForStaticData('', $aHeader, $aRows);
	}

	/**
	 * @param WebPage $oPage
	 * @param \CMDBObjectSet $oSet
	 * @param array $aExtraParams key used :
	 *      <ul>
	 *          <li>view_link : if true then for extkey will display links with friendly name and make column sortable, default true
	 *          <li>menu : if true prints DisplayBlock menu, default true
	 *          <li>display_aliases : list of query aliases that will be printed, defaults to [] (displays all)
	 *          <li>zlist : name of the zlist to use, false to disable zlist lookup, default to 'list'
	 *          <li>extra_fields : list of <alias>.<attcode> to add to the result, separator ',', defaults to empty string
	 *      </ul>
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @deprecated 3.0.0
	 */
	public static function GetDisplayExtendedSet(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod();
		if (empty($aExtraParams['currentId'])) {
			$iListId = utils::GetUniqueId(); // Works only if not in an Ajax page !!
		} else {
			$iListId = $aExtraParams['currentId'];
		}
		$aList = array();

		// Initialize and check the parameters
		$bViewLink = isset($aExtraParams['view_link']) ? $aExtraParams['view_link'] : true;
		$bDisplayMenu = isset($aExtraParams['menu']) ? $aExtraParams['menu'] == true : true;
		// Check if there is a list of aliases to limit the display to...
		$aDisplayAliases = isset($aExtraParams['display_aliases']) ? explode(',',
			$aExtraParams['display_aliases']) : array();
		$sZListName = isset($aExtraParams['zlist']) ? ($aExtraParams['zlist']) : 'list';

		$aExtraFieldsRaw = isset($aExtraParams['extra_fields']) ? explode(',',
			trim($aExtraParams['extra_fields'])) : array();
		$aExtraFields = array();
		$sAttCode = '';
		foreach($aExtraFieldsRaw as $sFieldName)
		{
			// Ignore attributes not of the main queried class
			if (preg_match('/^(.*)\.(.*)$/', $sFieldName, $aMatches))
			{
				$sClassAlias = $aMatches[1];
				$sAttCode = $aMatches[2];
				if (array_key_exists($sClassAlias, $oSet->GetSelectedClasses()))
				{
					$aExtraFields[$sClassAlias][] = $sAttCode;
				}
			}
			else
			{
				$aExtraFields['*'] = $sAttCode;
			}
		}

		$aClasses = $oSet->GetFilter()->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach($aClasses as $sAlias => $sClassName)
		{
			if ((UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $oSet) != UR_ALLOWED_NO) &&
				((count($aDisplayAliases) == 0) || (in_array($sAlias, $aDisplayAliases))))
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		foreach($aAuthorizedClasses as $sAlias => $sClassName)
		{
			if (array_key_exists($sAlias, $aExtraFields))
			{
				$aList[$sAlias] = $aExtraFields[$sAlias];
			}
			else
			{
				$aList[$sAlias] = array();
			}
			if ($sZListName !== false)
			{
				$aDefaultList = self::FlattenZList(MetaModel::GetZListItems($sClassName, $sZListName));

				$aList[$sAlias] = array_merge($aDefaultList, $aList[$sAlias]);
			}

			// Filter the list to removed linked set since we are not able to display them here
			foreach ($aList[$sAlias] as $index => $sAttCode)
			{
				$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
				if ($oAttDef instanceof AttributeLinkedSet)
				{
					// Removed from the display list
					unset($aList[$sAlias][$index]);
				}
			}

			if (empty($aList[$sAlias]))
			{
				unset($aList[$sAlias], $aAuthorizedClasses[$sAlias]);
			}
		}

		$sSelectMode = 'none';

		$oDataTable = new DataTable($iListId, $oSet, $aAuthorizedClasses);

		$oSettings = DataTableSettings::GetDataModelSettings($aAuthorizedClasses, $bViewLink, $aList);

		$bDisplayLimit = isset($aExtraParams['display_limit']) ? $aExtraParams['display_limit'] : true;
		if ($bDisplayLimit)
		{
			$iDefaultPageSize = appUserPreferences::GetPref('default_page_size',
				MetaModel::GetConfig()->GetMinDisplayLimit());
			$oSettings->iDefaultPageSize = $iDefaultPageSize;
		}

		$oSettings->aSortOrder = MetaModel::GetOrderByDefault($sClassName);

		return $oDataTable->Display($oPage, $oSettings, $bDisplayMenu, $sSelectMode, $bViewLink, $aExtraParams);
	}

	/**
	 * @param WebPage $oPage
	 * @param \CMDBObjectSet $oSet
	 * @param array $aParams
	 * @param string $sCharset
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public static function DisplaySetAsCSV(WebPage $oPage, CMDBObjectSet $oSet, $aParams = array(), $sCharset = 'UTF-8')
	{
		$oPage->add(self::GetSetAsCSV($oSet, $aParams, $sCharset));
	}

	/**
	 * @param \DBObjectSet $oSet
	 * @param array $aParams
	 * @param string $sCharset
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \Exception
	 */
	public static function GetSetAsCSV(DBObjectSet $oSet, $aParams = array(), $sCharset = 'UTF-8')
	{
		$sSeparator = isset($aParams['separator']) ? $aParams['separator'] : ','; // default separator is comma
		$sTextQualifier = isset($aParams['text_qualifier']) ? $aParams['text_qualifier'] : '"'; // default text qualifier is double quote
		$aFields = null;
		if (isset($aParams['fields']) && (strlen($aParams['fields']) > 0))
		{
			$aFields = explode(',', $aParams['fields']);
		}

		$bFieldsAdvanced = false;
		if (isset($aParams['fields_advanced']))
		{
			$bFieldsAdvanced = (bool)$aParams['fields_advanced'];
		}

		$bLocalize = true;
		if (isset($aParams['localize_values']))
		{
			$bLocalize = (bool)$aParams['localize_values'];
		}

		$aList = array();

		$aClasses = $oSet->GetFilter()->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach($aClasses as $sAlias => $sClassName)
		{
			if (UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $oSet) != UR_ALLOWED_NO)
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		$aHeader = array();
		foreach($aAuthorizedClasses as $sAlias => $sClassName)
		{
			$aList[$sAlias] = array();

			foreach(MetaModel::ListAttributeDefs($sClassName) as $sAttCode => $oAttDef)
			{
				if (is_null($aFields) || (count($aFields) == 0))
				{
					// Standard list of attributes (no link sets)
					if ($oAttDef->IsScalar() && ($oAttDef->IsWritable() || $oAttDef->IsExternalField()))
					{
						$sAttCodeEx = $oAttDef->IsExternalField() ? $oAttDef->GetKeyAttCode().'->'.$oAttDef->GetExtAttCode() : $sAttCode;

						if ($oAttDef->IsExternalKey(EXTKEY_ABSOLUTE))
						{
							if ($bFieldsAdvanced)
							{
								$aList[$sAlias][$sAttCodeEx] = $oAttDef;

								if ($oAttDef->IsExternalKey(EXTKEY_RELATIVE))
								{
									$sRemoteClass = $oAttDef->GetTargetClass();
									foreach(MetaModel::GetReconcKeys($sRemoteClass) as $sRemoteAttCode)
									{
										$aList[$sAlias][$sAttCode.'->'.$sRemoteAttCode] = MetaModel::GetAttributeDef($sRemoteClass,
											$sRemoteAttCode);
									}
								}
							}
						}
						else
						{
							// Any other attribute
							$aList[$sAlias][$sAttCodeEx] = $oAttDef;
						}
					}
				}
				else
				{
					// User defined list of attributes
					if (in_array($sAttCode, $aFields) || in_array($sAlias.'.'.$sAttCode, $aFields))
					{
						$aList[$sAlias][$sAttCode] = $oAttDef;
					}
				}
			}
			if ($bFieldsAdvanced)
			{
				$aHeader[] = 'id';
			}
			foreach($aList[$sAlias] as $sAttCodeEx => $oAttDef)
			{
				$aHeader[] = $bLocalize ? MetaModel::GetLabel($sClassName, $sAttCodeEx,
					isset($aParams['showMandatoryFields'])) : $sAttCodeEx;
			}
		}
		$sHtml = implode($sSeparator, $aHeader)."\n";
		$oSet->Seek(0);
		while ($aObjects = $oSet->FetchAssoc())
		{
			$aRow = array();
			foreach($aAuthorizedClasses as $sAlias => $sClassName)
			{
				$oObj = $aObjects[$sAlias];
				if ($bFieldsAdvanced)
				{
					if (is_null($oObj))
					{
						$aRow[] = '';
					}
					else
					{
						$aRow[] = $oObj->GetKey();
					}
				}
				foreach($aList[$sAlias] as $sAttCodeEx => $oAttDef)
				{
					if (is_null($oObj))
					{
						$aRow[] = '';
					}
					else
					{
						$value = $oObj->Get($sAttCodeEx);
						$sCSVValue = $oAttDef->GetAsCSV($value, $sSeparator, $sTextQualifier, $oObj, $bLocalize);
						$aRow[] = iconv('UTF-8', $sCharset.'//IGNORE//TRANSLIT', $sCSVValue);
					}
				}
			}
			$sHtml .= implode($sSeparator, $aRow)."\n";
		}

		return $sHtml;
	}

	/**
	 * @param WebPage $oPage
	 * @param \CMDBObjectSet $oSet
	 * @param array $aParams
	 *
	 * @throws \Exception
	 *  only used in old and deprecated export.php
	 *
	 * @internal Only to be used by `/webservices/export.php` : this is a legacy method that produces wrong HTML (no TR on table body rows)
	 */
	public static function DisplaySetAsHTMLSpreadsheet(WebPage $oPage, CMDBObjectSet $oSet, $aParams = array())
	{
		$oPage->add(self::GetSetAsHTMLSpreadsheet($oSet, $aParams));
	}

	/**
	 * Spreadsheet output: designed for end users doing some reporting
	 * Then the ids are excluded and replaced by the corresponding friendlyname
	 *
	 * @param \DBObjectSet $oSet
	 * @param array $aParams
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \Exception
	 * 
	 * @internal Only to be used by `/webservices/export.php` : this is a legacy method that produces wrong HTML (no TR on table body rows)
	 */
	public static function GetSetAsHTMLSpreadsheet(DBObjectSet $oSet, $aParams = array())
	{
		$aFields = null;
		if (isset($aParams['fields']) && (strlen($aParams['fields']) > 0))
		{
			$aFields = explode(',', $aParams['fields']);
		}

		$bFieldsAdvanced = false;
		if (isset($aParams['fields_advanced']))
		{
			$bFieldsAdvanced = (bool)$aParams['fields_advanced'];
		}

		$bLocalize = true;
		if (isset($aParams['localize_values']))
		{
			$bLocalize = (bool)$aParams['localize_values'];
		}

		$aList = array();

		$aClasses = $oSet->GetFilter()->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach($aClasses as $sAlias => $sClassName)
		{
			if (UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $oSet) != UR_ALLOWED_NO)
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		$aHeader = array();
		foreach($aAuthorizedClasses as $sAlias => $sClassName)
		{
			$aList[$sAlias] = array();

			foreach(MetaModel::ListAttributeDefs($sClassName) as $sAttCode => $oAttDef)
			{
				if (is_null($aFields) || (count($aFields) == 0))
				{
					// Standard list of attributes (no link sets)
					if ($oAttDef->IsScalar() && ($oAttDef->IsWritable() || $oAttDef->IsExternalField()))
					{
						$sAttCodeEx = $oAttDef->IsExternalField() ? $oAttDef->GetKeyAttCode().'->'.$oAttDef->GetExtAttCode() : $sAttCode;

						$aList[$sAlias][$sAttCodeEx] = $oAttDef;

						if ($bFieldsAdvanced && $oAttDef->IsExternalKey(EXTKEY_RELATIVE))
						{
							$sRemoteClass = $oAttDef->GetTargetClass();
							foreach(MetaModel::GetReconcKeys($sRemoteClass) as $sRemoteAttCode)
							{
								$aList[$sAlias][$sAttCode.'->'.$sRemoteAttCode] = MetaModel::GetAttributeDef($sRemoteClass,
									$sRemoteAttCode);
							}
						}
					}
				}
				else
				{
					// User defined list of attributes
					if (in_array($sAttCode, $aFields) || in_array($sAlias.'.'.$sAttCode, $aFields))
					{
						$aList[$sAlias][$sAttCode] = $oAttDef;
					}
				}
			}
			// Replace external key by the corresponding friendly name (if not already in the list)
			foreach($aList[$sAlias] as $sAttCode => $oAttDef)
			{
				if ($oAttDef->IsExternalKey())
				{
					unset($aList[$sAlias][$sAttCode]);
					$sFriendlyNameAttCode = $sAttCode.'_friendlyname';
					if (!array_key_exists($sFriendlyNameAttCode,
							$aList[$sAlias]) && MetaModel::IsValidAttCode($sClassName, $sFriendlyNameAttCode))
					{
						$oFriendlyNameAtt = MetaModel::GetAttributeDef($sClassName, $sFriendlyNameAttCode);
						$aList[$sAlias][$sFriendlyNameAttCode] = $oFriendlyNameAtt;
					}
				}
			}

			foreach($aList[$sAlias] as $sAttCodeEx => $oAttDef)
			{
				$sColLabel = $bLocalize ? MetaModel::GetLabel($sClassName, $sAttCodeEx) : $sAttCodeEx;

				$oFinalAttDef = $oAttDef->GetFinalAttDef();
				if (get_class($oFinalAttDef) == 'AttributeDateTime')
				{
					$aHeader[] = $sColLabel.' ('.Dict::S('UI:SplitDateTime-Date').')';
					$aHeader[] = $sColLabel.' ('.Dict::S('UI:SplitDateTime-Time').')';
				}
				else
				{
					$aHeader[] = $sColLabel;
				}
			}
		}


		$sHtml = "<table border=\"1\">\n";
		$sHtml .= "<tr>\n";
		$sHtml .= "<td>".implode("</td><td>", $aHeader)."</td>\n";
		$sHtml .= "</tr>\n";
		$oSet->Seek(0);
		while ($aObjects = $oSet->FetchAssoc())
		{
			$aRow = array();
			foreach($aAuthorizedClasses as $sAlias => $sClassName)
			{
				$oObj = $aObjects[$sAlias];
				foreach($aList[$sAlias] as $sAttCodeEx => $oAttDef)
				{
					if (is_null($oObj))
					{
						$aRow[] = '<td></td>';
					}
					else
					{
						$oFinalAttDef = $oAttDef->GetFinalAttDef();
						if (get_class($oFinalAttDef) == 'AttributeDateTime')
						{
							$sDate = $oObj->Get($sAttCodeEx);
							if ($sDate === null)
							{
								$aRow[] = '<td></td>';
								$aRow[] = '<td></td>';
							}
							else
							{
								$iDate = AttributeDateTime::GetAsUnixSeconds($sDate);
								$aRow[] = '<td>'.date('Y-m-d',
										$iDate).'</td>'; // Format kept as-is for 100% backward compatibility of the exports
								$aRow[] = '<td>'.date('H:i:s',
										$iDate).'</td>'; // Format kept as-is for 100% backward compatibility of the exports
							}
						}
						else
						{
							if ($oAttDef instanceof AttributeCaseLog)
							{
								$rawValue = $oObj->Get($sAttCodeEx);
								$outputValue = str_replace("\n", "<br/>",
									utils::EscapeHtml($rawValue->__toString()));
								// Trick for Excel: treat the content as text even if it begins with an equal sign
								$aRow[] = '<td x:str>'.$outputValue.'</td>';
							}
							else
							{
								$rawValue = $oObj->Get($sAttCodeEx);
								// Due to custom formatting rules, empty friendlynames may be rendered as non-empty strings
								// let's fix this and make sure we render an empty string if the key == 0
								if ($oAttDef instanceof AttributeExternalField && $oAttDef->IsFriendlyName())
								{
									$sKeyAttCode = $oAttDef->GetKeyAttCode();
									if ($oObj->Get($sKeyAttCode) == 0)
									{
										$rawValue = '';
									}
								}
								if ($bLocalize) {
									$outputValue = utils::EscapeHtml($oFinalAttDef->GetEditValue($rawValue));
								}
								else {
									$outputValue = utils::EscapeHtml($rawValue);
								}
								$aRow[] = '<td>'.$outputValue.'</td>';
							}
						}
					}
				}
			}
			$sHtml .= implode("\n", $aRow);
			$sHtml .= "</tr>\n";
		}
		$sHtml .= "</table>\n";

		return $sHtml;
	}

	/**
	 * @param WebPage $oPage
	 * @param \CMDBObjectSet $oSet
	 * @param array $aParams
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public static function DisplaySetAsXML(WebPage $oPage, CMDBObjectSet $oSet, $aParams = array())
	{
		$bLocalize = true;
		if (isset($aParams['localize_values']))
		{
			$bLocalize = (bool)$aParams['localize_values'];
		}

		$aClasses = $oSet->GetFilter()->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach($aClasses as $sAlias => $sClassName)
		{
			if (UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $oSet) != UR_ALLOWED_NO)
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		$aList = array();
		$aList[$sAlias] = MetaModel::GetZListItems($sClassName, 'details');
		$oPage->add("<Set>\n");
		$oSet->Seek(0);
		while ($aObjects = $oSet->FetchAssoc())
		{
			if (count($aAuthorizedClasses) > 1)
			{
				$oPage->add("<Row>\n");
			}
			foreach($aAuthorizedClasses as $sAlias => $sClassName)
			{
				$oObj = $aObjects[$sAlias];
				if (is_null($oObj))
				{
					$oPage->add("<$sClassName alias=\"$sAlias\" id=\"null\">\n");
				}
				else
				{
					$sClassName = get_class($oObj);
					$oPage->add("<$sClassName alias=\"$sAlias\" id=\"".$oObj->GetKey()."\">\n");
				}
				foreach(MetaModel::ListAttributeDefs($sClassName) as $sAttCode => $oAttDef)
				{
					if (is_null($oObj))
					{
						$oPage->add("<$sAttCode>null</$sAttCode>\n");
					}
					else
					{
						if ($oAttDef->IsWritable())
						{
							if (!$oAttDef->IsLinkSet())
							{
								$sValue = $oObj->GetAsXML($sAttCode, $bLocalize);
								$oPage->add("<$sAttCode>$sValue</$sAttCode>\n");
							}
						}
					}
				}
				$oPage->add("</$sClassName>\n");
			}
			if (count($aAuthorizedClasses) > 1)
			{
				$oPage->add("</Row>\n");
			}
		}
		$oPage->add("</Set>\n");
	}

	/**
	 * @param WebPage $oPage
	 * @param \CMDBObjectSet $oSet
	 * @param array $aExtraParams
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	public static function DisplaySearchForm(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{

		$oPage->add(self::GetSearchForm($oPage, $oSet, $aExtraParams));
	}

	/**
	 * @param WebPage $oPage
	 * @param CMDBObjectSet $oSet
	 * @param array $aExtraParams
	 *
	 * @return string
	 * @throws CoreException
	 * @throws DictExceptionMissingString
	 */
	public static function GetSearchForm(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		$oSearchForm = new SearchForm();

		return $oSearchForm->GetSearchForm($oPage, $oSet, $aExtraParams);
	}


	/**
	 * @param WebPage $oPage
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param \AttributeDefinition $oAttDef
	 * @param string $value
	 * @param string $sDisplayValue
	 * @param string $iId
	 * @param string $sNameSuffix
	 * @param int $iFlags
	 * @param array{this: \DBObject, formPrefix: string} $aArgs
	 * @param bool $bPreserveCurrentValue Preserve the current value even if not allowed
	 * @param string $sInputType type of rendering used, see ENUM_INPUT_TYPE_* const
	 *
	 * @return string
	 *
	 * @throws \ArchivedObjectException
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \ReflectionException
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 * @throws \Exception
	 */
	public static function GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef, $value = '', $sDisplayValue = '', $iId = '', $sNameSuffix = '', $iFlags = 0, $aArgs = array(), $bPreserveCurrentValue = true, &$sInputType = '')
	{
		$sFormPrefix = isset($aArgs['formPrefix']) ? $aArgs['formPrefix'] : '';
		$sFieldPrefix = isset($aArgs['prefix']) ? $sFormPrefix.$aArgs['prefix'] : $sFormPrefix;
		if ($sDisplayValue == '') {
			$sDisplayValue = $value;
		}

		if (isset($aArgs[$sAttCode]) && empty($value)) {
			// default value passed by the context (either the app context of the operation)
			$value = $aArgs[$sAttCode];
		}

		if (!empty($iId)) {
			$iInputId = $iId;
		} else {
			$iInputId = utils::GetUniqueId();
		}

		$sHTMLValue = '';

		// attributes not compatible with bulk modify
		$bAttNotCompatibleWithBulk = array_key_exists('bulk_context', $aArgs) && !$oAttDef->IsBulkModifyCompatible();
		if ($bAttNotCompatibleWithBulk) {
			$oTagSetBlock = new Html('<span class="ibo-bulk--bulk-modify--incompatible-attribute">'.Dict::S('UI:Bulk:modify:IncompatibleAttribute').'</span>');
			$sHTMLValue = ConsoleBlockRenderer::RenderBlockTemplateInPage($oPage, $oTagSetBlock);
		}

		if (!$oAttDef->IsExternalField() && !$bAttNotCompatibleWithBulk) {
			$bMandatory = 'false';
			if ((!$oAttDef->IsNullAllowed()) || ($iFlags & OPT_ATT_MANDATORY)) {
				$bMandatory = 'true';
			}
			$sValidationSpan = "<span class=\"form_validation ibo-field-validation\" id=\"v_{$iId}\"></span>";
			$sReloadSpan = "<span class=\"field_status\" id=\"fstatus_{$iId}\"></span>";
			$sHelpText = utils::EscapeHtml($oAttDef->GetHelpOnEdition());

			// mandatory field control vars
			$aEventsList = array(); // contains any native event (like change), plus 'validate' for the form submission
			$sNullValue = $oAttDef->GetNullValue(); // used for the ValidateField() call in js/forms-json-utils.js
			$sFieldToValidateId = $iId; // can be different than the displayed field (for example in TagSet)

			// List of attributes that depend on the current one
			// Might be modified depending on the current field
			$sWizardHelperJsVarName = "oWizardHelper{$sFormPrefix}";
			$aDependencies = MetaModel::GetDependentAttributes($sClass, $sAttCode);

			$sAttDefEditClass = $oAttDef->GetEditClass();
			switch ($sAttDefEditClass) {
				case 'Date':
					$sInputType = self::ENUM_INPUT_TYPE_SINGLE_INPUT;
					$aEventsList[] = 'validate';
					$aEventsList[] = 'keyup';
					$aEventsList[] = 'change';

					$sPlaceholderValue = 'placeholder="'.utils::EscapeHtml(AttributeDate::GetFormat()->ToPlaceholder()).'"';
					$sDisplayValueForHtml = utils::EscapeHtml($sDisplayValue);
					$sHTMLValue = <<<HTML
<div class="field_input_zone field_input_date ibo-input-wrapper ibo-input-date-wrapper" data-validation="untouched">
	<input title="$sHelpText" class="date-pick ibo-input ibo-input-date" type="text" {$sPlaceholderValue} name="attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}" value="{$sDisplayValueForHtml}" id="{$iId}" autocomplete="off" />
</div>{$sValidationSpan}{$sReloadSpan}
HTML;
					break;

				case 'DateTime':
					$sInputType = self::ENUM_INPUT_TYPE_SINGLE_INPUT;
					$aEventsList[] = 'validate';
					$aEventsList[] = 'keyup';
					$aEventsList[] = 'change';

					$sPlaceholderValue = 'placeholder="'.utils::EscapeHtml(AttributeDateTime::GetFormat()->ToPlaceholder()).'"';
					$sDisplayValueForHtml = utils::EscapeHtml($sDisplayValue);
					$sHTMLValue = <<<HTML
<div class="field_input_zone field_input_datetime ibo-input-wrapper ibo-input-datetime-wrapper" data-validation="untouched">
	<input title="{$sHelpText}" class="datetime-pick ibo-input ibo-input-datetime" type="text" size="19" {$sPlaceholderValue} name="attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}" value="{$sDisplayValueForHtml}" id="{$iId}" autocomplete="off" />
</div>{$sValidationSpan}{$sReloadSpan}
HTML;
					break;

				case 'Duration':
					$sInputType = self::ENUM_INPUT_TYPE_MULTIPLE_INPUTS;
					$aEventsList[] = 'validate';
					$aEventsList[] = 'change';
					$oPage->add_ready_script("$('#{$iId}_d').on('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
					$oPage->add_ready_script("$('#{$iId}_h').on('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
					$oPage->add_ready_script("$('#{$iId}_m').on('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
					$oPage->add_ready_script("$('#{$iId}_s').on('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
					$aVal = AttributeDuration::SplitDuration($value);
					$sDays = "<input class=\"ibo-input ibo-input-duration\" title=\"$sHelpText\" type=\"text\" size=\"3\" name=\"attr_{$sFieldPrefix}{$sAttCode}[d]{$sNameSuffix}\" value=\"{$aVal['days']}\" id=\"{$iId}_d\"/>";
					$sHours = "<input class=\"ibo-input ibo-input-duration\" title=\"$sHelpText\" type=\"text\" size=\"2\" name=\"attr_{$sFieldPrefix}{$sAttCode}[h]{$sNameSuffix}\" value=\"{$aVal['hours']}\" id=\"{$iId}_h\"/>";
					$sMinutes = "<input class=\"ibo-input ibo-input-duration\" title=\"$sHelpText\" type=\"text\" size=\"2\" name=\"attr_{$sFieldPrefix}{$sAttCode}[m]{$sNameSuffix}\" value=\"{$aVal['minutes']}\" id=\"{$iId}_m\"/>";
					$sSeconds = "<input class=\"ibo-input ibo-input-duration\" title=\"$sHelpText\" type=\"text\" size=\"2\" name=\"attr_{$sFieldPrefix}{$sAttCode}[s]{$sNameSuffix}\" value=\"{$aVal['seconds']}\" id=\"{$iId}_s\"/>";
					$sHidden = "<input type=\"hidden\" id=\"{$iId}\" value=\"".utils::EscapeHtml($value)."\"/>";
					$sHTMLValue = Dict::Format('UI:DurationForm_Days_Hours_Minutes_Seconds', $sDays, $sHours, $sMinutes, $sSeconds).$sHidden."&nbsp;".$sValidationSpan.$sReloadSpan;
					$oPage->add_ready_script("$('#{$iId}').on('update', function(evt, sFormId) { return ToggleDurationField('$iId'); });");
					break;

				case 'Password':
					$sInputType = self::ENUM_INPUT_TYPE_PASSWORD;
					$aEventsList[] = 'validate';
					$aEventsList[] = 'keyup';
					$aEventsList[] = 'change';
					$sHTMLValue = "<div class=\"field_input_zone field_input_password ibo-input-wrapper ibo-input-password-wrapper\" data-validation=\"untouched\"><input class=\"ibo-input ibo-input-password\" title=\"$sHelpText\" type=\"password\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"".utils::EscapeHtml($value)."\" id=\"$iId\"/></div>{$sValidationSpan}{$sReloadSpan}";
					break;

				case 'OQLExpression':
				case 'Text':
					$sInputType = self::ENUM_INPUT_TYPE_TEXTAREA;
					$aEventsList[] = 'validate';
					$aEventsList[] = 'keyup';
					$aEventsList[] = 'change';

					$sEditValue = $oAttDef->GetEditValue($value);
					$sEditValueForHtml = utils::EscapeHtml($sEditValue);
					$sFullscreenLabelForHtml = utils::EscapeHtml(Dict::S('UI:ToggleFullScreen'));

					$aStyles = array();
					$sStyle = '';
					$sWidth = $oAttDef->GetWidth();
					if (!empty($sWidth)) {
						$aStyles[] = 'width:'.$sWidth;
					}
					$sHeight = $oAttDef->GetHeight();
					if (!empty($sHeight)) {
						$aStyles[] = 'height:'.$sHeight;
					}
					if (count($aStyles) > 0) {
						$sStyle = 'style="'.implode('; ', $aStyles).'"';
					}

					$aTextareaCssClasses = [];

					if ($oAttDef->GetEditClass() == 'OQLExpression') {
						$aTextareaCssClasses[] = 'ibo-query-oql';
						$aTextareaCssClasses[] = 'ibo-is-code';
						// N°3227 button to open predefined queries dialog
						$sPredefinedBtnId = 'predef_btn_'.$sFieldPrefix.$sAttCode.$sNameSuffix;
						$sSearchQueryLbl = Dict::S('UI:Edit:SearchQuery');
						$oPredefQueryButton = ButtonUIBlockFactory::MakeIconAction(
							'fas fa-search',
							$sSearchQueryLbl,
							null,
							null,
							false,
							$sPredefinedBtnId
						);
						$oPredefQueryButton->AddCSSClass('ibo-action-button')
						->SetOnClickJsCode(
							<<<JS
	oACWidget_{$iId}.Search();
JS
						);
						$oPredefQueryRenderer = new BlockRenderer($oPredefQueryButton);
						$sAdditionalStuff = $oPredefQueryRenderer->RenderHtml();
						$oPage->add_ready_script($oPredefQueryRenderer->RenderJsInline($oPredefQueryButton::ENUM_JS_TYPE_ON_INIT));

						$oPage->add_ready_script(<<<JS
// noinspection JSAnnotator
oACWidget_{$iId} = new ExtKeyWidget('$iId', 'QueryOQL', 'SELECT QueryOQL WHERE is_template = \'yes\'', '$sSearchQueryLbl', true, null, null, true, true, 'oql');
// noinspection JSAnnotator
oACWidget_{$iId}.emptyHtml = "<div style=\"background: #fff; border:0; text-align:center; vertical-align:middle;\"><p>Use the search form above to search for objects to be added.</p></div>";

if ($('#ac_dlg_{$iId}').length == 0)
{
	$('body').append('<div id="ac_dlg_{$iId}"></div>');
	$('#ac_dlg_{$iId}').dialog({ 
			width: $(window).width()*0.8, 
			height: $(window).height()*0.8, 
			autoOpen: false, 
			modal: true, 
			title: '$sSearchQueryLbl', 
			resizeStop: oACWidget_{$iId}.UpdateSizes, 
			close: oACWidget_{$iId}.OnClose 
		});
}
JS
						);

						// test query link
						$sTestResId = 'query_res_'.$sFieldPrefix.$sAttCode.$sNameSuffix; //$oPage->GetUniqueId();
						$sBaseUrl = utils::GetAbsoluteUrlAppRoot().'pages/run_query.php?expression=';
						$sTestQueryLbl = Dict::S('UI:Edit:TestQuery');
						$oTestQueryButton = ButtonUIBlockFactory::MakeIconAction(
							'fas fa-play',
							$sTestQueryLbl,
							null,
							null,
							false,
							$sTestResId
						);
						$oTestQueryButton->AddCSSClass('ibo-action-button')
						->SetOnClickJsCode(
							<<<JS
var sQueryRaw = $("#$iId").val(),
sQueryEncoded = encodeURI(sQueryRaw);
window.open('$sBaseUrl' + sQueryEncoded, '_blank');
JS
						);
						$oTestQueryRenderer = new BlockRenderer($oTestQueryButton);
						$sAdditionalStuff .= $oTestQueryRenderer->RenderHtml();
						$oPage->add_ready_script($oTestQueryRenderer->RenderJsInline($oTestQueryButton::ENUM_JS_TYPE_ON_INIT));
					} else {
						$sAdditionalStuff = '';
					}

					// Ok, the text area is drawn here
					$sTextareCssClassesAsString = implode(' ', $aTextareaCssClasses);
					$sHTMLValue = <<<HTML
{$sAdditionalStuff}
<div class="field_input_zone field_input_text ibo-input-wrapper ibo-input-text-wrapper" data-validation="untouched">
	<div class="f_i_text_header">
		<span class="fullscreen_button" title="{$sFullscreenLabelForHtml}"></span>
	</div>
	<textarea class="ibo-input ibo-input-text {$sTextareCssClassesAsString}" title="{$sHelpText}" name="attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}" rows="8" cols="40" id="{$iId}" {$sStyle} >{$sEditValueForHtml}</textarea>
</div>
{$sValidationSpan}{$sReloadSpan}
HTML;
					$oPage->add_ready_script(
						<<<JS
                        $('#$iId').closest('.field_input_text').find('.fullscreen_button').on('click', function(oEvent){
                            var oOriginField = $('#$iId').closest('.field_input_text');
                            var oClonedField = oOriginField.clone();
                            oClonedField.addClass('fullscreen').appendTo('body');
                            oClonedField.find('.fullscreen_button').on('click', function(oEvent){
                                // Copying value to origin field
                                oOriginField.find('textarea').val(oClonedField.find('textarea').val());
                                oClonedField.remove();
                                // Triggering change event
                                oOriginField.find('textarea').triggerHandler('change');
                            });
                        });
                        
                        // Submit host form on "Ctrl + Enter" or "Meta (Cmd) + Enter" keyboard shortcut
                        $('#$iId').on('keyup', function (oEvent) {
                            if ((oEvent.ctrlKey || oEvent.metaKey) && oEvent.key === 'Enter') {
                                $(this).closest('form').trigger('submit');
                            }
                        });
JS
					);
				break;

				// Since 3.0 not used for activity panel but kept for bulk modify and bulk-event extension
				case 'CaseLog':
					$sInputType = self::ENUM_INPUT_TYPE_HTML_EDITOR;
					$aStyles = array();
					$sStyle = '';
					$sWidth = $oAttDef->GetWidth();
					if (!empty($sWidth)) {
						$aStyles[] = 'width:'.$sWidth;
					}
					$sHeight = $oAttDef->GetHeight();
					if (!empty($sHeight)) {
						$aStyles[] = 'height:'.$sHeight;
					}
					if (count($aStyles) > 0) {
						$sStyle = 'style="'.implode('; ', $aStyles).'"';
					}

					$sHeader = '<div class="ibo-caselog-entry-form--actions"><div class="""ibo-caselog-entry-form--actions" data-role="ibo-caselog-entry-form--action-buttons--extra-actions"></div></div>'; // will be hidden in CSS (via :empty) if it remains empty
					$sEditValue = is_object($value) ? $value->GetModifiedEntry('html') : '';
					$sPreviousLog = is_object($value) ? $value->GetAsHTML($oPage, true /* bEditMode */, array('AttributeText', 'RenderWikiHtml')) : '';
					$iEntriesCount = is_object($value) ? count($value->GetIndex()) : 0;
					$sHidden = "<input type=\"hidden\" id=\"{$iId}_count\" value=\"$iEntriesCount\"/>"; // To know how many entries the case log already contains

					$sHTMLValue = "$sHeader<div class=\"ibo-caselog-entry-form--text-input\" $sStyle data-role=\"ibo-caselog-entry-form--text-input\">";
					$sHTMLValue .= "<textarea class=\"htmlEditor ibo-input-richtext-placeholder\" style=\"border:0;width:100%\" title=\"$sHelpText\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" rows=\"8\" cols=\"40\" id=\"$iId\">".utils::EscapeHtml($sEditValue)."</textarea>";
					$sHTMLValue .= "$sPreviousLog</div>{$sValidationSpan}{$sReloadSpan}$sHidden";

					// Note: This should be refactored for all types of attribute (see at the end of this function) but as we are doing this for a maintenance release, we are scheduling it for the next main release in to order to avoid regressions as much as possible.
					$sNullValue = $oAttDef->GetNullValue();
					if (!is_numeric($sNullValue)) {
						$sNullValue = "'$sNullValue'"; // Add quotes to turn this into a JS string if it's not a number
					}
					$sOriginalValue = ($iFlags & OPT_ATT_MUSTCHANGE) ? json_encode($value->GetModifiedEntry('html')) : 'undefined';

					$oPage->add_ready_script("$('#$iId').on('keyup change validate', function(evt, sFormId) { return ValidateCaseLogField('$iId', $bMandatory, sFormId, $sNullValue, $sOriginalValue) } );"); // Custom validation function

					// Replace the text area with CKEditor
					// To change the default settings of the editor,
					// a) edit the file /js/ckeditor/config.js
					// b) or override some of the configuration settings, using the second parameter of ckeditor()
					$aConfig = CKEditorHelper::GetCkeditorPref();
					$aConfig['placeholder'] = Dict::S('UI:CaseLogTypeYourTextHere');

					// - Final config
					$sConfigJS = json_encode($aConfig);

					WebResourcesHelper::EnableCKEditorToWebPage($oPage);
					$oPage->add_ready_script("CombodoCKEditorHandler.CreateInstance('#$iId')");

					$oPage->add_ready_script(
<<<EOF
$('#$iId').on('update', function(evt){
	BlockField('cke_$iId', $('#$iId').attr('disabled'));
	//Delayed execution - ckeditor must be properly initialized before setting readonly
	var retryCount = 0;
	var oMe = $('#$iId');
	var delayedSetReadOnly = function () {
		if (oMe.data('ckeditorInstance').editable() == undefined && retryCount++ < 10) {
			setTimeout(delayedSetReadOnly, retryCount * 100); //Wait a while longer each iteration
		}
		else
		{
			oMe.data('ckeditorInstance').setReadOnly(oMe.prop('disabled'));
		}
	};
	setTimeout(delayedSetReadOnly, 50);
});
EOF
					);
					break;

				case 'HTML':
					$sInputType = self::ENUM_INPUT_TYPE_HTML_EDITOR;
					$sEditValue = $oAttDef->GetEditValue($value);
					$oWidget = new UIHTMLEditorWidget($iId, $oAttDef, $sNameSuffix, $sFieldPrefix, $sHelpText,
						$sValidationSpan.$sReloadSpan, $sEditValue, $bMandatory);
					$sHTMLValue = $oWidget->Display($oPage, $aArgs);
					break;

				case 'LinkedSet':
					if ($oAttDef->GetDisplayStyle() === LINKSET_DISPLAY_STYLE_PROPERTY) {
						$sInputType = self::ENUM_INPUT_TYPE_TAGSET_LINKEDSET;
						if (array_key_exists('bulk_context', $aArgs)) {
							$oTagSetBlock = LinkSetUIBlockFactory::MakeForBulkLinkSet($iId, $oAttDef, $value, $sWizardHelperJsVarName, $aArgs['bulk_context']);
						} else {
							$oTagSetBlock = LinkSetUIBlockFactory::MakeForLinkSet($iId, $oAttDef, $value, $sWizardHelperJsVarName, $aArgs['this']);
						}
						$oTagSetBlock->SetName("attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}");
						$aEventsList[] = 'validate';
						$aEventsList[] = 'change';
						$sHTMLValue = ConsoleBlockRenderer::RenderBlockTemplateInPage($oPage, $oTagSetBlock);
					} else {
						$sInputType = self::ENUM_INPUT_TYPE_LINKEDSET;
						$oObj = $aArgs['this'] ?? null;
						if ($oAttDef->IsIndirect()) {
							$oWidget = new UILinksWidget($sClass, $sAttCode, $iId, $sNameSuffix,
								$oAttDef->DuplicatesAllowed());
						} else {
							$oWidget = new UILinksWidgetDirect($sClass, $sAttCode, $iId, $sNameSuffix);
						}
						$aEventsList[] = 'validate';
						$aEventsList[] = 'change';
						$sHTMLValue = $oWidget->Display($oPage, $value, array(), $sFormPrefix, $oObj);
					}
					break;

				case 'Document':
					$sInputType = self::ENUM_INPUT_TYPE_DOCUMENT;
					$aEventsList[] = 'validate';
					$aEventsList[] = 'change';
					$oDocument = $value; // Value is an ormDocument object

					$sFileName = '';
					if (is_object($oDocument)) {
						$sFileName = $oDocument->GetFileName();
					}
					$sFileNameForHtml = utils::EscapeHtml($sFileName);
					$bHasFile = !empty($sFileName);

					$iMaxFileSize = utils::ConvertToBytes(ini_get('upload_max_filesize'));
					$sRemoveBtnLabelForHtml = utils::EscapeHtml(Dict::S('UI:Button:RemoveDocument'));
					$sExtraCSSClassesForRemoveButton = $bHasFile ? '' : 'ibo-is-hidden';

					$sHTMLValue = <<<HTML
<div class="field_input_zone field_input_document">
	<input type="hidden" name="MAX_FILE_SIZE" value="{$iMaxFileSize}" />
	<input type="hidden" id="do_remove_{$iId}" name="attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}[remove]" value="0"/>
	<input name="attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}[filename]" type="hidden" id="{$iId}" value="{$sFileNameForHtml}"/>
	<span id="name_{$iInputId}" >{$sFileNameForHtml}</span>&#160;&#160;
	<button id="remove_attr_{$iId}" class="ibo-button ibo-is-alternative ibo-is-danger {$sExtraCSSClassesForRemoveButton}" data-role="ibo-button" type="button" data-tooltip-content="{$sRemoveBtnLabelForHtml}" onClick="$('#file_{$iId}').val(''); UpdateFileName('{$iId}', '');">
		<span class="fas fa-trash"></span>
	</button>
</div>
<input title="{$sHelpText}" name="attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}[fcontents]" type="file" id="file_{$iId}" onChange="UpdateFileName('{$iId}', this.value)"/>
{$sValidationSpan}{$sReloadSpan}
HTML;

					if ($sFileName == '') {
						$oPage->add_ready_script("$('#remove_attr_{$iId}').addClass('ibo-is-hidden');");
					}
					break;

				case 'Image':
					$sInputType = self::ENUM_INPUT_TYPE_IMAGE;
					$aEventsList[] = 'validate';
					$aEventsList[] = 'change';
					$oPage->LinkScriptFromAppRoot('js/edit_image.js');
					$oDocument = $value; // Value is an ormDocument objectm
					$sDefaultUrl = $oAttDef->Get('default_image');
					if (is_object($oDocument) && !$oDocument->IsEmpty()) {
						$sUrl = 'data:'.$oDocument->GetMimeType().';base64,'.base64_encode($oDocument->GetData());
					} else {
						$sUrl = null;
					}

					$sHTMLValue = "<div class=\"field_input_zone ibo-input-image-wrapper\"><div id=\"edit_$iInputId\" class=\"ibo-input-image\"></div></div>\n";
					$sHTMLValue .= "{$sValidationSpan}{$sReloadSpan}\n";

					$aEditImage = array(
						'input_name' => 'attr_'.$sFieldPrefix.$sAttCode.$sNameSuffix,
						'max_file_size' => utils::ConvertToBytes(ini_get('upload_max_filesize')),
						'max_width_px' => $oAttDef->Get('display_max_width'),
						'max_height_px' => $oAttDef->Get('display_max_height'),
						'current_image_url' => $sUrl,
						'default_image_url' => $sDefaultUrl,
						'labels' => array(
							'reset_button' => utils::EscapeHtml(Dict::S('UI:Button:ResetImage')),
							'remove_button' => utils::EscapeHtml(Dict::S('UI:Button:RemoveImage')),
							'upload_button' => !empty($sHelpText) ? $sHelpText : utils::EscapeHtml(Dict::S('UI:Button:UploadImage')),
						),
					);
					$sEditImageOptions = json_encode($aEditImage);
					$oPage->add_ready_script("$('#edit_$iInputId').edit_image($sEditImageOptions);");
					break;

				case 'StopWatch':
					$sHTMLValue = "The edition of a stopwatch is not allowed!!!";
					break;

				case 'List':
					// Not editable for now...
					$sHTMLValue = '';
					break;

				case 'One Way Password':
					$sInputType = self::ENUM_INPUT_TYPE_PASSWORD;
					$aEventsList[] = 'validate';
					$oWidget = new UIPasswordWidget($sAttCode, $iId, $sNameSuffix);
					$sHTMLValue = $oWidget->Display($oPage, $aArgs);
					// Event list & validation is handled  directly by the widget
					break;

				case 'ExtKey':
					/** @var \AttributeExternalKey $oAttDef */
					$aEventsList[] = 'validate';
					$aEventsList[] = 'change';

					if ($bPreserveCurrentValue) {
						$oAllowedValues = MetaModel::GetAllowedValuesAsObjectSet($sClass, $sAttCode, $aArgs, '', $value);
					} else {
						$oAllowedValues = MetaModel::GetAllowedValuesAsObjectSet($sClass, $sAttCode, $aArgs);
					}
					$sFieldName = $sFieldPrefix.$sAttCode.$sNameSuffix;
					$aExtKeyParams = $aArgs;
					$aExtKeyParams['iFieldSize'] = $oAttDef->GetMaxSize();
					$aExtKeyParams['iMinChars'] = $oAttDef->GetMinAutoCompleteChars();
					$sHTMLValue = UIExtKeyWidget::DisplayFromAttCode($oPage, $sAttCode, $sClass, $oAttDef->GetLabel(),
						$oAllowedValues, $value, $iId, $bMandatory, $sFieldName, $sFormPrefix, $aExtKeyParams, false, $sInputType);
					$sHTMLValue .= "<!-- iFlags: $iFlags bMandatory: $bMandatory -->\n";

					$bHasExtKeyUpdatingRemoteClassFields = (
						array_key_exists('replaceDependenciesByRemoteClassFields', $aArgs)
						&& ($aArgs['replaceDependenciesByRemoteClassFields'])
					);
					if ($bHasExtKeyUpdatingRemoteClassFields) {
						// On this field update we need to update all the corresponding remote class fields
						// Used when extkey widget is in a linkedset indirect
						$sWizardHelperJsVarName = $aArgs['wizHelperRemote'];
						$aDependencies = $aArgs['remoteCodes'];
					}

					break;

				case 'RedundancySetting':
					$sHTMLValue .= '<div id="'.$iId.'">';
					$sHTMLValue .= $oAttDef->GetDisplayForm($value, $oPage, true);
					$sHTMLValue .= '</div>';
					$sHTMLValue .= '<div>'.$sValidationSpan.$sReloadSpan.'</div>';
					$oPage->add_ready_script("$('#$iId :input').on('keyup change validate', function(evt, sFormId) { return ValidateRedundancySettings('$iId',sFormId); } );"); // Custom validation function
					break;

				case 'CustomFields':
				case 'FormField':
					if ($sAttDefEditClass === 'CustomFields') {
						/** @var \ormCustomFieldsValue $value */
						$oForm = $value->GetForm($sFormPrefix);
					} else if ($sAttDefEditClass === 'FormField') {
						$oForm = $oAttDef->GetForm($aArgs['this'], $sFormPrefix);
					}

					$oFormRenderer = new ConsoleFormRenderer($oForm);
					$aFormRenderedContent = $oFormRenderer->Render();

					$aFieldSetOptions = array(
						'field_identifier_attr' => 'data-field-id',
						// convention: fields are rendered into a div and are identified by this attribute
						'fields_list'           => $aFormRenderedContent,
						'fields_impacts'        => $oForm->GetFieldsImpacts(),
						'form_path'             => $oForm->GetId(),
					);
					$sFieldSetOptions = json_encode($aFieldSetOptions);
					$aFormHandlerOptions = array(
						'wizard_helper_var_name' => 'oWizardHelper'.$sFormPrefix,
						'custom_field_attcode'   => $sAttCode,
					);
					$sFormHandlerOptions = json_encode($aFormHandlerOptions);

					$sHTMLValue .= '<div id="'.$iId.'_console_form">';
					$sHTMLValue .= '<div id="'.$iId.'_field_set">';
					$sHTMLValue .= '</div></div>';
					$sHTMLValue .= '<div>'.$sReloadSpan.'</div>'; // No validation span for this one: it does handle its own validation!
					$sHTMLValue .= "<input name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" type=\"hidden\" id=\"$iId\" value=\"\"/>\n";
					$oPage->LinkScriptFromAppRoot('js/form_handler.js');
					$oPage->LinkScriptFromAppRoot('js/console_form_handler.js');
					$oPage->LinkScriptFromAppRoot('js/field_set.js');
					$oPage->LinkScriptFromAppRoot('js/form_field.js');
					$oPage->LinkScriptFromAppRoot('js/subform_field.js');
					$oPage->add_ready_script(
						<<<JS
$('#{$iId}_field_set').field_set($sFieldSetOptions);

$('#{$iId}_console_form').console_form_handler($sFormHandlerOptions);
$('#{$iId}_console_form').console_form_handler('alignColumns');
$('#{$iId}_console_form').console_form_handler('option', 'field_set', $('#{$iId}_field_set'));
// field_change must be processed to refresh the hidden value at anytime
$('#{$iId}_console_form').on('value_change', function() { $('#{$iId}').val(JSON.stringify($('#{$iId}_field_set').triggerHandler('get_current_values'))); });
// Initialize the hidden value with current state
// update_value is triggered when preparing the wizard helper object for ajax calls
$('#{$iId}').on('update_value', function() { $(this).val(JSON.stringify($('#{$iId}_field_set').triggerHandler('get_current_values'))); });
// validate is triggered by CheckFields, on all the input fields, once at page init and once before submitting the form
$('#{$iId}').on('validate', function(evt, sFormId) {
    $(this).val(JSON.stringify($('#{$iId}_field_set').triggerHandler('get_current_values')));
    return ValidateCustomFields('$iId', sFormId); // Custom validation function
});
JS
					);

					break;

				case 'Set':
				case 'TagSet':
					$sInputType = self::ENUM_INPUT_TYPE_TAGSET;
					$oPage->LinkScriptFromAppRoot('js/selectize.min.js');
					$oPage->LinkStylesheetFromAppRoot('css/selectize.default.css');
					$oPage->LinkScriptFromAppRoot('js/jquery.itop-set-widget.js');

					$oPage->add_dict_entry('Core:AttributeSet:placeholder');

					/** @var \ormSet $value */
					$sJson = $oAttDef->GetJsonForWidget($value, $aArgs);
					$sEscapedJson = utils::EscapeHtml($sJson);
					$sSetInputName = "attr_{$sFormPrefix}{$sAttCode}";

					// handle form validation
					$aEventsList[] = 'change';
					$aEventsList[] = 'validate';
					$sNullValue = '';
					$sFieldToValidateId = $sFieldToValidateId.AttributeSet::EDITABLE_INPUT_ID_SUFFIX;

					// generate form HTML output
					$sValidationSpan = "<span class=\"form_validation ibo-field-validation\" id=\"v_{$sFieldToValidateId}\"></span>";
					$sHTMLValue = '<div class="field_input_zone field_input_set ibo-input-wrapper ibo-input-tagset-wrapper" data-validation="untouched"><input id="'.$iId.'" name="'.$sSetInputName.'" type="hidden" value="'.$sEscapedJson.'"></div>'.$sValidationSpan.$sReloadSpan;
					$sScript = "$('#$iId').set_widget({inputWidgetIdSuffix: '".AttributeSet::EDITABLE_INPUT_ID_SUFFIX."'});";
					$oPage->add_ready_script($sScript);

					break;

				case 'String':
				default:
					$aEventsList[] = 'validate';
					// #@# todo - add context information (depending on dimensions)
					$aAllowedValues = $oAttDef->GetAllowedValues($aArgs);
					$iFieldSize = $oAttDef->GetMaxSize();
					if ($aAllowedValues !== null)
					{
						// Discrete list of values, use a SELECT or RADIO buttons depending on the config
						$sDisplayStyle = $oAttDef->GetDisplayStyle();
						switch ($sDisplayStyle)
						{
							case 'radio':
							case 'radio_horizontal':
							case 'radio_vertical':
								$sInputType = self::ENUM_INPUT_TYPE_RADIO;
								$aEventsList[] = 'change';
								$sHTMLValue = "<div class=\"field_input_zone field_input_{$sDisplayStyle}\">";
								$bVertical = ($sDisplayStyle != 'radio_horizontal');
								$sHTMLValue .= $oPage->GetRadioButtons($aAllowedValues, $value, $iId,
									"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}", $bMandatory, $bVertical, '');
								$sHTMLValue .= "</div>{$sValidationSpan}{$sReloadSpan}\n";
								break;

							case 'select':
							default:
								$sInputType = self::ENUM_INPUT_TYPE_DROPDOWN_RAW;
								$aEventsList[] = 'change';
								$sHTMLValue = "<div class=\"field_input_zone field_input_string ibo-input-wrapper ibo-input-select-wrapper\" data-validation=\"untouched\"><select class=\"ibo-input ibo-input-select\" title=\"$sHelpText\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" id=\"$iId\">\n";
								$sHTMLValue .= "<option value=\"\">".Dict::S('UI:SelectOne')."</option>\n";
								foreach ($aAllowedValues as $key => $display_value) {
									if ((count($aAllowedValues) == 1) && ($bMandatory == 'true')) {
										// When there is only once choice, select it by default
										if ($value != $key) {
											$oPage->add_ready_script(
												<<<EOF
$('#$iId').attr('data-validate','dependencies');
EOF
											);
										}
										$sSelected = ' selected';
									} else {
										$sSelected = ($value == $key) ? ' selected' : '';
									}
									$sHTMLValue .= "<option value=\"$key\"$sSelected>$display_value</option>\n";
								}
								$sHTMLValue .= "</select></div>{$sValidationSpan}{$sReloadSpan}\n";
								break;
						}
					}
					else
					{
						$sInputType = self::ENUM_INPUT_TYPE_SINGLE_INPUT;
						$sDisplayValueForHtml = utils::EscapeHtml($sDisplayValue);

						// Adding tooltip so we can read the whole value when its very long (eg. URL)
                        $sTip = '';
						if (!empty($sDisplayValue)) {
							$sTip = 'data-tooltip-content="'.$sDisplayValueForHtml.'"';
							$oPage->add_ready_script(<<<JS
								$('#{$iId}').on('keyup', function(evt, sFormId){ 
									let sVal = $('#{$iId}').val();
									const oTippy = this._tippy;
									
									if(sVal === '')
									{
										oTippy.hide();
										oTippy.disable(); 
									}
									else
									{
										oTippy.enable(); 
									}
									oTippy.setContent(sVal);
								});
JS
							);
						}


						$sHTMLValue = <<<HTML
<div class="field_input_zone ibo-input-wrapper ibo-input-string-wrapper" data-validation="untouched">
	<input class="ibo-input ibo-input-string" title="{$sHelpText}" type="text" maxlength="{$iFieldSize}" name="attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}" value="{$sDisplayValueForHtml}" id="{$iId}" {$sTip} />
</div>
{$sValidationSpan}{$sReloadSpan}
HTML;
						$aEventsList[] = 'keyup';
						$aEventsList[] = 'change';

					}
					break;
			}
			$sPattern = addslashes($oAttDef->GetValidationPattern()); //'^([0-9]+)$';			
			if (!empty($aEventsList))
			{
				if (!is_numeric($sNullValue))
				{
					$sNullValue = "'$sNullValue'"; // Add quotes to turn this into a JS string if it's not a number
				}
				$sOriginalValue = ($iFlags & OPT_ATT_MUSTCHANGE) ? json_encode($value) : 'undefined';
				$sEventList = implode(' ', $aEventsList);
				$oPage->add_ready_script(<<<JS
$('#$sFieldToValidateId')
	.on('$sEventList', function(oEvent, sFormId) {
			// Bind to a custom event: validate
			return ValidateField('$sFieldToValidateId', '$sPattern', $bMandatory, sFormId, $sNullValue, $sOriginalValue);
		} 
	); 
JS
				);
			}

			// handle dependent fields updates (init for WizardHelper JS object)
			if (count($aDependencies) > 0)
			{
				//--- Add an event handler to launch a custom event: validate
				// * Unbind first to avoid duplicate event handlers in case of reload of the whole (or part of the) form
				// * We were using off/on directly on the node before, but that was causing an issue when adding dynamically new nodes
				//   indeed the events weren't attached on the of the new nodes !
				//   So we're adding the handler on a node above, and we're using a selector to catch only the event we're interested in !
				$sDependencies = implode("','", $aDependencies);

				$oPage->add_ready_script(<<<JS
$('div#field_{$iId}')
	.off('change.dependencies', '#$iId') 
	.on('change.dependencies', '#$iId', 
		function(evt, sFormId) { 
			return $sWizardHelperJsVarName.UpdateDependentFields(['$sDependencies']); 
		} 
	);
JS
				);
			}
		}
		$oPage->add_dict_entry('UI:ValueMustBeSet');
		$oPage->add_dict_entry('UI:ValueMustBeChanged');
		$oPage->add_dict_entry('UI:ValueInvalidFormat');

		// N°3750 refresh container data-input-type attribute if in an Ajax context
		// indeed in such a case we're only returning the field value content and not the parent container, so we need to update it !
		if (utils::IsXmlHttpRequest()) {
			// We are refreshing the data attribute only with the .attr() method
			// So any consumer that want to get this attribute value MUST use `.attr()` and not `.data()`
			// Actually the later uses a dedicated memory (that is initialized by the DOM values on page loading)
			// Whereas `.attr()` uses the DOM directly
			$oPage->add_init_script('$("[data-input-id=\''.$iId.'\']").attr("data-input-type", "'.$sInputType.'");');
		}
		//TODO 3.0 remove the data-attcode attribute (either because it's has been moved to .field_container in 2.7 or even better because the admin. console has been reworked)
		return "<div id=\"field_{$iId}\" class=\"field_value_container\"><div class=\"attribute-edit\" data-attcode=\"$sAttCode\">{$sHTMLValue}</div></div>";
	}

	/**
	 * @param WebPage $oPage
	 * @param array $aExtraParams
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function DisplayModifyForm(WebPage $oPage, $aExtraParams = array())
	{
		$sOwnershipToken = null;
		$iKey = $this->GetKey();
		$sClass = get_class($this);

		$this->SetDisplayMode(($iKey > 0) ? static::ENUM_DISPLAY_MODE_EDIT : static::ENUM_DISPLAY_MODE_CREATE);
		$sDisplayMode = $this->GetDisplayMode();

		if ($this->GetDisplayMode() === static::ENUM_DISPLAY_MODE_EDIT)
		{
			// The concurrent access lock makes sense only for already existing objects
			$LockEnabled = MetaModel::GetConfig()->Get('concurrent_lock_enabled');
			if ($LockEnabled)
			{
				$sOwnershipToken = utils::ReadPostedParam('ownership_token', null, 'raw_data');
				if ($sOwnershipToken !== null)
				{
					// We're probably inside something like "apply_modify" where the validation failed and we must prompt the user again to edit the object
					// let's extend our lock
				}
				else
				{
					$aLockInfo = iTopOwnershipLock::AcquireLock($sClass, $iKey);
					if ($aLockInfo['success'])
					{
						$sOwnershipToken = $aLockInfo['token'];
					}
					else
					{
						// If the object is locked by the current user, it's worth trying again, since
						// the lock may be released by 'onunload' which is called AFTER loading the current page.
						//$bTryAgain = $oOwner->GetKey() == UserRights::GetUserId();
						self::ReloadAndDisplay($oPage, $this, array('operation' => 'modify'));

						return;
					}
				}
			}
		}

		self::$iGlobalFormId++;
		$this->aFieldsMap = array();
		$sPrefix = '';
		if (isset($aExtraParams['formPrefix'])) {
			$sPrefix = $aExtraParams['formPrefix'];
		}

		$this->m_iFormId = $sPrefix.self::$iGlobalFormId;
		$oAppContext = new ApplicationContext();
		if (!isset($aExtraParams['action'])) {
			$sFormAction = utils::GetAbsoluteUrlAppRoot().'pages/'.$this->GetUIPage(); // No parameter in the URL, the only parameter will be the ones passed through the form
		} else {
			$sFormAction = $aExtraParams['action'];
		}
		// Custom label for the apply button ?
		if (isset($aExtraParams['custom_button'])) {
			$sApplyButton = $aExtraParams['custom_button'];
		} else {
			if ($this->GetDisplayMode() === static::ENUM_DISPLAY_MODE_EDIT) {
				$sApplyButton = Dict::S('UI:Button:Apply');
			} else {
				$sApplyButton = Dict::S('UI:Button:Create');
			}
		}
		// Custom operation for the form ?
        if (isset($aExtraParams['custom_operation'])) {
            $sOperation = $aExtraParams['custom_operation'];
        } else {
            if ($this->GetDisplayMode() === static::ENUM_DISPLAY_MODE_EDIT) {
                $sOperation = 'apply_modify';
            } else {
                $sOperation = 'apply_new';
            }
        }

        $oContentBlock = new UIContentBlock();
        $oPage->AddUiBlock($oContentBlock);

        $oForm = new Form("form_{$this->m_iFormId}");
        $oForm->SetAction($sFormAction);
        $sOnSubmitForm = "let bOnSubmitForm = OnSubmit('form_{$this->m_iFormId}');";
        if (isset($aExtraParams['js_handlers']['form_on_submit'])) {
            $oForm->SetOnSubmitJsCode($sOnSubmitForm . $aExtraParams['js_handlers']['form_on_submit']);
        } else {
            $oForm->SetOnSubmitJsCode($sOnSubmitForm . "return bOnSubmitForm;");
        }
        $oContentBlock->AddSubBlock($oForm);

        if ($this->GetDisplayMode() === static::ENUM_DISPLAY_MODE_EDIT) {
            // The object already exists in the database, it's a modification
	        $oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('id', $iKey, "{$sPrefix}_id"));
        }
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('operation', $sOperation));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('class', $sClass));

		// Add transaction ID
		$iTransactionId = isset($aExtraParams['transaction_id']) ? $aExtraParams['transaction_id'] : utils::GetNewTransactionId();
		$oPage->SetTransactionId($iTransactionId);
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('transaction_id', $iTransactionId));

		// Add temporary object watchdog (only on root form)
		if (!utils::IsXmlHttpRequest()) {
			$oPage->add_ready_script(TemporaryObjectHelper::GetWatchDogJS($iTransactionId));
		}

		// TODO 3.0.0: Is this (the if condition, not the code inside) still necessary?
		if (isset($aExtraParams['wizard_container']) && $aExtraParams['wizard_container']) {
			$sClassLabel = MetaModel::GetName($sClass);
			if ($this->GetDisplayMode() == static::ENUM_DISPLAY_MODE_CREATE) {
				$oPage->set_title(Dict::Format('UI:CreationPageTitle_Class', $sClassLabel)); // Set title will take care of the encoding
			} else {
				$oPage->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $this->GetRawName(), $sClassLabel)); // Set title will take care of the encoding
			}
		}

        $oToolbarButtons = ToolbarUIBlockFactory::MakeStandard(null);

        $oCancelButton = ButtonUIBlockFactory::MakeForCancel();
        $oCancelButton->AddCSSClasses(['action', 'cancel']);
        $oToolbarButtons->AddSubBlock($oCancelButton);
        $oApplyButton = ButtonUIBlockFactory::MakeForPrimaryAction($sApplyButton, null, null, true);
        $oApplyButton->AddCSSClass('action');
        $oToolbarButtons->AddSubBlock($oApplyButton);
        $bAreTransitionsHidden = isset($aExtraParams['hide_transitions']) && $aExtraParams['hide_transitions'] === true;
        $aTransitions = $this->EnumTransitions();
        if (!isset($aExtraParams['custom_operation']) && !$bAreTransitionsHidden && count($aTransitions)) {
            // Transitions are displayed only for the standard new/modify actions, not for modify_all or any other case...
            $oSetToCheckRights = DBObjectSet::FromObject($this);

            $oTransitionPopoverMenu = new PopoverMenu();
            $sTPMSectionId = 'transitions';
            $oTransitionPopoverMenu->AddSection($sTPMSectionId);
            $aStimuli = Metamodel::EnumStimuli($sClass);
            foreach ($aTransitions as $sStimulusCode => $aTransitionDef) {
                $iActionAllowed = (get_class($aStimuli[$sStimulusCode]) == 'StimulusUserAction') ? UserRights::IsStimulusAllowed($sClass,
                    $sStimulusCode, $oSetToCheckRights) : UR_ALLOWED_NO;
                switch ($iActionAllowed) {
                    case UR_ALLOWED_YES:
                        // Button to be displayed on its own on large screens
                        $oButton = ButtonUIBlockFactory::MakeForPrimaryAction($aStimuli[$sStimulusCode]->GetLabel(), 'next_action', $sStimulusCode, true);
                        $oButton->AddCSSClass('action');
                        $oButton->SetColor(Button::ENUM_COLOR_SCHEME_NEUTRAL);
                        $oToolbarButtons->AddSubBlock($oButton);

						// Button to be displayed in a grouped button on smaller screens
						$oTPMPopupMenuItem = new JSPopupMenuItem('next_action--'.$oButton->GetId(), $oButton->GetLabel(), "$(`#{$oButton->GetId()}`).trigger(`click`);");
						$oTransitionPopoverMenu->AddItem($sTPMSectionId, new JsPopoverMenuItem($oTPMPopupMenuItem));
						break;

					default:
						// Do nothing
				}
			}

			// If there are some allowed transitions, build the grouped button
			if ($oTransitionPopoverMenu->HasItems()) {
				$oApplyForButtonGroup = ButtonUIBlockFactory::MakeForPrimaryAction($oApplyButton->GetLabel(), null, null, true);
				$oApplyAndTransitionsButtonGroup = ButtonGroupUIBlockFactory::MakeButtonWithOptionsMenu($oApplyForButtonGroup, $oTransitionPopoverMenu)
					->SetIsHidden(true);
				$oToolbarButtons->AddSubBlock($oApplyAndTransitionsButtonGroup);
			}
		}

		$sStatesSelection = '';
		if (!isset($aExtraParams['custom_operation']) && $this->IsNew())
		{
			$aInitialStates = MetaModel::EnumInitialStates($sClass);
			//$aInitialStates = array('new' => 'foo', 'closed' => 'bar');
			if (count($aInitialStates) > 1)
			{
				$sStatesSelection = Dict::Format('UI:Create_Class_InState',
						MetaModel::GetName($sClass)).'<select name="obj_state" class="state_select_'.$this->m_iFormId.'">';
				foreach($aInitialStates as $sStateCode => $sStateData)
				{
					$sSelected = '';
					if ($sStateCode == $this->GetState()) {
						$sSelected = ' selected';
					}
					$sStatesSelection .= '<option value="'.$sStateCode.'" '.$sSelected.'>'.MetaModel::GetStateLabel($sClass,
							$sStateCode).'</option>';
				}
				$sStatesSelection .= '</select>';
				$sStatesSelection .= '<input type="hidden" id="obj_state_orig" name="obj_state_orig" value="'.$this->GetState().'"/>';
				$oPage->add_ready_script(<<<JS
$('.state_select_{$this->m_iFormId}').change( function() {
	if ($('#obj_state_orig').val() != $(this).val()) {
		$('.state_select_{$this->m_iFormId}').val($(this).val());
		$('#form_{$this->m_iFormId}').data('force_submit', true);
		$('#form_{$this->m_iFormId}').submit();
	}
});
JS
				);
			}
		}

		// Prepare blocker protection to avoid loosing data
		$sBlockerId = $sClass.':'.$iKey; // Important: This must have the synthax format as in js/layouts/activity-panel/activity-panel.js
		$sJSToken = json_encode($sOwnershipToken);
		$oPage->add_ready_script(<<<JS
// Try to release concurrent lock when leaving the page
$(window).on('unload',function() { return OnUnload('$iTransactionId', '$sClass', $iKey, $sJSToken) } );

// Leave handler for the current form (check if in a modal or not)
// Note: We use a self-invoking function to avoid making unique vars. names to avoid collision (this can be called multiple time if modal forms are displayed)
(function () {
	const sBlockerId = '{$sBlockerId}';
	
	// Register blocker for the whole form even though it has not been touched yet.
	// Note: This is a known limitation of the backoffice forms, which will be handled during the whole form SDK refactoring (hopefully summer '23).
	//       For now we have no way of knowing if a field (**of any type**) has been touched, so we consider the whole form has dirty no matter what 
	// - On page leave
	$('body').trigger('register_blocker.itop', {
		'sBlockerId': sBlockerId,
		'sTargetElemSelector': 'document',
		'oTargetElemSelector': document,
		'sEventName': 'beforeunload'
	});
	
	// - On modal close if we are in one
	const oModalElem = $('#{$oForm->GetId()}').closest('[data-role="ibo-modal"]');
	if (oModalElem.length !== 0) {
		$('body').trigger('register_blocker.itop', {
			'sBlockerId': sBlockerId,
			'sTargetElemSelector': '#' + oModalElem.attr('id'),
			'oTargetElemSelector': '#' + oModalElem.attr('id'),
			'sEventName': 'dialogbeforeclose'
		});
	}
	
	// Unregister blockers if any action button has been clicked (cancel, submit, transition, custom operation)
	// Important 1: The listener MUST be on the buttons directly (instead of on the toolbar with a filter on listener) as we need this to be call as early as possible, we can't wait for the event to bubble.
	//              Otherwise the buttons action listener will be triggered first.
	// Important 2: This must be declared BEFORE the cancel button callback in order to be triggered first as well
	$('#{$oToolbarButtons->GetId()}').find('button, a').on('click', function () {
		$('body').trigger('unregister_blocker.itop', {
			'sBlockerId': sBlockerId
		});
	});
})();
JS
		);

		if (isset($aExtraParams['nbBulkObj'])) {
			$sTitle = Dict::Format('UI:Modify_M_ObjectsOf_Class_OutOf_N', $aExtraParams['nbBulkObj'], $sClass, $aExtraParams['nbBulkObj']);
			$sClassIcon = MetaModel::GetClassIcon($sClass, false);
			$oObjectDetails = PanelUIBlockFactory::MakeForClass($sClass, $sTitle);
			$oObjectDetails->SetIcon($sClassIcon);
			$oToolbarButtons->AddCSSClass('ibo-toolbar--button');
		} else {
			$oObjectDetails = ObjectFactory::MakeDetails($this, $this->GetDisplayMode());
			$oToolbarButtons->AddCSSClass('ibo-toolbar-top');
			$oObjectDetails->AddToolbarBlock($oToolbarButtons);
			// Allow form title customization
			if (array_key_exists('form_title', $aExtraParams) && $aExtraParams['form_title'] !== null) {
				$oObjectDetails->SetTitle($aExtraParams['form_title']);
			}
		}

		$oForm->AddSubBlock($oObjectDetails);
		if (isset($aExtraParams['nbBulkObj'])) {
			// if bulk modify buttons must be after object display
			$oForm->AddSubBlock($oToolbarButtons);
		}
		$oPage->AddTabContainer(OBJECT_PROPERTIES_TAB, $sPrefix, $oObjectDetails);
		$oPage->SetCurrentTabContainer(OBJECT_PROPERTIES_TAB);
		$oPage->SetCurrentTab('UI:PropertiesTab');

		$oPage->p($sStatesSelection);

		$aFieldsMap = $this->DisplayBareProperties($oPage, true, $sPrefix, $aExtraParams);
		//if we are in bulk modify : Special case to display the case log, if any...
		// WARNING: if you modify the loop below, also check the corresponding code in UpdateObject and DisplayModifyForm
		if (isset($aExtraParams['nbBulkObj'])) {
			foreach (MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode => $oAttDef) {
				if ($oAttDef instanceof AttributeCaseLog) {
					$sComment = (isset($aExtraParams['fieldsComments'][$sAttCode])) ? $aExtraParams['fieldsComments'][$sAttCode] : '';
					$this->DisplayCaseLogForBulkModify($oPage, $sAttCode, $sComment, $sPrefix);
					$aFieldsMap[$sAttCode] = $this->m_iFormId.'_'.$sAttCode;
				}
			}
		}

		if (!is_array($aFieldsMap)) {
			$aFieldsMap = array();
		}
		if ($this->GetDisplayMode() === static::ENUM_DISPLAY_MODE_EDIT) {
			$aFieldsMap['id'] = $sPrefix.'_id';
		}
		// Now display the relations, one tab per relation
		if (!isset($aExtraParams['noRelations'])) {
			$this->DisplayBareRelations($oPage, true); // Edit mode, will fill $this->aFieldsMap
			$aFieldsMap = array_merge($aFieldsMap, $this->aFieldsMap);
		}

		$oPage->SetCurrentTab('');

		// Static fields values for wizard helper serialization
		$aWizardHelperStaticValues = [];

		// Add as hidden inputs values that we want displayed if they're readonly
		if(isset($aExtraParams['forceFieldsSubmission'])){
			$aExtraFlags = $aExtraParams['fieldsFlags'] ?? [];
			foreach ($aExtraParams['forceFieldsSubmission'] as $sAttCode) {
					if(FormHelper::GetAttributeFlagsForObject($this, $sAttCode, $aExtraFlags) & OPT_ATT_READONLY) {
						$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('attr_'.$sPrefix.$sAttCode, $this->Get($sAttCode)));
						$aWizardHelperStaticValues[$sAttCode] = $this->Get($sAttCode);
					}
			}
		}
		$sWizardHelperStaticValues = json_encode($aWizardHelperStaticValues);

		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('class', $sClass));
		$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('transaction_id', $iTransactionId));
		foreach ($aExtraParams as $sName => $value) {
			if (is_scalar($value)) {
				$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden($sName, $value));
			}
		}
		if ($sOwnershipToken !== null) {
			$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('ownership_token', utils::HtmlEntities($sOwnershipToken)));
		}
		$oPage->add($oAppContext->GetForForm());

		// Hook the cancel button via jQuery so that it can be unhooked easily as well if needed
		$sDefaultUrl = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=search_form&class='.$sClass.'&'.$oAppContext->GetForLink();

		$sCancelButtonOnClickScript = "let fOnClick{$this->m_iFormId}CancelButton = ";
		if(isset($aExtraParams['js_handlers']['cancel_button_on_click'])){
			$sCancelButtonOnClickScript .= $aExtraParams['js_handlers']['cancel_button_on_click'];
		} else {
			$sCancelButtonOnClickScript .= "function() { BackToDetails('$sClass', $iKey, '$sDefaultUrl', $sJSToken)};";
		}
		$sCancelButtonOnClickScript .= "$('#form_{$this->m_iFormId} button.cancel').on('click', fOnClick{$this->m_iFormId}CancelButton);";
		$oPage->add_ready_script($sCancelButtonOnClickScript);

		$iFieldsCount = count($aFieldsMap);
		$sJsonFieldsMap = json_encode($aFieldsMap);
		$sLifecycleStateForWizardHelper = '';
		if (MetaModel::HasLifecycle($sClass)) {
			$sLifecycleStateForWizardHelper = $this->GetState();
		}
		$sSessionStorageKey = $sClass.'_'.$iKey;
		$sTempId = utils::GetUploadTempId($iTransactionId);
		$oPage->add_ready_script(InlineImage::EnableCKEditorImageUpload($this, $sTempId));

		$oPage->add_script(
			<<<EOF
		sessionStorage.removeItem('$sSessionStorageKey');
		
		// Create the object once at the beginning of the page...
		var oWizardHelper$sPrefix = new WizardHelper('$sClass', '$sPrefix', '$sLifecycleStateForWizardHelper');
		oWizardHelper$sPrefix.SetFieldsMap($sJsonFieldsMap);
		oWizardHelper$sPrefix.SetFieldsCount($iFieldsCount);
		oWizardHelper$sPrefix.SetStaticValues($sWizardHelperStaticValues);
EOF
		);
		$oPage->add_ready_script(
			<<<EOF
		oWizardHelper$sPrefix.UpdateWizard();
		// Starts the validation when the page is ready
		CheckFields('form_{$this->m_iFormId}', false);

EOF
		);
		if ($sOwnershipToken !== null)
		{
			$this->GetOwnershipJSHandler($oPage, $sOwnershipToken);
		}
		else
		{
			// Probably a new object (or no concurrent lock), let's add a watchdog so that the session is kept open while editing
			$iInterval = MetaModel::GetConfig()->Get('concurrent_lock_expiration_delay') * 1000 / 2;
			if ($iInterval > 0)
			{
				$iInterval = max(MIN_WATCHDOG_INTERVAL * 1000,
					$iInterval); // Minimum interval for the watchdog is MIN_WATCHDOG_INTERVAL
				$oPage->add_ready_script(
					<<<EOF
				window.setInterval(function() {
					$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', {operation: 'watchdog'});
				}, $iInterval);
EOF
				);
			}
		}
	}

	/**
	 *  Select the derived class to create
	 * @param string $sClass
	 * @param WebPage $oP
	 * @param \ApplicationContext $oAppContext
	 * @param array $aPossibleClasses
	 * @param array $aHiddenFields
	 *
	 * @return void
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 *
	 * @since 3.0.0
	 */
	public static function DisplaySelectClassToCreate(string $sClass, WebPage $oP, ApplicationContext $oAppContext, array $aPossibleClasses, array $aHiddenFields)
	{
		$sClassLabel = MetaModel::GetName($sClass);
		$sTitle = Dict::Format('UI:CreationTitle_Class', $sClassLabel);

		$oP->set_title($sTitle);
		$sClassIconUrl = MetaModel::GetClassIcon($sClass, false);
		$oPanel = PanelUIBlockFactory::MakeForClass($sClass, $sTitle)
			->SetIcon($sClassIconUrl);
		$oPanel->AddMainBlock(self::DisplayFormBlockSelectClassToCreate($sClass, $sClassLabel, $oAppContext, $aPossibleClasses, $aHiddenFields));

		$oP->AddSubBlock($oPanel);
	}

	/**
	 * @param string $sClass
	 * @param string $sClassLabel
	 * @param array $aPossibleClasses
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Form\Form
	 * @throws \CoreException
	 */
	public static function DisplayFormBlockSelectClassToCreate( string $sClass, string $sClassLabel, ApplicationContext $oAppContext, array $aPossibleClasses, array $aHiddenFields): Form
	{
		$oClassForm = FormUIBlockFactory::MakeStandard();

		$oClassForm->AddHtml($oAppContext->GetForForm())
			->AddSubBlock(InputUIBlockFactory::MakeForHidden('checkSubclass', '0'))
			->AddSubBlock(InputUIBlockFactory::MakeForHidden('operation', 'new'));

		foreach ($aHiddenFields as $sKey => $sValue) {
			if (is_scalar($sValue)) {
				$oClassForm->AddSubBlock(InputUIBlockFactory::MakeForHidden($sKey, $sValue));
			}
		}

		$aDefaults = utils::ReadParam('default', array(), false, 'raw_data');
		foreach ($aDefaults as $key => $value) {
			if (is_array($value)) {
				foreach ($value as $key2 => $value2) {
					if (is_array($value2)) {
						foreach ($value2 as $key3 => $value3) {
							$sValue = utils::EscapeHtml($value3);
							$oClassForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("default[$key][$key2][$key3]", $sValue));
						}
					} else {
						$sValue = utils::EscapeHtml($value2);
						$oClassForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("default[$key][$key2]", $sValue));
					}
				}
			} else {
				$sValue = utils::EscapeHtml($value);
				$oClassForm->AddSubBlock(InputUIBlockFactory::MakeForHidden("default[$key]", $sValue));
			}
		}

		$oClassForm->AddSubBlock(self::DisplayBlockSelectClassToCreate($sClass, $sClassLabel, $aPossibleClasses));
		return $oClassForm;
	}
	/**
	 * @param string $sClassLabel
	 * @param array $aPossibleClasses
	 * @param string $sClass
	 *
	 * @return UIContentBlock
	 * @throws \CoreException
	 */
	public static function DisplayBlockSelectClassToCreate( string $sClass, string $sClassLabel,array $aPossibleClasses): UIContentBlock
	{
		$oBlock= UIContentBlockUIBlockFactory::MakeStandard();
		$oBlock->AddSubBlock(HtmlFactory::MakeRaw(Dict::Format('UI:SelectTheTypeOf_Class_ToCreate', $sClassLabel)));
		$oSelect = SelectUIBlockFactory::MakeForSelect('class');
		$oBlock->AddSubBlock($oSelect);
		asort($aPossibleClasses);
		foreach ($aPossibleClasses as $sClassName => $sClassLabel) {
			$oSelect->AddOption(SelectOptionUIBlockFactory::MakeForSelectOption($sClassName, $sClassLabel, ($sClassName == $sClass)));
		}

		$oToolbar = ToolbarUIBlockFactory::MakeForAction();
		$oBlock->AddSubBlock($oToolbar);
		$oToolbar->AddSubBlock(ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Apply'), null, null, true));
		return $oBlock;
	}
	/**
	 * @param WebPage $oPage
	 * @param string $sClass
	 * @param \DBObject|null $oSourceObject Object to use for the creation form, can be either the class to instantiate, an object to clone or an object to use (eg. already prefilled / modeled object)
	 * @param array $aArgs
	 * @param array $aExtraParams Extra parameters depending on the context, below is a WIP attempt at documenting them:
	 * [
	 *  ...
	 *  'keep_source_object' => true|false, // Whether the $oSourceObject should be kept or cloned. Default is true.
	 *  ...
	 * ]
	 *
	 * @return mixed
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public static function DisplayCreationForm(WebPage $oPage, $sClass, $oSourceObject = null, $aArgs = array(), $aExtraParams = array())
	{
		$sClass = ($oSourceObject == null) ? $sClass : get_class($oSourceObject);

		if ($oSourceObject == null) {
			$oObj = DBObject::MakeDefaultInstance($sClass);
		} elseif (isset($aExtraParams['keep_source_object']) && (true === isset($aExtraParams['keep_source_object']))) {
			$oObj = $oSourceObject;
		} else {
			$oObj = clone $oSourceObject;
		}
		$oObj->SetDisplayMode(static::ENUM_DISPLAY_MODE_CREATE);

		// Pre-fill the object with default values, when there is only on possible choice
		// AND the field is mandatory (otherwise there is always the possiblity to let it empty)
		$aArgs['this'] = $oObj;
		$aDetailsList = self::FLattenZList(MetaModel::GetZListItems($sClass, 'details'));
		// Order the fields based on their dependencies
		$aDeps = array();
		foreach($aDetailsList as $sAttCode)
		{
			$aDeps[$sAttCode] = MetaModel::GetPrerequisiteAttributes($sClass, $sAttCode);
		}
		$aList = self::OrderDependentFields($aDeps);

		// Now fill-in the fields with default/supplied values
		foreach($aList as $sAttCode)
		{
			if (isset($aArgs['default'][$sAttCode]))
			{
				$oObj->Set($sAttCode, $aArgs['default'][$sAttCode]);
			}
			else
			{
				$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);

				// If the field is mandatory, set it to the only possible value
				$iFlags = $oObj->GetInitialStateAttributeFlags($sAttCode);
				if ((!$oAttDef->IsNullAllowed()) || ($iFlags & OPT_ATT_MANDATORY))
				{
					if ($oAttDef->IsExternalKey())
					{
						/** @var DBObjectSet $oAllowedValues */
						$oAllowedValues = MetaModel::GetAllowedValuesAsObjectSet($sClass, $sAttCode, $aArgs);
						if ($oAllowedValues->CountWithLimit(2) == 1)
						{
							$oRemoteObj = $oAllowedValues->Fetch();
							$oObj->Set($sAttCode, $oRemoteObj->GetKey());
						}
					}
					else
					{
						$aAllowedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode, $aArgs);
						if (is_array($aAllowedValues) && (count($aAllowedValues) == 1))
						{
							$aValues = array_keys($aAllowedValues);
							$oObj->Set($sAttCode, $aValues[0]);
						}
					}
				}
			}
		}

		return $oObj->DisplayModifyForm($oPage, $aExtraParams);
	}

	/**
	 * @param WebPage      $oPage
	 * @param string        $sStimulus
	 * @param array|null    $aPrefillFormParam
	 * @param bool          $bDisplayBareProperties Whether to display the object details or not
	 *
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function DisplayStimulusForm(WebPage $oPage, $sStimulus, $aPrefillFormParam = null, $bDisplayBareProperties = true)
	{
		$this->SetDisplayMode(static::ENUM_DISPLAY_MODE_STIMULUS);

		$sClass = get_class($this);
		$iKey = $this->GetKey();
		$sDisplayMode = $this->GetDisplayMode();
		$iTransactionId = utils::GetNewTransactionId();

		$aTransitions = $this->EnumTransitions();
		$aStimuli = MetaModel::EnumStimuli($sClass);
		if (!isset($aTransitions[$sStimulus]))
		{
			// Invalid stimulus
			throw new ApplicationException(Dict::Format('UI:Error:Invalid_Stimulus_On_Object_In_State', $sStimulus,
				$this->GetName(), $this->GetStateLabel()));
		}

		// Check for concurrent access lock
		$LockEnabled = MetaModel::GetConfig()->Get('concurrent_lock_enabled');
		$sOwnershipToken = null;
		if ($LockEnabled)
		{
			$aLockInfo = iTopOwnershipLock::AcquireLock($sClass, $iKey);
			if ($aLockInfo['success'])
			{
				$sOwnershipToken = $aLockInfo['token'];
			}
			else
			{
				// If the object is locked by the current user, it's worth trying again, since
				// the lock may be released by 'onunload' which is called AFTER loading the current page.
				//$bTryAgain = $oOwner->GetKey() == UserRights::GetUserId();
				self::ReloadAndDisplay($oPage, $this, array('operation' => 'stimulus', 'stimulus' => $sStimulus));

				return;
			}
		}
		$sActionLabel = $aStimuli[$sStimulus]->GetLabel();
		$sActionDetails = $aStimuli[$sStimulus]->GetDescription();

		// Get info on current state
		$sCurrentState = $this->GetState();
		$sTargetState = $aTransitions[$sStimulus]['target_state'];

		$aExpectedAttributes = $this->GetTransitionAttributes($sStimulus /*, current state*/);
		if ($aPrefillFormParam != null)
		{
			$aPrefillFormParam['expected_attributes'] = $aExpectedAttributes;
			$this->PrefillForm('state_change', $aPrefillFormParam);
			$aExpectedAttributes = $aPrefillFormParam['expected_attributes'];
		}

		$aDetails = array();
		$iFieldIndex = 0;
		$aFieldsMap = [
			'id' => 'id',
		];

		// The list of candidate fields is made of the ordered list of "details" attributes + other attributes
		// First attributes from the "details" zlist as they were sorted...
		$aList = $this->FlattenZList(MetaModel::GetZListItems($sClass, 'details'));

		// ... then append forgotten attributes
		foreach (MetaModel::GetAttributesList($sClass) as $sAttCode) {
			if (!in_array($sAttCode, $aList)) {
				$aList[] = $sAttCode;
			}
		}

		$bExistFieldToDisplay = false;
		foreach ($aList as $sAttCode) {
			// Consider only the "expected" fields for the target state
			if (array_key_exists($sAttCode, $aExpectedAttributes)) {
				$iExpectCode = $aExpectedAttributes[$sAttCode];

				// Prompt for an attribute if
				// - the attribute must be changed or must be displayed to the user for confirmation
				// - or the field is mandatory and currently empty
				if (($iExpectCode & (OPT_ATT_MUSTCHANGE | OPT_ATT_MUSTPROMPT)) ||
					(($iExpectCode & OPT_ATT_MANDATORY) && (false === $this->HasAValue($sAttCode)))) {
					$aArgs = array('this' => $this);
					$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
					// If the field is mandatory, set it to the only possible value
					if ((!$oAttDef->IsNullAllowed()) || ($iExpectCode & OPT_ATT_MANDATORY)) {
						if ($oAttDef->IsExternalKey()) {
							/** @var DBObjectSet $oAllowedValues */
							$oAllowedValues = MetaModel::GetAllowedValuesAsObjectSet($sClass, $sAttCode, $aArgs, '',
								$this->Get($sAttCode));
							if ($oAllowedValues->CountWithLimit(2) == 1) {
								$oRemoteObj = $oAllowedValues->Fetch();
								$this->Set($sAttCode, $oRemoteObj->GetKey());
							}
						} else
						{
							if ($oAttDef instanceof \AttributeCaseLog) {
								// Add JS files for display caselog
								// Dummy collapsible section created in order to get JS files
								$oCollapsibleSection = new CollapsibleSection('');
								foreach ($oCollapsibleSection->GetJsFilesUrlRecursively(true) as $sJSFile) {
									$oPage->LinkScriptFromURI($sJSFile);
								}
							}
							$aAllowedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode, $aArgs);
							if (is_array($aAllowedValues) && count($aAllowedValues) == 1)
							{
								$aValues = array_keys($aAllowedValues);
								$this->Set($sAttCode, $aValues[0]);
							}
						}
					}
					$sInputType = '';
					$sInputId   = 'att_'.$iFieldIndex;
					$sHTMLValue = cmdbAbstractObject::GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef,
						$this->Get($sAttCode), $this->GetEditValue($sAttCode), $sInputId, '', $iExpectCode,
						$aArgs, true, $sInputType);
					$aAttrib    = array(
						'label' => '<span>'.$oAttDef->GetLabel().'</span>',
						'value' => "<span id=\"field_att_$iFieldIndex\">$sHTMLValue</span>",
					);

					//add attrib for data-attribute
					// Prepare metadata attributes
					$sAttCode     = $oAttDef->GetCode();
					$oAttDef      = MetaModel::GetAttributeDef($sClass, $sAttCode);
					$sAttDefClass = get_class($oAttDef);
					$sAttLabel    = MetaModel::GetLabel($sClass, $sAttCode);

					$aAttrib['attcode']  = $sAttCode;
					$aAttrib['atttype']  = $sAttDefClass;
					$aAttrib['attlabel'] = $sAttLabel;
					// - Attribute flags
					$aAttrib['attflags'] = $this->GetFormAttributeFlags($sAttCode);
					// - How the field should be rendered
					$aAttrib['layout']    = (in_array($oAttDef->GetEditClass(), static::GetAttEditClassesToRenderAsLargeField())) ? 'large' : 'small';
					$aAttrib['inputid']   = $sInputId;
					$aAttrib['inputtype'] = $sInputType;
					// - For simple fields, we get the raw (stored) value as well
					$bExcludeRawValue = false;
					foreach (static::GetAttDefClassesToExcludeFromMarkupMetadataRawValue() as $sAttDefClassToExclude) {
						if (is_a($sAttDefClass, $sAttDefClassToExclude, true)) {
							$bExcludeRawValue = true;
							break;
						}
					}
					$aAttrib['value_raw'] = ($bExcludeRawValue === false) ? $this->Get($sAttCode) : '';

					$aDetails[]            = $aAttrib;
					$aFieldsMap[$sAttCode] = $sInputId;
					$iFieldIndex++;
					$bExistFieldToDisplay = true;
				}
			}
		}

		if ($bExistFieldToDisplay || MetaModel::GetConfig()->Get('force_transition_confirmation')) {
			$oPage->set_title($sActionLabel);
			$oPage->add(<<<HTML
	<!-- Beginning of object-transition -->
	<div class="object-transition" data-object-class="$sClass" data-object-id="$iKey" data-object-mode="$sDisplayMode" data-object-current-state="$sCurrentState" data-object-target-state="$sTargetState">
HTML
			);

			// Page title and subtitles
			$oPage->AddUiBlock(TitleUIBlockFactory::MakeForPage($sActionLabel.' - '.$this->GetRawName()));
			if (!empty($sActionDetails)) {
				$oPage->AddUiBlock(TitleUIBlockFactory::MakeForPage($sActionDetails));
			}

			$oFormContainer = new UIContentBlock(null, ['ibo-wizard-container']);
			$oPage->AddUiBlock($oFormContainer);
			$oForm = new Combodo\iTop\Application\UI\Base\Component\Form\Form('apply_stimulus');
			$oFormContainer->AddSubBlock($oForm);

			$oForm->SetOnSubmitJsCode("return OnSubmit('apply_stimulus');")
				->AddSubBlock(InputUIBlockFactory::MakeForHidden('id', $this->GetKey(), 'id'))
				->AddSubBlock(InputUIBlockFactory::MakeForHidden('class', $sClass))
				->AddSubBlock(InputUIBlockFactory::MakeForHidden('operation', 'apply_stimulus'))
				->AddSubBlock(InputUIBlockFactory::MakeForHidden('stimulus', $sStimulus))
				->AddSubBlock(InputUIBlockFactory::MakeForHidden('transaction_id', $iTransactionId));

			if ($sOwnershipToken !== null) {
				$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('ownership_token', utils::HtmlEntities($sOwnershipToken)));
			}

			// Note: Remove the table if we want fields to occupy the whole width of the container, BUT with today's layout, fields' label will occupy way too much space. This should be part of the field layout rework.
			// Note 2: The hardcoded width allows the fields to be a bit wider (useful for long values) while still working on different screen sizes
			// Note 3: The inline style is not ideal but we are still wondring how transition form should be displayed
			$sHtml = '<table style="width: min(100%, 35rem); margin-bottom: 12px;"><tr><td>';
			$sHtml .= $oPage->GetDetails($aDetails);
			$sHtml .= '</td></tr></table>';

			$oAppContext = new ApplicationContext();
			$sHtml .= $oAppContext->GetForForm();
			$oForm->AddHtml($sHtml);

			$oCancelButton = ButtonUIBlockFactory::MakeForCancel(Dict::S('UI:Button:Cancel'), 'cancel', 'cancel')
				// Action type is changed on purpose so the button is more visible in the form.
				->SetActionType(Button::ENUM_ACTION_TYPE_REGULAR)
				->SetOnClickJsCode("BackToDetails('{$sClass}', '{$this->GetKey()}', '', '{$sOwnershipToken}');");
			$oForm->AddSubBlock($oCancelButton);

			$oSubmitButton = ButtonUIBlockFactory::MakeForPrimaryAction($sActionLabel, 'submit', 'submit', true);
			$oForm->AddSubBlock($oSubmitButton);

			$oPage->add(<<<HTML
	<!-- End of object-transition -->
	</div>
HTML
			);

			$iFieldsCount = count($aFieldsMap);
			$sJsonFieldsMap = json_encode($aFieldsMap);

			$oPage->add_script(
				<<<EOF
			// Initializes the object once at the beginning of the page...
			var oWizardHelper = new WizardHelper('$sClass', '', '$sTargetState', '{$this->GetState()}', '$sStimulus');
			oWizardHelper.SetFieldsMap($sJsonFieldsMap);
			oWizardHelper.SetFieldsCount($iFieldsCount);
EOF
			);
			$sJSToken = json_encode($sOwnershipToken);
			$oPage->add_ready_script(
				<<<EOF
			// Starts the validation when the page is ready
			CheckFields('apply_stimulus', false);
			$(window).on('unload', function() { return OnUnload('$iTransactionId', '$sClass', $iKey, $sJSToken) } );
EOF
			);

			if ($sOwnershipToken !== null) {
				$this->GetOwnershipJSHandler($oPage, $sOwnershipToken);
			}

			// Note: This part (inline images activation) is duplicated in self::DisplayModifyForm and several other places. Maybe it should be refactored so it automatically activates when an HTML field is present, or be an option of the attribute. See bug N°1240.
			$sTempId = utils::GetUploadTempId($iTransactionId);
			$oPage->add_ready_script(InlineImage::EnableCKEditorImageUpload($this, $sTempId));
		} else {
			//we can directly apply the stimuli
			$sExceptionMessage = null;
			try {
			$bApplyStimulus = $this->ApplyStimulus($sStimulus); // will write the object in the DB
			}
			catch (Exception $oException) {
				// Catch any exception happening during the stimulus
				$bApplyStimulus = false;
				$sExceptionMessage =   ($oException instanceof CoreCannotSaveObjectException) ? $oException->getHtmlMessage() : $oException->getMessage();
			}
			finally {
				if ($sOwnershipToken !== null) {
					// Release the concurrent lock, if any
					iTopOwnershipLock::ReleaseLock($sClass, $iKey, $sOwnershipToken);
				}
				if (!$bApplyStimulus) {
					// Throw an application oriented exception if necessary
					throw new ApplicationException($sExceptionMessage ?? Dict::S('UI:FailedToApplyStimuli'));
				} else {
					return true;
				}
			}
		}

		return false;
	}

	public static function ProcessZlist($aList, $aDetails, $sCurrentTab, $sCurrentCol, $sCurrentSet)
	{
		$index = 0;
		foreach($aList as $sKey => $value)
		{
			if (is_array($value))
			{
				if (preg_match('/^(.*):(.*)$/U', $sKey, $aMatches))
				{
					$sCode = $aMatches[1];
					$sName = $aMatches[2];
					switch ($sCode)
					{
						case 'tab':
							if (!isset($aDetails[$sName]))
							{
								$aDetails[$sName] = array('col1' => array());
							}
							$aDetails = self::ProcessZlist($value, $aDetails, $sName, 'col1', '');
							break;

						case 'fieldset':
							if (!isset($aDetailsStruct[$sCurrentTab][$sCurrentCol][$sName]))
							{
								$aDetails[$sCurrentTab][$sCurrentCol][$sName] = array();
							}
							$aDetails = self::ProcessZlist($value, $aDetails, $sCurrentTab, $sCurrentCol, $sName);
							break;

						default:
						case 'col':
							if (!isset($aDetails[$sCurrentTab][$sName]))
							{
								$aDetails[$sCurrentTab][$sName] = array();
							}
							$aDetails = self::ProcessZlist($value, $aDetails, $sCurrentTab, $sName, '');
							break;
					}
				}
			}
			else
			{
				if (empty($sCurrentSet))
				{
					$aDetails[$sCurrentTab][$sCurrentCol]['_'.$index][] = $value;
				}
				else
				{
					$aDetails[$sCurrentTab][$sCurrentCol][$sCurrentSet][] = $value;
				}
			}
			$index++;
		}

		return $aDetails;
	}

	public static function FlattenZList($aList)
	{
		$aResult = array();
		foreach($aList as $value)
		{
			if (!is_array($value))
			{
				$aResult[] = $value;
			}
			else
			{
				$aResult = array_merge($aResult, self::FlattenZList($value));
			}
		}

		return $aResult;
	}

	protected function GetFieldAsHtml($sClass, $sAttCode, $sStateAttCode)
	{
		$retVal = null;
		if ($this->IsNew()) {
			$iFlags = $this->GetInitialStateAttributeFlags($sAttCode);
		} else {
			$iFlags = $this->GetAttributeFlags($sAttCode);
		}
		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		$bIsLinkSetWithDisplayStyleTab = is_a($oAttDef, AttributeLinkedSet::class) && $oAttDef->GetDisplayStyle() === LINKSET_DISPLAY_STYLE_TAB;
		if ((($iFlags & OPT_ATT_HIDDEN) == 0) && !($oAttDef instanceof AttributeDashboard) && !$bIsLinkSetWithDisplayStyleTab) {
			// First prepare the label
			// - Attribute description
			$sDescription = $oAttDef->GetDescription();
			$sDescriptionForHTMLAttributes = utils::HtmlEntities($sDescription);
			$sDescriptionHTMLAttributes = (empty($sDescriptionForHTMLAttributes) || $sDescription === $oAttDef->GetLabel()) ? '' : 'class="ibo-has-description" data-tooltip-content="'.$sDescriptionForHTMLAttributes.'" data-tooltip-max-width="600px"';

			// - Fullscreen toggler for large fields
			$sFullscreenTogglerTooltip = Dict::S('UI:ToggleFullScreen');
			$sFullscreenTogglerHTML = (false === in_array($oAttDef->GetEditClass(), static::GetAttEditClassesToRenderAsLargeField())) ? '' : <<<HTML
<a href="#" class="ibo-field--fullscreen-toggler" data-role="ibo-field--fullscreen-toggler"
aria-label="{$sFullscreenTogglerTooltip}"
data-tooltip-content="{$sFullscreenTogglerTooltip}" data-fullscreen-toggler-target="$(this).closest('[data-role=\'ibo-field\']')"><span class="fas fa-fw fa-expand-arrows-alt"></span></a>
HTML;

			$sLabelAsHtml = '<span '.$sDescriptionHTMLAttributes.' >'.MetaModel::GetLabel($sClass, $sAttCode).'</span>'.$sFullscreenTogglerHTML;

			// Then prepare the value
			// - The field is visible in the current state of the object
			if ($oAttDef->GetEditClass() == 'Document') {
				/** @var \ormDocument $oDocument */
				$oDocument = $this->Get($sAttCode);
				if (is_object($oDocument) && !$oDocument->IsEmpty()) {
					$sFieldAsHtml = $this->GetAsHTML($sAttCode);

					$sDisplayLabel = Dict::S('UI:OpenDocumentInNewWindow_');
					$sDisplayUrl = $oDocument->GetDisplayURL(get_class($this), $this->GetKey(), $sAttCode);

					$sDownloadLabel = Dict::S('UI:DownloadDocument_');
					$sDownloadUrl = $oDocument->GetDownloadURL(get_class($this), $this->GetKey(), $sAttCode);

					$sDisplayValue = <<<HTML
{$sFieldAsHtml}
<a href="{$sDisplayUrl}" target="_blank">{$sDisplayLabel}</a> / <a href="{$sDownloadUrl}">{$sDownloadLabel}</a>
HTML;
				} else {
					$sDisplayValue = '';
				}
			} elseif ($oAttDef instanceof AttributeDashboard) {
				$sDisplayValue = '';
			} else {
				$sDisplayValue = $this->GetAsHTML($sAttCode);
			}
			$sValueAsHtml = $sDisplayValue;

			$retVal = [
				'label' => $sLabelAsHtml,
				'value' => $sValueAsHtml,
			];
		}

		return $retVal;
	}

	/**
	 * Displays a blob document *inline* (if possible, depending on the type of the document)
	 *
	 * @param WebPage $oPage
	 * @param $sAttCode
	 *
	 * @return string
	 * @throws \CoreException
	 */
	public function DisplayDocumentInline(WebPage $oPage, $sAttCode)
	{
		/** @var \ormDocument $oDoc */
		$oDoc = $this->Get($sAttCode);
		$sClass = get_class($this);
		$sId = $this->GetKey();
		$sDisplayUrl = $oDoc->GetDisplayURL($sClass, $sId, $sAttCode);
		switch ($oDoc->GetMainMimeType())
		{
			case 'text':
			case 'html':
				$data = $oDoc->GetData();
				switch ($oDoc->GetMimeType()) {
					case 'text/xml':
						$oPage->add("<iframe id='preview_$sAttCode' src=\"$sDisplayUrl\" width=\"100%\" height=\"400\">Loading...</iframe>\n");
						break;

					default:
						$oPage->add("<pre>".utils::EscapeHtml(MyHelpers::beautifulstr($data, 1000, true))."</pre>\n");
				}
				break;

			case 'application':
				switch ($oDoc->GetMimeType())
				{
					case 'application/pdf':
						$oPage->add("<iframe id='preview_$sAttCode' src=\"$sDisplayUrl\" width=\"100%\" height=\"400\">Loading...</iframe>\n");
						break;

					default:
						$oPage->add(Dict::S('UI:Document:NoPreview'));
				}
				break;

			case 'image':
				$oPage->add("<img src=\"$sDisplayUrl\" />\n");
				break;

			default:
				$oPage->add(Dict::S('UI:Document:NoPreview'));
		}
		return '';
	}

	// $m_highlightComparison[previous][new] => next value
	protected static $m_highlightComparison = array(
		HILIGHT_CLASS_CRITICAL => array(
			HILIGHT_CLASS_CRITICAL => HILIGHT_CLASS_CRITICAL,
			HILIGHT_CLASS_WARNING => HILIGHT_CLASS_CRITICAL,
			HILIGHT_CLASS_OK => HILIGHT_CLASS_CRITICAL,
			HILIGHT_CLASS_NONE => HILIGHT_CLASS_CRITICAL,
		),
		HILIGHT_CLASS_WARNING => array(
			HILIGHT_CLASS_CRITICAL => HILIGHT_CLASS_CRITICAL,
			HILIGHT_CLASS_WARNING => HILIGHT_CLASS_WARNING,
			HILIGHT_CLASS_OK => HILIGHT_CLASS_WARNING,
			HILIGHT_CLASS_NONE => HILIGHT_CLASS_WARNING,
		),
		HILIGHT_CLASS_OK => array(
			HILIGHT_CLASS_CRITICAL => HILIGHT_CLASS_CRITICAL,
			HILIGHT_CLASS_WARNING => HILIGHT_CLASS_WARNING,
			HILIGHT_CLASS_OK => HILIGHT_CLASS_OK,
			HILIGHT_CLASS_NONE => HILIGHT_CLASS_OK,
		),
		HILIGHT_CLASS_NONE => array(
			HILIGHT_CLASS_CRITICAL => HILIGHT_CLASS_CRITICAL,
			HILIGHT_CLASS_WARNING => HILIGHT_CLASS_WARNING,
			HILIGHT_CLASS_OK => HILIGHT_CLASS_OK,
			HILIGHT_CLASS_NONE => HILIGHT_CLASS_NONE,
		),
	);

	/**
	 * This function returns a 'hilight' CSS class, used to hilight a given row in a table
	 * There are currently (i.e defined in the CSS) 4 possible values HILIGHT_CLASS_CRITICAL,
	 * HILIGHT_CLASS_WARNING, HILIGHT_CLASS_OK, HILIGHT_CLASS_NONE
	 * To Be overridden by derived classes
	 *
	 * @param void
	 *
	 * @return String The desired higlight class for the object/row
	 */
	public function GetHilightClass()
	{
		// Possible return values are:
		// HILIGHT_CLASS_CRITICAL, HILIGHT_CLASS_WARNING, HILIGHT_CLASS_OK, HILIGHT_CLASS_NONE	
		$current = parent::GetHilightClass(); // Default computation

		// Invoke extensions before the deletion (the deletion will do some cleanup and we might loose some information
		/** @var \iApplicationUIExtension $oExtensionInstance */
		foreach(MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
		{
			$new = $oExtensionInstance->GetHilightClass($this);
			@$current = self::$m_highlightComparison[$current][$new];
		}

		return $current;
	}

	/**
	 * Re-order the fields based on their inter-dependencies
	 *
	 * @params hash @aFields field_code => array_of_depencies
	 *
	 * @param $aFields
	 * @return array Ordered array of fields or throws an exception
	 * @throws \Exception
	 */
	public static function OrderDependentFields($aFields)
	{
		$aResult = array();
		$iCount = 0;
		do
		{
			$bSet = false;
			$iCount++;
			foreach($aFields as $sFieldCode => $aDeps)
			{
				foreach($aDeps as $key => $sDependency)
				{
					if (in_array($sDependency, $aResult))
					{
						// Dependency is resolved, remove it
						unset($aFields[$sFieldCode][$key]);
					}
					else
					{
						if (!array_key_exists($sDependency, $aFields))
						{
							// The current fields depends on a field not present in the form
							// let's ignore it (since it cannot change)
							unset($aFields[$sFieldCode][$key]);
						}
					}
				}
				if (count($aFields[$sFieldCode]) == 0)
				{
					// No more pending depencies for this field, add it to the list
					$aResult[] = $sFieldCode;
					unset($aFields[$sFieldCode]);
					$bSet = true;
				}
			}
		} while ($bSet && (count($aFields) > 0));

		if (count($aFields) > 0)
		{
			$sMessage = "Error: Circular dependencies between the fields! <pre>".print_r($aFields, true)."</pre>";
			throw(new Exception($sMessage));
		}

		return $aResult;
	}

	/**
	 * Get the list of actions to be displayed as 'shortcuts' (i.e buttons) instead of inside the Actions popup menu
	 *
	 * @api
	 * @overwritable-hook
	 *
	 * @param string $sFinalClass The actual class of the objects for which to display the menu
	 *
	 * @return array the list of menu codes (i.e dictionary entries) that can be displayed as shortcuts next to the
	 *     actions menu
	 */
	public static function GetShortcutActions($sFinalClass)
	{
		$sShortcutActions = MetaModel::GetConfig()->Get('shortcut_actions');
		$aShortcutActions = explode(',', $sShortcutActions);

		return $aShortcutActions;
	}

	/**
	 * Maps the given context parameter name to the appropriate filter/search code for this class
	 *
	 * @param string $sContextParam Name of the context parameter, i.e. 'org_id'
	 *
	 * @return string Filter code, i.e. 'customer_id'
	 */
	public static function MapContextParam($sContextParam)
	{
		if ($sContextParam == 'menu')
		{
			return null;
		}
		else
		{
			return $sContextParam;
		}
	}

	/**
	 * Updates the object from a flat array of values
	 *
	 * @param $aAttList array $aAttList array of attcode
	 * @param $aErrors array Returns information about slave attributes
	 * @param $aAttFlags array Attribute codes => Flags to use instead of those from the MetaModel
	 *
	 * @return array of attcodes that can be used for writing on the current object
	 * @throws \CoreException
	 */
	public function GetWriteableAttList($aAttList, &$aErrors, $aAttFlags = array())
	{
		if (!is_array($aAttList))
		{
			$aAttList = $this->FlattenZList(MetaModel::GetZListItems(get_class($this), 'details'));
			// Special case to process the case log, if any...
			// WARNING: if you change this also check the functions DisplayModifyForm and DisplayCaseLog
			foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode => $oAttDef)
			{

				if (array_key_exists($sAttCode, $aAttFlags))
				{
					$iFlags = $aAttFlags[$sAttCode];
				}
				elseif ($this->IsNew())
				{
					$iFlags = $this->GetInitialStateAttributeFlags($sAttCode);
				}
				else
				{
					$aVoid = array();
					$iFlags = $this->GetAttributeFlags($sAttCode, $aVoid);
				}
				if ($oAttDef instanceof AttributeCaseLog)
				{
					if (!($iFlags & (OPT_ATT_HIDDEN | OPT_ATT_SLAVE | OPT_ATT_READONLY)))
					{
						// The case log is editable, append it to the list of fields to retrieve
						$aAttList[] = $sAttCode;
					}
				}
			}
		}
		$aWriteableAttList = array();
		foreach($aAttList as $sAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);

			if (array_key_exists($sAttCode, $aAttFlags))
			{
				$iFlags = $aAttFlags[$sAttCode];
			}
			elseif ($this->IsNew())
			{
				$iFlags = $this->GetInitialStateAttributeFlags($sAttCode);
			}
			else
			{
				$aVoid = array();
				$iFlags = $this->GetAttributeFlags($sAttCode, $aVoid);
			}
			if ($oAttDef->IsWritable())
			{
				if ($iFlags & (OPT_ATT_HIDDEN | OPT_ATT_READONLY))
				{
					// Non-visible, or read-only attribute, do nothing
				}
				elseif ($iFlags & OPT_ATT_SLAVE)
				{
					$aErrors[$sAttCode] = Dict::Format('UI:AttemptingToSetASlaveAttribute_Name', $oAttDef->GetLabel(), $sAttCode);
				}
				else
				{
					$aWriteableAttList[$sAttCode] = $oAttDef;
				}
			}
		}

		return $aWriteableAttList;
	}

	/**
	 * Compute the attribute flags depending on the object state
	 */
	public function GetFormAttributeFlags($sAttCode)
	{
		if ($this->IsNew())
		{
			$iFlags = $this->GetInitialStateAttributeFlags($sAttCode);
		}
		else
		{
			$iFlags = $this->GetAttributeFlags($sAttCode);
		}
		if (($iFlags & OPT_ATT_MANDATORY) && $this->IsNew())
		{
			$iFlags = $iFlags & ~OPT_ATT_READONLY; // Mandatory fields cannot be read-only when creating an object
		}

		return $iFlags;
	}

	/**
	 * Updates the object from a flat array of values
	 *
	 * @param $aValues array of attcode => scalar or array (N-N links)
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public function UpdateObjectFromArray($aValues)
	{
		foreach($aValues as $sAttCode => $value)
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
			switch ($oAttDef->GetEditClass())
			{
				case 'Document':
				case 'Image':
					// There should be an uploaded file with the named attr_<attCode>
					if ($value['remove'])
					{
						$this->Set($sAttCode, null);
					}
					else
					{
						$oDocument = $value['fcontents'];
						if (!$oDocument->IsEmpty())
						{
							// A new file has been uploaded
							$this->Set($sAttCode, $oDocument);
						}
					}
					break;
				case 'One Way Password':
					// Check if the password was typed/changed
					$aPwdData = $value;
					if (!is_null($aPwdData) && $aPwdData['changed'])
					{
						// The password has been changed or set
						$this->Set($sAttCode, $aPwdData['value']);
					}
					break;
				case 'Duration':
					$aDurationData = $value;
					if (!is_array($aDurationData))
					{
						break;
					}

					$iValue = (((24 * $aDurationData['d']) + $aDurationData['h']) * 60 + $aDurationData['m']) * 60 + $aDurationData['s'];
					$this->Set($sAttCode, $iValue);
					$previousValue = $this->Get($sAttCode);
					if ($previousValue !== $iValue)
					{
						$this->Set($sAttCode, $iValue);
					}
					break;
				case 'CustomFields':
					$this->Set($sAttCode, $value);
					break;
				case 'LinkedSet':
					if ($this->IsValueModified($value))
					{
						$oLinkSet = $this->Get($sAttCode);
						$sLinkedClass = $oAttDef->GetLinkedClass();
						if (array_key_exists('to_be_created', $value) && (count($value['to_be_created']) > 0))
						{
							// Now handle the links to be created
							foreach ($value['to_be_created'] as $aData)
							{
								$sSubClass = $aData['class'];
								if (($sLinkedClass == $sSubClass) || (is_subclass_of($sSubClass, $sLinkedClass))) {
									$aObjData = $aData['data'];
									// Avoid duplicates on bulk modify
									$bCanLinkBeCreated = true;
									// - Special case for n:n links
									if (
										($oAttDef instanceof AttributeLinkedSetIndirect)
										&& (false === $oAttDef->DuplicatesAllowed())
										&& in_array($aObjData[$oAttDef->GetExtKeyToRemote()], $oLinkSet->GetColumnAsArray($oAttDef->GetExtKeyToRemote(), false))
									) {
										$bCanLinkBeCreated = false;
									}
									if ($bCanLinkBeCreated) {
										$oLink = MetaModel::NewObject($sSubClass);
										$oLink->UpdateObjectFromArray($aObjData);
										$oLinkSet->AddItem($oLink);
									}
								}
						}
						}
						if (array_key_exists('to_be_added', $value) && (count($value['to_be_added']) > 0))
						{
							// Now handle the links to be added by making the remote object point to self
							foreach ($value['to_be_added'] as $iObjKey)
							{
								$oLink = MetaModel::GetObject($sLinkedClass, $iObjKey, false);
								if ($oLink)
								{
									$oLinkSet->AddItem($oLink);
								}
							}
						}
						if (array_key_exists('to_be_modified', $value) && (count($value['to_be_modified']) > 0))
						{
							// Now handle the links to be added by making the remote object point to self
							foreach ($value['to_be_modified'] as $iObjKey => $aData)
							{
								$oLink = MetaModel::GetObject($sLinkedClass, $iObjKey, false);
								if ($oLink)
								{
									$aObjData = $aData['data'];
									$oLink->UpdateObjectFromArray($aObjData);
									$oLinkSet->ModifyItem($oLink);
								}
							}
						}
						if (array_key_exists('to_be_removed', $value) && (count($value['to_be_removed']) > 0))
						{
							foreach ($value['to_be_removed'] as $iObjKey)
							{
								$oLinkSet->RemoveItem($iObjKey);
							}
						}
						if (array_key_exists('to_be_deleted', $value) && (count($value['to_be_deleted']) > 0))
						{
							foreach ($value['to_be_deleted'] as $iObjKey)
							{
								$oLinkSet->RemoveItem($iObjKey);
							}
						}
						$this->Set($sAttCode, $oLinkSet);
					}
					break;

				case 'TagSet':
					/** @var ormTagSet $oTagSet */
					$oTagSet = $this->Get($sAttCode);
					if (is_null($oTagSet))
					{
						$oTagSet = new ormTagSet(get_class($this), $sAttCode, $oAttDef->GetMaxItems());
					}
					$oTagSet->ApplyDelta($value);
					$this->Set($sAttCode, $oTagSet);
					break;

				case 'Set':
					/** @var ormSet $oSet */
					$oSet = $this->Get($sAttCode);
					if (is_null($oSet))
					{
						$oSet = new ormSet(get_class($this), $sAttCode, $oAttDef->GetMaxItems());
					}
					$oSet->ApplyDelta($value);
					$this->Set($sAttCode, $oSet);
					break;

				default:
					if (!is_null($value))
					{
						$aAttributes[$sAttCode] = trim($value);
						$previousValue = $this->Get($sAttCode);
						if ($previousValue !== $aAttributes[$sAttCode])
						{
							$this->Set($sAttCode, $aAttributes[$sAttCode]);
						}
					}
			}
		}
	}

	private function IsValueModified($value)
	{
		$aModifiedKeys = ['to_be_created', 'to_be_added', 'to_be_modified', 'to_be_removed', 'to_be_deleted'];
		foreach ($aModifiedKeys as $sModifiedKey) {
			if (array_key_exists( $sModifiedKey, $value) && (count($value[$sModifiedKey]) > 0))
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Updates the object from the POSTed parameters (form)
	 */
	public function UpdateObjectFromPostedForm($sFormPrefix = '', $aAttList = null, $aAttFlags = array())
	{
		if (is_null($aAttList))
		{
			$aAttList = array_keys(MetaModel::ListAttributeDefs(get_class($this)));
		}
		$aValues = array();
		foreach($aAttList as $sAttCode)
		{
			$value = $this->PrepareValueFromPostedForm($sFormPrefix, $sAttCode);
			if (!is_null($value))
			{
				$aValues[$sAttCode] = $value;
			}
		}

		$aErrors = array();
		$aFinalValues = array();
		foreach($this->GetWriteableAttList(array_keys($aValues), $aErrors, $aAttFlags) as $sAttCode => $oAttDef)
		{
			$aFinalValues[$sAttCode] = $aValues[$sAttCode];
		}
		try
		{
			$this->UpdateObjectFromArray($aFinalValues);
		}
		catch (CoreException $e)
		{
			$aErrors[] = $e->getMessage();
		}
		if (!$this->IsNew()) // for new objects this is performed in DBInsertNoReload()
		{
			InlineImage::FinalizeInlineImages($this);
		}

		// Invoke extensions after the update of the object from the form
		/** @var \iApplicationUIExtension $oExtensionInstance */
		foreach(MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnFormSubmit($this, $sFormPrefix);
		}

		return $aErrors;
	}

	/**
	 * @param string $sFormPrefix
	 * @param string $sAttCode
	 * @param string $sClass Optional parameter, host object's class for the $sAttCode
	 * @param array $aPostedData Optional parameter, used through recursive calls
	 *
	 * @return array|null
	 * @throws \FileUploadException
	 */
	protected function PrepareValueFromPostedForm($sFormPrefix, $sAttCode, $sClass = null, $aPostedData = null)
	{
		if ($sClass === null)
		{
			$sClass = get_class($this);
		}

		$value = null;

		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		switch ($oAttDef->GetEditClass())
		{
			case  'Document':
				$aOtherData = utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}", null, 'raw_data');
				if (is_array($aOtherData) && array_key_exists('remove', $aOtherData)) {
					$value = array('fcontents' => utils::ReadPostedDocument("attr_{$sFormPrefix}{$sAttCode}", 'fcontents'), 'remove' => $aOtherData['remove']);
				}
				break;

			case 'Image':
				$value = null;
				$oImage = utils::ReadPostedDocument("attr_{$sFormPrefix}{$sAttCode}", 'fcontents');
				if (!is_null($oImage->GetData()))
				{
					$aSize = utils::GetImageSize($oImage->GetData());
					if (is_array($aSize) && $aSize[0] > 0 && $aSize[1] > 0)
					{
						$oImage = utils::ResizeImageToFit(
							$oImage,
							$aSize[0],
							$aSize[1],
							$oAttDef->Get('storage_max_width'),
							$oAttDef->Get('storage_max_height')
						);
					}
					else
					{
						IssueLog::Warning($sClass . ':' . $this->GetKey() . '/' . $sAttCode . ': Image could not be resized. Mimetype: ' . $oImage->GetMimeType() . ', filename: ' . $oImage->GetFileName());
					}
				}
				$aOtherData = utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}", null, 'raw_data');
				if (is_array($aOtherData))
				{
					$value = array('fcontents' => $oImage, 'remove' => $aOtherData['remove']);
				}
				break;

			case 'RedundancySetting':
				$value = $oAttDef->ReadValueFromPostedForm($sFormPrefix);
				break;

			case 'CustomFields':
			case 'FormField':
				$value = $oAttDef->ReadValueFromPostedForm($this, $sFormPrefix);
				break;

			case 'LinkedSet':
				/** @var AttributeLinkedSet $oAttDef */
				if ($oAttDef->GetDisplayStyle() === LINKSET_DISPLAY_STYLE_PROPERTY) {
					$sLinkedClass = LinkSetModel::GetLinkedClass($oAttDef);
					$sTargetField = LinkSetModel::GetTargetField($oAttDef);
					$aOperations = json_decode(utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}_operations", '{}', 'raw_data'), true);
					$value = LinkSetDataTransformer::Encode($aOperations, $sLinkedClass, $sTargetField);
					break;
				}
				$aRawToBeCreated = json_decode(utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}_tbc", '{}',
					'raw_data'), true);
				$aToBeCreated = array();
				foreach ($aRawToBeCreated as $aData) {
					$sSubFormPrefix = $aData['formPrefix'];
					$sObjClass = isset($aData['class']) ? $aData['class'] : $oAttDef->GetLinkedClass();
					$aObjData = array();
					foreach ($aData as $sKey => $value) {
						if (preg_match("/^attr_$sSubFormPrefix(.*)$/", $sKey, $aMatches)) {
							$oLinkAttDef = MetaModel::GetAttributeDef($sObjClass, $aMatches[1]);
							// Recursing over n:n link datetime attributes
							// Note: We might need to do it with other attribute types, like Document or redundancy setting.
							if ($oLinkAttDef instanceof AttributeDateTime) {
								$aObjData[$aMatches[1]] = $this->PrepareValueFromPostedForm($sSubFormPrefix,
									$aMatches[1], $sObjClass, $aData);
							} else {
								$aObjData[$aMatches[1]] = $value;
							}
						}
					}
					$aToBeCreated[] = array('class' => $sObjClass, 'data' => $aObjData);
				}

				$aRawToBeModified = json_decode(utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}_tbm", '{}',
					'raw_data'), true);
				$aToBeModified = array();
				foreach($aRawToBeModified as $iObjKey => $aData) {
					$sSubFormPrefix = $aData['formPrefix'];
					$sObjClass = isset($aData['class']) ? $aData['class'] : $oAttDef->GetLinkedClass();
					$aObjData = array();
					foreach($aData as $sKey => $value) {
						if (preg_match("/^attr_$sSubFormPrefix(.*)$/", $sKey, $aMatches)) {
							$oLinkAttDef = MetaModel::GetAttributeDef($sObjClass, $aMatches[1]);
							// Recursing over n:n link datetime attributes
							// Note: We might need to do it with other attribute types, like Document or redundancy setting.
							if ($oLinkAttDef instanceof AttributeDateTime) {
								$aObjData[$aMatches[1]] = $this->PrepareValueFromPostedForm($sSubFormPrefix,
									$aMatches[1], $sObjClass, $aData);
							} else {
								$aObjData[$aMatches[1]] = $value;
							}
						}
					}
					$aToBeModified[$iObjKey] = array('data' => $aObjData);
				}

				$value = array(
					'to_be_created'  => $aToBeCreated,
					'to_be_modified' => $aToBeModified,
					'to_be_deleted'  => json_decode(utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}_tbd", '[]',
						'raw_data'), true),
					'to_be_added'    => json_decode(utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}_tba", '[]',
						'raw_data'), true),
					'to_be_removed'  => json_decode(utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}_tbr", '[]',
						'raw_data'), true),
				);
				break;

			case 'Set':
			case 'TagSet':
				$sTagSetJson = utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}", null, 'raw_data');
			if ($sTagSetJson !== null) { // bulk modify, direct linked set not handled
				$value = json_decode($sTagSetJson, true);
			}
				break;

			default:
				if ($oAttDef instanceof AttributeDateTime) // AttributeDate is derived from AttributeDateTime
				{
					// Retrieving value from array when present (means what we are in a recursion)
					if ($aPostedData !== null && isset($aPostedData['attr_'.$sFormPrefix.$sAttCode]))
					{
						$value = $aPostedData['attr_'.$sFormPrefix.$sAttCode];
					}
					else
					{
						$value = utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}", null, 'raw_data');
					}

					if ($value != null)
					{
						$oDate = $oAttDef->GetFormat()->Parse($value);
						if ($oDate instanceof DateTime)
						{
							$value = $oDate->format($oAttDef->GetInternalFormat());
						}
						else
						{
							$value = null;
						}
					}
				}
				else
				{
					// Retrieving value from array when present (means what we are in a recursion)
					if ($aPostedData !== null && isset($aPostedData['attr_'.$sFormPrefix.$sAttCode]))
					{
						$value = $aPostedData['attr_'.$sFormPrefix.$sAttCode];
					}
					else
					{
						$value = utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}", null, 'raw_data');
					}
				}
				break;
		}

		return $value;
	}

	/**
	 * Updates the object from a given page argument
	 *
	 * The values are read from parameters (GET or POST, using {@see utils::ReadParam()}).
	 *
	 * To pass the arg, either add in HTML :
	 *
	 * ```html
	 * <input type="hidden" name="sArgName[attCode1]" value="...">
	 * <input type="hidden" name="sArgName[attCode2]" value="...">
	 * ```
	 *
	 * Or directly in the URL :
	 *
	 * ```php
	 * $aObjectArgs = ['attCode1' => ..., 'attCode2' => ...];
	 * $sQueryString = http_build_query(['sArgName' => $aObjectArgs]);
	 * ```
	 *
	 * @uses utils::ReadParam()
	 * @uses self::UpdateObjectFromArray
	 */
	public function UpdateObjectFromArg($sArgName, $aAttList = null, $aAttFlags = array())
	{
		if (is_null($aAttList))
		{
			$aAttList = array_keys(MetaModel::ListAttributeDefs(get_class($this)));
		}
		$aRawValues = utils::ReadParam($sArgName, array(), '', 'raw_data');
		$aValues = array();
		foreach($aAttList as $sAttCode)
		{
			if (isset($aRawValues[$sAttCode]))
			{
				$aValues[$sAttCode] = $aRawValues[$sAttCode];
			}
		}

		$aErrors = array();
		$aFinalValues = array();
		foreach($this->GetWriteableAttList(array_keys($aValues), $aErrors, $aAttFlags) as $sAttCode => $oAttDef)
		{
			if ($oAttDef->IsLinkSet())
			{
				$aFinalValues[$sAttCode] = json_decode($aValues[$sAttCode], true);
			}
			else
			{
				$aFinalValues[$sAttCode] = $aValues[$sAttCode];
			}
		}
		try
		{
			$this->UpdateObjectFromArray($aFinalValues);
		}
		catch (CoreException $e)
		{
			$aErrors[] = $e->getMessage();
		}
		return $aErrors;
	}

	/**
	 * @inheritdoc
	 */
	public function DBInsertNoReload()
	{
		$this->LogCRUDEnter(__METHOD__);
		try {
			$res = parent::DBInsertNoReload();

			$this->SetWarningsAsSessionMessages('create');

		} finally {
			if (static::IsCrudStackEmpty()) {
				// Avoid signaling the current object that links were modified
				static::RemoveObjectAwaitingEventDbLinksChanged(get_class($this), $this->GetKey());
				static::FireEventDbLinksChangedForAllObjects();
			}
		}
		$this->LogCRUDExit(__METHOD__);
		return $res;
	}

	protected function PostInsertActions(): void
	{
		parent::PostInsertActions();

		// Invoke extensions after insertion (the object must exist, have an id, etc.)
		/** @var \iApplicationObjectExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins(iApplicationObjectExtension::class) as $oExtensionInstance) {
			$sExtensionClass = get_class($oExtensionInstance);
			$this->LogCRUDDebug(__METHOD__, "Calling $sExtensionClass::OnDBInsert()");
			$oKPI = new ExecutionKPI();
			$oExtensionInstance->OnDBInsert($this, self::GetCurrentChange());
			$oKPI->ComputeStatsForExtension($oExtensionInstance, 'OnDBInsert');
		}
	}

	/**
	 * @inheritdoc
	 * Attaches InlineImages to the current object
	 */
	protected function OnObjectKeyReady()
	{
		InlineImage::FinalizeInlineImages($this);
	}

	/**
	 * @deprecated 3.1.1 3.2.0 N°6966 We will have only one DBClone method in the future
	 */
	protected function DBCloneTracked_Internal($newKey = null)
	{
        /** @var cmdbAbstractObject $oNewObj */
        $oNewObj = MetaModel::GetObject(get_class($this), parent::DBCloneTracked_Internal($newKey));

		// Invoke extensions after insertion (the object must exist, have an id, etc.)
		/** @var \iApplicationObjectExtension $oExtensionInstance */
		foreach(MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
            $oKPI = new ExecutionKPI();
			$oExtensionInstance->OnDBInsert($oNewObj, self::GetCurrentChange());
            $oKPI->ComputeStatsForExtension($oExtensionInstance, 'OnDBInsert');
		}

		return $oNewObj;
	}

	public function DBUpdate()
	{
		$this->LogCRUDEnter(__METHOD__);
		$res = 0;

		try {
			if (count($this->ListChanges()) === 0) {
				$this->LogCRUDExit(__METHOD__);
				return $this->GetKey();
			}
			$res = parent::DBUpdate();

			$this->SetWarningsAsSessionMessages('update');
		} finally {
			if (static::IsCrudStackEmpty()) {
				static::FireEventDbLinksChangedForAllObjects();
			}
		}
		$this->LogCRUDExit(__METHOD__);

		return $res;
	}

	protected function PostUpdateActions(array $aChanges): void
	{
		parent::PostUpdateActions($aChanges);

		// Invoke extensions after the update (could be before)
		/** @var \iApplicationObjectExtension $oExtensionInstance */
		foreach (MetaModel::EnumPlugins(iApplicationObjectExtension::class) as $oExtensionInstance) {
			$sExtensionClass = get_class($oExtensionInstance);
			$this->LogCRUDDebug(__METHOD__, "Calling $sExtensionClass::OnDBUpdate()");
			$oKPI = new ExecutionKPI();
			$oExtensionInstance->OnDBUpdate($this, self::GetCurrentChange());
			$oKPI->ComputeStatsForExtension($oExtensionInstance, 'OnDBUpdate');
		}
	}

	/**
	 * @param string $sMessageIdPrefix
	 *
	 * @since 2.6.0
	 */
	protected function SetWarningsAsSessionMessages($sMessageIdPrefix)
	{
		if (!empty($this->m_aCheckWarnings) && is_array($this->m_aCheckWarnings)) {
			$iMsgNb = 0;
			foreach ($this->m_aCheckWarnings as $sWarningMessage) {
				$iMsgNb++;
				$sMessageId = "$sMessageIdPrefix-$iMsgNb"; // each message must have its own messageId !
				$this->SetSessionMessageFromInstance($sMessageId, $sWarningMessage, 'warning', 0);
			}
		}
	}

	public function DBDelete(&$oDeletionPlan = null)
	{
		$this->LogCRUDEnter(__METHOD__);
		try {
			parent::DBDelete($oDeletionPlan);
		}  finally {
			if (static::IsCrudStackEmpty()) {
				// Avoid signaling the current object that links were modified
				static::RemoveObjectAwaitingEventDbLinksChanged(get_class($this), $this->GetKey());
				$this->LogCRUDDebug(__METHOD__, var_export(self::$aObjectsAwaitingEventDbLinksChanged, true));
				static::FireEventDbLinksChangedForAllObjects();
			}
		}
		$this->LogCRUDExit(__METHOD__);

		return $oDeletionPlan;
	}

	protected function PostDeleteActions(): void
	{
		parent::PostDeleteActions();
	}

	/**
	 * @deprecated 3.1.1 3.2.0 N°6967 We will have only one DBDelete method in the future
	 */
	protected function DBDeleteTracked_Internal(&$oDeletionPlan = null)
	{
		// Invoke extensions before the deletion (the deletion will do some cleanup and we might loose some information
		/** @var \iApplicationObjectExtension $oExtensionInstance */
		foreach(MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
            $oKPI = new ExecutionKPI();
			$oExtensionInstance->OnDBDelete($this, self::GetCurrentChange());
            $oKPI->ComputeStatsForExtension($oExtensionInstance, 'OnDBDelete');
		}

		return parent::DBDeleteTracked_Internal($oDeletionPlan);
	}

	public function IsModified()
	{
		if (parent::IsModified())
		{
			return true;
		}

		// Plugins
		//
		/** @var \iApplicationObjectExtension $oExtensionInstance */
		foreach(MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			$sExtensionClass = get_class($oExtensionInstance);
			$oKPI = new ExecutionKPI();
			$bIsModified = $oExtensionInstance->OnIsModified($this);
			$oKPI->ComputeStatsForExtension($oExtensionInstance, 'OnIsModified');
			if ($bIsModified) {
				$this->LogCRUDDebug(__METHOD__, "Calling $sExtensionClass::OnIsModified() -> true");
				return true;
			} else {
				$this->LogCRUDDebug(__METHOD__, "Calling $sExtensionClass::OnIsModified() -> false");
			}
		}

		return false;
	}

	/**
	 * Bypass the check of the user rights when writing this object
	 *
	 * @param bool $bAllow True to bypass the checks, false to restore the default behavior
	 */
	public function AllowWrite($bAllow = true)
	{
		$this->bAllowWrite = $bAllow;
	}

	/**
	 * Whether to bypass the checks of user rights when writing this object, could be used in {@link \iApplicationObjectExtension::OnCheckToWrite()}
	 *
	 * @return bool
	 */
	public function GetAllowWrite()
	{
		return $this->bAllowWrite;
	}

	/**
	 * Bypass the check of the user rights when deleting this object
	 *
	 * @param bool $bAllow True to bypass the checks, false to restore the default behavior
	 */
	public function AllowDelete($bAllow = true)
	{
		$this->bAllowDelete = $bAllow;
	}

	/**
	 * @inheritdoc
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function DoCheckToWrite()
	{
		parent::DoCheckToWrite();

		// Plugins
		//
		/** @var \iApplicationObjectExtension $oExtensionInstance */
		foreach(MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
            $oKPI = new ExecutionKPI();
			$aNewIssues = $oExtensionInstance->OnCheckToWrite($this);
            $oKPI->ComputeStatsForExtension($oExtensionInstance, 'OnCheckToWrite');
			if (is_array($aNewIssues) && (count($aNewIssues) > 0)) // Some extensions return null instead of an empty array
			{
				$this->m_aCheckIssues = array_merge($this->m_aCheckIssues, $aNewIssues);
			}
		}

		// User rights
		//
		if (!$this->bAllowWrite)
		{
			$aChanges = $this->ListChanges();
			if (count($aChanges) > 0)
			{
				$aForbiddenFields = array();
				foreach($this->ListChanges() as $sAttCode => $value)
				{
					$bUpdateAllowed = UserRights::IsActionAllowedOnAttribute(get_class($this), $sAttCode,
						UR_ACTION_MODIFY, DBObjectSet::FromObject($this));
					if (!$bUpdateAllowed)
					{
						$oAttCode = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
						$aForbiddenFields[] = $oAttCode->GetLabel();
					}
				}
				if (count($aForbiddenFields) > 0)
				{
					// Security issue
					$this->m_bSecurityIssue = true;
					$this->m_aCheckIssues[] = Dict::Format('UI:Delete:NotAllowedToUpdate_Fields',
						implode(', ', $aForbiddenFields));
				}
			}
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function DoCheckToDelete(&$oDeletionPlan)
	{
		parent::DoCheckToDelete($oDeletionPlan);

		// Plugins
		//
		/** @var \iApplicationObjectExtension $oExtensionInstance */
		foreach(MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
            $oKPI = new ExecutionKPI();
			$aNewIssues = $oExtensionInstance->OnCheckToDelete($this);
            $oKPI->ComputeStatsForExtension($oExtensionInstance, 'OnCheckToDelete');
			if (is_array($aNewIssues) && count($aNewIssues) > 0)
			{
				$this->m_aDeleteIssues = array_merge($this->m_aDeleteIssues, $aNewIssues);
			}
		}

		// User rights
		//
		if (! $this->bAllowDelete)
		{
			$bDeleteAllowed = UserRights::IsActionAllowed(get_class($this), UR_ACTION_DELETE, DBObjectSet::FromObject($this));

			if (!$bDeleteAllowed)
			{
				// Security issue
				$this->m_bSecurityIssue = true;
				$this->m_aDeleteIssues[] = Dict::S('UI:Delete:NotAllowedToDelete');
			}
		}
	}

	/**
	 * Special display where the case log uses the whole "screen" at the bottom of the "Properties" tab
	 *
	 * @param WebPage $oPage
	 * @param string $sAttCode
	 * @param string $sComment
	 * @param string $sPrefix
	 * @param bool $bEditMode
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \Exception
	 * @deprecated 3.0.0, will be removed in 3.1.0
	 */
	public function DisplayCaseLog(WebPage $oPage, $sAttCode, $sComment = '', $sPrefix = '', $bEditMode = false)
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod();
		$oPage->SetCurrentTab('UI:PropertiesTab');
		$sClass = get_class($this);

		if ($this->IsNew()) {
			$iFlags = $this->GetInitialStateAttributeFlags($sAttCode);
		} else {
			$iFlags = $this->GetAttributeFlags($sAttCode);
		}

		if ($iFlags & OPT_ATT_HIDDEN) {
			// The case log is hidden do nothing
		} else
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
			$sAttDefClass = get_class($oAttDef);
			$sAttLabel = $oAttDef->GetLabel();
			$sAttMetaDataLabel = utils::HtmlEntities($sAttLabel);
			$sAttMetaDataFlagHidden = (($iFlags & OPT_ATT_HIDDEN) === OPT_ATT_HIDDEN) ? 'true' : 'false';
			$sAttMetaDataFlagReadOnly = (($iFlags & OPT_ATT_READONLY) === OPT_ATT_READONLY) ? 'true' : 'false';
			$sAttMetaDataFlagMandatory = (($iFlags & OPT_ATT_MANDATORY) === OPT_ATT_MANDATORY) ? 'true' : 'false';
			$sAttMetaDataFlagMustChange = (($iFlags & OPT_ATT_MUSTCHANGE) === OPT_ATT_MUSTCHANGE) ? 'true' : 'false';
			$sAttMetaDataFlagMustPrompt = (($iFlags & OPT_ATT_MUSTPROMPT) === OPT_ATT_MUSTPROMPT) ? 'true' : 'false';
			$sAttMetaDataFlagSlave = (($iFlags & OPT_ATT_SLAVE) === OPT_ATT_SLAVE) ? 'true' : 'false';

			$sInputId = $this->m_iFormId.'_'.$sAttCode;

			if ((!$bEditMode) || ($iFlags & (OPT_ATT_READONLY | OPT_ATT_SLAVE)))
			{
				// Check if the attribute is not read-only because of a synchro...
				if ($iFlags & OPT_ATT_SLAVE)
				{
					$aReasons = array();
					$sTip = '';
					foreach($aReasons as $aRow) {
						$sDescription = utils::EscapeHtml($aRow['description']);
						$sDescription = str_replace(array("\r\n", "\n"), "<br/>", $sDescription);
						$sTip .= "<div class=\"synchro-source\">";
						$sTip .= "<div class=\"synchro-source-title\">Synchronized with {$aRow['name']}</div>";
						$sTip .= "<div class=\"synchro-source-description\">$sDescription</div>";
					}
					$sTip = addslashes($sTip);
					$oPage->add_ready_script("$('#synchro_$sInputId').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );");
				}

				// Attribute is read-only
				$sHTMLValue = $this->GetAsHTML($sAttCode);
				$sHTMLValue .= '<input type="hidden" id="'.$sInputId.'" name="attr_'.$sPrefix.$sAttCode.'" value="'.utils::EscapeHtml($this->GetEditValue($sAttCode)).'"/>';
				$aFieldsMap[$sAttCode] = $sInputId;
			}
			else
			{
				$sValue = $this->Get($sAttCode);
				$sDisplayValue = $this->GetEditValue($sAttCode);
				$aArgs = array('this' => $this, 'formPrefix' => $sPrefix);

				$sCommentAsHtml = ($sComment != '') ? '<span>'.$sComment.'</span><br/>' : '';
				$sFieldAsHtml = self::GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef, $sValue, $sDisplayValue, $sInputId, '', $iFlags, $aArgs);
				$sHTMLValue = <<<HTML
<div class="field_data">
	<div class="field_value">
		$sCommentAsHtml
		$sFieldAsHtml
	</div>
</div>
HTML;

				$aFieldsMap[$sAttCode] = $sInputId;
			}

			$oPage->add(<<<HTML
<fieldset>
	<legend>{$sAttLabel}</legend>
	<div class="field_container field_large" data-attribute-code="{$sAttCode}" data-attribute-type="{$sAttDefClass}" data-attribute-label="{$sAttMetaDataLabel}"
		data-attribute-flag-hidden="{$sAttMetaDataFlagHidden}" data-attribute-flag-read-only="{$sAttMetaDataFlagReadOnly}" data-attribute-flag-mandatory="{$sAttMetaDataFlagMandatory}"
		data-attribute-flag-must-change="{$sAttMetaDataFlagMustChange}" data-attribute-flag-must-prompt="{$sAttMetaDataFlagMustPrompt}" data-attribute-flag-slave="{$sAttMetaDataFlagSlave}">
		{$sHTMLValue}
	</div>
</fieldset>
HTML
			);
		}
	}

	/**
	 * Special display where the case log uses the whole "screen" at the bottom of the "Properties" tab
	 *
	 * @param WebPage $oPage
	 * @param string $sAttCode
	 * @param string $sComment
	 * @param string $sPrefix
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function DisplayCaseLogForBulkModify(WebPage $oPage, $sAttCode, $sComment = '', $sPrefix = '')
	{
		$sClass = get_class($this);

		$iFlags = $this->GetAttributeFlags($sAttCode);

		if ($iFlags & (OPT_ATT_HIDDEN | OPT_ATT_READONLY | OPT_ATT_SLAVE)) {
			// The case log can not be updated
		} else {
			$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
			$sAttDefClass = get_class($oAttDef);
			$sAttLabel = $oAttDef->GetLabel();
			$sAttMetaDataLabel = utils::HtmlEntities($sAttLabel);
			$sAttMetaDataFlagMandatory = (($iFlags & OPT_ATT_MANDATORY) === OPT_ATT_MANDATORY) ? 'true' : 'false';
			$sAttMetaDataFlagMustChange = (($iFlags & OPT_ATT_MUSTCHANGE) === OPT_ATT_MUSTCHANGE) ? 'true' : 'false';
			$sAttMetaDataFlagMustPrompt = (($iFlags & OPT_ATT_MUSTPROMPT) === OPT_ATT_MUSTPROMPT) ? 'true' : 'false';

			$sInputId = $this->m_iFormId.'_'.$sAttCode;

			$sValue = $this->Get($sAttCode);
			$sDisplayValue = $this->GetEditValue($sAttCode);
			$aArgs = array('this' => $this, 'formPrefix' => $sPrefix);

			$aFieldsMap[$sAttCode] = $sInputId;

			$sCommentAsHtml = ($sComment != '') ? ' <div class="ibo-field--comments">'.$sComment.'</div>' : '';

			$oFieldset = FieldSetUIBlockFactory::MakeStandard($sAttLabel.$sCommentAsHtml);
			$oPage->AddSubBlock($oFieldset);

			$oDivField = FieldUIBlockFactory::MakeLarge("");
			//	UIContentBlockUIBlockFactory::MakeStandard(null,["field_container field_large"]);
			$oDivField->AddDataAttribute("attribute-type", $sAttDefClass);
			$oDivField->AddDataAttribute("attribute-label", $sAttMetaDataLabel);
			$oDivField->AddDataAttribute("attribute-flag-hidden", false);
			$oDivField->AddDataAttribute("attribute-flag-read-only", false);
			$oDivField->AddDataAttribute("attribute-flag-mandatory", $sAttMetaDataFlagMandatory);
			$oDivField->AddDataAttribute("attribute-flag-must-change", $sAttMetaDataFlagMustChange);
			$oDivField->AddDataAttribute("attribute-flag-must-prompt", $sAttMetaDataFlagMustPrompt);
			$oDivField->AddDataAttribute("attribute-flag-slave", false);
			$oFieldset->AddSubBlock($oDivField);
			//$oDivField->SetComments($sComment);
			$sFieldAsHtml = self::GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef, $sValue, $sDisplayValue, $sInputId, '', $iFlags, $aArgs);
			$sHTMLValue = $sFieldAsHtml;
			$oDivField->AddSubBlock(new Html($sHTMLValue));
		}
	}

	/**
	 * @param $sCurrentState
	 * @param $sStimulus
	 * @param $bOnlyNewOnes
	 *
	 * @return array
	 * @throws \ApplicationException
	 * @throws \CoreException
	 * @deprecated Since iTop 2.4, use DBObject::GetTransitionAttributes() instead.
	 */
	public function GetExpectedAttributes($sCurrentState, $sStimulus, $bOnlyNewOnes)
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('Since iTop 2.4, use DBObject::GetTransitionAttributes() instead');
		$aTransitions = $this->EnumTransitions();
		if (!isset($aTransitions[$sStimulus])) {
			// Invalid stimulus
			throw new ApplicationException(Dict::Format('UI:Error:Invalid_Stimulus_On_Object_In_State', $sStimulus,
				$this->GetName(), $this->GetStateLabel()));
		}
		$aTransition = $aTransitions[$sStimulus];
		$sTargetState = $aTransition['target_state'];
		$aTargetStates = MetaModel::EnumStates(get_class($this));
		$aTargetState = $aTargetStates[$sTargetState];
		$aCurrentState = $aTargetStates[$this->GetState()];
		$aExpectedAttributes = $aTargetState['attribute_list'];
		$aCurrentAttributes = $aCurrentState['attribute_list'];

		$aComputedAttributes = array();
		foreach($aExpectedAttributes as $sAttCode => $iExpectCode)
		{
			if (!array_key_exists($sAttCode, $aCurrentAttributes))
			{
				$aComputedAttributes[$sAttCode] = $iExpectCode;
			}
			else
			{
				if (!($aCurrentAttributes[$sAttCode] & (OPT_ATT_HIDDEN | OPT_ATT_READONLY)))
				{
					$iExpectCode = $iExpectCode & ~(OPT_ATT_MUSTPROMPT | OPT_ATT_MUSTCHANGE); // Already prompted/changed, reset the flags
				}
				// Later: better check if the attribute is not *null*
				if (($iExpectCode & OPT_ATT_MANDATORY) && ($this->Get($sAttCode) != ''))
				{
					$iExpectCode = $iExpectCode & ~(OPT_ATT_MANDATORY); // If the attribute is present, then no need to request its presence
				}

				$aComputedAttributes[$sAttCode] = $iExpectCode;
			}

			$aComputedAttributes[$sAttCode] = $aComputedAttributes[$sAttCode] & ~(OPT_ATT_READONLY | OPT_ATT_HIDDEN); // Don't care about this form now

			if ($aComputedAttributes[$sAttCode] == 0)
			{
				unset($aComputedAttributes[$sAttCode]);
			}
		}

		return $aComputedAttributes;
	}

	/**
	 * Display a form for modifying several objects at once
	 * The form will be submitted to the current page, with the specified additional values
	 *
	 * @param iTopWebPage $oP
	 * @param string $sClass
	 * @param array $aSelectedObj
	 * @param string $sCustomOperation
	 * @param string $sCancelUrl
	 * @param array $aExcludeAttributes
	 * @param array $aContextData
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public static function DisplayBulkModifyForm($oP, $sClass, $aSelectedObj, $sCustomOperation, $sCancelUrl, $aExcludeAttributes = array(), $aContextData = array())
	{
		if (count($aSelectedObj) > 0)
		{
			$iAllowedCount = count($aSelectedObj);
			$sSelectedObj = implode(',', $aSelectedObj);

			$sOQL = "SELECT $sClass WHERE id IN (".$sSelectedObj.")";
			$oSet = new CMDBObjectSet(DBObjectSearch::FromOQL($sOQL));

			// Compute the distribution of the values for each field to determine which of the "scalar or linked set" fields are homogeneous
			$aList = MetaModel::ListAttributeDefs($sClass);
			$aValues = array();
			foreach($aList as $sAttCode => $oAttDef)
			{
				if ($oAttDef->IsBulkModifyCompatible()) {
					$aValues[$sAttCode] = array();
				}
			}
			while ($oObj = $oSet->Fetch())
			{
				foreach($aList as $sAttCode => $oAttDef)
				{
					if ($oAttDef->IsBulkModifyCompatible() && $oAttDef->IsWritable()) {
						$currValue = $oObj->Get($sAttCode);
						$editValue = '';
						if ($oAttDef instanceof AttributeCaseLog) {
							$currValue = ''; // Put a single scalar value to force caselog to mock a new entry. For more info see N°1059.
						} elseif ($currValue instanceof ormSet) {
							$currValue = $oAttDef->GetEditValue($currValue, $oObj);
						} else if ($currValue instanceof ormLinkSet) {
							$sHtmlValue = $oAttDef->GetAsHTML($currValue);
							$editValue = $oAttDef->GetEditValue($currValue, $oObj);
							$currValue = $sHtmlValue;
						}
						if (is_object($currValue)) {
							continue;
						} // Skip non scalar values...
						if (!array_key_exists($currValue, $aValues[$sAttCode]))
						{
							$aValues[$sAttCode][$currValue] = array(
								'count'      => 1,
								'display'    => $oObj->GetAsHTML($sAttCode),
								'edit_value' => $editValue,
							);
						}
						else
						{
							$aValues[$sAttCode][$currValue]['count']++;
						}
					}
				}
			}
			// Now create an object that has values for the homogeneous values only
			/** @var \cmdbAbstractObject $oDummyObj */
			$oDummyObj = new $sClass(); // @@ What if the class is abstract ?
			$oDummyObj->SetDisplayMode(static::ENUM_DISPLAY_MODE_BULK_EDIT);
			$aComments = array();
			function MyComparison($a, $b) // Sort descending
			{
				if ($a['count'] == $b['count']) {
					return 0;
				}

				return ($a['count'] > $b['count']) ? -1 : 1;
			}

			$iFormId = cmdbAbstractObject::GetNextFormId(); // Identifier that prefixes all the form fields
			$sReadyScript = '';
			$sFormPrefix = '2_';
			foreach ($aList as $sAttCode => $oAttDef) {
				$aPrerequisites = MetaModel::GetPrerequisiteAttributes($sClass,
					$sAttCode); // List of attributes that are needed for the current one
				if (count($aPrerequisites) > 0) {
					// When 'enabling' a field, all its prerequisites must be enabled too
					$sFieldList = "['{$sFormPrefix}".implode("','{$sFormPrefix}", $aPrerequisites)."']";
					$oP->add_ready_script("$('#enable_{$sFormPrefix}{$sAttCode}').on('change', function(evt, sFormId) { return PropagateCheckBox( this.checked, $sFieldList, true); } );\n");
				}
				$aDependents = MetaModel::GetDependentAttributes($sClass,
					$sAttCode); // List of attributes that are needed for the current one
				if (count($aDependents) > 0) {
					// When 'disabling' a field, all its dependent fields must be disabled too
					$sFieldList = "['{$sFormPrefix}".implode("','{$sFormPrefix}", $aDependents)."']";
					$oP->add_ready_script("$('#enable_{$sFormPrefix}{$sAttCode}').on('change', function(evt, sFormId) { return PropagateCheckBox( this.checked, $sFieldList, false); } );\n");
				}
				if ($oAttDef->IsBulkModifyCompatible() && $oAttDef->IsWritable()) {
					if ($oAttDef->GetEditClass() == 'One Way Password') {

						$sTip = Dict::S('UI:Component:Field:BulkModify:UnknownValues:Tooltip');

						$oDummyObj->Set($sAttCode, null);
						$aComments[$sAttCode] = '<div class="multi_values ibo-field--enable-bulk ibo-pill ibo-is-failure" id="multi_values_'.$sAttCode.'" data-tooltip-content="'.$sTip.'">?';
						$aComments[$sAttCode] .= '<input type="checkbox" class="ibo-field--enable-bulk--checkbox" id="enable_'.$iFormId.'_'.$sAttCode.'" onClick="ToggleField(this.checked, \''.$iFormId.'_'.$sAttCode.'\')"/></div>';
						$sReadyScript .= 'ToggleField(false, \''.$iFormId.'_'.$sAttCode.'\');'."\n";
					} else {
						$iCount = count($aValues[$sAttCode]);
						if ($iCount == 1) {
							// Homogeneous value
							reset($aValues[$sAttCode]);
							$aKeys = array_keys($aValues[$sAttCode]);
							$currValue = $aKeys[0]; // The only value is the first key
							if ($oAttDef->GetEditClass() == 'LinkedSet') {
								$oOrmLinkSet = $oDummyObj->Get($sAttCode);
								LinkSetDataTransformer::StringToOrmLinkSet($aValues[$sAttCode][$currValue]['edit_value'], $oOrmLinkSet);

							} else {
								$oDummyObj->Set($sAttCode, $currValue);
							}
							$aComments[$sAttCode] = '';
							$sValueCheckbox = '';
							if ($sAttCode != MetaModel::GetStateAttributeCode($sClass) || !MetaModel::HasLifecycle($sClass)) {
								$sValueCheckbox .= '<input type="checkbox" class="ibo-field--enable-bulk--checkbox" checked id="enable_'.$iFormId.'_'.$sAttCode.'"  onClick="ToggleField(this.checked, \''.$iFormId.'_'.$sAttCode.'\')"/>';
							}
							$aComments[$sAttCode] .= '<div class="mono_value ibo-field--enable-bulk ibo-pill ibo-is-success">1'.$sValueCheckbox.'</div>';
						} else {
							// Non-homogeneous value
							$aMultiValues = $aValues[$sAttCode];
							uasort($aMultiValues, 'MyComparison');
							$iMaxCount = 5;
							$sTip = "<p><b>".Dict::Format('UI:BulkModify_Count_DistinctValues', $iCount)."</b><ul>";
							$index = 0;
							foreach ($aMultiValues as $sCurrValue => $aVal) {
								$sDisplayValue = empty($aVal['display']) ? '<i>'.Dict::S('Enum:Undefined').'</i>' : str_replace(array(
									"\n",
									"\r",
								), " ", $aVal['display']);
								$sTip .= "<li>".Dict::Format('UI:BulkModify:Value_Exists_N_Times', $sDisplayValue,
										$aVal['count'])."</li>";
								$index++;
								if ($iMaxCount == $index) {
									$sTip .= "<li>".Dict::Format('UI:BulkModify:N_MoreValues',
											count($aMultiValues) - $iMaxCount)."</li>";
									break;
								}
							}
							$sTip .= "</ul></p>";
							$sTip = utils::HtmlEntities($sTip);

							if (($oAttDef->GetEditClass() == 'TagSet') || ($oAttDef->GetEditClass() == 'Set')) {
								// Set the value by adding the values to the first one
								reset($aMultiValues);
								$aKeys = array_keys($aMultiValues);
								$currValue = $aKeys[0];
								$oDummyObj->Set($sAttCode, $currValue);
								/** @var ormTagSet $oTagSet */
								$oTagSet = $oDummyObj->Get($sAttCode);
								$oTagSet->SetDisplayPartial(true);
								foreach ($aKeys as $iIndex => $sValues) {
									if ($iIndex == 0) {
										continue;
									}
									$aTagCodes = $oAttDef->FromStringToArray($sValues);
									$oTagSet->GenerateDiffFromArray($aTagCodes);
								}
								$oDummyObj->Set($sAttCode, $oTagSet);
							} else if ($oAttDef->GetEditClass() == 'LinkedSet') {
								$oOrmLinkSet = $oDummyObj->Get($sAttCode);
								foreach ($aMultiValues as $key => $sValue) {
									LinkSetDataTransformer::StringToOrmLinkSet($sValue['edit_value'], $oOrmLinkSet);
								}

							} else {
								$oDummyObj->Set($sAttCode, null);
							}
							$aComments[$sAttCode] = '';
							$sValueCheckbox = '';

							if ($sAttCode != MetaModel::GetStateAttributeCode($sClass) || !MetaModel::HasLifecycle($sClass)) {
								$sValueCheckbox = '<input type="checkbox" class="ibo-field--enable-bulk--checkbox" id="enable_'.$iFormId.'_'.$sAttCode.'" onClick="ToggleField(this.checked, \''.$iFormId.'_'.$sAttCode.'\')"/>';
							}
							$aComments[$sAttCode] .= '<div class="multi_values ibo-field--enable-bulk ibo-pill ibo-is-failure" id="multi_values_'.$sAttCode.'" data-tooltip-content="'.$sTip.'" data-tooltip-html-enabled="true" data-tooltip-append-to="body">'.$iCount.$sValueCheckbox.'</div>';
						}
						$sReadyScript .= 'ToggleField('.(($iCount == 1) ? 'true' : 'false').', \''.$iFormId.'_'.$sAttCode.'\');'."\n";
					}
				}
			}

			if (MetaModel::HasLifecycle($sClass) && ($oDummyObj->GetState() == '')) {
				// Hmmm, it's not gonna work like this ! Set a default value for the "state"
				// Maybe we should use the "state" that is the most common among the objects...
				$sStateAttCode = MetaModel::GetStateAttributeCode($sClass);
				$aMultiValues = $aValues[$sStateAttCode];
				uasort($aMultiValues, 'MyComparison');
				foreach ($aMultiValues as $sCurrValue => $aVal) {
					$oDummyObj->Set($sStateAttCode, $sCurrValue);
					break;
				}
				//$oStateAtt = MetaModel::GetAttributeDef($sClass, $sStateAttCode);
				//$oDummyObj->Set($sStateAttCode, $oStateAtt->GetDefaultValue());
			}

			$oP->add("<div class=\"wizContainer\">\n");
			$sDisableFields = json_encode($aExcludeAttributes);

			$aParams = array
			(
				'fieldsComments'   => $aComments,
				'noRelations'      => true,
				'custom_operation' => $sCustomOperation,
				'custom_button'    => Dict::S('UI:Button:PreviewModifications'),
				'selectObj'        => $sSelectedObj,
				'nbBulkObj'        => $iAllowedCount,
				'preview_mode'     => true,
				'disabled_fields'  => $sDisableFields,
				'disable_plugins'  => true,
				'bulk_context'     => [
					'oql' => $sOQL,
				],
			);
			$aParams = $aParams + $aContextData; // merge keeping associations

			$oDummyObj->DisplayModifyForm($oP, $aParams);
			$oP->add("</div>\n");
			$oP->add_ready_script($sReadyScript);
			$oP->add_ready_script(
				<<<EOF
$('.wizContainer button.cancel').off('click');
$('.wizContainer button.cancel').on('click',  function() { window.location.href = '$sCancelUrl'; } );
EOF
			);

		} // Else no object selected ???
		else
		{
			$oP->p("No object selected !, nothing to do");
		}
	}

	/**
	 * Process the reply made from a form built with DisplayBulkModifyForm
	 *
	 * @param WebPage $oP
	 * @param string $sClass
	 * @param array $aSelectedObj
	 * @param string $sCustomOperation
	 * @param bool $bPreview
	 * @param string $sCancelUrl
	 * @param array $aContextData
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \OQLException
	 */
	public static function DoBulkModify($oP, $sClass, $aSelectedObj, $sCustomOperation, $bPreview, $sCancelUrl, $aContextData = array())
	{
		/** @var string[] $aHeaders */
		$aHeaders = array(
			'object' => array('label' => MetaModel::GetName($sClass), 'description' => Dict::S('UI:ModifiedObject')),
			'status' => array(
				'label' => Dict::S('UI:BulkModifyStatus'),
				'description' => Dict::S('UI:BulkModifyStatus+'),
			),
			'errors' => array(
				'label' => Dict::S('UI:BulkModifyErrors'),
				'description' => Dict::S('UI:BulkModifyErrors+'),
			),
		);
		$aRows = array();


		$sHeaderTitle = Dict::Format('UI:Modify_N_ObjectsOf_Class', count($aSelectedObj), MetaModel::GetName($sClass));
		$sClassIcon = MetaModel::GetClassIcon($sClass, false);


		$oP->set_title(Dict::Format('UI:Modify_N_ObjectsOf_Class', count($aSelectedObj), $sClass));
		if (!$bPreview) {
			// Not in preview mode, do the update for real
			$sTransactionId = utils::ReadPostedParam('transaction_id', '', 'transaction_id');
			if (!utils::IsTransactionValid($sTransactionId, false)) {
				throw new Exception(Dict::S('UI:Error:ObjectAlreadyUpdated'));
			}
			utils::RemoveTransaction($sTransactionId);
		}

		// Avoid too many events
		static::SetEventDBLinksChangedBlocked(true);
		$iPreviousTimeLimit = ini_get('max_execution_time');
		$iLoopTimeLimit = MetaModel::GetConfig()->Get('max_execution_time_per_loop');
		foreach ($aSelectedObj as $iId) {
			set_time_limit(intval($iLoopTimeLimit));
			/** @var \cmdbAbstractObject $oObj */
			$oObj = MetaModel::GetObject($sClass, $iId);
			$aErrors = $oObj->UpdateObjectFromPostedForm('');
			$bResult = (count($aErrors) == 0);
			if ($bResult) {
				[$bResult, $aErrors] = $oObj->CheckToWrite();
			}
			if ($bPreview) {
				$sStatus = $bResult ? Dict::S('UI:BulkModifyStatusOk') : Dict::S('UI:BulkModifyStatusError');
			} else {
				$sStatus = $bResult ? Dict::S('UI:BulkModifyStatusModified') : Dict::S('UI:BulkModifyStatusSkipped');
			}

			$aErrorsToDisplay = array_map(function($sError) {
				return utils::HtmlEntities($sError);
			}, $aErrors);
			$aRows[] = array(
				'object' => $oObj->GetHyperlink(),
				'status' => $sStatus,
				'errors' => '<p>'.($bResult ? '' : implode('</p><p>', $aErrorsToDisplay)).'</p>',
			);
			if ($bResult && (!$bPreview)) {
				// doing the check will load multiple times same objects :/
				// but it shouldn't cost too much on execution time
				// user can mitigate by selecting less extkeys/lnk to set and/or less objects to update 🤷‍♂️
				$oObj->CheckChangedExtKeysValues();

				$oObj->DBUpdate();
			}
		}
		// Send all the retained events for further computations
		static::SetEventDBLinksChangedBlocked(false);
		static::FireEventDbLinksChangedForAllObjects();

		set_time_limit(intval($iPreviousTimeLimit));
		$oTable = DataTableUIBlockFactory::MakeForForm('BulkModify', $aHeaders, $aRows);
		$oTable->AddOption("bFullscreen", true);

		$oPanel = PanelUIBlockFactory::MakeForClass($sClass, '');
		$oPanel->SetIcon($sClassIcon);
		$oPanel->SetTitle($sHeaderTitle);
		$oPanel->AddCSSClass('ibo-datatable-panel');
		$oPanel->AddSubBlock($oTable);


		if ($bPreview) {
			$sFormAction = utils::GetAbsoluteUrlAppRoot().'pages/UI.php'; // No parameter in the URL, the only parameter will be the ones passed through the form
			// Form to submit:
			$oForm = FormUIBlockFactory::MakeStandard('')->SetAction($sFormAction);
			$oP->AddSubBlock($oForm);
			$oForm->AddSubBlock($oPanel);

			$oAppContext = new ApplicationContext();
			$oP->add($oAppContext->GetForForm());
			foreach ($aContextData as $sKey => $value) {
				$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden($sKey, $value));
			}
			$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('operation', $sCustomOperation));
			$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('class', $sClass));
			$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('preview_mode', 0));
			$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('transaction_id', utils::GetNewTransactionId()));

			$oToolbarButtons = ToolbarUIBlockFactory::MakeStandard(null);
			$oToolbarButtons->AddCSSClass('ibo-toolbar--button');
			$oForm->AddSubBlock($oToolbarButtons);
			$oToolbarButtons->AddSubBlock(ButtonUIBlockFactory::MakeForCancel(Dict::S('UI:Button:Cancel'))->SetOnClickJsCode("window.location.href='$sCancelUrl'"));
			$oToolbarButtons->AddSubBlock(ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:ModifyAll'), '', '', true));

			foreach ($_POST as $sKey => $value) {
				if (preg_match('/attr_(.+)/', $sKey, $aMatches)) {
					// Beware: some values (like durations) are passed as arrays
					if (is_array($value)) {
						foreach ($value as $vKey => $vValue) {
							$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden($sKey.'['.$vKey.']', $vValue));
						}
					} else {
						$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden($sKey, $value));
					}
				}
			}
		} else {
			$oP->AddUiBlock($oPanel);
			$oP->AddSubBlock(ButtonUIBlockFactory::MakeForSecondaryAction(Dict::S('UI:Button:Done')))->SetOnClickJsCode("window.location.href='$sCancelUrl'")->AddCSSClass('mt-5');
		}
	}

	/**
	 * Perform all the needed checks to delete one (or more) objects
	 *
	 * @param WebPage $oP
	 * @param $sClass
	 * @param \DBObject[] $aObjects
	 * @param $bPreview
	 * @param $sCustomOperation
	 * @param array $aContextData
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
	 */
	public static function DeleteObjects(WebPage $oP, $sClass, $aObjects, $bPreview, $sCustomOperation, $aContextData = array())
	{
		$oDeletionPlan = new DeletionPlan();

		// Avoid too many events
		static::SetEventDBLinksChangedBlocked(true);
		try {
			foreach ($aObjects as $oObj) {
				if ($bPreview) {
					$oObj->CheckToDelete($oDeletionPlan);
				} else {
					$oObj->DBDelete($oDeletionPlan);
				}
			}
		} finally {
			// Send all the retained events for further computations
			static::SetEventDBLinksChangedBlocked(false);
			static::FireEventDbLinksChangedForAllObjects();
		}

		if ($bPreview) {
			if (count($aObjects) == 1) {
				$oObj = $aObjects[0];
				$sTitle = Dict::Format('UI:Delete:ConfirmDeletionOf_Name', $oObj->GetRawName());
			} else {
				$sTitle = Dict::Format('UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class', count($aObjects),
					MetaModel::GetName($sClass));
			}
			$oP->AddUiBlock(TitleUIBlockFactory::MakeForPage($sTitle));

			// Explain what should be done
			//
			$aDisplayData = array();
			foreach ($oDeletionPlan->ListDeletes() as $sTargetClass => $aDeletes) {
				foreach ($aDeletes as $iId => $aData) {
					$oToDelete = $aData['to_delete'];
					$bAutoDel = (($aData['mode'] == DEL_SILENT) || ($aData['mode'] == DEL_AUTO));
					$sRowCssClass = '';
					if (array_key_exists('issue', $aData))
					{
						if ($bAutoDel)
						{
							if (isset($aData['requested_explicitely']))
							{
								$sConsequence = Dict::Format('UI:Delete:CannotDeleteBecause', $aData['issue']);
							}
							else
							{
								$sConsequence = Dict::Format('UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible',
									$aData['issue']);
							}
						}
						else
						{
							$sConsequence = Dict::Format('UI:Delete:MustBeDeletedManuallyButNotPossible',
								$aData['issue']);
						}
						$sRowCssClass = 'ibo-is-alert';
					}
					else
					{
						if ($bAutoDel)
						{
							if (isset($aData['requested_explicitely']))
							{
								$sConsequence = ''; // not applicable
							}
							else
							{
								$sConsequence = Dict::S('UI:Delete:WillBeDeletedAutomatically');
							}
						}
						else
						{
							$sConsequence = Dict::S('UI:Delete:MustBeDeletedManually');
							$sRowCssClass = 'ibo-is-warning';
						}
					}
					$aDisplayData[] = array(
						'@class' => $sRowCssClass,
						'class' => MetaModel::GetName(get_class($oToDelete)),
						'object' => $oToDelete->GetHyperLink(),
						'consequence' => $sConsequence,
					);
				}
			}
			foreach($oDeletionPlan->ListUpdates() as $sRemoteClass => $aToUpdate)
			{
				foreach($aToUpdate as $iId => $aData)
				{
					$oToUpdate = $aData['to_reset'];
					$sRowCssClass = '';
					if (array_key_exists('issue', $aData))
					{
						$sConsequence = Dict::Format('UI:Delete:CannotUpdateBecause_Issue', $aData['issue']);
						$sRowCssClass = 'ibo-is-alert';
					}
					else
					{
						$sConsequence = Dict::Format('UI:Delete:WillAutomaticallyUpdate_Fields',
							$aData['attributes_list']);
					}
					$aDisplayData[] = array(
						'@class' => $sRowCssClass,
						'class' => MetaModel::GetName(get_class($oToUpdate)),
						'object' => $oToUpdate->GetHyperLink(),
						'consequence' => $sConsequence,
					);
				}
			}

			$iImpactedIndirectly = $oDeletionPlan->GetTargetCount() - count($aObjects);
			$sImpactedTableTitle = '';
			$sImpactedTableSubtitle = '';
			if ($iImpactedIndirectly > 0)
			{
				if (count($aObjects) == 1)
				{
					$oObj = $aObjects[0];
					$sImpactedTableTitle = Dict::Format('UI:Delete:Count_Objects/LinksReferencing_Object', $iImpactedIndirectly,
						$oObj->GetName());
				}
				else
				{
					$sImpactedTableTitle = Dict::Format('UI:Delete:Count_Objects/LinksReferencingTheObjects', $iImpactedIndirectly);
				}
				$sImpactedTableSubtitle = Dict::S('UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity');
			}

			if (($iImpactedIndirectly > 0) || $oDeletionPlan->FoundStopper())
			{
				$aDisplayConfig = array();
				$aDisplayConfig['class'] = array('label' => 'Class', 'description' => '');
				$aDisplayConfig['object'] = array('label' => 'Object', 'description' => '');
				$aDisplayConfig['consequence'] = array(
					'label' => 'Consequence',
					'description' => Dict::S('UI:Delete:Consequence+'),
				);
				$oBlock = PanelUIBlockFactory::MakeNeutral($sImpactedTableTitle, $sImpactedTableSubtitle);

				$oDataTable = DataTableUIBlockFactory::MakeForForm(utils::Sanitize(uniqid('form_', true), '', utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER), $aDisplayConfig, $aDisplayData);
				$oBlock->AddSubBlock($oDataTable);
				$oP->AddUiBlock($oBlock);
			}

			if ($oDeletionPlan->FoundStopper()) {
				if ($oDeletionPlan->FoundSecurityIssue()) {
					$oFailAlertBlock = AlertUIBlockFactory::MakeForDanger('', Dict::S('UI:Delete:SorryDeletionNotAllowed'));
					$oFailAlertBlock->SetIsClosable(false);
					$oP->AddUiBlock($oFailAlertBlock);
				}
				else {
					$oWarningAlertBlock = AlertUIBlockFactory::MakeForWarning('', Dict::S('UI:Delete:PleaseDoTheManualOperations'));
					$oWarningAlertBlock->SetIsClosable(false);
					$oP->AddUiBlock($oWarningAlertBlock);
				}

				$oForm = FormUIBlockFactory::MakeStandard('');
				$oP->AddSubBlock($oForm);
				$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('transaction_id', utils::ReadParam('transaction_id', '', false, 'transaction_id')));
				$oToolbarButtons = ToolbarUIBlockFactory::MakeStandard(null);
				$oToolbarButtons->AddCSSClass('ibo-toolbar--button');
				$oForm->AddSubBlock($oToolbarButtons);
				$oToolbarButtons->AddSubBlock(ButtonUIBlockFactory::MakeForCancel(Dict::S('UI:Button:Back'))->SetOnClickJsCode("window.history.back();"));
				$oToolbarButtons->AddSubBlock(ButtonUIBlockFactory::MakeForDestructiveAction(Dict::S('UI:Button:Delete'), null, null, true)->SetIsDisabled(true));
				$oAppContext = new ApplicationContext();
				$oForm->AddSubBlock($oAppContext->GetForFormBlock());
			}
			else {
				if (count($aObjects) == 1) {
					$oObj = $aObjects[0];
					$sSubtitle = Dict::Format('UI:Delect:Confirm_Object', $oObj->GetHyperLink());
				} else {
					$sSubtitle = Dict::Format('UI:Delect:Confirm_Count_ObjectsOf_Class', count($aObjects),
						MetaModel::GetName($sClass));
				}

				foreach ($aObjects as $oObj) {
					$aKeys[] = $oObj->GetKey();
				}
				$oFilter = new DBObjectSearch($sClass);
				$oFilter->AddCondition('id', $aKeys, 'IN');
				$oSet = new CMDBobjectSet($oFilter);
				$oDisplaySet = UIContentBlockUIBlockFactory::MakeStandard("0");
				$oP->AddSubBlock($oDisplaySet);
				$oDisplaySet->AddSubBlock(CMDBAbstractObject::GetDisplaySetBlock($oP, $oSet, array(
					'display_limit' => false,
					'menu' => false,
					'surround_with_panel' => true,
					'panel_title' => $sSubtitle,
					'panel_title_is_html' => true,
					'panel_icon' => MetaModel::GetClassIcon($sClass, false),
					'panel_class' => $sClass,
				)));

				$oForm = FormUIBlockFactory::MakeStandard('');
				$oP->AddSubBlock($oForm);
				foreach ($aContextData as $sKey => $value) {
					$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden($sKey, $value));
				}
				$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('transaction_id', utils::GetNewTransactionId()));
				$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('operation', $sCustomOperation));
				$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('filter', $oFilter->Serialize()));
				$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('class', $sClass));
				foreach ($aObjects as $oObj) {
					$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('selectObject[]', $oObj->GetKey()));
				}

				$oToolbarButtons = ToolbarUIBlockFactory::MakeStandard(null);
				$oToolbarButtons->AddCSSClass('ibo-toolbar--button');
				$oForm->AddSubBlock($oToolbarButtons);
				$oToolbarButtons->AddSubBlock(ButtonUIBlockFactory::MakeForCancel(Dict::S('UI:Button:Back'))->SetOnClickJsCode("window.history.back();"));
				$oToolbarButtons->AddSubBlock(ButtonUIBlockFactory::MakeForDestructiveAction(Dict::S('UI:Button:Delete'), null, null, true));
				$oAppContext = new ApplicationContext();
				$oForm->AddSubBlock($oAppContext->GetForFormBlock());

			}
		}
		else // if ($bPreview)...
		{
			// Execute the deletion
			//
			if (count($aObjects) == 1) {
				$oObj = $aObjects[0];
				$sTitle = Dict::Format('UI:Title:DeletionOf_Object', $oObj->GetRawName());
			} else {
				$sTitle = Dict::Format('UI:Title:BulkDeletionOf_Count_ObjectsOf_Class', count($aObjects), MetaModel::GetName($sClass));
			}
			$oP->AddUiBlock(TitleUIBlockFactory::MakeForPage($sTitle));

			// Security - do not allow the user to force a forbidden delete by the mean of page arguments...
			if ($oDeletionPlan->FoundSecurityIssue()) {
				throw new CoreException(Dict::S('UI:Error:NotEnoughRightsToDelete'));
			}
			if ($oDeletionPlan->FoundManualOperation()) {
				throw new CoreException(Dict::S('UI:Error:CannotDeleteBecauseManualOpNeeded'));
			}
			if ($oDeletionPlan->FoundManualDelete())
			{
				throw new CoreException(Dict::S('UI:Error:CannotDeleteBecauseOfDepencies'));
			}

			// Report deletions
			//
			$aDisplayData = array();
			foreach($oDeletionPlan->ListDeletes() as $sTargetClass => $aDeletes)
			{
				foreach($aDeletes as $iId => $aData)
				{
					$oToDelete = $aData['to_delete'];

					if (isset($aData['requested_explicitely']))
					{
						$sMessage = Dict::S('UI:Delete:Deleted');
					}
					else
					{
						$sMessage = Dict::S('UI:Delete:AutomaticallyDeleted');
					}
					$aDisplayData[] = array(
						'class' => MetaModel::GetName(get_class($oToDelete)),
						'object' => $oToDelete->GetName(),
						'consequence' => $sMessage,
					);
				}
			}

			// Report updates
			//
			foreach($oDeletionPlan->ListUpdates() as $sTargetClass => $aToUpdate)
			{
				foreach($aToUpdate as $iId => $aData)
				{
					$oToUpdate = $aData['to_reset'];
					$aDisplayData[] = array(
						'class' => MetaModel::GetName(get_class($oToUpdate)),
						'object' => $oToUpdate->GetHyperLink(),
						'consequence' => Dict::Format('UI:Delete:AutomaticResetOf_Fields', $aData['attributes_list']),
					);
				}
			}

			// Report automatic jobs
			//
			if ($oDeletionPlan->GetTargetCount() > 0) {
				if (count($aObjects) == 1) {
					$oObj = $aObjects[0];
					$sSubtitle = Dict::Format('UI:Delete:CleaningUpRefencesTo_Object', $oObj->GetName());
				} else {
					$sSubtitle = Dict::Format('UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class', count($aObjects),
						MetaModel::GetName($sClass));
				}

				$aDisplayConfig = array();
				$aDisplayConfig['class'] = array('label' => 'Class', 'description' => '');
				$aDisplayConfig['object'] = array('label' => 'Object', 'description' => '');
				$aDisplayConfig['consequence'] = array('label' => 'Done', 'description' => Dict::S('UI:Delete:Done+'));

				$oResultsPanel = PanelUIBlockFactory::MakeForInformation($sSubtitle);
				$oP->AddUiBlock($oResultsPanel);
				$oDatatable = DataTableUIBlockFactory::MakeForStaticData('', $aDisplayConfig, $aDisplayData);
				$oResultsPanel->AddSubBlock($oDatatable);
			}
		}
	}

	/**
	 * Find redundancy settings that can be viewed and modified in a tab
	 * Settings are distributed to the corresponding link set attribute so as to be shown in the relevant tab
	 *
	 * @throws \Exception
	 */
	protected function FindVisibleRedundancySettings()
	{
		$aRet = array();
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode => $oAttDef)
		{
			if ($oAttDef instanceof AttributeRedundancySettings)
			{
				if ($oAttDef->IsVisible())
				{
					$aQueryInfo = $oAttDef->GetRelationQueryData();
					if (isset($aQueryInfo['sAttribute']))
					{
						$oUpperAttDef = MetaModel::GetAttributeDef($aQueryInfo['sFromClass'],
							$aQueryInfo['sAttribute']);
						$oHostAttDef = $oUpperAttDef->GetMirrorLinkAttribute();
						if ($oHostAttDef)
						{
							$sHostAttCode = $oHostAttDef->GetCode();
							$aRet[$sHostAttCode][] = $oAttDef;
						}
					}
				}
			}
		}

		return $aRet;
	}

	/**
	 * Generates the javascript code handle the "watchdog" associated with the concurrent access locking mechanism
	 *
	 * @param Webpage $oPage
	 * @param string $sOwnershipToken
	 */
	protected function GetOwnershipJSHandler($oPage, $sOwnershipToken)
	{
		$iInterval = max(MIN_WATCHDOG_INTERVAL,
				MetaModel::GetConfig()->Get('concurrent_lock_expiration_delay')) * 1000 / 2; // Minimum interval for the watchdog is MIN_WATCHDOG_INTERVAL
		$sJSClass = json_encode(get_class($this));
		$iKey = (int)$this->GetKey();
		$sJSToken = json_encode($sOwnershipToken);
		$sJSTitle = json_encode(Dict::S('UI:DisconnectedDlgTitle'));
		$sJSOk = json_encode(Dict::S('UI:Button:Ok'));
		$oPage->add_ready_script(
			<<<JS
		// Prepare reusable modal
		const oOwnershipLockModal = $('<div></div>').dialog({
			title: $sJSTitle,
			modal: true,
			autoOpen: false,
			minWidth: 600,
			buttons:[{
				text: $sJSOk,
				class: 'ibo-is-alternative',
				click: function() { $(this).dialog('close'); }
			}],
			close: function() { $(this).dialog('close'); }
		});
		// Start periodic handler
		let hOwnershipLockHandlerInterval = window.setInterval(function() {
			if (window.bInSubmit || window.bInCancel) return;
			
			$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', {operation: 'extend_lock', obj_class: $sJSClass, obj_key: $iKey, token: $sJSToken }, function(data) {
				if (!data.status)
				{
					if ($('.lock_owned').length == 0)
					{
						$('.ui-layout-content').prepend('<div class="header_message message_error lock_owned">'+data.message+'</div>');
						oOwnershipLockModal.text(data.popup_message);
						oOwnershipLockModal.dialog('open');
					}
					$('[data-role="ibo-object-details"][data-object-class={$sJSClass}][data-object-id="{$iKey}"] .ibo-toolbar .ibo-button:not([name="cancel"])').prop('disabled', true);
					clearInterval(hOwnershipLockHandlerInterval);
				}
				else if ((data.operation == 'lost') || (data.operation == 'expired'))
				{
					if ($('.lock_owned').length == 0)
					{
						$('.ui-layout-content').prepend('<div class="header_message message_error lock_owned">'+data.message+'</div>');
						oOwnershipLockModal.text(data.popup_message);
						oOwnershipLockModal.dialog('open');
					}
					$('[data-role="ibo-object-details"][data-object-class={$sJSClass}][data-object-id="{$iKey}"] .ibo-toolbar .ibo-button:not([name="cancel"])').prop('disabled', true);
					clearInterval(hOwnershipLockHandlerInterval);
				}
			}, 'json');
		}, $iInterval);
JS
		);
	}

	/**
	 * Return an array of AttributeDefinition EditClass that should be rendered as large field in the UI
	 *
	 * @return array
	 * @since 2.7.0
	 */
	protected static function GetAttEditClassesToRenderAsLargeField(){
		return array(
			'CaseLog',
			'CustomFields',
			'HTML',
			'OQLExpression',
			'Text',
		);
	}

	/**
	 * Return an array of AttributeDefinition classes that should be excluded from the markup metadata when priting raw value (typically large values)
	 * This markup is mostly aimed at CSS/JS hooks for extensions and Behat tests
	 *
	 * @return array
	 * @since 2.7.0
	 *
	 * @internal Do NOT use, this is experimental and most likely to be moved elsewhere when we find its rightful place.
	 */
	public static function GetAttDefClassesToExcludeFromMarkupMetadataRawValue(){
		return array(
			'AttributeBlob',
			'AttributeCustomFields',
			'AttributeDashboard',
			'AttributeLinkedSet',
			'AttributeStopWatch',
			'AttributeSubItem',
			'AttributeTable',
			'AttributeText',
			'AttributePassword',
			'AttributeOneWayPassword',
		);
	}

	//////////////////
	/// CREATE
	///

	/**
	 * @return void
	 * @throws \CoreException
	 *
	 * @since 3.1.0
	 */
	final protected function FireEventCheckToWrite(): void
	{
		$this->FireEvent(EVENT_DB_CHECK_TO_WRITE, ['is_new' => $this->IsNew()]);
	}

	final protected function FireEventBeforeWrite()
	{
		$this->FireEvent(EVENT_DB_BEFORE_WRITE, ['is_new' => $this->IsNew()]);
	}

	/**
	 * @param array $aChanges
	 * @param bool $bIsNew
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @since 3.1.0
	 */
	final protected function FireEventAfterWrite(array $aChanges, bool $bIsNew): void
	{
		$this->NotifyAttachedObjectsOnLinkClassModification();
		$this->FireEventDbLinksChangedForCurrentObject();
		$this->FireEvent(EVENT_DB_AFTER_WRITE, ['is_new' => $bIsNew, 'changes' => $aChanges]);
	}

	//////////////
	/// DELETE
	///

	/**
	 * @param \DeletionPlan $oDeletionPlan
	 *
	 * @return void
	 * @throws \CoreException
	 * @since 3.1.0
	 */
	final protected function FireEventCheckToDelete(DeletionPlan $oDeletionPlan): void
	{
		$this->FireEvent(EVENT_DB_CHECK_TO_DELETE, ['deletion_plan' => $oDeletionPlan]);
	}

	/**
	 * @return void
	 * @throws \CoreException
	 * @since 3.1.2
	 */
	final protected function FireEventAboutToDelete(): void
	{
		$this->FireEvent(EVENT_DB_ABOUT_TO_DELETE);
	}

	/**
	 * @return void
	 * @throws \CoreException
	 *
	 * @since 3.1.0
	 */
	final protected function FireEventAfterDelete(): void
	{
		$this->NotifyAttachedObjectsOnLinkClassModification();
		$this->FireEvent(EVENT_DB_AFTER_DELETE);
	}

	/**
	 * Possibility for linked classes to be notified of current class modification
	 *
	 * If an external key was modified, register also the previous object that was linked previously.
	 *
	 * @uses static::RegisterObjectAwaitingEventDbLinksChanged()
	 *
	 * @throws ArchivedObjectException
	 * @throws CoreException
	 * @throws Exception
	 *
	 * @since 3.1.0 N°5906 method creation
	 * @since 3.1.1 3.2.0 N°6228 now just notify attributes having `with_php_computation`
	 */
	final protected function NotifyAttachedObjectsOnLinkClassModification(): void
	{
		// previous values in case of link change
		$aPreviousValues = $this->ListPreviousValuesForUpdatedAttributes();
		$sClass = get_class($this);
		$aClassExtKeyAttCodes = MetaModel::GetAttributesList($sClass, [AttributeExternalKey::class]);
		foreach ($aClassExtKeyAttCodes as $sExternalKeyAttCode) {
			/** @var AttributeExternalKey $oAttDef */
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sExternalKeyAttCode);

			if (false === $this->DoesTargetObjectHavePhpComputation($oAttDef)) {
				continue;
			}

			$sTargetObjectId = $this->Get($sExternalKeyAttCode);
			$sTargetClass = $oAttDef->GetTargetClass();
			if ($sTargetObjectId > 0) {
				$this->LogCRUDDebug(__METHOD__, "Add $sTargetClass:$sTargetObjectId for DBLINKS_CHANGED");
				self::RegisterObjectAwaitingEventDbLinksChanged($sTargetClass, $sTargetObjectId);
			}

			$sPreviousTargetObjectId = $aPreviousValues[$sExternalKeyAttCode] ?? 0;
			if ($sPreviousTargetObjectId > 0) {
				$this->LogCRUDDebug(__METHOD__, "Add $sTargetClass:$sPreviousTargetObjectId for DBLINKS_CHANGED");
				self::RegisterObjectAwaitingEventDbLinksChanged($sTargetClass, $sPreviousTargetObjectId);
			}
		}
	}

	private function DoesTargetObjectHavePhpComputation(AttributeExternalKey $oAttDef): bool
	{
		/** @var AttributeLinkedSet $oAttDefMirrorLink */
		$oAttDefMirrorLink = $oAttDef->GetMirrorLinkAttribute();
		if (is_null($oAttDefMirrorLink) || false === $oAttDefMirrorLink->HasPHPComputation()){
			return false;
		}

		return true;
	}

	/**
	 * Register one object for later EVENT_DB_LINKS_CHANGED event.
	 *
	 * @param string $sClass
	 * @param string|int|null $sId
	 *
	 * @since 3.1.0 N°5906
	 */
	private static function RegisterObjectAwaitingEventDbLinksChanged(string $sClass, $sId): void
	{
		if (isset(self::$aObjectsAwaitingEventDbLinksChanged[$sClass][$sId])) {
			self::$aObjectsAwaitingEventDbLinksChanged[$sClass][$sId]++;
		} else {
			self::$aObjectsAwaitingEventDbLinksChanged[$sClass][$sId] = 1;
		}
	}

	/**
	 * Fire the EVENT_DB_LINKS_CHANGED event if current object is registered
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 *
	 * @since 3.1.0 N°5906
	 */
	final protected function FireEventDbLinksChangedForCurrentObject(): void
	{
		if (true === static::IsEventDBLinksChangedBlocked()) {
			return;
		}

		$sClass = get_class($this);
		$sId = $this->GetKey();
		$bIsObjectAwaitingEventDbLinksChanged = self::RemoveObjectAwaitingEventDbLinksChanged($sClass, $sId);
		if (false === $bIsObjectAwaitingEventDbLinksChanged) {
			return;
		}
		self::FireEventDbLinksChangedForObject($this);
		self::RemoveObjectAwaitingEventDbLinksChanged($sClass, $sId);
	}

	/**
	 * Fire the EVENT_DB_LINKS_CHANGED event if given object is registered, and unregister it
	 *
	 * @param string $sClass
	 * @param string|int|null $sId
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	private static function FireEventDbLinksChangedForClassId(string $sClass, $sId): void
	{
		if (true === static::IsEventDBLinksChangedBlocked()) {
			return;
		}

		$bIsObjectAwaitingEventDbLinksChanged = self::RemoveObjectAwaitingEventDbLinksChanged($sClass, $sId);
		if (false === $bIsObjectAwaitingEventDbLinksChanged) {
			return;
		}

		// First we are disabling firing the event to avoid reentrance
		// For example on a Ticket :
		// - in the Ticket CRUD stack, DBWriteLinks will generate lnkApplicationSolutionToFunctionalCI instances
		// - therefore the $aObjectsAwaitingEventDbLinksChanged attribute will contain our Ticket
		// - we have a EVENT_DB_LINKS_CHANGED listener on Ticket that will update impacted items, so it will create new lnkApplicationSolutionToFunctionalCI
		// We want to avoid launching the listener twice, first here, and secondly after saving the Ticket in the listener
		// By disabling the event to be fired, we can remove the current object from the attribute !
		$oObject = MetaModel::GetObject($sClass, $sId, false);
		self::FireEventDbLinksChangedForObject($oObject);
		self::RemoveObjectAwaitingEventDbLinksChanged($sClass, $sId);
	}

	private static function FireEventDbLinksChangedForObject(DBObject $oObject)
	{
		self::SetEventDBLinksChangedBlocked(true);
		// N°6408 The object can have been deleted
		if (!is_null($oObject)) {
			MetaModel::StartReentranceProtection($oObject);
			$oObject->FireEvent(EVENT_DB_LINKS_CHANGED);
			MetaModel::StopReentranceProtection($oObject);
			if (count($oObject->ListChanges()) !== 0) {
				$oObject->DBUpdate();
			}
		}
		cmdbAbstractObject::SetEventDBLinksChangedBlocked(false);
	}

	/**
	 * Remove the registration of an object concerning the EVENT_DB_LINKS_CHANGED event
	 *
	 * @param string $sClass
	 * @param string|int|null $sId
	 *
	 * @return bool true if the object [class, id] was present in the list
	 * @throws \CoreException
	 */
	final protected static function RemoveObjectAwaitingEventDbLinksChanged(string $sClass, $sId): bool
	{
		$bFlagRemoved = false;
		$aClassesHierarchy = MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_ALL, false);
		foreach ($aClassesHierarchy as $sClassInHierarchy) {
			if (isset(self::$aObjectsAwaitingEventDbLinksChanged[$sClassInHierarchy][$sId])) {
				unset(self::$aObjectsAwaitingEventDbLinksChanged[$sClassInHierarchy][$sId]);
				$bFlagRemoved = true;
			}
		}

		return $bFlagRemoved;
	}

	/**
	 * Fire the EVENT_DB_LINKS_CHANGED event to all the registered objects
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 *
	 * @since 3.1.0 N°5906
	 */
	final public static function FireEventDbLinksChangedForAllObjects()
	{
		if (true === static::IsEventDBLinksChangedBlocked()) {
			return;
		}

		foreach (self::$aObjectsAwaitingEventDbLinksChanged as $sClass => $aClassInstances) {
			foreach ($aClassInstances as $sId => $iCallsNumber) {
				self::FireEventDbLinksChangedForClassId($sClass, $sId);
			}
		}
	}

	/**
	 * Check if the event EVENT_DB_LINKS_CHANGED is blocked or not (for bulk operations)
	 *
	 * @return bool
	 *
	 * @since 3.1.0 N°5906
	 */
	final public static function IsEventDBLinksChangedBlocked(): bool
	{
		return self::$bBlockEventDBLinksChanged;
	}

	/**
	 * Block/unblock the event EVENT_DB_LINKS_CHANGED (the registration of objects on links modifications continues to work)
	 *
	 * @param bool $bBlockEventDBLinksChanged
	 *
	 * @since 3.1.0 N°5906
	 */
	final public static function SetEventDBLinksChangedBlocked(bool $bBlockEventDBLinksChanged): void
	{
		self::$bBlockEventDBLinksChanged = $bBlockEventDBLinksChanged;
	}

	/**
	 * @inheritDoc
	 * @throws \CoreException
	 */
	final protected function FireEventComputeValues(): void
	{
		$this->FireEvent(EVENT_DB_COMPUTE_VALUES);
	}

	/**
	 * @inheritDoc
	 * @throws \CoreException
	 */
	final protected function FireEventArchive(): void
	{
		$this->FireEvent(EVENT_DB_ARCHIVE);
	}

	/**
	 * @inheritDoc
	 * @throws \CoreException
	 */
	final protected function FireEventUnArchive(): void
	{
		$this->FireEvent(EVENT_DB_UNARCHIVE);
	}

	/**
	 * Append $iFlags to $sAttCode attribute in $sTargetState
	 *
	 * @api
	 * @param string $sAttCode
	 * @param int $iFlags
	 * @param string $sTargetState
	 * @param string|null $sReason
	 *
	 * @return void
	 * @since 3.1.0
	 */
	final public function AddAttributeFlags(string $sAttCode, int $iFlags, string $sTargetState = '', string $sReason = null): void
	{
		if (!isset($this->aAttributesFlags[$sTargetState])) {
			$this->aAttributesFlags[$sTargetState] = [];
		}
		$this->aAttributesFlags[$sTargetState][$sAttCode]['flags'] = ($this->aAttributesFlags[$sTargetState][$sAttCode]['flags'] ?? 0) | $iFlags;
		if (!is_null($sReason)) {
			$this->aAttributesFlags[$sTargetState][$sAttCode]['reasons'][] = $sReason;
		}
	}

	/**
	 * Force $iFlags to $sAttCode attribute in $sTargetState
	 *
	 * @api
	 * @param string $sAttCode
	 * @param int $iFlags
	 * @param string $sTargetState
	 * @param string|null $sReason
	 *
	 * @return void
	 * @since 3.1.0
	 */
	final public function ForceAttributeFlags(string $sAttCode, int $iFlags, string $sTargetState = '', string $sReason = null): void
	{
		if (!isset($this->aAttributesFlags[$sTargetState])) {
			$this->aAttributesFlags[$sTargetState] = [];
		}
		$this->aAttributesFlags[$sTargetState][$sAttCode]['flags'] = $iFlags;
		if (!is_null($sReason)) {
			$this->aAttributesFlags[$sTargetState][$sAttCode]['reasons'] = [$sReason];
		}
	}

	/**
	 * @inheritDoc
	 * @throws \CoreException
	 */
	final protected function GetExtensionsAttributeFlags(string $sAttCode, array &$aReasons, string $sTargetState): int
	{
		if (!isset($this->aAttributesFlags[$sTargetState])) {
			$this->aAttributesFlags[$sTargetState] = [];
			$aEventData = [
				'target_state' => $sTargetState,
			];
			$this->FireEvent(EVENT_DB_SET_ATTRIBUTES_FLAGS, $aEventData);
		}
		$iFlags = $this->aAttributesFlags[$sTargetState][$sAttCode]['flags'] ?? 0;
		$aReasons += ($this->aAttributesFlags[$sTargetState][$sAttCode]['reasons'] ?? []);

		return $iFlags;
	}


	/**
	 * Append $iFlags to $sAttCode attribute in initial state
	 *
	 * @api
	 * @param string $sAttCode
	 * @param int $iFlags
	 * @param string|null $sReason
	 *
	 * @return void
	 * @since 3.1.0
	 */
	final public function AddInitialAttributeFlags(string $sAttCode, int $iFlags, string $sReason = null)
	{
		if (!isset($this->aInitialAttributesFlags)) {
			$this->aInitialAttributesFlags = [];
		}
		$this->aInitialAttributesFlags[$sAttCode]['flags'] = ($this->aInitialAttributesFlags[$sAttCode]['flags'] ?? 0) | $iFlags;
		if (!is_null($sReason)) {
			$this->aInitialAttributesFlags[$sAttCode]['reasons'][] = $sReason;
		}
	}

	/**
	 * Force $iFlags to $sAttCode attribute in initial state
	 *
	 * @api
	 * @param string $sAttCode
	 * @param int $iFlags
	 * @param string|null $sReason
	 *
	 * @return void
	 * @since 3.1.0
	 */
	final public function ForceInitialAttributeFlags(string $sAttCode, int $iFlags, string $sReason = null)
	{
		if (!isset($this->aInitialAttributesFlags)) {
			$this->aInitialAttributesFlags = [];
		}
		$this->aInitialAttributesFlags[$sAttCode]['flags'] = $iFlags;
		if (!is_null($sReason)) {
			$this->aInitialAttributesFlags[$sAttCode]['reasons'] = [$sReason];
		}
	}

	/**
	 * @inheritDoc
	 * @throws \CoreException
	 */
	final protected function GetExtensionsInitialStateAttributeFlags(string $sAttCode, array &$aReasons): int
	{
		if (!isset($this->aInitialAttributesFlags)) {
			$this->aInitialAttributesFlags = [];
			$this->FireEvent(EVENT_DB_SET_INITIAL_ATTRIBUTES_FLAGS);
		}
		$iFlags = $this->aInitialAttributesFlags[$sAttCode]['flags'] ?? 0;
		$aReasons += ($this->aInitialAttributesFlags[$sAttCode]['reasons'] ?? []);

		return $iFlags;
	}

}
