<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 *
 */
/**
 *
 */
Dict::Add('PL PL', 'Polish', 'Polski', [
	'Attachment:Max_Go' => '(Maksymalny rozmiar pliku: %1$s Go)',
	'Attachment:Max_Ko' => '(Maksymalny rozmiar pliku: %1$s Ko)',
	'Attachment:Max_Mo' => '(Maksymalny rozmiar pliku: %1$s Mo)',
	'Attachments:AddAttachment' => 'Dodaj załącznik: ',
	'Attachments:DeleteBtn' => 'Usuń',
	'Attachments:EmptyTabTitle' => 'Załączniki',
	'Attachments:Error:FileTooLarge' => 'Plik jest za duży do przesłania. %1$s',
	'Attachments:Error:UploadedFileEmpty' => 'Otrzymany plik jest pusty i nie można go załączyć.
Albo przesłałeś pusty plik,
lub zapytaj administratora '.ITOP_APPLICATION_SHORT.', czy dysk serwera '.ITOP_APPLICATION_SHORT.' jest pełny.',
	'Attachments:FieldsetTitle' => 'Załączniki',
	'Attachments:File:Date' => 'Data przesłania',
	'Attachments:File:DownloadsCount' => 'Pobrano',
	'Attachments:File:MimeType' => 'Typ',
	'Attachments:File:Name' => 'Nazwa pliku',
	'Attachments:File:Size' => 'Rozmiar',
	'Attachments:File:Thumbnail' => 'Ikona',
	'Attachments:File:Uploader' => 'Przesłany przez',
	'Attachments:History_File_Added' => 'Załącznik %1$s dodano.',
	'Attachments:History_File_Removed' => 'Załącznik %1$s usunięto.',
	'Attachments:NoAttachment' => 'Brak załącznika. ',
	'Attachments:PreviewNotAvailable' => 'Podgląd nie jest dostępny dla tego typu załącznika.',
	'Attachments:Render:Icons' => 'Wyświetlaj jako ikony',
	'Attachments:Render:Table' => 'Wyświetl jako listę',
	'Attachments:TabTitle_Count' => 'Załączniki (%1$d)',
	'Attachments:UploadNotAllowedOnThisSystem' => 'Przesyłanie plików NIE jest dozwolone w tym systemie.',
	'Class:Attachment' => 'Załącznik',
	'Class:Attachment+' => '',
	'Class:Attachment/Attribute:contact_id' => 'Id kontaktu',
	'Class:Attachment/Attribute:contact_id+' => '',
	'Class:Attachment/Attribute:contents' => 'Zawartość',
	'Class:Attachment/Attribute:contents+' => '',
	'Class:Attachment/Attribute:creation_date' => 'Data utworzenia',
	'Class:Attachment/Attribute:creation_date+' => '',
	'Class:Attachment/Attribute:expire' => 'Wygasa',
	'Class:Attachment/Attribute:expire+' => '',
	'Class:Attachment/Attribute:item_class' => 'Klasa pozycji',
	'Class:Attachment/Attribute:item_class+' => '',
	'Class:Attachment/Attribute:item_id' => 'Pozycja',
	'Class:Attachment/Attribute:item_id+' => '',
	'Class:Attachment/Attribute:item_org_id' => 'Organizacja pozycji',
	'Class:Attachment/Attribute:item_org_id+' => '',
	'Class:Attachment/Attribute:temp_id' => 'Id tymczasowy',
	'Class:Attachment/Attribute:temp_id+' => '',
	'Class:Attachment/Attribute:user_id' => 'Id użytkownika',
	'Class:Attachment/Attribute:user_id+' => '',
	'Class:TriggerOnAttachmentDownload' => 'Wyzwalacz (po pobraniu załącznika obiektu)',
	'Class:TriggerOnAttachmentDownload+' => 'Wyzwalacz po pobraniu załącznika obiektu [klasy podrzędnej] danej klasy',
	'UI:Attachments:DropYourFileHint' => 'Upuść pliki w dowolnym miejscu w tym obszarze',
    'Class:TriggerOnAttachmentCreate'                         => 'Trigger (on object\'s attachment create)~~',
    'Class:TriggerOnAttachmentCreate+'                        => 'Trigger on object\'s attachment create~~',
	'Class:TriggerOnAttachmentCreate/Attribute:file_in_email' => 'Add file in email~~',
	'Class:TriggerOnAttachmentCreate/Attribute:file_in_email+' => 'If checked, the file will be automatically attached to the email when an email action is triggered~~',
	'Class:TriggerOnAttachmentDelete'                         => 'Trigger (on object\'s attachment delete)~~',
	'Class:TriggerOnAttachmentDelete+'                        => 'Trigger on object\'s attachment delete~~',
    'Class:TriggerOnObject:TriggerClassAttachment/ReadOnlyMessage' => 'Trigger on object is not allowed on class Attachment. Please use specific trigger~~',
]);
