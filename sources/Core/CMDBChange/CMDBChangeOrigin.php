<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\CMDBChange;


/**
 * Class CMDBChangeOrigin
 *
 * The various origin of a CMDBChange
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Core\CMDBChange
 * @since 3.0.0
 */
class CMDBChangeOrigin
{
	/** @var string Made through the GUI by the author */
	public const INTERACTIVE = 'interactive';
	/** @var string Made through the csv-import.php script */
	public const CSV_IMPORT = 'csv-import.php';
	/** @var string Made through the GUI of the CSV import */
	public const CSV_INTERACTIVE = 'csv-interactive';
	/** @var string Made through email processing */
	public const EMAIL_PROCESSING = 'email-processing';
	/** @var string Made through synchro data source */
	public const SYNCHRO_DATA_SOURCE = 'synchro-data-source';
	/** @var string Made through the REST/JSON webservices */
	public const WEBSERVICE_REST = 'webservice-rest';
	/** @var string Made through the SAOP webservices */
	public const WEBSERVICE_SOAP = 'webservice-soap';
	/** @var string Made through an extension */
	public const CUSTOM_EXTENSION = 'custom-extension';
}