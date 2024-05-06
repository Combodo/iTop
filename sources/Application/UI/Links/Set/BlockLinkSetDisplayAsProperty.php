<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Links\Set;

use ApplicationContext;
use AttributeLinkedSet;
use cmdbAbstractObject;
use Combodo\iTop\Application\TwigBase\Twig\TwigHelper;
use Combodo\iTop\Application\UI\Base\Component\Html\HtmlFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Service\Links\LinkSetModel;
use Combodo\iTop\Service\Links\LinkSetRepository;
use ormLinkSet;
use Twig\Environment;
use utils;

/**
 * Class BlockLinkSetDisplayAsProperty
 *
 * @internal
 * @since 3.1.0
 * @package Combodo\iTop\Application\UI\Links\Indirect
 */
class BlockLinkSetDisplayAsProperty extends UIContentBlock
{
	public const BLOCK_CODE = 'ibo-block-links-set-as-property';

	/** @var AttributeLinkedSet $oAttribute Attribute link set */
	private AttributeLinkedSet $oAttribute;

	/** @var \ormLinkSet $oValue Link set value */
	private ormLinkSet $oValue;

	/** @var string $sTargetClass Link set target class */
	private string $sTargetClass;

	/** @var ?array $aObjectsData Links objects converted as array */
	private ?array $aObjectsData;

	/** @var \Twig\Environment Twig environment */
	private Environment $oTwigEnv;

	/** @var string $sAppContext Application context */
	private string $sAppContext;

	/** @var string $sUIPage UI page */
	private string $sUIPage;


	/**
	 * Constructor.
	 *
	 * @param string $sId block identifier
	 * @param AttributeLinkedSet $oAttribute
	 * @param ormLinkSet $oValue
	 *
	 * @throws \Exception
	 * @throws \Twig\Error\LoaderError
	 */
	public function __construct(string $sId, AttributeLinkedSet $oAttribute, ormLinkSet $oValue)
	{
		parent::__construct($sId);

		// retrieve parameters
		$this->oAttribute = $oAttribute;
		$this->oValue = $oValue;

		// Initialization
		$this->Init();

		// UI Initialization
		$this->InitUI();
	}

	/**
	 * Initialization.
	 *
	 * @return void
	 * @throws \Twig\Error\LoaderError
	 */
	private function Init()
	{
		// Link set model properties
		$this->sTargetClass = LinkSetModel::GetTargetClass($this->oAttribute);
		$sTargetField = LinkSetModel::GetTargetField($this->oAttribute);

		// Get objects from linked data
		$aObjectsData = [];
		LinkSetRepository::LinksDbSetToTargetObjectArray($this->oValue, false, $aObjectsData, $this->sTargetClass, $sTargetField);
		$this->aObjectsData = array_values($aObjectsData);

		// Twig environment
		$this->oTwigEnv = TwigHelper::GetTwigEnvironment(TwigHelper::ENUM_TEMPLATES_BASE_PATH_BACKOFFICE);

		$oAppContext = new ApplicationContext();
		$this->sAppContext = $oAppContext->GetForLink();
		$this->sUIPage = cmdbAbstractObject::ComputeStandardUIPage($this->sTargetClass);
	}

	/**
	 * UI Initialization.
	 *
	 * @return void
	 * @throws \Exception
	 */
	private function InitUI()
	{
		// Error handling
		if ($this->aObjectsData === null) {
			$sMessage = "Error while displaying attribute {$this->oAttribute->GetCode()}";
			$this->AddSubBlock(HtmlFactory::MakeHtmlContent($sMessage));

			return;
		}

		// Container
		$sHtml = '<span class="'.implode(' ', $this->oAttribute->GetCssClasses()).'">';

		// Iterate throw data...
		foreach ($this->aObjectsData as $aItem) {

			// Ignore obsolete data
			if (!utils::ShowObsoleteData() && $aItem['obsolescence_flag']) {
				continue;
			}

			// Generate template
			$sTemplate = TwigHelper::RenderTemplate($this->oTwigEnv, $aItem, 'application/object/set/set_renderer');

			// Friendly name
			$sFriendlyNameForHtml = utils::HtmlEntities($aItem['friendlyname']);
			
			// Full description
			$sFullDescriptionForHtml = utils::HtmlEntities($aItem['full_description']);

			// Append value
			$sHtml .= '<a'.$this->GenerateLinkUrl($aItem['key']).' class="attribute-set-item" data-label="'.$sFriendlyNameForHtml.'" data-tooltip-content="'.$sFullDescriptionForHtml.'" data-tooltip-html-enabled="true">'.$sTemplate.'</a>';
		}

		// Close container
		$sHtml .= '</span>';

		// Make html block
		$this->AddSubBlock(HtmlFactory::MakeHtmlContent($sHtml));
	}

	/**
	 * GenerateLinkUrl.
	 *
	 * @param $id
	 *
	 * @return string
	 * @throws \Exception
	 */
	private function GenerateLinkUrl($id): string
	{
		return ' href="'
			.utils::GetAbsoluteUrlAppRoot()
			."pages/$this->sUIPage?operation=details&class=$this->sTargetClass&id=$id&$this->sAppContext"
			.'" target="_self"';
	}
}