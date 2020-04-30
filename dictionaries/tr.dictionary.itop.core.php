<?php
// Copyright (C) 2010-2017 Combodo SARL
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
 * Localized data
 *
 * @author      Izzet Sirin <izzet.sirin@htr.com.tr>
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
//////////////////////////////////////////////////////////////////////
// Classes in 'core/cmdb'
//////////////////////////////////////////////////////////////////////
//
//
// Class: CMDBChange
//
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Core:DeletedObjectLabel' => '%1s (deleted)~~',
	'Core:DeletedObjectTip' => 'The object has been deleted on %1$s (%2$s)~~',

	'Core:UnknownObjectLabel' => 'Object not found (class: %1$s, id: %2$d)~~',
	'Core:UnknownObjectTip' => 'The object could not be found. It may have been deleted some time ago and the log has been purged since.~~',

	'Core:UniquenessDefaultError' => 'Uniqueness rule \'%1$s\' in error~~',

	'Core:AttributeLinkedSet' => 'Array of objects~~',
	'Core:AttributeLinkedSet+' => 'Any kind of objects of the same class or subclass~~',

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

	'Core:AttributeLinkedSetIndirect' => 'Array of objects (N-N)~~',
	'Core:AttributeLinkedSetIndirect+' => 'Any kind of objects [subclass] of the same class~~',

	'Core:AttributeInteger' => 'Integer~~',
	'Core:AttributeInteger+' => 'Numeric value (could be negative)~~',

	'Core:AttributeDecimal' => 'Decimal~~',
	'Core:AttributeDecimal+' => 'Decimal value (could be negative)~~',

	'Core:AttributeBoolean' => 'Boolean~~',
	'Core:AttributeBoolean+' => 'Boolean~~',
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

	'Core:AttributeString' => 'String~~',
	'Core:AttributeString+' => 'Alphanumeric string~~',

	'Core:AttributeClass' => 'Class~~',
	'Core:AttributeClass+' => 'Class~~',

	'Core:AttributeApplicationLanguage' => 'User language~~',
	'Core:AttributeApplicationLanguage+' => 'Language and country (EN US)~~',

	'Core:AttributeFinalClass' => 'Class (auto)~~',
	'Core:AttributeFinalClass+' => 'Real class of the object (automatically created by the core)~~',

	'Core:AttributePassword' => 'Password~~',
	'Core:AttributePassword+' => 'Password of an external device~~',

	'Core:AttributeEncryptedString' => 'Encrypted string~~',
	'Core:AttributeEncryptedString+' => 'String encrypted with a local key~~',
	'Core:AttributeEncryptUnknownLibrary' => 'Encryption library specified (%1$s) unknown~~',
	'Core:AttributeEncryptFailedToDecrypt' => '** decryption error **~~',

	'Core:AttributeText' => 'Text~~',
	'Core:AttributeText+' => 'Multiline character string~~',

	'Core:AttributeHTML' => 'HTML~~',
	'Core:AttributeHTML+' => 'HTML string~~',

	'Core:AttributeEmailAddress' => 'Email address~~',
	'Core:AttributeEmailAddress+' => 'Email address~~',

	'Core:AttributeIPAddress' => 'IP address~~',
	'Core:AttributeIPAddress+' => 'IP address~~',

	'Core:AttributeOQL' => 'OQL~~',
	'Core:AttributeOQL+' => 'Object Query Langage expression~~',

	'Core:AttributeEnum' => 'Enum~~',
	'Core:AttributeEnum+' => 'List of predefined alphanumeric strings~~',

	'Core:AttributeTemplateString' => 'Template string~~',
	'Core:AttributeTemplateString+' => 'String containing placeholders~~',

	'Core:AttributeTemplateText' => 'Template text~~',
	'Core:AttributeTemplateText+' => 'Text containing placeholders~~',

	'Core:AttributeTemplateHTML' => 'Template HTML~~',
	'Core:AttributeTemplateHTML+' => 'HTML containing placeholders~~',

	'Core:AttributeDateTime' => 'Date/time~~',
	'Core:AttributeDateTime+' => 'Date and time (year-month-day hh:mm:ss)~~',
	'Core:AttributeDateTime?SmartSearch' => '
<p>
	Date format:<br/>
	<b>%1$ss</b><br/>
	Example: %2$s
</p>
<p>
Operators:<br/>
	<b>&gt;</b><em>date</em><br/>
	<b>&lt;</b><em>date</em><br/>
	<b>[</b><em>date</em>,<em>date</em><b>]</b>
