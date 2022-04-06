<?php
// Copyright (C) 2010-2021 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Core:DeletedObjectLabel' => '%1s (削除されました)',
	'Core:DeletedObjectTip' => 'オブジェクトは削除されました %1$s (%2$s)',

	'Core:UnknownObjectLabel' => 'オブジェクトは見つかりません (クラス: %1$s, id: %2$d)',
	'Core:UnknownObjectTip' => 'オブジェクトは見つかりません。しばらく前に削除され、その後ログが削除されたかもしれません。',

	'Core:UniquenessDefaultError' => 'Uniqueness rule \'%1$s\' in error~~',

	'Core:AttributeLinkedSet' => 'オブジェクト配列',
	'Core:AttributeLinkedSet+' => '同一あるいはサブクラスに属するオブジェクト',

	'Core:AttributeLinkedSetDuplicatesFound' => 'Duplicates in the \'%1$s\' field : %2$s~~',

	'Core:AttributeDashboard' => 'Dashboard~~',
	'Core:AttributeDashboard+' => '~~',

	'Core:AttributePhoneNumber' => 'Phone number~~',
	'Core:AttributePhoneNumber+' => '~~',

	'Core:AttributeObsolescenceDate' => 'Obsolescence date~~',
	'Core:AttributeObsolescenceDate+' => '~~',

	'Core:AttributeTagSet' => 'List of tags~~',
	'Core:AttributeTagSet+' => '~~',
	'Core:AttributeSet:placeholder' => 'click to add~~',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass' => '%1$s (%2$s)~~',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass' => '%1$s (%2$s from %3$s)~~',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses' => '%1$s (%2$s from child classes)~~',

	'Core:AttributeCaseLog' => 'Log~~',
	'Core:AttributeCaseLog+' => '~~',

	'Core:AttributeMetaEnum' => 'Computed enum~~',
	'Core:AttributeMetaEnum+' => '~~',

	'Core:AttributeLinkedSetIndirect' => 'オブジェクト配列 (N-N)',
	'Core:AttributeLinkedSetIndirect+' => '同一クラスの任意のオブジェクト(サブクラス)',

	'Core:AttributeInteger' => 'Int型',
	'Core:AttributeInteger+' => '数値 (負数あり)',

	'Core:AttributeDecimal' => 'Decimal型',
	'Core:AttributeDecimal+' => 'Decimal値 (負数あり)',

	'Core:AttributeBoolean' => 'ブール型',
	'Core:AttributeBoolean+' => 'Bool値',
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => 'Yes~~',
	'Core:AttributeBoolean/Value:no' => 'No~~',

	'Core:AttributeArchiveFlag' => 'Archive flag~~',
	'Core:AttributeArchiveFlag/Value:yes' => 'Yes~~',
	'Core:AttributeArchiveFlag/Value:yes+' => 'This object is visible only in archive mode~~',
	'Core:AttributeArchiveFlag/Value:no' => 'No~~',
	'Core:AttributeArchiveFlag/Label' => 'Archived~~',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => 'Archive date~~',
	'Core:AttributeArchiveDate/Label+' => '',

	'Core:AttributeObsolescenceFlag' => 'Obsolescence flag~~',
	'Core:AttributeObsolescenceFlag/Value:yes' => 'Yes~~',
	'Core:AttributeObsolescenceFlag/Value:yes+' => 'This object is excluded from the impact analysis, and hidden from search results~~',
	'Core:AttributeObsolescenceFlag/Value:no' => 'No~~',
	'Core:AttributeObsolescenceFlag/Label' => 'Obsolete~~',
	'Core:AttributeObsolescenceFlag/Label+' => 'Computed dynamically on other attributes~~',
	'Core:AttributeObsolescenceDate/Label' => 'Obsolescence date~~',
	'Core:AttributeObsolescenceDate/Label+' => 'Approximative date at which the object has been considered obsolete~~',

	'Core:AttributeString' => '文字列',
	'Core:AttributeString+' => '文字列',

	'Core:AttributeClass' => 'クラス',
	'Core:AttributeClass+' => 'クラス',

	'Core:AttributeApplicationLanguage' => '使用言語',
	'Core:AttributeApplicationLanguage+' => '言語・国別 (JA JP)',

	'Core:AttributeFinalClass' => 'クラス (自動)',
	'Core:AttributeFinalClass+' => 'オブジェクトの実クラス (コアで自動的に生成される)',

	'Core:AttributePassword' => 'パスワード',
	'Core:AttributePassword+' => '外部デバイス用パスワード',

	'Core:AttributeEncryptedString' => '暗号化文字列',
	'Core:AttributeEncryptedString+' => 'ローカルキーで暗号化された文字列',
	'Core:AttributeEncryptUnknownLibrary' => 'Encryption library specified (%1$s) unknown~~',
	'Core:AttributeEncryptFailedToDecrypt' => '** decryption error **~~',

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

	'Core:AttributeDateTime' => '日付/時刻',
	'Core:AttributeDateTime+' => '日付と時刻(年-月-日 hh:mm:ss)',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
	Date format:<br/>
	<b>%1$s</b><br/>
	例: %2$s
</p>
<p>
Operators:<br/>
	<b>&gt;</b><em>日付</em><br/>
	<b>&lt;</b><em>日付</em><br/>
	<b>[</b><em>日付</em>,<em>日付</em><b>]</b>
</p>
<p>
もし、時刻がなければ、規定値 00:00:00となります。
</p>',

	'Core:AttributeDate' => '日付',
	'Core:AttributeDate+' => '日付 (年-月-日)',
	'Core:AttributeDate?SmartSearch' => '
<p>
	日付フォーマット:<br/>
	<b>%1$s</b><br/>
	例: %2$s
</p>
<p>
演算子:<br/>
	<b>&gt;</b><em>日付</em><br/>
	<b>&lt;</b><em>日付</em><br/>
	<b>[</b><em>日付</em>,<em>日付</em><b>]</b>
