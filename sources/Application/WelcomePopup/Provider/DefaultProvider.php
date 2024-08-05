<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\WelcomePopup\Provider;

use Combodo\iTop\Controller\Newsroom\iTopNewsroomController;
use Combodo\iTop\Controller\Notifications\NotificationsCenterController;
use Combodo\iTop\Service\Router\Router;
use Dict;
use AbstractWelcomePopupExtension;
use UserRights;
use utils;
use Combodo\iTop\Application\WelcomePopup\MessageFactory;

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
		// Messages for everyone
		$aMessages = [
			MessageFactory::MakeForLeftTextsRightIllustrationAsSVGMarkup(
				"320_01_Welcome",
				Dict::S("UI:WelcomePopup:Message:320_01_Welcome:Title"),
				Dict::S("UI:WelcomePopup:Message:320_01_Welcome:Description"),
				utils::GetAbsoluteUrlAppRoot() . "images/illustrations/undraw_relaunch_day.svg"
			),
			MessageFactory::MakeForLeftIllustrationAsSVGMarkupRightTexts(
				"320_02_Newsroom",
				Dict::S("UI:WelcomePopup:Message:320_02_Newsroom:Title"),
				Dict::Format("UI:WelcomePopup:Message:320_02_Newsroom:Description", Router::GetInstance()->GenerateUrl(iTopNewsroomController::ROUTE_NAMESPACE . ".view_all")),
				utils::GetAbsoluteUrlAppRoot() . "images/illustrations/undraw_newspaper.svg"
			),
			MessageFactory::MakeForLeftIllustrationAsSVGMarkupRightTexts(
				"320_03_NotificationsCenter",
				Dict::S("UI:WelcomePopup:Message:320_03_NotificationsCenter:Title"),
				Dict::Format("UI:WelcomePopup:Message:320_03_NotificationsCenter:Description", Router::GetInstance()->GenerateUrl(NotificationsCenterController::ROUTE_NAMESPACE . ".display_page")),
				utils::GetAbsoluteUrlAppRoot() . "images/illustrations/undraw_preferences_popup.svg"
			),
			MessageFactory::MakeForLeftTextsRightIllustrationAsSVGMarkup(
				"320_05_A11yThemes",
				Dict::S("UI:WelcomePopup:Message:320_05_A11yThemes:Title"),
				Dict::Format("UI:WelcomePopup:Message:320_05_A11yThemes:Description", utils::GetAbsoluteUrlAppRoot() . "pages/preferences.php"),
				utils::GetAbsoluteUrlAppRoot() . "images/illustrations/undraw_designer_mindset.svg"
			),
		];

		// For users that can configure notifications
		if (UserRights::IsActionAllowed(\Trigger::class, \UR_ACTION_MODIFY))
		{
			$aMessages[] = MessageFactory::MakeForLeftTextsRightIllustrationAsSVGMarkup(
				"320_04_PowerfulNotifications_AdminOnly",
				Dict::S("UI:WelcomePopup:Message:320_04_PowerfulNotifications_AdminOnly:Title"),
				Dict::Format("UI:WelcomePopup:Message:320_04_PowerfulNotifications_AdminOnly:Description", utils::GetAbsoluteUrlAppRoot() . "pages/notifications.php"),
				utils::GetAbsoluteUrlAppRoot() . "images/illustrations/undraw_new_notifications.svg"
			);
		}

		return $aMessages;
	}
}
