<?php

namespace Combodo\iTop\Application\Dashboard\FormBlock;

use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\Block\Base\CollectionBlock;
use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\Block\Base\TextFormBlock;

class DashboardFormBlock extends FormBlock
{
	protected function BuildForm(): void
	{
		// Label
		$this->Add('title', TextFormBlock::class, [
			'label' => 'Title',
		]);

		// Refresh
		$this->Add('refresh', ChoiceFormBlock::class, [
			'label'   => 'Refresh',
			'choices' => [
				'Never'            => 0,
				'Every 5 minutes'  => 5,
				'Every 15 minutes' => 15,
				'Every 30 minutes' => 30,
				'Every hour'       => 60,
			],
		]);

		$this->Add('dashlets_list', CollectionBlock::class, [
			'label'               => 'Dashlets List',
			'block_entry_type'    => DashletFormBlock::class,
			'block_entry_options' => [],
		]);

	}

}
