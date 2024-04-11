<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {

	// kernel.secret
	$container->parameters()->set('kernel.secret', MetaModel::GetConfig()->Get('application.secret'));

};