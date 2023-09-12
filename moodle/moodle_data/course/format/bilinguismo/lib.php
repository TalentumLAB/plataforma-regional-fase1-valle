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
 * bilinguismo_Formatt_renderer
 *
 * @package    bilinguismo_Format
 * @author     bilinguismo
 * @copyright  bilinguismo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot. '/course/format/topics/lib.php');
require_once($CFG->dirroot. '/course/format/lib.php');

class format_bilinguismo extends format_topics 
{
    
    /**
     * course_format_options
     *
     * @param bool $foreditform
     * @return array
     * @param stdclass|array $data
     */
    public function course_format_options($foreditform = false){
        
        global $PAGE,$DB;
        $course = $this->get_course();
        static $courseformatoptions = false;
        if ($courseformatoptions === false) {
            $courseconfig = get_config('moodlecourse');
            
            $courseformatoptions['numsections'] = array(
                'default' => $courseconfig->numsections,
                'type' => PARAM_INT,
            );
            $courseformatoptions['hiddensections'] = array(
                'default' => $courseconfig->hiddensections,
                'type' => PARAM_INT,    
            );
            $courseformatoptions['showdefaultsectionname'] = array(
                'default' => get_config('format_bilinguismo', 'showdefaultsectionname'),
                'type' => PARAM_INT,
            );
            // $courseformatoptions['Modulevideohome'] = array(
            //     'default' => get_config('format_bilinguismo', 'Modulevideohome'),
            //     'type' => PARAM_TEXT,
            // );
            $courseformatoptions['MainObjective'] = array(
                'default' => get_config('format_bilinguismo', 'MainObjective'),
                'type' => PARAM_TEXT,
            );
            
            
            for ($i = 1; $i <= 10; $i++) {
                $divisortext = get_config('format_bilinguismo', 'moduletitle'.$i);
                $divisortextvideo = get_config('format_bilinguismo', 'modulevideo'.$i);
                $divisortextobj = get_config('format_bilinguismo', 'moduleobjetive'.$i);
                $divisorname = get_config('format_bilinguismo', 'modulename'.$i);
                if (!$divisortext) {
                    $divisortext = '';
                    $divisortextrule = '';
                    $divisorname = '';
                    $divisortextvideo = '';
                    $divisortextobj = '';
                }
                // Titulo del modulo
                $courseformatoptions['moduletitle'.$i] = array(
                    'default' => $divisortext,
                    'type' => PARAM_TEXT,
                );
                // Nombre del modulo
                $courseformatoptions['modulename'.$i] = array(
                    'default' => $divisorname,
                    'type' => PARAM_TEXT,                    
                );
                
            $courseformatoptions['filepicker'.$i.'_filemanager'] = array(
                'label' => get_string('moduleimg', 'format_bilinguismo', $i),
                'help' => 'moduleimg',
                'help_component' => 'format_bilinguismo',
                'element_type' => 'filemanager',
                'element_attributes' => array(
                    null,
                    array(
                        'subdirs' => 0,
                        'maxbytes' => 2000000,
                        'maxfiles' => 1,
                        'accepted_types' => array(
                            '.jpg',
                            '.png',
                            '.jpeg',
                            '.svg',
                            '.gif',
                        ),
                    ),
                    
                ),
                );
                $optionsfile =array(
                    'subdirs' => 0,
                    'maxbytes' => 2000000,
                    'maxfiles' => 1,
                    'accepted_types' => array('.jpg','.png','.jpeg','.svg','.gif'),
                );
                if($this->courseid != null){
                $context = context_course::instance($this->courseid);
                $name = 'filepicker'.$i.'_filemanager';
                $draftid_editor_file = file_get_submitted_draft_itemid('filepicker'.$i.'_filemanager');
                
                file_prepare_draft_area($draftid_editor_file, $context->id, 'format_bilinguismo', 'images', $i, $optionsfile);
                //get the id of all section
                $idfile = $DB->get_record_sql("SELECT id FROM mdl_course_format_options  where name='$name' and courseid=$course->id");
                $idelementfile = (int)$idfile->id;
                $newvalueid = new stdClass();
                $newvalueid->id = $idelementfile;
                $newvalueid->value =  $draftid_editor_file;
                $DB->update_record('course_format_options', $newvalueid);
                }
                
                // $courseformatoptions['modulevideo'.$i] = array(
                //     'default' => $divisortextvideo,
                //     'type' => PARAM_TEXT,
                // );
                // $courseformatoptions['moduleobjetive'.$i] = array(
                //     'default' => $divisortextobj,
                //     'type' => PARAM_TEXT,
                // );
                
            }
        }
        if ($foreditform && !isset($courseformatoptions['coursedisplay']['label'])) {
            $courseconfig = get_config('moodlecourse');
            $max = $courseconfig->maxsections;
            if (!isset($max) || !is_numeric($max)) {
                $max = 10;
            }
            $max = 10;
            $sectionmenu = array();
            for ($i = 0; $i <= $max; $i++) {
                $sectionmenu[$i] = "$i";
            }
            $courseformatoptionsedit['numsections'] = array(
                'label' => get_string('numsections', 'format_bilinguismo'),
                'element_type' => 'select',
                'element_attributes' => array($sectionmenu),
            );
            $courseformatoptionsedit['hiddensections'] = array(
                'label' => new lang_string('hiddensections'),
                'help' => 'hiddensections',
                'help_component' => 'moodle',
                'element_type' => 'select',
                'element_attributes' => array(
                    array(
                        0 => new lang_string('hiddensectionscollapsed'),
                        1 => new lang_string('hiddensectionsinvisible')
                    )
                ),
            );
            
            $courseformatoptionsedit['showdefaultsectionname'] = array(
                'label' => get_string('showdefaultsectionname', 'format_bilinguismo'),
                'help' => 'showdefaultsectionname',
                'help_component' => 'format_bilinguismo',
                'element_type' => 'select',
                'element_attributes' => array(
                    array(
                        1 => get_string('yes', 'format_bilinguismo'),
                        0 => get_string('no', 'format_bilinguismo'),
                    ),
                ),
            );
            // $courseformatoptionsedit['Modulevideohome'] = array(
            //     'label' => get_string('Modulevideohome', 'format_bilinguismo'),
            //     'help' => 'Modulevideohome',
            //     'help_component' => 'format_bilinguismo',
            //     'element_type' => 'text',
            // );
            $courseformatoptionsedit['MainObjective'] = array(
                'label' => get_string('MainObjective', 'format_bilinguismo'),
                'help' => 'MainObjective',
                'help_component' => 'format_bilinguismo',
                'element_type' => 'text',
            );
            for ($i = 1; $i <= 10; $i++) {
                // titulo del modulo
                $courseformatoptionsedit['moduletitle'.$i] = array(
                    'label' => get_string('moduletitle', 'format_bilinguismo', $i),
                    'help' => 'moduletitle',
                    'help_component' => 'format_bilinguismo',
                    'element_type' => 'text',
                                       
                );
                
                // Descripcion del modulo
                $courseformatoptionsedit['modulename'.$i] = array(
                    'label' => get_string('modulename', 'format_bilinguismo', $i),
                    'help' => 'modulename',
                    'help_component' => 'format_bilinguismo',
                    'element_type' => 'text',
                ); 
               
                // $courseformatoptionsedit['modulevideo'.$i] = array(
                //     'label' => get_string('modulevideo', 'format_bilinguismo', $i),
                //     'help' => 'modulevideo',
                //     'help_component' => 'format_bilinguismo',
                //     'element_type' => 'text',
                // );
                // $courseformatoptionsedit['moduleobjetive'.$i] = array(
                //     'label' => get_string('moduleobjetive', 'format_bilinguismo', $i),
                //     'help' => 'moduleobjetive',
                //     'help_component' => 'format_bilinguismo',
                //     'element_type' => 'text',
                // );
            }
            $courseformatoptions = array_merge_recursive($courseformatoptions, $courseformatoptionsedit);
        }
        
        return $courseformatoptions;
        
    }
    /**
     * update_course_format_options
     *
     * @param stdclass|array $data
     * @param stdClass $oldcourse
     * @return bool
     */
    public function update_course_format_options($data, $oldcourse = null){
        global $DB;
        $data = (array)$data;
        $context = context_course::instance($this->courseid);
        /** prepare files to save
         * cuando se actualiza el formato de curso las imagenes se guardan
         */
      for ($i=1; $i <= 10 ; $i++) { 
                
               $draftitemid = file_get_submitted_draft_itemid('filepicker'.$i.'_filemanager');

                // ... store or update $entry
                $filesoptions =array('subdirs' => 0,'maxbytes' => 2000000,'maxfiles' => 1,
                'accepted_types' => array(
                        '.jpg',
                        '.png',
                        '.jpeg',
                        '.svg',
                        '.gif',
                    ),
                );
                    
                $entry = file_save_draft_area_files($draftitemid, $context->id, 'format_bilinguismo', 'images', $i, $filesoptions);
                
       }
        if ($oldcourse !== null) {
            $oldcourse = (array)$oldcourse;
            $options = $this->course_format_options();
            foreach ($options as $key => $unused) {
                if (!array_key_exists($key, $data)) {
                    if (array_key_exists($key, $oldcourse)) {
                        $data[$key] = $oldcourse[$key];
                    } else if ($key === 'numsections') {
                        $maxsection = $DB->get_field_sql('SELECT max(section) from {course_sections} WHERE course = ?', array($this->courseid));
                        if ($maxsection) {
                            $data['numsections'] = $maxsection;
                        }
                    }
                }
            }
        }
        $changed = $this->update_format_options($data);
        if ($changed && array_key_exists('numsections', $data)) {
            $numsections = (int)$data['numsections'];
            $maxsection = $DB->get_field_sql('SELECT max(section) from {course_sections} WHERE course = ?', array($this->courseid));
            for ($sectionnum = $maxsection; $sectionnum > $numsections; $sectionnum--) {
                if (!$this->delete_section($sectionnum, false)) {
                    break;
                }
            }
        }
       
        return $changed;
    }

