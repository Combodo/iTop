<?php

//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'servername-ticket/2.6.2',
	[
		// Identification
		//
		'label' => 'I3S Datamodel tickets',
		'category' => 'business',

		// Setup
		//
		'dependencies' => [
			'itop-attachments/2.5.0',
		],
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => [
			'model.servername-datamodel-ticket.php',
			'main.servername-datamodel-ticket.php',
		],
		'webservice' => [

		],
		'data.struct' => [
			// add your 'structure' definition XML files here,
		],
		'data.sample' => [
			// add your sample data XML files here,
		],

		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any

		// Default settings
		//
		'settings' => [
			// url d'accès au répertoire des traces applicatives liées aux tickets
			'traces_base_url' => 'file://'.$_SERVER['SERVER_NAME'].'/traces',
			// répertoire des faqi liées aux tickets
			'traces_base_dir_faqi' => '/data/i3s-gsit-tt/faqi',
			// url d'accès au répertoire des faqi liées aux tickets
			// ce serveur est-il le serveur de consolidation ?
			'consolidation_server' => false,
			// restriction des franchissements
			'max_allowed_transitions' => [
				// Les noms des transitions sont visibles dans Outils d'admin => Modèle de données => Incident => Cycle de vie
				// Les transitions qui ne sont pas présentes dans ce tableau sont considérées comme étant à 0
				/* 'nom technique de la transition' => nombre maximal autorisé */
				'ev_askinfo' => 0,          // Demander des informations (au demandeur)
				'ev_assign' => 0,           // Assigner
				'ev_cancel_by_user' => 0,   // Annuler (par le demandeur)
				'ev_cancel' => 0,           // Annuler
				'ev_close' => 0,            // Clore
				'ev_escalate' => 0,         // Escalader
				'ev_giveinfo' => 0,         // Envoyer les informations
				'ev_monitor' => 0,          // Surveiller
				'ev_pending' => 0,          // En attente
				'ev_reassign' => 0,         // Ré-assigner
				'ev_refuse_reject' => 0,    // Refuser le rejet
				'ev_refuse_solution' => 0,  // Refuser la solution
				'ev_reject' => 0,           // Rejeter
				'ev_resolve' => 0,          // Marquer comme résolu
				'ev_suspend' => 0,          // Suspendre
				'ev_terminate' => 0,        // Solder
				'ev_verify' => 0,           // Accepter la solution / Confirmer la résolution
			],
		],
	]
);
