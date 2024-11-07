<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Portal\Helper;

use BinaryExpression;
use Combodo\iTop\Portal\Brick\BrickCollection;
use CorePortalInvalidActionRuleException;
use DBObject;
use DBObjectSearch;
use DBObjectSet;
use DBProperty;
use DBSearch;
use DeprecatedCallsLog;
use DOMFormatException;
use DOMNodeList;
use Exception;
use FieldExpression;
use IssueLog;
use MetaModel;
use ModuleDesign;
use ScalarExpression;
use SimpleCrypt;
use Symfony\Component\Routing\RouterInterface;
use TrueExpression;
use UserRights;
use const UR_ACTION_READ;

/**
 * Class ContextManipulatorHelper
 *
 * @package Combodo\iTop\Portal\Helper
 * @since 2.3.0
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class ContextManipulatorHelper
{
	/** @var string ENUM_RULE_CALLBACK_BACK */
	const ENUM_RULE_CALLBACK_BACK = 'back';
	/** @var string ENUM_RULE_CALLBACK_GOTO */
	const ENUM_RULE_CALLBACK_GOTO = 'goto';
	/** @var string ENUM_RULE_CALLBACK_OPEN */
	const ENUM_RULE_CALLBACK_OPEN = 'open';
	/** @var string ENUM_RULE_CALLBACK_OPEN_VIEW */
	const ENUM_RULE_CALLBACK_OPEN_VIEW = 'view';
	/** @var string ENUM_RULE_CALLBACK_OPEN_EDIT */
	const ENUM_RULE_CALLBACK_OPEN_EDIT = 'edit';
	/** @var string DEFAULT_RULE_CALLBACK_OPEN */
	const DEFAULT_RULE_CALLBACK_OPEN = self::ENUM_RULE_CALLBACK_OPEN_VIEW;

	const PRIVATE_KEY = 'portal-priv-key';

	/** @var array $aRules */
	protected $aRules;
	/** @var \Symfony\Component\Routing\RouterInterface */
	private $oRouter;
	/** @var \Combodo\iTop\Portal\Brick\BrickCollection */
	private $oBrickCollection;
	/** @var \Combodo\iTop\Portal\Helper\ScopeValidatorHelper */
	private $oScopeValidator;

	/** @var string $sPrivateKey private key for encoding rules */
	private static $sPrivateKey;

	/**
	 * ContextManipulatorHelper constructor.
	 *
	 * @param \ModuleDesign                                    $oModuleDesign
	 * @param \Symfony\Component\Routing\RouterInterface       $oRouter
	 * @param \Combodo\iTop\Portal\Brick\BrickCollection       $oBrickCollection
	 * @param \Combodo\iTop\Portal\Helper\ScopeValidatorHelper $oScopeValidator
	 *
	 * @throws \DOMFormatException
	 */
	public function __construct(ModuleDesign $oModuleDesign, RouterInterface $oRouter, BrickCollection $oBrickCollection, ScopeValidatorHelper $oScopeValidator) {
		$this->aRules = array();
		$this->oRouter = $oRouter;
		$this->oBrickCollection = $oBrickCollection;

		$this->Init($oModuleDesign->GetNodes('/module_design/action_rules/action_rule'));
		$this->oScopeValidator = $oScopeValidator;
	}

	/**
	 * Initializes the ContextManipulatorHelper by caching action rules in memory.
	 *
	 * @param \DOMNodeList $oNodes
	 *
	 * @throws \Exception
	 * @throws \DOMFormatException
	 */
	public function Init(DOMNodeList $oNodes)
	{
		$this->aRules = array();

		// Iterating over the scope nodes
		/** @var \Combodo\iTop\DesignElement $oRuleNode */
		foreach ($oNodes as $oRuleNode)
		{
			// Retrieving mandatory id attribute
			$sRuleId = $oRuleNode->getAttribute('id');
			if ($sRuleId === '')
			{
				throw new DOMFormatException('Rule tag must have an id attribute.', null, null, $oRuleNode);
			}

			// Setting if the rule needs a source object
			$bNeedsSource = false;
			// Note : preset and retrofit are no longer plurals as it should match as much as possible iTopObjectCopier specs. We use plurals only in the xml for the collection tags
			$aRule = array(
				'source_oql' => null,
				'dest_class' => null,
				'preset' => array(),
				'retrofit' => array(),
				'submit' => null,
				'cancel' => null,
			);

			// Iterating over the rule's nodes
			/** @var \Combodo\iTop\DesignElement $oSubNode */
			foreach ($oRuleNode->GetNodes('*') as $oSubNode)
			{
				$sSubNodeName = $oSubNode->nodeName;
				switch ($sSubNodeName)
				{
					case 'source_class':
						$aRule['source_oql'] = 'SELECT '.$oSubNode->GetText();
						break;

					case 'source_oql':
					case 'dest_class':
						$aRule[$sSubNodeName] = $oSubNode->GetText();
						break;

					case 'presets':
					case 'retrofits':
						/** @var \Combodo\iTop\DesignElement $oActionNode */
						foreach ($oSubNode->GetNodes('*') as $oActionNode)
						{
							// Note : Caution, the index of $aRule is now $oActionNode->nodeName instead of $sSubNodeName, as we want to match iTopObjectCopier specs like told previously
							if (in_array($oActionNode->nodeName, array('preset', 'retrofit')))
							{
								$sActionText = $oActionNode->GetText();
								$aRule[$oActionNode->nodeName][] = $sActionText;

								// Checking if the rule needs a source object
								if (substr($sActionText, 0, 4) === 'copy')
								{
									$bNeedsSource = true;
								}
							}
						}
						break;

					case 'submit':
					case 'cancel':
						// Retrieving callback type and checking that it is allowed
						$sType = $oSubNode->getAttribute('xsi:type');
						if ($sType === '')
						{
							throw new DOMFormatException($sSubNodeName.' must have an xsi:type attribute.', null, null, $oSubNode);
						}
						if (($sType === static::ENUM_RULE_CALLBACK_OPEN) && ($sSubNodeName === 'cancel'))
						{
							throw new DOMFormatException('Cancel tag cannot be of type '.$sType.'.', null, null, $oSubNode);
						}

						$aRule[$sSubNodeName] = array('type' => $sType);
						switch ($sType)
						{
							case static::ENUM_RULE_CALLBACK_BACK:
								// Default value
								$sRefresh = false;
								// Retrieving value
								$oRefreshNode = $oSubNode->GetOptionalElement('refresh');
								if (($oRefreshNode !== null) && ($oRefreshNode->GetText() !== null))
								{
									$sRefresh = $oRefreshNode->GetText();
								}

								$aRule[$sSubNodeName]['refresh'] = $sRefresh;
								break;
							case static::ENUM_RULE_CALLBACK_GOTO:
								// Retrieving value
								$sBrickId = $oSubNode->GetUniqueElement('brick')->GetText();
								if ($sBrickId === null)
								{
									throw new DOMFormatException('Brick tag value must not be empty.', null, null, $oSubNode);
								}

								$aRule[$sSubNodeName]['brick_id'] = $sBrickId;
								break;
							case static::ENUM_RULE_CALLBACK_OPEN:
								// Default value
								$sMode = static::ENUM_RULE_CALLBACK_OPEN_VIEW;
								// Retrieving value
								$oModeNode = $oSubNode->GetOptionalElement('mode');
								if (($oModeNode !== null) && ($oModeNode->GetText() !== null))
								{
									$sMode = $oModeNode->GetText();
								}

								$aRule[$sSubNodeName]['mode'] = $sMode;
								break;
						}
						break;
				}
			}

			// If there is no source information we check if there is a preset that requires a copy in order to throw an exception
			if (($aRule['source_oql'] === null) && ($bNeedsSource === true))
			{
				throw new DOMFormatException('Rule tag must have either a "source_oql" or a "source_class" child node.', null, null, $oRuleNode);
			}

			$this->aRules[$sRuleId] = $aRule;
		}
	}


	/**
	 * Returns a hash array of rules
	 *
	 * @return array
	 */
	public function GetRules()
	{
		return $this->aRules;
	}

	/**
	 * Return the rule identified by its ID, as a hash array
	 *
	 * @param string $sId
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function GetRule($sId)
	{
		if (!array_key_exists($sId, $this->aRules))
		{
			throw new Exception('Context creator : Could not find "'.$sId.'" in the rules list');
		}

		return $this->aRules[$sId];
	}

	/**
	 * @param array $aData contains 2 keys : 'rules' for rules id, 'sources' for context objects (class / id pair).
	 * Example :
	 * <code>
	 * array(
	 *   'rules' => array(
	 *     'rule-id-1',
	 *     'rule-id-2',
	 *     ...
	 *   ),
	 *   'sources' => array(
	 *     <DBObject1 class> => <DBObject1 id>,
	 *     <DBObject2 class> => <DBObject2 id>,
	 *     ...
	 *   )
	 * )
	 * </code>
	 * @param \DBObject $oObject destination object
	 *
	 * @throws \Exception
	 * @throws \CoreException
	 * @throws \OQLException
	 * @throws \CorePortalInvalidActionRuleException N°2555 if at least 1 action_rule cannot be executed
	 */
	public function PrepareObject(array $aData, DBObject $oObject)
	{
		if (isset($aData['rules']) && isset($aData['sources']))
		{
			$aRules = $aData['rules'];
			$aSources = $aData['sources'];
			$aActionRulesErrors = array();
			foreach ($aRules as $sRuleId)
			{
				try
				{
					$this->PrepareAndExecActionRule($sRuleId, $aSources, $oObject);
				}
				catch (CorePortalInvalidActionRuleException $e)
				{
					$aActionRulesErrors[$sRuleId] = $e->getMessage();
				}
			}
			if (!empty($aActionRulesErrors))
			{
				$sDestinationObjectDesc = '';
				$sDestinationObjectDesc .= get_class($oObject);
				$sDestinationObjectDesc .= '['.$oObject->GetKey().']';
				throw new CorePortalInvalidActionRuleException('Some action rules were not executed',
					$aActionRulesErrors,
					'destination object: '.$sDestinationObjectDesc);
			}
		}
	}

	/**
	 * @param string $sRuleId
	 *
	 * @param array $aSources context objects (class / id pairs)
	 * @param \DBObject $oDestinationObject
	 *
	 * @throws \CoreException
	 * @throws \CorePortalInvalidActionRuleException N°2555 thrown if action rules gets more than 1 object
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	private function PrepareAndExecActionRule($sRuleId, $aSources, DBObject $oDestinationObject)
	{
		// Retrieving current rule
		$aRule = $this->GetRule($sRuleId);

		// Retrieving source object if needed
		if ($aRule['source_oql'] !== null)
		{
			// Preparing query to retrieve source object(s)
			/** @var \DBObjectSearch $oSearch */
			$oSearch = DBSearch::FromOQL($aRule['source_oql']);
			if (!$oSearch instanceof DBObjectSearch) {
				$sErrMsg = "Portal query was stopped: action_rule '$sRuleId' source_oql does not allow UNION";
				IssueLog::Error($sErrMsg);
				throw new CorePortalInvalidActionRuleException($sErrMsg);
			}
			$sSearchClass = $oSearch->GetClass();
			$aSearchParams = $oSearch->GetInternalParams();

			if (array_key_exists($sSearchClass, $aSources))
			{
				$sourceId = $aSources[$sSearchClass];

				if (array_key_exists('id', $oSearch->GetQueryParams()))
				{
					if (is_array($sourceId))
					{
						throw new Exception('Context creator : ":id" parameter in rule "'.$sRuleId.'" cannot be an array (This is a limitation of DBSearch)');
					}

					$aSearchParams['id'] = $sourceId;
				}
				else
				{
					if (!is_array($sourceId))
					{
						$sourceId = array($sourceId);
					}

					$iLoopMax = count($sourceId);
					$oFullBinExpr = null;
					for ($i = 0; $i < $iLoopMax; $i++)
					{
						// - Building full search expression
						$oBinExpr = new BinaryExpression(new FieldExpression('id', $oSearch->GetClassAlias()), '=',
							new ScalarExpression($sourceId[$i]));
						if ($i === 0)
						{
							$oFullBinExpr = $oBinExpr;
						}
						else
						{
							$oFullBinExpr = new BinaryExpression($oFullBinExpr, 'OR', $oBinExpr);
						}

						// - Adding it to the query when complete
						if ($i === ($iLoopMax - 1))
						{
							$oSearch->AddConditionExpression($oFullBinExpr);
						}
					}
				}
			}

			$oSearchRootCondition = $oSearch->GetCriteria();
			if (($oSearchRootCondition === null) || ($oSearchRootCondition instanceof TrueExpression))
			{
				// N°2555 : disallow searches without any condition
				$sErrMsg = "Portal query was stopped: action_rule '$sRuleId' searches for '$sSearchClass' without any condition is forbidden";
				IssueLog::Error($sErrMsg);
				throw new CorePortalInvalidActionRuleException($sErrMsg);
			}

			// Checking for silos
			$oScopeSearch = $this->oScopeValidator->GetScopeFilterForProfiles(UserRights::ListProfiles(), $sSearchClass,
				UR_ACTION_READ);
			if ($oScopeSearch->IsAllDataAllowed())
			{
				$oSearch->AllowAllData();
			}

			// Retrieving source object(s) and applying rules
			$oSet = new DBObjectSet($oSearch, array(), $aSearchParams);
			while ($oSourceObject = $oSet->Fetch()) // we need a loop for certain preset verbs like add_to_list, see N°2555
			{
				// Presets
				if (isset($aRule['preset']) && !empty($aRule['preset']))
				{
					$oDestinationObject->ExecActions($aRule['preset'], array('source' => $oSourceObject));
				}
				// Retrofits
				if (isset($aRule['retrofit']) && !empty($aRule['retrofit']))
				{
					$oSourceObject->ExecActions($aRule['retrofit'], array('source' => $oDestinationObject));
				}
			}
		}
		else
		{
			// Presets
			if (isset($aRule['preset']) && !empty($aRule['preset']))
			{
				$oDestinationObject->ExecActions($aRule['preset'], array('source' => $oDestinationObject));
			}
		}
	}

	/**
	 * Returns a hash array of urls for each type of callback
	 *
	 * eg :
	 * array(
	 *     'submit' => 'http://localhost/',
	 *     'cancel' => null
	 * );
	 *
	 * @since 2.3.0
	 * @deprecated 2.7.0 N°1192 Use navigation rules for form callbacks
	 *
	 * @param array     $aData
	 * @param \DBObject $oObject
	 * @param boolean   $bModal
	 *
	 * @return array
	 *
	 * @throws \Exception
	 */
	public function GetCallbackUrls(array $aData, DBObject $oObject, $bModal = false)
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('Use navigation rules for form callbacks');
		$aResults = array(
			'submit' => null,
			'cancel' => null,
		);

		if (isset($aData['rules'])) {
			foreach ($aData['rules'] as $sId) {
				// Retrieving current rule
				$aRule = $this->GetRule($sId);

				// For each type of callbacks, we check if there is a rule to apply
				foreach (array('submit', 'cancel') as $sCallbackName)
				{
					if (is_array($aRule[$sCallbackName]))
					{
						// Previously declared rule on a callback is overwritten by the last
						$sCallbackUrl = null;
						switch ($aRule[$sCallbackName]['type'])
						{
							case static::ENUM_RULE_CALLBACK_BACK:
								if (!$bModal)
								{
									$sCallbackUrl = ($_SERVER['HTTP_REFERER'] !== '') ? $_SERVER['HTTP_REFERER'] : null;
								}
								break;

							case static::ENUM_RULE_CALLBACK_GOTO:
								$oBrick = $this->oBrickCollection->GetBrickById($aRule[$sCallbackName]['brick_id']);
								$sCallbackUrl = $this->oRouter->generate($oBrick->GetRouteName(), array('sBrickId' => $oBrick->GetId()));
								break;

							case static::ENUM_RULE_CALLBACK_OPEN:
								$sCallbackUrl = ($oObject->IsNew()) ? null : $this->oRouter->generate('p_object_'.$aRule[$sCallbackName]['mode'],
									array('sObjectClass' => get_class($oObject), 'sObjectId' => $oObject->GetKey()));
								break;
						}

						$aResults[$sCallbackName] = $sCallbackUrl;
					}
				}
			}
		}

		return $aResults;
	}

	/**
	 * Prepares the rules as an array of rules and source objects so it can be tokenized
	 *
	 * @param array $aRules
	 * @param array $aObjects
	 *
	 * @return array
	 */
	public static function PrepareRulesForToken($aRules, $aObjects = array())
	{
		// Getting necessary information from objects
		$aSources = array();
		foreach ($aObjects as $oObject)
		{
			$aSources[get_class($oObject)] = $oObject->GetKey();
		}

		// Preparing data
		$aTokenRules = array(
			'rules' => $aRules,
			'sources' => $aSources,
		);

		return $aTokenRules;
	}

	/**
	 * Encodes a token made out of the rules.
	 *
	 * Token = base64_encode( json_encode( array( 'rules' => array(), 'sources' => array() ) ) )
	 *
	 * To retrieve it has
	 *
	 * @param array $aTokenRules
	 *
	 * @return string
	 */
	public static function EncodeRulesToken($aTokenRules)
	{
		$aTokenRules['salt'] = base64_encode(random_bytes(8));

		$sPPrivateKey = self::GetPrivateKey();
		$oCrypt = new SimpleCrypt(MetaModel::GetConfig()->GetEncryptionLibrary());
		return self::base64url_encode($oCrypt->Encrypt($sPPrivateKey, json_encode($aTokenRules)));
	}

	/**
	 * @param array $aRules
	 * @param array $aObjects
	 *
	 * @return string
	 */
	public static function PrepareAndEncodeRulesToken($aRules, $aObjects = array())
	{
		// Preparing rules before making a token
		$aTokenRules = static::PrepareRulesForToken($aRules, $aObjects);

		// Returning tokenized data
		return static::EncodeRulesToken($aTokenRules);
	}

	/**
	 * Decodes a token made out of the rules
	 *
	 * @param string $sToken
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public static function DecodeRulesToken($sToken)
	{
		$sPrivateKey = self::GetPrivateKey();
		$oCrypt = new SimpleCrypt(MetaModel::GetConfig()->GetEncryptionLibrary());
		$sDecryptedToken = $oCrypt->Decrypt($sPrivateKey, self::base64url_decode($sToken));

		$aTokenRules = json_decode($sDecryptedToken, true);
		if (!is_array($aTokenRules))
		{
			throw new Exception('DecodeRulesToken not a proper json structure.');
		}

		return $aTokenRules;
	}

	private static function base64url_encode($sData) {
		return rtrim(strtr(base64_encode($sData), '+/', '-_'), '=');
	}

	private static function base64url_decode($sData) {
		return base64_decode(str_pad(strtr($sData, '-_', '+/'), strlen($sData) % 4, '=', STR_PAD_RIGHT));
	}

	/**
	 * @return string
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	private static function GetPrivateKey()
	{
		if(self::$sPrivateKey === null) {
			self::$sPrivateKey = DBProperty::GetProperty(self::PRIVATE_KEY);
			if (is_null(self::$sPrivateKey)) {
				self::$sPrivateKey = bin2hex(random_bytes(32));
				DBProperty::SetProperty(self::PRIVATE_KEY, self::$sPrivateKey);
			}
		}
		return self::$sPrivateKey;
	}
}
