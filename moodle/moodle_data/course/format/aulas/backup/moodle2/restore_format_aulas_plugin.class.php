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
 * aulas_format Information
 *
 * @package    course/format
 * @subpackage aulas_format
 * @version    See the value of '$plugin->version' in below.
 * @copyright  &copy; 2012 G J Barnard in respect to modifications of standard topics format.
 * @author     G J Barnard - gjbarnard at gmail dot com and {@link http://moodle.org/user/profile.php?id=442195}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License
 *
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Provides the information to backup aulas_format course format
 */
class restore_format_aulas_plugin extends restore_format_plugin{ 

    /**
     * Returns the paths to be handled by the plugin at course level
     */
    protected function define_course_plugin_structure() {
        $paths = array();

        // Because of using get_recommended_name() it is able to find the
        // correct path just by using the part inside the element name (which
        // only has a /aulas_format element).
        $elepath = $this->get_pathfor('/aulas_format');

        // The 'lazystudents' here defines that it will use the
        // process_lazystudents function to restore its element.
        $paths[] = new restore_path_element('aulas_format', $elepath);

        return $paths;
    }

    /**
     * Called after this runs for a course.
     */
    function after_execute_course() {
        // Need to restore file
        $this->add_related_files('format_aulas_format', 'images', null);
    }

    /**
     * Process the 'lazystudents' element
     */
    public function process_aulas_format($data) {
        global $DB;

        // Get data record ready to insert in database
        $data = (object)$data;
        $data->courseid = $this->task->get_courseid();

        // See if there is an existing record for this course
        $existingid = $DB->get_field('course_format_options', 'id',
                array('courseid'=>$data->courseid));
        if ($existingid) {
            $data->id = $existingid;
            $DB->update_record('course_format_options', $data);
        } else {
            $DB->insert_record('course_format_options', $data);
        }

        // No need to record the old/new id as nothing ever refers to
        // the id of this table.
    }
}