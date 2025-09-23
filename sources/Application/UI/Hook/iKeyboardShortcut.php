<?php

namespace Combodo\iTop\Application\UI\Hook;

/**
 * @since 3.0.0
 */
interface iKeyboardShortcut
{
	/**
	 * Return default keys combination to trigger shortcut element
	 * @return array
	*/
	public static function GetShortcutKeys(): array;

	/**
	 * Element to be triggered when shortcut key combination is pressed
	 * @return string
	 */
	public static function GetShortcutTriggeredElementSelector(): string;
}