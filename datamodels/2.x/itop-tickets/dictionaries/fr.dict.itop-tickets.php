<?php
// Copyright (C) 2010-2021 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
//
// Class: Ticket
//
Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Ticket' => 'Ticket',
	'Class:Ticket+' => '',
	'Class:Ticket/Attribute:ref' => 'Référence',
	'Class:Ticket/Attribute:ref+' => '',
	'Class:Ticket/Attribute:org_id' => 'Client',
	'Class:Ticket/Attribute:org_id+' => '',
	'Class:Ticket/Attribute:org_name' => 'Nom Client',
	'Class:Ticket/Attribute:org_name+' => '',
	'Class:Ticket/Attribute:caller_id' => 'Demandeur',
	'Class:Ticket/Attribute:caller_id+' => '',
	'Class:Ticket/Attribute:caller_name' => 'Nom Demandeur',
	'Class:Ticket/Attribute:caller_name+' => '',
	'Class:Ticket/Attribute:team_id' => 'Equipe',
	'Class:Ticket/Attribute:team_id+' => '',
	'Class:Ticket/Attribute:team_name' => 'Nom Equipe',
	'Class:Ticket/Attribute:team_name+' => '',
	'Class:Ticket/Attribute:agent_id' => 'Agent',
	'Class:Ticket/Attribute:agent_id+' => '',
	'Class:Ticket/Attribute:agent_name' => 'Nom Agent',
	'Class:Ticket/Attribute:agent_name+' => '',
	'Class:Ticket/Attribute:title' => 'Titre',
	'Class:Ticket/Attribute:title+' => '',
	'Class:Ticket/Attribute:description' => 'Description',
	'Class:Ticket/Attribute:description+' => '',
	'Class:Ticket/Attribute:start_date' => 'Date de début',
	'Class:Ticket/Attribute:start_date+' => '',
	'Class:Ticket/Attribute:end_date' => 'Date de fin',
	'Class:Ticket/Attribute:end_date+' => '',
	'Class:Ticket/Attribute:last_update' => 'Dernière mise à jour',
	'Class:Ticket/Attribute:last_update+' => '',
	'Class:Ticket/Attribute:close_date' => 'Date de fermeture',
	'Class:Ticket/Attribute:close_date+' => '',
	'Class:Ticket/Attribute:private_log' => 'Journal privé',
	'Class:Ticket/Attribute:private_log+' => '',
	'Class:Ticket/Attribute:contacts_list' => 'Contacts',
	'Class:Ticket/Attribute:contacts_list+' => '',
	'Class:Ticket/Attribute:functionalcis_list' => 'CIs',
	'Class:Ticket/Attribute:functionalcis_list+' => 'Tous les éléments de configuration impactés par ce ticket. Les éléments marqués comme "Calculés" sont le résultat du calcul de l\'analyse d\'impact. Les éléments marqués comme "Non impactés" sont exclus de cette analyse.',
	'Class:Ticket/Attribute:workorders_list' => 'Tâches',
	'Class:Ticket/Attribute:workorders_list+' => '',
	'Class:Ticket/Attribute:finalclass' => 'Sous-classe de Ticket',
	'Class:Ticket/Attribute:finalclass+' => 'Nom de la classe instanciable',
	'Class:Ticket/Attribute:operational_status' => 'Statut opérationnel',
	'Class:Ticket/Attribute:operational_status+' => 'Calculé à partir du statut détaillé',
	'Class:Ticket/Attribute:operational_status/Value:ongoing' => 'En cours',
	'Class:Ticket/Attribute:operational_status/Value:ongoing+' => 'Traitement en cours',
	'Class:Ticket/Attribute:operational_status/Value:resolved' => 'Résolu',
	'Class:Ticket/Attribute:operational_status/Value:resolved+' => '',
	'Class:Ticket/Attribute:operational_status/Value:closed' => 'Clos',
	'Class:Ticket/Attribute:operational_status/Value:closed+' => 'Fermé',
	'Ticket:ImpactAnalysis' => 'Analyse d\'Impact',
));


