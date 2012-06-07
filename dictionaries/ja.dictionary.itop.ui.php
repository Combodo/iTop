<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Localized data
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @author      Tadashi Kaneda <kaneda@rworks.jp>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


//////////////////////////////////////////////////////////////////////
// Classes in 'gui'
//////////////////////////////////////////////////////////////////////
//

//////////////////////////////////////////////////////////////////////
// Classes in 'application'
//////////////////////////////////////////////////////////////////////
//

//
// Class: AuditCategory
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:AuditCategory' => '監査カテゴリ', //Audit Category',
					       'Class:AuditCategory+' => '監査全体の内部セクション', //'A section inside the overall audit',
					       'Class:AuditCategory/Attribute:name' => 'カテゴリ名', //'Category Name',
					       'Class:AuditCategory/Attribute:name+' => '本カテゴリの短縮名', //'Short name for this category',
					       'Class:AuditCategory/Attribute:description' => '監査カテゴリ概要', //'Audit Category Description',
					       'Class:AuditCategory/Attribute:description+' => '本監査カテゴリの詳細記述', //'Long description for this audit category',
					       'Class:AuditCategory/Attribute:definition_set' => '定義セット', //'Definition Set',
					       'Class:AuditCategory/Attribute:definition_set+' => '監査するべきオブジェクトの集合を定義するOQL式', //'OQL expression defining the set of objects to audit',
					       'Class:AuditCategory/Attribute:rules_list' => '監査ルール', //'Audit Rules',
					       'Class:AuditCategory/Attribute:rules_list+' => '本カテゴリの監査ルール', //'Audit rules for this category',
));

//
// Class: AuditRule
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:AuditRule' => '監査ルール', //'Audit Rule',
					       'Class:AuditRule+' => '指定された監査カテゴリをチェックするためのルール', //'A rule to check for a given Audit category',
					       'Class:AuditRule/Attribute:name' => 'ルール名', //'Rule Name',
					       'Class:AuditRule/Attribute:name+' => '本ルールの短縮名', //'Short name for this rule',
					       'Class:AuditRule/Attribute:description' => '監査ルール概要', //'Audit Rule Description',
					       'Class:AuditRule/Attribute:description+' => '本監査ルールの詳細記述', //'Long description for this audit rule',
					       'Class:AuditRule/Attribute:query' => '実行するクエリ', //'Query to Run',
					       'Class:AuditRule/Attribute:query+' => '実行するOQL式', //'The OQL expression to run',
					       'Class:AuditRule/Attribute:valid_flag' => '正しいオブジェクト?', // 'Valid Objects?', 
					       'Class:AuditRule/Attribute:valid_flag+' => 'このルールが正しいオブジェクトを返す場合は真、そうでなければ偽', //'True if the rule returns the valid objects, false otherwise',
					       'Class:AuditRule/Attribute:valid_flag/Value:true' => '真', //'true',
					       'Class:AuditRule/Attribute:valid_flag/Value:true+' => '真', //'true',
					       'Class:AuditRule/Attribute:valid_flag/Value:false' => '偽', //'false',
					       'Class:AuditRule/Attribute:valid_flag/Value:false+' => '偽', //'false',
					       'Class:AuditRule/Attribute:category_id' => 'カテゴリ', //'Category',
					       'Class:AuditRule/Attribute:category_id+' => '本ルールのカテゴリ', //'The category for this rule',
					       'Class:AuditRule/Attribute:category_name' => 'カテゴリ', //'Category',
					       'Class:AuditRule/Attribute:category_name+' => '本ルールのカテゴリ名', //'Name of the category for this rule',
));

//////////////////////////////////////////////////////////////////////
// Classes in 'addon/userrights'
//////////////////////////////////////////////////////////////////////
//

//
// Class: User
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:User' => 'ユーザ', //'User',
					       'Class:User+' => 'ユーザログイン', //'User login',
					       'Class:User/Attribute:finalclass' => 'アカウント種別', //'Type of account',
	'Class:User/Attribute:finalclass+' => '',
					       'Class:User/Attribute:contactid' => 'コンタクト(人)', //'Contact (person)',
					       'Class:User/Attribute:contactid+' => 'ビジネスデータから抽出した個人情報の詳細', //'Personal details from the business data',
					       'Class:User/Attribute:last_name' => '名字', //'Last name',
					       'Class:User/Attribute:last_name+' => '適切なコンタクト名', //'Name of the corresponding contact',
					       'Class:User/Attribute:first_name' => '名前', //'First name',
					       'Class:User/Attribute:first_name+' => '適切なコンタクトの名前', //'First name of the corresponding contact',
					       'Class:User/Attribute:email' => 'メールアドレス', //'Email',
					       'Class:User/Attribute:email+' => '適切なコンタクトのメールアドレス', //'Email of the corresponding contact',
					       'Class:User/Attribute:login' => 'ログイン', //'Login',
					       'Class:User/Attribute:login+' => 'ユーザ識別文字列', //'user identification string',
					       'Class:User/Attribute:language' => '言語', //'Language',
					       'Class:User/Attribute:language+' => 'ユーザ使用言語', //'user language',
					       'Class:User/Attribute:language/Value:EN US' => '英語', //'English',
					       'Class:User/Attribute:language/Value:EN US+' => '英語(米国)', //'English (U.S.)',
					       'Class:User/Attribute:language/Value:FR FR' => 'フランス語', //'French',
					       'Class:User/Attribute:language/Value:FR FR+' => 'フランス語(フランス)', //'French (France)',
					       'Class:User/Attribute:profile_list' => 'プロフィール', //'Profiles',
					       'Class:User/Attribute:profile_list+' => '役割、この人に委譲された権限', //'Roles, granting rights for that person',
					       'Class:User/Attribute:allowed_org_list' => '許可された組織', //'Allowed Organizations',
					       'Class:User/Attribute:allowed_org_list+' => 'このエンドユーザは以下の組織に属するデータの参照を許可されている。組織が指定されていなければ、制限はありません。', //'The end user is allowed to see data belonging to the following organizations. If no organization is specified, there is no restriction.',

					       'Class:User/Error:LoginMustBeUnique' => 'ログイン名は一意でないといけません。- "%1s" はすでに使われています。', //'Login must be unique - "%1s" is already being used.',
					       'Class:User/Error:AtLeastOneProfileIsNeeded' => '少なくとも1件のプロフィールがこのユーザに指定されていないといけません。', //'At least one profile must be assigned to this user.',
));

//
// Class: URP_Profiles
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:URP_Profiles' => 'プロフィール', //'Profile',
					       'Class:URP_Profiles+' => 'ユーザプロフィール', //'User profile',
					       'Class:URP_Profiles/Attribute:name' => '名前', //'Name',
					       'Class:URP_Profiles/Attribute:name+' => 'ラベル', //'label',
					       'Class:URP_Profiles/Attribute:description' => '概要', //'Description',
					       'Class:URP_Profiles/Attribute:description+' => '1行で書くと', //'one line description',
					       'Class:URP_Profiles/Attribute:user_list' => 'ユーザ', //'Users',
					       'Class:URP_Profiles/Attribute:user_list+' => 'この役割をもつ人', //'persons having this role',
));

//
// Class: URP_Dimensions
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:URP_Dimensions' => '次元', //'dimension',
	'Class:URP_Dimensions+' => 'application dimension (defining silos)',
					       'Class:URP_Dimensions/Attribute:name' => '名前', //'Name',
					       'Class:URP_Dimensions/Attribute:name+' => 'ラベル', //'label',
					       'Class:URP_Dimensions/Attribute:description' => '概要', //'Description',
					       'Class:URP_Dimensions/Attribute:description+' => '1行で書くと', //'one line description',
					       'Class:URP_Dimensions/Attribute:type' => '種別', //'Type',
					       'Class:URP_Dimensions/Attribute:type+' => 'クラス名、もしくはデータ型(projection unit)', //'class name or data type (projection unit)',
));

//
// Class: URP_UserProfile
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:URP_UserProfile' => 'User to profile',
	'Class:URP_UserProfile+' => 'ユーザプロフィール', //'user profiles',
	'Class:URP_UserProfile/Attribute:userid' => 'ユーザ', //'User',
	'Class:URP_UserProfile/Attribute:userid+' => 'ユーザアカウント', //'user account',
	'Class:URP_UserProfile/Attribute:userlogin' => 'ログイン', //'Login',
	'Class:URP_UserProfile/Attribute:userlogin+' => 'ユーザのログイン', //'User\'s login',
	'Class:URP_UserProfile/Attribute:profileid' => 'プロフィール', //'Profile',
	'Class:URP_UserProfile/Attribute:profileid+' => 'プロフィールの用法???', //'usage profile',
	'Class:URP_UserProfile/Attribute:profile' => 'プロフィール', //'Profile',
	'Class:URP_UserProfile/Attribute:profile+' => 'プロフィール名', //'Profile name',
	'Class:URP_UserProfile/Attribute:reason' => '理由', //'Reason',
	'Class:URP_UserProfile/Attribute:reason+' => 'なぜ、この人物がこの役割を持つかを説明する', //'explain why this person may have this role',
));

//
// Class: URP_UserOrg
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:URP_UserOrg' => 'ユーザ組織', //'User organizations',
					       'Class:URP_UserOrg+' => '許可された組織', //'Allowed organizations',
					       'Class:URP_UserOrg/Attribute:userid' => 'ユーザ', //'User',
					       'Class:URP_UserOrg/Attribute:userid+' => 'ユーザアカウント', //'user account',
					       'Class:URP_UserOrg/Attribute:userlogin' => 'ログイン', //'Login',
					       'Class:URP_UserOrg/Attribute:userlogin+' => 'ユーザのログイン', //'User\'s login',
					       'Class:URP_UserOrg/Attribute:allowed_org_id' => '組織', //'Organization',
					       'Class:URP_UserOrg/Attribute:allowed_org_id+' => '許可された組織', //'Allowed organization',
					       'Class:URP_UserOrg/Attribute:allowed_org_name' => '組織', //'Organization',
					       'Class:URP_UserOrg/Attribute:allowed_org_name+' => '許可された組織', //'Allowed organization',
					       'Class:URP_UserOrg/Attribute:reason' => '理由', //'Reason',
					       'Class:URP_UserOrg/Attribute:reason+' => 'なぜこの人物がこの組織に属するデータを参照できるのかを説明する', // 'explain why this person is allowed to see the data belonging to this organization',
));

