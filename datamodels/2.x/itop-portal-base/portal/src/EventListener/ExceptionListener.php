<?php

/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */


namespace Combodo\iTop\Portal\EventListener;


use Dict;
use ExceptionLog;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Twig\Environment;

/**
 * Class ExceptionListener
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Portal\EventListener
 * @since 2.7.0
 */
class ExceptionListener
{

	/**
	 * Constructor.
	 *
	 * @param \Twig\Environment $oTwig
	 */
	public function __construct(
		protected Environment $oTwig
	)
	{
	}

	/**
	 * @param ExceptionEvent $oEvent
	 *
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function onKernelException(ExceptionEvent $oEvent) : void
	{
		// Get the exception object from the received event
		$oException = $oEvent->getThrowable();

		// Prepare / format exception data
		if ($oException instanceof \MySQLException) {
			// Those exceptions should be caught before (in the metamodel startup, before event starting Symfony !)
			// They could contain far too much info :/
			// So as an extra precaution we are filtering here anyway !
			$sErrorMessage = 'DB Server error, check error log !';
		} else {
			$sErrorMessage = $oException->getMessage();
		}
		// - For none HTTP exception, status code will be a generic 500
		$iStatusCode = ($oException instanceof HttpExceptionInterface) ? $oException->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
		switch ($iStatusCode) {
			case 404:
				$sErrorTitle = Dict::S('Error:HTTP:404');
				break;
			default:
				$sErrorTitle = Dict::S('Error:HTTP:500');
				break;
		}

		// Prepare flatten exception
		$oFlattenException = ($_SERVER['APP_DEBUG'] == 1) ? FlattenException::createFromThrowable($oException) : null;
		// Remove APPROOT from file paths if in production (SF context)
		if (!is_null($oFlattenException) && ($_SERVER['APP_ENV'] === 'prod'))
		{
			$oFlattenException->setFile($this->removeAppRootFromPath($oFlattenException->getFile()));

			$aTrace = $oFlattenException->getTrace();
			foreach ($aTrace as $iIdx => $aEntry)
			{
				$aTrace[$iIdx]['file'] = $this->removeAppRootFromPath($aEntry['file']);
			}
			$oFlattenException->setTrace($aTrace, $oFlattenException->getFile(), $oFlattenException->getLine());
		}

		// Log exception in iTop log
		ExceptionLog::LogException($oException, [
			'uri' => $oEvent->getRequest()->getUri(),
		]);

		// Prepare data for template
		$aData = array(
			'exception'     => $oFlattenException,
			'code'          => $iStatusCode,
			'error_title'   => $sErrorTitle,
			'error_message' => $sErrorMessage,
		);

		// Generate the response
		if ($oEvent->getRequest()->isXmlHttpRequest())
		{
			$oResponse = new JsonResponse($aData);
		}
		else
		{
			$oResponse = new Response();
			$oResponse->setContent($this->oTwig->render('itop-portal-base/portal/templates/errors/layout.html.twig', $aData));
		}
		$oResponse->setStatusCode($iStatusCode);

		// HttpExceptionInterface is a special type of exception that holds status code and header details
		if ($oException instanceof HttpExceptionInterface) {
			$oResponse->headers->replace($oException->getHeaders());
		}

		// display original error page when app debug is on
		if (($_SERVER['APP_DEBUG'] == 1)) {
			return;
		}

		// Send the modified response object to the event
		$oEvent->setResponse($oResponse);
	}

	/**
	 * Normalize a path by replacing '\' with '/'
	 *
	 * @param string $sInputPath
	 *
	 * @return string|string[]
	 */
	protected function normalizePath($sInputPath)
	{
		return str_replace('\\', '/', $sInputPath);
	}

	/**
	 * Remove iTop's APPROOT path from the $sInputPath. Used to avoid "full path disclosure" vulnerabilities.
	 *
	 * @param string $sInputPath
	 *
	 * @return string
	 */
	protected function removeAppRootFromPath($sInputPath)
	{
		$sNormalizedAppRoot = $this->normalizePath(APPROOT);
		$sNormalizedInputPath = $this->normalizePath($sInputPath);
		return str_replace($sNormalizedAppRoot, '', $sNormalizedInputPath);
	}


}