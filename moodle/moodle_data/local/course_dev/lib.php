<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Extend navigation to add new options.
 *
 * @package    local_course_dev
 * @author     andres robin 
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// function local_course_dev_extend_settings_navigation($settingsnav, $context) {
//     global $CFG, $PAGE;

//     // Only add this settings item on non-site course pages.
//     // if (!$PAGE->course or $PAGE->course->id == 1) {
//     //     return;
//     // }

//     // // Only let users with the appropriate capability see this settings item.
//     // if (!has_capability('moodle/backup:backupcourse', context_course::instance($PAGE->course->id))) {
//     //     return;
//     // }

//     if ($settingnode = $settingsnav->find('courseadmin', navigation_node::NODETYPE_LEAF)) {
//         $strfoo = get_string('menuTitle', 'local_course_dev');
//         $url = new moodle_url('/local/course_dev/pages/admin.php');
//         $foonode = navigation_node::create(
//             $strfoo,
//             $url,
//             navigation_node::NODETYPE_LEAF,
//             'course_dev',
//             'course_dev',
//             new pix_icon('t/addcontact', $strfoo)
//         );
//         // if ($PAGE->url->compare($url, URL_MATCH_BASE)) {
//         //     $foonode->make_active();
//         // }
//         $settingnode->add_node($foonode);
//     }
// }

/** add option in main menu*/
// function local_course_dev_extend_navigation(global_navigation $navigation) {

//     if (!has_capability('moodle/site:config',context_system::instance())) {
//         return;
//     }

//     $strfoo = get_string('menuTitle', 'local_course_dev');
//     $url = new moodle_url('/local/course_dev/pages/admin.php');
//     $node = navigation_node::create(
//             $strfoo,
//             $url,
//             navigation_node::NODETYPE_LEAF,
//             'course_dev',
//             'course_dev',
//             new pix_icon('t/addcontact', 'configuracion')
//     );
//     $node->showinflatnavigation = true;
//     $navigation->add_node($node);

// }

