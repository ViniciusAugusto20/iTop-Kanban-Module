<?php
//
// iTop module definition file
//

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'lbl-kanban/1.0.0',
	array(
		// Identification
		//
		'label' => 'lbl-kanban',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
            'itop-tickets/2.x',
            'itop-request-mgmt/2.x',
            //'datamodel.lbl-campos-change/lbl-campos-change'
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			'main.lbl-kanban.php'
		),
		'webservice' => array(
			
		),
		'data.struct' => array(
			// add your 'structure' definition XML files here,
		),
		'data.sample' => array(
			// add your sample data XML files here,
		),
		
		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any 

		// Default settings
		//
		'settings' => array(
			// Module specific settings go here, if any
		),
	)
);


?>
