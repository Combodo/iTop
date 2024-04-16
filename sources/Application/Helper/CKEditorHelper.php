<?php

namespace Combodo\iTop\Application\Helper;

use UserRights;
use MetaModel;
use DBSearch;
use utils;
use appUserPreferences;

/***
 *
 * (34) [
 * 'blockQuote',
 * 'bold',
 * 'link',
 * 'ckfinder',
 * 'codeBlock',
 * 'selectAll',
 * 'undo',
 * 'redo',
 * 'heading',
 * 'horizontalLine',
 * 'imageTextAlternative',
 * 'toggleImageCaption',
 * 'imageStyle:inline',
 * 'imageStyle:alignLeft',
 * 'imageStyle:alignRight',
 * 'imageStyle:alignCenter',
 * 'imageStyle:alignBlockLeft',
 * 'imageStyle:alignBlockRight',
 * 'imageStyle:block',
 * 'imageStyle:side',
 * 'imageStyle:wrapText',
 * 'imageStyle:breakText',
 * 'uploadImage',
 * 'imageUpload',
 * 'indent',
 * 'outdent',
 * 'italic',
 * 'numberedList',
 * 'bulletedList',
 * 'mediaEmbed',
 * 'insertTable',
 * 'tableColumn',
 * 'tableRow',
 * 'mergeTableCells']
 *
 */

class CKEditorHelper
{
	/**
	 * Return the CKEditor config as an array
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @since 3.0.0
	 */
	static public function GetCkeditorPref()
	{
		// extract language from user preferences
		$sLanguageCountry = trim(UserRights::GetUserLanguage());
		$sLanguage = strtolower(explode(' ', $sLanguageCountry)[0]);

		$aDefaultConf = array(
			'language'=> $sLanguage,
		);

		// mentions
		$aDefaultConf['mention'] = self::GetMentionConfiguration();

		// rich text config
		$aRichTextConfig = 	json_decode(appUserPreferences::GetPref('richtext_config', '{}'), true);

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