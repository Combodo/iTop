<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */
/**
 * @author Lukáš Dvořák <lukas.dvorak@itopportal.cz>
 * @author Daniel Rokos <daniel.rokos@itopportal.cz>
 *
 */
Dict::Add('CS CZ', 'Czech', 'Čeština', [
	'Attachment:Max_Go' => '(Maximální velikost souboru: %1$s GiB)',
	'Attachment:Max_Ko' => '(Maximální velikost souboru: %1$s KiB)',
	'Attachment:Max_Mo' => '(Maximální velikost souboru: %1$s MiB)',
	'Attachments:AddAttachment' => 'Přidat přílohu: ',
	'Attachments:DeleteBtn' => 'Odstranit',
	'Attachments:EmptyTabTitle' => 'Přílohy',
	'Attachments:Error:FileTooLarge' => 'Soubor je příliš velký k nahrání. %1$s',
	'Attachments:Error:UploadedFileEmpty' => 'Obdržený soubr je prázný a nemůže být přiložen.
Buťo jste odeslali prázný soubor,
nebe se zeptejte'.ITOP_APPLICATION_SHORT.' správce '.ITOP_APPLICATION_SHORT.' zda není zaplněný disk.',
	'Attachments:FieldsetTitle' => 'Přílohy',
	'Attachments:File:Date' => 'Datum nahrání',
	'Attachments:File:DownloadsCount' => 'Staženo',
	'Attachments:File:MimeType' => 'Typ',
	'Attachments:File:Name' => 'Jméno souboru',
	'Attachments:File:Size' => 'Velikost',
	'Attachments:File:Thumbnail' => 'Ikona',
	'Attachments:File:Uploader' => 'Nahráno',
	'Attachments:History_File_Added' => 'Příloha %1$s byla přidána.',
	'Attachments:History_File_Removed' => 'Příloha %1$s byla odstraněna.',
	'Attachments:NoAttachment' => 'Žádná příloha. ',
	'Attachments:PreviewNotAvailable' => 'Pro tento typ přílohy není náhled k dispozici.',
	'Attachments:Render:Icons' => 'Zobrazit jako ikony',
	'Attachments:Render:Table' => 'Zobrazit jako seznam',
	'Attachments:TabTitle_Count' => 'Přílohy (%1$d)',
	'Attachments:UploadNotAllowedOnThisSystem' => 'Na tomto systému není povoleno nahrávání souborů.',
	'Class:Attachment' => 'Příloha',
	'Class:Attachment+' => '~~',
	'Class:Attachment/Attribute:contact_id' => 'Kontakt',
	'Class:Attachment/Attribute:contact_id+' => '~~',
	'Class:Attachment/Attribute:contents' => 'Obsah',
	'Class:Attachment/Attribute:contents+' => '~~',
	'Class:Attachment/Attribute:creation_date' => 'Datum vytvoření',
	'Class:Attachment/Attribute:creation_date+' => '~~',
	'Class:Attachment/Attribute:expire' => 'Exspirace',
	'Class:Attachment/Attribute:expire+' => '~~',
	'Class:Attachment/Attribute:item_class' => 'Položka třídy',
	'Class:Attachment/Attribute:item_class+' => '~~',
	'Class:Attachment/Attribute:item_id' => 'Položka',
	'Class:Attachment/Attribute:item_id+' => '~~',
	'Class:Attachment/Attribute:item_org_id' => 'Organizace',
	'Class:Attachment/Attribute:item_org_id+' => '~~',
	'Class:Attachment/Attribute:temp_id' => 'Dočasné id',
	'Class:Attachment/Attribute:temp_id+' => '~~',
	'Class:Attachment/Attribute:user_id' => 'Jméno uživatele',
	'Class:Attachment/Attribute:user_id+' => '~~',
	'Class:TriggerOnAttachmentDownload'                       => 'Trigger (on object\'s attachment download)~~',
	'Class:TriggerOnAttachmentDownload+'                      => 'Trigger on object\'s attachment download of [a child class of] the given class~~',
	'UI:Attachments:DropYourFileHint' => 'Drop files anywhere in this area~~',
	'Class:TriggerOnAttachmentCreate'                         => 'Trigger (on object\'s attachment create)~~',
	'Class:TriggerOnAttachmentCreate+'                        => 'Trigger on object\'s attachment create~~',
	'Class:TriggerOnAttachmentCreate/Attribute:file_in_email' => 'Add file in email~~',
	'Class:TriggerOnAttachmentCreate/Attribute:file_in_email+' => 'If checked, the file will be automatically attached to the email when an email action is triggered~~',
	'Class:TriggerOnAttachmentDelete'                         => 'Trigger (on object\'s attachment delete)~~',
	'Class:TriggerOnAttachmentDelete+'                        => 'Trigger on object\'s attachment delete~~',
    'Class:TriggerOnObject:TriggerClassAttachment/ReadOnlyMessage' => 'Trigger on object is not allowed on class Attachment. Please use specific trigger~~',
]);