</p>',

	'Core:AttributeDeadline' => '締切',
	'Core:AttributeDeadline+' => '日付, 現在時刻からの相対表示',

	'Core:AttributeExternalKey' => '外部キー',
	'Core:AttributeExternalKey+' => '外部(あるいはフォーリン)キー',

	'Core:AttributeHierarchicalKey' => '階層的なキー',
	'Core:AttributeHierarchicalKey+' => '',

	'Core:AttributeExternalField' => '外部フィールド',
	'Core:AttributeExternalField+' => '外部キーにマッピングされたフィールド',

	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => '絶対URLもしくは相対URLのテキスト文字列',

	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => '任意のバイナリコンテンツ(文書)',

	'Core:AttributeOneWayPassword' => '一方向パスワード',
	'Core:AttributeOneWayPassword+' => '一方向暗号化(ハッシュ)パスワード',

	'Core:AttributeTable' => 'テーブル',
	'Core:AttributeTable+' => 'インデックス化された二次元配列',

	'Core:AttributePropertySet' => 'プロパティ',
	'Core:AttributePropertySet+' => '型づけされていないプロパティのリスト(名前とバリュー)',

	'Core:AttributeFriendlyName' => 'Friendly name',
	'Core:AttributeFriendlyName+' => '属性は自動的に作成されました； the friendly name is computed after several attributes',

	'Core:FriendlyName-Label' => 'Name',
	'Core:FriendlyName-Description' => 'Friendly name',

	'Core:AttributeTag' => 'Tags~~',
	'Core:AttributeTag+' => 'Tags~~',
	
	'Core:Context=REST/JSON' => 'REST~~',
	'Core:Context=Synchro' => 'Synchro~~',
	'Core:Context=Setup' => 'Setup~~',
	'Core:Context=GUI:Console' => 'Console~~',
	'Core:Context=CRON' => 'cron~~',
	'Core:Context=GUI:Portal' => 'Portal~~',
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
	'Class:CMDBChange/Attribute:origin/Value:interactive' => 'User interaction in the GUI~~',
	'Class:CMDBChange/Attribute:origin/Value:csv-import.php' => 'CSV import script~~',
	'Class:CMDBChange/Attribute:origin/Value:csv-interactive' => 'CSV import in the GUI~~',
	'Class:CMDBChange/Attribute:origin/Value:email-processing' => 'Email processing~~',
	'Class:CMDBChange/Attribute:origin/Value:synchro-data-source' => 'Synchro. data source~~',
	'Class:CMDBChange/Attribute:origin/Value:webservice-rest' => 'REST/JSON webservices~~',
	'Class:CMDBChange/Attribute:origin/Value:webservice-soap' => 'SOAP webservices~~',
	'Class:CMDBChange/Attribute:origin/Value:custom-extension' => 'By an extension~~',
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
	'Class:CMDBChangeOp/Attribute:finalclass' => 'タイプ',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:CMDBChangeOpCreate' => 'オブジェクト作成',
	'Class:CMDBChangeOpCreate+' => 'オブジェクト作成履歴',
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
	'Change:ObjectModified' => 'オブジェクトを修正しました',
	'Change:TwoAttributesChanged' => 'Edited %1$s and %2$s~~',
	'Change:ThreeAttributesChanged' => 'Edited %1$s, %2$s and 1 other~~',
	'Change:FourOrMoreAttributesChanged' => 'Edited %1$s, %2$s and %3$s others~~',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$sを%2$sに設定しました (変更前の値: %3$s)',
	'Change:AttName_SetTo' => '%1$s は %2$sにセットされました。',
	'Change:Text_AppendedTo_AttName' => '%1$sを%2$sに追加しました',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$sを変更しました。更新前の値: %2$s',
	'Change:AttName_Changed' => '%1$sを変更しました',
	'Change:AttName_EntryAdded' => '%1$s は、修正されました。新しいエントリーが追加されました。: %2$s',
	'Change:State_Changed_NewValue_OldValue' => 'Changed from %2$s to %1$s~~',
	'Change:LinkSet:Added' => '追加されました %1$s',
	'Change:LinkSet:Removed' => '削除されました %1$s',
	'Change:LinkSet:Modified' => '修正されました %1$s',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'データ変更',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'データ変更履歴',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => '以前のデータ',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'この属性の以前の内容',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:CMDBChangeOpSetAttributeText' => 'テキストの変更',
	'Class:CMDBChangeOpSetAttributeText+' => 'テキストの変更履歴',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => '以前の内容',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'この属性の以前の内容',
));

//
// Class: Event
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Event' => 'ログイベント',
	'Class:Event+' => 'アプリケーション内部イベント',
	'Class:Event/Attribute:message' => 'メッセージ',
	'Class:Event/Attribute:message+' => 'イベントの短い説明',
	'Class:Event/Attribute:date' => '日付',
	'Class:Event/Attribute:date+' => '変更が記録された日時',
	'Class:Event/Attribute:userinfo' => 'ユーザ情報',
	'Class:Event/Attribute:userinfo+' => 'このイベントをトリガーしたアクションを行ったユーザ',
	'Class:Event/Attribute:finalclass' => 'タイプ',
	'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:EventNotification' => '通知イベント',
	'Class:EventNotification+' => '送信された通知のトレース',
	'Class:EventNotification/Attribute:trigger_id' => 'トリガー',
	'Class:EventNotification/Attribute:trigger_id+' => 'ユーザアカウント',
	'Class:EventNotification/Attribute:action_id' => 'ユーザ',
	'Class:EventNotification/Attribute:action_id+' => 'ユーザアカウント',
	'Class:EventNotification/Attribute:object_id' => 'オブジェクトID',
	'Class:EventNotification/Attribute:object_id+' => 'オブジェクトID(トリガーでクラスが定義済み?)',
));

//
// Class: EventNotificationEmail
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:EventNotificationEmail' => 'メール送出イベント',
	'Class:EventNotificationEmail+' => '送出されたメールのトレース',
	'Class:EventNotificationEmail/Attribute:to' => 'TO',
	'Class:EventNotificationEmail/Attribute:to+' => 'TO',
	'Class:EventNotificationEmail/Attribute:cc' => 'CC',
	'Class:EventNotificationEmail/Attribute:cc+' => 'CC',
	'Class:EventNotificationEmail/Attribute:bcc' => 'BCC',
	'Class:EventNotificationEmail/Attribute:bcc+' => 'BCC',
	'Class:EventNotificationEmail/Attribute:from' => 'From',
	'Class:EventNotificationEmail/Attribute:from+' => 'メール送信者',
	'Class:EventNotificationEmail/Attribute:subject' => 'Subject',
	'Class:EventNotificationEmail/Attribute:subject+' => '件名',
	'Class:EventNotificationEmail/Attribute:body' => 'Body',
	'Class:EventNotificationEmail/Attribute:body+' => '本文',
	'Class:EventNotificationEmail/Attribute:attachments' => '添付',
	'Class:EventNotificationEmail/Attribute:attachments+' => '',
));

