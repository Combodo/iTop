<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * @author	LinProfs <info@linprofs.com>
 * 
 * Linux & Open Source Professionals
 * http://www.linprofs.com
 *
 * @author jbostoen (2018)
 *
 * @copyright   Copyright (C) 2010-2018 Combodo SARL
 * @licence	http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Menu:RequestManagement' => 'Helpdesk',
	'Menu:RequestManagement+' => 'Helpdesk',
	'Menu:RequestManagementProvider' => 'Helpdesk leverancier',
	'Menu:RequestManagementProvider+' => 'Helpdesk leverancier',
	'Menu:UserRequest:Provider' => 'Open verzoeken overgemaakt aan de leverancier',
	'Menu:UserRequest:Provider+' => 'Open verzoeken overgemaakt aan de leverancier',
	'Menu:UserRequest:Overview' => 'Overzicht',
	'Menu:UserRequest:Overview+' => 'Overzicht',
	'Menu:NewUserRequest' => 'Nieuw gebruikersverzoek',
	'Menu:NewUserRequest+' => 'Maak een nieuw gebruikersverzoek aan',
	'Menu:SearchUserRequests' => 'Zoek naar gebruikersverzoeken',
	'Menu:SearchUserRequests+' => 'Zoek naar gebruikersverzoeken',
	'Menu:UserRequest:Shortcuts' => 'Snelkoppelingen',
	'Menu:UserRequest:Shortcuts+' => '',
	'Menu:UserRequest:MyRequests' => 'Verzoeken toegewezen aan mij',
	'Menu:UserRequest:MyRequests+' => 'Verzoeken toegewezen aan mij (als agent)',
	'Menu:UserRequest:MySupportRequests' => 'Mijn supportverzoeken',
	'Menu:UserRequest:MySupportRequests+' => 'Mijn supportverzoeken',
	'Menu:UserRequest:EscalatedRequests' => 'Geëscaleerde verzoeken',
	'Menu:UserRequest:EscalatedRequests+' => 'Geëscaleerde verzoeken',
	'Menu:UserRequest:OpenRequests' => 'Alle open verzoeken',
	'Menu:UserRequest:OpenRequests+' => 'Alle open verzoeken',
	'UI:WelcomeMenu:MyAssignedCalls' => 'Verzoeken toegewezen aan mij',
	'UI-RequestManagementOverview-RequestByType-last-14-days' => 'Verzoeken van de afgelopen 14 dagen (per type)',
	'UI-RequestManagementOverview-Last-14-days' => 'Verzoeken van de afgelopen 14 dagen (per dag)',
	'UI-RequestManagementOverview-OpenRequestByStatus' => 'Open verzoeken per status',
	'UI-RequestManagementOverview-OpenRequestByAgent' => 'Open verzoeken per medewerker',
	'UI-RequestManagementOverview-OpenRequestByType' => 'Open verzoeken per type',
	'UI-RequestManagementOverview-OpenRequestByCustomer' => 'Open verzoeken per organisatie',
	'Class:UserRequest:KnownErrorList' => 'Gekende fouten',
	'Menu:UserRequest:MyWorkOrders' => 'Werkopdrachten toegewezen aan mij',
	'Menu:UserRequest:MyWorkOrders+' => 'Alle werkopdrachten toegewezen aan mij',
	'Class:Problem:KnownProblemList' => 'Gekende fouten',
	'Tickets:Related:OpenIncidents' => 'Open incidenten',
));

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
// Class: UserRequest
//

Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Class:UserRequest' => 'Gebruikersverzoek',
	'Class:UserRequest+' => '',
	'Class:UserRequest/Attribute:status' => 'Status',
	'Class:UserRequest/Attribute:status+' => '',
	'Class:UserRequest/Attribute:status/Value:new' => 'Nieuw',
	'Class:UserRequest/Attribute:status/Value:new+' => '',
	'Class:UserRequest/Attribute:status/Value:escalated_tto' => 'Geëscaleerde TTO',
	'Class:UserRequest/Attribute:status/Value:escalated_tto+' => '',
	'Class:UserRequest/Attribute:status/Value:assigned' => 'Toegewezen',
	'Class:UserRequest/Attribute:status/Value:assigned+' => '',
	'Class:UserRequest/Attribute:status/Value:escalated_ttr' => 'Geëscaleerde TTR',
	'Class:UserRequest/Attribute:status/Value:escalated_ttr+' => '',
	'Class:UserRequest/Attribute:status/Value:waiting_for_approval' => 'Wacht op goedkeuring',
	'Class:UserRequest/Attribute:status/Value:waiting_for_approval+' => '',
	'Class:UserRequest/Attribute:status/Value:approved' => 'Goedgekeurd',
	'Class:UserRequest/Attribute:status/Value:approved+' => '',
	'Class:UserRequest/Attribute:status/Value:rejected' => 'Afgewezen',
	'Class:UserRequest/Attribute:status/Value:rejected+' => '',
	'Class:UserRequest/Attribute:status/Value:pending' => 'In afwachting van',
	'Class:UserRequest/Attribute:status/Value:pending+' => '',
	'Class:UserRequest/Attribute:status/Value:resolved' => 'Opgelost',
	'Class:UserRequest/Attribute:status/Value:resolved+' => '',
	'Class:UserRequest/Attribute:status/Value:closed' => 'Gesloten',
	'Class:UserRequest/Attribute:status/Value:closed+' => '',
	'Class:UserRequest/Attribute:request_type' => 'Soort verzoek',
	'Class:UserRequest/Attribute:request_type+' => '',
	'Class:UserRequest/Attribute:request_type/Value:incident' => 'Incident',
	'Class:UserRequest/Attribute:request_type/Value:incident+' => 'Incident',
	'Class:UserRequest/Attribute:request_type/Value:service_request' => 'Serviceverzoek',
	'Class:UserRequest/Attribute:request_type/Value:service_request+' => 'Serviceverzoek',
	'Class:UserRequest/Attribute:impact' => 'Impact',
	'Class:UserRequest/Attribute:impact+' => '',
	'Class:UserRequest/Attribute:impact/Value:1' => 'Afdeling',
	'Class:UserRequest/Attribute:impact/Value:1+' => '',
	'Class:UserRequest/Attribute:impact/Value:2' => 'Dienst',
	'Class:UserRequest/Attribute:impact/Value:2+' => '',
	'Class:UserRequest/Attribute:impact/Value:3' => 'Persoon',
	'Class:UserRequest/Attribute:impact/Value:3+' => '',
	'Class:UserRequest/Attribute:priority' => 'Prioriteit',
	'Class:UserRequest/Attribute:priority+' => '',
	'Class:UserRequest/Attribute:priority/Value:1' => 'Kritisch',
	'Class:UserRequest/Attribute:priority/Value:1+' => 'Kritisch',
	'Class:UserRequest/Attribute:priority/Value:2' => 'Hoog',
	'Class:UserRequest/Attribute:priority/Value:2+' => 'Hoog',
	'Class:UserRequest/Attribute:priority/Value:3' => 'Normaal',
	'Class:UserRequest/Attribute:priority/Value:3+' => 'Normaal',
	'Class:UserRequest/Attribute:priority/Value:4' => 'Laag',
	'Class:UserRequest/Attribute:priority/Value:4+' => 'Laag',
	'Class:UserRequest/Attribute:urgency' => 'Urgentie',
	'Class:UserRequest/Attribute:urgency+' => '',
	'Class:UserRequest/Attribute:urgency/Value:1' => 'Kritisch',
	'Class:UserRequest/Attribute:urgency/Value:1+' => 'Kritisch',
	'Class:UserRequest/Attribute:urgency/Value:2' => 'Hoog',
	'Class:UserRequest/Attribute:urgency/Value:2+' => 'Hoog',
	'Class:UserRequest/Attribute:urgency/Value:3' => 'Normaal',
	'Class:UserRequest/Attribute:urgency/Value:3+' => 'Normaal',
	'Class:UserRequest/Attribute:urgency/Value:4' => 'Laag',
	'Class:UserRequest/Attribute:urgency/Value:4+' => 'Laag',
	'Class:UserRequest/Attribute:origin' => 'Oorsprong',
	'Class:UserRequest/Attribute:origin+' => '',
	'Class:UserRequest/Attribute:origin/Value:mail' => 'E-mail',
	'Class:UserRequest/Attribute:origin/Value:mail+' => 'E-mail',
	'Class:UserRequest/Attribute:origin/Value:monitoring' => 'Monitoring',
	'Class:UserRequest/Attribute:origin/Value:monitoring+' => 'Monitoring',
	'Class:UserRequest/Attribute:origin/Value:phone' => 'Telefoon',
	'Class:UserRequest/Attribute:origin/Value:phone+' => 'Telefoon',
	'Class:UserRequest/Attribute:origin/Value:portal' => 'Portaal',
	'Class:UserRequest/Attribute:origin/Value:portal+' => 'Portaal',
	'Class:UserRequest/Attribute:approver_id' => 'Goedkeurder',
	'Class:UserRequest/Attribute:approver_id+' => '',
	'Class:UserRequest/Attribute:approver_email' => 'E-mailadres Goedkeurder',
	'Class:UserRequest/Attribute:approver_email+' => '',
	'Class:UserRequest/Attribute:service_id' => 'Service',
	'Class:UserRequest/Attribute:service_id+' => '',
	'Class:UserRequest/Attribute:service_name' => 'Naam service',
	'Class:UserRequest/Attribute:service_name+' => '',
	'Class:UserRequest/Attribute:servicesubcategory_id' => 'Subcategorie service',
	'Class:UserRequest/Attribute:servicesubcategory_id+' => '',
	'Class:UserRequest/Attribute:servicesubcategory_name' => 'Naam subcategorie service',
	'Class:UserRequest/Attribute:servicesubcategory_name+' => '',
	'Class:UserRequest/Attribute:escalation_flag' => 'Geëscaleerd',
	'Class:UserRequest/Attribute:escalation_flag+' => '',
	'Class:UserRequest/Attribute:escalation_flag/Value:no' => 'Nee',
	'Class:UserRequest/Attribute:escalation_flag/Value:no+' => 'Nee',
	'Class:UserRequest/Attribute:escalation_flag/Value:yes' => 'Ja',
	'Class:UserRequest/Attribute:escalation_flag/Value:yes+' => 'Ja',
	'Class:UserRequest/Attribute:escalation_reason' => 'Reden escalatie',
	'Class:UserRequest/Attribute:escalation_reason+' => '',
	'Class:UserRequest/Attribute:assignment_date' => 'Toegewezen op',
	'Class:UserRequest/Attribute:assignment_date+' => '',
	'Class:UserRequest/Attribute:resolution_date' => 'Opgelost sinds',
	'Class:UserRequest/Attribute:resolution_date+' => '',
	'Class:UserRequest/Attribute:last_pending_date' => 'Laatst in afwachting op',
	'Class:UserRequest/Attribute:last_pending_date+' => '',
	'Class:UserRequest/Attribute:cumulatedpending' => 'cumulatedpending',
	'Class:UserRequest/Attribute:cumulatedpending+' => '',
	'Class:UserRequest/Attribute:tto' => 'TTO',
	'Class:UserRequest/Attribute:tto+' => '',
	'Class:UserRequest/Attribute:ttr' => 'TTR',
	'Class:UserRequest/Attribute:ttr+' => '',
	'Class:UserRequest/Attribute:tto_escalation_deadline' => 'TTO-deadline',
	'Class:UserRequest/Attribute:tto_escalation_deadline+' => '',
	'Class:UserRequest/Attribute:sla_tto_passed' => 'SLA TTO gepasseerd',
	'Class:UserRequest/Attribute:sla_tto_passed+' => '',
	'Class:UserRequest/Attribute:sla_tto_over' => 'SLA TTO over',
	'Class:UserRequest/Attribute:sla_tto_over+' => '',
	'Class:UserRequest/Attribute:ttr_escalation_deadline' => 'TTR-deadline',
	'Class:UserRequest/Attribute:ttr_escalation_deadline+' => '',
	'Class:UserRequest/Attribute:sla_ttr_passed' => 'SLA TTR gepasseerd',
	'Class:UserRequest/Attribute:sla_ttr_passed+' => '',
	'Class:UserRequest/Attribute:sla_ttr_over' => 'SLA TTR over',
	'Class:UserRequest/Attribute:sla_ttr_over+' => '',
	'Class:UserRequest/Attribute:time_spent' => 'Gespendeerde tijd',
	'Class:UserRequest/Attribute:time_spent+' => '',
	'Class:UserRequest/Attribute:resolution_code' => 'Oplossingscode',
	'Class:UserRequest/Attribute:resolution_code+' => '',
	'Class:UserRequest/Attribute:resolution_code/Value:assistance' => 'Assistentie',
	'Class:UserRequest/Attribute:resolution_code/Value:assistance+' => 'Assistentie',
	'Class:UserRequest/Attribute:resolution_code/Value:bug fixed' => 'Bug opgelost',
	'Class:UserRequest/Attribute:resolution_code/Value:bug fixed+' => 'Bug opgelost',
	'Class:UserRequest/Attribute:resolution_code/Value:hardware repair' => 'Hardware gerepareerd',
	'Class:UserRequest/Attribute:resolution_code/Value:hardware repair+' => 'Hardware gerepareerd',
	'Class:UserRequest/Attribute:resolution_code/Value:other' => 'Anders',
	'Class:UserRequest/Attribute:resolution_code/Value:other+' => 'Anders',
	'Class:UserRequest/Attribute:resolution_code/Value:software patch' => 'Softwarepatch',
	'Class:UserRequest/Attribute:resolution_code/Value:software patch+' => 'Softwarepatch',
	'Class:UserRequest/Attribute:resolution_code/Value:system update' => 'Systeemupdate',
	'Class:UserRequest/Attribute:resolution_code/Value:system update+' => 'Systeemupdate',
	'Class:UserRequest/Attribute:resolution_code/Value:training' => 'Training',
	'Class:UserRequest/Attribute:resolution_code/Value:training+' => 'Training',
	'Class:UserRequest/Attribute:solution' => 'Oplossing',
	'Class:UserRequest/Attribute:solution+' => '',
	'Class:UserRequest/Attribute:pending_reason' => 'Reden waardoor het in afwachting staat',
	'Class:UserRequest/Attribute:pending_reason+' => '',
	'Class:UserRequest/Attribute:parent_request_id' => 'Hoofdaanvraag',
	'Class:UserRequest/Attribute:parent_request_id+' => '',
	'Class:UserRequest/Attribute:parent_request_ref' => 'Ref. verzoek',
	'Class:UserRequest/Attribute:parent_request_ref+' => '',
	'Class:UserRequest/Attribute:parent_problem_id' => 'Hoofdprobleem',
	'Class:UserRequest/Attribute:parent_problem_id+' => '',
	'Class:UserRequest/Attribute:parent_problem_ref' => 'Ref. probleem',
	'Class:UserRequest/Attribute:parent_problem_ref+' => '',
	'Class:UserRequest/Attribute:parent_change_id' => 'Hoofdchange',
	'Class:UserRequest/Attribute:parent_change_id+' => '',
	'Class:UserRequest/Attribute:parent_change_ref' => 'Ref. change',
	'Class:UserRequest/Attribute:parent_change_ref+' => '',
	'Class:UserRequest/Attribute:related_request_list' => 'Subverzoeken',
	'Class:UserRequest/Attribute:related_request_list+' => 'Alle verzoeken die gerelateerd zijn aan dit hoofdverzoek',
	'Class:UserRequest/Attribute:public_log' => 'Publieke log',
	'Class:UserRequest/Attribute:public_log+' => '',
	'Class:UserRequest/Attribute:user_satisfaction' => 'Klant tevredenheid',
	'Class:UserRequest/Attribute:user_satisfaction+' => '',
	'Class:UserRequest/Attribute:user_satisfaction/Value:1' => 'Erg tevreden',
	'Class:UserRequest/Attribute:user_satisfaction/Value:1+' => 'Erg tevreden',
	'Class:UserRequest/Attribute:user_satisfaction/Value:2' => 'Tevreden',
	'Class:UserRequest/Attribute:user_satisfaction/Value:2+' => 'Tevreden',
	'Class:UserRequest/Attribute:user_satisfaction/Value:3' => 'Ontevreden',
	'Class:UserRequest/Attribute:user_satisfaction/Value:3+' => 'Ontevreden',
	'Class:UserRequest/Attribute:user_satisfaction/Value:4' => 'Erg ontevreden',
	'Class:UserRequest/Attribute:user_satisfaction/Value:4+' => 'Erg ontevreden',
	'Class:UserRequest/Attribute:user_comment' => 'Reactie gebruiker',
	'Class:UserRequest/Attribute:user_comment+' => '',
	'Class:UserRequest/Attribute:parent_request_id_friendlyname' => 'parent_request_id_friendlyname',
	'Class:UserRequest/Attribute:parent_request_id_friendlyname+' => '',
	'Class:UserRequest/Stimulus:ev_assign' => 'Wijs toe',
	'Class:UserRequest/Stimulus:ev_assign+' => '',
	'Class:UserRequest/Stimulus:ev_reassign' => 'Wijs opnieuw toe',
	'Class:UserRequest/Stimulus:ev_reassign+' => '',
	'Class:UserRequest/Stimulus:ev_approve' => 'Keur goed',
	'Class:UserRequest/Stimulus:ev_approve+' => '',
	'Class:UserRequest/Stimulus:ev_reject' => 'Keur af',
	'Class:UserRequest/Stimulus:ev_reject+' => '',
	'Class:UserRequest/Stimulus:ev_pending' => 'In afwachting',
	'Class:UserRequest/Stimulus:ev_pending+' => '',
	'Class:UserRequest/Stimulus:ev_timeout' => 'Timeout',
	'Class:UserRequest/Stimulus:ev_timeout+' => '',
	'Class:UserRequest/Stimulus:ev_autoresolve' => 'Automatisch oplossen',
	'Class:UserRequest/Stimulus:ev_autoresolve+' => '',
	'Class:UserRequest/Stimulus:ev_autoclose' => 'Automatisch afsluiten',
	'Class:UserRequest/Stimulus:ev_autoclose+' => '',
	'Class:UserRequest/Stimulus:ev_resolve' => 'Markeren als opgelost',
	'Class:UserRequest/Stimulus:ev_resolve+' => '',
	'Class:UserRequest/Stimulus:ev_close' => 'Sluit af',
	'Class:UserRequest/Stimulus:ev_close+' => '',
	'Class:UserRequest/Stimulus:ev_reopen' => 'Heropen',
	'Class:UserRequest/Stimulus:ev_reopen+' => '',
	'Class:UserRequest/Stimulus:ev_wait_for_approval' => 'Wacht op goedkeuring',
	'Class:UserRequest/Stimulus:ev_wait_for_approval+' => '',
	'Class:UserRequest/Error:CannotAssignParentRequestIdToSelf' => 'Kan niet naar zichzelf verwijzen als hoofdverzoek',
));


Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Portal:TitleDetailsFor_Request' => 'Details van het verzoek',
	'Portal:ButtonUpdate' => 'Wijzig',
	'Portal:ButtonClose' => 'Sluit',
	'Portal:ButtonReopen' => 'Heropen',
	'Portal:ShowServices' => 'Toon services',
	'Portal:SelectRequestType' => 'Selecteer een type verzoek',
	'Portal:SelectServiceElementFrom_Service' => 'Selecteer een service voor %1$s',
	'Portal:ListServices' => 'Lijst met services',
	'Portal:TitleDetailsFor_Service' => 'Details van de service',
	'Portal:Button:CreateRequestFromService' => 'Maak een verzoek aan voor deze service',
	'Portal:ListOpenRequests' => 'Lijst met open verzoeken',
	'Portal:UserRequest:MoreInfo' => 'Meer informatie',
	'Portal:Details-Service-Element' => 'Service-elementen',
	'Portal:NoClosedTicket' => 'Geen gesloten verzoeken',
	'Portal:NoService' => '',
	'Portal:ListOpenProblems' => 'Openstaande problemen',
	'Portal:ShowProblem' => 'Problemen',
	'Portal:ShowFaqs' => 'Veelgestelde vragen',
	'Portal:NoOpenProblem' => 'Geen openstaand probleem',
	'Portal:SelectLanguage' => 'Verander je taal',
	'Portal:LanguageChangedTo_Lang' => 'Taal veranderd naar',
	'Portal:ChooseYourFavoriteLanguage' => 'Kies je voorkeurstaal',

	'Class:UserRequest/Method:ResolveChildTickets' => 'Los subtickets op',
	'Class:UserRequest/Method:ResolveChildTickets+' => 'Pas de oplossing ook toe op subverzoeken (ev_autoresolve) en neem de kenmerken over wat betreft service, team, agent, oplossing',
));


Dict::Add('NL NL', 'Dutch', 'Nederlands', array(
	'Organization:Overview:UserRequests' => 'Gebruikersverzoeken van deze organisatie',
	'Organization:Overview:MyUserRequests' => 'Mijn gebruikersverzoeken voor deze organisatie',
	'Organization:Overview:Tickets' => 'Tickets voor deze organisatie',
));
