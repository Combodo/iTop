<?php
// Copyright (C) 2010-2023 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
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
	'Core:DeletedObjectLabel' => '%1s (Silinmiş)',
	'Core:DeletedObjectTip' => 'Nesne%1$s (%2$s) \'de silinmiştir',
	'Core:UnknownObjectLabel' => 'Nesne bulunamadı (sınıf: %1$s, id: %2$d)',
	'Core:UnknownObjectTip' => 'Nesne bulunamadı.Nesne ve günlük kaydı bir süre önce silinmiş olabilir',
	'Core:UniquenessDefaultError' => 'Uniqueness rule \'%1$s\' in error~~',
	'Core:CheckConsistencyError' => 'Consistency rules not followed: %1$s~~',
	'Core:CheckValueError' => 'Unexpected value for attribute \'%1$s\' (%2$s) : %3$s~~',
	'Core:AttributeLinkedSet' => 'Nesnelerin dizisi',
	'Core:AttributeLinkedSet+' => 'Aynı sınıf veya alt sınıfın her türlü nesnesi',
	'Core:AttributeLinkedSetDuplicatesFound' => 'Duplicates in the \'%1$s\' field : %2$s~~',
	'Core:AttributeDashboard' => 'Dashboard~~',
	'Core:AttributeDashboard+' => '',
	'Core:AttributePhoneNumber' => 'Phone number~~',
	'Core:AttributePhoneNumber+' => '',
	'Core:AttributeObsolescenceDate' => 'Obsolescence date~~',
	'Core:AttributeObsolescenceDate+' => '',
	'Core:AttributeTagSet' => 'List of tags~~',
	'Core:AttributeTagSet+' => '',
	'Core:AttributeSet:placeholder' => 'click to add~~',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass' => '%1$s (%2$s)~~',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass' => '%1$s (%2$s from %3$s)~~',
	'Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses' => '%1$s (%2$s from child classes)~~',
	'Core:AttributeCaseLog' => 'Log~~',
	'Core:AttributeCaseLog+' => '',
	'Core:AttributeMetaEnum' => 'Computed enum~~',
	'Core:AttributeMetaEnum+' => '',
	'Core:AttributeLinkedSetIndirect' => 'Nesnelerin dizisi (n-n)',
	'Core:AttributeLinkedSetIndirect+' => 'Aynı sınıftan her türlü nesne [Altsınıf]',
	'Core:AttributeInteger' => 'Tamsayı',
	'Core:AttributeInteger+' => 'Sayısal değer (negatif olabilir)',
	'Core:AttributeDecimal' => 'Ondalık',
	'Core:AttributeDecimal+' => 'Ondalık değer (negatif olabilir)',
	'Core:AttributeBoolean' => 'Boolean',
	'Core:AttributeBoolean+' => '',
	'Core:AttributeBoolean/Value:null' => '',
	'Core:AttributeBoolean/Value:yes' => 'Evet',
	'Core:AttributeBoolean/Value:no' => 'Hayır',
	'Core:AttributeArchiveFlag' => 'Arşiv işareti',
	'Core:AttributeArchiveFlag/Value:yes' => 'Evet',
	'Core:AttributeArchiveFlag/Value:yes+' => 'Bu nesne yalnızca arşiv modunda görünürdür',
	'Core:AttributeArchiveFlag/Value:no' => 'Hayır',
	'Core:AttributeArchiveFlag/Label' => 'Arşivlendi',
	'Core:AttributeArchiveFlag/Label+' => '',
	'Core:AttributeArchiveDate/Label' => 'Arşiv Tarihi',
	'Core:AttributeArchiveDate/Label+' => '',
	'Core:AttributeObsolescenceFlag' => 'Kullanım dışı işareti',
	'Core:AttributeObsolescenceFlag/Value:yes' => 'Evet',
	'Core:AttributeObsolescenceFlag/Value:yes+' => 'Bu nesne, etki analizinden hariç tutulur ve arama sonuçlarından gizlenir',
	'Core:AttributeObsolescenceFlag/Value:no' => 'Hayır',
	'Core:AttributeObsolescenceFlag/Label' => 'Kullanım dışı',
	'Core:AttributeObsolescenceFlag/Label+' => 'Diğer özelliklerde dinamik olarak hesaplandı',
	'Core:AttributeObsolescenceDate/Label' => 'Kullanım dışı olma tarihi',
	'Core:AttributeObsolescenceDate/Label+' => 'Nesnenin eski olarak kabul edildiği yaklaşık tarih',
	'Core:AttributeString' => 'Dize',
	'Core:AttributeString+' => 'Alfanümerik dize',
	'Core:AttributeClass' => 'Sınıf',
	'Core:AttributeClass+' => '',
	'Core:AttributeApplicationLanguage' => 'Kullanıcı dili',
	'Core:AttributeApplicationLanguage+' => 'Dil ve Ülke (TR Türkiye)',
	'Core:AttributeFinalClass' => 'Sınıf (Otomatik)',
	'Core:AttributeFinalClass+' => 'Nesnenin gerçek sınıfı (çekirdek tarafından otomatik olarak oluşturulur)',
	'Core:AttributePassword' => 'Şifre',
	'Core:AttributePassword+' => 'Harici bir cihazın şifresi',
	'Core:AttributeEncryptedString' => 'Şifreli dize',
	'Core:AttributeEncryptedString+' => 'Dize yerel bir anahtarla şifrelenmiş',
	'Core:AttributeEncryptUnknownLibrary' => 'Encryption library specified (%1$s) unknown~~',
	'Core:AttributeEncryptFailedToDecrypt' => '** decryption error **~~',
	'Core:AttributeText' => 'Metin',
	'Core:AttributeText+' => 'Çok satırlı karakter dizesi',
	'Core:AttributeHTML' => 'HTML',
	'Core:AttributeHTML+' => 'HTML dizesi',
	'Core:AttributeEmailAddress' => 'E-posta Adresi',
	'Core:AttributeEmailAddress+' => '',
	'Core:AttributeIPAddress' => 'IP adresi',
	'Core:AttributeIPAddress+' => '',
	'Core:AttributeOQL' => 'OQL',
	'Core:AttributeOQL+' => 'Nesne sorgusu Dili ifadesi',
	'Core:AttributeEnum' => 'Enum',
	'Core:AttributeEnum+' => 'Önceden tanımlanmış alfasayısal dizelerin listesi',
	'Core:AttributeTemplateString' => 'Şablon dizesi',
	'Core:AttributeTemplateString+' => 'Yer sahipleri içeren dize',
	'Core:AttributeTemplateText' => 'Şablon metni',
	'Core:AttributeTemplateText+' => 'Yer sahipleri içeren metin',
	'Core:AttributeTemplateHTML' => 'Şablon HTML',
	'Core:AttributeTemplateHTML+' => 'Yer sahipleri içeren HTML',
	'Core:AttributeDateTime' => 'Tarih / Saat',
	'Core:AttributeDateTime+' => 'Tarih ve Saat (yıl-ay-gün hh:mm:ss)',
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
	'Core:AttributeDate' => 'Tarih',
	'Core:AttributeDate+' => 'Tarih (yıl-ay-gün)',
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
	'Core:AttributeDeadline' => 'Son tarih',
	'Core:AttributeDeadline+' => 'Geçerli saate göre görüntülenen tarih',
	'Core:AttributeExternalKey' => 'Harici anahtar',
	'Core:AttributeExternalKey+' => 'Harici (veya yabancı) anahtar',
	'Core:AttributeHierarchicalKey' => 'Hiyerarşik anahtar',
	'Core:AttributeHierarchicalKey+' => 'Ana kaynağın dış (veya yabancı) anahtarı',
	'Core:AttributeExternalField' => 'Harici alan',
	'Core:AttributeExternalField+' => 'Harici bir anahtarla eşlenen alan',
	'Core:AttributeURL' => 'URL',
	'Core:AttributeURL+' => 'Bir metin dizesi olarak mutlak veya göreceli URL',
	'Core:AttributeBlob' => 'Blob',
	'Core:AttributeBlob+' => 'Herhangi bir ikili içerik (belge)',
	'Core:AttributeOneWayPassword' => 'Tek yönlü şifre',
	'Core:AttributeOneWayPassword+' => 'Tek Yönlü Şifrelenmiş (Hashed) Şifre',
	'Core:AttributeTable' => 'Tablo',
	'Core:AttributeTable+' => 'İki boyuta sahip dizine eklenmiş dizi',
	'Core:AttributePropertySet' => 'Özellikler',
	'Core:AttributePropertySet+' => 'Kaynaklı özelliklerin listesi (isim ve değer)',
	'Core:AttributeFriendlyName' => 'Yaygın Adı',
	'Core:AttributeFriendlyName+' => 'Otomatik olarak oluşturulan nitelik; Yaygın Ad, birkaç öznitelikten sonra hesaplanır',
	'Core:FriendlyName-Label' => 'Yaygın Adı',
	'Core:FriendlyName-Description' => 'Yaygın Adı',
	'Core:AttributeTag' => 'Tags~~',
	'Core:AttributeTag+' => '',
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

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:CMDBChangeOp' => 'Değişiklik işlemi',
	'Class:CMDBChangeOp+' => '',
	'Class:CMDBChangeOp/Attribute:change' => 'değişiklik',
	'Class:CMDBChangeOp/Attribute:change+' => '',
	'Class:CMDBChangeOp/Attribute:date' => 'tarih',
	'Class:CMDBChangeOp/Attribute:date+' => 'değişikliğin yapıldığı zaman',
	'Class:CMDBChangeOp/Attribute:userinfo' => 'kullanıcı',
	'Class:CMDBChangeOp/Attribute:userinfo+' => 'değişikliğ yapan',
	'Class:CMDBChangeOp/Attribute:objclass' => 'nesne sınıfı',
	'Class:CMDBChangeOp/Attribute:objclass+' => '',
	'Class:CMDBChangeOp/Attribute:objkey' => 'nesne no',
	'Class:CMDBChangeOp/Attribute:objkey+' => '',
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
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:oldvalue+' => '',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue' => 'Yeni değer',
	'Class:CMDBChangeOpSetAttributeScalar/Attribute:newvalue+' => '',
));
// Used by CMDBChangeOp... & derived classes
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Change:ObjectCreated' => 'Nesne yaratıldı',
	'Change:ObjectDeleted' => 'Nesne silindi',
	'Change:ObjectModified' => 'Nesne değiştirildi',
	'Change:TwoAttributesChanged' => 'Edited %1$s and %2$s~~',
	'Change:ThreeAttributesChanged' => 'Edited %1$s, %2$s and 1 other~~',
	'Change:FourOrMoreAttributesChanged' => 'Edited %1$s, %2$s and %3$s others~~',
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s\'nin değeri %2$s olarak atandı (önceki değer: %3$s)',
	'Change:AttName_SetTo' => '%1$s\'nin değeri %2$s olarak atandı',
	'Change:Text_AppendedTo_AttName' => '%2$s\'ye %1$s eklendi',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$s nin değeri deiştirildi, önceki değer: %2$s',
	'Change:AttName_Changed' => '%1$s değiştirildi',
	'Change:AttName_EntryAdded' => '%1$s değiştirilmiş, yeni giriş eklendi.',
	'Change:State_Changed_NewValue_OldValue' => 'Changed from %2$s to %1$s~~',
	'Change:LinkSet:Added' => '%1$s \'eklendi',
	'Change:LinkSet:Removed' => 'Kaldırıldı %1$s',
	'Change:LinkSet:Modified' => 'Değiştirilmiş %1$s',
));

