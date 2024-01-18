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

	// 1 - NO DECLARATION ///////////////////////////////////////////////////////////////////////////////////

	// on utilise un service accessible depuis le controlleur abstrait.
	// on accède aux paramètres via ce service que l'on peut injecter dans nos controlleurs et services.
	// Moins déclaratif, on perds l'information des paramètres utiles aux services.
	// on redéfinit la function getParameter du controlleur abstrait pour véfifier les paramètres en plus de ceux de Symfony.

	// 2 - EXPLICIT PARAMETERS DECLARATION //////////////////////////////////////////////////////////////////

	// On déclare explicitement les paramètres d'iTop dont on a besoin dans nos services.
	// On peut ensuite les injecter dans nos services mais il est nécessaire de binder les noms de variables.

	/**
	 * parameters:
	 * apc_cache.enabled: true
	 *
	 * services:
	 *     _defaults:
	 *         bind:
	 *           $bCacheEnabled: '%apc_cache.enabled%'
	 *
	 *  public function __construct(bool $bCacheEnabled){
	 *      ...
	 *  }
	 */

	// apc_cache.enabled
	$container->parameters()->set('apc_cache.enabled', $oConfig->Get('apc_cache.enabled'));

	// 3 - FULL PARAMETERS DECLARATION ///////////////////////////////////////////////////////////////////////

	// On déclare automatiquement tous les paramètres d'iTop.
	// On peut ensuite les injecter dans nos services mais il est nécessaire de binder les noms de variables.

	foreach ($aConfigurationEntries as $key => $value){
		$container->parameters()->set($key, $value);
	}

	// 4 - WITH AUTO WIRE ATTRIBUTE ///////////////////////////////////////////////////////////////////////////

	// On utilise l'atttribute #[Autowire au lieu du binding
	// https://symfony.com/doc/current/service_container/autowiring.html#fixing-non-autowireable-arguments

	/**
	 *
	 *  public function __construct(#[Autowire('%apc_cache.enabled%')] bool $bCacheEnabled){
	 *      ...
	 *  }
	 */

	// 5 - BINDING AUTOMATIC //////////////////////////////////////////////////////////////////////////////////

	// On bind tous les paramètres d'iTop en adaptant leur noms
	// temporary_object.lifetime => temporaryObject_lifetime
	// pour le faire en PHP, les services doivent êtres déclarés dans le même fichier.
	// https://symfony.com/doc/6.4/service_container.html#binding-arguments-by-name-or-type
	// ne marche pas et n'est de toutes les facons pas clair sur le nommage

};


