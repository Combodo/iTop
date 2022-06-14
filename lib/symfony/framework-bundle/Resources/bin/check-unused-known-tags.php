<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require dirname(__DIR__, 6).'/vendor/autoload.php';

use Symfony\Bundle\FrameworkBundle\Tests\DependencyInjection\Compiler\UnusedTagsPassUtils;

$target = dirname(__DIR__, 2).'/DependencyInjection/Compiler/UnusedTagsPass.php';
$contents = file_get_contents($target);
$contents = preg_replace('{private const KNOWN_TAGS = \[(.+?)\];}sm', "private const KNOWN_TAGS = [\n        '".implode("',\n        '", UnusedTagsPassUtils::getDefinedTags())."',\n    ];", $contents);
file_put_contents($target, $contents);
