<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
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
// Database inconsistencies
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	// Dictionary entries go here
	'Menu:DBToolsMenu' => 'Strumenti DB',
	'DBTools:Class' => 'Classe',
	'DBTools:Title' => 'Strumenti di Manutenzione del Database',
	'DBTools:ErrorsFound' => 'Errori Trovati',
	'DBTools:Indication' => 'Importante: dopo aver corretto gli errori nel database dovrai eseguire nuovamente l\'analisi poiché verranno generati nuovi errori di coerenza',
	'DBTools:Disclaimer' => 'AVVERTENZA: EFFETTUA UN BACKUP DEL DATABASE PRIMA DI ESEGUIRE LE CORREZIONI',
	'DBTools:Error' => 'Errore',
	'DBTools:Count' => 'Conteggio',
	'DBTools:SQLquery' => 'Query SQL',
	'DBTools:FixitSQLquery' => 'Query SQL per la Correzione (indicazione)',
	'DBTools:SQLresult' => 'Risultato SQL',
	'DBTools:NoError' => 'Il database è OK',
	'DBTools:HideIds' => 'Elenco Errori',
	'DBTools:ShowIds' => 'Vista Dettagliata',
	'DBTools:ShowReport' => 'Rapporto',
	'DBTools:IntegrityCheck' => 'Controllo di Integrità',
	'DBTools:FetchCheck' => 'Controllo di Recupero (lungo)',
	'DBTools:SelectAnalysisType' => 'Seleziona tipo di analisi',
	'DBTools:Analyze' => 'Analizza',
	'DBTools:Details' => 'Mostra Dettagli',
	'DBTools:ShowAll' => 'Mostra Tutti gli Errori',
	'DBTools:Inconsistencies' => 'Incoerenze del Database',
	'DBTools:DetailedErrorTitle' => '%2$s errore(i) nella classe %1$s: %3$s',
	'DBTools:DetailedErrorLimit' => 'Elenco limitato a %1$s errori',
	'DBAnalyzer-Integrity-OrphanRecord' => 'Record orfano in `%1$s`, dovrebbe avere una controparte nella tabella `%2$s`',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Chiave esterna non valida %1$s (colonna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Chiave esterna mancante %1$s (colonna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => 'Valore non valido per %1$s (colonna: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Alcuni account utente non hanno alcun profilo',
	'DBAnalyzer-Integrity-HKInvalid' => 'Chiave gerarchica non valida `%1$s`',
	'DBAnalyzer-Fetch-Count-Error' => 'Errore di conteggio di recupero in `%1$s`, %2$d voci recuperate / %3$d conteggiate',
	'DBAnalyzer-Integrity-FinalClass' => 'Il campo `%2$s`.`%1$s` deve avere lo stesso valore di `%3$s`.`%1$s`',
	'DBAnalyzer-Integrity-RootFinalClass' => 'Il campo `%2$s`.`%1$s` deve contenere una classe valida',
));

// Database Info
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'DBTools:DatabaseInfo' => 'Informazioni Database',
	'DBTools:Base' => 'Base~~',
	'DBTools:Size' => 'Size~~',
));

// Lost attachments
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'DBTools:LostAttachments' => 'Allegati Persi',
	'DBTools:LostAttachments:Disclaimer' => 'Qui puoi cercare nel tuo database gli allegati persi o mal posizionati. Questo NON è uno strumento di recupero dati, non recupera dati cancellati.',
	'DBTools:LostAttachments:Button:Analyze' => 'Analizza',
	'DBTools:LostAttachments:Button:Restore' => 'Ripristina',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'Questa azione non può essere annullata, conferma di voler ripristinare i file selezionati.',
	'DBTools:LostAttachments:Button:Busy' => 'Attendere prego...',
	'DBTools:LostAttachments:Step:Analyze' => 'Prima di tutto, cerca gli allegati persi o mal posizionati analizzando il database.',
	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Risultati dell\'analisi:',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Ottimo! Sembra che tutto sia al posto giusto.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Alcuni allegati (%1$d) sembrano essere mal posizionati. Dai un\'occhiata alla seguente lista e seleziona quelli che vorresti spostare.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Nome del file',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Posizione attuale',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Sposta in...',
	'DBTools:LostAttachments:Step:RestoreResults' => 'Risultati del ripristino:',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d allegati sono stati ripristinati.',
	'DBTools:LostAttachments:StoredAsInlineImage' => 'Salvato come immagine in linea',
	'DBTools:LostAttachments:History' => 'Allegato "%1$s" ripristinato con gli strumenti DB',
));