</p>
<p>
If the time is omitted, it defaults to 00:00:00
</p>~~',

	'Core:AttributeDate' => 'Date~~',
	'Core:AttributeDate+' => 'Date (year-month-day)~~',
	'Core:AttributeDate?SmartSearch' => '
<p>
	Date format:<br/>
	<b>%1$s</b><br/>
	Example: %2$s
</p>
<p>
Operators:<br/>
	<b>&gt;</b><em>date</em><br/>
	<b>&lt;</b><em>date</em><br/>
	<b>[</b><em>date</em>,<em>date</em><b>]</b>
</p>~~',

	'Core:AttributeDeadline' => 'Deadline~~',
	'Core:AttributeDeadline+' => 'Date, displayed relatively to the current time~~',

	'Core:AttributeExternalKey' => 'External key~~',
	'Core:AttributeExternalKey+' => 'External (or foreign) key~~',

	'Core:AttributeHierarchicalKey' => 'Hierarchical Key~~',
	'Core:AttributeHierarchicalKey+' => 'External (or foreign) key to the parent~~',

	'Core:AttributeExternalField' => 'External field~~',
	'Core:AttributeExternalField+' => 'Field mapped to an external key~~',

	'Core:AttributeURL' => 'URL~~',
	'Core:AttributeURL+' => 'Absolute or relative URL as a text string~~',

	'Core:AttributeBlob' => 'Blob~~',
	'Core:AttributeBlob+' => 'Any binary content (document)~~',

	'Core:AttributeOneWayPassword' => 'One way password~~',
	'Core:AttributeOneWayPassword+' => 'One way encrypted (hashed) password~~',

	'Core:AttributeTable' => 'Table~~',
	'Core:AttributeTable+' => 'Indexed array having two dimensions~~',

	'Core:AttributePropertySet' => 'Properties~~',
	'Core:AttributePropertySet+' => 'List of untyped properties (name and value)~~',

	'Core:AttributeFriendlyName' => 'Friendly name~~',
	'Core:AttributeFriendlyName+' => 'Attribute created automatically ; the friendly name is computed after several attributes~~',

	'Core:FriendlyName-Label' => 'Friendly name~~',
	'Core:FriendlyName-Description' => 'Friendly name~~',

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

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:CMDBChange' => 'Değişiklik',
	'Class:CMDBChange+' => 'Değişiklik izleme',
	'Class:CMDBChange/Attribute:date' => 'tarih',
	'Class:CMDBChange/Attribute:date+' => 'değişikliğin yapıldığı tarih',
	'Class:CMDBChange/Attribute:userinfo' => 'diğer bilgiler',
	'Class:CMDBChange/Attribute:userinfo+' => 'ilave bilgiler',
));

//
// Class: CMDBChangeOp
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:CMDBChangeOp' => 'Değişiklik işlemi',
	'Class:CMDBChangeOp+' => 'Değişiklik izleme',
	'Class:CMDBChangeOp/Attribute:change' => 'değişiklik',
	'Class:CMDBChangeOp/Attribute:change+' => 'değişiklik',
	'Class:CMDBChangeOp/Attribute:date' => 'tarih',
	'Class:CMDBChangeOp/Attribute:date+' => 'değişikliğin yapıldığı zaman',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'kullanıcı',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'değişikliğ yapan',
	'Class:CMDBChangeOp/Attribute:objclass' => 'nesne sınıfı',
	'Class:CMDBChangeOp/Attribute:objclass+' => 'nesne sınıfı',
	'Class:CMDBChangeOp/Attribute:objkey' => 'nesne no',
	'Class:CMDBChangeOp/Attribute:objkey+' => 'nesne  no',
	'Class:CMDBChangeOp/Attribute:finalclass' => 'tip',
	'Class:CMDBChangeOp/Attribute:finalclass+' => '',
));

//
// Class: CMDBChangeOpCreate
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:CMDBChangeOpCreate' => 'nesne yaratımı',
	'Class:CMDBChangeOpCreate+' => 'Nesne Yaratım izleme',
));

//
// Class: CMDBChangeOpDelete
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:CMDBChangeOpDelete' => 'nesne silimi',
	'Class:CMDBChangeOpDelete+' => 'Nesne silme izleme',
));

//
// Class: CMDBChangeOpSetAttribute
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:CMDBChangeOpSetAttribute' => 'nesne değişikliği',
	'Class:CMDBChangeOpSetAttribute+' => 'Nesne değişiminin izlemesi',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode' => 'Özellik',
	'Class:CMDBChangeOpSetAttribute/Attribute:attcode+' => 'Değişen özelliğin kodu',
));

