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
 * @author      Tadashi Kaneda <kaneda@smartec.co.jp>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Core:AttributeLinkedSet' => 'オブジェクト配列',
	'Core:AttributeLinkedSet+' => '同一あるいはサブクラスに属するオブジェクト',

	'Core:AttributeLinkedSetIndirect' => 'オブジェクト配列 (N-N)',
	'Core:AttributeLinkedSetIndirect+' => '同一クラスの任意のオブジェクト(サブクラス)',

	'Core:AttributeInteger' => 'Int型',
	'Core:AttributeInteger+' => '数値 (負数あり)',

	'Core:AttributeDecimal' => 'Decimal型',
	'Core:AttributeDecimal+' => 'Decimal値 (負数あり)',

	'Core:AttributeBoolean' => 'ブール型',
	'Core:AttributeBoolean+' => 'Bool値',

	'Core:AttributeString' => '文字列',
	'Core:AttributeString+' => '文字列',

	'Core:AttributeClass' => 'クラス',
	'Core:AttributeClass+' => 'クラス',

	'Core:AttributeApplicationLanguage' => '使用言語',
	'Core:AttributeApplicationLanguage+' => '言語・国別 (EN US)',

	'Core:AttributeFinalClass' => 'クラス (自動)',
	'Core:AttributeFinalClass+' => 'オブジェクトの実クラス (コアで自動的に生成される)',

	'Core:AttributePassword' => 'パスワード',
	'Core:AttributePassword+' => '外部デバイス用パスワード',

 	'Core:AttributeEncryptedString' => '暗号化文字列',
	'Core:AttributeEncryptedString+' => 'ローカルキーで暗号化された文字列',

	'Core:AttributeText' => 'テキスト',
	'Core:AttributeText+' => '複数行文字列',

	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'HTML文字列',

	'Core:AttributeEmailAddress' => 'メールアドレス',
	'Core:AttributeEmailAddress+' => 'メールアドレス',

	'Core:AttributeIPAddress' => 'IPアドレス',
	'Core:AttributeIPAddress+' => 'IPアドレス',

	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => 'OQL式',

	'Core:AttributeEnum' => '列挙型',
	'Core:AttributeEnum+' => 'ナンバリング済み文字列のリスト',

	'Core:AttributeTemplateString' => 'テンプレート文字列',
	'Core:AttributeTemplateString+' => 'プレースホルダを含む文字列',

	'Core:AttributeTemplateText' => 'テンプレートテキスト',
	'Core:AttributeTemplateText+' => 'プレースホルダを含むテキスト',

	'Core:AttributeTemplateHTML' => 'テンプレートHTML',
	'Core:AttributeTemplateHTML+' => 'プレースホルダを含むHTML',

	'Core:AttributeWikiText' => 'Wikiアーティクル',
	'Core:AttributeWikiText+' => 'Wikiフォーマット済みテキスト',

	'Core:AttributeDateTime' => '日付/時刻',
	'Core:AttributeDateTime+' => '日付と時刻(年-月-日 hh:mm:ss)',

	'Core:AttributeDate' => '日付',
	'Core:AttributeDate+' => '日付 (年-月-日)',

	'Core:AttributeDeadline' => '締切',
	'Core:AttributeDeadline+' => '日付, 現在時刻からの相対表示',

	'Core:AttributeExternalKey' => '外部キー',
	'Core:AttributeExternalKey+' => '外部(あるいはフォーリン)キー',

	'Core:AttributeExternalField' => '外部フィールド',
	'Core:AttributeExternalField+' => '外部キーにマッピングされたフィールド',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => '絶対URLもしくは相対URLのテキスト文字列',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => '任意のバイナリコンテンツ(ドキュメント)',

	'Core:AttributeOneWayPassword' => '一方向パスワード',
	'Core:AttributeOneWayPassword+' => '一方向暗号化(ハッシュ)パスワード',

	'Core:AttributeTable' => 'テーブル',
	'Core:AttributeTable+' => 'インデックス化された二次元配列',

	'Core:AttributePropertySet' => 'プロパティ',
	'Core:AttributePropertySet+' => '型づけされていないプロパティのリスト(名前とバリュー)',
));


