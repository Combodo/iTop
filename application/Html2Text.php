<?php
namespace Html2Text;

if (!function_exists('mb_split'))
{
	function mb_split($pattern, $subject, $limit = -1)
	{
		return preg_split('/'.$pattern.'/', $subject, $limit);
	}
}
/**
 * Replace all occurrences of the search string with the replacement string.
 *
 * @author Sean Murphy <sean@iamseanmurphy.com>
 * @copyright Copyright 2012 Sean Murphy. All rights reserved.
 * @license http://creativecommons.org/publicdomain/zero/1.0/
 * @link http://php.net/manual/function.str-replace.php
 *
 * @param mixed $search
 * @param mixed $replace
 * @param mixed $subject
 * @param int $count
 * @return mixed
 */
function mb_str_replace($search, $replace, $subject, &$count = 0) {
	if (!is_array($subject)) {
		// Normalize $search and $replace so they are both arrays of the same length
		$searches = is_array($search) ? array_values($search) : array($search);
		$replacements = is_array($replace) ? array_values($replace) : array($replace);
		$replacements = array_pad($replacements, count($searches), '');
		foreach ($searches as $key => $search) {
			$parts = mb_split(preg_quote($search), $subject);
			$count += count($parts) - 1;
			$subject = implode($replacements[$key], $parts);
		}
	} else {
		// Call mb_str_replace for each subject in array, recursively
		foreach ($subject as $key => $value) {
			$subject[$key] = mb_str_replace($search, $replace, $value, $count);
		}
	}
	return $subject;
}

/******************************************************************************
 * Copyright (c) 2010 Jevon Wright and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * or
 *
 * LGPL which is available at http://www.gnu.org/licenses/lgpl.html
 *
 *
 * Contributors:
 *    Jevon Wright - initial API and implementation
 *    Denis Flaven - some fixes for properly handling UTF-8 characters
 ****************************************************************************/

class Html2Text {

	/**
	 * Tries to convert the given HTML into a plain text format - best suited for
	 * e-mail display, etc.
	 *
	 * <p>In particular, it tries to maintain the following features:
	 * <ul>
	 *   <li>Links are maintained, with the 'href' copied over
	 *   <li>Information in the &lt;head&gt; is lost
	 * </ul>
	 *
	 * @param string html the input HTML
	 * @return string the HTML converted, as best as possible, to text
	 * @throws Html2TextException if the HTML could not be loaded as a {@link DOMDocument}
	 */
	static function convert($html) {
		// replace &nbsp; with spaces

		$html = str_replace("&nbsp;", " ", $html);
		$html = mb_str_replace("\xc2\xa0", " ", $html); // DO NOT USE str_replace since it breaks the "à" character which is \xc3 \xa0 in UTF-8

		$html = static::fixNewlines($html);

		$doc = new \DOMDocument();
		if (!@$doc->loadHTML('<?xml encoding="UTF-8">'.$html)) // Forces the UTF-8 character set for HTML fragments
		{
			throw new Html2TextException("Could not load HTML - badly formed?", $html);
		}

		$output = static::iterateOverNode($doc);

		// remove leading and trailing spaces on each line
		$output = preg_replace("/[ \t]*\n[ \t]*/im", "\n", $output);
		$output = preg_replace("/ *\t */im", "\t", $output);

		// remove unnecessary empty lines
		$output = preg_replace("/\n\n\n*/im", "\n\n", $output);

		// remove leading and trailing whitespace
		$output = trim($output);

		return $output;
	}

	/**
	 * Unify newlines; in particular, \r\n becomes \n, and
	 * then \r becomes \n. This means that all newlines (Unix, Windows, Mac)
	 * all become \ns.
	 *
	 * @param string text text with any number of \r, \r\n and \n combinations
	 * @return string the fixed text
	 */
	static function fixNewlines($text) {
		// replace \r\n to \n
		$text = str_replace("\r\n", "\n", $text);
		// remove \rs
		$text = str_replace("\r", "\n", $text);

		return $text;
	}

	static function nextChildName($node) {
		// get the next child
		$nextNode = $node->nextSibling;
		while ($nextNode != null) {
			if ($nextNode instanceof \DOMElement) {
				break;
			}
			$nextNode = $nextNode->nextSibling;
		}
		$nextName = null;
		if ($nextNode instanceof \DOMElement && $nextNode != null) {
			$nextName = strtolower($nextNode->nodeName);
		}

		return $nextName;
	}