//
// Class: CMDBChangeOpSetAttributeScalar
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:CMDBChangeOpSetAttributeScalar' => 'özellik değişimi',
	'Class:CMDBChangeOpSetAttributeScalar+' => 'Nesne özellik değişimi izleme',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue' => 'Önceki değer',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => 'önceki değer',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Yeni değer',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => 'yeni değer',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Change:ObjectCreated' => 'Nesne yaratıldı',
	'Change:ObjectDeleted' => 'Nesne silindi',
	'Change:ObjectModified' => 'Object modified~~',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s\'nin değeri %2$s olarak atandı (önceki değer: %3$s)',
	'Change:AttName_SetTo' => '%1$s\'nin değeri %2$s olarak atandı',
	'Change:Text_AppendedTo_AttName' => '%2$s\'ye %1$s eklendi',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$\'nin değeri deiştirildi, önceki değer: %2$s',
	'Change:AttName_Changed' => '%1$s değiştirildi',
	'Change:AttName_EntryAdded' => '%1$s modified, new entry added.~~',
	'Change:LinkSet:Added' => 'added %1$s~~',
	'Change:LinkSet:Removed' => 'removed %1$s~~',
	'Change:LinkSet:Modified' => 'modified %1$s~~',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'tarih değişimi',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'tarih değişim izleme',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Önceki veri',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => 'önceki değer',
));

//
// Class: CMDBChangeOpSetAttributeText
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:CMDBChangeOpSetAttributeText' => 'metin değişikliği',
	'Class:CMDBChangeOpSetAttributeText+' => 'metin değişikliği izleme',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata' => 'Önceki veri',
	'Class:CMDBChangeOpSetAttributeText/Attribute:prevdata+' => 'önceki değer',
));

//
// Class: Event
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Event' => 'Olay kaydı',
	'Class:Event+' => 'Uygulama olayı',
	'Class:Event/Attribute:message' => 'mesaj',
	'Class:Event/Attribute:message+' => 'Olay tanımlama',
	'Class:Event/Attribute:date' => 'tarih',
	'Class:Event/Attribute:date+' => 'değişiklik tarihi',
	'Class:Event/Attribute:userinfo' => 'kullanıcı bigileri',
	'Class:Event/Attribute:userinfo+' => 'olay anındaki kullanıcı',
	'Class:Event/Attribute:finalclass' => 'tip',
	'Class:Event/Attribute:finalclass+' => '',
));

//
// Class: EventNotification
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:EventNotification' => 'Olay uyarımı',
	'Class:EventNotification+' => 'Uyarının tarihçesi',
	'Class:EventNotification/Attribute:trigger_id' => 'Uyarı tetikçisi',
	'Class:EventNotification/Attribute:trigger_id+' => 'kullanıcı hesabı',
	'Class:EventNotification/Attribute:action_id' => 'kullanıcı',
	'Class:EventNotification/Attribute:action_id+' => 'kullanıcı hesabı',
	'Class:EventNotification/Attribute:object_id' => 'Nesne belirleyicisi',
	'Class:EventNotification/Attribute:object_id+' => 'nesne belirleyicisi (olayı tetikleyen nesne ?)',
));

//
// Class: EventNotificationEmail
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:EventNotificationEmail' => 'E-posta gönderim işlemi',
	'Class:EventNotificationEmail+' => 'Gönderilen E-posta tarihçesi',
	'Class:EventNotificationEmail/Attribute:to' => 'Kime',
	'Class:EventNotificationEmail/Attribute:to+' => 'Kime',
	'Class:EventNotificationEmail/Attribute:cc' => 'Kopya',
	'Class:EventNotificationEmail/Attribute:cc+' => 'Kopya',
	'Class:EventNotificationEmail/Attribute:bcc' => 'Gizli Kopya',
	'Class:EventNotificationEmail/Attribute:bcc+' => 'Gizli Kopya',
	'Class:EventNotificationEmail/Attribute:from' => 'Kimden',
	'Class:EventNotificationEmail/Attribute:from+' => 'Mesajı gönderen',
	'Class:EventNotificationEmail/Attribute:subject' => 'Konu',
	'Class:EventNotificationEmail/Attribute:subject+' => 'Konu',
	'Class:EventNotificationEmail/Attribute:body' => 'Mesaj',
	'Class:EventNotificationEmail/Attribute:body+' => 'Mesaj',
	'Class:EventNotificationEmail/Attribute:attachments' => 'Attachments~~',
	'Class:EventNotificationEmail/Attribute:attachments+' => '~~',
));