    /**
     * get_view_url
     *
     * @param int|stdclass $section
     * @param array $options
     * @return null|moodle_url
     */
    public function get_view_url($section, $options = array()){
        global $CFG;
        $course = $this->get_course();
        $url = new moodle_url('/course/view.php', array('id' => $course->id));

        $sr = null;
        if (array_key_exists('sr', $options)) {
            $sr = $options['sr'];
        }
        if (is_object($section)) {
            $sectionno = $section->section;
        } else {
            $sectionno = $section;
        }
        if ($sectionno !== null) {
            if ($sr !== null) {
                if ($sr) {
                    $usercoursedisplay = COURSE_DISPLAY_MULTIPAGE;
                    $sectionno = $sr;
                } else {
                    $usercoursedisplay = COURSE_DISPLAY_SINGLEPAGE;
                }
            } else {
                $usercoursedisplay = 0;
            }
            if ($sectionno != 0 && $usercoursedisplay == COURSE_DISPLAY_MULTIPAGE) {
                $url->param('section', $sectionno);
            } else {
                if (empty($CFG->linkcoursesections) && !empty($options['navigation'])) {
                    return null;
                }
                $url->set_anchor('section-'.$sectionno);
            }
        }
        return $url;
    }

      /**
     * Returns the information about the ajax support in the given source format.
     *
     * The returned object's property (boolean)capable indicates that
     * the course format supports Moodle course ajax features.
     *
     * @return stdClass
     */
    public function supports_ajax() {
        $ajaxsupport = new stdClass();
        $ajaxsupport->capable = true;
        return $ajaxsupport;
    }