//
// Class: URP_ProfileProjection
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:URP_ProfileProjection' => 'プロファイルプロジェクション???', // 'profile_projection',
					       'Class:URP_ProfileProjection+' => 'プロファイルプロジェクション???', //'profile projections',
					       'Class:URP_ProfileProjection/Attribute:dimensionid' => '次元', //'Dimension',
					       'Class:URP_ProfileProjection/Attribute:dimensionid+' => 'アプリケーション次元', // 'application dimension',
					       'Class:URP_ProfileProjection/Attribute:dimension' => '次元', //'Dimension',
					       'Class:URP_ProfileProjection/Attribute:dimension+' => 'アプリケーション次元', //'application dimension',
					       'Class:URP_ProfileProjection/Attribute:profileid' => 'プロフィール', //'Profile',
	'Class:URP_ProfileProjection/Attribute:profileid+' => 'usage profile???',
					       'Class:URP_ProfileProjection/Attribute:profile' => 'プロフィール', //'Profile',
					       'Class:URP_ProfileProjection/Attribute:profile+' => 'プロフィール名', //'Profile name',
					       'Class:URP_ProfileProjection/Attribute:value' => 'Value式', //'Value expression',
					       'Class:URP_ProfileProjection/Attribute:value+' => '($userを使う)OQL式 | アクセス先 | +attribute code', //'OQL expression (using $user) | constant |  | +attribute code',
					       'Class:URP_ProfileProjection/Attribute:attribute' => '属性', //'Attribute',
	'Class:URP_ProfileProjection/Attribute:attribute+' => 'Target attribute code (optional)',
));

//
// Class: URP_ClassProjection
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:URP_ClassProjection' => 'class_projection',
	'Class:URP_ClassProjection+' => 'クラスの射影???', // 'clas projection', 
	'Class:URP_ClassProjection/Attribute:dimensionid' => '次元', //'Dimension',
	'Class:URP_ClassProjection/Attribute:dimensionid+' => 'アプリケーション次元', //'application dimension',
	'Class:URP_ClassProjection/Attribute:dimension' => '次元', //'Dimension',
	'Class:URP_ClassProjection/Attribute:dimension+' => 'アプリケーション次元', //'application dimension',
	'Class:URP_ClassProjection/Attribute:class' => 'クラス', //'Class',
	'Class:URP_ClassProjection/Attribute:class+' => 'ターゲットクラス', //'Target class',
	'Class:URP_ClassProjection/Attribute:value' => 'Value式???', //'Value expression',
	'Class:URP_ClassProjection/Attribute:value+' => '($this を使った)OQL式 | 定数 | +attribute code', //'OQL expression (using $this) | constant |  | +attribute code',
	'Class:URP_ClassProjection/Attribute:attribute' => '属性', //'Attribute',
	'Class:URP_ClassProjection/Attribute:attribute+' => 'ターゲット属性コード(オプション)', //'Target attribute code (optional)',
));

//
// Class: URP_ActionGrant
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:URP_ActionGrant' => 'アクション権限', //'action_permission',
 					       'Class:URP_ActionGrant+' => 'クラスに対する権限', //'permissions on classes',
					       'Class:URP_ActionGrant/Attribute:profileid' => 'プロファイル', //'Profile',
	'Class:URP_ActionGrant/Attribute:profileid+' => 'usage profile',
					       'Class:URP_ActionGrant/Attribute:profile' => 'プロファイル', //'Profile',
	'Class:URP_ActionGrant/Attribute:profile+' => 'usage profile',
					       'Class:URP_ActionGrant/Attribute:class' => 'クラス', //'Class',
					       'Class:URP_ActionGrant/Attribute:class+' => 'ターゲットクラス', //'Target class',
					       'Class:URP_ActionGrant/Attribute:permission' => '権限', //'Permission',
					       'Class:URP_ActionGrant/Attribute:permission+' => '権限の有無は?', //'allowed or not allowed?',
					       'Class:URP_ActionGrant/Attribute:permission/Value:yes' => 'はい', //'yes',
					       'Class:URP_ActionGrant/Attribute:permission/Value:yes+' => 'はい', //'yes',
					       'Class:URP_ActionGrant/Attribute:permission/Value:no' => 'いいえ', //'no',
					       'Class:URP_ActionGrant/Attribute:permission/Value:no+' => 'いいえ', //'no',
					       'Class:URP_ActionGrant/Attribute:action' => 'アクション', //'Action',
					       'Class:URP_ActionGrant/Attribute:action+' => '指定されたクラスにすべき操作', //'operations to perform on the given class',
));

//
// Class: URP_StimulusGrant
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:URP_StimulusGrant' => 'stimulus_permission',
	'Class:URP_StimulusGrant+' => 'permissions on stimilus in the life cycle of the object',
	'Class:URP_StimulusGrant/Attribute:profileid' => 'プロファイル', //'Profile',
	'Class:URP_StimulusGrant/Attribute:profileid+' => 'usage profile',
	'Class:URP_StimulusGrant/Attribute:profile' => 'プロファイル', //'Profile',
	'Class:URP_StimulusGrant/Attribute:profile+' => 'usage profile',
	'Class:URP_StimulusGrant/Attribute:class' => 'クラス', //'Class',
	'Class:URP_StimulusGrant/Attribute:class+' => 'ターゲットクラス', //'Target class',
	'Class:URP_StimulusGrant/Attribute:permission' => '権限', // 'Permission',
	'Class:URP_StimulusGrant/Attribute:permission+' => '権限の有無?', //'allowed or not allowed?',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes' => 'はい', //'yes',
	'Class:URP_StimulusGrant/Attribute:permission/Value:yes+' => 'はい', //'yes',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no' => 'いいえ', //'no',
	'Class:URP_StimulusGrant/Attribute:permission/Value:no+' => 'いいえ', //'no',
	'Class:URP_StimulusGrant/Attribute:stimulus' => 'Stimulus',
	'Class:URP_StimulusGrant/Attribute:stimulus+' => 'stimulus code',
));

//
// Class: URP_AttributeGrant
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:URP_AttributeGrant' => '権限属性', //'attribute_permission',
					       'Class:URP_AttributeGrant+' => '属性レベルでの権限', //'permissions at the attributes level',
					       'Class:URP_AttributeGrant/Attribute:actiongrantid' => '実行権限', //'Action grant',
					       'Class:URP_AttributeGrant/Attribute:actiongrantid+' => '実行権限', //'action grant',
					       'Class:URP_AttributeGrant/Attribute:attcode' => '属性', //'Attribute',
					       'Class:URP_AttributeGrant/Attribute:attcode+' => '属性コード', //'attribute code',
));

//
// String from the User Interface: menu, messages, buttons, etc...
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Menu:WelcomeMenu' => 'ようこそ', //'Welcome',
					       'Menu:WelcomeMenu+' => 'ようこそ、iTopへ', //'Welcome to iTop',
					       'Menu:WelcomeMenuPage' => 'ようこそ', //'Welcome',
					       'Menu:WelcomeMenuPage+' => 'ようこそ、iTopへ', //'Welcome to iTop',
					       'UI:WelcomeMenu:Title' => 'ようこそ、iTopへ', //'Welcome to iTop',
					       // '<p>iTop is a complete, OpenSource, IT Operational Portal.</p>
					       'UI:WelcomeMenu:LeftBlock' => '<p>iTopは、オープンソースの、これだけで完結したIT業務用ポータルです。</p>
<ul>下記に挙げるものが同梱されています。
<li>IT資産インベントリをドキュメント化、管理を行うための完全なCMDB(コンフィグレーション管理データベース)</li>
<li>IT資産関連で発生した問題のトラッキングとそれに関する議論のためのインシデント管理モジュール</li>
<li>IT資産環境への変更を加える場合のプランニングと変更をトラッキングするための変更管理モジュール</li>
<li>インシデント解決のスピードアップするための既知エラーデータベース</li>
<li>計画停電をすべてドキュメント化し、適切な連絡先に通知するための停電モジュール</li>
<li>IT資産の概観を素早く得るためのダッシュボード</li>
</ul>
<p>すべてのモジュールはそれぞれ独立して別個にセットアップが可能である。</p>',

					       //'<p>iTop is service provider oriented, it allows IT engineers to manage easily multiple customers or organizations.
	'UI:WelcomeMenu:RightBlock' => '<p>iTopはサービスプロバイダ指向であり、ITエンジニアが複数の顧客や組織を簡単に管理できるようになる。
