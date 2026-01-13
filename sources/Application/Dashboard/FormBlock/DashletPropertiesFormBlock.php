<?php

namespace Combodo\iTop\Application\Dashboard\FormBlock;

use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\IO\Format\StringIOFormat;
use Combodo\iTop\Forms\Register\IORegister;
use Combodo\iTop\PropertyType\PropertyTypeService;

class DashletPropertiesFormBlock extends FormBlock
{
	// inputs
	public const INPUT_DASHLET_TYPE = 'dashlet_type';

	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddInput(self::INPUT_DASHLET_TYPE, StringIOFormat::class);
	}

	public function GetFormType(): string
	{
		$sDashletType = strval($this->GetInputValue(self::INPUT_DASHLET_TYPE));
		$oDashlet = PropertyTypeService::GetInstance()->GetFormBlockById($sDashletType, 'Dashlet');

		return $oDashlet->GetFormType();
	}

	public function GetOptions(): array
	{
		$sDashletType = strval($this->GetInputValue(self::INPUT_DASHLET_TYPE));
		$oDashlet = PropertyTypeService::GetInstance()->GetFormBlockById($sDashletType, 'Dashlet');

		return $oDashlet->GetOptions();
	}
}