    public function supports_components() {
        return true;
    }

    /**
     * Custom action after section has been moved in AJAX mode.
     *
     * Used in course/rest.php
     *
     * @return array This will be passed in ajax respose
     */
    public function ajax_section_move() {
        global $PAGE;
        $titles = [];
        $course = $this->get_course();
        $modinfo = get_fast_modinfo($course);
        $renderer = $this->get_renderer($PAGE);
        if ($renderer && ($sections = $modinfo->get_section_info_all())) {
            foreach ($sections as $number => $section) {
                $titles[$number] = $renderer->section_title($section, $course);
            }
        }
        return ['sectiontitles' => $titles, 'action' => 'move'];
    }

}

/**
 * Implements callback inplace_editable() allowing to edit values in-place
 *
 * @param string $itemtype
 * @param int $itemid
 * @param mixed $newvalue
 * @return \core\output\inplace_editable
 */
function format_bilinguismo_inplace_editable($itemtype, $itemid, $newvalue){
    
    global $DB, $CFG;
    require_once($CFG->dirroot . '/course/lib.php');
    if ($itemtype === 'sectionname' || $itemtype === 'sectionnamenl') {
        $section = $DB->get_record_sql(
            'SELECT s.* FROM {course_sections} s JOIN {course} c ON s.course = c.id WHERE s.id = ? AND c.format = ?',
            array($itemid, 'bilinguismo'),
            MUST_EXIST
        );
        return course_get_format($section->course)->inplace_editable_update_section_name($section, $itemtype, $newvalue);
    }
}

  


