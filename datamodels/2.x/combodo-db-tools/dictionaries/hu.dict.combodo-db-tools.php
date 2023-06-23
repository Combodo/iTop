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
// Database inconsistencies
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	// Dictionary entries go here
	'Menu:DBToolsMenu' => 'DB eszközök',
	'DBTools:Class' => 'Osztály',
	'DBTools:Title' => 'Adatbázis karbantartó eszközök',
	'DBTools:ErrorsFound' => 'Hibák vannak',
	'DBTools:Indication' => 'Fontos: az adatbázisban lévő hibák kijavítása után újra kell futtatni az elemzést, mivel új következetlenségek keletkeznek.',
	'DBTools:Disclaimer' => 'A JAVÍTÁSOK FUTTATÁSA ELŐTT MINDIG KÉSZÍTSEN BIZTONSÁGI MENTÉST AZ ADATBÁZISÁRÓL.',
	'DBTools:Error' => 'Hiba',
	'DBTools:Count' => 'Sorszám',
	'DBTools:SQLquery' => 'SQL lekérdezés',
	'DBTools:FixitSQLquery' => 'SQL lekérdezés To Fix it (indication)',
	'DBTools:SQLresult' => 'SQL eredmény',
	'DBTools:NoError' => 'Az adatbázis OK',
	'DBTools:HideIds' => 'Hibalista',
	'DBTools:ShowIds' => 'Részletes nézet',
	'DBTools:ShowReport' => 'Jelentés',
	'DBTools:IntegrityCheck' => 'Integritás ellenőrzés',
	'DBTools:FetchCheck' => 'Lehívás ellenőrzés (hosszú)',
	'DBTools:SelectAnalysisType' => 'Válasszon elemzés típust',
	'DBTools:Analyze' => 'Elemzés',
	'DBTools:Details' => 'Részletek mutatása',
	'DBTools:ShowAll' => 'Minden hiba mutatása',
	'DBTools:Inconsistencies' => 'Adatbázis inkonzisztenciák',
	'DBTools:DetailedErrorTitle' => '%2$s hiba a %1$s osztályban: %3$s',
	'DBTools:DetailedErrorLimit' => 'List limited to %1$s errors~~',
	'DBAnalyzer-Integrity-OrphanRecord' => 'Árva rekord a `%1$s` -ban, kell hogy legyen megfelelője a `%2$s` táblázatban',
	'DBAnalyzer-Integrity-InvalidExtKey' => 'Érvénytelen a %1$s  külső kulcs (oszlop: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-MissingExtKey' => 'Hiányzik a %1$s külső külcs (oszlop: `%2$s.%3$s`)',
	'DBAnalyzer-Integrity-InvalidValue' => '%1$s értéke érvénytelen (oszlop: `%2$s.%3$s`)~~',
	'DBAnalyzer-Integrity-UsersWithoutProfile' => 'Néhány felhasználónak egyáltalán nincs fiókja',
	'DBAnalyzer-Integrity-HKInvalid' => 'Sérült a `%1$s` hierarchikus kulcs',
	'DBAnalyzer-Fetch-Count-Error' => 'Lekérési hiba a `%1$s` -nál, %2$d bejegyzés lekérve / %3$d megszámlálva',
	'DBAnalyzer-Integrity-FinalClass' => 'A `%2$s`.`%1$s` mezőnek ugyanolyan értékűnek kell lennie mint a `%3$s`.`%1$s`',
	'DBAnalyzer-Integrity-RootFinalClass' => '`%2$s`.`%1$s` mezőnek érvényes osztályt kell tartalmaznia',
));

// Database Info
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'DBTools:DatabaseInfo' => 'Adatbázis információ',
	'DBTools:Base' => 'Bázis',
	'DBTools:Size' => 'Méret',
));

// Lost attachments
Dict::Add('HU HU', 'Hungarian', 'Magyar', array(
	'DBTools:LostAttachments' => 'Elveszett mellékletek',
	'DBTools:LostAttachments:Disclaimer' => 'Itt kereshet az adatbázisban elveszett vagy elkeveredett mellékletek után. Ez NEM egy adat-visszaállítási eszköz, nem állítja vissza a törölt adatokat.',
	'DBTools:LostAttachments:Button:Analyze' => 'Elemzés',
	'DBTools:LostAttachments:Button:Restore' => 'Visszaállítás',
	'DBTools:LostAttachments:Button:Restore:Confirm' => 'Ez a művelet nem vonható vissza, kérjük, erősítse meg, hogy vissza kívánja-e állítani a kiválasztott fájlokat.',
	'DBTools:LostAttachments:Button:Busy' => 'Kérem várjon...',
	'DBTools:LostAttachments:Step:Analyze' => 'Először az adatbázis elemzésével keresse meg az elveszett/áthelyezett mellékleteket.',
	'DBTools:LostAttachments:Step:AnalyzeResults' => 'Elemzés eredménye:',
	'DBTools:LostAttachments:Step:AnalyzeResults:None' => 'Nagyszerű! Úgy tűnik, minden a helyén van.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Some' => 'Úgy tűnik, hogy néhány melléklet (%1$d) rossz helyen van. Nézze meg az alábbi listát, és ellenőrizze azokat, amelyeket szeretne áthelyezni.',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:Filename' => 'Fájlnév',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:CurrentLocation' => 'Jelenlegi helye',
	'DBTools:LostAttachments:Step:AnalyzeResults:Item:TargetLocation' => 'Áthelyezés...',
	'DBTools:LostAttachments:Step:RestoreResults' => 'Visszaállítás eredménye:',
	'DBTools:LostAttachments:Step:RestoreResults:Results' => '%1$d/%2$d melléklet lett visszaállítva.',
	'DBTools:LostAttachments:StoredAsInlineImage' => 'Soron belüli képként tárolva',
	'DBTools:LostAttachments:History' => 'A "%1$s" melléklet visszaállítva a DB eszközzel'
));
