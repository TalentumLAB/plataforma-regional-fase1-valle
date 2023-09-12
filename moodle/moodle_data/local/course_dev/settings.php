<?php

defined('MOODLE_INTERNAL') || die;
// Ensure the configurations for this site are set
if ( $hassiteconfig ){

	// Create the new settings page
	// - in a local plugin this is not defined as standard, so normal $settings->methods will throw an error as
	// $settings will be NULL
	$settings = new admin_settingpage( 'local_course_dev', get_string('pluginname', 'local_course_dev')  );

	// Create 
	$ADMIN->add( 'localplugins', $settings );

	// Add a setting field to the settings for this page
	// $settings->add( 
    //     new admin_setting_configcheckbox(
    //         'local_course_dev', 
    //         get_string('pluginname', 'local_course_dev'),
    //         get_string('pluginname', 'local_course_dev'), 
    //         0)
    // );

	// Add a setting field to the simat server
	$settings->add( 
		new admin_setting_configtext(
		// This is the reference you will use to your configuration
		'local_course_dev/simat_conection_server',
		// This is the friendly title for the config, which will be displayed
		'Simat config:server',
		// This is helper text for this config field
		'Configuracion del nombre del servidor de la bd simat',
		// This is the default value
		'localhost:8080',
		// This is the type of Parameter this config is
		PARAM_TEXT
	) );

	// Add a setting field to the simat server
	$settings->add( 
		new admin_setting_configtext(
		// This is the reference you will use to your configuration
		'local_course_dev/simat_conection_user',
		// This is the friendly title for the config, which will be displayed
		'Simat config:user_db',
		// This is helper text for this config field
		'Configuracion del usuario de la bd simat',
		// This is the default value
		'root',
		// This is the type of Parameter this config is
		PARAM_TEXT
	) );

	// Add a setting field to the simat db user
	$settings->add( 
		new admin_setting_configtext(
		// This is the reference you will use to your configuration
		'local_course_dev/simat_conection_password',
		// This is the friendly title for the config, which will be displayed
		'Simat config:password_db',
		// This is helper text for this config field
		'Configuracion de la contraseña de la bd simat',
		// This is the default value
		'12345',
		// This is the type of Parameter this config is
		PARAM_TEXT
	) );

	// Add a setting field to the simat password
	$settings->add( 
		new admin_setting_configtext(
		// This is the reference you will use to your configuration
		'local_course_dev/simat_conection_db_name',
		// This is the friendly title for the config, which will be displayed
		'Simat config:name_db',
		// This is helper text for this config field
		'Configuracion del nombre de la bd simat',
		// This is the default value
		'bilinguismo',
		// This is the type of Parameter this config is
		PARAM_TEXT
	) );

	// $configkey = new lang_string('cfg_local_test_key', 'local_course_dev');
	// $configdesc = new lang_string('cfg_local_test_desc', 'local_course_dev');
	// $configdefault = 'Titulo de prueba';
	// $settings->add(new admin_setting_configtext('local_course_dev/test', $configkey, $configdesc, $configdefault, PARAM_TEXT));

}