//
// Class: EventIssue
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:EventIssue' => '課題',
	'Class:EventIssue+' => '課題(警告、エラー、etc)のトレース',
	'Class:EventIssue/Attribute:issue' => '課題',
	'Class:EventIssue/Attribute:issue+' => '課題',
	'Class:EventIssue/Attribute:impact' => 'インパクト',
	'Class:EventIssue/Attribute:impact+' => 'その結果',
	'Class:EventIssue/Attribute:page' => 'ページ',
	'Class:EventIssue/Attribute:page+' => 'HTTPエントリポイント',
	'Class:EventIssue/Attribute:arguments_post' => 'POSTされた引数',
	'Class:EventIssue/Attribute:arguments_post+' => 'HTTP POST引数',
	'Class:EventIssue/Attribute:arguments_get' => 'URLパラメータ',
	'Class:EventIssue/Attribute:arguments_get+' => 'HTTP GETパラメータ',
	'Class:EventIssue/Attribute:callstack' => 'コールスタック',
	'Class:EventIssue/Attribute:callstack+' => 'スタックをコールする',
	'Class:EventIssue/Attribute:data' => 'データ',
	'Class:EventIssue/Attribute:data+' => '追加情報',
));

//
// Class: EventWebService
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:EventWebService' => 'ウェブサービスイベント',
	'Class:EventWebService+' => 'ウェブサービス呼出のトレース',
	'Class:EventWebService/Attribute:verb' => '動作',
	'Class:EventWebService/Attribute:verb+' => '操作名',
	'Class:EventWebService/Attribute:result' => '結果',
	'Class:EventWebService/Attribute:result+' => '総体的な成功/失敗',
	'Class:EventWebService/Attribute:log_info' => 'インフォログ',
	'Class:EventWebService/Attribute:log_info+' => 'インフォログの結果',
	'Class:EventWebService/Attribute:log_warning' => 'ワーニンググ',
	'Class:EventWebService/Attribute:log_warning+' => 'ワーニングログ結果',
	'Class:EventWebService/Attribute:log_error' => 'エラーログ',
	'Class:EventWebService/Attribute:log_error+' => 'エラーログ結果',
	'Class:EventWebService/Attribute:data' => 'データ',
	'Class:EventWebService/Attribute:data+' => '結果データ',
));

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:EventRestService' => 'REST/JSON call~~',
	'Class:EventRestService+' => 'Trace of a REST/JSON service call~~',
	'Class:EventRestService/Attribute:operation' => 'Operation~~',
	'Class:EventRestService/Attribute:operation+' => 'Argument \'operation\'~~',
	'Class:EventRestService/Attribute:version' => 'Version~~',
	'Class:EventRestService/Attribute:version+' => 'Argument \'version\'~~',
	'Class:EventRestService/Attribute:json_input' => 'Input~~',
	'Class:EventRestService/Attribute:json_input+' => 'Argument \'json_data\'~~',
	'Class:EventRestService/Attribute:code' => 'Code~~',
	'Class:EventRestService/Attribute:code+' => 'Result code~~',
	'Class:EventRestService/Attribute:json_output' => 'Response~~',
	'Class:EventRestService/Attribute:json_output+' => 'HTTP response (json)~~',
	'Class:EventRestService/Attribute:provider' => 'Provider~~',
	'Class:EventRestService/Attribute:provider+' => 'PHP class implementing the expected operation~~',
));

//
// Class: EventLoginUsage
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:EventLoginUsage' => 'ログイン方法',
	'Class:EventLoginUsage+' => 'アプリケーションへ接続します。',
	'Class:EventLoginUsage/Attribute:user_id' => 'ログイン',
	'Class:EventLoginUsage/Attribute:user_id+' => 'ログイン',
	'Class:EventLoginUsage/Attribute:contact_name' => 'ユーザ名',
	'Class:EventLoginUsage/Attribute:contact_name+' => 'ユーザ名',
	'Class:EventLoginUsage/Attribute:contact_email' => 'ユーザのEmail',
	'Class:EventLoginUsage/Attribute:contact_email+' => 'ユーザの電子メールアドレス',
));

//
// Class: Action
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Action' => 'カスタムアクション',
	'Class:Action+' => 'ユーザ定義アクション',
	'Class:Action/Attribute:name' => '名前',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => '説明',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => '状態',
	'Class:Action/Attribute:status+' => '稼働中、あるいは?',
	'Class:Action/Attribute:status/Value:test' => 'テスト中',
	'Class:Action/Attribute:status/Value:test+' => 'テスト中',
	'Class:Action/Attribute:status/Value:enabled' => '稼働中',
	'Class:Action/Attribute:status/Value:enabled+' => '稼働中',
	'Class:Action/Attribute:status/Value:disabled' => '非アクティブ',
	'Class:Action/Attribute:status/Value:disabled+' => '非アクティブ',
	'Class:Action/Attribute:trigger_list' => '関連トリガー',
	'Class:Action/Attribute:trigger_list+' => 'このアクションにリンクされたトリガー',
	'Class:Action/Attribute:finalclass' => 'タイプ',
	'Class:Action/Attribute:finalclass+' => 'タイプ',
	'Action:WarningNoTriggerLinked' => 'Warning, no trigger is linked to the action. It will not be active until it has at least 1.~~',
));

//
// Class: ActionNotification
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:ActionNotification' => '通知',
	'Class:ActionNotification+' => '通知(要約)',
));

//
// Class: ActionEmail
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:ActionEmail' => 'メール通知',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:status+' => 'This status drives who will be notified: just the Test recipient, all (To, cc and Bcc) or no-one~~',
	'Class:ActionEmail/Attribute:status/Value:test+' => 'Only the Test recipient is notified~~',
	'Class:ActionEmail/Attribute:status/Value:enabled+' => 'All To, Cc and Bcc emails are notified~~',
	'Class:ActionEmail/Attribute:status/Value:disabled+' => 'The email notification will not be sent~~',
	'Class:ActionEmail/Attribute:test_recipient' => 'テストレシピ',
	'Class:ActionEmail/Attribute:test_recipient+' => '状態がテストの場合の宛先',
	'Class:ActionEmail/Attribute:from' => 'From~~',
	'Class:ActionEmail/Attribute:from+' => '電子メールのヘッダーに挿入されます~~',
	'Class:ActionEmail/Attribute:from_label' => 'From (label)~~',
	'Class:ActionEmail/Attribute:from_label+' => 'Sender display name will be sent into the email header~~',
	'Class:ActionEmail/Attribute:reply_to' => 'Reply to~~',
	'Class:ActionEmail/Attribute:reply_to+' => '電子メールのヘッダーに挿入されます~~',
	'Class:ActionEmail/Attribute:reply_to_label' => 'Reply to (label)~~',
	'Class:ActionEmail/Attribute:reply_to_label+' => 'Reply to display name will be sent into the email header~~',
	'Class:ActionEmail/Attribute:to' => 'To',
	'Class:ActionEmail/Attribute:to+' => 'メールの宛先',
	'Class:ActionEmail/Attribute:cc' => 'Cc',
	'Class:ActionEmail/Attribute:cc+' => 'Carbon Copy',
	'Class:ActionEmail/Attribute:bcc' => 'Bcc',
	'Class:ActionEmail/Attribute:bcc+' => 'Blind Carbon Copy',
	'Class:ActionEmail/Attribute:subject' => 'Subject',
	'Class:ActionEmail/Attribute:subject+' => 'メールの題名',
	'Class:ActionEmail/Attribute:body' => 'Body',
	'Class:ActionEmail/Attribute:body+' => 'メールの本文',
	'Class:ActionEmail/Attribute:importance' => '重要度',
	'Class:ActionEmail/Attribute:importance+' => '重要度フラグ',
	'Class:ActionEmail/Attribute:importance/Value:low' => '低',
	'Class:ActionEmail/Attribute:importance/Value:low+' => '低',
	'Class:ActionEmail/Attribute:importance/Value:normal' => '通常',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => '通常',
	'Class:ActionEmail/Attribute:importance/Value:high' => '高',
	'Class:ActionEmail/Attribute:importance/Value:high+' => '高',
));