	static function prevChildName($node) {
		// get the previous child
		$nextNode = $node->previousSibling;
		while ($nextNode != null) {
			if ($nextNode instanceof \DOMElement) {
				break;
			}
			$nextNode = $nextNode->previousSibling;
		}
		$nextName = null;
		if ($nextNode instanceof \DOMElement && $nextNode != null) {
			$nextName = strtolower($nextNode->nodeName);
		}

		return $nextName;
	}

	static function iterateOverNode($node) {
		if ($node instanceof \DOMText) {
		  // Replace whitespace characters with a space (equivilant to \s)
			return preg_replace("/[\\t\\n\\f\\r ]+/im", " ", $node->wholeText);
		}
		if ($node instanceof \DOMDocumentType) {
			// ignore
			return "";
		}

		$nextName = static::nextChildName($node);
		$prevName = static::prevChildName($node);

		$name = strtolower($node->nodeName);

		// start whitespace
		switch ($name) {
			case "hr":
				return "---------------------------------------------------------------\n";

			case "style":
			case "head":
			case "title":
			case "meta":
			case "script":
				// ignore these tags
				return "";

			case "h1":
			case "h2":
			case "h3":
			case "h4":
			case "h5":
			case "h6":
			case "ol":
			case "ul":
				// add two newlines, second line is added below
				$output = "\n";
				break;

			case "td":
			case "th":
				// add tab char to separate table fields
			   $output = "\t";
			   break;

			case "tr":
			case "p":
			case "div":
				// add one line
				$output = "\n";
				break;

			case "li":
				$output = "- ";
				break;

			default:
				// print out contents of unknown tags
				$output = "";
				break;
		}

		// debug
		//$output .= "[$name,$nextName]";

		if (isset($node->childNodes)) {
			for ($i = 0; $i < $node->childNodes->length; $i++) {
				$n = $node->childNodes->item($i);

				$text = static::iterateOverNode($n);

				$output .= $text;
			}
		}

		// end whitespace
		switch ($name) {
			case "h1":
			case "h2":
			case "h3":
			case "h4":
			case "h5":
			case "h6":
				$output .= "\n";
				break;

			case "p":
			case "br":
				// add one line
				if ($nextName != "div")
					$output .= "\n";
				break;

			case "div":
				// add one line only if the next child isn't a div
				if ($nextName != "div" && $nextName != null)
					$output .= "\n";
				break;

			case "a":
				// links are returned in [text](link) format
				$href = $node->getAttribute("href");

				$output = trim($output);

				// remove double [[ ]] s from linking images
				if (substr($output, 0, 1) == "[" && substr($output, -1) == "]") {
					$output = substr($output, 1, strlen($output) - 2);

					// for linking images, the title of the <a> overrides the title of the <img>
					if ($node->getAttribute("title")) {
						$output = $node->getAttribute("title");
					}
				}

				// if there is no link text, but a title attr
				if (!$output && $node->getAttribute("title")) {
					$output = $node->getAttribute("title");
				}

				if ($href == null) {
					// it doesn't link anywhere
					if ($node->getAttribute("name") != null) {
						$output = "[$output]";
					}
				} else {
					if ($href == $output || $href == "mailto:$output" || $href == "http://$output" || $href == "https://$output") {
						// link to the same address: just use link
						$output;
					} else {
						// replace it
						if ($output) {
							$output = "[$output]($href)";
						} else {
							// empty string
							$output = $href;
						}
					}
				}

				// does the next node require additional whitespace?
				switch ($nextName) {
					case "h1": case "h2": case "h3": case "h4": case "h5": case "h6":
						$output .= "\n";
						break;
				}
				break;

			case "img":
				if ($node->getAttribute("title")) {
					$output = "[" . $node->getAttribute("title") . "]";
				} elseif ($node->getAttribute("alt")) {
					$output = "[" . $node->getAttribute("alt") . "]";
				} else {
					$output = "";
				}
				break;

			case "li":
				$output .= "\n";
				break;

			default:
				// do nothing
		}

		return $output;
	}

}
