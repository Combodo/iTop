<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

declare(strict_types = 1);

/**
 * This file is only here to allow setting a specific PHP version to run the analysis for without
 * having to explicitly set it in the .neon file. This is the best way we found so far.
 *
 * @link https://phpstan.org/config-reference#phpversion
 *
 * Usage: Uses the CLI PHP version by default, which would work fine for
 *   - The CI as the docker image has the target PHP version in both CLI and web
 *   - The developer's IDE as PHPStorm also has a default PHP version configured which can be changed on the fly
 */

// Default PHP version to analyse is the one running in CLI
$config = [];
$config['parameters']['phpVersion'] = PHP_VERSION_ID;

return $config;