//
// Class: Trigger
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:Trigger' => 'トリガー',
	'Class:Trigger+' => 'カスタムイベントハンドラー',
	'Class:Trigger/Attribute:description' => '説明',
	'Class:Trigger/Attribute:description+' => '1行の説明',
	'Class:Trigger/Attribute:action_list' => 'トリガーされたアクション',
	'Class:Trigger/Attribute:action_list+' => 'トリガーが発行された場合に動作するアクション',
	'Class:Trigger/Attribute:finalclass' => 'タイプ',
	'Class:Trigger/Attribute:finalclass+' => 'タイプ',
	'Class:Trigger/Attribute:context' => 'Context~~',
	'Class:Trigger/Attribute:context+' => 'Context to allow the trigger to start~~',
));

//
// Class: TriggerOnObject
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:TriggerOnObject' => 'トリガー(クラス依存)',
	'Class:TriggerOnObject+' => 'オブジェクトの指定されたクラスのトリガー',
	'Class:TriggerOnObject/Attribute:target_class' => 'ターゲットクラス',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
	'Class:TriggerOnObject/Attribute:filter' => 'Filter~~',
	'Class:TriggerOnObject/Attribute:filter+' => 'Limit the object list (of the target class) which will activate the trigger~~',
	'TriggerOnObject:WrongFilterQuery' => 'Wrong filter query: %1$s~~',
	'TriggerOnObject:WrongFilterClass' => 'The filter query must return objects of class \\"%1$s\\"~~',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:TriggerOnPortalUpdate' => 'トリガー（ポータルから更新された時）',
	'Class:TriggerOnPortalUpdate+' => 'エンドユーザがポータルから更新した場合のトリガー',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:TriggerOnStateChange' => '(状態変化の)トリガー',
	'Class:TriggerOnStateChange+' => 'オブジェクトの状態変化のトリガー',
	'Class:TriggerOnStateChange/Attribute:state' => '状態',
	'Class:TriggerOnStateChange/Attribute:state+' => '状態',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:TriggerOnStateEnter' => '入状態トリガー',
	'Class:TriggerOnStateEnter+' => 'オブジェクトの状態へ入る変化（エンター,on entering a state）時のトリガー',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:TriggerOnStateLeave' => '出状態トリガー',
	'Class:TriggerOnStateLeave+' => 'オブジェクトの状態から出る変化（リーブ,on leaving a state）時のトリガー',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:TriggerOnObjectCreate' => 'オブジェクト作成トリガー',
	'Class:TriggerOnObjectCreate+' => '指定されたクラスの(子クラスの)オブジェクト作成時のトリガ',
));

//
// Class: TriggerOnObjectDelete
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:TriggerOnObjectDelete' => 'Trigger (on object deletion)~~',
	'Class:TriggerOnObjectDelete+' => 'Trigger on object deletion of [a child class of] the given class~~',
));

//
// Class: TriggerOnObjectUpdate
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:TriggerOnObjectUpdate' => 'Trigger (on object update)~~',
	'Class:TriggerOnObjectUpdate+' => 'Trigger on object update of [a child class of] the given class~~',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => 'Target fields~~',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => '~~',
));

//
// Class: TriggerOnObjectMention
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:TriggerOnObjectMention' => 'Trigger (on object mention)~~',
	'Class:TriggerOnObjectMention+' => 'Trigger on mention (@xxx) of an object of [a child class of] the given class in a log attribute~~',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter' => 'Mentioned filter~~',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter+' => 'Limit the list of mentioned objects which will activate the trigger. If empty, any mentioned object (of any class) will activate it.~~',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:TriggerOnThresholdReached' => 'トリガー (on threshold)',
	'Class:TriggerOnThresholdReached+' => 'トリガー (on Stop-Watch threshold reached)',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'ストップウオッチ',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'しきい値',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '',
));

//
// Class: lnkTriggerAction
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:lnkTriggerAction' => 'トリガ/アクション',
	'Class:lnkTriggerAction+' => 'トリガとアクション間のリンク',
	'Class:lnkTriggerAction/Attribute:action_id' => 'アクション',
	'Class:lnkTriggerAction/Attribute:action_id+' => '実行されるアクション',
	'Class:lnkTriggerAction/Attribute:action_name' => 'アクション',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'トリガ',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'トリガ',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => '順序',
	'Class:lnkTriggerAction/Attribute:order+' => 'アクション実行順序',
));