//
// Class: CMDBChangeOpSetAttributeBlob
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:CMDBChangeOpSetAttributeBlob' => 'tarih değişimi',
	'Class:CMDBChangeOpSetAttributeBlob+' => 'tarih değişim izleme',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata' => 'Önceki veri',
	'Class:CMDBChangeOpSetAttributeBlob/Attribute:prevdata+' => '',
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
	'Class:EventNotificationEmail/Attribute:to+' => '',
	'Class:EventNotificationEmail/Attribute:cc' => 'Kopya',
	'Class:EventNotificationEmail/Attribute:cc+' => '',
	'Class:EventNotificationEmail/Attribute:bcc' => 'Gizli Kopya',
	'Class:EventNotificationEmail/Attribute:bcc+' => '',
	'Class:EventNotificationEmail/Attribute:from' => 'Kimden',
	'Class:EventNotificationEmail/Attribute:from+' => 'Mesajı gönderen',
	'Class:EventNotificationEmail/Attribute:subject' => 'Konu',
	'Class:EventNotificationEmail/Attribute:subject+' => '',
	'Class:EventNotificationEmail/Attribute:body' => 'Mesaj',
	'Class:EventNotificationEmail/Attribute:body+' => '',
	'Class:EventNotificationEmail/Attribute:attachments' => 'Eklentiler',
	'Class:EventNotificationEmail/Attribute:attachments+' => '',
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
	'Class:EventIssue/Attribute:callstack+' => '',
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
	'Class:EventRestService' => 'REST/JSON çağrısı',
	'Class:EventRestService+' => 'REST/JSON izleme hizmet çağrısı',
	'Class:EventRestService/Attribute:operation' => 'Operasyon',
	'Class:EventRestService/Attribute:operation+' => 'Argüman \'operasyon\'',
	'Class:EventRestService/Attribute:version' => 'Sürüm',
	'Class:EventRestService/Attribute:version+' => 'Argüman \'versiyon\'',
	'Class:EventRestService/Attribute:json_input' => 'Girdi',
	'Class:EventRestService/Attribute:json_input+' => 'Argüman \'json_data\'',
	'Class:EventRestService/Attribute:code' => 'Kod',
	'Class:EventRestService/Attribute:code+' => 'Sonuç Kodu',
	'Class:EventRestService/Attribute:json_output' => 'Yanıt',
	'Class:EventRestService/Attribute:json_output+' => 'HTTP Yanıt (JSON)',
	'Class:EventRestService/Attribute:provider' => 'Sağlayıcı',
	'Class:EventRestService/Attribute:provider+' => 'PHP Sınıfı Beklenen Operasyonun Uygulanması',
));

//
// Class: EventLoginUsage
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:EventLoginUsage' => 'Giriş Kullanımı',
	'Class:EventLoginUsage+' => 'Uygulamaya bağlantı',
	'Class:EventLoginUsage/Attribute:user_id' => 'Giriş',
	'Class:EventLoginUsage/Attribute:user_id+' => '',
	'Class:EventLoginUsage/Attribute:contact_name' => 'Kullanıcı adı',
	'Class:EventLoginUsage/Attribute:contact_name+' => '',
	'Class:EventLoginUsage/Attribute:contact_email' => 'Kullanıcı e-postası',
	'Class:EventLoginUsage/Attribute:contact_email+' => 'Kullanıcının e-posta adresi',
));

