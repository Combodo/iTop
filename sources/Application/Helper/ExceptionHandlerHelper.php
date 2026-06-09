<?php

namespace Combodo\iTop\Application\Helper;

use Combodo\iTop\Application\WebPage\ErrorPage;
use IssueLog;
use SimpleXMLElement;
use Throwable;

class ExceptionHandlerHelper
{
	public static array $aSupportedMimeTypes = [
		'application/json' => 'json',
		'application/xml'  => 'xml',
		'text/html'        => 'html',
		'text/plain'       => 'text',
	];

	public static function HandleException(Throwable $oException)
	{
		$aStatus = ob_get_status();
		if (count($aStatus) !== 0) {
			ob_end_clean();
		}

		// Log the exception
		IssueLog::Exception('Fatal error', $oException);

		$mime = self::NegotiateMimeType();

		if ($mime === null) {
			http_response_code(406);
			header('Content-Type: application/json; charset=utf-8');
			header('Vary: Accept');
			echo json_encode(['error' => 'Not Acceptable'], JSON_UNESCAPED_UNICODE);
			return;
		}

		http_response_code(500);
		header("Content-Type: {$mime}; charset=utf-8");
		header('Vary: Accept');

		$aData = [
			'error' => 'Fatal error',
			'message' => 'We are sorry, an unexpected error has occurred. Please try again later.',

		];

		switch (self::$aSupportedMimeTypes[$mime]) {
			case 'json':
				echo json_encode($aData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
				break;

			case 'xml':
				$oXml = new SimpleXMLElement('<response/>');
				array_walk_recursive($aData, function ($sValue, $sKey) use ($oXml) {
					$oXml->addChild((string)$sKey, htmlspecialchars((string)$sValue, ENT_QUOTES | ENT_XML1, 'UTF-8'));
				});
				echo $oXml->asXML();
				break;

			case 'html':
				// Create error page
				$oErrorPage = new ErrorPage('Fatal error');
				$oErrorPage->error('We are sorry, an unexpected error has occurred. Please try again later.<br><br>', $oException);
				$oErrorPage->output();
				break;

			case 'text':
				echo "Fatal error\n";
				echo "We are sorry, an unexpected error has occurred. Please try again later.\n";
				break;
		}
	}

	public static function NegotiateMimeType(): ?string
	{
		$supportedMimes = array_keys(self::$aSupportedMimeTypes);
		$acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? '*/*';

		if (trim($acceptHeader) === '' || $acceptHeader === '*/*') {
			return in_array('application/json', $supportedMimes, true) ? 'application/json' : $supportedMimes[0];
		}

		$accepted = [];
		foreach (explode(',', $acceptHeader) as $part) {
			$part = trim($part);
			$q = 1.0;
			if (str_contains($part, ';')) {
				[$type, $params] = array_map('trim', explode(';', $part, 2));
				if (preg_match('/q=([0-9.]+)/', $params, $m)) {
					$q = (float)$m[1];
				}
			} else {
				$type = $part;
			}
			$accepted[] = ['type' => $type, 'q' => $q];
		}

		usort($accepted, fn ($a, $b) => $b['q'] <=> $a['q']);

		foreach ($accepted as $a) {
			foreach ($supportedMimes as $mime) {
				if ($a['type'] === $mime || $a['type'] === '*/*') {
					return $mime;
				}
				// Ex: application/* match application/json
				if (str_ends_with($a['type'], '/*')) {
					$prefix = explode('/', $a['type'])[0].'/';
					if (str_starts_with($mime, $prefix)) {
						return $mime;
					}
				}
			}
		}

		return null;
	}
}