//
// Synchro Data Source
//
Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:SynchroDataSource/Attribute:name' => '名前',
	'Class:SynchroDataSource/Attribute:name+' => '名前',
	'Class:SynchroDataSource/Attribute:description' => '説明',
	'Class:SynchroDataSource/Attribute:status' => '状態',
	'Class:SynchroDataSource/Attribute:scope_class' => 'ターゲットクラス',
	'Class:SynchroDataSource/Attribute:user_id' => 'ユーザ',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => '通知する連絡先',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'エラーが発生した場合に通知する連絡先。',
	'Class:SynchroDataSource/Attribute:url_icon' => 'アイコンのハイパーリンク',
	'Class:SynchroDataSource/Attribute:url_icon+' => ITOP_APPLICATION_SHORT.'が同期されたアプリケーションを示すハイパーリンク（小さな）イメージ',
	'Class:SynchroDataSource/Attribute:url_application' => 'アプリケーションのハイパーリンク',
	'Class:SynchroDataSource/Attribute:url_application+' => ITOP_APPLICATION_SHORT.'が同期化された外部アプリケーションの'.ITOP_APPLICATION_SHORT.'オブジェクトへのハイパーリンク（該当する場合）。可能なプレースホルダ: $this->attribute$ and $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => '調整ポリシー',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => '全データロードの間隔',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => '全データの完全な再ロードを最低ここに指定されている間隔で行う必要があります。',
	'Class:SynchroDataSource/Attribute:action_on_zero' => '検索結果0件時のアクション',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => '検索結果としてオブジェクトが何も返さない場合に実行されるアクション',
	'Class:SynchroDataSource/Attribute:action_on_one' => '検索結果１件時のアクション',
	'Class:SynchroDataSource/Attribute:action_on_one+' => '検索結果として一つのみのオブジェクトが返されたときに実行されるアクション',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => '検索結果複数時のアクション',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => '検索結果として二つ以上のオブジェクトが返されたときに実行されるアクション',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => '許可されたユーザ',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => '同期されたオブジェクトの削除が許可されたユーザ',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => '誰もいない',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => '管理者のみ',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => '全ての許可されたユーザ',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => '更新ルール',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => '構文: フィールド名:値; ...',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => '保持時間',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => '廃止されたオブジェクトを削除するまでに保持しておく時間',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'データテーブル',
	'Class:SynchroDataSource/Attribute:database_table_name+' => '同期データを保存するテーブル名。 もし、空欄の場合は、規定の名前が計算されます。',
	'SynchroDataSource:Description' => '説明',
	'SynchroDataSource:Reconciliation' => '検索と調整',
	'SynchroDataSource:Deletion' => '削除ルール',
	'SynchroDataSource:Status' => '状態',
	'SynchroDataSource:Information' => 'インフォメーション',
	'SynchroDataSource:Definition' => '定義',
	'Core:SynchroAttributes' => '属性',
	'Core:SynchroStatus' => '状態',
	'Core:Synchro:ErrorsLabel' => 'エラー',
	'Core:Synchro:CreatedLabel' => '作成',
	'Core:Synchro:ModifiedLabel' => '修正',
	'Core:Synchro:UnchangedLabel' => '無変更',
	'Core:Synchro:ReconciledErrorsLabel' => 'エラー',
	'Core:Synchro:ReconciledLabel' => '調整',
	'Core:Synchro:ReconciledNewLabel' => '新',
	'Core:SynchroReconcile:Yes' => 'はい',
	'Core:SynchroReconcile:No' => 'いいえ',
	'Core:SynchroUpdate:Yes' => 'はい',
	'Core:SynchroUpdate:No' => 'いいえ',
	'Core:Synchro:LastestStatus' => '最新の状態',
	'Core:Synchro:History' => '同期履歴',
	'Core:Synchro:NeverRun' => 'この同期は実行されたことがありません。ログはありません。',
	'Core:Synchro:SynchroEndedOn_Date' => '最後の同期は %1$s に終了しました。',
	'Core:Synchro:SynchroRunningStartedOn_Date' => '同期は %1$s に始まり、現在実行中です。',
	'Menu:DataSources' => '同期データソース', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => '全ての同期データソース', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Core:Synchro:label_repl_ignored' => '無視 (%1$s)',
	'Core:Synchro:label_repl_disappeared' => '消えた (%1$s)',
	'Core:Synchro:label_repl_existing' => '存在 (%1$s)',
	'Core:Synchro:label_repl_new' => '新しい (%1$s)',
	'Core:Synchro:label_obj_deleted' => '削除 (%1$s)',
	'Core:Synchro:label_obj_obsoleted' => '廃止 (%1$s)',
	'Core:Synchro:label_obj_disappeared_errors' => 'エラー (%1$s)',
	'Core:Synchro:label_obj_disappeared_no_action' => '何もしない (%1$s)',
	'Core:Synchro:label_obj_unchanged' => '無変更 (%1$s)',
	'Core:Synchro:label_obj_updated' => '更新 (%1$s)',
	'Core:Synchro:label_obj_updated_errors' => 'エラー (%1$s)',
	'Core:Synchro:label_obj_new_unchanged' => '無変更 (%1$s)',
	'Core:Synchro:label_obj_new_updated' => '無変更 (%1$s)',
	'Core:Synchro:label_obj_created' => '作成 (%1$s)',
	'Core:Synchro:label_obj_new_errors' => 'エラー (%1$s)',
	'Core:SynchroLogTitle' => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica' => 'レプリカプロセス: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => '少なくとも一つの調整キーが必要です。または、調整ポリシーは主キーを使用しなければなりません。',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'オブジェクトは廃止としてマークされた後に削除されますので、削除の保存期間を指定する必要があります。',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => '廃止されたオブジェクトは更新されます、しかし、更新は指定されていません。',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'テーブル %1$s は、データベース中にすでに存在しています。 同期データテーブルには、別の名前をお使いください。',
	'Core:SynchroReplica:PublicData' => 'パブリックデータ',
	'Core:SynchroReplica:PrivateDetails' => 'プライベート詳細',
	'Core:SynchroReplica:BackToDataSource' => '同期データソースへ戻る: %1$s',
	'Core:SynchroReplica:ListOfReplicas' => 'レプリカのリスト',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id (主キー)',
	'Core:SynchroAtt:attcode' => '属性',
	'Core:SynchroAtt:attcode+' => 'オブジェクトのフィールド',
	'Core:SynchroAtt:reconciliation' => '調整?',
	'Core:SynchroAtt:reconciliation+' => '検索に使用',
	'Core:SynchroAtt:update' => '更新?',
	'Core:SynchroAtt:update+' => 'オブジェクトの更新のため使用',
	'Core:SynchroAtt:update_policy' => '更新ポリシー',
	'Core:SynchroAtt:update_policy+' => '更新されたフィールドの振る舞い',
	'Core:SynchroAtt:reconciliation_attcode' => '調整キー',
	'Core:SynchroAtt:reconciliation_attcode+' => '外部キー調整用の属性コード',
	'Core:SyncDataExchangeComment' => '(データ同期)',
	'Core:Synchro:ListOfDataSources' => 'データソースのリスト:',
	'Core:Synchro:LastSynchro' => '最後の同期:',
	'Core:Synchro:ThisObjectIsSynchronized' => 'このオブジェクトは、外部データソースと同期されます。',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'このオブジェクトは、外部データソース%1$sにより<b>作成</b>されました。',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'オブジェクトは、外部データソース%1$sにより削除可能です。',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'このオブジェクトは、外部データソースに保持されているので削除できません。',
	'TitleSynchroExecution' => '同期の実行',
	'Class:SynchroDataSource:DataTable' => 'データベーステーブル: %1$s',
	'Core:SyncDataSourceObsolete' => 'データソースは廃止とマークされています。操作はキャンセルされました。',
	'Core:SyncDataSourceAccessRestriction' => '管理者またはデータ·ソースに指定されたユーザーのみ、この操作を実行することができます。操作はキャンセルされました。',
	'Core:SyncTooManyMissingReplicas' => '暫くの間全てのレコードは変更されていません。（全てのオブジェクトが削除される可能性があります。）同期テーブルへ書き込むプロセスがまだ実行中であることを確認ください。操作は、キャンセルされました。',
	'Core:SyncSplitModeCLIOnly' => 'CLIモードでの実行時のみチャンクで同期を実行することが出来ます。',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s レプリカ、 %2$s エラー、 %3$s 警告。',
	'Core:SynchroReplica:TargetObject' => '同期されたオブジェクト: %1$s',
	'Class:AsyncSendEmail' => '電子メール (非同期)',
	'Class:AsyncSendEmail/Attribute:to' => 'To',
	'Class:AsyncSendEmail/Attribute:subject' => '件名',
	'Class:AsyncSendEmail/Attribute:body' => '本文',
	'Class:AsyncSendEmail/Attribute:header' => 'ヘッダー',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => '暗号化パスワード',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => '以前の値',
	'Class:CMDBChangeOpSetAttributeEncrypted' => '暗号化フィールド',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => '以前の値',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'ケースログ',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => '最後のエントリー',
	'Class:SynchroDataSource' => '同期データソース',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => '実装中',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => '廃止済',
	'Class:SynchroDataSource/Attribute:status/Value:production' => '稼働中',
	'Class:SynchroDataSource/Attribute:scope_restriction' => '範囲の制限',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => '属性を使用',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => '主キーフィールドを使用',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => '作成',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'エラー',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'エラー',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => '更新',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => '作成',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'エラー',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => '最初を採用 (ランダム?)',
	'Class:SynchroDataSource/Attribute:delete_policy' => '削除ポリシー',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => '削除',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => '無視',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => '更新',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => '更新そして削除',
	'Class:SynchroDataSource/Attribute:attribute_list' => '属性リスト',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => '管理者のみ',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => '誰でもがそのようなオブジェクトを削除出来ます。',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => '誰もない',
	'Class:SynchroAttribute' => '同期属性',
	'Class:SynchroAttribute/Attribute:sync_source_id' => '同期データソース',
	'Class:SynchroAttribute/Attribute:attcode' => '属性コード',
	'Class:SynchroAttribute/Attribute:update' => '更新',
	'Class:SynchroAttribute/Attribute:reconcile' => '調整',
	'Class:SynchroAttribute/Attribute:update_policy' => '更新ポリシー',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'ロック',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'アンロック',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => '空の場合は、初期化',
	'Class:SynchroAttribute/Attribute:finalclass' => 'クラス',
	'Class:SynchroAttExtKey' => '同期属性 (外部キー)',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => '調整属性',
	'Class:SynchroAttLinkSet' => '同期属性 (リンクセット)',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => '行の区切り',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => '属性区切り',
	'Class:SynchroLog' => '同期ログ',
	'Class:SynchroLog/Attribute:sync_source_id' => '同期データソース',
	'Class:SynchroLog/Attribute:start_date' => '開始日',
	'Class:SynchroLog/Attribute:end_date' => '終了日',
	'Class:SynchroLog/Attribute:status' => '状態',
	'Class:SynchroLog/Attribute:status/Value:completed' => '完了',
	'Class:SynchroLog/Attribute:status/Value:error' => 'エラー',
	'Class:SynchroLog/Attribute:status/Value:running' => '実行中',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'のレプリカ　',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'レプリカ合計　',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'オブジェクト削除　',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => '削除中のエラー　',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'オブジェクト廃止　',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => '廃止中のエラー　',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'オブジェクト作成　',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => '作成中のエラー　',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'オブジェクト更新　',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => '更新中のエラー　',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => '調整中のエラー　',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'レプリカ消　',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => ' オブジェクトは更新されました',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => ' オブジェクトは変更されていません',
	'Class:SynchroLog/Attribute:last_error' => '最後のエラー',
	'Class:SynchroLog/Attribute:traces' => 'トレース',
	'Class:SynchroReplica' => '同期レプリカ',
	'Class:SynchroReplica/Attribute:sync_source_id' => '同期データソース',
	'Class:SynchroReplica/Attribute:dest_id' => '同期先オブジェクト (ID)',
	'Class:SynchroReplica/Attribute:dest_class' => '同期先タイプ',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'ラストシーン',
	'Class:SynchroReplica/Attribute:status' => '状態',
	'Class:SynchroReplica/Attribute:status/Value:modified' => '修正済み',
	'Class:SynchroReplica/Attribute:status/Value:new' => '新規',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => '廃止',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => '孤立',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => '同期済み',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'オブジェクト作成 ?',
	'Class:SynchroReplica/Attribute:status_last_error' => '最後のエラー',
	'Class:SynchroReplica/Attribute:status_last_warning' => '警告',
	'Class:SynchroReplica/Attribute:info_creation_date' => '作成日',
	'Class:SynchroReplica/Attribute:info_last_modified' => '最終修正日',
	'Class:appUserPreferences' => 'ユーザプリファレンス',
	'Class:appUserPreferences/Attribute:userid' => 'ユーザ',
	'Class:appUserPreferences/Attribute:preferences' => 'プリファレンス',
	'Core:ExecProcess:Code1' => '間違ったコマンドまたはエラーで終了したコマンド（例えば、間違ったスクリプト名）',
	'Core:ExecProcess:Code255' => 'PHP エラー (parsing, or runtime)',

	// Attribute Duration
	'Core:Duration_Seconds' => '%1$ds',
	'Core:Duration_Minutes_Seconds' => '%1$d分 %2$d秒',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$d時 %2$d分 %3$d秒',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$s日 %2$d時 %3$d分 %4$d秒',

	// Explain working time computing
	'Core:ExplainWTC:ElapsedTime' => 'Time elapsed (stored as \\"%1$s\\")~~',
	'Core:ExplainWTC:StopWatch-TimeSpent' => 'Time spent for \\"%1$s\\"~~',
	'Core:ExplainWTC:StopWatch-Deadline' => 'Deadline for \\"%1$s\\" at %2$d%%~~',

	// Bulk export
	'Core:BulkExport:MissingParameter_Param' => 'Missing parameter \\"%1$s\\"~~',
	'Core:BulkExport:InvalidParameter_Query' => 'Invalid value for the parameter \\"query\\". There is no Query Phrasebook corresponding to the id: \\"%1$s\\".~~',
	'Core:BulkExport:ExportFormatPrompt' => 'Export format:~~',
	'Core:BulkExportOf_Class' => '%1$s Export~~',
	'Core:BulkExport:ClickHereToDownload_FileName' => 'Click here to download %1$s~~',
	'Core:BulkExport:ExportResult' => 'Result of the export:~~',
	'Core:BulkExport:RetrievingData' => 'Retrieving data...~~',
	'Core:BulkExport:HTMLFormat' => 'Web Page (*.html)~~',
	'Core:BulkExport:CSVFormat' => 'Comma Separated Values (*.csv)~~',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007 or newer (*.xlsx)~~',
	'Core:BulkExport:PDFFormat' => 'PDF Document (*.pdf)~~',
	'Core:BulkExport:DragAndDropHelp' => 'Drag and drop the columns\' headers to arrange the columns. Preview of %1$s lines. Total number of lines to export: %2$s.~~',
	'Core:BulkExport:EmptyPreview' => 'Select the columns to be exported from the list above~~',
	'Core:BulkExport:ColumnsOrder' => 'Columns order~~',
	'Core:BulkExport:AvailableColumnsFrom_Class' => 'Available columns from %1$s~~',
	'Core:BulkExport:NoFieldSelected' => 'Select at least one column to be exported~~',
	'Core:BulkExport:CheckAll' => 'Check All~~',
	'Core:BulkExport:UncheckAll' => 'Uncheck All~~',
	'Core:BulkExport:ExportCancelledByUser' => 'Export cancelled by the user~~',
	'Core:BulkExport:CSVOptions' => 'CSV Options~~',
	'Core:BulkExport:CSVLocalization' => 'Localization~~',
	'Core:BulkExport:PDFOptions' => 'PDF Options~~',
	'Core:BulkExport:PDFPageFormat' => 'Page Format~~',
	'Core:BulkExport:PDFPageSize' => 'Page Size:~~',
	'Core:BulkExport:PageSize-A4' => 'A4~~',
	'Core:BulkExport:PageSize-A3' => 'A3~~',
	'Core:BulkExport:PageSize-Letter' => 'Letter~~',
	'Core:BulkExport:PDFPageOrientation' => 'Page Orientation:~~',
	'Core:BulkExport:PageOrientation-L' => 'Landscape~~',
	'Core:BulkExport:PageOrientation-P' => 'Portrait~~',
	'Core:BulkExport:XMLFormat' => 'XML file (*.xml)~~',
	'Core:BulkExport:XMLOptions' => 'XML Options~~',
	'Core:BulkExport:SpreadsheetFormat' => 'Spreadsheet HTML format (*.html)~~',
	'Core:BulkExport:SpreadsheetOptions' => 'Spreadsheet Options~~',
	'Core:BulkExport:OptionNoLocalize' => 'Export Code instead of Label~~',
	'Core:BulkExport:OptionLinkSets' => 'Include linked objects~~',
	'Core:BulkExport:OptionFormattedText' => 'Preserve text formatting~~',
	'Core:BulkExport:ScopeDefinition' => 'Definition of the objects to export~~',
	'Core:BulkExportLabelOQLExpression' => 'OQL Query:~~',
	'Core:BulkExportLabelPhrasebookEntry' => 'Query Phrasebook Entry:~~',
	'Core:BulkExportMessageEmptyOQL' => 'Please enter a valid OQL query.~~',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Please select a valid phrasebook entry.~~',
	'Core:BulkExportQueryPlaceholder' => 'Type an OQL query here...~~',
	'Core:BulkExportCanRunNonInteractive' => 'Click here to run the export in non-interactive mode.~~',
	'Core:BulkExportLegacyExport' => 'Click here to access the legacy export.~~',
	'Core:BulkExport:XLSXOptions' => 'Excel Options~~',
	'Core:BulkExport:TextFormat' => 'Text fields containing some HTML markup~~',
	'Core:BulkExport:DateTimeFormat' => 'Date and Time format~~',
	'Core:BulkExport:DateTimeFormatDefault_Example' => 'Default format (%1$s), e.g. %2$s~~',
	'Core:BulkExport:DateTimeFormatCustom_Format' => 'Custom format: %1$s~~',
	'Core:BulkExport:PDF:PageNumber' => 'Page %1$s~~',
	'Core:DateTime:Placeholder_d' => 'DD~~', // Day of the month: 2 digits (with leading zero)
	'Core:DateTime:Placeholder_j' => 'D~~', // Day of the month: 1 or 2 digits (without leading zero)
	'Core:DateTime:Placeholder_m' => 'MM~~', // Month on 2 digits i.e. 01-12
	'Core:DateTime:Placeholder_n' => 'M~~', // Month on 1 or 2 digits 1-12
	'Core:DateTime:Placeholder_Y' => 'YYYY~~', // Year on 4 digits
	'Core:DateTime:Placeholder_y' => 'YY~~', // Year on 2 digits
	'Core:DateTime:Placeholder_H' => 'hh~~', // Hour 00..23
	'Core:DateTime:Placeholder_h' => 'h~~', // Hour 01..12
	'Core:DateTime:Placeholder_G' => 'hh~~', // Hour 0..23
	'Core:DateTime:Placeholder_g' => 'h~~', // Hour 1..12
	'Core:DateTime:Placeholder_a' => 'am/pm~~', // am/pm (lowercase)
	'Core:DateTime:Placeholder_A' => 'AM/PM~~', // AM/PM (uppercase)
	'Core:DateTime:Placeholder_i' => 'mm~~', // minutes, 2 digits: 00..59
	'Core:DateTime:Placeholder_s' => 'ss~~', // seconds, 2 digits 00..59
	'Core:Validator:Default' => 'Wrong format~~',
	'Core:Validator:Mandatory' => 'Please, fill this field~~',
	'Core:Validator:MustBeInteger' => 'Must be an integer~~',
	'Core:Validator:MustSelectOne' => 'Please, select one~~',
));

