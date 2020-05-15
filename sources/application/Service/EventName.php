<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Service;


class EventName
{
	// Core
	//
	// OrmDocument
	const DOWNLOAD_DOCUMENT = 'DownloadDocument';

	// DBObject
	const DB_OBJECT_LOADED = 'DBObjectLoaded';
	const DB_OBJECT_RELOAD = 'DBObjectReload';
	const DB_OBJECT_NEW = 'DBObjectNew';
	const BEFORE_INSERT = 'BeforeInsert';
	const DB_OBJECT_KEY_READY = 'DBObjectKeyReady';
	const AFTER_INSERT = 'AfterInsert';
	const BEFORE_UPDATE = 'BeforeUpdate';
	const AFTER_UPDATE = 'AfterUpdate';
	const BEFORE_DELETE = 'BeforeDelete';
	const AFTER_DELETE = 'AfterDelete';
	const BEFORE_APPLY_STIMULUS = 'BeforeApplyStimulus';
	const AFTER_APPLY_STIMULUS = 'AfterApplyStimulus';
	const ON_CHECK_TO_WRITE = 'OnCheckToWrite';
	const ON_CHECK_TO_DELETE = 'OnCheckToDelete';
	const OBJECT_DETAILS = 'ObjectDetails';

	// Application
	//
	const LOGIN = 'OnLogin';
}