//////////////////////////////////////////////////////////////////////
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//

//
// Class: CMDBChange
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:CMDBChange' => '変更',
	'Class:CMDBChange+' => '変更履歴',
	'Class:CMDBChange/Attribute:date' => '日付',
	'Class:CMDBChange/Attribute:date+' => '変更が記録された日時',
	'Class:CMDBChange/Attribute:userinfo' => 'その他情報',
	'Class:CMDBChange/Attribute:userinfo+' => '呼出側の定義済み情報',
));

//
// Class: CMDBChangeOp
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:CMDBChangeOp' => '変更操作',
	'Class:CMDBChangeOp+' => '変更操作履歴',
	'Class:CMDBChangeOp/Attribute:change' => '変更',
	'Class:CMDBChangeOp/Attribute:change+' => '変更',
	'Class:CMDBChangeOp/Attribute:date' => '日付',
	'Class:CMDBChangeOp/Attribute:date+' => '変更日時',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'ユーザ',
	'Class:CMDBChangeOp/Attribute:userinfo+' => '変更者',
	'Class:CMDBChangeOp/Attribute:objclass' => 'オブジェクトクラス',
	'Class:CMDBChangeOp/Attribute:objclass+' => 'オブジェクトクラス',
	'Class:CMDBChangeOp/Attribute:objkey' => 'オブジェクトID',
	'Class:CMDBChangeOp/Attribute:objkey+' => 'オブジェクトID',
	'Class:CMDBChangeOp/Attribute:finalclass' => '型',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:CMDBChangeOpCreate' => 'オブジェクト生成',
	'Class:CMDBChangeOpCreate+' => 'オブジェクト生成履歴',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:CMDBChangeOpDelete' => 'オブジェクト削除',
	'Class:CMDBChangeOpDelete+' => 'オブジェクト削除履歴',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:CMDBChangeOpSetAttribute' => 'オブジェクト更新',
	'Class:CMDBChangeOpSetAttribute+' => 'オブジェクトプロパティの更新履歴',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => '属性',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => '更新プロパティのコード',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'プロパティ更新',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'オブジェクトのスカラープロパティの更新履歴',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => '変更前の値',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => '属性の変更前の値',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => '新規の値',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => '属性の新規の値',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Change:ObjectCreated' => 'オブジェクトを生成しました',
	'Change:ObjectDeleted' => 'オブジェクトを削除しました',
	'Change:ObjectModified' => 'オブジェクトを更新しました',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$sを%2$sに設定しました (変更前の値: %3$s)',
	'Change:Text_AppendedTo_AttName' => '%1$sを%2$sに追加しました',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$sを更新しました。更新前の値: %2$s',
	'Change:AttName_Changed' => '%1$sを更新しました',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'データ変更',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'データ変更履歴',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => '変更前のデータ', //'Previous data',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'この属性の以前の内容', //'previous contents of the attribute',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:CMDBChangeOpSetAttributeText' => 'テキストの変更', //'text change',
	'Class:CMDBChangeOpSetAttributeText+' => 'テキストの変更履歴', //'text change tracking',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => '以前の内容', //'Previous data',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'この属性の以前の内容', //'previous contents of the attribute',
));