//
// Class: TagSetFieldData
//
Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:TagSetFieldData' => '%2$s for class %1$s~~',
	'Class:TagSetFieldData+' => '~~',

	'Class:TagSetFieldData/Attribute:code' => 'Code~~',
	'Class:TagSetFieldData/Attribute:code+' => 'Internal code. Must contain at least 3 alphanumeric characters~~',
	'Class:TagSetFieldData/Attribute:label' => 'Label~~',
	'Class:TagSetFieldData/Attribute:label+' => 'Displayed label~~',
	'Class:TagSetFieldData/Attribute:description' => 'Description~~',
	'Class:TagSetFieldData/Attribute:description+' => 'Description~~',
	'Class:TagSetFieldData/Attribute:finalclass' => 'Tag class~~',
	'Class:TagSetFieldData/Attribute:obj_class' => 'Object class~~',
	'Class:TagSetFieldData/Attribute:obj_attcode' => 'Field code~~',

	'Core:TagSetFieldData:ErrorDeleteUsedTag' => 'Used tags cannot be deleted~~',
	'Core:TagSetFieldData:ErrorDuplicateTagCodeOrLabel' => 'Tags codes or labels must be unique~~',
	'Core:TagSetFieldData:ErrorTagCodeSyntax' => 'Tags code must contain between 3 and %1$d alphanumeric characters~~',
	'Core:TagSetFieldData:ErrorTagCodeReservedWord' => 'The chosen tag code is a reserved word~~',
	'Core:TagSetFieldData:ErrorTagLabelSyntax' => 'Tags label must not contain \'%1$s\' nor be empty~~',
	'Core:TagSetFieldData:ErrorCodeUpdateNotAllowed' => 'Tags Code cannot be changed when used~~',
	'Core:TagSetFieldData:ErrorClassUpdateNotAllowed' => 'Tags "Object Class" cannot be changed~~',
	'Core:TagSetFieldData:ErrorAttCodeUpdateNotAllowed' => 'Tags "Attribute Code" cannot be changed~~',
	'Core:TagSetFieldData:WhereIsThisTagTab' => 'Tag usage (%1$d)~~',
	'Core:TagSetFieldData:NoEntryFound' => 'No entry found for this tag~~',
));