<ul>iTopでは　下記のように、機能豊富なビジネスプロセスを取り揃えた。
<li>効果的なIT資産管理</li>
<li>IT業務の効率化推進</li>
<li>顧客満足度の改善と、経営幹部へ、ビジネスパフォーマンス見える化を提供</li>
</ul>
</p>
<p>iTopは完全にオープンなので、あなたが今使っているIT資産管理インフラとの統合が可能である。</p>
<p>
<ul>この次世代IT資産管理業務ポータルを採用すれば、こんなことが可能になる。
<li>より複雑になりつつある、IT資産環境の管理を確実にする。</li>
<li>自分のペースでITILプロセス実装することができる。</li>
<li>IT資産の中でもっとも重要なアセットである、「ドキュメンテーション」を管理することができる。</li>
</ul>
</p>',
					       'UI:WelcomeMenu:AllOpenRequests' => 'リクエストを開く: %1$d', //'Open requests: %1$d',
					       'UI:WelcomeMenu:MyCalls' => 'マイリクエスト', //'My requests',
					       'UI:WelcomeMenu:OpenIncidents' => 'インシデントを開く: %1$d', //'Open incidents: %1$d',
					       'UI:WelcomeMenu:AllConfigItems' => '設定項目', //'Configuration Items: %1$d',
					       'UI:WelcomeMenu:MyIncidents' => '自分にアサインされたインシデント', //'Incidents assigned to me',
					       'UI:AllOrganizations' => '全組織', //' All Organizations ',
					       'UI:YourSearch' => 'あなたのサーチ', //'Your Search',
					       'UI:LoggedAsMessage' => '%1$s としてログインする', //'Logged in as %1$s',
					       'UI:LoggedAsMessage+Admin' => '%1$s　(管理者)としてログインする', //'Logged in as %1$s (Administrator)',
					       'UI:Button:Logoff' => 'ログオフ', //'Log off',
					       'UI:Button:GlobalSearch' => 'サーチ', //'Search',
					       'UI:Button:Search' => ' サーチ', //' Search ',
					       'UI:Button:Query' => ' クエリ', //' Query ',
					       'UI:Button:Ok' => 'OK', //'Ok',
					       'UI:Button:Cancel' => 'キャンセル', //'Cancel',
					       'UI:Button:Apply' => '適用する', //'Apply',
					       'UI:Button:Back' => ' << 戻る', //' << Back ',
					       'UI:Button:Restart' => ' |<< リスタート', //' |<< Restart ',
					       'UI:Button:Next' => ' 次へ >> ', //' Next >> ',
					       'UI:Button:Finish' => ' 終了 ', //' Finish ',
					       'UI:Button:DoImport' => ' インポート実行! ', //' Run the Import ! ',
					       'UI:Button:Done' => ' 完了 ', //' Done ',
					       'UI:Button:SimulateImport' => ' インポートをシュミレート ', //' Simulate the Import ',
					       'UI:Button:Test' => 'テスト実行!', //'Test!',
					       'UI:Button:Evaluate' => ' 評価 ', //' Evaluate ',
					       'UI:Button:AddObject' => ' 追加...', //' Add... ',
					       'UI:Button:BrowseObjects' => 'ブラウズ...', //' Browse... ',
					       'UI:Button:Add' => ' 追加 ', //' Add ',
					       'UI:Button:AddToList' => ' << 追加 ', //' << Add ',
					       'UI:Button:RemoveFromList' => '削除 >> ', //' Remove >> ',
					       'UI:Button:FilterList' => ' フィルタ... ', //' Filter... ',
					       'UI:Button:Create' => ' 生成 ', //' Create ',
					       'UI:Button:Delete' => ' 削除! ', //' Delete ! ',
					       'UI:Button:ChangePassword' => ' パスワード変更 ', //' Change Password ',
					       'UI:Button:ResetPassword' => 'パスワードリセット ', //' Reset Password ',
	
					       'UI:SearchToggle' => 'サーチ', //'Search',
					       'UI:ClickToCreateNew' => '新規 %1$s を生成', //'Create a new %1$s',
					       'UI:SearchFor_Class' => '%1$s オブジェクトをサーチ', //'Search for %1$s objects',
					       'UI:NoObjectToDisplay' => '表示すべきオブジェクトがありません。', //'No object to display.',
					       'UI:Error:MandatoryTemplateParameter_object_id' => 'link_attrが指定されている時は、object_idパラメータは必須です。表示テンプレートの定義を確認してください。', //'Parameter object_id is mandatory when link_attr is specified. Check the definition of the display template.',
					       'UI:Error:MandatoryTemplateParameter_target_attr' => 'link_attrを指定する場合は、target_attrパラメータは必須です。表示テンプレートの定義を確認してください。', //'Parameter target_attr is mandatory when link_attr is specified. Check the definition of the display template.',
					       'UI:Error:MandatoryTemplateParameter_group_by' => 'group_byパラメータは必須です。表示テンプレートの定義を確認してください。', //'Parameter group_by is mandatory. Check the definition of the display template.',
	'UI:Error:InvalidGroupByFields' => 'Invalid list of fields to group by: "%1$s".',
					       'UI:Error:UnsupportedStyleOfBlock' => 'エラー："%1$s"はサポートされていないブロックスタイルです。', //'Error: unsupported style of block: "%1$s".',
					       'UI:Error:IncorrectLinkDefinition_LinkedClass_Class' => 'リンク定義が正しくありません。管理オブジェクトのクラス：%1Ss は、クラス %2$s クラスの外部キーとして見つかりません。', //'Incorrect link definition: the class of objects to manage: %1$s was not found as an external key in the class %2$s',
					       'UI:Error:Object_Class_Id_NotFound' => 'オブジェクト：%1$s:%2$d が見つかりません。', //'Object: %1$s:%2$d not found.',
					       'UI:Error:WizardCircularReferenceInDependencies' => 'エラー: フィールド間の依存関係に循環参照があります。データモデルを確認してください。', //'Error: Circular reference in the dependencies between the fields, check the data model.',
					       'UI:Error:UploadedFileTooBig' => 'アップロードファイルが大きすぎます(上限は %1$s )。PHPの設定にある、upload_max_filesizeと、post_max_sizeを確認してください。', //'Uploaded file is too big. (Max allowed size is %1$s). Check you PHP configuration for upload_max_filesize and post_max_size.',
	'UI:Error:UploadedFileTruncated.' => 'アップロードファイルが切り捨てられました!', //'Uploaded file has been truncated !',
					       'UI:Error:NoTmpDir' => 'この一時ディレクトリは定義されていません。', //'The temporary directory is not defined.',
					       'UI:Error:CannotWriteToTmp_Dir' => '一時ファイルをディスクに書き込めません。upload_tmp_dir = "%1$s" です。', //'Unable to write the temporary file to the disk. upload_tmp_dir = "%1$s".',
					       'UI:Error:UploadStoppedByExtension_FileName' => 'extensionにより、アップロードを停止しました。(オリジナルのファイル名は"%1$s"です)。', //'Upload stopped  by extension. (Original file name = "%1$s").',
					       'UI:Error:UploadFailedUnknownCause_Code' => 'ファイルのアップロードに失敗しました。原因は不明(エラーコード: "%1$s")です。', //'File upload failed, unknown cause. (Error code = "%1$s").',
	
					       'UI:Error:1ParametersMissing' => 'エラー: この操作には下記のパラメータを指定する必要があります：%1$s', //'Error: the following parameter must be specified for this operation: %1$s.',
					       'UI:Error:2ParametersMissing' => 'エラー：この操作には、下記のパラメータを指定する必要があります：%1$s , %2$s', //'Error: the following parameters must be specified for this operation: %1$s and %2$s.',
					       'UI:Error:3ParametersMissing' => 'エラー：この操作には、下記のパラメータを指定する必要があります：%1$s, %2$s, %3$s', //Error: the following parameters must be specified for this operation: %1$s, %2$s and %3$s.',
					       'UI:Error:4ParametersMissing' => 'エラー：この操作には、下記のパラメータを指定する必要があります：%1$s, %2$s, %3$s,%4$s', //'Error: the following parameters must be specified for this operation: %1$s, %2$s, %3$s and %4$s.',
					       'UI:Error:IncorrectOQLQuery_Message' => 'エラー：OQLクエリが正しくありません：%1$s', //'Error: incorrect OQL query: %1$s',
					       'UI:Error:AnErrorOccuredWhileRunningTheQuery_Message' => 'クエリ；%1$s 実行中にエラーが発生しました。', //'An error occured while running the query: %1$s',
					       'UI:Error:ObjectAlreadyUpdated' => 'エラー：このオブジェクトはすでに更新済みです。', //'Error: the object has already been updated.',
					       'UI:Error:ObjectCannotBeUpdated' => 'エラー：オブジェクトを更新できません。', //'Error: object cannot be updated.',
					       'UI:Error:ObjectsAlreadyDeleted' => 'エラー：オブジェクトは既に削除されています。', //'Error: objects have already been deleted!',
					       'UI:Error:BulkDeleteNotAllowedOn_Class' => '%1$s クラスのオブジェクトに対するバルク削除は許可されていません。', //'You are not allowed to perform a bulk delete of objects of class %1$s',
					       'UI:Error:DeleteNotAllowedOn_Class' => '%1$s クラスのオブジェクトの削除は許可されていません。', //'You are not allowed to delete objects of class %1$s',
					       'UI:Error:BulkModifyNotAllowedOn_Class' => '%1$s クラスのオブジェクトに対するバルクアップデート処理の実行は許可されていません。', //'You are not allowed to perform a bulk update of objects of class %1$s',
					       'UI:Error:ObjectAlreadyCloned' => 'エラー：このオブジェクトはすでに、クローニングされています。', // 'Error: the object has already been cloned!',
					       'UI:Error:ObjectAlreadyCreated' => 'エラー：このオブジェクトは既に生成済みです。', //'Error: the object has already been created!',
	'UI:Error:Invalid_Stimulus_On_Object_In_State' => 'エラー：Error: invalid stimulus "%1$s" on object %2$s in state "%3$s".',
	
	
					       'UI:GroupBy:Count' => 'カウント', //'Count',
					       'UI:GroupBy:Count+' => '要素数', //'Number of elements',
					       'UI:CountOfObjects' => '%1$d 個のオブジェクトが条件にマッチしました。', //'%1$d objects matching the criteria.',
					       'UI_CountOfObjectsShort' => '%1$d オブジェクトです。', //'%1$d objects.',
					       'UI:NoObject_Class_ToDisplay' => '表示できる %1$s はありません。', //'No %1$s to display',
					       'UI:History:LastModified_On_By' => '最終更新日: %1$s ( %2$s )', //'Last modified on %1$s by %2$s.',
					       'UI:HistoryTab' => '履歴', //'History',
					       'UI:NotificationsTab' => '通知', //'Notifications',
					       'UI:History:BulkImports' => '履歴', //'History',
					       'UI:History:BulkImports+' => 'CSVインポートのリスト(last first)', //'List of CSV imports (last first)',
					       'UI:History:BulkImportDetails' => '%2$s により実行された %1$s へのCSVインポート結果の変更???', // 'Changes resulting from the CSV import performed on %1$s (by %2$s)',
					       'UI:History:Date' => '日付',//'Date',
					       'UI:History:Date+' => '更新日時', //'Date of the change',
					       'UI:History:User' => 'ユーザ', //'User',
					       'UI:History:User+' => 'この変更を行ったユーザ', //'User who made the change',
					       'UI:History:Changes' => '変更', //'Changes',
					       'UI:History:Changes+' => 'このオブジェクトを変更する', //'Changes made to the object',
					       'UI:History:StatsCreations' => '生成された', //'Created',
					       'UI:History:StatsCreations+' => '生成されたオブジェクト数', //'Count of objects created',
					       'UI:History:StatsModifs' => '修正された', //'Modified',
					       'UI:History:StatsModifs+' => '修正されたオブジェクト数', //'Count of objects modified',
					       'UI:History:StatsDeletes' => '削除された', //'Deleted',
					       'UI:History:StatsDeletes+' => '削除されたオブジェクト数', //'Count of objects deleted',
					       'UI:Loading' => '読み込み...', //'Loading...',
					       'UI:Menu:Actions' => '実行...', //'Actions',
       'UI:Menu:OtherActions' => '実行...', //'Actions',
					       'UI:Menu:New' => '新規...', //'New...',
					       'UI:Menu:Add' => '追加...', //'Add...',
					       'UI:Menu:Manage' => '管理する...', //'Manage...',
					       'UI:Menu:EMail' => 'Eメール', //'eMail',
					       'UI:Menu:CSVExport' => 'CSVエクスポート', //'CSV Export',
					       'UI:Menu:Modify' => '修正する...', //'Modify...',
					       'UI:Menu:Delete' => '削除する...', //'Delete...',
					       'UI:Menu:Manage' => '管理する...', //'Manage...',
					       'UI:Menu:BulkDelete' => '削除する', //'Delete...',
					       'UI:UndefinedObject' => '未定義', //'undefined',
					       'UI:Document:OpenInNewWindow:Download' => '新規ウィンドウで開く: %1$s, ダウンロード: %2$s', //'Open in new window: %1$s, Download: %2$s',
					       'UI:SelectAllToggle+' => 'すべて選択 / すべて非選択', //'Select / Deselect All',
	'UI:TruncatedResults' => '%1$d objects displayed out of %2$d',
					       'UI:DisplayAll' => 'すべて表示', //'Display All',
					       'UI:CollapseList' => '折り畳む', //'Collapse',
					       'UI:CountOfResults' => '%1$d オブジェクト', //'%1$d object(s)',
					       'UI:ChangesLogTitle' => '変更履歴(%1$d)', //'Changes log (%1$d):',
					       'UI:EmptyChangesLogTitle' => '変更履歴は空です。', //'Changes log is empty',
					       'UI:SearchFor_Class_Objects' => '%1$s オブジェクトを検索', //'Search for %1$s Objects',
					       'UI:OQLQueryBuilderTitle' => 'OQLクエリビルダ', //'OQL Query Builder',
					       'UI:OQLQueryTab' => 'OQLクエリ', //'OQL Query',
					       'UI:SimpleSearchTab' => '単純検索', //'Simple Search',
					       'UI:Details+' => '詳細情報', //'Details',
					       'UI:SearchValue:Any' => '* 任意 *', //'* Any *',
					       'UI:SearchValue:Mixed' => '* 混成 *', //'* mixed *',
					       'UI:SelectOne' => '-- 選んでください --', //'-- select one --',
					       'UI:Login:Welcome' => 'iTopへようこそ', //'Welcome to iTop!',
					       'UI:Login:IncorrectLoginPassword' => 'ログイン/パスワードが正しくありません。再度ログインしてください。', //'Incorrect login/password, please try again.',
					       'UI:Login:IdentifyYourself' => '続けて作業を行う前に認証を受けてください。', //'Identify yourself before continuing',
					       'UI:Login:UserNamePrompt' => 'ユーザ名', //'User Name',
					       'UI:Login:PasswordPrompt' => 'パスワード', //'Password',
					       'UI:Login:ChangeYourPassword' => 'パスワードを変更してください', //'Change Your Password',
					       'UI:Login:OldPasswordPrompt' => '既存パスワード',//'Old password',
					       'UI:Login:NewPasswordPrompt' => '新規パスワード', //'New password',
					       'UI:Login:RetypeNewPasswordPrompt' => '新規パスワードを再度入力してください。', //'Retype new password',
					       'UI:Login:IncorrectOldPassword' => 'エラー：既存パスワードが正しくありません。', //'Error: the old password is incorrect',
					       'UI:LogOffMenu' => 'ログオフ', //'Log off',
					       'UI:LogOff:ThankYou' => 'iTopをご利用いただき、ありがとうございます。', //'Thank you for using iTop',
					       'UI:LogOff:ClickHereToLoginAgain' => '再度ログインするにはここをクリックしてください...', //'Click here to login again...',
					       'UI:ChangePwdMenu' => 'パスワードを変更する...', //'Change Password...',
					       'UI:AccessRO-All' => 'iTopは参照のみ有効です。', //'iTop is read-only',
					       'UI:AccessRO-Users' => 'エンドユーザの方はiTopは参照のみ有効です。', //'iTop is read-only for end-users',
					       'UI:Login:RetypePwdDoesNotMatch' => '2度入力された新規パスワードが一致しません!', //'New password and retyped new password do not match !',
					       'UI:Button:Login' => 'iTopへ入る', //'Enter iTop',
					       'UI:Login:Error:AccessRestricted' => 'iTopへのアクセスは制限されています。iTop管理者に問い合わせしてください。', //'iTop access is restricted. Please, contact an iTop administrator.',
					       'UI:Login:Error:AccessAdmin' => '管理者権限をもつユーザにアクセスが制限されています。iTop管理者に問い合わせしてください。', //'Access restricted to people having administrator privileges. Please, contact an iTop administrator.',
					       'UI:CSVImport:MappingSelectOne' => '-- 選択してください --', //'-- select one --',
					       'UI:CSVImport:MappingNotApplicable' => '--このフィールドを無視する --', //'-- ignore this field --',
					       'UI:CSVImport:NoData' => 'データが空です..., データを指定してください。', // 'Empty data set..., please provide some data!',
					       'UI:Title:DataPreview' => 'データプレビュー', //'Data Preview',
					       'UI:CSVImport:ErrorOnlyOneColumn' => 'エラー：このデータにはカラムが1つしか含まれていません。適切なセパレータ文字を選択しましたか?', //'Error: The data contains only one column. Did you select the appropriate separator character?',
					       'UI:CSVImport:FieldName' => 'フィールド： %1$d', //'Field %1$d',
					       'UI:CSVImport:DataLine1' => 'データ行 1', //'Data Line 1',
					       'UI:CSVImport:DataLine2' => 'データ行 2', //'Data Line 2',
					       'UI:CSVImport:idField' => 'ID (プライマリキー)', //'id (Primary Key)',
					       'UI:Title:BulkImport' => 'iTop - バルクインポート', //'iTop - Bulk import',
					       'UI:Title:BulkImport+' => 'CSV インポートウィザード', //'CSV Import Wizard',
					       'UI:Title:BulkSynchro_nbItem_ofClass_class' => '%2$s クラスの %1$d オブジェクトを同期', //'Synchronization of %1$d objects of class %2$s',
					       'UI:CSVImport:ClassesSelectOne' => '--選択してください --',  //'-- select one --',
					       'UI:CSVImport:ErrorExtendedAttCode' => '内部エラー： "%2$s" は"%3$s"クラスの外部キーではないので、"%1$s" は正しくないコードです。', // 'Internal error: "%1$s" is an incorrect code because "%2$s" is NOT an external key of the class "%3$s"',
					       'UI:CSVImport:ObjectsWillStayUnchanged' => '%1$d オブジェクトが変更されないままです。', //'%1$d objects(s) will stay unchanged.',
					       'UI:CSVImport:ObjectsWillBeModified' => '%1$d オブジェクトが修正されます。', //'%1$d objects(s) will be modified.',
					       'UI:CSVImport:ObjectsWillBeAdded' => '%1$d オブジェクトが追加されます。', //'%1$d objects(s) will be added.',
					       'UI:CSVImport:ObjectsWillHaveErrors' => '%1$d オブジェクトにエラーがあります。', //'%1$d objects(s) will have errors.',
					       'UI:CSVImport:ObjectsRemainedUnchanged' => '%1$d オブジェクトは変更されていません。', //'%1$d objects(s) remained unchanged.',
					       'UI:CSVImport:ObjectsWereModified' => '%1$d オブジェクトが変更されました。', //'%1$d objects(s) were modified.',
					       'UI:CSVImport:ObjectsWereAdded' => '%1$d オブジェクトが追加されました。', //'%1$d objects(s) were added.',
					       'UI:CSVImport:ObjectsHadErrors' => '%1$s オブジェクトにエラーがあります。', //'%1$d objects(s) had errors.',
					       'UI:Title:CSVImportStep2' => 'ステップ2/5: CSVデータオプション', //'Step 2 of 5: CSV data options',
					       'UI:Title:CSVImportStep3' => 'ステップ3/5: データマッピング', //'Step 3 of 5: Data mapping',
					       'UI:Title:CSVImportStep4' => 'ステップ4/5: インポートシミュレーション', //'Step 4 of 5: Import simulation',
					       'UI:Title:CSVImportStep5' => 'ステップ5/5: インポート完了', //'Step 5 of 5: Import completed',
					       'UI:CSVImport:LinesNotImported' => 'ロードできなかった行：', //'Lines that could not be loaded:',
					       'UI:CSVImport:LinesNotImported+' => '下記の行はエラーが含まれていたのでインポートされませんでした。', //'The following lines have not been imported because they contain errors',
					       'UI:CSVImport:SeparatorComma+' => ', (コンマ)', //', (comma)',
					       'UI:CSVImport:SeparatorSemicolon+' => '; (セミコロン)', //'; (semicolon)',
					       'UI:CSVImport:SeparatorTab+' => 'タブ', //'tab',
					       'UI:CSVImport:SeparatorOther' => 'その他:', //'other:',
					       'UI:CSVImport:QualifierDoubleQuote+' => '" (ダブルクォート)', //'" (double quote)',
					       'UI:CSVImport:QualifierSimpleQuote+' => '\' (シングルクォート)', //'\' (simple quote)',
					       'UI:CSVImport:QualifierOther' => 'その他：', //'other:',
					       'UI:CSVImport:TreatFirstLineAsHeader' => '1行めをヘッダ(カラム名)として扱う', // 'Treat the first line as a header (column names)',
					       'UI:CSVImport:Skip_N_LinesAtTheBeginning' => 'ファイル冒頭の%1$s 行をスキップする', //'Skip %1$s line(s) at the beginning of the file',
					       'UI:CSVImport:CSVDataPreview' => 'CSVデータプレビュー', //'CSV Data Preview',
					       'UI:CSVImport:SelectFile' => 'インポートするファイルを選択してください:', //'Select the file to import:',
					       'UI:CSVImport:Tab:LoadFromFile' => 'ファイルからロードしてください', //'Load from a file',
					       'UI:CSVImport:Tab:CopyPaste' => 'データをコピーペーストしてください', //'Copy and paste data',
					       'UI:CSVImport:Tab:Templates' => 'テンプレート', //'Templates',
					       'UI:CSVImport:PasteData' => 'インポートするデータをペーストしてください', //'Paste the data to import:',
					       'UI:CSVImport:PickClassForTemplate' => 'ダウンロードするテンプレートを選んでください', //'Pick the template to download: ',
					       'UI:CSVImport:SeparatorCharacter' => 'セパレータ文字', //'Separator character:',
					       'UI:CSVImport:TextQualifierCharacter' => 'テキスト識別文字', //'Text qualifier character',
					       'UI:CSVImport:CommentsAndHeader' => 'コメントとヘッダ', //'Comments and header',
					       'UI:CSVImport:SelectClass' => 'インポートするクラスを選択してください', //'Select the class to import:',
					       'UI:CSVImport:AdvancedMode' => '拡張モード', //'Advanced mode',
					       'UI:CSVImport:AdvancedMode+' => '拡張モードでは、オブジェクトに付与されている"id"(プライマリキー)がオブジェクトの更新、リネームに指定可能です。' . //In advanced mode the "id" (primary key) of the objects can be used to update and rename objects.' .
					       'しかしながら、"id"カラムは(たとえ存在しても)検索条件として指定できるのみであり、他の検索条件と組み合わせて利用することはできません。', //'However the column "id" (if present) can only be used as a search criteria and can not be combined with any other search criteria.',
					       'UI:CSVImport:SelectAClassFirst' => 'マッピングを設定するには、まず最初にクラスを選択してください。', //'To configure the mapping, select a class first.',
					       'UI:CSVImport:HeaderFields' => 'フィールド', //'Fields',
					       'UI:CSVImport:HeaderMappings' => 'マッピング', //'Mappings',
					       'UI:CSVImport:HeaderSearch' => '検索しますか?', //'Search?',
					       'UI:CSVImport:AlertIncompleteMapping' => 'すべてのフィールドのマッピングを選択してください。', //'Please select a mapping for every field.',
					       'UI:CSVImport:AlertNoSearchCriteria' => '少なくとも1つ以上の検索条件を選択してください。', //'Please select at least one search criteria',
					       'UI:CSVImport:Encoding' => '文字エンコーディング', //'Character encoding',	
					       'UI:UniversalSearchTitle' => 'iTop - ユニバーサルサーチ', //'iTop - Universal Search',
					       'UI:UniversalSearch:Error' => 'エラー：%1$s', //'Error: %1$s',
					       'UI:UniversalSearch:LabelSelectTheClass' => '検索するクラスを選択してください。', //'Select the class to search: ',
	
					       'UI:Audit:Title' => 'iTop - CMDB 監査', //'iTop - CMDB Audit',
					       'UI:Audit:InteractiveAudit' => '対話型監査', //'Interactive Audit',
					       'UI:Audit:HeaderAuditRule' => '監査ルール', //'Audit Rule',
					       'UI:Audit:HeaderNbObjects' => 'オブジェクト数', //'# Objects',
					       'UI:Audit:HeaderNbErrors' => 'エラー数', //'# Errors',
					       'UI:Audit:PercentageOk' => '% OK', //'% Ok',
	
					       'UI:RunQuery:Title' => 'iTop - OQLクエリ評価', //'iTop - OQL Query Evaluation',
					       'UI:RunQuery:QueryExamples' => 'クエリの例', //'Query Examples',
					       'UI:RunQuery:HeaderPurpose' => '目的', //'Purpose',
					       'UI:RunQuery:HeaderPurpose+' => 'クエリについての説明', //'Explanation about the query',
					       'UI:RunQuery:HeaderOQLExpression' => 'OQL式', //'OQL Expression',
					       'UI:RunQuery:HeaderOQLExpression+' => 'OQL文法によるクエリ', //'The query in OQL syntax',
					       'UI:RunQuery:ExpressionToEvaluate' => '評価式', //'Expression to evaluate: ',
					       'UI:RunQuery:MoreInfo' => '本クエリに関する詳細情報', //'More information about the query: ',
					       'UI:RunQuery:DevelopedQuery' => 'クエリ式の再開発', //'Redevelopped query expression: ',
					       'UI:RunQuery:SerializedFilter' => '序列化フィルタ：', //'Serialized filter: ',
					       'UI:RunQuery:Error' => '本クエリ実行時にエラーが発生しました：%1$s', //'An error occured while running the query: %1$s',
	
					       'UI:Schema:Title' => 'iTop オブジェクトスキーマ', //'iTop objects schema',
					       'UI:Schema:CategoryMenuItem' => 'カテゴリ <b>%1$s</b>', //'Category <b>%1$s</b>',
					       'UI:Schema:Relationships' => '関連', //'Relationships',
					       'UI:Schema:AbstractClass' => '抽象クラス：このクラスのインスタンスを生成することはできません。', //'Abstract class: no object from this class can be instantiated.',
					       'UI:Schema:NonAbstractClass' => '非抽象クラス：このクラスのインスタンスを生成できます。', //'Non abstract class: objects from this class can be instantiated.',
					       'UI:Schema:ClassHierarchyTitle' => 'クラス階層', //'Class hierarchy',
					       'UI:Schema:AllClasses' => '全クラス', //'All classes',
					       'UI:Schema:ExternalKey_To' => '%1$s の外部キー', //'External key to %1$s',
					       'UI:Schema:Columns_Description' => 'カラム： <em>%1$s</em>', //'Columns: <em>%1$s</em>',
					       'UI:Schema:Default_Description' => 'デフォルト： "%1$s"', //'Default: "%1$s"',
					       'UI:Schema:NullAllowed' => 'Null許容', //'Null Allowed',
					       'UI:Schema:NullNotAllowed' => 'Null 非許容', //'Null NOT Allowed',
					       'UI:Schema:Attributes' => '属性', //'Attributes',
					       'UI:Schema:AttributeCode' => '属性コード', //'Attribute Code',
					       'UI:Schema:AttributeCode+' => '属性の内部コード', //'Internal code of the attribute',
					       'UI:Schema:Label' => 'ラベル', //'Label',
					       'UI:Schema:Label+' => '属性のラベル', //'Label of the attribute',
					       'UI:Schema:Type' => '型', //'Type',
	
					       'UI:Schema:Type+' => '属性のデータ型', //'Data type of the attribute',
					       'UI:Schema:Origin' => 'オリジン', //'Origin',
					       'UI:Schema:Origin+' => 'この属性が定義されているベースクラス', //'The base class in which this attribute is defined',
					       'UI:Schema:Description' => '概要', //'Description',
					       'UI:Schema:Description+' => '本属性の概要', //'Description of the attribute',
					       'UI:Schema:AllowedValues' => '取りうる値', //'Allowed values',
					       'UI:Schema:AllowedValues+' => '本属性で取りうる値の制限', //'Restrictions on the possible values for this attribute',
					       'UI:Schema:MoreInfo' => '詳細情報', //'More info',
					       'UI:Schema:MoreInfo+' => 'データベースに定義された本フィールドの詳細情報', //'More information about the field defined in the database',
					       'UI:Schema:SearchCriteria' => '検索条件', //'Search criteria',
					       'UI:Schema:FilterCode' => 'フィルタコード', //'Filter code',
					       'UI:Schema:FilterCode+' => '本検索条件のコード', //'Code of this search criteria',
					       'UI:Schema:FilterDescription' => '概要', //'Description',
					       'UI:Schema:FilterDescription+' => '本検索条件の概要', //'Description of this search criteria',
					       'UI:Schema:AvailOperators' => '利用可能な演算子', //'Available operators',
					       'UI:Schema:AvailOperators+' => '本検索条件で利用可能な演算子', //'Possible operators for this search criteria',
					       'UI:Schema:ChildClasses' => '子クラス', //'Child classes',
					       'UI:Schema:ReferencingClasses' => '参照クラス', //'Referencing classes',
					       'UI:Schema:RelatedClasses' => '関係するクラス', //'Related classes',
					       'UI:Schema:LifeCycle' => 'ライフサイクル', //'Life cycle',
					       'UI:Schema:Triggers' => 'トリガ', //'Triggers',
					       'UI:Schema:Relation_Code_Description' => 'リレーション <em>%1$s</em> (%2$s)', //'Relation <em>%1$s</em> (%2$s)',
					       'UI:Schema:RelationDown_Description' => '下へ: %1$s', //'Down: %1$s',
					       'UI:Schema:RelationUp_Description' => '上へ: %1$s', //'Up: %1$s',
					       'UI:Schema:RelationPropagates' => '%1$s: %2$d レベルへ伝播、クエリ：%3$s', //'%1$s: propagate to %2$d levels, query: %3$s',
					       'UI:Schema:RelationDoesNotPropagate' => '%1$s: 伝播しない (%2$d レベル), クエリ: %3$s', //'%1$s: does not propagates (%2$d levels), query: %3$s',
					       'UI:Schema:Class_ReferencingClasses_From_By' => '%1$s は%2$s クラスから %3$s フィールドにより参照されている', //'%1$s is referenced by the class %2$s via the field %3$s',
					       'UI:Schema:Class_IsLinkedTo_Class_Via_ClassAndAttribute' => '%1$s は %3$s::<em>%4$s</em>により%2$s へリンクされています。', //'%1$s is linked to %2$s via %3$s::<em>%4$s</em>',
					       'UI:Schema:Links:1-n' => 'クラスは%1$sへポイントしています。(1:n リンク)', //'Classes pointing to %1$s (1:n links):',
					       'UI:Schema:Links:n-n' => 'クラスは%1$sへリンクしています。(n:n リンク)', //'Classes linked to %1$s (n:n links):',
					       'UI:Schema:Links:All' => '関連する全クラスのグラフ表示', //'Graph of all related classes',
					       'UI:Schema:NoLifeCyle' => 'このクラスにはライフサイクルが定義されていません。', //'There is no life cycle defined for this class.',
					       'UI:Schema:LifeCycleTransitions' => 'トランジション', //'Transitions',
					       'UI:Schema:LifeCyleAttributeOptions' => '属性オプション', //'Attribute options',
					       'UI:Schema:LifeCycleHiddenAttribute' => '隠し', //'Hidden',
					       'UI:Schema:LifeCycleReadOnlyAttribute' => '参照限定',// 'Read-only',
					       'UI:Schema:LifeCycleMandatoryAttribute' => '必須', //'Mandatory',
					       'UI:Schema:LifeCycleAttributeMustChange' => '変更必須', //'Must change',
					       'UI:Schema:LifeCycleAttributeMustPrompt' => 'ユーザはこの値を変更するよう、促されます。', //'User will be prompted to change the value',
					       'UI:Schema:LifeCycleEmptyList' => '空リスト', //'empty list',
	
					       'UI:LinksWidget:Autocomplete+' => '最初の3文字をタイプしてください...', //'Type the first 3 characters...',
					       'UI:Combo:SelectValue' => '--- 値を選んでください ---', //'--- select a value ---',
					       'UI:Label:SelectedObjects' => '選択されたオブジェクト: ', //'Selected objects: ',
					       'UI:Label:AvailableObjects' => '選択可能なオブジェクト: ', //'Available objects: ',
					       'UI:Link_Class_Attributes' => '%1$s 属性', //'%1$s attributes',
					       'UI:SelectAllToggle+' => '全部を選択 / 全部を非選択', //'Select All / Deselect All',
					       'UI:AddObjectsOf_Class_LinkedWith_Class_Instance' => '%2$s にリンクされた%1$sオブジェクトを追加：%3$s', //'Add %1$s objects linked with %2$s: %3$s',
					       'UI:AddObjectsOf_Class_LinkedWith_Class' => '%1$s オブジェクトを%2$sとのリンクに追加', //'Add %1$s objects to link with the %2$s',
					       'UI:ManageObjectsOf_Class_LinkedWith_Class_Instance' => '%2$s とりんくされた%1$sオブジェクトを管理する: %3$s', //'Manage %1$s objects linked with %2$s: %3$s',
					       'UI:AddLinkedObjectsOf_Class' => '%1$s を追加...', //'Add %1$ss...',
					       'UI:RemoveLinkedObjectsOf_Class' => '選択したオブジェクトを除外', //'Remove selected objects',
					       'UI:Message:EmptyList:UseAdd' => 'リストは空です。"追加..."ボタンを利用して要素を追加してください。', //'The list is empty, use the "Add..." button to add elements.',
					       'UI:Message:EmptyList:UseSearchForm' => '上の検索フォームを使って追加するオブジェクトを検索してください。', //'Use the search form above to search for objects to be added.',
	
					       'UI:Wizard:FinalStepTitle' => '最終ステップ：コンファーム', //'Final step: confirmation',
					       'UI:Title:DeletionOf_Object' => '%1$sの削除', //'Deletion of %1$s',
					       'UI:Title:BulkDeletionOf_Count_ObjectsOf_Class' => '%2$s クラスの%1$d個のオブジェクトをバルク削除', //'Bulk deletion of %1$d objects of class %2$s',
					       'UI:Delete:NotAllowedToDelete' => 'このオブジェクトを削除する権限がありません。', //'You are not allowed to delete this object',
					       'UI:Delete:NotAllowedToUpdate_Fields' => '以下のフィールドを更新する権限が与えられていません: %1$s', //'You are not allowed to update the following field(s): %1$s',
					       'UI:Error:NotEnoughRightsToDelete' => 'カレントユーザは十分な権限を持っていないので、このオブジェクトは削除することができません。', //'This object could not be deleted because the current user do not have sufficient rights',
					       'UI:Error:CannotDeleteBecauseOfDepencies' => 'いくつかのマニュアル操作を先に実装する必要があるので、このオブジェクトは削除できません。', //'This object could not be deleted because some manual operations must be performed prior to that',
					       'UI:Archive_User_OnBehalfOf_User' => '%2$s を代表して %1$s', // '%1$s on behalf of %2$s',
					       'UI:Delete:AutomaticallyDeleted' => '自動的に削除されました。', //'automatically deleted',
					       'UI:Delete:AutomaticResetOf_Fields' => 'フィールドの自動リセット: %1$s', //'automatic reset of field(s): %1$s',
					       'UI:Delete:CleaningUpRefencesTo_Object' => '%1$s への参照すべてをクリア', //'Cleaning up all references to %1$s...',
					       'UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class' => '%2$s クラスの　%1$d個のオブジェクトへの参照をすべてクリア', //'Cleaning up all references to %1$d objects of class %2$s...',
					       'UI:Delete:Done+' => '実行しました...???', //'What was done...',
					       'UI:Delete:_Name_Class_Deleted' => '%1$s - %2$s 削除しました。', //'%1$s - %2$s deleted.',
					       'UI:Delete:ConfirmDeletionOf_Name' => '%1$s の削除', //'Deletion of %1$s',
					       'UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class' => '%2$sクラスの%1$dオブジェクトの削除', //'Deletion of %1$d objects of class %2$s',
					       'UI:Delete:ShouldBeDeletedAtomaticallyButNotAllowed' => '自動的に削除されるべきだが、そのための権限がありません。', //'Should be automaticaly deleted, but you are not allowed to do so',
					       'UI:Delete:MustBeDeletedManuallyButNotAllowed' => '手動で削除されるべきだが、このオブジェクトを削除するための権限がありません。アプリケーション管理者に問い合わせてください。', //'Must be deleted manually - but you are not allowed to delete this object, please contact your application admin',
					       'UI:Delete:WillBeDeletedAutomatically' => '自動的に削除されます。', //'Will be automaticaly deleted',
					       'UI:Delete:MustBeDeletedManually' => '手動で削除されるべきです。', //'Must be deleted manually',
					       'UI:Delete:CannotUpdateBecause_Issue' => '自動的に更新されるべきだが: %1$s', //'Should be automatically updated, but: %1$s',
					       'UI:Delete:WillAutomaticallyUpdate_Fields' => 'は自動的に更新されます。(reset: %1$s)', //'will be automaticaly updated (reset: %1$s)',
					       'UI:Delete:Count_Objects/LinksReferencing_Object' => '%1$dオブジェクト/リンクは%2$sを参照しています。', //'%1$d objects/links are referencing %2$s',
					       'UI:Delete:Count_Objects/LinksReferencingTheObjects' => '%1$dオブジェクト/リンクは削除されるべきオブジェクトを参照しています。', //'%1$d objects/links are referencing some of the objects to be deleted',	
					       'UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity' => 'データベース一貫性を確実にするために、いくつかの参照を除去する必要があります。', //'To ensure Database integrity, any reference should be further eliminated',
	'UI:Delete:Consequence+' => 'What will be done',
					       'UI:Delete:SorryDeletionNotAllowed' => '申し訳ございません。このオブジェクトを削除する権限がありません。上述の詳細説明を参照してください。', //'Sorry, you are not allowed to delete this object, see the detailed explanations above',
					       'UI:Delete:PleaseDoTheManualOperations' => '本オブジェクトの削除を要求する前に、上記にリストされている操作を手動で行ってください。', //'Please perform the manual operations listed above prior to requesting the deletion of this object',
					       'UI:Delect:Confirm_Object' => '%1$sを削除しようとしています。確認してください。', //'Please confirm that you want to delete %1$s.',
					       'UI:Delect:Confirm_Count_ObjectsOf_Class' => '以下の%2$sクラスの%1$dオブジェクトを削除しようとしています。確認してください。', //'Please confirm that you want to delete the following %1$d objects of class %2$s.',
					       'UI:WelcomeToITop' => 'iTopへようこそ', //'Welcome to iTop',
					       'UI:DetailsPageTitle' => 'iTop - %1$s - %2$sの詳細', //'iTop - %1$s - %2$s details',
					       'UI:ErrorPageTitle' => 'iTop - エラー', //'iTop - Error',
					       'UI:ObjectDoesNotExist' => '申し訳ございません。このオブジェクトは既に存在しません。(あるいは参照する権限がありません。)', //'Sorry, this object does not exist (or you are not allowed to view it).',
					       'UI:SearchResultsPageTitle' => 'iTop - 検索結果', //'iTop - Search Results',
					       'UI:Search:NoSearch' => '検索するものがありません。', //'Nothing to search for',
					       'UI:FullTextSearchTitle_Text' => '"%1$s"の結果：', //'Results for "%1$s":',
					       'UI:Search:Count_ObjectsOf_Class_Found' => '%2$sクラスの%1$dオブジェクトが見つかりました。', //'%1$d object(s) of class %2$s found.',
					       'UI:Search:NoObjectFound' => 'オブジェクトが見つかりませんでした。', //'No object found.',
					       'UI:ModificationPageTitle_Object_Class' => 'iTop - %1$s - %2$s 修正？？？', //'iTop - %1$s - %2$s modification',
					       'UI:ModificationTitle_Class_Object' => '%1$sの修正： <span class=\"hilite\">%2$s</span>', //'Modification of %1$s: <span class=\"hilite\">%2$s</span>',
					       'UI:ClonePageTitle_Object_Class' => 'iTop - クローン%1$s - %2$s 修正？？？', //'iTop - Clone %1$s - %2$s modification',
					       'UI:CloneTitle_Class_Object' => '%1$sのクローン：<span class=\"hilite">%2$s</span>', //'Clone of %1$s: <span class=\"hilite\">%2$s</span>',
					       'UI:CreationPageTitle_Class' => 'iTop - 新規%1$sを生成', //'iTop - Creation of a new %1$s ',
					       'UI:CreationTitle_Class' => '新規%1$sの生成', //'Creation of a new %1$s',
					       'UI:SelectTheTypeOf_Class_ToCreate' => '生成する%1$sの型を選択', //'Select the type of %1$s to create:',
					       'UI:Class_Object_NotUpdated' => '変更は検出されませんでした。%1$sは修正されて<strong>いません</strong>', //'No change detected, %1$s (%2$s) has <strong>not</strong> been modified.',
					       'UI:Class_Object_Updated' => '%1$s (%2$s) は更新されました。', //'%1$s (%2$s) updated.',
					       'UI:BulkDeletePageTitle' => 'iTop - バルク削除', //'iTop - Bulk Delete',
					       'UI:BulkDeleteTitle' => '削除するオブジェクトを選択してください。', //'Select the objects you want to delete:',
					       'UI:PageTitle:ObjectCreated' => 'iTopオブジェクトが生成されました。', //'iTop Object Created.',
					       'UI:Title:Object_Of_Class_Created' => '%1$s - %2$s が生成されました。', //'%1$s - %2$s created.',
					       'UI:Apply_Stimulus_On_Object_In_State_ToTarget_State' => '状態%3$sにある%1$sを状態%4$s状態をターゲットに、オブジェクト：%2$sに適用します。', //'Applying %1$s on object: %2$s in state %3$s to target state: %4$s.',
					       'UI:ObjectCouldNotBeWritten' => 'そのオブジェクトは書き込みできません: %1$s', //'The object could not be written: %1$s',
					       'UI:PageTitle:FatalError' => 'iTop - 致命的エラー', // 'iTop - Fatal Error',
					       'UI:SystemIntrusion' => 'アクセスできません。権限のない操作を行おうとしています。', //'Access denied. You have trying to perform an operation that is not allowed for you.',
					       'UI:FatalErrorMessage' => '致命的エラー、iTopは処理を継続できません。', //'Fatal error, iTop cannot continue.',
					       'UI:Error_Details' => 'エラー：%1$s', //'Error: %1$s.',

					       'UI:PageTitle:ClassProjections'	=> 'iTop ユーザ管理', //'iTop user management - class projections',
					       'UI:PageTitle:ProfileProjections' => 'iTop ユーザ管理 - プロファイル立案', //'iTop user management - profile projections',
					       'UI:UserManagement:Class' => 'クラス', //'Class',
					       'UI:UserManagement:Class+' => 'オブジェクトのクラス', //'Class of objects',
					       'UI:UserManagement:ProjectedObject' => 'オブジェクト', //'Object',
	'UI:UserManagement:ProjectedObject+' => 'Projected object',
					       'UI:UserManagement:AnyObject' => '* 任意 *', //'* any *',
					       'UI:UserManagement:User' => 'ユーザ', //'User',
	'UI:UserManagement:User+' => 'User involved in the projection',
					       'UI:UserManagement:Profile' => 'プロファイル', //'Profile',
	'UI:UserManagement:Profile+' => 'Profile in which the projection is specified',
					       'UI:UserManagement:Action:Read' => '読み込み', //'Read',
					       'UI:UserManagement:Action:Read+' => 'オブジェクトの読み込み/表示', //'Read/display objects',
					       'UI:UserManagement:Action:Modify' => '修正', //'Modify',
					       'UI:UserManagement:Action:Modify+' => 'オブジェクトの生成、編集(修正)', //'Create and edit (modify) objects',
					       'UI:UserManagement:Action:Delete' => '削除', //'Delete',
					       'UI:UserManagement:Action:Delete+' => 'オブジェクトの削除', //'Delete objects',
					       'UI:UserManagement:Action:BulkRead' => '一括読み出し(エクスポート)', //'Bulk Read (Export)',
	'UI:UserManagement:Action:BulkRead+' =>  'オブジェクトのリスト表示、もしくは一括エクスポート', // 'List objects or export massively',
	'UI:UserManagement:Action:BulkModify' => '一括修正', // 'Bulk Modify',
	'UI:UserManagement:Action:BulkModify+' => '一括生成/編集(CVSインポート)', //'Massively create/edit (CSV import)',
	'UI:UserManagement:Action:BulkDelete' => '一括削除', //'Bulk Delete',
	'UI:UserManagement:Action:BulkDelete+' => '複数オブジェクトをまとめて削除', //'Massively delete objects',
	'UI:UserManagement:Action:Stimuli' => 'Stimuli',
	'UI:UserManagement:Action:Stimuli+' => '許可されている(複合)アクション', //'Allowed (compound) actions',
	'UI:UserManagement:Action' => 'アクション', // 'Action',
	'UI:UserManagement:Action+' => 'ユーザが実行したアクション', // 'Action performed by the user',
	'UI:UserManagement:TitleActions' => 'アクション', //'Actions',
	'UI:UserManagement:Permission' => 'パーミッション', //'Permission',
	'UI:UserManagement:Permission+' => 'ユーザのパーミッション', // 'User\'s permissions',
	'UI:UserManagement:Attributes' => '属性', // 'Attributes',
	'UI:UserManagement:ActionAllowed:Yes' => 'はい', //'Yes',
	'UI:UserManagement:ActionAllowed:No' => 'いいえ', //'No',
	'UI:UserManagement:AdminProfile+' => '管理者にはデータベース中の全てのオブジェクトに対する読み/書きの全権限が与えられます。', //'Administrators have full read/write access to all objects in the database.',
	'UI:UserManagement:NoLifeCycleApplicable' => 'N/A',
	'UI:UserManagement:NoLifeCycleApplicable+' => 'この暮らすにはライフサイクルは定義されていません。', //'No lifecycle has been defined for this class',
	'UI:UserManagement:GrantMatrix' => '権限マトリクス', //'Grant Matrix',
	'UI:UserManagement:LinkBetween_User_And_Profile' => '%1$s と %2$s間のリンク', //'Link between %1$s and %2$s',
	'UI:UserManagement:LinkBetween_User_And_Org' => '%1$s と %2$s 間のリンク', // 'Link between %1$s and %2$s',
	
	'Menu:AdminTools' => '管理ツール', //'Admin tools',
	'Menu:AdminTools+' => '管理ツール', //'Administration tools',
	'Menu:AdminTools?' => 'このツールは管理者プロファイルが設定されているユーザにのみアクセスが可能です。', //'Tools accessible only to users having the administrator profile',

	'UI:ChangeManagementMenu' => '変更管理', //'Change Management',
	'UI:ChangeManagementMenu+' => '変更管理', //'Change Management',
	'UI:ChangeManagementMenu:Title' => '変更状況概観', //'Changes Overview',
	'UI-ChangeManagementMenu-ChangesByType' => '型別変更内容', //'Changes by type',
	'UI-ChangeManagementMenu-ChangesByStatus' => '状態別変更内容', //'Changes by status',
	'UI-ChangeManagementMenu-ChangesByWorkgroup' => 'ワークグループ別変更内容', //'Changes by workgroup',
	'UI-ChangeManagementMenu-ChangesNotYetAssigned' => 'まだアサインされていない変更', //'Changes not yet assigned',

	'UI:ConfigurationItemsMenu'=> '設定項目', //'Configuration Items',
	'UI:ConfigurationItemsMenu+'=> 'すべてのデバイス', //'All Devices',
	'UI:ConfigurationItemsMenu:Title' => '設定項目概観', //'Configuration Items Overview',
	'UI-ConfigurationItemsMenu-ServersByCriticity' => 'サーバ(by criticity)', // 'Servers by criticity',
	'UI-ConfigurationItemsMenu-PCsByCriticity' => 'PC (by criticity)', // 'PCs by criticity',
	'UI-ConfigurationItemsMenu-NWDevicesByCriticity' => 'ネットワークデバイス (by criticity)', // 'Network devices by criticity',
	'UI-ConfigurationItemsMenu-ApplicationsByCriticity' => 'アプリケーション (by criticity)', // 'Applications by criticity',
	
	'UI:ConfigurationManagementMenu' => 'コンフィグレーション管理', //'Configuration Management',
	'UI:ConfigurationManagementMenu+' => 'コンフィグレーション管理', // 'Configuration Management',
	'UI:ConfigurationManagementMenu:Title' => 'インフラストラクチャ概観', // 'Infrastructure Overview',
	'UI-ConfigurationManagementMenu-InfraByType' => '型別インフラオブジェクト', // 'Infrastructure objects by type',
	'UI-ConfigurationManagementMenu-InfraByStatus' => '状態別インフラオブジェクト', // 'Infrastructure objects by status',

