<?php
/**
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\FilesInformation\Service;

use Exception;

class FilesInformationException extends Exception
{

}

class FileNotExistException extends FilesInformationException
{

}

class FileIntegrityException extends FilesInformationException
{

}
