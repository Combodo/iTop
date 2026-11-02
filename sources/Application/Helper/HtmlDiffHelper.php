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

namespace Combodo\iTop\Application\Helper;

use Combodo\iTop\Core\AttributeDefinition\AttributeText;
use CoreException;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * Compare the visible text of two rich-text HTML fragments while preserving their markup.
 *
 * @since 3.3.0 N°1365 - Highlight CaseLog and HTML attribute history differences
 */
class HtmlDiffHelper
{
	private const CHANGE_REMOVED = 'removed';
	private const CHANGE_ADDED = 'added';
	private const IMAGE_PLACEHOLDER = "\u{FFFC}";
	private const MAX_DIFF_TRACE_CELLS = 250000;
	private const BLOCK_TAGS = [
		'address', 'article', 'aside', 'blockquote', 'dd', 'div', 'dl', 'dt', 'fieldset', 'figcaption', 'figure',
		'footer', 'form', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'header', 'li', 'main', 'nav', 'ol', 'p', 'pre',
		'section', 'table', 'tbody', 'td', 'tfoot', 'th', 'thead', 'tr', 'ul',
	];

	/**
	 * Return both versions with removed and added visible text highlighted.
	 *
	 * @param string $sPreviousHtml
	 * @param string $sNewHtml
	 *
	 * @return array{previous: string, new: string}
	 * @throws \CoreException
	 * @since 3.3.0 N°1365 - Compare rendered rich-text versions
	 */
	public static function Compare(string $sPreviousHtml, string $sNewHtml): array
	{
		$aPrevious = static::ParseHtml(static::PrepareHtml($sPreviousHtml));
		$aNew = static::ParseHtml(static::PrepareHtml($sNewHtml));
		$aPreviousValues = array_column($aPrevious['tokens'], 'value');
		$aNewValues = array_column($aNew['tokens'], 'value');
		[$aRemovedIndexes, $aAddedIndexes] = static::FindChangedTokenIndexes($aPreviousValues, $aNewValues);

		return [
			'previous' => static::ApplyChanges($aPrevious, $aRemovedIndexes, static::CHANGE_REMOVED),
			'new' => static::ApplyChanges($aNew, $aAddedIndexes, static::CHANGE_ADDED),
		];
	}

	/**
	 * Sanitize and wiki-render content in the same way as the activity panel.
	 *
	 * @param string $sHtml
	 *
	 * @return string
	 * @since 3.3.0 N°1365 - Compare the same content users see
	 */
	private static function PrepareHtml(string $sHtml): string
	{
		return AttributeText::RenderWikiHtml(\HTMLSanitizer::Sanitize($sHtml), true);
	}

	/**
	 * Parse one HTML fragment and map its visible text tokens back to their DOM nodes.
	 *
	 * @param string $sHtml
	 *
	 * @return array{document: DOMDocument, root: DOMElement, tokens: array}
	 * @throws \CoreException
	 * @since 3.3.0 N°1365 - Tokenize rendered rich text
	 */
	private static function ParseHtml(string $sHtml): array
	{
		$oDocument = new DOMDocument('1.0', 'UTF-8');
		$oDocument->preserveWhiteSpace = true;
		$bPreviousInternalErrors = libxml_use_internal_errors(true);
		try {
			$bLoaded = $oDocument->loadHTML(
				'<?xml encoding="UTF-8"?><html><body><div id="itop-rich-text-diff-root">'.$sHtml.'</div></body></html>',
				LIBXML_HTML_NODEFDTD
			);
		} finally {
			libxml_clear_errors();
			libxml_use_internal_errors($bPreviousInternalErrors);
		}

		$oBody = $oDocument->getElementsByTagName('body')->item(0);
		$oRoot = ($oBody instanceof DOMElement) ? $oBody->firstChild : null;
		if (($bLoaded === false) || !($oRoot instanceof DOMElement)) {
			throw new CoreException('Unable to parse rich-text content for comparison');
		}

		$sVisibleText = '';
		$aTextSegments = [];
		$aSpecialTokens = [];
		foreach ($oRoot->childNodes as $oChild) {
			static::AppendVisibleContent($oChild, $sVisibleText, $aTextSegments, $aSpecialTokens, false);
		}

		return [
			'document' => $oDocument,
			'root' => $oRoot,
			'tokens' => static::TokenizeVisibleText($sVisibleText, $aTextSegments, $aSpecialTokens),
		];
	}

