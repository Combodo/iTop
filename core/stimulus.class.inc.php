<?php

/**
 * A stimulus is the trigger that makes the lifecycle go ahead (state machine) 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */

// #@# Really dirty !!!
// #@# TO BE CLEANED -> ALIGN WITH OTHER METAMODEL DECLARATIONS

class ObjectStimulus
{
	private $m_aParams = array();
	private $m_sHostClass = null;
	private $m_sCode = null;

	public function __construct($aParams)
	{
		// obsolete: $this->m_aParams = $aParams;
		$this->m_aParams['label'] = 'foo';
		$this->m_aParams['description'] = 'foo';
		$this->ConsistencyCheck();
	}

	public function SetHostClass($sHostClass)
	{
		$this->m_sHostClass = $sHostClass;
	}
	public function GetHostClass()
	{
		return $this->m_sHostClass;
	}
	public function SetCode($sCode)
	{
		$this->m_sCode = $sCode;
		$this->m_aParams['label'] = Dict::S('Class:'.$this->m_sHostClass.'/Stimulus:'.$this->m_sCode, $this->m_sCode); 
		$this->m_aParams['description'] = Dict::S('Class:'.$this->m_sHostClass.'/Stimulus:'.$this->m_sCode.'+', '');
	}
	public function GetCode()
	{
		return $this->m_sCode;
	}

	public function Get($sParamName) {return $this->m_aParams[$sParamName];}

	// Note: I could factorize this code with the parameter management made for the AttributeDef class
	// to be overloaded
	static protected function ListExpectedParams()
	{
		return array("label", "description");
	}

	private function ConsistencyCheck()
	{

		// Check that any mandatory param has been specified
		//
		$aExpectedParams = $this->ListExpectedParams();
		foreach($aExpectedParams as $sParamName)
		{
			if (!array_key_exists($sParamName, $this->m_aParams))
			{
				$aBacktrace = debug_backtrace();
				$sTargetClass = $aBacktrace[2]["class"];
				$sCodeInfo = $aBacktrace[1]["file"]." - ".$aBacktrace[1]["line"];
				throw new CoreException("missing parameter '$sParamName' in ".get_class($this)." declaration for class $sTargetClass ($sCodeInfo)");
			}
		}
	}
}



class StimulusUserAction extends ObjectStimulus
{
}

?>