//
// Class: Event
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Event' => 'ログイベント',// 'Log Event',
	'Class:Event+' => 'アプリケーション内部イベント', //'An application internal event',
	'Class:Event/Attribute:message' => 'メッセージ', //'message',
	'Class:Event/Attribute:message+' => 'イベント概略', //'short description of the event',
	'Class:Event/Attribute:date' => '日付', //'date',
	'Class:Event/Attribute:date+' => '変更が記録された日時', //'date and time at which the changes have been recorded',
	'Class:Event/Attribute:userinfo' => 'ユーザ情報', //'user info',
	'Class:Event/Attribute:userinfo+' => 'このイベントをトリガーにアクションを起こすユーザの識別', //'identification of the user that was doing the action that triggered this event',
	'Class:Event/Attribute:finalclass' => '型', //'type',
	'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:EventNotification' => '通知イベント', // 'Notification event',
	'Class:EventNotification+' => '創出された通知のトレース', //'Trace of a notification that has been sent',
	'Class:EventNotification/Attribute:trigger_id' => 'トリガー', //'Trigger',
	'Class:EventNotification/Attribute:trigger_id+' => 'ユーザアカウント', //'user account',
	'Class:EventNotification/Attribute:action_id' => 'ユーザ', //'user',
	'Class:EventNotification/Attribute:action_id+' => 'ユーザアカウント', //'user account',
	'Class:EventNotification/Attribute:object_id' => 'オブジェクトID', //'Object id',
	'Class:EventNotification/Attribute:object_id+' => 'オブジェクトID(トリガーでクラスが定義済み?)', //'object id (class defined by the trigger ?)',
));

//
// Class: EventNotificationEmail
//

Dict::Add('JA JP', 'Japanese', '日本語', array('Class:EventNotificationEmail' => 'メール送出イベント', //'Email emission event',
					       'Class:EventNotificationEmail+' => '送出されたメールのトレース',//Trace of an email that has been sent',
	'Class:EventNotificationEmail/Attribute:to' => 'TO',
	'Class:EventNotificationEmail/Attribute:to+' => 'TO',
	'Class:EventNotificationEmail/Attribute:cc' => 'CC',
	'Class:EventNotificationEmail/Attribute:cc+' => 'CC',
	'Class:EventNotificationEmail/Attribute:bcc' => 'BCC',
	'Class:EventNotificationEmail/Attribute:bcc+' => 'BCC',
	'Class:EventNotificationEmail/Attribute:from' => 'From',
					       'Class:EventNotificationEmail/Attribute:from+' => 'メール送信者', //'Sender of the message',
	'Class:EventNotificationEmail/Attribute:subject' => 'Subject',
	'Class:EventNotificationEmail/Attribute:subject+' => 'Subject',
	'Class:EventNotificationEmail/Attribute:body' => 'Body',
	'Class:EventNotificationEmail/Attribute:body+' => 'Body',
));

//
// Class: EventIssue
//

Dict::Add('EN US', 'English', 'English', array(
					       'Class:EventIssue' => 'イシューイベント', //'Issue event',
					       'Class:EventIssue+' => 'イシュー(警告、エラーetc)のトレース', //'Trace of an issue (warning, error, etc.)',
					       'Class:EventIssue/Attribute:issue' => 'イシュー', //'Issue',
					       'Class:EventIssue/Attribute:issue+' => '何が起こったか', //'What happened',
					       'Class:EventIssue/Attribute:impact' => 'インパクト', //'Impact',
					       'Class:EventIssue/Attribute:impact+' => 'その結果', //'What are the consequences',
					       'Class:EventIssue/Attribute:page' => 'ページ', //'Page',
					       'Class:EventIssue/Attribute:page+' => 'HTTPエントリポイント', //'HTTP entry point',
					       'Class:EventIssue/Attribute:arguments_post' => 'POSTされた引数', //'Posted arguments',
					       'Class:EventIssue/Attribute:arguments_post+' => 'HTTP POST引数', //'HTTP POST arguments',
					       'Class:EventIssue/Attribute:arguments_get' => 'URLパラメータ', //'URL arguments',
					       'Class:EventIssue/Attribute:arguments_get+' => 'HTTP GETパラメータ', //'HTTP GET arguments',
					       'Class:EventIssue/Attribute:callstack' => 'コールスタック', //'Callstack',
					       'Class:EventIssue/Attribute:callstack+' => 'スタックをコールする', //'Call stack',
					       'Class:EventIssue/Attribute:data' => 'データ', //'Data',
					       'Class:EventIssue/Attribute:data+' => '詳細情報', //'More information',
));