//
// Class: EventIssue
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:EventIssue' => 'Olay ekle',
	'Class:EventIssue+' => 'Olay tipi (uyarı, hata, vb.)',
	'Class:EventIssue/Attribute:issue' => 'Konu',
	'Class:EventIssue/Attribute:issue+' => 'Olan',
	'Class:EventIssue/Attribute:impact' => 'Etkisi',
	'Class:EventIssue/Attribute:impact+' => 'Sonuçları',
	'Class:EventIssue/Attribute:page' => 'Sayfa',
	'Class:EventIssue/Attribute:page+' => 'HTTP giriş noktası',
	'Class:EventIssue/Attribute:arguments_post' => 'Verilen değişkenlerin değerleri',
	'Class:EventIssue/Attribute:arguments_post+' => 'HTTP değişken değerleri',
	'Class:EventIssue/Attribute:arguments_get' => 'URL POST değişken değerleri',
	'Class:EventIssue/Attribute:arguments_get+' => 'HTTP GET değişken değerleri',
	'Class:EventIssue/Attribute:callstack' => 'Çağrım sırası',
	'Class:EventIssue/Attribute:callstack+' => 'Çağrım sırası',
	'Class:EventIssue/Attribute:data' => 'Veri',
	'Class:EventIssue/Attribute:data+' => 'Diğer bilgiler',
));

//
// Class: EventWebService
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:EventWebService' => 'Web service olayı',
	'Class:EventWebService+' => 'web service çağrım sırası',
	'Class:EventWebService/Attribute:verb' => 'Fiil',
	'Class:EventWebService/Attribute:verb+' => 'Operasyonun adı',
	'Class:EventWebService/Attribute:result' => 'Sonuç',
	'Class:EventWebService/Attribute:result+' => 'Genel başarı/başarısızlık',
	'Class:EventWebService/Attribute:log_info' => 'Bilgi kaydı',
	'Class:EventWebService/Attribute:log_info+' => 'Sonuç bilgi kaydı',
	'Class:EventWebService/Attribute:log_warning' => 'Uyarı kaydı',
	'Class:EventWebService/Attribute:log_warning+' => 'Sonuç uyarı kaydı',
	'Class:EventWebService/Attribute:log_error' => 'Hata kaydı',
	'Class:EventWebService/Attribute:log_error+' => 'Sonuç hata kaydı',
	'Class:EventWebService/Attribute:data' => 'Veri',
	'Class:EventWebService/Attribute:data+' => 'Sonuç veri',
));

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
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

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:EventLoginUsage' => 'Login Usage~~',
	'Class:EventLoginUsage+' => 'Connection to the application~~',
	'Class:EventLoginUsage/Attribute:user_id' => 'Login~~',
	'Class:EventLoginUsage/Attribute:user_id+' => 'Login~~',
	'Class:EventLoginUsage/Attribute:contact_name' => 'User Name~~',
	'Class:EventLoginUsage/Attribute:contact_name+' => 'User Name~~',
	'Class:EventLoginUsage/Attribute:contact_email' => 'User Email~~',
	'Class:EventLoginUsage/Attribute:contact_email+' => 'Email Address of the User~~',
));

//
// Class: Action
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Action' => 'Özel işlem',
	'Class:Action+' => 'Kullanıcının tanımladığı işlemler',
	'Class:Action/Attribute:name' => 'Adı',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => 'Tanımlama',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Durum',
	'Class:Action/Attribute:status+' => 'Kullanımda mı?',
	'Class:Action/Attribute:status/Value:test' => 'Test aşamasında',
	'Class:Action/Attribute:status/Value:test+' => 'Test aşamasında',
	'Class:Action/Attribute:status/Value:enabled' => 'Kullanımda',
	'Class:Action/Attribute:status/Value:enabled+' => 'Kullanımda',
	'Class:Action/Attribute:status/Value:disabled' => 'Etkin değil',
	'Class:Action/Attribute:status/Value:disabled+' => 'Etkin değil',
	'Class:Action/Attribute:trigger_list' => 'İlgili tetikleyiciler',
	'Class:Action/Attribute:trigger_list+' => 'İşleme bağlı tetikleyici',
	'Class:Action/Attribute:finalclass' => 'Tip',
	'Class:Action/Attribute:finalclass+' => '',
));

//
// Class: ActionNotification
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:ActionNotification' => 'Bildirim',
	'Class:ActionNotification+' => 'Bildirim (soyut)',
));

