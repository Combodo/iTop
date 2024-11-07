<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

// UI elements
Dict::Add('IT IT', 'Italian', 'Italiano', [
	'UI:WelcomePopup:Button:RemindLater' => 'Remind me later~~',
	'UI:WelcomePopup:Button:AcknowledgeAndNext' => 'Next~~',
	'UI:WelcomePopup:Button:AcknowledgeAndClose' => 'Close~~',
]);

// Message
Dict::Add('IT IT', 'Italian', 'Italiano', [
	'UI:WelcomePopup:Message:320_01_Welcome:Title' => 'Welcome to ' . ITOP_APPLICATION_SHORT . ' 3.2~~',
	'UI:WelcomePopup:Message:320_01_Welcome:Description' => '<div>Congratulations, you landed on '.ITOP_APPLICATION.' '.ITOP_VERSION_NAME.'!</div>
<br>
<div>We\'re excited to announce this new release. </div>
<div>In addition to introducing new features such as Newsroom, ' . ITOP_APPLICATION_SHORT . ' 3.2 includes critical security patches, enhanced accessibility and other significant improvements focused on providing you with stability and security.</div>
<br>
<div>Discover all of '.ITOP_APPLICATION_SHORT.'\'s exciting new features and stay up-to-date with important notifications with our new welcome pop-up!</div>
<div>We hope you\'ll enjoy this version as much as we enjoyed imagining and creating it.</div>
<br>
<div>Customize your '.ITOP_APPLICATION_SHORT.' preferences for a personalized experience.</div>~~',
	'UI:WelcomePopup:Message:320_02_Newsroom:Title' => 'Say "Hello" to the newsroom~~',
	'UI:WelcomePopup:Message:320_02_Newsroom:Description' => '<div>Say goodbye to cluttered inboxes and hello to personalized alerts with <a href="%1$s" target="_blank">'.ITOP_APPLICATION_SHORT.'\'s Newsroom</a>!</div>
<div>Newsroom allows you to easily manage notifications within the platform, so you can stay on top of important updates without constantly checking your email. With the ability to mark messages as read or unread, and automatically delete old notifications, you have complete control over your notifications. </div>
<br>
<div>Try it out today and streamline your ' . ITOP_APPLICATION_SHORT . '\'s communication experience!</div>~~',
	'UI:WelcomePopup:Message:320_03_NotificationsCenter:Title' => 'Notifications center~~',
	'UI:WelcomePopup:Message:320_03_NotificationsCenter:Description' => '<div>As we know your information intake is already at its max, you can now easily choose how you receive your notifications - via email, chat, or even the Newsroom feature</div>
<div>You don\'t want to receive a certain type of alerts? Nothing easier with these advanced customization capabilities giving you the flexibility to tailor your experience to your needs. </div>
<br>
<div>Access your <a href="%1$s" target="_blank">notifications center</a> through the newsroom or through your preferences and avoid information overload on all your communication channels!</div>~~',
	'UI:WelcomePopup:Message:320_05_A11yThemes:Title' => 'Accessibility for ' . ITOP_APPLICATION_SHORT . '\'s UI~~',
	'UI:WelcomePopup:Message:320_05_A11yThemes:Description' => '<div>To ensure ' . ITOP_APPLICATION_SHORT . '\'s accessibility, our team has been working on <a href="%1$s" target="_blank">new back-office themes</a>. WCAG compliants, those UI focus on making it easier for users with visual impairments to use the solution:
<ul>
	<li><b>Color-blind theme:</b> Designed to help users with colorblindness, this theme actually breaks down in two sub-themes to adapt to specific cases: </li>
		<ul>
			<li>One adapted to protanopia and deuteranopia</li>	
			<li>And another one for tritanopia</li>	
		</ul>
		<br>
	<li><b>High-contrast theme:</b> Increased contrast to allow users an easier distinction between different elements on screen and avoid to rely on color schema to convey information. It can be helpful for users with different pathology from colorblindness to low vision issues.</li>
</ul>
</div>~~',
	'UI:WelcomePopup:Message:320_04_PowerfulNotifications_AdminOnly:Title' => 'Powerful notifications~~',
	'UI:WelcomePopup:Message:320_04_PowerfulNotifications_AdminOnly:Description' => '<div>'.ITOP_APPLICATION_SHORT.'\'s Newsroom gives you a new way to <a href="%1$s" target="_blank"><b>automate</b> your alerts based on events</a> with recurrence, so you can easily set up rules that work for you. </div>
<div>Our <b>priority-based notifications sorting</b> ensures that important messages are displayed first, while our URL customization options allow you to direct recipients to the right place. </div>
<br>
<div>With support for <b>multiple languages</b>, you have now complete control over your notifications display.</div>
<br>
<div>Configure it today and see how much more efficient your alerts process can be!</div>~~',
]);