	/**
	 * Collect rendered text in document order and separate block-level content.
	 *
	 * @param \DOMNode $oNode
	 * @param string $sVisibleText
	 * @param array $aTextSegments
	 * @param array $aSpecialTokens
	 * @param bool $bPreserveWhitespace
	 *
	 * @since 3.3.0 N°1365 - Extract rendered text from rich-text fragments
	 */
	private static function AppendVisibleContent(DOMNode $oNode, string &$sVisibleText, array &$aTextSegments, array &$aSpecialTokens, bool $bPreserveWhitespace): void
	{
		if ($oNode instanceof DOMText) {
			$sNodeValue = $oNode->nodeValue ?? '';
			if ($sNodeValue !== '') {
				$iStart = strlen($sVisibleText);
				$sVisibleText .= $sNodeValue;
				$aTextSegments[] = [
					'start' => $iStart,
					'length' => strlen($sNodeValue),
					'node' => $oNode,
					'kind' => 'text',
					'preserve_whitespace' => $bPreserveWhitespace,
				];
			}

			return;
		}

		if (!($oNode instanceof DOMElement)) {
			return;
		}

		$sTagName = strtolower($oNode->tagName);
		if (in_array($sTagName, ['script', 'style', 'template', 'head', 'title', 'noscript'], true)) {
			return;
		}

		if ($sTagName === 'img') {
			$iStart = strlen($sVisibleText);
			$sVisibleText .= static::IMAGE_PLACEHOLDER;
			$aTextSegments[] = [
				'start' => $iStart,
				'length' => strlen(static::IMAGE_PLACEHOLDER),
				'node' => $oNode,
				'kind' => 'image',
			];
			$aSpecialTokens[$iStart] = [
				'value' => 'image:'.$oNode->getAttribute('src')."\0".$oNode->getAttribute('alt')."\0".$oNode->getAttribute('title'),
				'node' => $oNode,
			];

			return;
		}

		if ($sTagName === 'br') {
			static::AppendSeparator($sVisibleText);

			return;
		}

		$sWhiteSpaceStyle = strtolower($oNode->getAttribute('style'));
		$bPreserveWhitespace = $bPreserveWhitespace
			|| ($sTagName === 'pre')
			|| (preg_match('/(?:^|;)\s*white-space\s*:\s*(?:pre|pre-wrap|break-spaces)\b/', $sWhiteSpaceStyle) === 1);
		$bIsBlock = in_array($sTagName, static::BLOCK_TAGS, true);
		if ($bIsBlock) {
			static::AppendSeparator($sVisibleText);
		}
		foreach ($oNode->childNodes as $oChild) {
			static::AppendVisibleContent($oChild, $sVisibleText, $aTextSegments, $aSpecialTokens, $bPreserveWhitespace);
		}
		if ($bIsBlock) {
			static::AppendSeparator($sVisibleText);
		}
	}

	/**
	 * Insert a comparison-only space between rendered blocks.
	 *
	 * @param string $sVisibleText
	 *
	 * @since 3.3.0 N°1365 - Normalize rendered block boundaries
	 */
	private static function AppendSeparator(string &$sVisibleText): void
	{
		if (($sVisibleText !== '') && (preg_match('/[\p{Z}\s]\z/u', $sVisibleText) !== 1)) {
			$sVisibleText .= ' ';
		}
	}