//
// Class: Action
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Action' => 'Özel işlem',
	'Class:Action+' => 'Kullanıcının tanımladığı işlemler',
	'Class:Action/ComplementaryName' => '%1$s: %2$s~~',
	'Class:Action/Attribute:name' => 'Adı',
	'Class:Action/Attribute:name+' => '',
	'Class:Action/Attribute:description' => 'Tanımlama',
	'Class:Action/Attribute:description+' => '',
	'Class:Action/Attribute:status' => 'Durum',
	'Class:Action/Attribute:status+' => 'Kullanımda mı?',
	'Class:Action/Attribute:status/Value:test' => 'Test aşamasında',
	'Class:Action/Attribute:status/Value:test+' => '',
	'Class:Action/Attribute:status/Value:enabled' => 'Kullanımda',
	'Class:Action/Attribute:status/Value:enabled+' => '',
	'Class:Action/Attribute:status/Value:disabled' => 'Etkin değil',
	'Class:Action/Attribute:status/Value:disabled+' => '',
	'Class:Action/Attribute:trigger_list' => 'İlgili tetikleyiciler',
	'Class:Action/Attribute:trigger_list+' => 'İşleme bağlı tetikleyici',
	'Class:Action/Attribute:finalclass' => 'Tip',
	'Class:Action/Attribute:finalclass+' => '',
	'Action:WarningNoTriggerLinked' => 'Warning, no trigger is linked to the action. It will not be active until it has at least 1.~~',
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
	'Class:ActionEmail/Attribute:status+' => 'This status drives who will be notified: just the Test recipient, all (To, cc and Bcc) or no-one~~',
	'Class:ActionEmail/Attribute:status/Value:test+' => 'Only the Test recipient is notified~~',
	'Class:ActionEmail/Attribute:status/Value:enabled+' => 'All To, Cc and Bcc emails are notified~~',
	'Class:ActionEmail/Attribute:status/Value:disabled+' => 'The email notification will not be sent~~',
	'Class:ActionEmail/Attribute:test_recipient' => 'Test alıcısı',
	'Class:ActionEmail/Attribute:test_recipient+' => 'Durumu "Test" olması durumundaki alıcı',
	'Class:ActionEmail/Attribute:from' => 'Kimden~~',
	'Class:ActionEmail/Attribute:from+' => 'e-posta başlığında gönderilecek~~',
	'Class:ActionEmail/Attribute:from_label' => 'From (label)~~',
	'Class:ActionEmail/Attribute:from_label+' => 'Sender display name will be sent into the email header~~',
	'Class:ActionEmail/Attribute:reply_to' => 'Yanıtla~~',
	'Class:ActionEmail/Attribute:reply_to+' => 'e-posta başlığında gönderilecek~~',
	'Class:ActionEmail/Attribute:reply_to_label' => 'Reply to (label)~~',
	'Class:ActionEmail/Attribute:reply_to_label+' => 'Reply to display name will be sent into the email header~~',
	'Class:ActionEmail/Attribute:to' => 'Kime',
	'Class:ActionEmail/Attribute:to+' => 'E-posta alıcısı',
	'Class:ActionEmail/Attribute:cc' => 'Kopya',
	'Class:ActionEmail/Attribute:cc+' => '',
	'Class:ActionEmail/Attribute:bcc' => 'Gizli kopya',
	'Class:ActionEmail/Attribute:bcc+' => 'Gizli alıcı',
	'Class:ActionEmail/Attribute:subject' => 'Konu',
	'Class:ActionEmail/Attribute:subject+' => 'E-posta konusu',
	'Class:ActionEmail/Attribute:body' => 'E-posta içeriği',
	'Class:ActionEmail/Attribute:body+' => '',
	'Class:ActionEmail/Attribute:importance' => 'önem derecesi',
	'Class:ActionEmail/Attribute:importance+' => '',
	'Class:ActionEmail/Attribute:importance/Value:low' => 'düşük',
	'Class:ActionEmail/Attribute:importance/Value:low+' => '',
	'Class:ActionEmail/Attribute:importance/Value:normal' => 'normal',
	'Class:ActionEmail/Attribute:importance/Value:normal+' => '',
	'Class:ActionEmail/Attribute:importance/Value:high' => 'yüksek',
	'Class:ActionEmail/Attribute:importance/Value:high+' => '',
	'Class:ActionEmail/Attribute:language' => 'Language~~',
	'Class:ActionEmail/Attribute:language+' => 'Language to use for placeholders ($xxx$) inside the message (state, importance, priority, etc)~~',
	'Class:ActionEmail/Attribute:html_template' => 'HTML template~~',
	'Class:ActionEmail/Attribute:html_template+' => 'Optional HTML template wrapping around the content of the \'Body\' attribute below, useful for tailored email layouts (in the template, content of the \'Body\' attribute will replace the $content$ placeholder)~~',
	'Class:ActionEmail/Attribute:ignore_notify' => 'Ignore the Notify flag~~',
	'Class:ActionEmail/Attribute:ignore_notify+' => 'If set to \'Yes\' the \'Notify\' flag on Contacts has no effect.~~',
	'Class:ActionEmail/Attribute:ignore_notify/Value:no' => 'No~~',
	'Class:ActionEmail/Attribute:ignore_notify/Value:yes' => 'Yes~~',
	'ActionEmail:main' => 'Message~~',
	'ActionEmail:trigger' => 'Triggers~~',
	'ActionEmail:recipients' => 'Contacts~~',
	'ActionEmail:preview_tab' => 'Preview~~',
	'ActionEmail:preview_tab+' => 'Preview of the eMail template~~',
	'ActionEmail:preview_warning' => 'The actual eMail may look different in the eMail client than this preview in your browser.~~',
	'ActionEmail:preview_more_info' => 'For more information about the CSS features supported by the different eMail clients, refer to %1$s~~',
	'ActionEmail:content_placeholder_missing' => 'The placeholder "%1$s" was not found in the HTML template. The content of the field "%2$s" will not be included in the generated emails.~~',
));

//
// Class: Trigger
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:Trigger' => 'Tetikleyici',
	'Class:Trigger+' => 'Özel olay yürütücü',
	'Class:Trigger/ComplementaryName' => '%1$s, %2$s~~',
	'Class:Trigger/Attribute:description' => 'Tanımlama',
	'Class:Trigger/Attribute:description+' => 'tek satır tanımlama',
	'Class:Trigger/Attribute:action_list' => 'Tetiklenen işlemler',
	'Class:Trigger/Attribute:action_list+' => 'Actions performed when the trigger is activated~~',
	'Class:Trigger/Attribute:finalclass' => 'Tip',
	'Class:Trigger/Attribute:finalclass+' => '',
	'Class:Trigger/Attribute:context' => 'Context~~',
	'Class:Trigger/Attribute:context+' => 'Context to allow the trigger to start~~',
	'Class:Trigger/Attribute:complement' => 'Additional information~~',
	'Class:Trigger/Attribute:complement+' => 'Further information as provided in english, by this trigger~~',
));

//
// Class: TriggerOnObject
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:TriggerOnObject' => 'Tetiklenen (sınıf bağımlılığı)',
	'Class:TriggerOnObject+' => 'Verilen sınıflar üzerinde işlemleri gerçekleştir',
	'Class:TriggerOnObject/Attribute:target_class' => 'Hedef sınıf',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
	'Class:TriggerOnObject/Attribute:filter' => 'Filtre',
	'Class:TriggerOnObject/Attribute:filter+' => '',
	'TriggerOnObject:WrongFilterQuery' => 'Yanlış filtre sorgusu: %1$s',
	'TriggerOnObject:WrongFilterClass' => 'Filtre sorgusu, \\"%1$s\\"  \'sınıfının nesnelerini dönmelidir.',
));

