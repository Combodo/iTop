<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2023 Combodo SARL
 * @license	http://opensource.org/licenses/AGPL-3.0
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 */
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Attachments:TabTitle_Count' => 'Mellékletek (%1$d)',
	'Attachments:EmptyTabTitle' => 'Mellékletek',
	'Attachments:FieldsetTitle' => 'Mellékletek',
	'Attachments:DeleteBtn' => 'Törlés',
	'Attachments:History_File_Added' => '%1$s melléklet hozzáadva',
	'Attachments:History_File_Removed' => '%1$s melléklet eltávolítva',
	'Attachments:AddAttachment' => 'Melléklet hozzáadása: ',
	'Attachments:UploadNotAllowedOnThisSystem' => 'A fájlfeltöltés nem engedélyezett ezen a rendszeren',
	'Attachment:Max_Go' => '(Maximum fájlméret: %1$s GB)',
	'Attachment:Max_Mo' => '(Maximum fájlméret: %1$s MB)',
	'Attachment:Max_Ko' => '(Maximum fájlméret: %1$s KB)',
	'Attachments:NoAttachment' => 'Nincs melléklet. ',
	'Attachments:PreviewNotAvailable' => 'Az előnézet nem érhető el ilyen típusú melléklethez',
	'Attachments:Error:FileTooLarge' => 'Túl nagy a fájl a feltöltéshez. %1$s',
	'Attachments:Error:UploadedFileEmpty' => 'A kapott fájl üres, ezért nem csatolható. Vagy egy üres fájlt húzott be, vagy kérdezze meg a rendszergazdát, hátha az iTop szerver lemeze telt meg.',
	'Attachments:Render:Icons' => 'Mutassa ikonként',
	'Attachments:Render:Table' => 'Mutassa listaként',
	'UI:Attachments:DropYourFileHint' => 'Húzza a fájlokat erre a területre',
));

//
// Class: Attachment
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Attachment' => 'Mellékletek',
	'Class:Attachment+' => '~~',
	'Class:Attachment/Attribute:expire' => 'Lejárat',
	'Class:Attachment/Attribute:expire+' => '~~',
	'Class:Attachment/Attribute:temp_id' => 'Átmeneti azonosító',
	'Class:Attachment/Attribute:temp_id+' => '~~',
	'Class:Attachment/Attribute:item_class' => 'Elem osztály',
	'Class:Attachment/Attribute:item_class+' => '~~',
	'Class:Attachment/Attribute:item_id' => 'Elem',
	'Class:Attachment/Attribute:item_id+' => '~~',
	'Class:Attachment/Attribute:item_org_id' => 'Elem szervezeti egység',
	'Class:Attachment/Attribute:item_org_id+' => '~~',
	'Class:Attachment/Attribute:contents' => 'Tartalom',
	'Class:Attachment/Attribute:contents+' => '~~',
));


Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Attachments:File:Thumbnail' => 'Ikon',
	'Attachments:File:Name' => 'Fájlnév',
	'Attachments:File:Date' => 'Feltöltés dátuma',
	'Attachments:File:Uploader' => 'Feltöltötte ',
	'Attachments:File:Size' => 'Méret',
	'Attachments:File:MimeType' => 'Típus',
	'Attachments:File:DownloadsCount' => 'Downloads~~',
));
//
// Class: Attachment
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:Attachment/Attribute:creation_date' => 'Létrehozás dátuma',
	'Class:Attachment/Attribute:creation_date+' => '~~',
	'Class:Attachment/Attribute:user_id' => 'Felhasználó',
	'Class:Attachment/Attribute:user_id+' => '~~',
	'Class:Attachment/Attribute:contact_id' => 'Kapcsolattartó',
	'Class:Attachment/Attribute:contact_id+' => '~~',
));

//
// Class: TriggerOnAttachmentDownload
//

Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'Class:TriggerOnAttachmentDownload' => 'Trigger (on object\'s attachment download)~~',
	'Class:TriggerOnAttachmentDownload+' => 'Trigger on object\'s attachment download of [a child class of] the given class~~',
));