//
// Class: ActionEmail
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:ActionEmail' => 'E-posta bildirimi',
	'Class:ActionEmail+' => '',
	'Class:ActionEmail/Attribute:test_recipient' => 'Test alıcısı',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Durumu "Test" olması durumundaki alıcı',
	'Class:ActionEmail/Attribute:from' => 'Kimden',
	'Class:ActionEmail/Attribute:from+' => 'e-posta başlığında gönderilecek',
	'Class:ActionEmail/Attribute:reply_to' => 'Yanıtla',
	'Class:ActionEmail/Attribute:reply_to+' => 'e-posta başlığında gönderilecek',
	'Class:ActionEmail/Attribute:to' => 'Kime',
	'Class:ActionEmail/Attribute:to+' => 'E-posta alıcısı',
	'Class:ActionEmail/Attribute:cc' => 'Kopya',
	'Class:ActionEmail/Attribute:cc+' => 'Kopya',
	'Class:ActionEmail/Attribute:bcc' => 'Gizli kopya',
	'Class:ActionEmail/Attribute:bcc+' => 'Gizli alıcı',
	'Class:ActionEmail/Attribute:subject' => 'Konu',
	'Class:ActionEmail/Attribute:subject+' => 'E-posta konusu',
	'Class:ActionEmail/Attribute:body' => 'E-posta içeriği',
	'Class:ActionEmail/Attribute:body+' => 'E-posta içeriği',
	'Class:ActionEmail/Attribute:importance' => 'önem derecesi',
	'Class:ActionEmail/Attribute:importance+' => 'önem derecesi',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'düşük',
	'Class:ActionEmail/Attribute:importance/Value:low+' => 'düşük',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'normal',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => 'normal',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'yüksek',
	'Class:ActionEmail/Attribute:importance/Value:high+' => 'yüksek',
));

//
// Class: Trigger
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Trigger' => 'Tetikleyici',
	'Class:Trigger+' => 'Özel olay yürütücü',
	'Class:Trigger/Attribute:description' => 'Tanımlama',
	'Class:Trigger/Attribute:description+' => 'tek satır tanımlama',
	'Class:Trigger/Attribute:action_list' => 'Tetiklenen işlemler',
	'Class:Trigger/Attribute:action_list+' => 'Tetiklenen işlemler',
	'Class:Trigger/Attribute:finalclass' => 'Tip',
	'Class:Trigger/Attribute:finalclass+' => '',
	'Class:Trigger/Attribute:context' => 'Context~~',
	'Class:Trigger/Attribute:context+' => 'Context to allow the trigger to start~~',
));

//
// Class: TriggerOnObject
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:TriggerOnObject' => 'Tetiklenen (sınıf bağımlılığı)',
	'Class:TriggerOnObject+' => 'Verilen sınıflar üzerinde işlemleri gerçekleştir',
	'Class:TriggerOnObject/Attribute:target_class' => 'Hedef sınıf',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
	'Class:TriggerOnObject/Attribute:filter' => 'Filter~~',
	'Class:TriggerOnObject/Attribute:filter+' => '~~',
	'TriggerOnObject:WrongFilterQuery' => 'Wrong filter query: %1$s~~',
	'TriggerOnObject:WrongFilterClass' => 'The filter query must return objects of class \\"%1$s\\"~~',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:TriggerOnPortalUpdate' => 'Trigger (when updated from the portal)~~',
	'Class:TriggerOnPortalUpdate+' => 'Trigger on a end-user\'s update from the portal~~',
));

//
// Class: TriggerOnStateChange
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:TriggerOnStateChange' => 'Tetiklenen (durum değişikliğinde)',
	'Class:TriggerOnStateChange+' => 'Durum değişikliğinde tetiklenen işlemler',
	'Class:TriggerOnStateChange/Attribute:state' => 'Durum',
	'Class:TriggerOnStateChange/Attribute:state+' => '',
));

//
// Class: TriggerOnStateEnter
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:TriggerOnStateEnter' => 'Tetiklenen (duruma girişte)',
	'Class:TriggerOnStateEnter+' => 'Durum değişikliğinde tetiklenen işlemler (duruma giriş)',
));

//
// Class: TriggerOnStateLeave
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:TriggerOnStateLeave' => 'Tetiklenen (durum çıkışında)',
	'Class:TriggerOnStateLeave+' => 'Durum değişikliğinde tetiklenen işlemler (duruma çıkış)',
));

//
// Class: TriggerOnObjectCreate
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:TriggerOnObjectCreate' => 'Tetiklenen (nesne yaratımında)',
	'Class:TriggerOnObjectCreate+' => 'Verilen sınıf tipi nesne yaratımında tetiklenen işlemler',
));

//
// Class: TriggerOnObjectDelete
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:TriggerOnObjectDelete' => 'Trigger (on object deletion)~~',
	'Class:TriggerOnObjectDelete+' => 'Trigger on object deletion of [a child class of] the given class~~',
));

