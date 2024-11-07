<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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

namespace Combodo\iTop\Application\UI\Base\Component\Button;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Dict;
use utils;

/**
 * Class ButtonUIBlockFactory
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package UIBlockAPI
 * @api
 * @since 3.0.0
 *
 * @link <itop_url>/test/VisualTest/Backoffice/RenderAllUiBlocks.php#title-buttons to see live examples
 */
class ButtonUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIButton';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Button::class;

	//---------------------------------------------
	// Regular action buttons, mostly used in forms
	//---------------------------------------------

	/**
	 * Make a basis Button component for any purpose
	 *
	 * @api
	 * @param string $sLabel
	 * @param string|null $sName See {@link Button::$sName}
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\Button
	 */
	public static function MakeNeutral(string $sLabel, string $sName = null, ?string $sId = null)
	{
		$oButton = new ButtonJS($sLabel, $sId);
		$oButton->SetActionType(Button::ENUM_ACTION_TYPE_REGULAR)
			->SetColor(Button::ENUM_COLOR_SCHEME_NEUTRAL);

		if (!empty($sName)) {
			$oButton->SetName($sName);
		}

		return $oButton;
	}

	/**
	 * Make a Button component for a primary action, should be used to tell the user this is the main choice
	 *
	 * @param string $sLabel
	 * @param string|null $sName See Button::$sName
	 * @param string|null $sValue See Button::$sValue
	 * @param bool $bIsSubmit See Button::$sType
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\Button
	 */
	public static function MakeForPrimaryAction(
		string $sLabel,
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	) {
		return static::MakeForAction($sLabel, Button::ENUM_COLOR_SCHEME_PRIMARY, Button::ENUM_ACTION_TYPE_REGULAR, $sValue, $sName, $bIsSubmit, $sId);
	}

	/**
	 * Make a Button component for a secondary action, should be used to tell the user this is an second hand choice
	 *
	 * @param string $sLabel
	 * @param string|null $sName See Button::$sName
	 * @param string|null $sValue See Button::$sValue
	 * @param bool $bIsSubmit See Button::$sType
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\Button
	 */
	public static function MakeForSecondaryAction(
		string $sLabel,
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	) {
		return static::MakeForAction($sLabel, Button::ENUM_COLOR_SCHEME_SECONDARY, Button::ENUM_ACTION_TYPE_REGULAR, $sValue, $sName, $bIsSubmit, $sId);
	}

	/**
	 * Make a Button component for a success action, should be used to tell the user he/she going to make a positive action/choice
	 *
	 * @param string $sLabel
	 * @param string|null $sName See Button::$sName
	 * @param string|null $sValue See Button::$sValue
	 * @param bool $bIsSubmit See Button::$sType
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\Button
	 */
	public static function MakeForPositiveAction(
		string $sLabel,
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	) {
		return static::MakeForAction($sLabel, Button::ENUM_COLOR_SCHEME_VALIDATION, Button::ENUM_ACTION_TYPE_REGULAR, $sValue, $sName, $bIsSubmit, $sId);
	}

	/**
	 * Make a Button component for a destructive action, should be used to tell the user he/she going to make something that cannot be
	 * undone easily (deleting an object) or break something (link between objects)
	 *
	 * @param string $sLabel
	 * @param string|null $sName See Button::$sName
	 * @param string|null $sValue See Button::$sValue
	 * @param bool $bIsSubmit See Button::$sType
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\Button
	 */
	public static function MakeForDestructiveAction(
		string $sLabel,
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	) {
		return static::MakeForAction($sLabel, Button::ENUM_COLOR_SCHEME_DESTRUCTIVE, Button::ENUM_ACTION_TYPE_REGULAR, $sValue, $sName,
			$bIsSubmit, $sId);
	}

	//-------------------------------------------------
	// Alternative action buttons, mostly used in forms
	//-------------------------------------------------

	/**
	 * Make a basis Button component for any purpose
	 *
	 * @param string $sLabel
	 * @param string|null $sName See Button::$sName
	 * @param string|null $sValue See Button::$sValue
	 * @param bool $bIsSubmit See Button::$sType
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\Button
	 */
	public static function MakeAlternativeNeutral(
		string $sLabel,
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	) {
		return static::MakeForAction($sLabel, Button::ENUM_COLOR_SCHEME_NEUTRAL, Button::ENUM_ACTION_TYPE_ALTERNATIVE, $sValue, $sName,
			$bIsSubmit, $sId);
	}

	/**
	 * Make a Button component for an alternative primary action, should be used to avoid the user to consider this action as the first
	 * choice
	 *
	 * @param string $sLabel
	 * @param string|null $sName See Button::$sName
	 * @param string|null $sValue See Button::$sValue
	 * @param bool $bIsSubmit See Button::$sType
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\Button
	 */
	public static function MakeForAlternativePrimaryAction(
		string $sLabel,
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	) {
		return static::MakeForAction($sLabel, Button::ENUM_COLOR_SCHEME_PRIMARY, Button::ENUM_ACTION_TYPE_ALTERNATIVE, $sValue, $sName,
			$bIsSubmit, $sId);
	}

	/**
	 * Make a Button component for an alternative secondary action, should be used to avoid the user to focus on this
	 *
	 * @param string $sLabel
	 * @param string|null $sName See Button::$sName
	 * @param string|null $sValue See Button::$sValue
	 * @param bool $bIsSubmit See Button::$sType
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\Button
	 */
	public static function MakeForAlternativeSecondaryAction(
		string $sLabel,
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	) {
		return static::MakeForAction($sLabel, Button::ENUM_COLOR_SCHEME_SECONDARY, Button::ENUM_ACTION_TYPE_ALTERNATIVE, $sValue, $sName,
			$bIsSubmit, $sId);
	}

	/**
	 * Make a Button component for a validation action, should be used to avoid the user to focus on this
	 *
	 * @param string $sLabel
	 * @param string|null $sName See Button::$sName
	 * @param string|null $sValue See Button::$sValue
	 * @param bool $bIsSubmit See Button::$sType
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\Button
	 */
	public static function MakeForAlternativeValidationAction(
		string $sLabel,
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	) {
		return static::MakeForAction($sLabel, Button::ENUM_COLOR_SCHEME_VALIDATION, Button::ENUM_ACTION_TYPE_ALTERNATIVE, $sValue, $sName,
			$bIsSubmit, $sId);
	}

	/**
	 * Make a Button component for a destructive action, should be used to avoid the user to focus on this
	 *
	 * @param string $sLabel
	 * @param string|null $sName See Button::$sName
	 * @param string|null $sValue See Button::$sValue
	 * @param bool $bIsSubmit See Button::$sType
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\Button
	 */
	public static function MakeForAlternativeDestructiveAction(
		string $sLabel,
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	) {
		return static::MakeForAction($sLabel, Button::ENUM_COLOR_SCHEME_DESTRUCTIVE, Button::ENUM_ACTION_TYPE_ALTERNATIVE, $sValue, $sName,
			$bIsSubmit, $sId);
	}

	/**
	 * Make a Button component for a cancel, should be used only for UI navigation, not destructive action
	 *
	 * @param string|null $sLabel
	 * @param string|null $sName See Button::$sName
	 * @param string|null $sValue See Button::$sValue
	 * @param bool $bIsSubmit See Button::$sType
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\Button
	 */
	public static function MakeForCancel(
		string $sLabel = null,
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	) {
		$sLabel = $sLabel ?? Dict::S('UI:Button:Cancel');
		$sName = $sName ?? 'cancel';

		return static::MakeForAction($sLabel, Button::ENUM_COLOR_SCHEME_NEUTRAL, Button::ENUM_ACTION_TYPE_ALTERNATIVE, $sValue, $sName,
			$bIsSubmit, $sId);
	}

	/**
	 * @param string $sIconClasses
	 * @param string $sTooltipText
	 * @param string|null $sName
	 * @param string|null $sValue
	 * @param bool $bIsSubmit
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\ButtonJS
	 */
	public static function MakeIconAction(
		string $sIconClasses,
		string $sTooltipText = '',
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	) {
		$oButton =  static::MakeForAction('', Button::ENUM_COLOR_SCHEME_NEUTRAL, Button::ENUM_ACTION_TYPE_ALTERNATIVE, $sValue, $sName,
			$bIsSubmit, $sId);
		$oButton->SetIconClass($sIconClasses);
		$oButton->SetTooltip($sTooltipText);

		return $oButton;
	}

	//----------------------------------------------------------------------------------------------
	// Link buttons, mostly used outside forms, to redirect somewhere whilst keeping a button aspect
	//----------------------------------------------------------------------------------------------

	/**
	 * Make a link Button component to open an URL instead of triggering a form action
	 *
	 * @param string $sURL
	 * @param string|null $sLabel
	 * @param string|null $sIconClasses
	 * @param string|null $sTarget
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\Button
	 */
	public static function MakeLinkNeutral(
		string $sURL, ?string $sLabel = '', ?string $sIconClasses = null, ?string $sTarget = null,
		?string $sId = null
	) {
		if (empty($sTarget)) {
			$sTarget = ButtonURL::DEFAULT_TARGET;
		}
		$sType = empty($sIconClasses) ? Button::ENUM_ACTION_TYPE_REGULAR : Button::ENUM_ACTION_TYPE_ALTERNATIVE;
		$oButton = static::MakeForLink($sLabel, $sURL,Button::ENUM_COLOR_SCHEME_NEUTRAL, $sType,  $sTarget, $sId);

		if (!empty($sIconClasses)) {
			$oButton->SetIconClass($sIconClasses);
		}

		return $oButton;
	}

	/**
	 * @param string $sIconClasses
	 * @param string $sTooltipText
	 * @param string|null $sURL
	 * @param string|null $sTarget
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\ButtonURL
	 */
	public static function MakeIconLink(
		string $sIconClasses, string $sTooltipText, ?string $sURL = '', ?string $sTarget = null,
		?string $sId = null
	) {
		if (empty($sTarget)) {
			$sTarget = ButtonURL::DEFAULT_TARGET;
		}
		$oButton = static::MakeForLink('', $sURL,Button::ENUM_COLOR_SCHEME_NEUTRAL, Button::ENUM_ACTION_TYPE_ALTERNATIVE, $sTarget, $sId);
		$oButton->SetIconClass($sIconClasses);
		$oButton->SetTooltip($sTooltipText);

		return $oButton;
	}
	
	/**
	 * @param string $sIconClasses
	 * @param string $sTooltipText
	 * @param string|null $sURL
	 * @param string|null $sName
	 * @param string|null $sTarget
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\Button
	 */
	public static function MakeDestructiveIconLink(
		string $sIconClasses, string $sTooltipText, ?string $sURL = null, ?string $sName = null, ?string $sTarget = null,
		?string $sId = null
	) {
		$oButton = static::MakeIconLink($sIconClasses, $sTooltipText, $sURL,  $sTarget, $sId);
		$oButton->SetColor(Button::ENUM_COLOR_SCHEME_DESTRUCTIVE);
		$oButton->SetTooltip($sTooltipText);
		return $oButton;
	}

	//--------
	// Helpers
	//--------

	/**
	 * Internal helper
	 *
	 * @param string $sLabel
	 * @param string $sColor See Button::$sColor
	 * @param string $sActionType See Button::$sActionType
	 * @param string|null $sValue See Button::$sValue
	 * @param string|null $sName See Button::$sValue
	 * @param bool $bIsSubmit
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\ButtonJS
	 * @internal
	 */
	protected static function MakeForAction(
		string $sLabel,
		string $sColor,
		string $sActionType,
		string $sValue = null,
		string $sName = null,
		bool $bIsSubmit = false,
		?string $sId = null
	) {
		$oButton = new ButtonJS($sLabel, $sId);
		$oButton->SetActionType($sActionType)
			->SetColor($sColor);

		if (utils::IsNotNullOrEmptyString($sValue)) {
			$oButton->SetValue($sValue);
		}

		if (utils::IsNotNullOrEmptyString($sName)) {
			$oButton->SetName($sName);
		}

		// Set as submit button if necessary
		if ($bIsSubmit === true) {
			$oButton->SetType(ButtonJS::ENUM_TYPE_SUBMIT);
		}

		return $oButton;
	}

	/**
	 * Internal helper
	 *
	 * @internal
	 *
	 * @param string $sLabel
	 *
	 * @param string $sURL
	 * @param string $sColor See Button::$sColor
	 * @param string $sActionType See Button::$sActionType
	 * @param string|null $sTarget
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Button\ButtonURL
	 */
	protected static function MakeForLink(
		string $sLabel,
		string $sURL,
		string $sColor,
		string $sActionType,
		string $sTarget = null,
		?string $sId = null
	) {
		$oButton = new ButtonURL($sLabel, $sURL, $sId, $sTarget);
		$oButton->SetActionType($sActionType)
			->SetColor($sColor);
		
		return $oButton;
	}
}