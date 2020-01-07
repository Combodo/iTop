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
	 * validate
	 *
	 * @param $sConfig
	 * @param $bAllowUnsecure
	 */
	public function Validate($sConfig, $bAllowUnsecure)
	{
		exec('php -v', $aOutput, $iReturnVar);
		$bCanRunCli = ($iReturnVar == 0);

		if ($bCanRunCli)
		{
			$this->CheckSyntaxSecure($sConfig);
		}
		elseif($bAllowUnsecure)
		{
			$this->CheckSyntaxNotSecure($sConfig);
		}
		else
		{
			throw new \Exception('Cannot check configuration syntax: PHP CLI is not accessible.'."\n".implode("\n", $aOutput));
		}
	}

	/**
	 * This will use the php cli linter in order to check the syntax,
	 *
	 * The php cli may not be based on the same php version, but since the cron run using the cli, we can assume that it is well configured anyway...
	 * Also, the config syntax is very limited so there should not be a problem with checking the validity against another php version
	 *
	 * @param $sConfig
	 * @param $iReturnVar
	 * @param $aOutput
	 *
	 * @return array
	 */
	private function CheckSyntaxSecure($sConfig)
	{
		$sTempFile = tempnam(sys_get_temp_dir(), 'syntax_check_me_').'.temp.txt';
		file_put_contents($sTempFile, $sConfig);
		exec("php -l $sTempFile 2>&1", $aOutput, $iReturnVar);
		unlink($sTempFile);

		if ($iReturnVar != 0)
		{
			throw new \Exception(implode("\n", $aOutput));
		}
	}

	/**
	 * @param $sRawConfig
	 */
	private function CheckSyntaxNotSecure($sRawConfig)
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
			ob_end_clean();
		}
		catch (Error $e)
		{
			// ParseError only thrown in PHP7
			throw new Exception('Error in configuration: '.$e->getMessage().' at line '.$e->getLine());
		}
		if (strlen($sNoise) > 0)
		{
			if (preg_match("/(Error|Parse error|Notice|Warning): (.+) in \S+ : eval\(\)'d code on line (\d+)/i", strip_tags($sNoise), $aMatches))
			{
				$sMessage = $aMatches[2];
				$sLine = $aMatches[3];
				$sMessage = Dict::Format('config-parse-error', $sMessage, $sLine);
				throw new Exception($sMessage);
			}
			else
			{
				// Note: sNoise is an html output, but so far it was ok for me (e.g. showing the entire call stack)
				throw new Exception('Syntax error in configuration file: <tt>'.$sNoise.'</tt>');
			}
		}
	}
}