//
// Class: TriggerOnObjectUpdate
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:TriggerOnObjectUpdate' => 'Trigger (on object update)~~',
	'Class:TriggerOnObjectUpdate+' => 'Trigger on object update of [a child class of] the given class~~',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes' => 'Target fields~~',
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => '~~',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:TriggerOnThresholdReached' => 'Trigger (on threshold)~~',
	'Class:TriggerOnThresholdReached+' => 'Trigger on Stop-Watch threshold reached~~',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'Stop watch~~',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '~~',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Threshold~~',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '~~',
));

//
// Class: lnkTriggerAction
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:lnkTriggerAction' => 'İşlem/Tetikleme',
	'Class:lnkTriggerAction+' => 'Tetikleme ve işlem arasındaki ilişki',
	'Class:lnkTriggerAction/Attribute:action_id' => 'İşlem',
	'Class:lnkTriggerAction/Attribute:action_id+' => 'Yapılacak işlem',
	'Class:lnkTriggerAction/Attribute:action_name' => 'İşlem',
	'Class:lnkTriggerAction/Attribute:action_name+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_id' => 'Tetikleme',
	'Class:lnkTriggerAction/Attribute:trigger_id+' => '',
	'Class:lnkTriggerAction/Attribute:trigger_name' => 'Tetikleme',
	'Class:lnkTriggerAction/Attribute:trigger_name+' => '',
	'Class:lnkTriggerAction/Attribute:order' => 'Order',
	'Class:lnkTriggerAction/Attribute:order+' => 'İşlem uygulama sırası',
));

