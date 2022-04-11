<?php
require_once('../approot.inc.php');
require_once(APPROOT.'/application/startup.inc.php');

function GetDirectChildClasses($sRootClass)
{
  $aDirectChildClasses = array();
  $aAllChildClasses = MetaModel::EnumChildClasses($sRootClass);
  foreach ($aAllChildClasses as $sChildClass)
  {
    if (get_parent_class($sChildClass) == $sRootClass)
    {
      $aDirectChildClasses[] = $sChildClass;
    }
  }
  return $aDirectChildClasses;
}

// START

$fStart = microtime(true);

// this script produces a working query with a class linking fci to fci on the said customers datamodel.
// even when giving $aGivenClasses = array("CiManagedByCi", "CiManagedByCi");, so: simulating two times
// querying the problematic class, it produces a query, which works.

// additional optimization: merge the combodo-optimization from DbObjectSet::Load() in the workround for N.689
// to only request the finalclass of the to-be-retrieved-objects


// like from this line: foreach($this->m_oFilter->GetSelectedClasses() as $sClassAlias => $sClass)
// this the line in DbObjectSet::Load() within the workaround for N.689
// -> we might have more than one given class, so we start the prototype from the
// exact same point.
$aGivenClasses = array("CiManagedByCi");
// This works aswell:
// $aGivenClasses = array("CiManagedByCi", "CiManagedByCi");

// now we are going to create a new array with this structure:
// array(
//   "givenclass1" => array(
//     "extkeyattr1" => array(all, direct, subclasses, of, the, targetclass, of, extkeyattr1),
//     "extkeyattr2" => array(all, direct, subclasses, of, the, targetclass, of, extkeyattr2),
//   ),
//   "givenclass2" => array(...),
//   ...
// )

$aJoinClasses = array();
foreach ($aGivenClasses as $sClass)
{
	$aAttrs = MetaModel::GetAttributesList($sClass);
	foreach ($aAttrs as $sAttr)
	{
    $oAttrDef = MetaModel::GetAttributeDef($sClass, $sAttr);
    $sAttrCode = $oAttrDef->GetCode();
		if ($oAttrDef->IsExternalKey(EXTKEY_RELATIVE) ||
        $oAttrDef->IsExternalKey(EXTKEY_ABSOLUTE) ||
        $oAttrDef->IsHierarchicalKey() // ||
        // $oAttrDef->IsExternalField() // we dont need them here, we only need
        // the directly linked classes
    )
		{
			$sTargetClass =  $oAttrDef->GetTargetClass();
			$aChildClasses = GetDirectChildClasses($sTargetClass);
      foreach ($aChildClasses as $sChildClass)
      {
          $aJoinClasses[$sClass][$sAttrCode][] = $sChildClass;
      }
    }
  }
}

// echo "<pre>";
// var_dump($aJoinClasses);
// echo "</pre>";
// die();

$aSingleQueries = array();
$iOuterCount = 1;
$iInnerCount = 1;

foreach ($aJoinClasses as $sGivenClass => $aAttrCodes)
{
  foreach ($aAttrCodes as $sAttrCode => $aSubClasses)
  {
    // TODO: Check, if there exists a where-clause containing a question to an external field at the rootclass.
    // if so, check if the given subclass actually _has_ the field referenced in the external key. if so: add the where-clause.
    // if not, we can just skip this class, since the where-clause with this subclass makes no sense.
    // note: a where-clause possibly even reduces the amount of produced queries, but never adds to it.
    foreach ($aSubClasses as $sSubClass)
    {
      // TODO: Re-Insert the possible WHERE-parts before the UNION.
      // This means the possibly set Filter for the ext-key aswell!
      // If both given, it should be possible to simply concat them, should it?
      $aSingleQueries[] = "SELECT {$sGivenClass} AS o{$iOuterCount} JOIN {$sSubClass} AS j{$iInnerCount} ON o{$iOuterCount}.{$sAttrCode} = j{$iInnerCount}.id UNION ";
      // TODO: Implement recursion, to further subdivide a class, which still returns the 1116-MySQL-Error
      $iInnerCount++;
    }
  }
  $iOuterCount++;
}

$sAllUnions = implode("", $aSingleQueries);
$sAllUnionsClean = substr($sAllUnions, 0, -7); // removes the last " UNION" at the end.


$oObjSet = new DBObjectSet(DBSearch::FromOQL($sAllUnionsClean));
$iCount = $oObjSet->Count();
$oObjSet->Load();
$fTimeElapsedSecs = microtime(true) - $fStart;
echo "<ul>";
echo "<li>Reworked query ran successful and reports a count of " . $oObjSet->Count() . " elements.</li>";
echo "<li>Execution (including generating the new query, counting the set _and_ loading all data) took " . number_format($fTimeElapsedSecs, 3) . " seconds.</li>";
echo "</ul>";


echo "<pre>";
print_r($sAllUnionsClean);
echo "</pre>";
die();