'UI:ConfigMgmtMenuOverview:Title' => 'コンフィグレーション管理用ダッシュボード', // 'Dashboard for Configuration Management',
'UI-ConfigMgmtMenuOverview-FunctionalCIbyStatus' => '状態別コンフィグレーション項目', //'Configuration Items by status',
'UI-ConfigMgmtMenuOverview-FunctionalCIByType' => '型別コンフィグレーション項目', // 'Configuration Items by type',

'UI:RequestMgmtMenuOverview:Title' => 'リクエスト管理用ダッシュボード', // 'Dashboard for Request Management',
'UI-RequestManagementOverview-RequestByService' => 'サービス別ユーザリクエスト', //'User Requests by service',
'UI-RequestManagementOverview-RequestByPriority' => '優先度別ユーザリクエスト', // 'User Requests by priority',
'UI-RequestManagementOverview-RequestUnassigned' => 'エージェントへ未アサインのユーザリクエスト', // 'User Requests not yet assigned to an agent',

'UI:IncidentMgmtMenuOverview:Title' => 'インシデント管理用ダッシュボード', // 'Dashboard for Incident Management',
'UI-IncidentManagementOverview-IncidentByService' => 'サービス別インシデント', // 'Incidents by service',
'UI-IncidentManagementOverview-IncidentByPriority' => '優先度別インシデント', // 'Incidents by priority',
'UI-IncidentManagementOverview-IncidentUnassigned' => 'エージェントへ未アサインのインシデント', // 'Incidents not yet assigned to an agent',

