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
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Attachments:TabTitle_Count' => 'Allegati (%1$d)',
	'Attachments:EmptyTabTitle' => 'Allegati',
	'Attachments:FieldsetTitle' => 'Allegati',
	'Attachments:DeleteBtn' => 'Elimina',
	'Attachments:History_File_Added' => 'Allegato %1$s aggiunto.',
	'Attachments:History_File_Removed' => 'Allegato %1$s rimosso.',
	'Attachments:AddAttachment' => 'Aggiungi allegato: ',
	'Attachments:UploadNotAllowedOnThisSystem' => 'Caricamento file NON consentito su questo sistema.',
	'Attachment:Max_Go' => '(Dimensione massima del file: %1$s GB)',
	'Attachment:Max_Mo' => '(Dimensione massima del file: %1$s MB)',
	'Attachment:Max_Ko' => '(Dimensione massima del file: %1$s KB)',
	'Attachments:NoAttachment' => 'Nessun allegato. ',
	'Attachments:PreviewNotAvailable' => 'Anteprima non disponibile per questo tipo di allegato.',
	'Attachments:Error:FileTooLarge' => 'Il file è troppo grande per essere caricato. %1$s',
	'Attachments:Error:UploadedFileEmpty' => 'Il file ricevuto è vuoto e non può essere allegato.
	O hai inviato un file vuoto,
	o chiedi al tuo amministratore di '.ITOP_APPLICATION_SHORT.' se il disco del server '.ITOP_APPLICATION_SHORT.' è pieno.',
	'Attachments:Render:Icons' => 'Visualizza come icone',
	'Attachments:Render:Table' => 'Visualizza come lista',
	'UI:Attachments:DropYourFileHint' => 'Rilascia i file ovunque in quest\'area',
));

//
// Class: Attachment
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Attachment' => 'Allegato',
	'Class:Attachment+' => '~~',
	'Class:Attachment/Attribute:expire' => 'Scadenza',
	'Class:Attachment/Attribute:expire+' => '~~',
	'Class:Attachment/Attribute:temp_id' => 'ID temporaneo',
	'Class:Attachment/Attribute:temp_id+' => '~~',
	'Class:Attachment/Attribute:item_class' => 'Classe dell\'oggetto',
	'Class:Attachment/Attribute:item_class+' => '~~',
	'Class:Attachment/Attribute:item_id' => 'Oggetto',
	'Class:Attachment/Attribute:item_id+' => '~~',
	'Class:Attachment/Attribute:item_org_id' => 'Organizzazione dell\'oggetto',
	'Class:Attachment/Attribute:item_org_id+' => '~~',
	'Class:Attachment/Attribute:contents' => 'Contenuti',
	'Class:Attachment/Attribute:contents+' => '~~',
));


Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Attachments:File:Thumbnail' => 'Icona',
	'Attachments:File:Name' => 'Nome del file',
	'Attachments:File:Date' => 'Data di caricamento',
	'Attachments:File:Uploader' => 'Caricato da',
	'Attachments:File:Size' => 'Dimensione',
	'Attachments:File:MimeType' => 'Tipo',
	'Attachments:File:DownloadsCount' => 'Download',
));
//
// Class: Attachment
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:Attachment/Attribute:creation_date' => 'Data di creazione',
	'Class:Attachment/Attribute:creation_date+' => '~~',
	'Class:Attachment/Attribute:user_id' => 'ID utente',
	'Class:Attachment/Attribute:user_id+' => '~~',
	'Class:Attachment/Attribute:contact_id' => 'ID contatto',
	'Class:Attachment/Attribute:contact_id+' => '~~',
));

//
// Class: TriggerOnAttachmentDownload
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:TriggerOnAttachmentDownload' => 'Trigger (al download di un allegato dell\'oggetto)',
	'Class:TriggerOnAttachmentDownload+' => 'Trigger al download di un allegato di un oggetto di [una sottoclasse di] la classe data',
));
