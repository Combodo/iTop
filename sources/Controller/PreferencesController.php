<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller;

use appUserPreferences;
use CoreUnexpectedValue;
use Exception;
use MetaModel;
use ormDocument;
use UserRights;
use utils;

/**
 * Class PreferencesController
 *
 * @internal
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 3.0.0
 * @package Combodo\iTop\Controller
 */
class PreferencesController extends AbstractController
{
	/**
	 * @return string[]
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	public function SetUserPicture(): array
	{
		$sImageFilename = utils::ReadPostedParam('image_filename', null, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);

		// Set preference for the user
		appUserPreferences::SetPref('user_picture_placeholder', $sImageFilename);

		$sUserPicturesFolder = 'images/user-pictures/';
		$sImageAbsPath = utils::RealPath(APPROOT.$sUserPicturesFolder.$sImageFilename, APPROOT.$sUserPicturesFolder);
		$sImageAbsUrl = utils::GetAbsoluteUrlAppRoot().$sUserPicturesFolder.$sImageFilename;
		
		// Check if we're still in the right folder
		if($sImageAbsPath === false){
			throw new CoreUnexpectedValue('Error while updating user image, invalid image path "'.$sUserPicturesFolder.$sImageFilename.'"');
		}
		
		// Check file can be read
		$sImageData = file_get_contents($sImageAbsPath);
		if (false === $sImageData) {
			throw new Exception('Error while updating user image, could not open file "'.$sImageAbsPath.'"');
		}

		// Check if user has a contact with an image attribute, so we put the image in it also
		$sPersonClass = 'Person';
		if (true === MetaModel::HasImageAttributeCode($sPersonClass)) {
			$oCurContact = UserRights::GetContactObject();
			if (null !== $oCurContact) {
				// Update contact
				$sImageMimeType = mime_content_type($sImageAbsPath);
				$oOrmImage = new ormDocument($sImageData, $sImageMimeType, $sImageFilename);

				$sImageAttCode = MetaModel::GetImageAttributeCode($sPersonClass);
				$oCurContact->Set($sImageAttCode, $oOrmImage);
				$oCurContact->DBUpdate();
			}
		}

		return ['image_url' => $sImageAbsUrl];
	}
}
