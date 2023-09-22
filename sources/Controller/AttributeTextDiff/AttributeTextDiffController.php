<?php

namespace Combodo\iTop\Controller\AttributeTextDiff;

use AjaxPage;
use ArchivedObjectException;
use CMDBChangeOpSetAttributeLongText;
use CMDBObject;
use Combodo\iTop\Application\Helper\CMDBChangeHelper;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Controller\AbstractController;
use CoreException;
use Dict;
use InvalidParameterException;
use Jfcherng\Diff\DiffHelper;
use Jfcherng\Diff\Renderer\RendererConstant;
use MetaModel;
use NiceWebPage;
use utils;
use WebPage;

class AttributeTextDiffController extends AbstractController
{
	public const ROUTE_NAMESPACE = 'attributetext_diff';

	/**
	 * @throws InvalidParameterException
	 * @noinspection PhpUnused
	 */
	public function OperationDisplayDiff(): WebPage
	{
		if ($this->IsHandlingXmlHttpRequest()) {
			$oPage = new AjaxPage('AttributeText diff');
			//FIXME no output of aCssInline in AjaxPage (not present in /templates/pages/backoffice/ajaxpage/layout.html.twig)
		} else {
			$oPage = new NiceWebPage('AttributeText diff');
		}

		$sChangeOpId = utils::ReadParam('changeop', -1, false, utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER);

		$oChangeOp = $this->GetChangeOp($sChangeOpId);
		if (\is_null($oChangeOp)) {
			throw new InvalidParameterException('Cannot load object from changeop param');
		}
		$sDataPrev = $oChangeOp->Get('prevdata');

		$sDataNew = CMDBChangeHelper::GetAttributeNewValueFromChangeOp($oChangeOp);

		/** @var CMDBObject $oObject */
		$this->GenerateDiffContent($oPage, $oChangeOp, $sDataPrev, $sDataNew);

		return $oPage;
	}

	private function GetChangeOp(string $sChangeOpId): ?CMDBChangeOpSetAttributeLongText
	{
		try {
			/** @var CMDBChangeOpSetAttributeLongText $oChangeOp */
			$oChangeOp = MetaModel::GetObject(CMDBChangeOpSetAttributeLongText::class, $sChangeOpId, false);
		} catch (ArchivedObjectException|CoreException $e) {
			$oChangeOp = null;
		}
		if (\is_null($oChangeOp)) {
			return null;
		}

		return $oChangeOp;
	}

	private function GenerateDiffContent(WebPage $oPage, CMDBChangeOpSetAttributeLongText $oChangeOp, string $sOld, string $sNew): void
	{
		$sClass = $oChangeOp->Get('objclass');
		$sClassIconUrl = MetaModel::GetClassIcon($sClass, false);

		$sAttCode = $oChangeOp->Get('attcode');
		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		$sSubtitle = Dict::Format('Change:AttName_Changed', $oAttDef->GetLabel());

		$oObject = MetaModel::GetObject($oChangeOp->Get('objclass'), $oChangeOp->Get('objkey'));
		$oPanel = PanelUIBlockFactory::MakeNeutral($oObject->GetName())
			->SetSubTitle($sSubtitle)
			->SetIcon($sClassIconUrl, Panel::ENUM_ICON_COVER_METHOD_ZOOMOUT, true);
		$oPage->AddUiBlock($oPanel);

		$sOldLabel = Dict::S('Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue');

		$sNewAuthor = $oChangeOp->Get('userinfo');
		$sNewDate = $oChangeOp->Get('date');
		$sNewLabel = "$sNewAuthor ($sNewDate)";

		$oPage->add_style(DiffHelper::getStyleSheet());
		$oPage->add_style(<<<CSS
table.diff-side-by-side {
    width: 100%;
    table-layout: fixed;
}
CSS
		);
		$sDiffHtml = $this->GetDiffHtmlCode($sOld, $sOldLabel, $sNew, $sNewLabel);
		$oPanel->AddSubBlock(new Html($sDiffHtml));
	}

	private function GetDiffHtmlCode(string $sOld, string $sOldLabel, string $sNew, string $sNewLabel): string
	{
		$rendererName = 'SideBySide';

		$differOptions = [
			// show how many neighbor lines
			// Differ::CONTEXT_ALL can be used to show the whole file
			'context' => 3,
			'ignoreCase' => false,
			'ignoreLineEnding' => true,
			'ignoreWhitespace' => false,
			'lengthLimit' => 2000,
		];

		$rendererOptions = [
			// how detailed the rendered HTML in-line diff is? (none, line, word, char)
			'detailLevel' => 'char',
			// renderer language: eng, cht, chs, jpn, ...
			// or an array which has the same keys with a language file
			// check the "Custom Language" section in the readme for more advanced usage
			'language' => [
				'eng',
				[
					'old_version' => $sOldLabel,
					'new_version' => $sNewLabel,
				]
			],
			// show line numbers in HTML renderers
			'lineNumbers' => false,
			// show a separator between different diff hunks in HTML renderers
			'separateBlock' => true,
			// show the (table) header
			'showHeader' => true,
			// the frontend HTML could use CSS "white-space: pre;" to visualize consecutive whitespaces
			// but if you want to visualize them in the backend with "&nbsp;", you can set this to true
			'spacesToNbsp' => false,
			// HTML renderer tab width (negative = do not convert into spaces)
			'tabSize' => 3,
			// this option is currently only for the Combined renderer.
			// it determines whether a replace-type block should be merged or not
			// depending on the content changed ratio, which values between 0 and 1.
//			'mergeThreshold' => 0.8,
			// this option is currently only for the Unified and the Context renderers.
			// RendererConstant::CLI_COLOR_AUTO = colorize the output if possible (default)
			// RendererConstant::CLI_COLOR_ENABLE = force to colorize the output
			// RendererConstant::CLI_COLOR_DISABLE = force not to colorize the output
			'cliColorization' => RendererConstant::CLI_COLOR_ENABLE,
			// this option is currently only for the Json renderer.
			// internally, ops (tags) are all int type but this is not good for human reading.
			// set this to "true" to convert them into string form before outputting.
//			'outputTagAsString' => false,
			// this option is currently only for the Json renderer.
			// it controls how the output JSON is formatted.
			// see available options on https://www.php.net/manual/en/function.json-encode.php
//			'jsonEncodeFlags' => \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE,
			// this option is currently effective when the "detailLevel" is "word"
			// characters listed in this array can be used to make diff segments into a whole
			// for example, making "<del>good</del>-<del>looking</del>" into "<del>good-looking</del>"
			// this should bring better readability but set this to empty array if you do not want it
			'wordGlues' => [' ', '-'],
			// change this value to a string as the returned diff if the two input strings are identical
			'resultForIdenticals' => null,
			// extra HTML classes added to the DOM of the diff container
			'wrapperClasses' => ['diff-wrapper'],
		];

		return DiffHelper::calculate($sOld, $sNew, $rendererName, $differOptions, $rendererOptions);
	}
}