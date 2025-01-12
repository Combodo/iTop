<?php

namespace Combodo\iTop\Portal\DataCollector;

use Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesProviderService;
use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Template information collector for Symfony profiler.
 *
 * @package Combodo\iTop\Portal\DataCollector
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
	public function collect(Request $request, Response $response, Throwable $exception = null): void
	{
		$oRegister = $this->oTemplatesProviderService->GetRegister();
		$aTemplatesDefinitions = $oRegister->GetTemplatesDefinitions();
		$this->data = [
			'templates_definitions' => $aTemplatesDefinitions,
			'instances_overridden_templates' => $this->oTemplatesProviderService->GetInstancesOverriddenTemplatesPaths(),
			'templates_count' => $this->ComputeOverridesCount($aTemplatesDefinitions),
			'ui_version' => $oRegister->GetUIVersion(),
		];
	}

	/**
	 * @return string|null
	 */
	public static function getTemplate(): ?string
	{
		return 'itop-portal-base/portal/templates/data_collector/portal.html.twig';
	}

	/**
	 * @return array
	 * @noinspection PhpUnused
	 */
	public function GetTemplatesDefinitions(): array
	{
		return $this->data['templates_definitions'];
	}

	/**
	 * @return array
	 * @noinspection PhpUnused
	 */
	public function GetInstancesOverriddenTemplates(): array
	{
		return $this->data['instances_overridden_templates'];
	}

	/**
	 * @return array
	 * @noinspection PhpUnused
	 */
	public function GetTemplatesCount(): array
	{
		return $this->data['templates_count'];
	}

	/**
	 * @return string
	 * @noinspection PhpUnused
	 */
	public function GetUIVersion(): string
	{
		return $this->data['ui_version'];
	}

	private function ComputeOverridesCount($aTemplatesDefinitions): array
	{
		$iCount = 0;
		$iOverridesCount = 0;
		$aExtensions = [];

		foreach($aTemplatesDefinitions as $templates){
			foreach ($templates as $template) {

				$aMatches = [];
				preg_match('#([\w-]+)/#', $template->GetPath(), $aMatches);

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
			'providers_count' => count($aTemplatesDefinitions),
			'overrides_count' => $iOverridesCount,
			'extensions_count' => count($aExtensions),
			'bricks_count'     => count($this->oTemplatesProviderService->GetInstancesOverriddenTemplatesPaths()),
		];
	}

	/**
	 * @return string
	 * @noinspection PhpUnused
	 */
	public function GetInstanceDescriptionName(): string
	{
		return 'portal';
	}

}