//
// Synchro Data Source
//
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:SynchroDataSource/Attribute:name' => 'Name~~',
	'Class:SynchroDataSource/Attribute:name+' => 'Name~~',
	'Class:SynchroDataSource/Attribute:description' => 'Description~~',
	'Class:SynchroDataSource/Attribute:status' => 'Status~~',
	'Class:SynchroDataSource/Attribute:scope_class' => 'Target class~~',
	'Class:SynchroDataSource/Attribute:user_id' => 'User~~',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Contact to notify~~',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Contact to notify in case of error~~',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Icon\'s hyperlink~~',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Hyperlink a (small) image representing the application with which iTop is synchronized~~',
	'Class:SynchroDataSource/Attribute:url_application' => 'Application\'s hyperlink~~',
	'Class:SynchroDataSource/Attribute:url_application+' => 'Hyperlink to the iTop object in the external application with which iTop is synchronized (if applicable). Possible placeholders: $this->attribute$ and $replica->primary_key$~~',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Reconciliation policy~~',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Full load interval~~',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'A complete reload of all data must occur at least as often as specified here~~',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Action on zero~~',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Action taken when the search returns no object~~',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Action on one~~',
	'Class:SynchroDataSource/Attribute:action_on_one+' => 'Action taken when the search returns exactly one object~~',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Action on many~~',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Action taken when the search returns more than one object~~',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'Users allowed~~',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Who is allowed to delete synchronized objects~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Nobody~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Administrators only~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'All allowed users~~',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Update rules~~',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Syntax: field_name:value; ...~~',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Retention Duration~~',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'How much time an obsolete object is kept before being deleted~~',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Data table~~',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'Name of the table to store the synchronization data. If left empty, a default name will be computed.~~',
	'SynchroDataSource:Description' => 'Description~~',
	'SynchroDataSource:Reconciliation' => 'Search &amp; reconciliation~~',
	'SynchroDataSource:Deletion' => 'Deletion rules~~',
	'SynchroDataSource:Status' => 'Status~~',
	'SynchroDataSource:Information' => 'Information~~',
	'SynchroDataSource:Definition' => 'Definition~~',
	'Core:SynchroAttributes' => 'Attributes~~',
	'Core:SynchroStatus' => 'Status~~',
	'Core:Synchro:ErrorsLabel' => 'Errors~~',
	'Core:Synchro:CreatedLabel' => 'Created~~',
	'Core:Synchro:ModifiedLabel' => 'Modified~~',
	'Core:Synchro:UnchangedLabel' => 'Unchanged~~',
	'Core:Synchro:ReconciledErrorsLabel' => 'Errors~~',
	'Core:Synchro:ReconciledLabel' => 'Reconciled~~',
	'Core:Synchro:ReconciledNewLabel' => 'Created~~',
	'Core:SynchroReconcile:Yes' => 'Yes~~',
	'Core:SynchroReconcile:No' => 'No~~',
	'Core:SynchroUpdate:Yes' => 'Yes~~',
	'Core:SynchroUpdate:No' => 'No~~',
	'Core:Synchro:LastestStatus' => 'Latest Status~~',
	'Core:Synchro:History' => 'Synchronization History~~',
	'Core:Synchro:NeverRun' => 'This synchro was never run. No log yet.~~',
	'Core:Synchro:SynchroEndedOn_Date' => 'The latest synchronization ended on %1$s.~~',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'The synchronization started on %1$s is still running...~~',
	'Menu:DataSources' => 'Synchronization Data Sources~~', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => 'All Synchronization Data Sources~~', // Duplicated into itop-welcome-itil (will be removed from here...)
	'Core:Synchro:label_repl_ignored' => 'Ignored (%1$s)~~',
	'Core:Synchro:label_repl_disappeared' => 'Disappeared (%1$s)~~',
	'Core:Synchro:label_repl_existing' => 'Existing (%1$s)~~',
	'Core:Synchro:label_repl_new' => 'New (%1$s)~~',
	'Core:Synchro:label_obj_deleted' => 'Deleted (%1$s)~~',
	'Core:Synchro:label_obj_obsoleted' => 'Obsoleted (%1$s)~~',
	'Core:Synchro:label_obj_disappeared_errors' => 'Errors (%1$s)~~',
	'Core:Synchro:label_obj_disappeared_no_action' => 'No Action (%1$s)~~',
	'Core:Synchro:label_obj_unchanged' => 'Unchanged (%1$s)~~',
	'Core:Synchro:label_obj_updated' => 'Updated (%1$s)~~',
	'Core:Synchro:label_obj_updated_errors' => 'Errors (%1$s)~~',
	'Core:Synchro:label_obj_new_unchanged' => 'Unchanged (%1$s)~~',
	'Core:Synchro:label_obj_new_updated' => 'Updated (%1$s)~~',
	'Core:Synchro:label_obj_created' => 'Created (%1$s)~~',
	'Core:Synchro:label_obj_new_errors' => 'Errors (%1$s)~~',
	'Core:SynchroLogTitle' => '%1$s - %2$s~~',
	'Core:Synchro:Nb_Replica' => 'Replica processed: %1$s~~',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s~~',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'At Least one reconciliation key must be specified, or the reconciliation policy must be to use the primary key.~~',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'A delete retention period must be specified, since objects are to be deleted after being marked as obsolete~~',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Obsolete objects are to be updated, but no update is specified.~~',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'The table %1$s already exists in the database. Please use another name for the synchro data table.~~',
	'Core:SynchroReplica:PublicData' => 'Public Data~~',
	'Core:SynchroReplica:PrivateDetails' => 'Private Details~~',
	'Core:SynchroReplica:BackToDataSource' => 'Go Back to the Synchro Data Source: %1$s~~',
	'Core:SynchroReplica:ListOfReplicas' => 'List of Replica~~',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id (Primary Key)~~',
	'Core:SynchroAtt:attcode' => 'Attribute~~',
	'Core:SynchroAtt:attcode+' => 'Field of the object~~',
	'Core:SynchroAtt:reconciliation' => 'Reconciliation ?~~',
	'Core:SynchroAtt:reconciliation+' => 'Used for searching~~',
	'Core:SynchroAtt:update' => 'Update ?~~',
	'Core:SynchroAtt:update+' => 'Used to update the object~~',
	'Core:SynchroAtt:update_policy' => 'Update Policy~~',
	'Core:SynchroAtt:update_policy+' => 'Behavior of the updated field~~',
	'Core:SynchroAtt:reconciliation_attcode' => 'Reconciliation Key~~',
	'Core:SynchroAtt:reconciliation_attcode+' => 'Attribute Code for the External Key Reconciliation~~',
	'Core:SyncDataExchangeComment' => '(Data Synchro)~~',
	'Core:Synchro:ListOfDataSources' => 'List of data sources:~~',
	'Core:Synchro:LastSynchro' => 'Last synchronization:~~',
	'Core:Synchro:ThisObjectIsSynchronized' => 'This object is synchronized with an external data source~~',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'The object was <b>created</b> by the external data source %1$s~~',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'The object <b>can be deleted</b> by the external data source %1$s~~',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => 'You <b>cannot delete the object</b> because it is owned by the external data source %1$s~~',
	'TitleSynchroExecution' => 'Execution of the synchronization~~',
	'Class:SynchroDataSource:DataTable' => 'Database table: %1$s~~',
	'Core:SyncDataSourceObsolete' => 'The data source is marked as obsolete. Operation cancelled.~~',
	'Core:SyncDataSourceAccessRestriction' => 'Only adminstrators or the user specified in the data source can execute this operation. Operation cancelled.~~',
	'Core:SyncTooManyMissingReplicas' => 'All records have been untouched for some time (all of the objects could be deleted). Please check that the process that writes into the synchronization table is still running. Operation cancelled.~~',
	'Core:SyncSplitModeCLIOnly' => 'The synchronization can be executed in chunks only if run in mode CLI~~',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s replicas, %2$s error(s), %3$s warning(s).~~',
	'Core:SynchroReplica:TargetObject' => 'Synchronized Object: %1$s~~',
	'Class:AsyncSendEmail' => 'Email (asynchronous)~~',
	'Class:AsyncSendEmail/Attribute:to' => 'To~~',
	'Class:AsyncSendEmail/Attribute:subject' => 'Subject~~',
	'Class:AsyncSendEmail/Attribute:body' => 'Body~~',
	'Class:AsyncSendEmail/Attribute:header' => 'Header~~',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Encrypted Password~~',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Previous Value~~',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Encrypted Field~~',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Previous Value~~',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Case Log~~',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Last Entry~~',
	'Class:SynchroDataSource' => 'Synchro Data Source~~',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Implementation~~',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Obsolete~~',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Production~~',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Scope restriction~~',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Use the attributes~~',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Use the primary_key field~~',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Create~~',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Error~~',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Error~~',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Update~~',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Create~~',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Error~~',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'Take the first one (random?)~~',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Delete Policy~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Delete~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Ignore~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Update~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Update then Delete~~',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Attributes List~~',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Administrators only~~',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Everybody allowed to delete such objects~~',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Nobody~~',
	'Class:SynchroAttribute' => 'Synchro Attribute~~',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Synchro Data Source~~',
	'Class:SynchroAttribute/Attribute:attcode' => 'Attribute Code~~',
	'Class:SynchroAttribute/Attribute:update' => 'Update~~',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Reconcile~~',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Update Policy~~',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Locked~~',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Unlocked~~',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Initialize if empty~~',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Class~~',
	'Class:SynchroAttExtKey' => 'Synchro Attribute (ExtKey)~~',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Reconciliation Attribute~~',
	'Class:SynchroAttLinkSet' => 'Synchro Attribute (Linkset)~~',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Rows separator~~',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Attributes separator~~',
	'Class:SynchroLog' => 'Synchr Log~~',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Synchro Data Source~~',
	'Class:SynchroLog/Attribute:start_date' => 'Start Date~~',
	'Class:SynchroLog/Attribute:end_date' => 'End Date~~',
	'Class:SynchroLog/Attribute:status' => 'Status~~',
	'Class:SynchroLog/Attribute:status/Value:completed' => 'Completed~~',
	'Class:SynchroLog/Attribute:status/Value:error' => 'Error~~',
	'Class:SynchroLog/Attribute:status/Value:running' => 'Still Running~~',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'Nb replica seen~~',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'Nb replica total~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'Nb objects deleted~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'Nb of errors while deleting~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'Nb objects obsoleted~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'Nb of errors while obsoleting~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'Nb objects created~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Nb or errors while creating~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Nb objects updated~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Nb errors while updating~~',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Nb of errors during reconciliation~~',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Nb replica disappeared~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Nb objects updated~~',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Nb objects unchanged~~',
	'Class:SynchroLog/Attribute:last_error' => 'Last error~~',
	'Class:SynchroLog/Attribute:traces' => 'Traces~~',
	'Class:SynchroReplica' => 'Synchro Replica~~',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Synchro Data Source~~',
	'Class:SynchroReplica/Attribute:dest_id' => 'Destination object (ID)~~',
	'Class:SynchroReplica/Attribute:dest_class' => 'Destination type~~',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Last seen~~',
	'Class:SynchroReplica/Attribute:status' => 'Status~~',
	'Class:SynchroReplica/Attribute:status/Value:modified' => 'Modified~~',
	'Class:SynchroReplica/Attribute:status/Value:new' => 'New~~',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Obsolete~~',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Orphan~~',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Synchronized~~',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Object Created ?~~',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Last Error~~',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Warnings~~',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Creation Date~~',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Last Modified Date~~',
	'Class:appUserPreferences' => 'User Preferences~~',
	'Class:appUserPreferences/Attribute:userid' => 'User~~',
	'Class:appUserPreferences/Attribute:preferences' => 'Prefs~~',
	'Core:ExecProcess:Code1' => 'Wrong command or command finished with errors (e.g. wrong script name)~~',
	'Core:ExecProcess:Code255' => 'PHP Error (parsing, or runtime)~~',

	// Attribute Duration
	'Core:Duration_Seconds' => '%1$ds~~',
	'Core:Duration_Minutes_Seconds' => '%1$dmin %2$ds~~',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds~~',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$dh %3$dmin %4$ds~~',

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
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
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
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
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
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
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
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
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
));