//
// Class: lnkContactToTicket
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:lnkContactToTicket' => 'Lien Contact / Ticket',
	'Class:lnkContactToTicket+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_id' => 'Ticket',
	'Class:lnkContactToTicket/Attribute:ticket_id+' => '',
	'Class:lnkContactToTicket/Attribute:ticket_ref' => 'Référence',
	'Class:lnkContactToTicket/Attribute:ticket_ref+' => '',
	'Class:lnkContactToTicket/Attribute:contact_id' => 'Contact',
	'Class:lnkContactToTicket/Attribute:contact_id+' => '',
	'Class:lnkContactToTicket/Attribute:contact_email' => 'Email Contact',
	'Class:lnkContactToTicket/Attribute:contact_email+' => '',
	'Class:lnkContactToTicket/Attribute:role' => 'Rôle (texte)',
	'Class:lnkContactToTicket/Attribute:role+' => '',
	'Class:lnkContactToTicket/Attribute:role_code' => 'Rôle',
	'Class:lnkContactToTicket/Attribute:role_code/Value:manual' => 'Ajouté manuellement',
	'Class:lnkContactToTicket/Attribute:role_code/Value:computed' => 'Calculé',
	'Class:lnkContactToTicket/Attribute:role_code/Value:do_not_notify' => 'Ne pas notifier',
));

//
// Class: WorkOrder
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:WorkOrder' => 'Tâche',
	'Class:WorkOrder+' => '',
	'Class:WorkOrder/Attribute:name' => 'Nom',
	'Class:WorkOrder/Attribute:name+' => '',
	'Class:WorkOrder/Attribute:status' => 'Statut',
	'Class:WorkOrder/Attribute:status+' => '',
	'Class:WorkOrder/Attribute:status/Value:open' => 'ouverte',
	'Class:WorkOrder/Attribute:status/Value:open+' => '',
	'Class:WorkOrder/Attribute:status/Value:closed' => 'fermée',
	'Class:WorkOrder/Attribute:status/Value:closed+' => '',
	'Class:WorkOrder/Attribute:description' => 'Description',
	'Class:WorkOrder/Attribute:description+' => '',
	'Class:WorkOrder/Attribute:ticket_id' => 'Ticket',
	'Class:WorkOrder/Attribute:ticket_id+' => '',
	'Class:WorkOrder/Attribute:ticket_ref' => 'Référence ticket',
	'Class:WorkOrder/Attribute:ticket_ref+' => '',
	'Class:WorkOrder/Attribute:team_id' => 'Equipe',
	'Class:WorkOrder/Attribute:team_id+' => '',
	'Class:WorkOrder/Attribute:team_name' => 'Nom Equipe',
	'Class:WorkOrder/Attribute:team_name+' => '',
	'Class:WorkOrder/Attribute:agent_id' => 'Agent',
	'Class:WorkOrder/Attribute:agent_id+' => '',
	'Class:WorkOrder/Attribute:agent_email' => 'Email Agent',
	'Class:WorkOrder/Attribute:agent_email+' => '',
	'Class:WorkOrder/Attribute:start_date' => 'Date de début',
	'Class:WorkOrder/Attribute:start_date+' => '',
	'Class:WorkOrder/Attribute:end_date' => 'Date de fin',
	'Class:WorkOrder/Attribute:end_date+' => '',
	'Class:WorkOrder/Attribute:log' => 'Journal',
	'Class:WorkOrder/Attribute:log+' => '',
	'Class:WorkOrder/Stimulus:ev_close' => 'Fermer',
	'Class:WorkOrder/Stimulus:ev_close+' => '',
));