	/**
	 * Tokenize visible text while retaining byte ranges for each text node.
	 *
	 * @param string $sVisibleText
	 * @param array $aTextSegments
	 * @param array $aSpecialTokens
	 *
	 * @return array<int, array{value: string, pieces: array, image_node: DOMElement|null}>
	 * @throws \CoreException
	 * @since 3.3.0 N°1365 - Preserve rich-text markup while diffing visible words
	 */
	private static function TokenizeVisibleText(string $sVisibleText, array $aTextSegments, array $aSpecialTokens): array
	{
		if ($sVisibleText === '') {
			return [];
		}

		$iMatchCount = preg_match_all(
			'/[\p{Z}\s]+|[\p{Han}\p{Hiragana}\p{Katakana}\p{Hangul}]|[\p{L}\p{M}\p{N}_]+|[^\p{Z}\s\p{L}\p{M}\p{N}_]/u',
			$sVisibleText,
			$aMatches,
			PREG_OFFSET_CAPTURE
		);
		if ($iMatchCount === false) {
			throw new CoreException('Unable to tokenize rich-text content for comparison');
		}

		$aTokens = [];
		$iFirstSegment = 0;
		foreach ($aMatches[0] as [$sRawToken, $iTokenStart]) {
			$iTokenEnd = $iTokenStart + strlen($sRawToken);
			$aSpecialToken = $aSpecialTokens[$iTokenStart] ?? null;
			$aPieces = [];
			$bPreserveWhitespace = false;

			while (isset($aTextSegments[$iFirstSegment]) && (($aTextSegments[$iFirstSegment]['start'] + $aTextSegments[$iFirstSegment]['length']) <= $iTokenStart)) {
				$iFirstSegment++;
			}
			for ($iSegment = $iFirstSegment; isset($aTextSegments[$iSegment]); $iSegment++) {
				$aSegment = $aTextSegments[$iSegment];
				if ($aSegment['start'] >= $iTokenEnd) {
					break;
				}

				$iOverlapStart = max($iTokenStart, $aSegment['start']);
				$iOverlapEnd = min($iTokenEnd, $aSegment['start'] + $aSegment['length']);
				if (($iOverlapStart < $iOverlapEnd) && ($aSegment['kind'] === 'text') && $aSegment['preserve_whitespace']) {
					$bPreserveWhitespace = true;
				}
				if (($iOverlapStart >= $iOverlapEnd) || ($aSegment['kind'] !== 'text') || !($aSegment['node'] instanceof DOMText)) {
					continue;
				}

				$aPieces[] = [
					'node' => $aSegment['node'],
					'start' => $iOverlapStart - $aSegment['start'],
					'length' => $iOverlapEnd - $iOverlapStart,
				];
			}

			if (is_array($aSpecialToken)) {
				$sTokenValue = $aSpecialToken['value'];
			} elseif ($bPreserveWhitespace && (preg_match('/^[\p{Z}\s]+$/u', $sRawToken) === 1)) {
				$sTokenValue = "whitespace:\0".$sRawToken;
			} else {
				$sTokenValue = preg_replace('/[\p{Z}\s]+/u', ' ', $sRawToken) ?? $sRawToken;
			}
			$aTokens[] = [
				'value' => $sTokenValue,
				'pieces' => $aPieces,
				'image_node' => is_array($aSpecialToken) && ($aSpecialToken['node'] instanceof DOMElement) ? $aSpecialToken['node'] : null,
			];
		}

		return $aTokens;
	}

	/**
	 * Return the token indexes whose content was removed or added.
	 *
	 * @param string[] $aPreviousValues
	 * @param string[] $aNewValues
	 *
	 * @return array{0: array<int, bool>, 1: array<int, bool>}
	 * @since 3.3.0 N°1365 - Identify added and removed rendered text
	 */
	private static function FindChangedTokenIndexes(array $aPreviousValues, array $aNewValues): array
	{
		$iPrefixLength = 0;
		$iPreviousCount = count($aPreviousValues);
		$iNewCount = count($aNewValues);
		while (($iPrefixLength < $iPreviousCount) && ($iPrefixLength < $iNewCount) && ($aPreviousValues[$iPrefixLength] === $aNewValues[$iPrefixLength])) {
			$iPrefixLength++;
		}

		$iSuffixLength = 0;
		while (($iSuffixLength < ($iPreviousCount - $iPrefixLength))
			&& ($iSuffixLength < ($iNewCount - $iPrefixLength))
			&& ($aPreviousValues[$iPreviousCount - $iSuffixLength - 1] === $aNewValues[$iNewCount - $iSuffixLength - 1])) {
			$iSuffixLength++;
		}

		$aPreviousMiddle = array_slice($aPreviousValues, $iPrefixLength, $iPreviousCount - $iPrefixLength - $iSuffixLength);
		$aNewMiddle = array_slice($aNewValues, $iPrefixLength, $iNewCount - $iPrefixLength - $iSuffixLength);
		$aEditScript = static::ComputeEditScript($aPreviousMiddle, $aNewMiddle);
		if ($aEditScript === null) {
			$aEditScript = array_merge(
				array_fill(0, count($aPreviousMiddle), 'delete'),
				array_fill(0, count($aNewMiddle), 'insert')
			);
		}

		$aRemovedIndexes = [];
		$aAddedIndexes = [];
		$iPreviousIndex = $iPrefixLength;
		$iNewIndex = $iPrefixLength;
		foreach ($aEditScript as $sOperation) {
			switch ($sOperation) {
				case 'same':
					$iPreviousIndex++;
					$iNewIndex++;
					break;

				case 'delete':
					$aRemovedIndexes[$iPreviousIndex] = true;
					$iPreviousIndex++;
					break;

				case 'insert':
					$aAddedIndexes[$iNewIndex] = true;
					$iNewIndex++;
					break;
			}
		}

		return [$aRemovedIndexes, $aAddedIndexes];
	}

