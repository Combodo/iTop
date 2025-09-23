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
Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:UserLDAP' => 'LDAP-Benutzer',
	'Class:UserLDAP+' => 'Benutzer, der via LDAP authentifiziert wird',
	'UserLDAP:server' => 'LDAP-Einstellungen',
));

//
// Class: UserLDAP
//

Dict::Add('DE DE', 'German', 'Deutsch', array(
	'Class:UserLDAP/Attribute:ldap_server' => 'LDAP-Server',
	'Class:UserLDAP/Attribute:ldap_server+' => 'Optional: LDAP-Server, der zur Authentifizierung verwendet werden soll, falls mehrere LDAP-Server konfiguriert sind.',
));