'UI:ChangeMgmtMenuOverview:Title' => '変更管理用ダッシュボード', // 'Dashboard for Change Management',
'UI-ChangeManagementOverview-ChangeByType' => '型別変更内容', // 'Changes by type',
'UI-ChangeManagementOverview-ChangeUnassigned' => 'エージェントへ未アサインの変更内容', // 'Changes not yet assigned to an agent',
'UI-ChangeManagementOverview-ChangeWithOutage' => '変更すべき一時停止???', // 'Outages due to changes',

'UI:ServiceMgmtMenuOverview:Title' => 'サービス管理用ダッシュボード', // 'Dashboard for Service Management',
'UI-ServiceManagementOverview-CustomerContractToRenew' => '30日以内に契約更新が必要な顧客', // 'Customer contracts to be renewed in 30 days',
'UI-ServiceManagementOverview-ProviderContractToRenew' => '30日以内に契約更新が必要なプロバイダ', // 'Provider contracts to be renewed in 30 days',

	'UI:ContactsMenu' => 'コンタクト', // 'Contacts',
	'UI:ContactsMenu+' => 'コンタクト', // 'Contacts',
	'UI:ContactsMenu:Title' => 'コンタクト概観', // 'Contacts Overview',
	'UI-ContactsMenu-ContactsByLocation' => 'ロケーション別コンタクト', // 'Contacts by location',
	'UI-ContactsMenu-ContactsByType' => 'タイプ別コンタクト', // 'Contacts by type',
	'UI-ContactsMenu-ContactsByStatus' => '状態別コンタクト', //'Contacts by status',

	'Menu:CSVImportMenu' => 'CSV インポート', // 'CSV import',
	'Menu:CSVImportMenu+' => '一括生成/一括更新', //'Bulk creation or update',
	
	'Menu:DataModelMenu' => 'データモデル', // 'Data Model',
	'Menu:DataModelMenu+' => 'データモデル概観', // 'Overview of the Data Model',
	
	'Menu:ExportMenu' => 'エクスポート', // 'Export',
	'Menu:ExportMenu+' => '任意のクエリ結果をHTML、CSV、XMLでエクスポートする', // 'Export the results of any query in HTML, CSV or XML',
	
	'Menu:NotificationsMenu' => 'ノーティフィケーション', // 'Notifications',
	'Menu:NotificationsMenu+' => 'ノーティフィケーションの設定', // 'Configuration of the Notifications',
	'UI:NotificationsMenu:Title' => '<span class="hilite">ノーティフィケーション</span>の設定', // 'Configuration of the <span class="hilite">Notifications</span>',
	'UI:NotificationsMenu:Help' => 'ヘルプ', // 'Help'