	/**
	 * Build a shortest edit script using Myers' algorithm.
	 *
	 * @param string[] $aPreviousValues
	 * @param string[] $aNewValues
	 *
	 * @return string[]|null Null when the trace budget is exceeded
	 * @since 3.3.0 N°1365 - Compute a minimal rich-text edit script
	 */
	private static function ComputeEditScript(array $aPreviousValues, array $aNewValues): ?array
	{
		$iPreviousCount = count($aPreviousValues);
		$iNewCount = count($aNewValues);
		$aFrontier = [1 => 0];
		$aTrace = [];
		$iTraceCells = 0;
		$iFoundDepth = null;

		for ($iDepth = 0; $iDepth <= ($iPreviousCount + $iNewCount); $iDepth++) {
			$iTraceCells += count($aFrontier);
			if ($iTraceCells > static::MAX_DIFF_TRACE_CELLS) {
				return null;
			}

			for ($iDiagonal = -$iDepth; $iDiagonal <= $iDepth; $iDiagonal += 2) {
				if (($iDiagonal === -$iDepth)
					|| (($iDiagonal !== $iDepth) && (($aFrontier[$iDiagonal - 1] ?? -1) < ($aFrontier[$iDiagonal + 1] ?? -1)))) {
					$iX = $aFrontier[$iDiagonal + 1] ?? 0;
				} else {
					$iX = ($aFrontier[$iDiagonal - 1] ?? 0) + 1;
				}
				$iY = $iX - $iDiagonal;

				while (($iX < $iPreviousCount) && ($iY < $iNewCount) && ($aPreviousValues[$iX] === $aNewValues[$iY])) {
					$iX++;
					$iY++;
				}

				$aFrontier[$iDiagonal] = $iX;
				if (($iX >= $iPreviousCount) && ($iY >= $iNewCount)) {
					$iFoundDepth = $iDepth;
					break;
				}
			}

			$aTrace[] = $aFrontier;
			if ($iFoundDepth !== null) {
				break;
			}
		}

		if ($iFoundDepth === null) {
			return null;
		}

		$aReverseScript = [];
		$iX = $iPreviousCount;
		$iY = $iNewCount;
		for ($iDepth = $iFoundDepth; $iDepth > 0; $iDepth--) {
			$aPreviousFrontier = $aTrace[$iDepth - 1];
			$iDiagonal = $iX - $iY;
			if (($iDiagonal === -$iDepth)
				|| (($iDiagonal !== $iDepth) && (($aPreviousFrontier[$iDiagonal - 1] ?? -1) < ($aPreviousFrontier[$iDiagonal + 1] ?? -1)))) {
				$iPreviousDiagonal = $iDiagonal + 1;
				$iPreviousX = $aPreviousFrontier[$iPreviousDiagonal] ?? 0;
				$iPreviousY = $iPreviousX - $iPreviousDiagonal;
				while (($iX > $iPreviousX) && ($iY > $iPreviousY)) {
					$aReverseScript[] = 'same';
					$iX--;
					$iY--;
				}
				$aReverseScript[] = 'insert';
				$iY--;
			} else {
				$iPreviousDiagonal = $iDiagonal - 1;
				$iPreviousX = $aPreviousFrontier[$iPreviousDiagonal] ?? 0;
				$iPreviousY = $iPreviousX - $iPreviousDiagonal;
				while (($iX > $iPreviousX) && ($iY > $iPreviousY)) {
					$aReverseScript[] = 'same';
					$iX--;
					$iY--;
				}
				$aReverseScript[] = 'delete';
				$iX--;
			}
		}

		while (($iX > 0) && ($iY > 0)) {
			$aReverseScript[] = 'same';
			$iX--;
			$iY--;
		}
		while ($iX > 0) {
			$aReverseScript[] = 'delete';
			$iX--;
		}
		while ($iY > 0) {
			$aReverseScript[] = 'insert';
			$iY--;
		}

		return array_reverse($aReverseScript);
	}

