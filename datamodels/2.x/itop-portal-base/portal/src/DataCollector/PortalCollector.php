<?php

namespace Combodo\iTop\Portal\DataCollector;

use Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesProviderService;
use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Collector for Symfony profiler.
 *
 * @since 3.2.1
 */
class PortalCollector extends AbstractDataCollector
{

	/**
	 * Constructor.
	 *
	 *
	 * @param \Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesProviderService $oTemplatesProviderService
	 */
	public function __construct(private readonly TemplatesProviderService $oTemplatesProviderService)
	{
	}

	/** @inheritdoc  */
	public function collect(Request $request, Response $response, Throwable $exception = null)
	{
		$aTemplatesDefinitions = $this->oTemplatesProviderService->GetTemplatesDefinitions();
		$this->data = [
			'templates' => $aTemplatesDefinitions,
			'templates_count' => $this->ComputeOverridesCount($aTemplatesDefinitions),
			'ui_version' => $this->oTemplatesProviderService->GetUIVersion(),
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

	public function getTemplatesCount(): array
	{
		return $this->data['templates_count'];
	}

	public function getUIVersion(): string
	{
		return $this->data['ui_version'];
	}

	private function ComputeOverridesCount($aTemplatesDefinitions): array
	{
		$iCount = 0;
		$iOverridesCount = 0;
		$aExtensions = [];

		foreach($aTemplatesDefinitions as $sScope => $templates){
			foreach ($templates as $sId => $template) {

				$aMatches = [];
				preg_match('#([\w-]+)/#', $template->GetValue(), $aMatches);

				if(!in_array($aMatches[1], $aExtensions)){
					$aExtensions[] = $aMatches[1];
				}

				$iCount++;
				if ($template->IsOverridden()) {
					$iOverridesCount++;
				}
			}
		}
		return [
			'count' => $iCount,
			'scope_count' => count($aTemplatesDefinitions),
			'overrides_count' => $iOverridesCount,
			'extensions_count' => count($aExtensions)
		];
	}

}