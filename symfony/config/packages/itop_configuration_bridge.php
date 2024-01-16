<?php
/**
 * Copyright (C) 2013-2023 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {

	$oConfig = utils::GetConfig();

	// configuration entries
	$aConfigurationEntries = $oConfig->ToArray();

	///////////////////////////////////// NO DECLARATION

	// on utilise un service accessible depuis de controlleur abstrait.
	// on accède aux paramètres via le service
	// Moins déclaratif, on perds l'information des paramètres utiles au servie
	// + simple et comprehensible
	// on redéfinit la function getParameter du controlleur abstrait pour véfifier les paramètre iTop avant ceux de Symfony.

	///////////////////////////////////// EXPLICIT PARAMETERS DECLARATION

	// On déclare explicitement les paramètres d'iTop dont on a besoin dans nos services.
	// On peut ensuite les injecter dans nos services mais il est nécessaire de binder les noms de variables.

	/**
	 * services:
	 *     _defaults:
	 *         bind:
	 *           $sApplicationSecret: '%application.secret%'
	 *
	 *  public function __construct(string $sApplicationSecret){
	 *      ...
	 *  }
	 */

	// kernel.secret
	$container->parameters()->set('kernel.secret', $oConfig->Get('application.secret'));

	///////////////////////////////////// 🧙‍♂️ AUTOMATIC PARAMETERS DECLARATION

	// On déclare automatiquement tous les paramètres d'iTop.
	// On peut ensuite les injecter dans nos services mais il est nécessaire de binder les noms de variables.

	foreach ($aConfigurationEntries as $key => $value){
		$container->parameters()->set($key, $value);
	}

	///////////////////////////////////// AUTOMATIC PARAMETERS DECLARATION WITH AUTO WIRE ATTRIBUTE

	// On déclare automatiquement tous les paramètres d'iTop.
	// in utilise l'atttribute #[Autowire au lieu du binding
	// https://symfony.com/doc/current/service_container/autowiring.html#fixing-non-autowireable-arguments

	/**
	 *
	 *  public function __construct(#[Autowire('%log_level_min%')] string $sApplicationSecret){
	 *      ...
	 *  }
	 */

	///////////////////////////////////// 🧘‍♂️ BIND AUTOMATIC

	// On bind tous les paramètres d'iTop en adaptant leur noms
	// temporary_object.lifetime => temporaryObject_lifetime
	// pour le faire en PHP, les services doivent êtres déclarés dans le même fichier.
	// https://symfony.com/doc/6.4/service_container.html#binding-arguments-by-name-or-type
	// ne marche pas et n'est pas clair sur le nommage

};


