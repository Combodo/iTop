<?php

namespace Combodo\iTop\Forms\Block\FormType;

use Combodo\iTop\Forms\Block\Base\FormBlock;
use Symfony\Component\Form\FormInterface;

class FormTypeHelper
{

	/**
	 * @param FormInterface $oForm
	 *
	 * @return string
	 */
	public static function GetFormId(FormInterface $oForm): string
	{
		if (is_null($oForm->getParent())) {
			return $oForm->getName();
		}
		return self::GetFormId($oForm->getParent()).'_'.$oForm->getName();
	}

	/**
	 * Compute the blocks to redraw based on a turbo trigger.
	 *
	 * @param FormBlock $oFormBlock
	 * @param FormInterface $oForm
	 * @param string $sBlockTurboTriggerName
	 *
	 * @return array
	 */
	public static function ComputeBlocksToRedraw(FormBlock $oFormBlock, FormInterface $oForm, string $sBlockTurboTriggerName): array
	{
		// Result
		$aBlocksToRedraw = [];

		// Get the form corresponding to the turbo trigger
		$oFormTurboTrigger = self::GetFormAt($oForm, $sBlockTurboTriggerName);
		$sBlockTurboTriggerId = self::GetFormId($oFormTurboTrigger);

		// Get the parent form
		$oParent = $oFormTurboTrigger->getParent();
		$sParentName =  self::GetFormId($oParent);

		// Get the block corresponding to the turbo trigger form
		$oBlockTurboTrigger = $oFormTurboTrigger->getConfig()->getOption('form_block');
		$oMap = $oBlockTurboTrigger->GetParent()->GetDependenciesMap();

		// Add itself
		$aBlocksToRedraw[$sBlockTurboTriggerId] = $oFormTurboTrigger->createView();

		// Add impacted blocks
		$aImpacted = $oMap->GetImpacted($oBlockTurboTrigger->GetName());
		foreach ($aImpacted as $oImpactedBlock) {
			$sName = $sParentName.'_'.$oImpactedBlock->GetName();
			if($oParent->has($oImpactedBlock->GetName())){
				$aBlocksToRedraw[$sName] = $oParent->get($oImpactedBlock->GetName())->createView();
			}
			else{
				$aBlocksToRedraw[$sName] = null;
			}

		}

		return $aBlocksToRedraw;
	}

	/**
	 * @param FormInterface $oForm
	 * @param string $sBlockTurboTriggerName
	 *
	 * @return FormInterface|null
	 */
	public static function GetFormAt(FormInterface $oForm, string $sBlockTurboTriggerName): ?FormInterface
	{
		if (preg_match_all('/\[(?<level>[^\[]+)\]/', $sBlockTurboTriggerName, $aMatches)) {
			foreach ($aMatches['level'] as $level) {
				$oForm = $oForm->Get($level);
			}
		}

		return $oForm;
	}


}