<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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
 */

// Navigation menu
Dict::Add('FR FR', 'French', 'Français', array(
	'UI:Preferences:Title' => 'Préférences',
	'UI:Preferences:UserInterface:Title' => 'Interface utilisateur',
	'UI:Preferences:Lists:Title' => 'Listes',
	'UI:Preferences:RichText:Title' => 'Éditeur texte riche',
	'UI:Preferences:RichText:ToolbarState' => 'Toolbar default state~~',
	'UI:Preferences:RichText:ToolbarState:Expanded' => 'Déplié',
	'UI:Preferences:RichText:ToolbarState:Collapsed' => 'Replié',
	'UI:Preferences:ActivityPanel:Title' => 'Activity panel~~',
	'UI:Preferences:ActivityPanel:EntryFormOpened' => 'Formulaire de saisie ouvert par défaut',
	'UI:Preferences:ActivityPanel:EntryFormOpened+' => 'Whether the entry form will be opened when displaying an object. If unchecked, you will still be able to open it by clicking the compose button~~',
	// 'UI:Preferences:ActivityPanel:EntryFormOpened+' => 'État du formulaire de saisie lors de l\'affichage d\'un objet. Si la case est décochée, le formulaire pourra être ouvert en utilisant l\'icone "Nouvelle entrée".', // pas de label sur ce bouton mais un tooltip, défini dans UI:Layout:ActivityPanel:ComposeButton:Tooltip (dictionaries/ui/layouts/fr.dictionary.itop.activity-panel.php)
	'UI:Preferences:PersonalizeKeyboardShortcuts:Title' => 'Raccourci clavier de l\'application',
	'UI:Preferences:PersonalizeKeyboardShortcuts:Input:Hint' => 'Saisissez un raccourci clavier',
	'UI:Preferences:PersonalizeKeyboardShortcuts:Button:Tooltip' => 'Enregistrer un raccourcir clavier',
	'UI:Preferences:Tabs:Title' => 'Onglets',
	'UI:Preferences:Tabs:Layout:Label' => 'Layout~~',
	'UI:Preferences:Tabs:Layout:Horizontal' => 'Horizontal~~',
	'UI:Preferences:Tabs:Layout:Vertical' => 'Vertical~~',
	'UI:Preferences:Tabs:Scrollable:Label' => 'Navigation~~',
	'UI:Preferences:Tabs:Scrollable:Classic' => 'Classic~~',
	'UI:Preferences:Tabs:Scrollable:Scrollable' => 'Scrollable~~',
	'UI:Preferences:ChooseAPlaceholder' => 'User placeholder image~~',
	'UI:Preferences:ChooseAPlaceholder+' => 'Choose a placeholder image that will be displayed if the contact linked to your user doesn\'t have one~~',
));
