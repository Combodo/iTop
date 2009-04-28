<?php

/**
 * ValueSetDefinition
 * value sets API and implementations
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */

require_once('MyHelpers.class.inc.php');

abstract class ValueSetDefinition
{
	protected $m_bIsLoaded = false;
	protected $m_aValues = array();
	protected $m_aArgsObj = array();
	protected $m_aArgsApp = array();


	// Displayable description that could be computed out of the std usage context
	public function GetValuesDescription()
	{
		$aValues = $this->GetValues(array(), '');
		$aDisplayedValues = array();
		foreach($aValues as $key => $value)
		{
			$aDisplayedValues[] = "$key => $value";
		}
		$sAllowedValues = implode(', ', $aDisplayedValues);
		return $sAllowedValues;
	}


	public function GetValues($aArgs, $sBeginsWith)
	{
		if (!$this->m_bIsLoaded)
		{
			$this->LoadValues($aArgs);
			$this->m_bIsLoaded = true;
		}
		if (strlen($sBeginsWith) == 0)
		{
			$aRet = $this->m_aValues;
		}
		else
		{
			$iCheckedLen = strlen($sBeginsWith);
			$sBeginsWith = strtolower($sBeginsWith);
			$aRet = array();
			foreach ($this->m_aValues as $sKey=>$sValue)
			{
				if (strtolower(substr($sValue, 0, $iCheckedLen)) == $sBeginsWith)
				{
					$aRet[$sKey] = $sValue;
				}
			}
		}
		return $aRet;
	}

	public function ListArgsFromContextApp()
	{
		return $this->m_aArgsObj;
	}
	public function ListArgsFromContextObj()
	{
		return $this->m_aArgsApp;
	}

	abstract protected function LoadValues($aArgs);
}


/**
 * Set of existing values for an attribute, given a search filter 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class ValueSetObjects extends ValueSetDefinition
{
	protected $m_sFilterExpr; // in SibuSQL
	protected $m_sValueAttCode;
	protected $m_aOrderBy;

	public function __construct($sFilterExp, $sValueAttCode = '', $aOrderBy = array())
	{
		$this->m_sFilterExpr = $sFilterExp;
		$this->m_sValueAttCode = $sValueAttCode;
		$this->m_aOrderBy = $aOrderBy;
	}

	protected function LoadValues($aArgs)
	{
		$this->m_aValues = array();
		
		$oFilter = DBObjectSearch::FromSibuSQL($this->m_sFilterExpr, $aArgs);
		if (!$oFilter) return false;

        if (empty($this->m_sValueAttCode))
        {
            $this->m_sValueAttCode = MetaModel::GetNameAttributeCode($oFilter->GetClass());
        }
		$oObjects = new DBObjectSet($oFilter, $this->m_aOrderBy);
		while ($oObject = $oObjects->Fetch())
		{
			$this->m_aValues[$oObject->GetKey()] = $oObject->GetAsHTML($this->m_sValueAttCode);
		}
		return true;
	}
	
	public function GetValuesDescription()
	{
		return 'Filter: '.$this->m_sFilterExpr;
	}
}


/**
 * Set of existing values for an attribute, given a search filter and a relation id 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class ValueSetRelatedObjects extends ValueSetObjects
{
	public function __construct($sFilterExp, $sRelCode, $sClass, $sValueAttCode = '', $aOrderBy = array())
	{
		$sFullFilterExp = "$sClass: RELATED ($sRelCode, 1) TO ($sFilterExp)";
		parent::__construct($sFullFilterExp, $sValueAttCode, $aOrderBy);
	}
}


/**
 * Set oof existing values for an attribute, given a set of objects (AttributeLinkedSet) 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class ValueSetRelatedObjectsFromLinkedSet extends ValueSetDefinition
{
	protected $m_sLinkedSetAttCode;
	protected $m_sRelCode;
	protected $m_sValueAttCode;
	protected $m_aOrderBy;

	public function __construct($sLinkedSetAttCode, $sRelCode, $sValueAttCode = '', $aOrderBy = array())
	{
		$this->m_sLinkedSetAttCode = $sLinkedSetAttCode;
		$this->m_sRelCode = $sRelCode;
		$this->m_sValueAttCode = $sValueAttCode;
		$this->m_aOrderBy = $aOrderBy;
	}

	protected function LoadValues($aArgs)
	{
		$this->m_aValues = array();

        if (empty($this->m_sValueAttCode))
        {
            $this->m_sValueAttCode = MetaModel::GetNameAttributeCode($oFilter->GetClass());
        }

        $oCurrentObject = @$aArgs['*this*'];
        if (!is_object($oCurrentObject)) return false;

		$oObjects = $oCurrentObject->Get($this->m_sLinkedSetAttCode);
		while ($oObject = $oObjects->Fetch())
		{
			$this->m_aValues[$oObject->GetKey()] = $oObject->Get($this->m_sValueAttCode);
		}
		return true;
	}
	
	public function GetValuesDescription()
	{
		return 'Objects related ('.$this->m_sRelCode.') to objects linked through '.$this->m_sLinkedSetAttCode;
	}
}


/**
 * Fixed set values (could be hardcoded in the business model) 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class ValueSetEnum extends ValueSetDefinition
{
	public function __construct($Values)
	{
		if (is_array($Values))
		{
			$aValues = $Values;
		}
		else
		{
			$aValues = array();
			foreach (explode(",", $Values) as $sVal)
			{
				$sVal = trim($sVal);
				$sKey = $sVal; 
				$aValues[$sKey] = $sVal;
			}
		}
		$this->m_aValues = $aValues;
	}

	protected function LoadValues($aArgs)
	{
		return true;
	}
}

?>
