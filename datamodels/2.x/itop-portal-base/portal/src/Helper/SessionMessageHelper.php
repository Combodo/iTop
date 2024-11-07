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

namespace Combodo\iTop\Portal\Helper;

use ArrayIterator;
use Combodo\iTop\Application\Helper\Session;
use IteratorAggregate;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Traversable;
use utils;

/**
 * Class SessionMessageHelper
 *
 * @package Combodo\iTop\Portal\EventListener
 * @since 2.7.0
 */
class SessionMessageHelper implements IteratorAggregate
{
	/** @var string ENUM_SEVERITY_INFO */
	const ENUM_SEVERITY_INFO = 'info';
	/** @var string ENUM_SEVERITY_OK */
	const ENUM_SEVERITY_OK = 'ok';
	/** @var string ENUM_SEVERITY_WARNING */
	const ENUM_SEVERITY_WARNING = 'warning';
	/** @var string ENUM_SEVERITY_ERROR */
	const ENUM_SEVERITY_ERROR = 'error';

	/** @var string DEFAULT_SEVERITY */
	const DEFAULT_SEVERITY = self::ENUM_SEVERITY_INFO;

	/** @var \Symfony\Component\DependencyInjection\ContainerInterface $oContainer */
	private $oContainer;
	/** @var array $aAllMessages */
	private $aAllMessages = array();

	/**
	 * SessionMessageHelper constructor.
	 *
	 * @param \Symfony\Component\DependencyInjection\ContainerInterface $oContainer
	 */
	public function __construct(ContainerInterface $oContainer)
	{
		$this->oContainer = $oContainer;
	}

	/**
	 * Add a message to the session messages to be fetch later.
	 * Will be for this portal only.
	 *
	 * @param string $sId
	 * @param string $sContent
	 * @param string $sSeverity
	 * @param array $aMetadata An array of key => scalar value
	 * @param int $iRank
	 */
	public function AddMessage($sId, $sContent, $sSeverity = self::DEFAULT_SEVERITY, $aMetadata = array(), $iRank = 1)
	{
		$sKey = $this->GetMessagesKey();
		if(!Session::IsSet(['obj_messages', $sKey]))
		{
			Session::Set(['obj_messages', $sKey], []);
		}

		Session::Set(['obj_messages', $sKey, $sId], [
			'severity' => $sSeverity,
			'rank' => $iRank,
			'message' => $sContent,
			'metadata' => $aMetadata,
		]);
	}

	/**
	 * Remove the message identified by $sId (for this portal) from the session messages
	 *
	 * @param string $sId
	 */
	public function RemoveMessage($sId)
	{
		$sKey = $this->GetMessagesKey();
		Session::Unset(['obj_messages', $sKey, $sId]);
	}

	/**
	 * @return \ArrayIterator|\Traversable (\Traversable is the return type from the interface, \ArrayIterator is what we actually return)
	 */
	public function getIterator(): Traversable
	{
		$this->FetchMessages();

		return new ArrayIterator($this->aAllMessages);
	}

	/**
	 * Return the key under which the session messages of the portal are stored ("GUI:<PORTAL_ID>")
	 *
	 * @return string
	 */
	private function GetMessagesKey()
	{
		return 'GUI:' . $this->oContainer->getParameter('combodo.portal.instance.id');
	}

	/**
	 * Fetch session messages and delete them afterwards
	 * Note: We keep this system instead of following the Symfony system to make it simpler for extension developers to use them across the admin. console and the portal.
	 *
	 * @return void
	 */
	private function FetchMessages()
	{
		if (!empty($this->aAllMessages))
		{
			return;
		}

		$this->aAllMessages = array();
		if (is_array(Session::Get('obj_messages')))
		{
			foreach (Session::Get('obj_messages') as $sMessageKey => $aMessageObjectData)
			{
				$aObjectMessages = array();
				$aRanks = array();
				foreach ($aMessageObjectData as $sMessageId => $aMessageData)
				{
					$sMsgClass = 'alert alert-dismissible alert-';
					switch ($aMessageData['severity'])
					{
						case static::ENUM_SEVERITY_INFO:
							$sMsgClass .= 'info';
							break;

						case static::ENUM_SEVERITY_WARNING:
							$sMsgClass .= 'warning';
							break;

						case static::ENUM_SEVERITY_ERROR:
							$sMsgClass .= 'danger';
							break;

						case static::ENUM_SEVERITY_OK:
						default:
							$sMsgClass .= 'success';
							break;
					}

					$sMsgMetadata = '';
					// Protection for missing metadata entry when session messages are not created from the portal
					if (isset($aMessageData['metadata'])) {
						foreach ($aMessageData['metadata'] as $sMetadatumName => $sMetadatumValue) {
							$sMsgMetadata .= 'data-'.str_replace('_', '-', $sMetadatumName).'="'.utils::HtmlEntities($sMetadatumValue).'" ';
						}
					}
					$aObjectMessages[] = array('css_classes' => $sMsgClass, 'message' => $aMessageData['message'], 'metadata' => $sMsgMetadata);
					$aRanks[] = $aMessageData['rank'];
				}
				Session::Unset(['obj_messages', $sMessageKey]);

				array_multisort($aRanks, $aObjectMessages);
				foreach ($aObjectMessages as $aObjectMessage)
				{
					$this->aAllMessages[] = $aObjectMessage;
				}
			}
		}
	}
}