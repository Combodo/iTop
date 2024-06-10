<?php

namespace Combodo\iTop\Application\Helper;

use Combodo\iTop\Application\TwigBase\Twig\TwigHelper;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Renderer\BlockRenderer;
use Combodo\iTop\Renderer\RenderingOutput;
use Dict;
use Exception;
use ExceptionLog;
use UserRights;
use MetaModel;
use DBSearch;
use utils;
use appUserPreferences;

/**
 * Class CKEditorHelper
 *
 * Utilities for CKEditor.
 *
 * @package Combodo\iTop\Application\Helper
 * @since 3.2.0
 */
class CKEditorHelper
{
	/**
	 * Get the CKEditor configuration.
	 *
	 * Create a default configuration, merge it with the user preferences and overload it with the provided configuration.
	 *
	 * @param bool $bWithMentions
	 * @param string|null $sInitialValue
	 * @param array $aOverloadConfiguration
	 *
	 * @return array
	 */
	static public function GetCkeditorConfiguration(bool $bWithMentions, ?string $sInitialValue, array $aOverloadConfiguration = []) : array
	{
		// Extract language from user preferences
		$sLanguageCountry = trim(UserRights::GetUserLanguage());
		$sLanguage = strtolower(explode(' ', $sLanguageCountry)[0]);

		// configuration
		$aConfiguration = array(
			'language' => $sLanguage,
			'detectChanges' => [
				'initialValue' => $sInitialValue
			],
			'objectShortcut' => [
				'buttonLabel' => Dict::S('UI:ObjectShortcutInsert')
			],
		);

		// Mentions
		if($bWithMentions){
			try{
				$aMentionConfiguration = self::GetMentionConfiguration();
				$aConfiguration['mention'] = $aMentionConfiguration;
			}
			catch(Exception $e){
				ExceptionLog::LogException($e);
			}
		}

		// merge with overloaded configuration
		return array_merge($aConfiguration, $aOverloadConfiguration);
	}

	/**
	 * Get mention configuration.
	 *
	 * @return array|array[]
	 * @throws \Exception
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

	/**
	 * Encode value when using CKEditor with a TextArea.
	 * @see https://ckeditor.com/docs/ckeditor5/latest/installation/getting-started/getting-and-setting-data.html#automatic-integration-with-html-forms
	 *
	 * @param string|null $sValue
	 *
	 * @return string|null
	 */
	public static function PrepareCKEditorValueTextEncodingForTextarea(string $sValue = null) : ?string
	{
		if($sValue === null){
			return null;
		}
		return str_replace( '&', '&amp;', $sValue );
	}

	/**
	 * Configure CKEditor element (WebPage).
	 *
	 * @param \Combodo\iTop\Application\WebPage\WebPage $oPage
	 * @param string $sInputElementId ID of the HTML Input element
	 * @param string|null $sInitialValue input initial value
	 * @param bool $bWithMentions enable mentions
	 * @param array $aOverloadConfiguration overload configuration
	 *
	 * @return void
	 */
	public static function ConfigureCKEditorElementForWebPage(WebPage $oPage, string $sInputElementId, string $sInitialValue = null, bool $bWithMentions = false, array $aOverloadConfiguration = []): void
	{
		// link CKEditor JS files
		foreach (static::GetJSFilesRelPathsForCKEditor() as $sFile) {
			try{
				$oPage->LinkScriptFromAppRoot($sFile);
			}
			catch(Exception $e){
				ExceptionLog::LogException($e);
			}
		}

		// retrieve CKEditor configuration
		$aConfiguration = self::GetCkeditorConfiguration($bWithMentions, $sInitialValue, $aOverloadConfiguration);

		// add CKEditor initialization script
		$sConfigJS = json_encode($aConfiguration);
		$oPage->add_ready_script("CombodoCKEditorHandler.CreateInstance('#$sInputElementId', $sConfigJS)");

		// handle mentions template
		if($bWithMentions){
			try{
				$sMentionTemplate = self::GetMentionsTemplate($sInputElementId);
				$oPage->add($sMentionTemplate);
			}
			catch(Exception $e){
				ExceptionLog::LogException($e);
			}
		}

	}

	/**
	 * Configure CKEditor element (RenderingOutput).
	 *
	 * @param \Combodo\iTop\Renderer\RenderingOutput $oOutput
	 * @param string $sInputElementId ID of the HTML Input element
	 * @param string|null $sInitialValue input initial value
	 * @param bool $bWithMentions enable mentions
	 * @param bool $bAddJSFiles add JS files to the output
	 * @param array $aOverloadConfiguration overload configuration
	 *
	 * @return void
	 */
	public static function ConfigureCKEditorElementForRenderingOutput(RenderingOutput $oOutput, string $sInputElementId, string $sInitialValue = null, bool $bWithMentions = false, bool $bAddJSFiles = true, array $aOverloadConfiguration = []): void
	{
		// link CKEditor JS files
		if($bAddJSFiles){
			foreach (static::GetJSFilesRelPathsForCKEditor() as $sFile) {
				try{
					$oOutput->AddJsFile($sFile);
				}
				catch(Exception $e){
					ExceptionLog::LogException($e);
				}
			}
		}

		// configuration
		$aConfiguration = self::GetCkeditorConfiguration($bWithMentions, $sInitialValue, $aOverloadConfiguration);

		// add CKEditor initialization script
		$sConfigJS = json_encode($aConfiguration);
		$oOutput->AddJs("CombodoCKEditorHandler.CreateInstance('#$sInputElementId', $sConfigJS)");

		// mentions template
		if($bWithMentions){
			try{
				$sMentionTemplate = self::GetMentionsTemplate($sInputElementId);
				$oOutput->add($sMentionTemplate);
			}
			catch(Exception $e){
				ExceptionLog::LogException($e);
			}
		}
	}

	/**
	 * GetMentionsTemplate.
	 *
	 * @param string $sComponentId
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function GetMentionsTemplate(string $sComponentId): string
	{
		// twig environment
		$oTwig = TwigHelper::GetTwigEnvironment(BlockRenderer::TWIG_BASE_PATH);

		// mention template
		$sMentionsTemplate = $oTwig->render('application/object/set/option_renderer.html.twig');

		return <<<HTML
<template id="{$sComponentId}_items_template">
$sMentionsTemplate
</template>
HTML;
	}

	/**
	 * @return string[] Relative URLs to the JS files necessary for CKEditor
	 */
	public static function GetJSFilesRelPathsForCKEditor(): array
	{
		// all js file needed by ckeditor
		$aJSRelPaths = [
			'js/ckeditor/build/ckeditor.js',
			'js/highlight/highlight.js',
			'js/ckeditor.handler.js',
			'js/ckeditor.feeds.js'
		];

		// add CKEditor translations resource
		$sUserLanguage = Dict::GetUserLanguage();
		$sLanguage = strtolower(explode(' ', $sUserLanguage)[0]);
		$sCountry = strtolower(explode(' ', $sUserLanguage)[1]);

		// add corresponding ckeditor language file
		// P1 language + country
		// P2 language
		$sLanguageFileRelPath = 'js/ckeditor/build/translations/' . $sLanguage . '-' . $sCountry . '.js';
		if(file_exists(APPROOT . $sLanguageFileRelPath)){
			$aJSRelPaths[] = $sLanguageFileRelPath;
		}
		else {
			$sLanguageFileRelPath = 'js/ckeditor/build/translations/' . $sLanguage . '.js';
			if(file_exists(APPROOT . $sLanguageFileRelPath)){
				$aJSRelPaths[] = $sLanguageFileRelPath;
			}
		}

		return $aJSRelPaths;
	}
}