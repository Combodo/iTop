<?php
require_once("../application/utils.inc.php");
/**
 * Helper class to store and manipulate the parameters that make the application's context
 *
 * Usage:
 * 1) Build the application's context by constructing the object
 *   (the object will read some of the page's parameters)
 *
 * 2) Add these parameters to hyperlinks or to forms using the helper, functions
 *    GetForLink(), GetForForm() or GetAsHash()
 */
class ApplicationContext
{
	protected $aNames;
	protected $aValues;
	
	public function __construct()
	{
		$this->aNames = array(
			'org_id', 'menu'
		);
		$this->ReadContext();
	}
	
	/**
	 * Read the context directly in the PHP parameters (either POST or GET)
	 * return nothing
	 */
	protected function ReadContext()
	{
		$this->aValues = array();
		foreach($this->aNames as $sName)
		{
			$sValue = utils::ReadParam($sName, '');
			// TO DO: check if some of the context parameters are mandatory (or have default values)
			if (!empty($sValue))
			{
				$this->aValues[$sName] = $sValue;
			}
		}
	}
	
	/**
	 * Returns the context as string with the format name1=value1&name2=value2....
	 * return string The context as a string to be appended to an href property
	 */
	public function GetForLink()
	{
		$aParams = array();
		foreach($this->aValues as $sName => $sValue)
		{
			$aParams[] = $sName.'='.urlencode($sValue);
		}
		return implode("&", $aParams);
	}
	
	/**
	 * Returns the context as sequence of input tags to be inserted inside a <form> tag
	 * return string The context as a sequence of <input type="hidden" /> tags
	 */
	public function GetForForm()
	{
		$sContext = "";
		foreach($this->aValues as $sName => $sValue)
		{
			$sContext .= "<input type=\"hidden\" name=\"$sName\" value=\"$sValue\" />\n";
		}
		return $sContext;
	}

	/**
	 * Returns the context as a hash array 'parameter_name' => value
	 * return array The context information
	 */
	public function GetAsHash()
	{
		return $this->aValues;
	}
}
?>
