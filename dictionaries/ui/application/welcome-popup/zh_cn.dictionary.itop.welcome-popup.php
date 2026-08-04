<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     https://opensource.org/licenses/AGPL-3.0
 */

// UI elements
Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'UI:WelcomePopup:Button:RemindLater' => '稍后再提醒我',
	'UI:WelcomePopup:Button:AcknowledgeAndNext' => '下一步',
	'UI:WelcomePopup:Button:AcknowledgeAndClose' => '关闭',
]);

// Message
Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'UI:WelcomePopup:Message:320_01_Welcome:Title' => '欢迎使用 '.ITOP_APPLICATION_SHORT.' 3.2',
	'UI:WelcomePopup:Message:320_01_Welcome:Description' => '<div>恭喜, 您已成功登录到 '.ITOP_APPLICATION.' '.ITOP_VERSION_NAME.'!</div>
<br>
<div>很高兴向您宣布这个新版本. </div>
<div>新增了新闻室等新功能, '.ITOP_APPLICATION_SHORT.' 3.2 还包含了关键的安全补丁、增强的亲和性以及其它重要改进,旨在为您提供更好的稳定性和安全性.</div>
<br>
<div>发现 '.ITOP_APPLICATION_SHORT.' 所有令人兴奋的新功能,并通过我们的新欢迎弹窗保持与重要通知的同步！</div>
<div>希望您会像我们一样, 从构思到创造, 全程享受这个版本.</div>
<br>
<div>定制您的 '.ITOP_APPLICATION_SHORT.' 偏好设置,可以获得个性化的体验.</div>',
	'UI:WelcomePopup:Message:320_02_Newsroom:Title' => '向新闻室说"Hello"',
	'UI:WelcomePopup:Message:320_02_Newsroom:Description' => '<div>告别杂乱的收件箱,用 <a href="%1$s" target="_blank">'.ITOP_APPLICATION_SHORT.' 新闻室</a>迎接个性化的告警！</div>
<div>新闻室允许您轻松管理平台内的通知,因此您可以掌握重要更新而无需频繁查收电子邮件.通过将消息标记为已读或未读,并自动删除旧通知,您可以完全控制您的通知.</div>
<br>
<div>今天就试试,简化您的 '.ITOP_APPLICATION_SHORT.' 沟通体验！</div>',
	'UI:WelcomePopup:Message:320_03_NotificationsCenter:Title' => '通知中心',
	'UI:WelcomePopup:Message:320_03_NotificationsCenter:Description' => '<div>由于我们知道您的信息摄入量已经达到最大限度,现在您可以轻松选择如何接收通知 - 通过电子邮件、聊天,甚至新闻室功能</div>
<div>您不想接收某种类型的警报?使用这些高级自定义功能,您可以根据需要轻松定制体验.</div>
<br>
<div>通过新闻室或您的偏好设置访问您的<a href="%1$s" target="_blank">通知中心</a>,避免所有通信渠道的信息过载！</div>',
	'UI:WelcomePopup:Message:320_05_A11yThemes:Title' => ITOP_APPLICATION_SHORT.' UI 的亲和性',
	'UI:WelcomePopup:Message:320_05_A11yThemes:Description' => '<div>为了确保 '.ITOP_APPLICATION_SHORT.' 的亲和性,我们的团队一直在开发<a href="%1$s" target="_blank">新的后台主题</a>.符合 WCAG 标准,这些 UI 主题可以帮助视力障碍用户更容易的使用:
<ul>
	<li><b>色盲主题:</b> 设计用于帮助色盲用户,此主题实际上分为两个子主题以适应特定情况:</li>
		<ul>
			<li>一个适用于红绿色盲和绿色色盲</li>	
			<li>另一个适用于黄蓝色盲</li>	
		</ul>
		<br>
	<li><b>高对比度主题:</b> 增加对比度以允许用户更容易区分屏幕上的不同元素,并避免依赖颜色方案传递信息.它可以帮助从色盲到弱视等不同病理的用户.</li>
</ul>
</div>',
	'UI:WelcomePopup:Message:320_04_PowerfulNotifications_AdminOnly:Title' => '强大的通知',
	'UI:WelcomePopup:Message:320_04_PowerfulNotifications_AdminOnly:Description' => '<div>'.ITOP_APPLICATION_SHORT.' 的新闻室为您提供了一种新的方法,可以 <a href="%1$s" target="_blank"><b>自动化</b> 基于事件的告警</a> 并支持重复设置, 因此您可以轻松设置适合您的规则. </div>
<div>我们的<b>基于优先级的通知排序</b>确保重要消息优先展示,同时,我们的 URL 自定义选项允许您将收件人引导到正确的位置.</div>
<br>
<div>支持<b>多语言</b>,您现在可以完全控制通知显示.</div>
<br>
<div>现在就配置它,看看您的警报流程可以变得多么高效！</div>',
]);
