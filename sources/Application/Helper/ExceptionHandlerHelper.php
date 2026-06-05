<?php

namespace Combodo\iTop\Application\Helper;

use Combodo\iTop\Application\WebPage\ErrorPage;
use IssueLog;
use Throwable;

class ExceptionHandlerHelper
{
	static array $aSupportedMimeTypes = [
		'application/json' => 'json',
		'application/xml'  => 'xml',
		'text/html'        => 'html',
		'text/plain'       => 'text',
	];

	public static function HandleException(Throwable $oException)
	{
		ob_end_clean();

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

		switch (self::$aSupportedMimeTypes[$mime]) {
			case 'json':
//				echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
				break;

			case 'xml':
				// Version simple: sérialisation minimale
//				$xml = new SimpleXMLElement('<response/>');
//				array_walk_recursive((array)$data, function ($value, $key) use ($xml) {
//					$xml->addChild((string)$key, htmlspecialchars((string)$value, ENT_QUOTES | ENT_XML1, 'UTF-8'));
//				});
//				echo $xml->asXML();
				break;

			case 'html':
				// Create error page
				$oErrorPage = new ErrorPage('Fatal error');
				$oErrorPage->error('We are sorry, an unexpected error has occurred. Please try again later.<br><br>', $oException);
				$oErrorPage->output();
				break;

			case 'text':
				echo 'Fatal error';
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

		usort($accepted, fn($a, $b) => $b['q'] <=> $a['q']);

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