//
// Class: EventWebService
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:EventWebService' => 'ウェブサービスイベント', //'Web service event',
					       'Class:EventWebService+' => 'ウェブサービス呼出のYトレース', //'Trace of an web service call',
					       'Class:EventWebService/Attribute:verb' => '動詞', //'Verb',
					       'Class:EventWebService/Attribute:verb+' => '操作名', //'Name of the operation',
					       'Class:EventWebService/Attribute:result' => '結果', //'Result',
					       'Class:EventWebService/Attribute:result+' => '総体的な成功/失敗', //'Overall success/failure',
					       'Class:EventWebService/Attribute:log_info' => 'インフォログ', //'Info log',
					       'Class:EventWebService/Attribute:log_info+' => 'インフォログの結果', //'Result info log',
					       'Class:EventWebService/Attribute:log_warning' => 'ウォーニングログ', //'Warning log',
					       'Class:EventWebService/Attribute:log_warning+' => 'ウォーニングログ結果', //'Result warning log',
					       'Class:EventWebService/Attribute:log_error' => 'エラーログ', //'Error log',
					       'Class:EventWebService/Attribute:log_error+' => 'エラーログ結果', //'Result error log',
					       'Class:EventWebService/Attribute:data' => 'データ', //'Data',
					       'Class:EventWebService/Attribute:data+' => 'データ結果', //'Result data',
));

//
// Class: Action
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:Action' => 'カスタムアクション', //'Custom Action',
					       'Class:Action+' => 'ユーザ定義アクション', //'User defined action',
					       'Class:Action/Attribute:name' => '名前', //'Name',
	'Class:Action/Attribute:name+' => '',
					       'Class:Action/Attribute:description' => '概要', //'Description',
	'Class:Action/Attribute:description+' => '',
					       'Class:Action/Attribute:status' => 'ステータス', //'Status',
					       'Class:Action/Attribute:status+' => '製品化済み、あるいは?', //'In production or ?',
					       'Class:Action/Attribute:status/Value:test' => 'テスト済み', //'Being tested',
					       'Class:Action/Attribute:status/Value:test+' => 'テスト済み', //'Being tested',
					       'Class:Action/Attribute:status/Value:enabled' => '製品化済み', //'In production',
					       'Class:Action/Attribute:status/Value:enabled+' => '製品化済み', //'In production',
					       'Class:Action/Attribute:status/Value:disabled' => '非アクティブ', //'Inactive',
					       'Class:Action/Attribute:status/Value:disabled+' => '非アクティブ', //'Inactive',
					       'Class:Action/Attribute:trigger_list' => '関連トリガ', //'Related Triggers',
					       'Class:Action/Attribute:trigger_list+' => 'このアクションにリンクされたトリガ', //'Triggers linked to this action',
					       'Class:Action/Attribute:finalclass' => '型', //'Type',
	'Class:Action/Attribute:finalclass+' => '',
));

//
// Class: ActionNotification
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:ActionNotification' => 'ノーティフィケーション', //'Notification',
					       'Class:ActionNotification+' => 'ノーティフィケーション(抽象)', //'Notification (abstract)',
));

//
// Class: ActionEmail
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:ActionEmail' => 'メール通知', //'Email notification',
	'Class:ActionEmail+' => '',
					       'Class:ActionEmail/Attribute:test_recipient' => 'テストレシピ', //'Test recipient',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Detination in case status is set to "Test"',
	'Class:ActionEmail/Attribute:from' => 'From',
	'Class:ActionEmail/Attribute:from+' => 'Will be sent into the email header',
	'Class:ActionEmail/Attribute:reply_to' => 'Reply to',
	'Class:ActionEmail/Attribute:reply_to+' => 'Will be sent into the email header',
	'Class:ActionEmail/Attribute:to' => 'To',
					       'Class:ActionEmail/Attribute:to+' => 'メールの宛先', //'Destination of the email',
	'Class:ActionEmail/Attribute:cc' => 'Cc',
	'Class:ActionEmail/Attribute:cc+' => 'Carbon Copy',
	'Class:ActionEmail/Attribute:bcc' => 'bcc',
	'Class:ActionEmail/Attribute:bcc+' => 'Blind Carbon Copy',
	'Class:ActionEmail/Attribute:subject' => 'subject',
					       'Class:ActionEmail/Attribute:subject+' => 'メールのタイトル', //'Title of the email',
	'Class:ActionEmail/Attribute:body' => 'body',
					       'Class:ActionEmail/Attribute:body+' => 'メールの本文', //'Contents of the email',
	'Class:ActionEmail/Attribute:importance' => 'importance',
					       'Class:ActionEmail/Attribute:importance+' => '重要度フラグ', //'Importance flag',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'low',
	'Class:ActionEmail/Attribute:importance/Value:low+' => 'low',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'normal',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => 'normal',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'high',
	'Class:ActionEmail/Attribute:importance/Value:high+' => 'high',
));

