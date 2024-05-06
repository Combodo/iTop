<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\MetaModel;


/**
 * Class FriendlyNameType
 *
 * The various type of FriendlyNameType
 *
 * @package Combodo\iTop\Core\MetaModel
 * @since 3.0.0
 */
class FriendlyNameType
{
	/** @var string usual friendly name */
	public const SHORT = 'short';
	/** @var string complement of friendly name used in select box in order to help to choose the good element*/
	public const COMPLEMENTARY = 'compl';
	/** @var string long friendly name made whith short + complementary data*/
	public const LONG = 'long';
}