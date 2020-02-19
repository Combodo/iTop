<?php
/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 31/12/2019
 * Time: 12:29
 */

namespace Combodo\iTop\Config\Validator;

class iTopConfigSyntaxValidator
{

	/**
	 * @param $sRawConfig
	 *
	 * @throws \Exception
	 */
	public function Validate($sRawConfig)
	{
		try
		{
			ini_set('display_errors', 1);
			ob_start();
			// in PHP < 7.0.0 syntax errors are in output
			// in PHP >= 7.0.0 syntax errors are thrown as Error
			$sConfig = preg_replace(array('#^\s*<\?php#', '#\?>\s*$#'), '', $sRawConfig);
			eval('if(0){'.trim($sConfig).'}');
			$sNoise = trim(ob_get_contents());
		}
		catch (\Error $e)
		{
			// ParseError only thrown in PHP7
			throw new \Exception('Error in configuration: '.$e->getMessage().' at line '.$e->getLine());
		}
		finally
		{
			ob_end_clean();
		}

		if (strlen($sNoise) > 0)
		{
			if (preg_match("/(Error|Parse error|Notice|Warning): (.+) in \S+ : eval\(\)'d code on line (\d+)/i", strip_tags($sNoise), $aMatches))
			{
				$sMessage = $aMatches[2];
				$sLine = $aMatches[3];
				$sMessage = \Dict::Format('config-parse-error', $sMessage, $sLine);
				throw new \Exception($sMessage);
			}
			else
			{
				// Note: sNoise is an html output, but so far it was ok for me (e.g. showing the entire call stack)
				throw new \Exception('Syntax error in configuration file: <tt>'.$sNoise.'</tt>');
			}
		}
	}
}