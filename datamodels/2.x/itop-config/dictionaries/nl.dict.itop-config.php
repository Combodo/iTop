<?php
/**
 * Localized data
 *
 * @copyright Copyright (C) 2010-2024 Combodo SAS
 * @license    https://opensource.org/licenses/AGPL-3.0
 * 
 */

/**
 * @author Jeffrey Bostoen <info@jeffreybostoen.be> (2018 - 2022)
 * @author Thomas Casteleyn <thomas.casteleyn@super-visions.com>
 */
Dict::Add('NL NL', 'Dutch', 'Nederlands', [
	'Menu:ConfigEditor' => 'Configuratie',
	'config-apply' => 'Opslaan',
	'config-apply-title' => 'Opslaan (Ctrl+S)',
	'config-cancel' => 'Herbegin',
	'config-confirm-cancel' => 'Je wijzigingen zullen verloren gaan.',
	'config-current-line' => 'Huidige regel: %1$s',
	'config-edit-intro' => 'Wees uiterst voorzichtig bij het aanpassen van de configuratie.',
	'config-edit-title' => 'Aanpassen configuratie',
	'config-error-file-changed' => 'Fout: Het configuratiebestand is gewijzigd sinds je het geopend hebt en kan niet opgeslagen worden. Herlaad en pas je wijzigingen opnieuw toe.',
	'config-error-transaction' => 'Fout: ongeldige Transactie ID. De configuratie is <b>NIET</b> aangepast.',
	'config-interactive-not-allowed' => 'Interactieve aanpassing van de '.ITOP_APPLICATION_SHORT.' configuratie is uitgeschackeld. Zie <code>\'config_editor\' => \'disabled\'</code> in het configuratiebestand.',
	'config-no-change' => 'Er zijn geen wijzigingen vastgesteld.',
	'config-not-allowed-in-demo' => 'Sorry, '.ITOP_APPLICATION_SHORT.' is in <b>demo mode</b>: Het configuratiebestand kan niet aangepast worden.',
	'config-parse-error' => 'Regel %2$d: %1$s.<br/>Het bestand werd <b>NIET</b> opgeslagen.',
	'config-reverted' => 'De wijzigingen zijn ongedaan gemaakt.',
	'config-saved' => 'Wijzigingen zijn opgeslagen.',
	'config-saved-warning-db-password' => 'Wijzigingen zijn opgeslagen, maar de backup zal niet werken doordat het databasewachtwoord karakters bevat die niet ondersteund zijn.',
]);
