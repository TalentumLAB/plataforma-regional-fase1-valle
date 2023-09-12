<?php
 
$functions = array(
        'local_course_dev_insert_users' => array(         //web service function name
            'classname'   => 'local_course_dev_external',  //class containing the external function OR namespaced class in classes/external/XXXX.php
            'methodname'  => 'insertRecord',          //external function name
            'classpath'   => 'local/course_dev/externallib.php',  //file containing the class/external function - not required if using namespaced auto-loading classes.
                                                       // defaults to the service's externalib.php
            'description' => 'insert in external db',    //human readable description of the web service function
            'type'        => 'write',                  //database rights of the web service function (read, write)
            'ajax' => true,        // is the service available to 'internal' ajax calls. 
        ),
        // 'local_course_dev_create_users' => array(         //web service function name
        //     'classname'   => 'local_course_dev_external',  //class containing the external function OR namespaced class in classes/external/XXXX.php
        //     'methodname'  => 'create_users',          //external function name
        //     'classpath'   => 'local/course_dev/externallib.php',  //file containing the class/external function - not required if using namespaced auto-loading classes.
        //                                                // defaults to the service's externalib.php
        //     'description' => 'Create users in moodle',    //human readable description of the web service function
        //     'type'        => 'write',                  //database rights of the web service function (read, write)
        //     'ajax' => true,        // is the service available to 'internal' ajax calls. 
        // ),
    );
$services = array( 
        'Dev service test' => array(
            'functions' => array (
                'local_course_dev_insert_users',
                'core_user_create_users',
                'core_course_duplicate_course',
                'core_course_create_categories',
                'core_course_delete_categories',
                'enrol_manual_unenrol_users'
            ), //web service function name  
            'requiredcapability' => '',                // if set, the web service user need this capability to access 
                                                                              // any function of this service. For example: 'some/capability:specified'                 
          'restrictedusers' => 0,                                             // if enabled, the Moodle administrator must link some user to this service
                                                                              // into the administration
          'enabled' => 1,                                                       // if enabled, the service can be reachable on a default installation
          'shortname' =>  '',       // optional – but needed if restrictedusers is set so as to allow logins.
          'downloadfiles' => 0,    // allow file downloads.
          'uploadfiles'  => 0      // allow file uploads.
        )
    );