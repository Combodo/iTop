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
Dict::Add('FR FR', 'French', 'Français', [
	'UI:Bulk:modify:IncompatibleAttribute' => 'Cet attribut ne peut être édité dans une modification en masse',
	'UI:Bulk:Export:MaliciousInjection:Alert:Title' => 'Avertissement sur la sécurité d\'Excel',
	'UI:Bulk:Export:MaliciousInjection:Alert:Message' => 'L\'ouverture d\'un fichier contenant des données non fiables dans Microsoft Excel peut entraîner l\'injection de formules. Assurez-vous que vos paramètres Excel sont configurés pour traiter les fichiers en toute sécurité. <a href="%1$s" target="_blank">Pour en savoir plus, consultez notre documentation.</a>',
	'UI:Bulk:Export:MaliciousInjection:Sanitization:Alert:Message' => 'Certaines valeurs ont été échappées pour prévenir de potentielles failles de sécurités dans Microsoft Excel. <a href="%1$s" target="_blank">Pour en savoir plus, consultez notre documentation</a>',
	'UI:Bulk:Export:MaliciousInjection:Input:Label' => 'Sanitiser les valeurs potentiellement dangereuses',
	'UI:Bulk:Export:MaliciousInjection:Input:Tooltip' => 'Lorsqu\'elle est activée, les valeurs potentiellement dangereuses seront sanitizées lors de l\'exportation. Cela empêchera Microsoft Excel de les interpréter comme des formules. Notez que cela peut altérer les données originales en les préfixant avec une simple quote (\') pour s\'assurer qu\'elles soient traitées comme du texte.',
	'Core:BulkExport:Security' => 'Sécurité',
]);
