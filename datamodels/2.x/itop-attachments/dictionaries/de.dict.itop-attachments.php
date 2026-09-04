<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */
/**
 * @author ITOMIG GmbH <martin.raenker@itomig.de>
 *
 */
Dict::Add('DE DE', 'German', 'Deutsch', [
	'Attachments:TabTitle_Count' => 'Attachments (%1$d)',
	'Attachments:EmptyTabTitle' => 'Attachments',
	'Attachments:FieldsetTitle' => 'Attachments',
	'Attachments:DeleteBtn' => 'Löschen',
	'Attachments:History_File_Added' => 'Attachment %1$s hinzugefügt.',
	'Attachments:History_File_Removed' => 'Attachment %1$s entfernt.',
	'Attachments:AddAttachment' => 'Attachment hinzufügen: ',
	'Attachments:UploadNotAllowedOnThisSystem' => 'Dateiupload in diesem System NICHT erlaubt',
	'Attachment:Max_Go' => '(Maximale Dateigröße: %1$s GB)',
	'Attachment:Max_Mo' => '(Maximale Dateigröße: %1$s MB)',
	'Attachment:Max_Ko' => '(Maximale Dateigröße: %1$s KB)',
	'Attachments:NoAttachment' => 'Kein Attachment',
	'Attachments:PreviewNotAvailable' => 'Vorschau für diesen Attachment-Typ nicht verfügbar',
	'Attachments:Error:FileTooLarge' => 'Die Datei ist zu groß für den Upload: %1$s',
	'Attachments:Error:UploadedFileEmpty' => 'Die Datei ist leer und kann nicht angehängt werden.
Entweder ist die von Ihnen hochdeladene Datei leer,
oder melden Sie dem '.ITOP_APPLICATION_SHORT.' Administrator diesen Fehler, weil eventuell kein ausreichender Speicherplatz zur Verfügung steht',
	'Attachments:Render:Icons' => 'Als Icons anzeigen',
	'Attachments:Render:Table' => 'Als Liste anzeigen',
	'UI:Attachments:DropYourFileHint' => 'Dateien in diesem Bereich ablegen...',
	'Class:Attachment' => 'Attachment',
	'Class:Attachment+' => 'Datei – Text oder Bild –, die mit genau einem Objekt verknüpft ist. Sie kann nicht geändert, sondern nur gelöscht werden. Anhänge lassen sich ausschließlich beim Bearbeiten ihres Objekts anlegen.',
	'Class:Attachment/Attribute:expire' => 'Läuft ab',
	'Class:Attachment/Attribute:expire+' => '~~',
	'Class:Attachment/Attribute:temp_id' => 'Temporäre ID',
	'Class:Attachment/Attribute:temp_id+' => '~~',
	'Class:Attachment/Attribute:item_class' => 'Itemklasse',
	'Class:Attachment/Attribute:item_class+' => '~~',
	'Class:Attachment/Attribute:item_id' => 'Item',
	'Class:Attachment/Attribute:item_id+' => '~~',
	'Class:Attachment/Attribute:item_org_id' => 'Item Organisation',
	'Class:Attachment/Attribute:item_org_id+' => '~~',
	'Class:Attachment/Attribute:contents' => 'Inhalt',
	'Class:Attachment/Attribute:contents+' => '~~',
	'Attachments:File:Thumbnail' => 'Icon',
	'Attachments:File:Name' => 'Dateiname',
	'Attachments:File:Date' => 'Upload-Datum',
	'Attachments:File:Uploader' => 'hochgeladen von',
	'Attachments:File:Size' => 'Größe',
	'Attachments:File:MimeType' => 'Typ',
	'Attachments:File:DownloadsCount' => 'Downloads',
	'Class:Attachment/Attribute:creation_date' => 'Erstellungsdatum',
	'Class:Attachment/Attribute:creation_date+' => '~~',
	'Class:Attachment/Attribute:user_id' => 'Benutzer ID',
	'Class:Attachment/Attribute:user_id+' => '~~',
	'Class:Attachment/Attribute:contact_id' => 'Kontakt ID',
	'Class:Attachment/Attribute:contact_id+' => '~~',
	'Class:TriggerOnAttachmentDownload' => 'Trigger (beim Herunterladen eines Attachment eines Objekts)',
	'Class:TriggerOnAttachmentDownload+' => 'Trigger für das Herunterladen des Attachments der angegebenen Klasse oder einer Unterklasse',
	'Class:TriggerOnAttachmentCreate' => 'Trigger (bei Erstellung eines Anhangs)',
	'Class:TriggerOnAttachmentCreate+' => 'Trigger bei Erstellung eines Anhangs an einem Objekt',
	'Class:TriggerOnAttachmentDelete' => 'Trigger (bei Löschung eines Anhangs)',
	'Class:TriggerOnAttachmentDelete+' => 'Trigger bei Löschung eines Anhangs an einem Objekt',
	'Class:TriggerOnAttachmentCreate/Attribute:file_in_email' => 'Datei an E-Mail anhängen',
	'Class:TriggerOnAttachmentCreate/Attribute:file_in_email+' => 'Wenn aktiviert, wird die Datei automatisch an die E-Mail angehängt, sobald eine E-Mail-Aktion ausgelöst wird',
	'Class:TriggerOnAttachmentDelete/Attribute:file_in_email' => 'Gelöschte Datei an E-Mail anhängen',
	'Class:TriggerOnAttachmentDelete/Attribute:file_in_email+' => 'Wenn aktiviert, wird die gelöschte Datei automatisch an die E-Mail angehängt, sobald eine E-Mail-Aktion ausgelöst wird',
	'Class:TriggerOnObject:TriggerClassAttachment/ReadOnlyMessage' => 'Ein klassenunabhängiger Trigger ist für die Klasse Attachment nicht zulässig. Bitte verwenden Sie einen spezifischen Trigger',
]);
