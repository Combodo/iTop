<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 *
 */
Dict::Add('ZH CN', 'Chinese', '简体中文', [
	'ActionGoogleChatNotification:message' => '消息',
	'ActionMicrosoftTeamsNotification:additionalelements' => '要包含的附加元素',
	'ActionMicrosoftTeamsNotification:message' => '基本消息',
	'ActionMicrosoftTeamsNotification:theme' => '主题',
	'ActionRocketChatNotification:additionalelements' => '自动程序信息',
	'ActionRocketChatNotification:message' => '基本消息',
	'ActionSlackNotification:Payload:BlockKit:UserInfo' => '通知来自于<%2$s|%1$s>（%3$s）',
	'ActionSlackNotification:additionalelements' => '要包含的附加元素',
	'ActionSlackNotification:message' => '基本消息',
	'ActionWebhook:advancedparameters' => '高级参数',
	'ActionWebhook:baseinfo' => '常规信息',
	'ActionWebhook:moreinfo' => '更多信息',
	'ActionWebhook:requestparameters' => '请求参数',
	'ActionWebhook:webhookconnection' => 'Webhook连接',
	'Class:ActionGoogleChatNotification' => '谷歌聊天通知',
	'Class:ActionGoogleChatNotification+' => '以谷歌空间聊天消息发送通知',
	'Class:ActionGoogleChatNotification/Attribute:message' => '消息',
	'Class:ActionGoogleChatNotification/Attribute:message+' => '在聊天中要显示的消息，目前仅支持纯文本。',
	'Class:ActionGoogleChatNotification/Attribute:prepare_payload_callback+' => '筹备载荷数据的PHP方法，以用于此webhook调用时发送。请在标准选项不够灵活，或者所发送数据必须动态生成时使用。

可以使用2种类型的方法：
——来自触发对象自身（如用户请求），必须是公开的。例如：$this->XXX
——来自任何PHP类，必须是静态的且公开的。名称必须是合法的全名。例如：\SomeClass::XXX

请注意：如果设置了此参数，则会忽略“消息”参数。',
	'Class:ActionMicrosoftTeamsNotification' => '微软Teams通知',
	'Class:ActionMicrosoftTeamsNotification+' => '以微软Teams频道消息发送通知',
	'Class:ActionMicrosoftTeamsNotification/Attribute:image_url' => '徽章图像',
	'Class:ActionMicrosoftTeamsNotification/Attribute:image_url+' => '在此消息卡片中显示为徽章的图片URL，为使微软Teams能显示，其必须是在互联网可公开访问的',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button' => '删除按钮',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button+' => '在此消息之后包含一个按钮，以在'.ITOP_APPLICATION_SHORT.'中删除此对象',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button/Value:no' => '否',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_delete_button/Value:yes' => '是',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes' => '发送者属性',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes+' => '在此消息之后显示额外属性。其可以是来自触发此通知的对象的常规“列表”视图，或者自定义“msteams”视图。请注意，此“msteams”视图必须先在数据模型中定义（zlist）',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes/Value:list' => '常规“列表”视图',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_list_attributes/Value:msteams' => '自定义“msteams”视图',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button' => '修改按钮',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button+' => '在此消息之后包含一个按钮，以在'.ITOP_APPLICATION_SHORT.'中修改此对象',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button/Value:no' => '否',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_modify_button/Value:yes' => '是',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button' => '其他操作按钮',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button+' => '在此消息之后包含其他操作（例如当前状态下可用的转变）',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:no' => '否',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:specify' => '指定',
	'Class:ActionMicrosoftTeamsNotification/Attribute:include_other_actions_button/Value:yes' => '是',
	'Class:ActionMicrosoftTeamsNotification/Attribute:message' => '消息',
	'Class:ActionMicrosoftTeamsNotification/Attribute:prepare_payload_callback+' => '筹备载荷数据的PHP方法，以用于此webhook调用时发送。请在标准选项不够灵活，或者所发送数据必须动态生成时使用。

可以使用2种类型的方法：
——来自触发对象自身（如用户请求），必须是公开的。例如：$this->XXX
——来自任何PHP类，必须是静态的且公开的。名称必须是合法的全名。例如：\SomeClass::XXX

请注意：如果设置了此参数，则会忽略“标题”、“消息”和所有的“附加元素”参数。',
	'Class:ActionMicrosoftTeamsNotification/Attribute:specified_other_actions' => '其他操作编码',
	'Class:ActionMicrosoftTeamsNotification/Attribute:specified_other_actions+' => '指定在此消息之后要包含的操作按钮。应该为逗号分割的操作编码列表（例如“ev_reopen, ev_close”）',
	'Class:ActionMicrosoftTeamsNotification/Attribute:theme_color' => '高亮颜色',
	'Class:ActionMicrosoftTeamsNotification/Attribute:theme_color+' => '此消息卡片在微软Teams中的高亮颜色，必须是合法的16进制颜色编码（例如FF0000）',
	'Class:ActionMicrosoftTeamsNotification/Attribute:title' => '标题',
	'Class:ActionRocketChatNotification' => 'Rocket.Chat通知',
	'Class:ActionRocketChatNotification+' => '以Rocket.Chat频道或用户消息发送通知',
	'Class:ActionRocketChatNotification/Attribute:bot_alias' => '别名',
	'Class:ActionRocketChatNotification/Attribute:bot_alias+' => '覆盖默认的自动程序别名，显示在消息用户名之前',
	'Class:ActionRocketChatNotification/Attribute:bot_emoji_avatar' => '表情符号',
	'Class:ActionRocketChatNotification/Attribute:bot_emoji_avatar+' => '覆盖默认的自动程序表情符号，可以是任何Rocket.Chat表情符号（例如:ghost:，:white_check_mark:，...）。请注意如果设置了头像URL，将不会显示表情符号。',
	'Class:ActionRocketChatNotification/Attribute:bot_url_avatar' => '图片头像',
	'Class:ActionRocketChatNotification/Attribute:bot_url_avatar+' => '覆盖默认的自动程序头像，必须是此图片的绝对URL',
	'Class:ActionRocketChatNotification/Attribute:message' => '消息',
	'Class:ActionRocketChatNotification/Attribute:message+' => '在聊天中要显示的消息',
	'Class:ActionRocketChatNotification/Attribute:prepare_payload_callback+' => '筹备载荷数据的PHP方法，以用于此webhook调用时发送。请在标准选项不够灵活，或者所发送数据必须动态生成时使用。

可以使用2种类型的方法：
——来自触发对象自身（如用户请求），必须是公开的。例如：$this->XXX
——来自任何PHP类，必须是静态的且公开的。名称必须是合法的全名。例如：\SomeClass::XXX

请注意：如果设置了此参数，则会忽略“消息”和所有的“自动程序信息”参数。',
	'Class:ActionSlackNotification' => 'Slack通知',
	'Class:ActionSlackNotification+' => '以Slack频道或用户消息发送通知',
	'Class:ActionSlackNotification/Attribute:include_delete_button' => '删除按钮',
	'Class:ActionSlackNotification/Attribute:include_delete_button+' => '在此消息之后包含一个按钮，以在'.ITOP_APPLICATION_SHORT.'中删除此对象',
	'Class:ActionSlackNotification/Attribute:include_delete_button/Value:no' => '否',
	'Class:ActionSlackNotification/Attribute:include_delete_button/Value:yes' => '是',
	'Class:ActionSlackNotification/Attribute:include_list_attributes' => '发送者属性',
	'Class:ActionSlackNotification/Attribute:include_list_attributes+' => '在此消息之后显示额外属性。其可以是来自触发此通知的对象的常规“列表”视图，或者自定义“slack”视图。请注意，此“slack”视图必须先在数据模型中定义（zlist）',
	'Class:ActionSlackNotification/Attribute:include_list_attributes/Value:list' => '常规“列表”视图',
	'Class:ActionSlackNotification/Attribute:include_list_attributes/Value:slack' => '自定义“slack”视图',
	'Class:ActionSlackNotification/Attribute:include_modify_button' => '修改按钮',
	'Class:ActionSlackNotification/Attribute:include_modify_button+' => '在此消息之后包含一个按钮，以在'.ITOP_APPLICATION_SHORT.'中修改此对象',
	'Class:ActionSlackNotification/Attribute:include_modify_button/Value:no' => '否',
	'Class:ActionSlackNotification/Attribute:include_modify_button/Value:yes' => '是',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button' => '其他操作按钮',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button+' => '在此消息之后包含其他操作（例如当前状态下可用的转变）',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:no' => '否',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:specify' => '指定',
	'Class:ActionSlackNotification/Attribute:include_other_actions_button/Value:yes' => '是',
	'Class:ActionSlackNotification/Attribute:include_user_info' => '用户信息',
	'Class:ActionSlackNotification/Attribute:include_user_info+' => '显示用户信息（全名）在此消息之后',
	'Class:ActionSlackNotification/Attribute:include_user_info/Value:no' => '否',
	'Class:ActionSlackNotification/Attribute:include_user_info/Value:yes' => '是',
	'Class:ActionSlackNotification/Attribute:message' => '消息',
	'Class:ActionSlackNotification/Attribute:prepare_payload_callback+' => '筹备载荷数据的PHP方法，以用于此webhook调用时发送。请在标准选项不够灵活，或者所发送数据必须动态生成时使用。

可以使用2种类型的方法：
——来自触发对象自身（如用户请求），必须是公开的。例如：$this->XXX
——来自任何PHP类，必须是静态的且公开的。名称必须是合法的全名。例如：\SomeClass::XXX

请注意：如果设置了此参数，则会忽略“消息”和所有的“附加元素”参数。',
	'Class:ActionSlackNotification/Attribute:specified_other_actions' => '其他操作编码',
	'Class:ActionSlackNotification/Attribute:specified_other_actions+' => '指定在此消息之后要包含的操作按钮。应该为逗号分割的操作编码列表（例如“ev_reopen, ev_close”）',
	'Class:ActionWebhook' => 'Webhook调用（通用）',
	'Class:ActionWebhook+' => 'Webhook调用可用于任何应用',
	'Class:ActionWebhook/Attribute:asynchronous+' => 'Whether this action should be executed in background or not (mind that global setting for webhook actions is the "prefer_asynchronous" conf. parameter of the "combodo-webhook-action" module)~~',
	'Class:ActionWebhook/Attribute:headers' => '头信息',
	'Class:ActionWebhook/Attribute:headers+' => 'HTTP请求的头信息，每个必须时1行（例如“Content-type: application/json”）',
	'Class:ActionWebhook/Attribute:language' => '语言',
	'Class:ActionWebhook/Attribute:language+' => '通知的语言，通常用于搜索通知，也可用于翻译属性标签',
	'Class:ActionWebhook/Attribute:method' => '方法',
	'Class:ActionWebhook/Attribute:method+' => 'HTTP请求的方法',
	'Class:ActionWebhook/Attribute:method/Value:delete' => 'DELETE',
	'Class:ActionWebhook/Attribute:method/Value:get' => 'GET',
	'Class:ActionWebhook/Attribute:method/Value:head' => 'HEAD',
	'Class:ActionWebhook/Attribute:method/Value:patch' => 'PATCH',
	'Class:ActionWebhook/Attribute:method/Value:post' => 'POST',
	'Class:ActionWebhook/Attribute:method/Value:put' => 'PUT',
	'Class:ActionWebhook/Attribute:path' => 'Path~~',
	'Class:ActionWebhook/Attribute:path+' => 'Additional path to append to the connection URL (eg. \'/some/specific-endpoint\')~~',
	'Class:ActionWebhook/Attribute:payload' => '载荷',
	'Class:ActionWebhook/Attribute:payload+' => '调用时发送的数据，通常为JSON字符串。请在所发送数据为静态时使用。

请注意：如果设置了“筹备payload回调”，其将被忽略',
	'Class:ActionWebhook/Attribute:prepare_payload_callback' => '筹备载荷回调',
	'Class:ActionWebhook/Attribute:prepare_payload_callback+' => '筹备载荷数据的PHP方法，以用于此webhook调用时发送。请在所发送数据必须动态生成时使用。

可以使用2种类型的方法：
——来自触发对象自身（如用户请求），必须是公开的。例如：$this->XXX
——来自任何PHP类，必须是静态的且公开的。名称必须是合法的全名。例如：\SomeClass::XXX

请注意：如果设置了，则会忽略“载荷”属性。',
	'Class:ActionWebhook/Attribute:process_response_callback' => '处理响应回调',
	'Class:ActionWebhook/Attribute:process_response_callback+' => 'PHP方法用以处理此webhook调用的响应。

可以使用2种类型的方法：
——来自触发对象自身（如用户请求），必须是公开的。例如：$this->XXX
——来自任何PHP类，必须是静态的且公开的。名称必须是合法的全名。例如：\SomeClass::XXX
——在某些情况下$oResponse可以为空（例如发送请求失败）',
	'Class:ActionWebhook/Attribute:remoteapplicationconnection_id' => '连接',
	'Class:ActionWebhook/Attribute:remoteapplicationconnection_id+' => '状态为“线上”时使用的连接信息',
	'Class:ActionWebhook/Attribute:test_remoteapplicationconnection_id' => '测试的连接',
	'Class:ActionWebhook/Attribute:test_remoteapplicationconnection_id+' => '状态为“测试”时使用的连接信息',
	'Class:ActioniTopWebhook' => ITOP_APPLICATION_SHORT.'webhook调用',
	'Class:ActioniTopWebhook+' => '远程'.ITOP_APPLICATION_SHORT.'应用的Webhook调用',
	'Class:ActioniTopWebhook/Attribute:headers+' => 'HTTP请求的头信息，每个必须1行（例如：“Content-type: application/x-www-form-urlencoded”）

请注意：
——当应用为'.ITOP_APPLICATION_SHORT.'时“Content-type”必须设置为“application/x-www-form-urlencoded”，即使发送的是JSON
——名为“Basic authorization”的头信息将在发送时自动添加到请求，包含此所选连接的认证信息',
	'Class:ActioniTopWebhook/Attribute:payload' => 'JSON数据',
	'Class:ActioniTopWebhook/Attribute:payload+' => '此JSON载荷，必须是包含操作名称和参数的JSON字符串，详细信息请参考说明文档',
	'Class:ActioniTopWebhook/Attribute:prepare_payload_callback+' => '筹备载荷数据的PHP方法，以用于此webhook调用时发送。请在所发送数据必须动态生成时使用。

可以使用2种类型的方法：
——来自触发对象自身（如用户请求），必须是公开的。例如：$this->XXX
——来自任何PHP类，必须是静态的且公开的。名称必须是合法的全名。例如：\SomeClass::XXX

请注意：如果设置了此参数，则会忽略“JSON数据”参数。',
	'Class:EventWebhook' => 'Webhook发行事件',
	'Class:EventWebhook/Attribute:action_finalclass' => '终态类',
	'Class:EventWebhook/Attribute:headers' => '头信息',
	'Class:EventWebhook/Attribute:payload' => '载荷',
	'Class:EventWebhook/Attribute:response' => '响应',
	'Class:EventWebhook/Attribute:webhook_url' => 'Webhook的URL',
	'Class:RemoteApplicationConnection' => '远程应用连接',
	'Class:RemoteApplicationConnection/Attribute:actions_list' => 'Webhook通知',
	'Class:RemoteApplicationConnection/Attribute:actions_list+' => '使用此连接的Webhook通知',
	'Class:RemoteApplicationConnection/Attribute:environment' => '环境',
	'Class:RemoteApplicationConnection/Attribute:environment+' => '此连接的环境的类型',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:1-development' => '开发',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:2-test' => '测试',
	'Class:RemoteApplicationConnection/Attribute:environment/Value:3-production' => '线上',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id' => '应用类型',
	'Class:RemoteApplicationConnection/Attribute:remoteapplicationtype_id+' => '此连接的应用的类型（如果列表中没有您的请使用“通用”）',
	'Class:RemoteApplicationConnection/Attribute:url' => 'URL',
	'Class:RemoteApplicationType' => '远程应用类型',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list' => '连接',
	'Class:RemoteApplicationType/Attribute:remoteapplicationconnections_list+' => '外部应用的连接',
	'Class:RemoteiTopConnection' => '远程'.ITOP_APPLICATION_SHORT.'连接',
	'Class:RemoteiTopConnection/Attribute:auth_pwd' => '验证密码',
	'Class:RemoteiTopConnection/Attribute:auth_pwd+' => '此身份验证所使用的用户（远程'.ITOP_APPLICATION_SHORT.'）密码',
	'Class:RemoteiTopConnection/Attribute:auth_user' => '验证用户',
	'Class:RemoteiTopConnection/Attribute:auth_user+' => '此身份验证所使用的用户（远程'.ITOP_APPLICATION_SHORT.'）登录名',
	'Class:RemoteiTopConnection/Attribute:version' => 'API版本',
	'Class:RemoteiTopConnection/Attribute:version+' => '此API调用的版本（例如1.3）',
	'Class:RemoteiTopConnectionToken' => 'Remote iTop connection using a Token~~',
	'Class:RemoteiTopConnectionToken/Attribute:token+' => 'Token~~',
	'Dashboard:Integrations:ActionWebhookList:Title' => 'Webhook类型操作',
	'Dashboard:Integrations:Outgoing:Title' => '外部的webhook集成',
	'Dashboard:Integrations:Title' => '与外部应用集成',
	'Menu:Webhook' => 'Webhooks~~',
	'RemoteApplicationConnection:authinfo' => '认证',
	'RemoteApplicationConnection:baseinfo' => '常规信息',
	'RemoteApplicationConnection:moreinfo' => '更多信息',
	'Class:ActionWebhook/Error:InvalidJSONFormatForPayload' => 'Invalid JSON format for Payload~~',
]);
