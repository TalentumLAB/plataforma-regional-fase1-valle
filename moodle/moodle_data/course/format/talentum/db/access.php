<?php
$capabilities = array(
    'mod/course:managefiles' => array(
        'riskbitmask' => RISK_SPAM,
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),
    // 'repository/coursefiles:view' => array(
    //     'riskbitmask' => RISK_SPAM,
    //     'captype' => 'write',
    //     'contextlevel' => CONTEXT_COURSE,
    //     'archetypes' => array(
    //         'user' => CAP_ALLOW
    //     )
    // ),
    // 'moodle/user:manageownfiles' => array(

    //     'riskbitmap' => RISK_SPAM | RISK_PERSONAL,

    //     'captype' => 'write',
    //     'contextlevel' => CONTEXT_SYSTEM,
    //     'archetypes' => array(
    //         'user' => CAP_ALLOW
    //     )
    // ),
    // 'moodle/badges:viewotherbadges' => array(
    //     'riskbitmap'    => RISK_PERSONAL,
    //     'captype'       => 'read',
    //     'contextlevel'  => CONTEXT_USER,
    //     'archetypes'    => array(
    //         'user'    => CAP_ALLOW
    //     )
    // ),
 );