//
// Class: TriggerOnPortalUpdate
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:TriggerOnPortalUpdate' => 'Tetikle (portaldan güncellendiğinde)',
	'Class:TriggerOnPortalUpdate+' => 'Son kullanıcının portalından gelen güncellemelerinde tetikle',
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
	'Class:TriggerOnObjectUpdate/Attribute:target_attcodes+' => '',
));

//
// Class: TriggerOnObjectMention
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:TriggerOnObjectMention' => 'Trigger (on object mention)~~',
	'Class:TriggerOnObjectMention+' => 'Trigger on mention (@xxx) of an object of [a child class of] the given class in a log attribute~~',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter' => 'Mentioned filter~~',
	'Class:TriggerOnObjectMention/Attribute:mentioned_filter+' => 'Limit the list of mentioned objects which will activate the trigger. If empty, any mentioned object (of any class) will activate it.~~',
));

//
// Class: TriggerOnAttributeBlobDownload
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:TriggerOnAttributeBlobDownload' => 'Trigger (on object\'s document download)~~',
	'Class:TriggerOnAttributeBlobDownload+' => 'Trigger on object\'s document field download of [a child class of] the given class~~',
	'Class:TriggerOnAttributeBlobDownload/Attribute:target_attcodes' => 'Target fields~~',
	'Class:TriggerOnAttributeBlobDownload/Attribute:target_attcodes+' => '~~',
));

//
// Class: TriggerOnThresholdReached
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:TriggerOnThresholdReached' => 'Tetikle (eşik üzerinde)',
	'Class:TriggerOnThresholdReached+' => 'Dur-izle eşiğinde tetikle',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code' => 'İzlemeyi bırak',
	'Class:TriggerOnThresholdReached/Attribute:stop_watch_code+' => '',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index' => 'Eşik',
	'Class:TriggerOnThresholdReached/Attribute:threshold_index+' => '',
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
	'Class:SynchroDataSource' => 'Synchro Veri Kaynağı',
	'Class:SynchroDataSource/Attribute:name' => 'İsim',
	'Class:SynchroDataSource/Attribute:name+' => '',
	'Class:SynchroDataSource/Attribute:description' => 'Açıklama',
	'Class:SynchroDataSource/Attribute:status' => 'Durum',
	'Class:SynchroDataSource/Attribute:scope_class' => 'Hedef sınıf',
	'Class:SynchroDataSource/Attribute:scope_class+' => 'A Synchro Data Source can only populate a single '.ITOP_APPLICATION_SHORT.' class~~',
	'Class:SynchroDataSource/Attribute:user_id' => 'Kullanıcı',
	'Class:SynchroDataSource/Attribute:notify_contact_id' => 'Bildirim iletilecek kişi',
	'Class:SynchroDataSource/Attribute:notify_contact_id+' => 'Hata durumunda bildirmek yapılacak kişi',
	'Class:SynchroDataSource/Attribute:url_icon' => 'Simge\'nin köprüsü',
	'Class:SynchroDataSource/Attribute:url_icon+' => 'Hyprinlink, '.ITOP_APPLICATION_SHORT.'\'un senkronize edildiği uygulamayı temsil eden (küçük) bir görüntü',
	'Class:SynchroDataSource/Attribute:url_application' => 'Uygulama\'nın köprüsü',
	'Class:SynchroDataSource/Attribute:url_application+' => ITOP_APPLICATION_SHORT.'\'un senkronize edildiği harici uygulamadaki '.ITOP_APPLICATION_SHORT.' nesnesine köprü (varsa). Muhtemel yer tutucular: $this->attribute$ ve $replica->primary_key$',
	'Class:SynchroDataSource/Attribute:reconciliation_policy' => 'Uzlaşma Politikası',
	'Class:SynchroDataSource/Attribute:reconciliation_policy+' => '"Use the attributes": '.ITOP_APPLICATION_SHORT.' object matches replica values for each Synchro attributes flagged for Reconciliation.
"Use primary_key": the column primary_key of the replica is expected to contain the identifier of the '.ITOP_APPLICATION_SHORT.' object~~',
	'Class:SynchroDataSource/Attribute:full_load_periodicity' => 'Tam Yük Aralığı',
	'Class:SynchroDataSource/Attribute:full_load_periodicity+' => 'Tüm verilerin  yeniden yüklenmesi, en azından burada belirtilen sıklıkta olmalıdır',
	'Class:SynchroDataSource/Attribute:action_on_zero' => 'Sıfırda eylem',
	'Class:SynchroDataSource/Attribute:action_on_zero+' => 'Arama nesne dönmediğinde yapılan aksiyon',
	'Class:SynchroDataSource/Attribute:action_on_one' => 'Birde eylem',
	'Class:SynchroDataSource/Attribute:action_on_one+' => 'Arama tam olarak bir nesneyi döndürdüğünde gerçekleştirilen eylem',
	'Class:SynchroDataSource/Attribute:action_on_multiple' => 'Birçok Eylem',
	'Class:SynchroDataSource/Attribute:action_on_multiple+' => 'Arama birden fazla nesne geri döndüğünde gerçekleştirilen eylem',
	'Class:SynchroDataSource/Attribute:user_delete_policy' => 'İzin verilen kullanıcılar',
	'Class:SynchroDataSource/Attribute:user_delete_policy+' => 'Kim senkronize nesneleri silmek için izinli',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:never' => 'Kimse',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:depends' => 'Sadece yöneticiler',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:always' => 'Tüm izin verilen kullanıcılar',
	'Class:SynchroDataSource/Attribute:delete_policy_update' => 'Güncelleme kuralları',
	'Class:SynchroDataSource/Attribute:delete_policy_update+' => 'Sözdizimi: field_name: değer; ...',
	'Class:SynchroDataSource/Attribute:delete_policy_retention' => 'Tutma süresi',
	'Class:SynchroDataSource/Attribute:delete_policy_retention+' => 'Silinmeden önce kullanım dışı bir nesne ne kadar tutulacak',
	'Class:SynchroDataSource/Attribute:database_table_name' => 'Veri tablosu',
	'Class:SynchroDataSource/Attribute:database_table_name+' => 'Senkronizasyon verilerini saklamak için tablonun adı. Boş bırakılırsa, varsayılan bir isim hesaplanacaktır.',
	'Class:SynchroDataSource/Attribute:status/Value:implementation' => 'Uygulama',
	'Class:SynchroDataSource/Attribute:status/Value:obsolete' => 'Kullanım dışı',
	'Class:SynchroDataSource/Attribute:status/Value:production' => 'Kullanımda',
	'Class:SynchroDataSource/Attribute:scope_restriction' => 'Kapsam Kısıtlaması',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_attributes' => 'Öznitelikleri kullanın',
	'Class:SynchroDataSource/Attribute:reconciliation_policy/Value:use_primary_key' => 'Birincil_anahtar alanını kullanın',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:create' => 'Oluşturun',
	'Class:SynchroDataSource/Attribute:action_on_zero/Value:error' => 'Hata',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:error' => 'Hata',
	'Class:SynchroDataSource/Attribute:action_on_one/Value:update' => 'Güncelleme',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:create' => 'Oluşturun',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:error' => 'Hata',
	'Class:SynchroDataSource/Attribute:action_on_multiple/Value:take_first' => 'İlkini al (rastgele?)',
	'Class:SynchroDataSource/Attribute:delete_policy' => 'Politikayı Sil',
	'Class:SynchroDataSource/Attribute:delete_policy+' => 'What to do when a replica becomes obsolete:
