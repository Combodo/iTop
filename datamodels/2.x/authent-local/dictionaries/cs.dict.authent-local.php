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
Dict::Add('CS CZ', 'Czech', 'Čeština', array(
	'Class:UserLocal' => 'interní uživatel '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal+' => 'Uživatel ověřen interně v '.ITOP_APPLICATION_SHORT,
	'Class:UserLocal/Attribute:password' => 'Heslo',
	'Class:UserLocal/Attribute:password+' => '',
	'Class:UserLocal/Attribute:expiration' => 'Exspirace hesla',
	'Class:UserLocal/Attribute:expiration+' => 'Status exspirace hesla (je vyžadováno rozšíření, aby mělo efekt)',
	'Class:UserLocal/Attribute:expiration/Value:can_expire' => 'Může exspirovat',
	'Class:UserLocal/Attribute:expiration/Value:can_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:never_expire' => 'Nikdy neexspiruje',
	'Class:UserLocal/Attribute:expiration/Value:never_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:force_expire' => 'Exspirován',
	'Class:UserLocal/Attribute:expiration/Value:force_expire+' => '~~',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire' => 'Jednorázové heslo',
	'Class:UserLocal/Attribute:expiration/Value:otp_expire+' => 'Heslo nemůže uživatel změnit.',
	'Class:UserLocal/Attribute:password_renewed_date' => 'Heslo bylo obnoveno',
	'Class:UserLocal/Attribute:password_renewed_date+' => 'Termín, kdy bylo heslo změneno',
	'Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed' => 'Heslo musí obsahovat minimálně 8 znaků a musí obsahovat minimálně jedno velké písmeno, jedno malé písmeno, jedno číslo a speciální znak.',
	'UserLocal:password:expiration' => 'Níže uvedená pole vyžadují rozšíření',
	'Class:UserLocal/Error:OneTimePasswordChangeIsNotAllowed' => 'Nastavení exspirace "Jednorázového hesla" nelze u vlastního účtu uživatele.',
));
