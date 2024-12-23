<?php

namespace Combodo\iTop\Portal\DataCollector;

use Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesProviderService;
use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PortalCollector extends AbstractDataCollector
{

	public function __construct(private readonly TemplatesProviderService $oTemplatesProviderService)
	{
	}

	public function collect(Request $request, Response $response, Throwable $exception = null)
	{
		$aTemplatesDefinitions = $this->oTemplatesProviderService->GetTemplatesDefinitions();
		$this->data = [
			'templates' => $aTemplatesDefinitions,
			'overrides_count' => $this->ComputeOverridesCount($aTemplatesDefinitions),
		];
	}

	public static function getTemplate(): ?string
	{
		return 'itop-portal-base/portal/templates/data_collector/portal.html.twig';
	}

	public function getTemplates(): array
	{
		return $this->data['templates'];
	}

	public function getOverridesCount(): int
	{
		return $this->data['overrides_count'];
	}

	private function ComputeOverridesCount($aTemplatesDefinitions): int
	{
		$iCount = 0;
		foreach($aTemplatesDefinitions as $sScope => $templates){
			foreach ($templates as $sId => $template) {
				if ($template->IsOverridden()) {
					$iCount++;
				}
			}
		}
		return $iCount;
	}

}