"Ignore": do nothing, the associated object remains as is in iTop.
"Delete": Delete the associated object in iTop (and the replica in the data table).
"Update": Update the associated object as specified by the Update rules (see below).
"Update then Delete": apply the "Update rules". When Retention Duration expires, execute a "Delete" ~~',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:delete' => 'Sil',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:ignore' => 'Yoksay',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update' => 'Güncelle',
	'Class:SynchroDataSource/Attribute:delete_policy/Value:update_then_delete' => 'Güncelle ve sil',
	'Class:SynchroDataSource/Attribute:attribute_list' => 'Nitelikler listesi',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:administrators' => 'Sadece yöneticiler',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:everybody' => 'Herkes bu tür nesneleri silmek için izinlidir',
	'Class:SynchroDataSource/Attribute:user_delete_policy/Value:nobody' => 'Kimse',
	'SynchroDataSource:Description' => 'Açıklama',
	'SynchroDataSource:Reconciliation' => 'Arama ve amp; Uzlaşma',
	'SynchroDataSource:Deletion' => 'Silme kuralları',
	'SynchroDataSource:Status' => 'Durum',
	'SynchroDataSource:Information' => 'Bilgi',
	'SynchroDataSource:Definition' => 'Tanım',
	'Core:SynchroAttributes' => 'Nitelikler',
	'Core:SynchroStatus' => 'Durum',
	'Core:Synchro:ErrorsLabel' => 'Hatalar',
	'Core:Synchro:CreatedLabel' => 'Yaratıldı',
	'Core:Synchro:ModifiedLabel' => 'Değiştirildi',
	'Core:Synchro:UnchangedLabel' => 'Değiştirilmedi',
	'Core:Synchro:ReconciledErrorsLabel' => 'Hatalar',
	'Core:Synchro:ReconciledLabel' => 'Uzlaştırıldı',
	'Core:Synchro:ReconciledNewLabel' => 'Yaratıldı',
	'Core:SynchroReconcile:Yes' => 'Evet',
	'Core:SynchroReconcile:No' => 'Hayır',
	'Core:SynchroUpdate:Yes' => 'Evet',
	'Core:SynchroUpdate:No' => 'Hayır',
	'Core:Synchro:LastestStatus' => 'Son Durum',
	'Core:Synchro:History' => 'Senkronizasyon Tarihi',
	'Core:Synchro:NeverRun' => 'Bu senkronizasyon hiç çalışmadı. Henüz günlüğü yok.',
	'Core:Synchro:SynchroEndedOn_Date' => 'En son senkronizasyon %1$s.',
	'Core:Synchro:SynchroRunningStartedOn_Date' => 'Senkronizasyon %1$s\'de başladı hala çalışıyor...',
	'Menu:DataSources' => 'Senkronizasyon Veri Kaynakları',
    // Duplicated into itop-welcome-itil (will be removed from here...)
	'Menu:DataSources+' => 'Tüm Senkronizasyon Veri Kaynakları',
    // Duplicated into itop-welcome-itil (will be removed from here...)
	'Core:Synchro:label_repl_ignored' => 'Yoksayıldı (%1$s)',
	'Core:Synchro:label_repl_disappeared' => 'Kayboldu (%1$s)',
	'Core:Synchro:label_repl_existing' => 'Mevcut (%1$s)',
	'Core:Synchro:label_repl_new' => 'Yeni (%1$s)',
	'Core:Synchro:label_obj_deleted' => 'Silindi (%1$s)',
	'Core:Synchro:label_obj_obsoleted' => 'Kullanım dışı (%1$s)',
	'Core:Synchro:label_obj_disappeared_errors' => 'Hatalar (%1$s)',
	'Core:Synchro:label_obj_disappeared_no_action' => 'Hiçbir işlem (%1$s)',
	'Core:Synchro:label_obj_unchanged' => 'Değiştirildi (%1$s)',
	'Core:Synchro:label_obj_updated' => 'Güncellendi (%1$s)',
	'Core:Synchro:label_obj_updated_errors' => 'Hatalar (%1$s)',
	'Core:Synchro:label_obj_new_unchanged' => 'Değiştirilmedi (%1$s)',
	'Core:Synchro:label_obj_new_updated' => 'Güncellendi (%1$s)',
	'Core:Synchro:label_obj_created' => 'Oluşturuldu (%1$s)',
	'Core:Synchro:label_obj_new_errors' => 'Hatalar (%1$s)',
	'Core:SynchroLogTitle' => '%1$s - %2$s',
	'Core:Synchro:Nb_Replica' => 'Çoğaltma İşlendi: %1$s',
	'Core:Synchro:Nb_Class:Objects' => '%1$s: %2$s',
	'Class:SynchroDataSource/Error:AtLeastOneReconciliationKeyMustBeSpecified' => 'En az bir uzlaşma anahtarı belirtilmeli veya uzlaşma politikası birincil anahtarı kullanmak için olmalıdır.',
	'Class:SynchroDataSource/Error:DeleteRetentionDurationMustBeSpecified' => 'Bir silme tutma süresi belirtilmelidir, çünkü nesneler eski olarak işaretlendikten sonra silinir.',
	'Class:SynchroDataSource/Error:DeletePolicyUpdateMustBeSpecified' => 'Eski nesneler güncellenecek, ancak güncelleme belirtilmemektedir.',
	'Class:SynchroDataSource/Error:DataTableAlreadyExists' => 'Tablo %1$s zaten veritabanında var. Lütfen senkronizasyon veri tablosu için başka bir isim kullanın.',
	'Core:SynchroReplica:PublicData' => 'Genel Veriler',
	'Core:SynchroReplica:PrivateDetails' => 'Özel detaylar',
	'Core:SynchroReplica:BackToDataSource' => 'Synchro veri kaynağına geri dön: %1$s',
	'Core:SynchroReplica:ListOfReplicas' => 'Replika listesi',
	'Core:SynchroAttExtKey:ReconciliationById' => 'id (birincil anahtar)~~',
	'Core:SynchroAtt:attcode' => 'Öznitelik',
	'Core:SynchroAtt:attcode+' => 'Nesnenin alanı',
	'Core:SynchroAtt:reconciliation' => 'Uzlaşma ?',
	'Core:SynchroAtt:reconciliation+' => 'Arama için kullanılır',
	'Core:SynchroAtt:update' => 'Güncelleme ?',
	'Core:SynchroAtt:update+' => 'Nesneyi güncellemek için kullanılır',
	'Core:SynchroAtt:update_policy' => 'Güncelleme politikası',
	'Core:SynchroAtt:update_policy+' => 'Güncellenen alanın davranışı',
	'Core:SynchroAtt:reconciliation_attcode' => 'Uzlaşma Anahtarı',
	'Core:SynchroAtt:reconciliation_attcode+' => 'Harici anahtar mutabakatı için öznitelik kodu',
	'Core:SyncDataExchangeComment' => '(Veri Synchro)',
	'Core:Synchro:ListOfDataSources' => 'Veri kaynakları listesi:',
	'Core:Synchro:LastSynchro' => 'Son senkronizasyon:',
	'Core:Synchro:ThisObjectIsSynchronized' => 'Bu nesne harici bir veri kaynağı ile senkronize edilir',
	'Core:Synchro:TheObjectWasCreatedBy_Source' => 'Nesne %1$s dış kaynağı tarafından <b>oluşturuldu</b>',
	'Core:Synchro:TheObjectCanBeDeletedBy_Source' => 'Nesne %1$s dış kaynağı tarafından <b>silindi</b>',
	'Core:Synchro:TheObjectCannotBeDeletedByUser_Source' => '<b>Bu nesneyi silemezsin</b> çünkü %1$s dış kaynağı tarafından sahiplenilmiş',
	'TitleSynchroExecution' => 'Senkronizasyonun yürütülmesi',
	'Class:SynchroDataSource:DataTable' => 'Veritabanı Tablosu: %1$s',
	'Core:SyncDataSourceObsolete' => 'Veri kaynağı eski olarak işaretlenmiştir. İşlem iptal edildi',
	'Core:SyncDataSourceAccessRestriction' => 'Yalnızca Yönetici veya veri kaynağında belirtilen kullanıcı bu işlemi yürütebilir. İşlem iptal edildi.',
	'Core:SyncTooManyMissingReplicas' => 'Tüm kayıtlar bir süredir dokunulmamıştır (tüm nesneler silinebilir). Lütfen senkronizasyon tablosuna yazan işlemin hala çalıştığını kontrol edin. İşlem iptal edildi.',
	'Core:SyncSplitModeCLIOnly' => 'Senkronizasyon parçalı olarak, yalnızca Mode CLI \'de çalıştırıldığında yapılabilir',
	'Core:Synchro:ListReplicas_AllReplicas_Errors_Warnings' => '%1$s Replika,%2$s Hata (lar),%3$s Uyarı (lar).',
	'Core:SynchroReplica:TargetObject' => 'Senkronize Nesne: %1$s~~',
	'Class:AsyncSendEmail' => 'E-posta (Asenkron)',
	'Class:AsyncSendEmail/Attribute:to' => 'Kime',
	'Class:AsyncSendEmail/Attribute:subject' => 'Konu',
	'Class:AsyncSendEmail/Attribute:body' => 'İçerik',
	'Class:AsyncSendEmail/Attribute:header' => 'Başlık',
	'Class:CMDBChangeOpSetAttributeOneWayPassword' => 'Şifrelenmiş şifre',
	'Class:CMDBChangeOpSetAttributeOneWayPassword/Attribute:prev_pwd' => 'Önceki değer',
	'Class:CMDBChangeOpSetAttributeEncrypted' => 'Şifreli alan',
	'Class:CMDBChangeOpSetAttributeEncrypted/Attribute:prevstring' => 'Önceki değer',
	'Class:CMDBChangeOpSetAttributeCaseLog' => 'Dosya kaydı',
	'Class:CMDBChangeOpSetAttributeCaseLog/Attribute:lastentry' => 'Son giriş',
	'Class:SynchroAttribute' => 'Synchro niteliği',
	'Class:SynchroAttribute/Attribute:sync_source_id' => 'Synchro Veri Kaynağı',
	'Class:SynchroAttribute/Attribute:attcode' => 'Öznitelik kodu',
	'Class:SynchroAttribute/Attribute:update' => 'Güncelle',
	'Class:SynchroAttribute/Attribute:reconcile' => 'Uzlaştır',
	'Class:SynchroAttribute/Attribute:update_policy' => 'Güncelleme politikası',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_locked' => 'Kilitli',
	'Class:SynchroAttribute/Attribute:update_policy/Value:master_unlocked' => 'Kilitsiz',
	'Class:SynchroAttribute/Attribute:update_policy/Value:write_if_empty' => 'Boş ise başlat',
	'Class:SynchroAttribute/Attribute:finalclass' => 'Sınıf',
	'Class:SynchroAttExtKey' => 'Synchro Özniteliği (ExtKey)',
	'Class:SynchroAttExtKey/Attribute:reconciliation_attcode' => 'Uzlaşma özniteliği',
	'Class:SynchroAttLinkSet' => 'Synchro niteliği (LinkSet)',
	'Class:SynchroAttLinkSet/Attribute:row_separator' => 'Satır Ayırıcı',
	'Class:SynchroAttLinkSet/Attribute:attribute_separator' => 'Nitelik Ayırıcı',
	'Class:SynchroLog' => 'Synchr log',
	'Class:SynchroLog/Attribute:sync_source_id' => 'Synchro Veri Kaynağı',
	'Class:SynchroLog/Attribute:start_date' => 'Başlangıç tarihi',
	'Class:SynchroLog/Attribute:end_date' => 'Bitiş Tarihi',
	'Class:SynchroLog/Attribute:status' => 'Durum',
	'Class:SynchroLog/Attribute:status/Value:completed' => 'Tamamlandı',
	'Class:SynchroLog/Attribute:status/Value:error' => 'Hata',
	'Class:SynchroLog/Attribute:status/Value:running' => 'Hala çalışıyor',
	'Class:SynchroLog/Attribute:stats_nb_replica_seen' => 'Nb Görülen replikaların miktarı',
	'Class:SynchroLog/Attribute:stats_nb_replica_total' => 'Replica Toplamı',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted' => 'Silinen nesne miktarı',
	'Class:SynchroLog/Attribute:stats_nb_obj_deleted_errors' => 'Silme sırasında hataların miktarı',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted' => 'Eskitilmiş NB nesnelerin miktarı',
	'Class:SynchroLog/Attribute:stats_nb_obj_obsoleted_errors' => 'Eskitme sırasında oluşan  Hataların miktarı',
	'Class:SynchroLog/Attribute:stats_nb_obj_created' => 'Oluşturulan nesnelerin miktarı',
	'Class:SynchroLog/Attribute:stats_nb_obj_created_errors' => 'Oluşturulurken meydana gelen hataların miktarı',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated' => 'Güncellenen nesnelerin miktarı',
	'Class:SynchroLog/Attribute:stats_nb_obj_updated_errors' => 'Güncellenirken oluşan hataların miktarı',
	'Class:SynchroLog/Attribute:stats_nb_replica_reconciled_errors' => 'Uzlaşma Sırasında oluşan hataların miktarı',
	'Class:SynchroLog/Attribute:stats_nb_replica_disappeared_no_action' => 'Kaybolan replikaların miktarı',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_updated' => 'Güncellenen nesnelerin miktarı',
	'Class:SynchroLog/Attribute:stats_nb_obj_new_unchanged' => 'Değiştirilmeyen nesnelerin miktarı',
	'Class:SynchroLog/Attribute:last_error' => 'Son hata',
	'Class:SynchroLog/Attribute:traces' => 'İzler',
	'Class:SynchroReplica' => 'Synchro Replica',
	'Class:SynchroReplica/Attribute:sync_source_id' => 'Synchro Veri Kaynağı',
	'Class:SynchroReplica/Attribute:dest_id' => 'Hedef Nesnesi (ID)',
	'Class:SynchroReplica/Attribute:dest_class' => 'Hedef Türü',
	'Class:SynchroReplica/Attribute:status_last_seen' => 'Son görülme',
	'Class:SynchroReplica/Attribute:status' => 'Durum',
	'Class:SynchroReplica/Attribute:status/Value:modified' => 'Değiştirilmiş',
	'Class:SynchroReplica/Attribute:status/Value:new' => 'Yeni',
	'Class:SynchroReplica/Attribute:status/Value:obsolete' => 'Kullanım dışı',
	'Class:SynchroReplica/Attribute:status/Value:orphan' => 'Sahipsiz',
	'Class:SynchroReplica/Attribute:status/Value:synchronized' => 'Senkronize edilmiş',
	'Class:SynchroReplica/Attribute:status_dest_creator' => 'Nesne yaratıldı?',
	'Class:SynchroReplica/Attribute:status_last_error' => 'Son hata',
	'Class:SynchroReplica/Attribute:status_last_warning' => 'Uyarılar',
	'Class:SynchroReplica/Attribute:info_creation_date' => 'Yaratılış Tarihi',
	'Class:SynchroReplica/Attribute:info_last_modified' => 'Son değiştirilme tarih',
	'Class:appUserPreferences' => 'Kullanıcı Tercihleri',
	'Class:appUserPreferences/Attribute:userid' => 'Kullanıcı',
	'Class:appUserPreferences/Attribute:preferences' => 'Tercihler',
	'Core:ExecProcess:Code1' => 'Yanlış komut veya komut hataları ile bitti (örneğin, yanlış senaryo adı)',
	'Core:ExecProcess:Code255' => 'PHP hatası (ayrıştırma veya çalışma zamanı)',
    // Attribute Duration
	'Core:Duration_Seconds' => '%1$ds',
	'Core:Duration_Minutes_Seconds' => '%1$dmin %2$ds',
	'Core:Duration_Hours_Minutes_Seconds' => '%1$dh %2$dmin %3$ds',
	'Core:Duration_Days_Hours_Minutes_Seconds' => '%1$sd %2$dh %3$dmin %4$ds',
    // Explain working time computing
	'Core:ExplainWTC:ElapsedTime' => 'Geçen zaman (\\"%1$s\\" olarak saklanır)',
	'Core:ExplainWTC:StopWatch-TimeSpent' => '\\"%1$s\\" için harcanan zaman',
	'Core:ExplainWTC:StopWatch-Deadline' => '\\"%1$s\\" için son tarih %2$d%%',
    // Bulk export
	'Core:BulkExport:MissingParameter_Param' => 'Eksik parametre \\"%1$s\\"',
	'Core:BulkExport:InvalidParameter_Query' => '\\"Sorgu\\" parametresi için geçersiz değer. ID\'ye karşılık gelen sorgu dizimi yok: \\"%1$s\\".',
	'Core:BulkExport:ExportFormatPrompt' => 'Dışarı çıkartma formatı:',
	'Core:BulkExportOf_Class' => '%1$s dışarı çıkartıldı',
	'Core:BulkExport:ClickHereToDownload_FileName' => '%1$s \'indirmek için buraya tıklayın',
	'Core:BulkExport:ExportResult' => 'Dışarı çıkartma sonucu:',
	'Core:BulkExport:RetrievingData' => 'Verileri Alma...',
	'Core:BulkExport:HTMLFormat' => 'Web sayfası (*.html)',
	'Core:BulkExport:CSVFormat' => 'Virgülle ayrılmış değerler (*.csv)',
	'Core:BulkExport:XLSXFormat' => 'Excel 2007 veya daha yeni (*.xlsx)',
	'Core:BulkExport:PDFFormat' => 'PDF belgesi (*.pdf)',
	'Core:BulkExport:DragAndDropHelp' => 'Sütunları düzenlemek için sütunlar \'başlıklarını sürükleyip bırakın. %1$s satırlarının önizlemesi. Dışarı aktarılacak toplam satır sayısı: %2$s.',
	'Core:BulkExport:EmptyPreview' => 'Yukarıdaki listeden dışa aktarılacak sütunları seçin',
	'Core:BulkExport:ColumnsOrder' => 'Sütunların sırası',
	'Core:BulkExport:AvailableColumnsFrom_Class' => '%1$s \'den sonraki uygun sütunlar',
	'Core:BulkExport:NoFieldSelected' => 'Dışarı aktarılacak en az bir sütun seçin',
	'Core:BulkExport:CheckAll' => 'Hepsini kontrol edin',
	'Core:BulkExport:UncheckAll' => 'Hepsinin işaretini kaldırın',
	'Core:BulkExport:ExportCancelledByUser' => 'Dışarı aktarma kullanıcı tarafından iptal edildi',
	'Core:BulkExport:CSVOptions' => 'CSV Seçenekleri',
	'Core:BulkExport:CSVLocalization' => 'Yerelleştirme',
	'Core:BulkExport:PDFOptions' => 'PDF Seçenekleri',
	'Core:BulkExport:PDFPageFormat' => 'Sayfa Biçimi',
	'Core:BulkExport:PDFPageSize' => 'Sayfa Boyutu:',
	'Core:BulkExport:PageSize-A4' => 'A4',
	'Core:BulkExport:PageSize-A3' => 'A3',
	'Core:BulkExport:PageSize-Letter' => 'Mektup',
	'Core:BulkExport:PDFPageOrientation' => 'Sayfa Yönlendirme:',
	'Core:BulkExport:PageOrientation-L' => 'Yatay',
	'Core:BulkExport:PageOrientation-P' => 'Dikey',
	'Core:BulkExport:XMLFormat' => 'XML dosyası (*.xml)',
	'Core:BulkExport:XMLOptions' => 'XML Seçenekleri',
	'Core:BulkExport:SpreadsheetFormat' => 'Elektronik tablo HTML formatı (*.html)',
	'Core:BulkExport:SpreadsheetOptions' => 'Elektronik tablo seçenekleri',
	'Core:BulkExport:OptionNoLocalize' => 'Değerleri yerelleştirmeyin (sayılmamış alanlar için)',
	'Core:BulkExport:OptionLinkSets' => 'Bağlantılı nesneleri ekleyin',
	'Core:BulkExport:OptionFormattedText' => 'Metin biçimlendirmesini koru',
	'Core:BulkExport:ScopeDefinition' => 'Dışarı çıkartma için nesnelerin tanımı',
	'Core:BulkExportLabelOQLExpression' => 'OQL sorgusu:',
	'Core:BulkExportLabelPhrasebookEntry' => 'Sorgu dizimi girişi',
	'Core:BulkExportMessageEmptyOQL' => 'Lütfen geçerli bir OQL sorgusu girin',
	'Core:BulkExportMessageEmptyPhrasebookEntry' => 'Lütfen geçerli bir dizim girişi seçin',
	'Core:BulkExportQueryPlaceholder' => 'Buraya bir OQL sorgusu yazın...',
	'Core:BulkExportCanRunNonInteractive' => 'Dışarı aktarmayı etkileşimli olmayan modda çalıştırmak için buraya tıklayın.',
	'Core:BulkExportLegacyExport' => 'Eski dışarı aktarmaya  erişmek için buraya tıklayın.',
	'Core:BulkExport:XLSXOptions' => 'Excel Seçenekleri',
	'Core:BulkExport:TextFormat' => 'Bazı HTML işaretlemesi içeren metin alanları',
	'Core:BulkExport:DateTimeFormat' => 'Date and Time format~~',
	'Core:BulkExport:DateTimeFormatDefault_Example' => 'Default format (%1$s), e.g. %2$s~~',
	'Core:BulkExport:DateTimeFormatCustom_Format' => 'Custom format: %1$s~~',
	'Core:BulkExport:PDF:PageNumber' => 'Page %1$s~~',
	'Core:DateTime:Placeholder_d' => 'DD~~',
    // Day of the month: 2 digits (with leading zero)
	'Core:DateTime:Placeholder_j' => 'D~~',
    // Day of the month: 1 or 2 digits (without leading zero)
	'Core:DateTime:Placeholder_m' => 'MM~~',
    // Month on 2 digits i.e. 01-12
	'Core:DateTime:Placeholder_n' => 'M~~',
    // Month on 1 or 2 digits 1-12
	'Core:DateTime:Placeholder_Y' => 'YYYY~~',
    // Year on 4 digits
	'Core:DateTime:Placeholder_y' => 'YY~~',
    // Year on 2 digits
	'Core:DateTime:Placeholder_H' => 'hh~~',
    // Hour 00..23
	'Core:DateTime:Placeholder_h' => 'h~~',
    // Hour 01..12
	'Core:DateTime:Placeholder_G' => 'hh~~',
    // Hour 0..23
	'Core:DateTime:Placeholder_g' => 'h~~',
    // Hour 1..12
	'Core:DateTime:Placeholder_a' => 'am/pm~~',
    // am/pm (lowercase)
	'Core:DateTime:Placeholder_A' => 'AM/PM~~',
    // AM/PM (uppercase)
	'Core:DateTime:Placeholder_i' => 'mm~~',
    // minutes, 2 digits: 00..59
	'Core:DateTime:Placeholder_s' => 'ss~~',
    // seconds, 2 digits 00..59
	'Core:Validator:Default' => 'Yanlış format',
	'Core:Validator:Mandatory' => 'Lütfen bu alanı doldurun',
	'Core:Validator:MustBeInteger' => 'Bir tamsayı olmalı',
	'Core:Validator:MustSelectOne' => 'Lütfen bir tane seçin',
));

