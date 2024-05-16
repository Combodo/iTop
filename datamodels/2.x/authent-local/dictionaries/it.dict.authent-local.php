<?php
// Copyright (C) 2010-2024 Combodo SAS
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
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
// Dictionnay conventions
// Class:<class_name>
// Class:<class_name>+
// Class:<class_name>/Attribute:<attribute_code>
// Class:<class_name>/Attribute:<attribute_code>+
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>
// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+
// Class:<class_name>/Stimulus:<stimulus_code>
// Class:<class_name>/Stimulus:<stimulus_code>+
//
// Class: UserLocal
//
Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:UserLocal' => 'Utente '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal+' => 'Utente autenticato da '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal/Attribute:password' => 'Password',
	'Class:UserLocal/Attribute:password+' => 'stringa di autenticazione utente',
	'Class:UserLocal/Attribute:expiration' => 'Scadenza della password',
	'Class:UserLocal/Attribute:expiration+' => 'Stato della scadenza della password (richiede un\'estensione per avere effetto)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Può scadere',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Non scade',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Scaduta',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire' => 'Password monouso',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire+' => 'La password non può essere cambiata dall\'utente.',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Rinnovo della password',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Quando è stata cambiata l\'ultima volta la password',
	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'La password deve essere di almeno 8 caratteri e includere lettere maiuscole, minuscole, numeri e caratteri speciali.',
	'UserLocal:password:expiration' => 'I campi sottostanti richiedono un\'estensione',
	'Class:UserLocal/Error:OneTimePasswordChangeIsNotAllowed' => 'Impostare la scadenza della password su "Password monouso" non è consentito per il proprio utente',
));