/**---------------------------------------------- */
/**
 * return url image for display
 */
  /** get items by the itemid field in the mdl_files db table
   * @param $itemid an itemid defined in the mdl_files table 
   * @return array an array of file objects to play with
   */
function get_files_by_itemid($itemid) {
        global $DB;
        $fs = get_file_storage();
        // Preallocate the result array
        $result = array();
        // Conditions for teh database call
    if ($itemid !== false) {
        $conditions['itemid'] = $itemid;
    }
        $sort = "sortorder, itemid, filepath, filename";
        // Get the file records from the database 
        $file_records = $DB->get_records('files', $conditions, $sort);
        // Loop through
    foreach ($file_records as $file_record) {
        // If there's content- for some reason there is stacks of these rows with nothing in them
        if ($file_record->filename === '.') {
            continue;
        }
        $result[$file_record->pathnamehash] = new stored_file($fs, $file_record);
    } 
        return $result;
} 
/*
 * Serve the files from the MYPLUGIN file areas
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if the file not found, just send the file otherwise and do not return anything
 */
function format_bilinguismo_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    // Check the contextlevel is as expected - if your plugin is a block, this becomes CONTEXT_BLOCK, etc.
    
    if ($context->contextlevel != CONTEXT_COURSE) {
        print_error('context');
    }
 
    // Make sure the filearea is one of those used by the plugin.
    if ($filearea !== 'images') {
        print_error('filearea');
    }
 
    // Make sure the user is logged in and has access to the module (plugins that are not course modules should leave out the 'cm' part).
    require_login($course, true, $cm);
 
 
    // Leave this line out if you set the itemid to null in make_pluginfile_url (set $itemid to 0 instead).
    $itemid = array_shift($args); // The first item in the $args array.
 
    // Use the itemid to retrieve any relevant data records and perform any security checks to see if the
    // user really does have access to the file in question.
 
    // Extract the filename / filepath from the $args array.
    $filename = array_pop($args); // The last item in the $args array.
    if (!$args) {
        $filepath = '/'; // $args is empty => the path is '/'
    } else {
        $filepath = '/'.implode('/', $args).'/'; // $args contains elements of the filepath
    }
 
    // Retrieve the file from the Files API.
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'format_bilinguismo', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false; // The file does not exist.
        print_error('file does exist');

    }
 
    // We can now send the file back to the browser - in this case with a cache lifetime of 1 day and no filtering. 
    send_stored_file($file, 0, 0, $forcedownload, $options);
}
/**
 * get the id of all section in the course
 *  $courseid  the course id
 */

 function progress_percentage_bilinguismo($courseid) {
    global $DB;
    $activities = array();
    //get the id of all section
    $sections = $DB->get_records_sql('SELECT id as section FROM mdl_course_sections  where course='.$courseid.'');
    
    
    foreach($sections as $a){
        
        $activities[] = $a;
        
    }
   
    return $activities;
} 
/**
 * Get all activities of a section
 * $course the id of course
 * $section the id of section
 */
function activities_bilinguismo($course,$section,$user) {
    global $DB; 
    $completecount = 0;
    $complete_act = 0;
    //get the count of the active activities of the section
    $sections = $DB->get_records_sql('SELECT count(module) as c from mdl_course_modules where course='.$course.' and section='.$section.' and deletioninprogress = 0 and completion != 0');
    //get the count of the complete activities of the section
    $complete = $DB->get_records_sql('SELECT count(module) as module  FROM mdl_course_modules_completion as complete JOIN mdl_course_modules as module WHERE module.id = complete.coursemoduleid and course='.$course.' and section='.$section.' and completionstate = 1 and complete.userid='.$user.'');
    
    foreach($sections as $a){
        $completecount = $a->c;
    }
    foreach($complete as $a){
        $complete_act=$a->module;
    }
    if ($completecount == 0) {
        $total = "No hay actividades";
    }
    //obtain the module progress percent
    else {
    $total = round(($complete_act * 100) / $completecount);
    }
    return $total;

}