//
// Class: TagSetFieldData
//
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:TagSetFieldData' => '%2$s for class %1$s~~',
	'Class:TagSetFieldData+' => '',
	'Class:TagSetFieldData/Attribute:code' => 'Code~~',
	'Class:TagSetFieldData/Attribute:code+' => 'Internal code. Must contain at least 3 alphanumeric characters~~',
	'Class:TagSetFieldData/Attribute:label' => 'Label~~',
	'Class:TagSetFieldData/Attribute:label+' => 'Displayed label~~',
	'Class:TagSetFieldData/Attribute:description' => 'Description~~',
	'Class:TagSetFieldData/Attribute:description+' => '',
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
	'Class:DBProperty+' => '',
	'Class:DBProperty/Attribute:name' => 'Name~~',
	'Class:DBProperty/Attribute:name+' => '',
	'Class:DBProperty/Attribute:description' => 'Description~~',
	'Class:DBProperty/Attribute:description+' => '',
	'Class:DBProperty/Attribute:value' => 'Value~~',
	'Class:DBProperty/Attribute:value+' => '',
	'Class:DBProperty/Attribute:change_date' => 'Change date~~',
	'Class:DBProperty/Attribute:change_date+' => '',
	'Class:DBProperty/Attribute:change_comment' => 'Change comment~~',
	'Class:DBProperty/Attribute:change_comment+' => '',
));