//
// Class: DBProperty
//
Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:DBProperty' => 'DB property~~',
	'Class:DBProperty+' => '~~',
	'Class:DBProperty/Attribute:name' => 'Name~~',
	'Class:DBProperty/Attribute:name+' => '~~',
	'Class:DBProperty/Attribute:description' => 'Description~~',
	'Class:DBProperty/Attribute:description+' => '~~',
	'Class:DBProperty/Attribute:value' => 'Value~~',
	'Class:DBProperty/Attribute:value+' => '~~',
	'Class:DBProperty/Attribute:change_date' => 'Change date~~',
	'Class:DBProperty/Attribute:change_date+' => '~~',
	'Class:DBProperty/Attribute:change_comment' => 'Change comment~~',
	'Class:DBProperty/Attribute:change_comment+' => '~~',
));

//
// Class: BackgroundTask
//
Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:BackgroundTask' => 'Background task~~',
	'Class:BackgroundTask+' => '~~',
	'Class:BackgroundTask/Attribute:class_name' => 'Class name~~',
	'Class:BackgroundTask/Attribute:class_name+' => '~~',
	'Class:BackgroundTask/Attribute:first_run_date' => 'First run date~~',
	'Class:BackgroundTask/Attribute:first_run_date+' => '~~',
	'Class:BackgroundTask/Attribute:latest_run_date' => 'Latest run date~~',
	'Class:BackgroundTask/Attribute:latest_run_date+' => '~~',
	'Class:BackgroundTask/Attribute:next_run_date' => 'Next run date~~',
	'Class:BackgroundTask/Attribute:next_run_date+' => '~~',
	'Class:BackgroundTask/Attribute:total_exec_count' => 'Total exec. count~~',
	'Class:BackgroundTask/Attribute:total_exec_count+' => '~~',
	'Class:BackgroundTask/Attribute:latest_run_duration' => 'Latest run duration~~',
	'Class:BackgroundTask/Attribute:latest_run_duration+' => '~~',
	'Class:BackgroundTask/Attribute:min_run_duration' => 'Min. run duration~~',
	'Class:BackgroundTask/Attribute:min_run_duration+' => '~~',
	'Class:BackgroundTask/Attribute:max_run_duration' => 'Max. run duration~~',
	'Class:BackgroundTask/Attribute:max_run_duration+' => '~~',
	'Class:BackgroundTask/Attribute:average_run_duration' => 'Average run duration~~',
	'Class:BackgroundTask/Attribute:average_run_duration+' => '~~',
	'Class:BackgroundTask/Attribute:running' => 'Running~~',
	'Class:BackgroundTask/Attribute:running+' => '~~',
	'Class:BackgroundTask/Attribute:status' => 'Status~~',
	'Class:BackgroundTask/Attribute:status+' => '~~',
));