// Fieldset translation
Dict::Add('FR FR', 'French', 'Français', array(
	'Ticket:baseinfo'                                                => 'Informations générales',
	'Ticket:date'                                                    => 'Dates',
	'Ticket:contact'                                                 => 'Contacts',
	'Ticket:moreinfo'                                               => 'Informations complémentaires',
	'Ticket:relation'                                               => 'Relations',
	'Ticket:log'                                                    => 'Communications',
	'Ticket:Type'                                                   => 'Qualification',
	'Ticket:support'                                                => 'Support',
	'Ticket:resolution'                                             => 'Résolution',
	'Ticket:SLA'                                                    => 'Rapport SLA',
	'WorkOrder:Details'                                             => 'Détails',
	'WorkOrder:Moreinfo'                                            => 'Informations complémentaires',
	'Tickets:ResolvedFrom'                                          => 'Résolu via %1$s',
	'Class:cmdbAbstractObject/Method:Set'                           => 'Set (remplir)',
	'Class:cmdbAbstractObject/Method:Set+'                          => 'Remplir un champ avec une valeur statique',
	'Class:cmdbAbstractObject/Method:Set/Param:1'                   => 'Champ Cible',
	'Class:cmdbAbstractObject/Method:Set/Param:1+'                  => 'Le champ à initialiser, dans l\'objet courant',
	'Class:cmdbAbstractObject/Method:Set/Param:2'                   => 'Valeur',
	'Class:cmdbAbstractObject/Method:Set/Param:2+'                  => 'La valeur statique',
	'Class:cmdbAbstractObject/Method:SetCurrentDate'                => 'SetCurrentDate (reinitialiser à la date courante)',
	'Class:cmdbAbstractObject/Method:SetCurrentDate+'               => 'Initialiser un champ avec la date et l\'heure courantes',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1'        => 'Champ Cible',
	'Class:cmdbAbstractObject/Method:SetCurrentDate/Param:1+'       => 'Le champ à initialiser, dans l\'objet courant',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull'          => 'SetCurrentDateIfNull (initialiser à la date courante)',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull+'         => 'Initialiser un champ seulement s\'il est vide, avec la date et l\'heure courantes',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull/Param:1'  => 'Champ Cible',
	'Class:cmdbAbstractObject/Method:SetCurrentDateIfNull/Param:1+' => 'Le champ à initialiser, dans l\'objet courant',
	'Class:cmdbAbstractObject/Method:SetCurrentUser'                => 'SetCurrentUser (reinitialiser à l\'utilisateur courant)',
	'Class:cmdbAbstractObject/Method:SetCurrentUser+'               => 'Initialiser un champ avec l\'utilisateur qui est en train d\'effectuer une action sur l\'objet',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1'        => 'Champ Cible',
	'Class:cmdbAbstractObject/Method:SetCurrentUser/Param:1+'       => 'Le champ à initialiser, dans l\'objet courant. Si ce champ est une chaîne de caractère, alors le nom usuel sera utilisé. Dans les autres cas, ce sera l\'identifiant de l\'objet. Le nom usuel est le nom usuel de la personne attachée au compte utilisateur. Si aucune personne n\'est rattachée au compte utilisateur, alors le nom usuel est l\'identifiant de connexion.',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson'              => 'SetCurrentPerson (initialiser à l\'utilisateur courant)',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson+'             => 'Initialiser un champ avec la personne associée au compte de l\'utilisateur qui est en train d\'effectuer une action sur l\'objet',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1'      => 'Champ Cible',
	'Class:cmdbAbstractObject/Method:SetCurrentPerson/Param:1+'     => 'Le champ à initialiser, dans l\'objet courant. Si ce champ est une chaîne de caractère, alors le nom usuel sera utilisé. Dans les autres cas, ce sera l\'identifiant de l\'objet',
	'Class:cmdbAbstractObject/Method:SetElapsedTime'                => 'SetElapsedTime (initialiser avec le temps passé)',
	'Class:cmdbAbstractObject/Method:SetElapsedTime+'               => 'Initialiser un champ avec la durée écoulée depuis une date donnée par un autre champ (champ de référence)',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1'        => 'Champ Cible',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:1+'       => 'Le champ à initialiser, dans l\'objet courant',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2'        => 'Champ de Référence',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:2+'       => 'Le champ contenant la date de début',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3'        => 'Jours et Heures Ouvrés',
	'Class:cmdbAbstractObject/Method:SetElapsedTime/Param:3+'       => 'Laisser ce champ vide pour bénéficier de la gestion des fenêtres de couverture, ou saisir "DefaultWorkingTimeComputer" pour passer en mode 24h/24 7j/7',
	'Class:cmdbAbstractObject/Method:SetIfNull'                     => 'SetIfNull (initialiser)',
	'Class:cmdbAbstractObject/Method:SetIfNull+'                    => 'Remplir seulement s\'il est vide, un champ avec une valeur statique',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:1'             => 'Champ Cible',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:1+'            => 'Le champ à initialiser, dans l\'objet courant',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:2'              => 'Valeur',
	'Class:cmdbAbstractObject/Method:SetIfNull/Param:2+'             => 'La valeur à mettre dans le champ',
	'Class:cmdbAbstractObject/Method:AddValue'                       => 'AddValue (ajouter une valeur)',
	'Class:cmdbAbstractObject/Method:AddValue+'                      => 'Ajouter une valeur à un champ',
	'Class:cmdbAbstractObject/Method:AddValue/Param:1'               => 'Champ Cible',
	'Class:cmdbAbstractObject/Method:AddValue/Param:1+'              => 'Le champ à modifier, dans l\'objet courant',
	'Class:cmdbAbstractObject/Method:AddValue/Param:2'               => 'Valeur',
	'Class:cmdbAbstractObject/Method:AddValue/Param:2+'              => 'Valeur décimal qui sera ajoutée. Cette valeur peut être négative',
	'Class:cmdbAbstractObject/Method:SetComputedDate'                => 'SetComputedDate (remplir avec une date calculée)',
	'Class:cmdbAbstractObject/Method:SetComputedDate+'               => 'Remplir un champ avec une date relative à celle d\'un autre champ',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:1'        => 'Champ Cible',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:1+'       => 'Le champ à initialiser, dans l\'objet courant',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:2'        => 'Modificateur',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:2+'       => 'Texte en anglais spécifiant la modification à appliquer sur le champ source, ex. "+3 days"',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:3'        => 'Champ source',
	'Class:cmdbAbstractObject/Method:SetComputedDate/Param:3+'       => 'Champ utilisé comme base pour y appliquer le Modificateur',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull'          => 'SetComputedDateIfNull (initialiser avec une date calculée)',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull+'         => 'Remplir un champ vide avec une date relative à celle d\'un autre champ',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:1'  => 'Champ Cible',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:1+' => 'Le champ à initialiser, dans l\'objet courant',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:2'  => 'Modificateur',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:2+' => 'Texte en anglais spécifiant la modification à appliquer sur le champ source, ex. "monday of next week"',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:3'  => 'Champ source',
	'Class:cmdbAbstractObject/Method:SetComputedDateIfNull/Param:3+' => 'Champ utilisé comme base pour y appliquer le Modificateur',
	'Class:cmdbAbstractObject/Method:Reset'                          => 'Réinitialiser',
	'Class:cmdbAbstractObject/Method:Reset+'                         => 'Réinitialiser un champ à sa valeur par défaut',
	'Class:cmdbAbstractObject/Method:Reset/Param:1'                  => 'Champ Cible',
	'Class:cmdbAbstractObject/Method:Reset/Param:1+'                 => 'Le champ à réinitialiser, dans l\'objet courant',
	'Class:cmdbAbstractObject/Method:Copy'                           => 'Copy (copier)',
	'Class:cmdbAbstractObject/Method:Copy+'                          => 'Copier la valeur d\'un champ dans un autre',
	'Class:cmdbAbstractObject/Method:Copy/Param:1'                   => 'Champ Cible',
	'Class:cmdbAbstractObject/Method:Copy/Param:1+'                  => 'Le champ à initialiser, dans l\'objet courant',
	'Class:cmdbAbstractObject/Method:Copy/Param:2'                   => 'Champ Source',
	'Class:cmdbAbstractObject/Method:Copy/Param:2+'                  => 'Le champ dans lequel on va lire la valeur, dans l\'objet courant',
	'Class:cmdbAbstractObject/Method:ApplyStimulus'                  => 'ApplyStimulus (appliquer un stimulus)',
	'Class:cmdbAbstractObject/Method:ApplyStimulus+'                 => 'Applique le stimulus spécifié à l\'objet courant',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1'          => 'Code du stimulus',
	'Class:cmdbAbstractObject/Method:ApplyStimulus/Param:1+'         => 'Un code de stimulus valide pour la classe courante',
	'Class:ResponseTicketTTO/Interface:iMetricComputer'              => 'Temps d\'Assignation (TTO)',
	'Class:ResponseTicketTTO/Interface:iMetricComputer+'             => 'Objectif calculé à partir d\'un SLT de type TTO',
	'Class:ResponseTicketTTR/Interface:iMetricComputer'              => 'Temps de Résolution (TTR)',
	'Class:ResponseTicketTTR/Interface:iMetricComputer+'             => 'Objectif calculé à partir d\'un SLT de type TTR',
));

//
// Class: Document
//

Dict::Add('FR FR', 'French', 'Français', array(
	'Class:Document/Attribute:contracts_list' => 'Contrats',
	'Class:Document/Attribute:contracts_list+' => '',
	'Class:Document/Attribute:services_list' => 'Services',
	'Class:Document/Attribute:services_list+' => '',
));