//	'UI:NotificationsMenu:HelpContent' => 	'<p>In iTop the notifications are fully customizable. They are based on two sets of objects: <i>triggers and actions</i>.</p>
//<p><i><b>Triggers</b></i> define when a notification will be executed. There are 3 types of triggers for covering 3 differents phases of an object life cycle:
//<ol>
//	<li>the "OnCreate" triggers get executed when an object of the specified class is created</li>
//	<li>the "OnStateEnter" triggers get executed before an object of the given class enters a specified state (coming from another state)</li>
//	<li>the "OnStateLeave" triggers get executed when an object of the given class is leaving a specified state</li>
//</ol>
//</p>
//<p>
//<i><b>Actions</b></i> define the actions to be performed when the triggers execute. For now there is only one kind of action consisting in sending an email message.
//Such actions also define the template to be used for sending the email as well as the other parameters of the message like the recipients, importance, etc.
//</p>
//<p>A special page: <a href="../setup/email.test.php" target="_blank">email.test.php</a> is available for testing and troubleshooting your PHP mail configuration.</p>
//<p>To be executed, actions must be associated to triggers.
//When associated with a trigger, each action is given an "order" number, specifying in which order the actions are to be executed.</p>',

	'UI:NotificationsMenu:HelpContent' => '<p>iTopでは、ノーティフィケーションはすべてカスタマイズが可能です。ノーティフィケーションは<i>トリガーとアクション</i>という二つのオブジェクトがベースになっています。
