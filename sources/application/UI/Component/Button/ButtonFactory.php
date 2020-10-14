<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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

namespace Combodo\iTop\Application\UI\Component\Button;

/**
 * Class ButtonFactory
 *
 * @internal
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Component\Button
 * @since 3.0.0
 */
class ButtonFactory
{
	//---------------------------------------------
	// Regular action buttons, mostly used in forms
	//---------------------------------------------

	/**
	 * Make a basis Button component for any purpose
	 *
	 * @param string $sLabel
	 * @param string $sName See Button::$sName
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Button\Button
	 */
	public static function MakeNeutral(string $sLabel, string $sName, ?string $sId = null): Button {
		$oButton = new Button($sLabel, $sId);
		$oButton->SetActionType(Button::ENUM_ACTION_TYPE_REGULAR)
			->SetColor(Button::ENUM_COLOR_NEUTRAL)
			->SetName($sName);

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
	 * @return \Combodo\iTop\Application\UI\Component\Button\Button
	 */
	public static function MakeForPrimaryAction(
		string $sLabel,
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	): Button {
		return static::MakeForAction($sLabel, Button::ENUM_COLOR_PRIMARY, Button::ENUM_ACTION_TYPE_REGULAR, $sValue, $sName, $bIsSubmit, $sId);
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
	 * @return \Combodo\iTop\Application\UI\Component\Button\Button
	 */
	public static function MakeForSecondaryAction(
		string $sLabel,
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	): Button {
		return static::MakeForAction($sLabel, Button::ENUM_COLOR_SECONDARY, Button::ENUM_ACTION_TYPE_REGULAR, $sValue, $sName, $bIsSubmit, $sId);
	}

	/**
	 * Make a Button component for a validation action, should be used to tell the user he/she going to save / validate / confirm his/her
	 * choices
	 *
	 * @param string $sLabel
	 * @param string|null $sName See Button::$sName
	 * @param string|null $sValue See Button::$sValue
	 * @param bool $bIsSubmit See Button::$sType
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Button\Button
	 */
	public static function MakeForValidationAction(
		string $sLabel,
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	): Button {
		return static::MakeForAction($sLabel, Button::ENUM_COLOR_VALIDATION, Button::ENUM_ACTION_TYPE_REGULAR, $sValue, $sName, $bIsSubmit, $sId);
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
	 * @return \Combodo\iTop\Application\UI\Component\Button\Button
	 */
	public static function MakeForDestructiveAction(
		string $sLabel,
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	): Button {
		return static::MakeForAction($sLabel, Button::ENUM_COLOR_DESTRUCTIVE, Button::ENUM_ACTION_TYPE_REGULAR, $sValue, $sName,
			$bIsSubmit, $sId);
	}

	//-------------------------------------------------
	// Alternative action buttons, mostly used in forms
	//-------------------------------------------------

	/**
	 * Make a basis Button component for any purpose
	 *
	 * @param string      $sLabel
	 * @param string      $sName See Button::$sName
	 * @param string|null $sValue See Button::$sValue
	 * @param bool        $bIsSubmit See Button::$sType
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Button\Button
	 */
	public static function MakeAlternativeNeutral(
		string $sLabel,
		string $sName,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	): Button {
		return static::MakeForAction($sLabel, Button::ENUM_COLOR_NEUTRAL, Button::ENUM_ACTION_TYPE_ALTERNATIVE, $sValue, $sName,
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
	 * @return \Combodo\iTop\Application\UI\Component\Button\Button
	 */
	public static function MakeForAlternativePrimaryAction(
		string $sLabel,
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	): Button {
		return static::MakeForAction($sLabel, Button::ENUM_COLOR_PRIMARY, Button::ENUM_ACTION_TYPE_ALTERNATIVE, $sValue, $sName,
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
	 * @return \Combodo\iTop\Application\UI\Component\Button\Button
	 */
	public static function MakeForAlternativeSecondaryAction(
		string $sLabel,
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	): Button {
		return static::MakeForAction($sLabel, Button::ENUM_COLOR_SECONDARY, Button::ENUM_ACTION_TYPE_ALTERNATIVE, $sValue, $sName,
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
	 * @return \Combodo\iTop\Application\UI\Component\Button\Button
	 */
	public static function MakeForAlternativeValidationAction(
		string $sLabel,
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	): Button {
		return static::MakeForAction($sLabel, Button::ENUM_COLOR_VALIDATION, Button::ENUM_ACTION_TYPE_ALTERNATIVE, $sValue, $sName,
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
	 * @return \Combodo\iTop\Application\UI\Component\Button\Button
	 */
	public static function MakeForAlternativeDestructiveAction(
		string $sLabel,
		string $sName = null,
		string $sValue = null,
		bool $bIsSubmit = false,
		?string $sId = null
	): Button {
		return static::MakeForAction($sLabel, Button::ENUM_COLOR_DESTRUCTIVE, Button::ENUM_ACTION_TYPE_ALTERNATIVE, $sValue, $sName,
			$bIsSubmit, $sId);
	}

	//----------------------------------------------------------------------------------------------
	// Link buttons, mostly used outside forms, to redirect somewhere whilst keeping a button aspect
	//----------------------------------------------------------------------------------------------

	/**
	 * Make a link Button component to open an URL instead of triggering a form action
	 *
	 * @param string $sURL
	 * @param string|null $sLabel
	 * @param string|null $sIconClass
	 * @param string|null $sName See Button::$sName
	 * @param string|null $sTarget
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Component\Button\Button
	 */
	public static function MakeLinkNeutral(string $sURL, ?string $sLabel = null, ?string $sIconClass = null, ?string $sName = null, ?string $sTarget = null, ?string $sId = null): Button
	{
		$sType = empty($sIconClass) ? Button::ENUM_ACTION_TYPE_REGULAR : Button::ENUM_ACTION_TYPE_ALTERNATIVE;
		$oButton = static::MakeForAction($sLabel, Button::ENUM_COLOR_NEUTRAL, $sType, null, $sName, false, $sId);

		if (!empty($sIconClass)) {
			$oButton->SetIconClass($sIconClass);
		}

		if (!empty($sURL)) {
			if (empty($sTarget)) {
				$sTarget = "_self";
			}
			$oButton->SetOnClickJsCode("window.open('{$sURL}', '{$sTarget}');");
		}

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
	 * @return \Combodo\iTop\Application\UI\Component\Button\Button
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
	): Button {
		$oButton = new Button($sLabel, $sId);
		$oButton->SetActionType($sActionType)
			->SetColor($sColor);

		if (empty($sValue) === false) {
			$oButton->SetValue($sValue);
		}

		if (empty($sName) === false) {
			$oButton->SetName($sName);
		}

		// Set as submit button if necessary
		if ($bIsSubmit === true) {
			$oButton->SetType(Button::ENUM_TYPE_SUBMIT);
		}

		return $oButton;
	}
}