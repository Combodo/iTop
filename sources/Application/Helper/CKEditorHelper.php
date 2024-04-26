<?php

namespace Combodo\iTop\Application\Helper;

use UserRights;
use MetaModel;
use DBSearch;
use utils;
use appUserPreferences;

/**
 * Class CKEditorHelper
 *
 * Utilities for CKEditor
 *
 * @package Combodo\iTop\Application\Helper
 * @since 3.2.0
 */
class CKEditorHelper
{
	/**
	 * Get the CKEditor configuration
	 *
	 * @param bool $bWithMentions
	 * @param string|null $sInitialValue
	 *
	 * @return array
	 */
	static public function GetCkeditorPref(bool $bWithMentions, ?string $sInitialValue) : array
	{
		// Extract language from user preferences
		$sLanguageCountry = trim(UserRights::GetUserLanguage());
		$sLanguage = strtolower(explode(' ', $sLanguageCountry)[0]);

		$aDefaultConf = array(
			'language' => $sLanguage,
		);

		// Mentions
		if($bWithMentions){
			$aDefaultConf['mention'] = self::GetMentionConfiguration();
		}

		// Rich text config
		$aRichTextConfig = 	json_decode(appUserPreferences::GetPref('richtext_config', '{}'), true);

		// detect changes
		$aDefaultConf['detectChanges'] = ['initialValue' => $sInitialValue];

		// object shortcut
		$aDefaultConf['objectShortcut'] = [
			'buttonLabel' => \Dict::S('UI:ObjectShortcutInsert')
        ];

		return array_merge($aDefaultConf, $aRichTextConfig);
	}

	/**
	 * @return array|array[]
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	static private function GetMentionConfiguration() : array
	{
		// initialize feeds
		$aMentionConfiguration = ['feeds' => []];

		// retrieve mentions allowed classes
		$aMentionsAllowedClasses = MetaModel::GetConfig()->Get('mentions.allowed_classes');

		// iterate throw classes...
		foreach($aMentionsAllowedClasses as $sMentionMarker => $sMentionScope) {

			// Retrieve mention class
			// - First test if the conf is a simple data model class
			if (MetaModel::IsValidClass($sMentionScope)) {
				$sMentionClass = $sMentionScope;
			}
			// - Otherwise it must be a valid OQL
			else {
				$oTmpSearch = DBSearch::FromOQL($sMentionScope);
				$sMentionClass = $oTmpSearch->GetClass();
				unset($oTmpSearch);
			}

			// append mention configuration
			$aMentionConfiguration['feeds'][] = [
					'marker' => $sMentionMarker,
					'feed' => null,
					'minimumCharacters' => MetaModel::GetConfig()->Get('min_autocomplete_chars'),
					'feed_type' => 'ajax',
					'feed_ajax_options' => [
						'url' => utils::GetAbsoluteUrlAppRoot(). "pages/ajax.render.php?route=object.search&object_class=$sMentionClass&oql=SELECT $sMentionClass&search=",
						'throttle' => 500,
						'marker' => $sMentionMarker,
					],
			];

		}

		return $aMentionConfiguration;
	}

}