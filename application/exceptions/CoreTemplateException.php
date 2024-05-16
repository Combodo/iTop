<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * @since 3.0.0 N°3522
 */
class CoreTemplateException extends CoreException
{
	public function __construct(Exception $oTwigException, string $sTemplatePath)
	{
		$sMessage = "Twig Exception when rendering '$sTemplatePath' : ".$oTwigException->getMessage();
		parent::__construct($sMessage, null, '', $oTwigException);
	}
}