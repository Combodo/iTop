<?php

namespace Combodo\iTop\Service;

use DBObject;
use Exception;
use IssueLog;
use ReflectionClass;
use ReflectionException;
use SecurityException;

/**
 * A service that will perform checks on the signature of a method.
 * Usage:
 * $callbackService = new CallbackService('ClassName::methodName');
 * $callbackService->CheckCallbackSignature(get_class($oTriggeringObject), [DBObject::class, 'string', 'int']);
 * $callbackService->Invoke($oTriggeringObject, ['stringValue', 42]);
 *
 */
class CallbackService
{
	private string $sCallBackClassName;
	private string $sCallBackMethodName;
	private bool $bIsStatic;

	/**
	 * @throws Exception
	 */
	public function __construct(string $sCallBackDefinition)
	{
		if (stripos($sCallBackDefinition, '$this->') !== false) {
			$this->sCallBackMethodName = str_ireplace('$this->', '', $sCallBackDefinition);
			$this->bIsStatic = false;
			$this->sCallBackClassName = ''; // we don't need it for non-static methods
		} else {
			if (!is_callable($sCallBackDefinition)) {
				$sMessageError = "The callback '$sCallBackDefinition' is not valid.";
				IssueLog::Error($sMessageError, 'console');
				throw new Exception($sMessageError);
			}
			$iPos = strrpos($sCallBackDefinition, '::');
			if ($iPos !== false && $iPos > 0) {
				$this->sCallBackClassName = substr($sCallBackDefinition, 0, $iPos);
				$this->sCallBackMethodName = substr($sCallBackDefinition, $iPos + 2);
				$this->bIsStatic = true;
			} else {
				$sMessageError = "The callback '$sCallBackDefinition' is not a valid static method.";
				IssueLog::Error($sMessageError, 'console');
				throw new Exception($sMessageError);
			}
		}
	}

	public function IsStatic(): bool
	{
		return $this->bIsStatic;
	}

	/**
	 * @throws ReflectionException
	 * @throws SecurityException
	 */
	public function CheckCallbackSignature(string $sTriggeringObjectClass, array $aParamsType): void
	{
		$iParamCount = 0;
		if ($this->bIsStatic) { // If the callback is static, we expect the first parameter to be the triggering object type
			array_unshift($aParamsType, DBObject::class);
		} else {
			$this->sCallBackClassName = $sTriggeringObjectClass;
		}
		$oReflector = new ReflectionClass($this->sCallBackClassName);
		$aCallbackParameters = $oReflector->getMethod($this->sCallBackMethodName)->getParameters();
		$iRequiredNumberOfParams = count($aParamsType);
		if (count($aCallbackParameters) !== $iRequiredNumberOfParams) {
			$sErrorMessage = "The callback method '$this->sCallBackMethodName' of class '$this->sCallBackClassName' must have exactly $iRequiredNumberOfParams parameters.";
			IssueLog::Error($sErrorMessage);
			throw new SecurityException($sErrorMessage);
		}
		foreach ($aParamsType as $iParamOrder => $sParamType) {
			$oParam = $aCallbackParameters[$iParamOrder];
			if ($oParam->getType() === null) {
				$sErrorMessage = "The callback method '$this->sCallBackMethodName' of class '$this->sCallBackClassName' must have type-hinted parameters, but parameter {$oParam->getName()} is not type-hinted.";
				IssueLog::Error($sErrorMessage);
				throw new SecurityException($sErrorMessage);
			}
			if ($oParam->getType()->getName() !== $sParamType) {
				$sErrorMessage = "The callback method '$this->sCallBackMethodName' of class '$this->sCallBackClassName' must have a parameter of type '$sParamType', but it has {$oParam->getType()->getName()} instead.";
				IssueLog::Error($sErrorMessage);
				throw new SecurityException($sErrorMessage);
			}
		}
	}

	/**
	 * @param $oTriggeringObject
	 * @param array $aParams
	 *
	 * @return mixed
	 */
	public function Invoke($oTriggeringObject, array $aParams): mixed
	{
		if ($this->bIsStatic) {
			return call_user_func_array([$this->sCallBackClassName, $this->sCallBackMethodName], array_merge([$oTriggeringObject], $aParams));

		} else {
			return call_user_func_array([$oTriggeringObject, $this->sCallBackMethodName], $aParams);
		}
	}
}