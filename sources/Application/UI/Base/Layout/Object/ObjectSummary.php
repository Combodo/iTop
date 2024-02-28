<?php 
namespace Combodo\iTop\Application\UI\Base\Layout\Object;


use ApplicationContext;
use cmdbAbstractObject;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroupUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItemFactory;
use Combodo\iTop\Application\UI\Base\tUIContentAreas;
use Combodo\iTop\Application\UI\Base\UIBlock;
use Combodo\iTop\Core\MetaModel\FriendlyNameType;
use Combodo\iTop\Service\Router\Router;
use DBObject;
use Dict;
use MetaModel;
use URLPopupMenuItem;
use UserRights;
use utils;

/**
* Class ObjectSummary
*
* @package Combodo\iTop\Application\UI\Base\Layout\Object
* @since 3.1.0
*/
class ObjectSummary extends ObjectDetails
{
	use tUIContentAreas;


	// Overloaded constants
	/**
	 * @inheritDoc
	 */
	public const BLOCK_CODE = 'ibo-object-summary';
	/**
	 * @inheritDoc
	 */
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/object/object-summary/layout';
	/**
	 * @inheritDoc
	 */
	public const REQUIRES_ANCESTORS_DEFAULT_JS_FILES = true;
	/** @var \DBObject Object that will be displayed as summary */
	protected  $oObject;

	/** @var array Map of fields that will be displayed for the current object */
	protected $aObjectDisplayValues;
	/** @var \Combodo\iTop\Application\UI\Base\UIBlock Actions that will be displayed in the header */
	protected $oActions;

	public function __construct(DBObject $oObject, ?string $sId = null)
	{
		parent::__construct($oObject,cmdbAbstractObject::ENUM_DISPLAY_MODE_VIEW, $sId);
		$this->oObject = $oObject;
		
		$this->ComputeDetails();
		$this->SetToolBlocks([]);
		$this->ComputeActions();
	}

	/**
	 * Compute object zlists and build the Field map that will be displayed
	 * 
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	public function ComputeDetails(){
		$sClass = $this->sClassName;
		
		$aDetailsList = MetaModel::GetZListItems($sClass, 'summary');
		
		if(empty($aDetailsList)){
			$aComplementAttributeSpec = MetaModel::GetNameSpec($sClass, FriendlyNameType::COMPLEMENTARY);
			$aAdditionalField = $aComplementAttributeSpec[1];
			if (!empty($aAdditionalField)) {
				$aDetailsList = $aAdditionalField;
			}
		}
		
		$aFieldsMap = [];
		foreach ($aDetailsList as $sAttCode) {
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			$sAttLabel = MetaModel::GetLabel($sClass, $sAttCode);
			$aFieldsMap[$sAttLabel] = $this->oObject->GetAsHTML($sAttCode);
		}
		$this->aObjectDisplayValues = $aFieldsMap;
	}


	/**
	 * Build the Actions that will be displayed in the summary header
	 * 
	 * @return void
	 * @throws \Exception
	 */
	private function ComputeActions()
	{
		$oRouter = Router::GetInstance();
		$oDetailsButton = null;
		// We can pass a DBObject to the UIBlock, so we check for the DisplayModifyForm method
		if(method_exists($this->oObject, 'DisplayModifyForm') && UserRights::IsActionAllowed($this->sClassName, UR_ACTION_MODIFY)) {
			$oPopoverMenu = new PopoverMenu();
			
			$oDetailsAction = new URLPopupMenuItem(
				'UI:Menu:View',
				Dict::S('UI:Menu:View'),
				ApplicationContext::MakeObjectUrl($this->sClassName, $this->sObjectId),
				'_blank'
			); 
			$oModifyButton = ButtonUIBlockFactory::MakeLinkNeutral(
				$oRouter->GenerateUrl('object.modify', ['class' => $this->sClassName, 'id' => $this->sObjectId]),
				Dict::S('UI:Menu:Modify'),
				'fas fa-external-link-alt',
				'_blank',
			);
			
			$oPopoverMenu->AddItem('more-actions', PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem($oDetailsAction))->SetContainer(PopoverMenu::ENUM_CONTAINER_PARENT);
			
			$oDetailsButton = ButtonGroupUIBlockFactory::MakeButtonWithOptionsMenu($oModifyButton, $oPopoverMenu);
		}
		else {
			$oDetailsButton = ButtonUIBlockFactory::MakeLinkNeutral(
				ApplicationContext::MakeObjectUrl($this->sClassName, $this->sObjectId),
				Dict::S('UI:Menu:View'),
				'fas fa-external-link-alt',
				'_blank',
			);
		}
		
		$this->oActions = $oDetailsButton;
		$this->AddToolbarBlock($oDetailsButton);
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\UIBlock
	 */
	public function GetActions(): UIBlock
	{
		return $this->oActions;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Base\UIBlock $oActions
	 *
	 * @return $this
	 */
	public function SetActions(UIBlock $oActions)
	{
		$this->oActions = $oActions;
		return $this;
	}

	/**
	 * @return array
	 */
	public function GetDisplayValues() {
		return $this->aObjectDisplayValues;
	}

	/**
	 * @param array $aObjectDisplayValues
	 *
	 * @return $this
	 */
	public function SetObjectDisplayValues(array $aObjectDisplayValues)
	{
		$this->aObjectDisplayValues = $aObjectDisplayValues;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public static function GetShortcutKeys(): array
	{
		return [];
	}

	/**
	 * @inheritDoc
	 */
	public static function GetShortcutTriggeredElementSelector(): string
	{
		return "";
	}
}