	/**
	 * Mark the changed tokens in one parsed version and return its HTML fragment.
	 *
	 * @param array $aParsedHtml
	 * @param array<int, bool> $aChangedIndexes
	 * @param string $sChange
	 *
	 * @return string
	 * @since 3.3.0 N°1365 - Render the rich-text comparison
	 */
	private static function ApplyChanges(array $aParsedHtml, array $aChangedIndexes, string $sChange): string
	{
		$aRangesByNode = [];
		$aImageNodes = [];
		foreach ($aChangedIndexes as $iTokenIndex => $_) {
			$aToken = $aParsedHtml['tokens'][$iTokenIndex];
			if ($aToken['image_node'] instanceof DOMElement) {
				$aImageNodes[spl_object_hash($aToken['image_node'])] = $aToken['image_node'];
			}

			foreach ($aToken['pieces'] as $aPiece) {
				$sNodeHash = spl_object_hash($aPiece['node']);
				$aRangesByNode[$sNodeHash]['node'] = $aPiece['node'];
				$aRangesByNode[$sNodeHash]['ranges'][] = [
					$aPiece['start'],
					$aPiece['start'] + $aPiece['length'],
				];
			}
		}

		$sMarkerClass = 'ibo-rich-text-diff--'.$sChange;
		foreach ($aRangesByNode as $aNodeData) {
			/** @var DOMText $oTextNode */
			$oTextNode = $aNodeData['node'];
			$aRanges = $aNodeData['ranges'];
			usort($aRanges, static function (array $aLeft, array $aRight): int {
				return $aLeft[0] <=> $aRight[0];
			});
			$aMergedRanges = [];
			foreach ($aRanges as $aRange) {
				$iLastRangeIndex = count($aMergedRanges) - 1;
				if (($iLastRangeIndex >= 0) && ($aRange[0] <= $aMergedRanges[$iLastRangeIndex][1])) {
					$aMergedRanges[$iLastRangeIndex][1] = max($aMergedRanges[$iLastRangeIndex][1], $aRange[1]);
				} else {
					$aMergedRanges[] = $aRange;
				}
			}

			$oDocument = $aParsedHtml['document'];
			$oFragment = $oDocument->createDocumentFragment();
			$sText = $oTextNode->nodeValue ?? '';
			$iCursor = 0;
			foreach ($aMergedRanges as [$iStart, $iEnd]) {
				if ($iStart > $iCursor) {
					$oFragment->appendChild($oDocument->createTextNode(substr($sText, $iCursor, $iStart - $iCursor)));
				}

				$oMarker = $oDocument->createElement('span');
				$oMarker->setAttribute('class', $sMarkerClass);
				$oMarker->appendChild($oDocument->createTextNode(substr($sText, $iStart, $iEnd - $iStart)));
				$oFragment->appendChild($oMarker);
				$iCursor = $iEnd;
			}
			if ($iCursor < strlen($sText)) {
				$oFragment->appendChild($oDocument->createTextNode(substr($sText, $iCursor)));
			}

			$oParent = $oTextNode->parentNode;
			if ($oParent !== null) {
				$oParent->replaceChild($oFragment, $oTextNode);
			}
		}

		foreach ($aImageNodes as $oImageNode) {
			$oParent = $oImageNode->parentNode;
			if ($oParent === null) {
				continue;
			}

			$oWrapper = $aParsedHtml['document']->createElement('span');
			$oWrapper->setAttribute('class', $sMarkerClass.' ibo-rich-text-diff--image');
			$oParent->replaceChild($oWrapper, $oImageNode);
			$oWrapper->appendChild($oImageNode);
		}

		$sHtml = '';
		foreach ($aParsedHtml['root']->childNodes as $oChild) {
			$sHtml .= $aParsedHtml['document']->saveHTML($oChild);
		}

		return $sHtml;
	}
}