//
// Class: BackgroundTask
//
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:BackgroundTask' => 'Background task~~',
	'Class:BackgroundTask+' => '',
	'Class:BackgroundTask/Attribute:class_name' => 'Class name~~',
	'Class:BackgroundTask/Attribute:class_name+' => '',
	'Class:BackgroundTask/Attribute:first_run_date' => 'First run date~~',
	'Class:BackgroundTask/Attribute:first_run_date+' => '',
	'Class:BackgroundTask/Attribute:latest_run_date' => 'Latest run date~~',
	'Class:BackgroundTask/Attribute:latest_run_date+' => '',
	'Class:BackgroundTask/Attribute:next_run_date' => 'Next run date~~',
	'Class:BackgroundTask/Attribute:next_run_date+' => '',
	'Class:BackgroundTask/Attribute:total_exec_count' => 'Total exec. count~~',
	'Class:BackgroundTask/Attribute:total_exec_count+' => '',
	'Class:BackgroundTask/Attribute:latest_run_duration' => 'Latest run duration~~',
	'Class:BackgroundTask/Attribute:latest_run_duration+' => '',
	'Class:BackgroundTask/Attribute:min_run_duration' => 'Min. run duration~~',
	'Class:BackgroundTask/Attribute:min_run_duration+' => '',
	'Class:BackgroundTask/Attribute:max_run_duration' => 'Max. run duration~~',
	'Class:BackgroundTask/Attribute:max_run_duration+' => '',
	'Class:BackgroundTask/Attribute:average_run_duration' => 'Average run duration~~',
	'Class:BackgroundTask/Attribute:average_run_duration+' => '',
	'Class:BackgroundTask/Attribute:running' => 'Running~~',
	'Class:BackgroundTask/Attribute:running+' => '',
	'Class:BackgroundTask/Attribute:status' => 'Status~~',
	'Class:BackgroundTask/Attribute:status+' => '',
));