//
// Class: Trigger
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:Trigger' => 'トリガー', //'Trigger',
					       'Class:Trigger+' => 'カスタムイベントヘッダ', //'Custom event handler',
					       'Class:Trigger/Attribute:description' => '概要', //'Description',
					       'Class:Trigger/Attribute:description+' => '1行概要', //'one line description',
					       'Class:Trigger/Attribute:action_list' => 'トリガされたアクション', //'Triggered actions',
					       'Class:Trigger/Attribute:action_list+' => 'トリガが発火した場合に動作するアクション', //'Actions performed when the trigger is activated',
					       'Class:Trigger/Attribute:finalclass' => '型', //'Type',
	'Class:Trigger/Attribute:finalclass+' => '',
));

//
// Class: TriggerOnObject
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:TriggerOnObject' => 'トリガ(クラス依存)', //'Trigger (class dependent)',
					       'Class:TriggerOnObject+' => '指定オブジェクトのクラスへのトリガ', //'Trigger on a given class of objects',
					       'Class:TriggerOnObject/Attribute:target_class' => 'ターゲットクラス', //'Target class',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:TriggerOnStateChange' => '(状態変更の)トリガ', // Trigger (on state change)',
					       'Class:TriggerOnStateChange+' => 'オブジェクト状態変更のトリガ', //'Trigger on object state change',
					       'Class:TriggerOnStateChange/Attribute:state' => '状態', //'State',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:TriggerOnStateEnter' => 'トリガ(ある状態に入る)', // 'Trigger (on entering a state)',
					       'Class:TriggerOnStateEnter+' => 'オブジェクト状態変更のトリガ: 入場', //'Trigger on object state change - entering',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:TriggerOnStateLeave' => '(ある状態から退場する)トリガ', // 'Trigger (on leaving a state)',
					       'Class:TriggerOnStateLeave+' => 'オブジェクト状態変更のトリガ: 退場', //Trigger on object state change - leaving',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:TriggerOnObjectCreate' => '(オブジェクト生成の)トリガ', //Trigger (on object creation)',
					       'Class:TriggerOnObjectCreate+' => '指定されたクラスの(子クラスの)オブジェクト生成のトリガ', //Trigger on object creation of [a child class of] the given class',
));

//
// Class: lnkTriggerAction
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
					       'Class:lnkTriggerAction' => 'アクション/トリガ', //'Action/Trigger',
					       'Class:lnkTriggerAction+' => 'トリガとアクション間のリンク', //'Link between a trigger and an action',
					       'Class:lnkTriggerAction/Attribute:action_id' => 'アクション', //'Action',
					       'Class:lnkTriggerAction/Attribute:action_id+' => '実行されるべきアクション', //'The action to be executed',
					       'Class:lnkTriggerAction/Attribute:action_name' => 'アクション', //'Action',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
					       'Class:lnkTriggerAction/Attribute:trigger_id' => 'トリガ', //'Trigger',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
					       'Class:lnkTriggerAction/Attribute:trigger_name' => 'トリガ', //'Trigger',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
					       'Class:lnkTriggerAction/Attribute:order' => '処理順序', //'Order',
					       'Class:lnkTriggerAction/Attribute:order+' => 'アクション実行順序', //'Actions execution order',
));


?>
