<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\Dashlet\Service\DashletService;
use Combodo\iTop\Forms\Block\FormBlockService;
use Combodo\iTop\Forms\FormBuilder\FormFactoryBuilderService;
use Combodo\iTop\PropertyType\PropertyTypeService;
use Combodo\iTop\Service\Cache\DataModelDependantCache;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

return [
	'ModelReflection' => ModelReflectionRuntime::class,
	'DashletService' => DashletService::class,
	'PropertyTypeService' => PropertyTypeService::class,
	'DataModelDependantCache' => DataModelDependantCache::class,
	'FormBlockService' => FormBlockService::class,
	'CsrfTokenManager' => CsrfTokenManager::class,
	'FormFactoryBuilderService' => FormFactoryBuilderService::class,
];
