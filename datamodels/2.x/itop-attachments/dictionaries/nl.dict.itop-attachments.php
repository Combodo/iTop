<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */

/**
 * @author LinProfs <info@linprofs.com>
 * @author Jeffrey Bostoen <info@jeffreybostoen.be> (2018 - 2022)
 * @author Thomas Casteleyn <thomas.casteleyn@super-visions.com>
 */
Dict::Add('NL NL', 'Dutch', 'Nederlands', [
	'Attachment:Max_Go' => '(Maximale bestandsgrootte: %1$s GB)',
	'Attachment:Max_Ko' => '(Maximale bestandsgrootte: %1$s kB)',
	'Attachment:Max_Mo' => '(Maximale bestandsgrootte: %1$s MB)',
	'Attachments:AddAttachment' => 'Voeg bijlage toe: ',
	'Attachments:DeleteBtn' => 'Verwijder',
	'Attachments:EmptyTabTitle' => 'Bijlagen',
	'Attachments:Error:FileTooLarge' => 'Het bestand is te groot om geüpload te worden: %1$s',
	'Attachments:Error:UploadedFileEmpty' => 'Het bestand is leeg en kan niet worden toegevoegd.
Mogelijk heb je een leeg bestand geüpload,
of vraag de iTop administrator om de opslagruimte van de iTop-server na te kijken',
	'Attachments:FieldsetTitle' => 'Bijlagen',
	'Attachments:File:Date' => 'Geüpload op',
	'Attachments:File:DownloadsCount' => 'Downloads',
	'Attachments:File:MimeType' => 'Type',
	'Attachments:File:Name' => 'Bestandsnaam',
	'Attachments:File:Size' => 'Grootte',
	'Attachments:File:Thumbnail' => 'Pictogram',
	'Attachments:File:Uploader' => 'Geüpload door',
	'Attachments:History_File_Added' => 'Bijlage %1$s toegevoegd.',
	'Attachments:History_File_Removed' => 'Bijlage %1$s verwijderd.',
	'Attachments:NoAttachment' => 'Geen bijlage. ',
	'Attachments:PreviewNotAvailable' => 'Er is geen voorbeeld beschikbaar voor dit type bijlage.',
	'Attachments:Render:Icons' => 'Toon als pictogram',
	'Attachments:Render:Table' => 'Toon als lijst',
	'Attachments:TabTitle_Count' => 'Bijlagen (%1$d)',
	'Attachments:UploadNotAllowedOnThisSystem' => 'Bestanden uploaden is NIET toegestaan op dit systeem.',
	'Class:Attachment' => 'Bijlage',
	'Class:Attachment+' => '',
	'Class:Attachment/Attribute:contact_id' => 'ID Contact',
	'Class:Attachment/Attribute:contact_id+' => '',
	'Class:Attachment/Attribute:contents' => 'Inhoud',
	'Class:Attachment/Attribute:contents+' => '',
	'Class:Attachment/Attribute:creation_date' => 'Datum creatie',
	'Class:Attachment/Attribute:creation_date+' => '',
	'Class:Attachment/Attribute:expire' => 'Vervalt',
	'Class:Attachment/Attribute:expire+' => '',
	'Class:Attachment/Attribute:item_class' => 'Klasse item',
	'Class:Attachment/Attribute:item_class+' => '',
	'Class:Attachment/Attribute:item_id' => 'Item',
	'Class:Attachment/Attribute:item_id+' => '',
	'Class:Attachment/Attribute:item_org_id' => 'Organisatie item',
	'Class:Attachment/Attribute:item_org_id+' => '',
	'Class:Attachment/Attribute:temp_id' => 'Tijdelijke ID',
	'Class:Attachment/Attribute:temp_id+' => '',
	'Class:Attachment/Attribute:user_id' => 'ID Gebruiker',
	'Class:Attachment/Attribute:user_id+' => '',
	'Class:TriggerOnAttachmentDownload' => 'Trigger (Bij het downloaden van een bijlage)',
	'Class:TriggerOnAttachmentDownload+' => 'Trigger bij het downloaden van een bijlage van een object van de opgegeven klasse (of subklasse ervan)',
	'UI:Attachments:DropYourFileHint' => 'Sleep bestanden in dit gebied',
    'Class:TriggerOnAttachmentCreate'                         => 'Trigger (Bij het toevoegen van een bijlage)',
    'Class:TriggerOnAttachmentCreate+'                        => 'Trigger bij het toevoegen van een bijlage aan een object van de opgegeven klasse (of subklasse ervan)',
	'Class:TriggerOnAttachmentCreate/Attribute:file_in_email' => 'Bestand toevoegen in e-mail',
	'Class:TriggerOnAttachmentCreate/Attribute:file_in_email+'=> 'If checked, the file will be automatically attached to the email when an email action is triggered~~',
	'Class:TriggerOnAttachmentDelete'                         => 'Trigger (Bij het verwijderen van een bijlage)',
	'Class:TriggerOnAttachmentDelete+'                        => 'Trigger bij het verwijderen van een bijlage van een object van de opgegeven klasse (of subklasse ervan)',
    'Class:TriggerOnObject:TriggerClassAttachment/ReadOnlyMessage' => 'Trigger on object is not allowed on class Attachment. Please use specific trigger~~',
]);
