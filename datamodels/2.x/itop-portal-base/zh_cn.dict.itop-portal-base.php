<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */
// Portal
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Page:DefaultTitle' => 'iTop 用户门户',
	'Page:PleaseWait' => '请稍后...',
	'Page:Home' => '主页',
	'Page:GoPortalHome' => '主页面',
	'Page:GoPreviousPage' => '上一页',
	'Page:ReloadPage' => '重新加载',
	'Portal:Button:Submit' => '提交',
	'Portal:Button:Apply' => '更新',
	'Portal:Button:Cancel' => '取消',
	'Portal:Button:Close' => '关闭',
	'Portal:Button:Add' => '添加',
	'Portal:Button:Remove' => '移除',
	'Portal:Button:Delete' => '删除',
	'Portal:EnvironmentBanner:Title' => '您目前处于 <strong>%1$s</strong> 模式',
	'Portal:EnvironmentBanner:GoToProduction' => '回到产品模式',
	'Error:HTTP:400' => 'Bad request~~',
	'Error:HTTP:401' => '认证',
	'Error:HTTP:404' => '页面找不到',
	'Error:HTTP:500' => 'Oops! 发生了一个错误.',
	'Error:HTTP:GetHelp' => '如果问题仍然存在,请联系管理员.',
	'Error:XHR:Fail' => '无法加载数据, 请联系管理员',
	'Portal:ErrorUserLoggedOut' => '您已退出，请重新登录.',
	'Portal:Datatables:Language:Processing' => '请稍后...',
	'Portal:Datatables:Language:Search' => '过滤器:',
	'Portal:Datatables:Language:LengthMenu' => '每页显示 _MENU_ 项',
	'Portal:Datatables:Language:ZeroRecords' => '没有结果',
	'Portal:Datatables:Language:Info' => '第 _PAGE_ 页,共 _PAGES_ 页',
	'Portal:Datatables:Language:InfoEmpty' => '没有信息',
	'Portal:Datatables:Language:InfoFiltered' => 'filtered out of _MAX_ items',
	'Portal:Datatables:Language:EmptyTable' => '表格中没有数据',
	'Portal:Datatables:Language:DisplayLength:All' => '全部',
	'Portal:Datatables:Language:Paginate:First' => '首页',
	'Portal:Datatables:Language:Paginate:Previous' => '上一页',
	'Portal:Datatables:Language:Paginate:Next' => '下一页',
	'Portal:Datatables:Language:Paginate:Last' => '尾页',
	'Portal:Datatables:Language:Sort:Ascending' => '升序排序',
	'Portal:Datatables:Language:Sort:Descending' => '降序排序',
	'Portal:Autocomplete:NoResult' => '没有数据',
	'Portal:Attachments:DropZone:Message' => '把文件添加为附件',
	'Portal:File:None' => '没有文件',
	'Portal:File:DisplayInfo' => '<a href="%2$s" class="file_download_link">%1$s</a>',
	'Portal:File:DisplayInfo+' => '%1$s (%2$s) <a href="%3$s" class="file_open_link" target="_blank">Open</a> / <a href="%4$s" class="file_download_link">Download</a>',
	'Portal:Calendar-FirstDayOfWeek' => 'en-us~~', //work with moment.js locales
	'Portal:Form:Close:Warning' => 'Do you want to leave this form ? Data entered may be lost~~',
));

// UserProfile brick
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Brick:Portal:UserProfile:Name' => '用户资料',
	'Brick:Portal:UserProfile:Navigation:Dropdown:MyProfil' => '我的资料',
	'Brick:Portal:UserProfile:Navigation:Dropdown:Logout' => '注销',
	'Brick:Portal:UserProfile:Password:Title' => '密码',
	'Brick:Portal:UserProfile:Password:ChoosePassword' => '新密码',
	'Brick:Portal:UserProfile:Password:ConfirmPassword' => '确认密码',
	'Brick:Portal:UserProfile:Password:CantChangeContactAdministrator' => '要修改密码,请联系管理员',
	'Brick:Portal:UserProfile:Password:CantChangeForUnknownReason' => '无法修改密码, 请联系管理员',
	'Brick:Portal:UserProfile:PersonalInformations:Title' => '个体信息',
	'Brick:Portal:UserProfile:Photo:Title' => '头像',
));