//
// Class: AsyncTask
//
Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:AsyncTask' => 'Async. task~~',
	'Class:AsyncTask+' => '~~',
	'Class:AsyncTask/Attribute:created' => 'Created~~',
	'Class:AsyncTask/Attribute:created+' => '~~',
	'Class:AsyncTask/Attribute:started' => 'Started~~',
	'Class:AsyncTask/Attribute:started+' => '~~',
	'Class:AsyncTask/Attribute:planned' => 'Planned~~',
	'Class:AsyncTask/Attribute:planned+' => '~~',
	'Class:AsyncTask/Attribute:event_id' => 'Event~~',
	'Class:AsyncTask/Attribute:event_id+' => '~~',
	'Class:AsyncTask/Attribute:finalclass' => 'Final class~~',
	'Class:AsyncTask/Attribute:finalclass+' => '~~',
	'Class:AsyncTask/Attribute:status' => 'Status~~',
	'Class:AsyncTask/Attribute:status+' => '~~',
	'Class:AsyncTask/Attribute:remaining_retries' => 'Remaining retries~~',
	'Class:AsyncTask/Attribute:remaining_retries+' => '~~',
	'Class:AsyncTask/Attribute:last_error_code' => 'Last error code~~',
	'Class:AsyncTask/Attribute:last_error_code+' => '~~',
	'Class:AsyncTask/Attribute:last_error' => 'Last error~~',
	'Class:AsyncTask/Attribute:last_error+' => '~~',
	'Class:AsyncTask/Attribute:last_attempt' => 'Last attempt~~',
	'Class:AsyncTask/Attribute:last_attempt+' => '~~',
    'Class:AsyncTask:InvalidConfig_Class_Keys' => 'Invalid format for the configuration of "async_task_retries[%1$s]". Expecting an array with the following keys: %2$s~~',
    'Class:AsyncTask:InvalidConfig_Class_InvalidKey_Keys' => 'Invalid format for the configuration of "async_task_retries[%1$s]": unexpected key "%2$s". Expecting only the following keys: %3$s~~',
));

//
// Class: AbstractResource
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:AbstractResource' => 'Abstract Resource~~',
	'Class:AbstractResource+' => '~~',
));

//
// Class: ResourceAdminMenu
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:ResourceAdminMenu' => 'Resource Admin Menu~~',
	'Class:ResourceAdminMenu+' => '~~',
));

//
// Class: ResourceRunQueriesMenu
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:ResourceRunQueriesMenu' => 'Resource Run Queries Menu~~',
	'Class:ResourceRunQueriesMenu+' => '~~',
));

//
// Class: Action
//

Dict::Add('JA JP', 'Japanese', '日本語', array(
	'Class:ResourceSystemMenu' => 'Resource System Menu~~',
	'Class:ResourceSystemMenu+' => '~~',
));



