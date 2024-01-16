<?php

namespace Combodo\iTop\Controller;

use Combodo\iTop\Core\Configuration\ConfigurationService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractAppController
{



	#[Route('/itop_configuration', name: 'itop_configuration_bridge')]
	public function list(#[Autowire('%log_level_min%')] $logLevelMin_writeInDb, #[Autowire('%temporary_object.lifetime%')] $filterEvent): JsonResponse
	{
		return new JsonResponse([
			'secret' => $this->getParameter('application.secret'),
            'new_parameter' => $this->getParameter('new_parameter'),
			'db_host' => $this->getParameter('db_host'),
			'log_level' => $logLevelMin_writeInDb,
			'filter_events' => $filterEvent
			]);
	}

}