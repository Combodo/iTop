<?php

namespace Combodo\iTop\Application\Dashboard\FormBlock;

use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\Block\Base\IntegerFormBlock;
use Combodo\iTop\PropertyType\PropertyTypeService;
use MetaModel;

class DashletFormBlock extends FormBlock
{
	private PropertyTypeService $oPropertyTypeService;

	public function __construct(string $sName, array $aOptions = [])
	{
		parent::__construct($sName, $aOptions);
		$this->oPropertyTypeService = MetaModel::GetService('PropertyTypeService');
	}

	protected function BuildForm(): void
	{
		// type
		$aPropertyTypes = $this->oPropertyTypeService->ListPropertyTypesByType('Dashlet');
		$this->Add('class', ChoiceFormBlock::class, [
			'label'   => 'Class',
			'choices' => array_combine($aPropertyTypes, $aPropertyTypes),
		]);

		// column
		$this->Add('position_x', IntegerFormBlock::class, [
			'label' => 'Position X',
		]);

		// row
		$this->Add('position_y', IntegerFormBlock::class, [
			'label' => 'Position Y',
		]);

		// column
		$this->Add('height', IntegerFormBlock::class, [
			'label' => 'Height',
		]);

		// row
		$this->Add('width', IntegerFormBlock::class, [
			'label' => 'Width',
		]);

		// dashlet
		$this->Add('dashlet', DashletPropertiesFormBlock::class, [
			'label' => 'Dashlet',
		])
			->InputDependsOn(DashletPropertiesFormBlock::INPUT_DASHLET_TYPE, 'class', ChoiceFormBlock::OUTPUT_VALUE);
	}

}
