<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

// UI elements
Dict::Add('EN US', 'English', 'English', [
	'UI:WelcomePopup:Button:RemindLater' => 'Remind me later',
	'UI:WelcomePopup:Button:Acknowledge' => 'Got it',
]);

// Message
Dict::Add('EN US', 'English', 'English', [
	'UI:WelcomePopup:Message:320_01_Welcome:Title' => 'Welcome to ' . ITOP_APPLICATION_SHORT . ' 3.2',
	'UI:WelcomePopup:Message:320_01_Welcome:Description' => '<div>Congratulations, you landed on '.ITOP_APPLICATION.' '.ITOP_VERSION_NAME.'!</div>
<br>
<div>We\'re excited to announce long-term support for this new release. </div>
<div>In addition to introducing new features like Newsroom, we\'ve also made significant improvements in terms of compatibility with popular frameworks and databases, ensuring that your '.ITOP_APPLICATION_SHORT.' instance remains stable and secure over the long term.</div>
<div> Plus, our team has worked tirelessly to include critical security patches and enhance accessibility, so you can be confident that your data is always protected and accessible to all users. </div>
<div>Discover all of '.ITOP_APPLICATION_SHORT.'\'s exciting new features and stay up-to-date with important notifications with our new welcome pop-up!</div>
<div>We hope you\'ll enjoy this version as much as we enjoyed imagining and creating it.</div>
<br>
<div>Customize your '.ITOP_APPLICATION_SHORT.' preferences for a personalized experience.</div>',
	'UI:WelcomePopup:Message:320_02_Newsroom:Title' => 'Say "Hello" to the newsroom 🔔',
	'UI:WelcomePopup:Message:320_02_Newsroom:Description' => '<div>Say goodbye to cluttered inboxes and hello to personalized alerts with <b>'.ITOP_APPLICATION_SHORT.'\'s Newsroom</b>!</div>
<div>Newsroom allows you to easily manage notifications within the platform, so you can stay on top of important updates without constantly checking your email. With the ability to mark messages as read or unread, and automatically delete old notifications, you have complete control over your notifications. </div>
<div>Try it out today and see how much more efficient your workflow can be!</div>',
	'UI:WelcomePopup:Message:320_03_NotificationsCenter:Title' => 'Notifications center',
	'UI:WelcomePopup:Message:320_03_NotificationsCenter:Description' => '<div> Thanks to '.ITOP_APPLICATION_SHORT.' <b>notifications center</b> you can easily choose how you receive your notifications - via email, chat, or even our Newsroom feature.</div>
<div>You don\'t want to receive a certain type of alert? Nothing easier with the customizable notification giving you the flexibility to tailor your experience to your needs. </div>',
	'UI:WelcomePopup:Message:320_05_A11yThemes:Title' => ITOP_APPLICATION_SHORT . ' UI is accessible',
	'UI:WelcomePopup:Message:320_05_A11yThemes:Description' => '<div>In addition to '.ITOP_APPLICATION_SHORT.' 3.0 high-contrast theme in '.ITOP_APPLICATION_SHORT.' portal and WCAG compliant architecture updates, '.ITOP_APPLICATION_SHORT.' is making a new step forward to ensure the accessibility of the solution, by adding new back-office themes.
<ul>
	<li><b>Color blind theme</b></li>
	<li><b>High-contrast theme</b></li>
</ul>
</div>',
	'UI:WelcomePopup:Message:320_04_PowerfulNotifications_AdminOnly:Title' => 'Powerful notifications',
	'UI:WelcomePopup:Message:320_04_PowerfulNotifications_AdminOnly:Description' => '<div>'.ITOP_APPLICATION_SHORT.'\'s Newsroom gives you a new way to <b>automate</b> your news notifications based on events with recurrence, so you can easily set up rules that work for you. </div>
<div>Our <b>priority-based scheduling</b> ensures that important messages are sent first, while our URL customization options allow you to direct recipients to the right place. </div>
<div>And with support for <b>multiple languages and asynchronous actions</b>, you have complete control over how all kinds of notifications are sent and received. </div>
<div>Configure it today and see how much more efficient your team can be!</div>',
]);
