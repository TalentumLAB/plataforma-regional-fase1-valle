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
 * talentum_format Information
 *
 * @package    course/format
 * @subpackage talentum_format
 * @version    See the value of '$plugin->version' in below.
 * @copyright  &copy; 2012 G J Barnard in respect to modifications of standard topics format.
 * @author     G J Barnard - gjbarnard at gmail dot com and {@link http://moodle.org/user/profile.php?id=442195}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License
 *
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Provides the information to backup talentum_format course format
 */
class backup_format_talentum_plugin extends backup_format_plugin {

    protected function define_course_plugin_structure() {

        // Define the virtual plugin element with the condition to fulfill.
        $plugin = $this->get_plugin_element(null, '/course/format', 'talentum_format');

        // Create one standard named plugin element (the visible container).
        // The courseid not required as populated on restore.
        $pluginwrapper = new backup_nested_element($this->get_recommended_name());

        // Add wrapper to plugin
        $plugin->add_child($pluginwrapper);

        $filesformat = new backup_nested_element('file', array('id'), array(
            'contenthash',
            'contextid',
            'component',
            'filearea',
            'itemid',
            'filepath',
            'filename',
            'userid',
            'filesize',
            'mimetype',
            'status',
            'timecreated',
            'timemodified',
            'source',
            'license',
            'sortorder',
            'repositorytype',
            'repositoryid',
            'reference',
            ));
        $pluginwrapper->add_child($filesformat);

            // Use database to get source
            $filesformat->set_source_table('files',
            array('contextid' => backup::VAR_CONTEXTID));

            // Include files which have coursereport_lazystudents and area image and no itemid
            $filesformat->annotate_files('format_talentum_format', 'images', null);
        

        // Don't need to annotate ids nor files.
        return $plugin;
    }
    /**
    * Returns a condition for whether we include this report in the backup
    * or not. We do that based on if it has any settings in the table.
    * @return array Condition array
    */
   protected function get_include_condition() {
    global $DB;
    if ($DB->record_exists('course_format_options',
            array('courseid' => $this->task->get_courseid()))) {
        $result = 'include';
    } else {
        $result = '';
    }
    return array('sqlparam' => $result);
}

}