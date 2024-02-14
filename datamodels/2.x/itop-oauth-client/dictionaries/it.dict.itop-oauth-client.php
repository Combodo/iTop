<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
Dict::Add('IT IT', 'Italian', 'Italiano', [
	'Menu:CreateMailbox' => 'Crea una casella di posta...~~',
	'Menu:OAuthClient' => 'Client OAuth~~',
	'Menu:GenerateTokens' => 'Genera token di accesso...~~',
	'Menu:RegenerateTokens' => 'Rigenera token di accesso...~~',
	'itop-oauth-client/Operation:CreateMailBox/Title' => 'Creazione di una casella di posta~~',
	'itop-oauth-client:UsedForSMTP' => 'Questo client OAuth è utilizzato per SMTP~~',
	'itop-oauth-client:TestSMTP' => 'Test di invio email~~',
	'itop-oauth-client:MissingOAuthClient' => 'Client OAuth mancante per il nome utente %1$s~~',
	'itop-oauth-client:Message:MissingToken' => 'Genera un token di accesso prima di utilizzare questo client OAuth~~',
	'itop-oauth-client:Message:RegenerateToken' => 'Rigenera il token di accesso per prendere in considerazione le modifiche~~',
	'itop-oauth-client:Message:TokenCreated' => 'Token di accesso creato~~',
	'itop-oauth-client:Message:TokenRecreated' => 'Token di accesso rigenerato~~',
	'itop-oauth-client:Message:TokenError' => 'Token di accesso non generato a causa di un errore del server~~',
	'OAuthClient:Name/UseForSMTPMustBeUnique' => 'La combinazione Login (%1$s) e Uso per SMTP (%2$s) è già stata utilizzata per un altro Client OAuth~~',
	'OAuthClient:baseinfo' => 'Informazioni di base~~',
	'OAuthClient:scope' => 'Ambito~~',
]);

//
// Class: OAuthClient
//

Dict::Add('IT IT', 'Italian', 'Italiano', [
	'Class:OAuthClient' => 'Client OAuth~~',
	'Class:OAuthClient/Attribute:provider' => 'Fornitore~~',
	'Class:OAuthClient/Attribute:name' => 'Login~~',
	'Class:OAuthClient/Attribute:scope' => 'Ambito~~',
	'Class:OAuthClient/Attribute:status' => 'Stato~~',
	'Class:OAuthClient/Attribute:status+' => 'Dopo la creazione, utilizzare l\'azione “Genera token di accesso” per poter utilizzare questo client OAuth~~',
	'Class:OAuthClient/Attribute:status/Value:active' => 'Token di accesso generato~~',
	'Class:OAuthClient/Attribute:status/Value:inactive' => 'Nessun token di accesso~~',
	'Class:OAuthClient/Attribute:description' => 'Descrizione~~',
	'Class:OAuthClient/Attribute:client_id' => 'ID cliente~~',
	'Class:OAuthClient/Attribute:client_secret' => 'Segreto del cliente~~',
	'Class:OAuthClient/Attribute:refresh_token' => 'Token di aggiornamento~~',
	'Class:OAuthClient/Attribute:refresh_token_expiration' => 'Scadenza del token di aggiornamento~~',
	'Class:OAuthClient/Attribute:token' => 'Token di accesso~~',
	'Class:OAuthClient/Attribute:token_expiration' => 'Scadenza del token di accesso~~',
	'Class:OAuthClient/Attribute:redirect_url' => 'URL di reindirizzamento~~',
	'Class:OAuthClient/Attribute:mailbox_list' => 'Lista delle caselle di posta~~',
]);

//
// Class: OAuthClientAzure
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:OAuthClientAzure' => 'Client OAuth per Microsoft Azure~~',
	'Class:OAuthClientAzure/Name' => '%1$s (%2$s)~~',
	'Class:OAuthClientAzure/Attribute:scope' => 'Ambito~~',
	'Class:OAuthClientAzure/Attribute:scope+' => 'Di solito la selezione predefinita è appropriata~~',
	'Class:OAuthClientAzure/Attribute:scope/Value:SMTP' => 'SMTP~~',
	'Class:OAuthClientAzure/Attribute:scope/Value:IMAP' => 'IMAP~~',
	'Class:OAuthClientAzure/Attribute:advanced_scope' => 'Ambito avanzato~~',
	'Class:OAuthClientAzure/Attribute:advanced_scope+' => 'Non appena inserisci qualcosa qui, essa ha la precedenza sulla selezione “Ambito” che viene quindi ignorata~~',
	'Class:OAuthClientAzure/Attribute:used_scope' => 'Ambito utilizzato~~',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:simple' => 'Semplice~~',
	'Class:OAuthClientAzure/Attribute:used_scope/Value:advanced' => 'Avanzato~~',
	'Class:OAuthClientAzure/Attribute:used_for_smtp' => 'Utilizzato per SMTP~~',
	'Class:OAuthClientAzure/Attribute:used_for_smtp+' => 'Almeno un client OAuth deve avere questo flag impostato su “Sì”, se si desidera che iTop lo utilizzi per inviare email~~',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:yes' => 'Sì~~',
	'Class:OAuthClientAzure/Attribute:used_for_smtp/Value:no' => 'No~~',
));

//
// Class: OAuthClientGoogle
//

Dict::Add('IT IT', 'Italian', 'Italiano', array(
	'Class:OAuthClientGoogle' => 'Client OAuth per Google~~',
	'Class:OAuthClientGoogle/Name' => '%1$s (%2$s)~~',
	'Class:OAuthClientGoogle/Attribute:scope' => 'Ambito~~',
	'Class:OAuthClientGoogle/Attribute:scope+' => 'Di solito la selezione predefinita è appropriata~~',
	'Class:OAuthClientGoogle/Attribute:scope/Value:SMTP' => 'SMTP~~',
	'Class:OAuthClientGoogle/Attribute:scope/Value:IMAP' => 'IMAP~~',
	'Class:OAuthClientGoogle/Attribute:advanced_scope' => 'Ambito avanzato~~',
	'Class:OAuthClientGoogle/Attribute:advanced_scope+' => 'Non appena inserisci qualcosa qui, essa ha la precedenza sulla selezione “Ambito” che viene quindi ignorata~~',
	'Class:OAuthClientGoogle/Attribute:used_scope' => 'Ambito utilizzato~~',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:simple' => 'Semplice~~',
	'Class:OAuthClientGoogle/Attribute:used_scope/Value:advanced' => 'Avanzato~~',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp' => 'Utilizzato per SMTP~~',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp+' => 'Almeno un client OAuth deve avere questo flag impostato su “Sì”, se si desidera che iTop lo utilizzi per inviare email~~',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:yes' => 'Sì~~',
	'Class:OAuthClientGoogle/Attribute:used_for_smtp/Value:no' => 'No~~',
));