// AggregatePageBrick
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Brick:Portal:AggregatePage:DefaultTitle' => 'Dashboard',
));

// BrowseBrick brick
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Brick:Portal:Browse:Name' => '浏览项目',
	'Brick:Portal:Browse:Mode:List' => '列表',
	'Brick:Portal:Browse:Mode:Tree' => '树形',
	'Brick:Portal:Browse:Mode:Mosaic' => '嵌套',
	'Brick:Portal:Browse:Action:Drilldown' => '明细',
	'Brick:Portal:Browse:Action:View' => '详情',
	'Brick:Portal:Browse:Action:Edit' => '编辑',
	'Brick:Portal:Browse:Action:Create' => '新建',
	'Brick:Portal:Browse:Action:CreateObjectFromThis' => '新建 %1$s',
	'Brick:Portal:Browse:Tree:ExpandAll' => '全部展开',
	'Brick:Portal:Browse:Tree:CollapseAll' => '全部收起',
	'Brick:Portal:Browse:Filter:NoData' => '没有项目',
));

// ManageBrick brick
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Brick:Portal:Manage:Name' => '管理项目',
	'Brick:Portal:Manage:Table:NoData' => '没有项目.',
	'Brick:Portal:Manage:Table:ItemActions' => 'Actions',
	'Brick:Portal:Manage:DisplayMode:list' => '列表',
	'Brick:Portal:Manage:DisplayMode:pie-chart' => '饼图',
	'Brick:Portal:Manage:DisplayMode:bar-chart' => '条形图',
	'Brick:Portal:Manage:Others' => 'Others',
	'Brick:Portal:Manage:All' => '全部',
	'Brick:Portal:Manage:Group' => '分组',
	'Brick:Portal:Manage:fct:count' => '个数',
	'Brick:Portal:Manage:fct:sum' => '总数',
	'Brick:Portal:Manage:fct:avg' => '平均',
	'Brick:Portal:Manage:fct:min' => '最小',
	'Brick:Portal:Manage:fct:max' => '最大',
));

// ObjectBrick brick
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Brick:Portal:Object:Name' => '对象',
	'Brick:Portal:Object:Form:Create:Title' => '新建 %1$s',
	'Brick:Portal:Object:Form:Edit:Title' => '正在更新 %2$s (%1$s)',
	'Brick:Portal:Object:Form:View:Title' => '%1$s : %2$s',
	'Brick:Portal:Object:Form:Stimulus:Title' => 'Please, fill the following informations:',
	'Brick:Portal:Object:Form:Message:Saved' => '已保存',
	'Brick:Portal:Object:Form:Message:ObjectSaved' => '已保存 %1$s~~',
	'Brick:Portal:Object:Search:Regular:Title' => '选择 %1$s (%2$s)',
	'Brick:Portal:Object:Search:Hierarchy:Title' => '选择 %1$s (%2$s)',
	'Brick:Portal:Object:Copy:TextToCopy' => '%1$s: %2$s~~',
	'Brick:Portal:Object:Copy:Tooltip' => 'Copy object link~~',
	'Brick:Portal:Object:Copy:CopiedTooltip' => 'Copied~~'
));

// CreateBrick brick
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Brick:Portal:Create:Name' => '快速创建',
	'Brick:Portal:Create:ChooseType' => 'Please, choose a type',
));

// Filter brick
Dict::Add('ZH CN', 'Chinese', '简体中文', array(
	'Brick:Portal:Filter:Name' => 'Prefilter a brick',
	'Brick:Portal:Filter:SearchInput:Placeholder' => '例如. 连接 wifi',
	'Brick:Portal:Filter:SearchInput:Submit' => '搜素',
));