<p><i><b>トリガー</b></i>は、あるノーティフィケーションがいつ実行されるのか、を定義する。トリガーは3つのタイプに分類され、オブジェクトライフサイクルにおける3つの異なるフェーズに対応する：
<ol>
	<li>"onCreate"トリガーは、指定されたクラスのオブジェクトが生成されたときに実行される。</li>
	<li>"onStateEnter"トリガーは、指定されたクラスのオブジェクトが(他の状態から)指定された状態に入る前に実行される。</li>
	<li>"onStateLeave"トリガーは、指定されたクラスのオブジェクトが指定された状態から出る際に実行される。</li>
</ol>
</p>
<p>
<i><b>アクション</b></i>はトリガーが実行される際の動作を定義する。例えば今、「メールを送信する」という動作で構成されるたった1種類だけのアクションがあるとしよう。
このようなアクションは、受信者、重要度といったメッセージに付随する他のパラメータと同様、メール送信に利用されるテンプレートも定義する。
</p>
<p>特別なページ: <a href="../setup/email.test.php" target="_blank">email.test.php</p>は、PHPのメール設定をテストしたりトラブルシュートするのに利用可能である。</p>
<p>実行するには、アクションがトリガーに関連づけられている必要がある。
トリガーに関連づけられると、各々のアクションは順番が与えられ、どの順序でそのアクションが実行されるかが指定される。</p>',

	'UI:NotificationsMenu:Triggers' => 'トリガー', // 'Triggers',
	'UI:NotificationsMenu:AvailableTriggers' => '実行可能トリガー', // 'Available triggers',
	'UI:NotificationsMenu:OnCreate' => 'オブジェクトが生成された時', // 'When an object is created',
	'UI:NotificationsMenu:OnStateEnter' => 'オブジェクトが指定状態に入った時', // 'When an object enters a given state',
	'UI:NotificationsMenu:OnStateLeave' => 'オブジェクトが指定状態から出た時', // 'When an object leaves a given state',
	'UI:NotificationsMenu:Actions' => 'アクション', // 'Actions',
	'UI:NotificationsMenu:AvailableActions' => '実行可能アクション', // 'Available actions',
	
	'Menu:AuditCategories' => '監査カテゴリ', // 'Audit Categories',
	'Menu:AuditCategories+' => '監査カテゴリ', // 'Audit Categories',
	'Menu:Notifications:Title' => '監査カテゴリ', // 'Audit Categories',
	
	'Menu:RunQueriesMenu' => 'クエリ実行', // 'Run Queries',
	'Menu:RunQueriesMenu+' => '任意のクエリを実行', // 'Run any query',
	
	'Menu:DataAdministration' => 'データ管理', // 'Data administration',
	'Menu:DataAdministration+' => 'データ管理', // 'Data administration',
	
	'Menu:UniversalSearchMenu' => '全検索', // 'Universal Search',
	'Menu:UniversalSearchMenu+' => '何か...を検索', // 'Search for anything...',
	
	'Menu:ApplicationLogMenu' => 'Log de l\'application',
	'Menu:ApplicationLogMenu+' => 'Log de l\'application',
	'Menu:ApplicationLogMenu:Title' => 'Log de l\'application',

	'Menu:UserManagementMenu' => 'ユーザ管理', // 'User Management',
	'Menu:UserManagementMenu+' => 'ユーザ管理', // 'User management',

	'Menu:ProfilesMenu' => 'プロファイル', // 'Profiles',
	'Menu:ProfilesMenu+' => 'プロファイル', // 'Profiles',
	'Menu:ProfilesMenu:Title' => 'プロファイル', // 'Profiles',

	'Menu:UserAccountsMenu' => 'ユーザアカウント', // 'User Accounts',
	'Menu:UserAccountsMenu+' => 'ユーザアカウント', // 'User Accounts',
	'Menu:UserAccountsMenu:Title' => 'ユーザアカウント', // 'User Accounts',	

	'UI:iTopVersion:Short' => 'iTopバージョン%1$s', // 'iTop version %1$s',
	'UI:iTopVersion:Long' => 'iTopバージョン%1$s-%2$s, %3$sビルド', // 'iTop version %1$s-%2$s built on %3$s',
	'UI:PropertiesTab' => 'プロパティ', // 'Properties',

	'UI:OpenDocumentInNewWindow_' => '新規ウィンドウで本ドキュメント: %1$sを開く', // 'Open this document in a new window: %1$s',
	'UI:DownloadDocument_' => '本ドキュメント: %1$sをダウンロードする', // 'Download this document: %1$s',
	'UI:Document:NoPreview' => 'このタイプのドキュメントはプレビューできません。', // 'No preview is available for this type of document',

	'UI:DeadlineMissedBy_duration' => '%1$s によって消去されました。', // 'Missed  by %1$s',
	'UI:Deadline_LessThan1Min' => '1分以内', // '< 1 min',		
	'UI:Deadline_Minutes' => '%1$d 分', // '%1$d min',			
	'UI:Deadline_Hours_Minutes' => '%1$d時間%2$d分', // '%1$dh %2$dmin',			
	'UI:Deadline_Days_Hours_Minutes' => '%1$d日%2$d時間%3$d分', // '%1$dd %2$dh %3$dmin',
	'UI:Help' => 'ヘルプ', // 'Help',
	'UI:PasswordConfirm' => '(確認)', // '(Confirm)',
	'UI:BeforeAdding_Class_ObjectsSaveThisObject' => '%1$sオブジェクトをさらに追加する前に、このオブジェクトを保存してください。', // 'Before adding more %1$s objects, save this object.',
	'UI:DisplayThisMessageAtStartup' => '起動時にこのメッセージを表示する', // 'Display this message at startup',
	'UI:RelationshipGraph' => 'グラフィカル表示', // 'Graphical view',
	'UI:RelationshipList' => 'リスト', // 'List',
	'UI:OperationCancelled' => '操作はキャンセルされました', // 'Operation Cancelled',

	'Portal:Title' => 'iTopユーザポータル', // 'iTop user portal',
	'Portal:Refresh' => '更新', // 'Refresh',
	'Portal:Back' => '戻る', // 'Back',
	'Portal:WelcomeUserOrg' => 'Welcome %1$s, from %2$s',
	'Portal:ShowOngoing' => 'Show open requests',
	'Portal:ShowClosed' => 'Show closed requests',
	'Portal:CreateNewRequest' => '新規リクエストを生成する', // 'Create a new request',
	'Portal:ChangeMyPassword' => 'パスワードを変更する', // 'Change my password',
	'Portal:Disconnect' => '切断する', // 'Disconnect',
	'Portal:OpenRequests' => '発行済みリクエスト', // 'My open requests',
	'Portal:ClosedRequests'  => 'My closed requests',
	'Portal:ResolvedRequests'  => '解決済みリクエスト', // 'My resolved requests',
	'Portal:SelectService' => 'カタログからサービスを選択してください：', // 'Select a service from the catalog:',
	'Portal:PleaseSelectOneService' => 'サービスを1つ選んでください', // 'Please select one service',
	'Portal:SelectSubcategoryFrom_Service' => '本サービス：%1$sのサブカテゴリを選んでください', // 'Select a sub-category for the service %1$s:',
	'Portal:PleaseSelectAServiceSubCategory' => 'サブカテゴリを1つ選んでください', // 'Please select one sub-category',
	'Portal:DescriptionOfTheRequest' => 'あなたのリクエストの詳細を記入してください：', // 'Enter the description of your request:',
	'Portal:TitleRequestDetailsFor_Request' => 'リクエスト%1$sの詳細：', // Details for request %1$s:',
	'Portal:NoOpenRequest' => '本カテゴリにリクエストはありません', // 'No request in this category.',
	'Portal:NoClosedRequest' => 'No request in this category',
	'Portal:Button:ReopenTicket' => 'Reopen this ticket',
	'Portal:Button:CloseTicket' => '本チケットを閉じます。', // 'Close this ticket',
	'Portal:Button:UpdateRequest' => 'Update the request',
	'Portal:EnterYourCommentsOnTicket' => '本チケットの解決について、コメントを入力してください。', // 'Enter your comments about the resolution of this ticket:',
	'Portal:ErrorNoContactForThisUser' => 'エラー：現在のユーザはコンタクト/人物に関連づけられていません。管理者に問い合わせてください。', // 'Error: the current user is not associated with a Contact/Person. Please contact your administrator.',
	'Portal:Attachments' => '添付', // 'Attachments',
	'Portal:AddAttachment' => ' 添付を付加する ', // ' Add Attachment ',
	'Portal:RemoveAttachment' => ' 添付を除去する ', // ' Remove Attachment ',
	'Portal:Attachment_No_To_Ticket_Name' => '#%1$d を$2$s ($3$s)に添付する', // 'Attachment #%1$d to %2$s (%3$s)',
	'Enum:Undefined' => '定義されていません', // 'Undefined',
	'UI:Button:Refresh' => '更新', // 'Refresh',
));



?>