//
// Class: AsyncTask
//
Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:AsyncTask' => 'Async. task~~',
	'Class:AsyncTask+' => '',
	'Class:AsyncTask/Attribute:created' => 'Created~~',
	'Class:AsyncTask/Attribute:created+' => '',
	'Class:AsyncTask/Attribute:started' => 'Started~~',
	'Class:AsyncTask/Attribute:started+' => '',
	'Class:AsyncTask/Attribute:planned' => 'Planned~~',
	'Class:AsyncTask/Attribute:planned+' => '',
	'Class:AsyncTask/Attribute:event_id' => 'Event~~',
	'Class:AsyncTask/Attribute:event_id+' => '',
	'Class:AsyncTask/Attribute:finalclass' => 'Final class~~',
	'Class:AsyncTask/Attribute:finalclass+' => '',
	'Class:AsyncTask/Attribute:status' => 'Status~~',
	'Class:AsyncTask/Attribute:status+' => '',
	'Class:AsyncTask/Attribute:remaining_retries' => 'Remaining retries~~',
	'Class:AsyncTask/Attribute:remaining_retries+' => '',
	'Class:AsyncTask/Attribute:last_error_code' => 'Last error code~~',
	'Class:AsyncTask/Attribute:last_error_code+' => '',
	'Class:AsyncTask/Attribute:last_error' => 'Last error~~',
	'Class:AsyncTask/Attribute:last_error+' => '',
	'Class:AsyncTask/Attribute:last_attempt' => 'Last attempt~~',
	'Class:AsyncTask/Attribute:last_attempt+' => '',
	'Class:AsyncTask:InvalidConfig_Class_Keys' => 'Invalid format for the configuration of "async_task_retries[%1$s]". Expecting an array with the following keys: %2$s~~',
	'Class:AsyncTask:InvalidConfig_Class_InvalidKey_Keys' => 'Invalid format for the configuration of "async_task_retries[%1$s]": unexpected key "%2$s". Expecting only the following keys: %3$s~~',
));

//
// Class: AbstractResource
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:AbstractResource' => 'Abstract Resource~~',
	'Class:AbstractResource+' => '',
));

//
// Class: ResourceAdminMenu
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:ResourceAdminMenu' => 'Resource Admin Menu~~',
	'Class:ResourceAdminMenu+' => '',
));

//
// Class: ResourceRunQueriesMenu
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:ResourceRunQueriesMenu' => 'Resource Run Queries Menu~~',
	'Class:ResourceRunQueriesMenu+' => '',
));

//
// Class: Action
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:ResourceSystemMenu' => 'Resource System Menu~~',
	'Class:ResourceSystemMenu+' => '',
));



