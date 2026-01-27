<?php

namespace Combodo\iTop\Application\Dashboard\FormBlock;

use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\IO\Format\StringIOFormat;
use Combodo\iTop\Forms\Register\IORegister;
use Combodo\iTop\PropertyType\PropertyTypeService;
use MetaModel;

class DashletPropertiesFormBlock extends FormBlock
{
	// inputs
	public const INPUT_DASHLET_TYPE = 'dashlet_type';
	private PropertyTypeService $oPropertyTypeService;

	public function __construct(string $sName, array $aOptions = [])
	{
		parent::__construct($sName, $aOptions);
		$this->oPropertyTypeService = MetaModel::GetService('PropertyTypeService');
	}

	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddInput(self::INPUT_DASHLET_TYPE, StringIOFormat::class);
	}

	public function GetFormType(): string
	{
		$sDashletType = strval($this->GetInputValue(self::INPUT_DASHLET_TYPE));
		$oDashlet = $this->oPropertyTypeService->GetFormBlockById($sDashletType, 'Dashlet');

		return $oDashlet->GetFormType();
	}

	public function GetOptions(): array
	{
		$sDashletType = strval($this->GetInputValue(self::INPUT_DASHLET_TYPE));
		$oDashlet = $this->oPropertyTypeService->GetFormBlockById($sDashletType, 'Dashlet');

		return $oDashlet->GetOptions();
	}
}
