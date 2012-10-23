<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
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
	'Change:AttName_SetTo_NewValue_PreviousValue_OldValue' => '%1$s\'nin değeri %2$s olarak atandı (önceki değer: %3$s)',
	'Change:AttName_SetTo' => '%1$s\'nin değeri %2$s olarak atandı',
	'Change:Text_AppendedTo_AttName' => '%2$s\'ye %1$s eklendi',
	'Change:AttName_Changed_PreviousValue_OldValue' => '%1$\'nin değeri deiştirildi, önceki değer: %2$s',
	'Change:AttName_Changed' => '%1$s değiştirildi',
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
	'Class:ActionEmail/Attribute:bcc' => 'gizli kopya',
	'Class:ActionEmail/Attribute:bcc+' => 'Gizli alıcı',
	'Class:ActionEmail/Attribute:subject' => 'konu',
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
));

//
// Class: TriggerOnObject
//

Dict::Add('TR TR', 'Turkish', 'Türkçe', array(
	'Class:TriggerOnObject' => 'Tetiklenen (sınıf bağımlılığı)',
	'Class:TriggerOnObject+' => 'Verilen sınıflar üzerinde işlemleri gerçekleştir',
	'Class:TriggerOnObject/Attribute:target_class' => 'Hedef sınıf',
	'Class:TriggerOnObject/Attribute:target_class+' => '',
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


?>
