<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\WelcomePopup\Provider;

use AbstractWelcomePopupExtension;
use Combodo\iTop\Application\WelcomePopup\MessageFactory;
use Dict;
use UserRights;
use utils;

/**
 * Implementation of the "default" Welcome Popup message
 * @since 3.2.0
 */
class DefaultProvider extends AbstractWelcomePopupExtension
{
	/**
	 * @inheritDoc
	 */
	public function GetMessages(): array
	{
		$aMessages = [];

		// Messages for Administrators, Super Users and Configuration Managers only
		if (UserRights::IsAdministrator()
			|| UserRights::HasProfile('SuperUser')
			|| UserRights::HasProfile('Configuration Manager')
		) {
			$aMessages[] = MessageFactory::MakeForLeftTextsRightIllustrationAsSVGMarkup(
				"330_01_Welcome",
				Dict::S("UI:WelcomePopup:Message:330_01_Welcome:Title"),
				Dict::S("UI:WelcomePopup:Message:330_01_Welcome:Description"),
				utils::GetAbsoluteUrlAppRoot()."images/illustrations/undraw_to_the_moon.svg"
			);

			$aMessages[] = MessageFactory::MakeForLeftIllustrationAsSVGMarkupRightTexts(
				"330_03_CMDB",
				Dict::S("UI:WelcomePopup:Message:330_03_CMDB:Title"),
				Dict::S("UI:WelcomePopup:Message:330_03_CMDB:Description"),
				utils::GetAbsoluteUrlAppRoot()."images/illustrations/undraw_maintenance.svg"
			);
		}

		// Messages for Administrators and Super Users only
		if (UserRights::IsAdministrator()
			|| UserRights::HasProfile('SuperUser')
		) {
			$aMessages[] = MessageFactory::MakeForLeftIllustrationAsSVGMarkupRightTexts(
				"330_02_AIFoundations",
				Dict::S("UI:WelcomePopup:Message:330_02_AIFoundations:Title"),
				Dict::S("UI:WelcomePopup:Message:330_02_AIFoundations:Description"),
				utils::GetAbsoluteUrlAppRoot()."images/illustrations/undraw_building_blocks.svg"
			);

			$aMessages[] = MessageFactory::MakeForLeftTextsRightIllustrationAsSVGMarkup(
				"330_04_MFA",
				Dict::S("UI:WelcomePopup:Message:330_04_MFA:Title"),
				Dict::S("UI:WelcomePopup:Message:330_04_MFA:Description"),
				utils::GetAbsoluteUrlAppRoot()."images/illustrations/undraw_two_factor_authentication.svg"
			);

			$aMessages[] = MessageFactory::MakeForLeftTextsRightIllustrationAsSVGMarkup(
				"330_05_Portal",
				Dict::S("UI:WelcomePopup:Message:330_05_Portal:Title"),
				Dict::S("UI:WelcomePopup:Message:330_05_Portal:Description"),
				utils::GetAbsoluteUrlAppRoot()."images/illustrations/undraw_prototyping_process.svg"
			);
		}

		return $aMessages;
	}
}
