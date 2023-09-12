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
// MERCHANsILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Talentum_Format_renderer
 *
 * @package    Talentum_Format
 * @author     Talentum
 * @copyright  Talentum
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/format/topics/renderer.php');

/**
 * format_Talentum_renderer
 *
 * @package    format_Talentum
 * @author     Rodrigo Brandão (rodrigobrandao.com.br)
 * @copyright  2017 Rodrigo Brandão
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class format_talentum_renderer extends format_topics_renderer{
    
    /**
     * get_button_section
     *
     * @param stdclass $course
     * @param string $name
     * @return string
     */
    protected function get_color_config($course, $name)
    {
        $return = false;
        if (isset($course->{$name})) {
            $color = str_replace('#', '', $course->{$name});
            $color = substr($color, 0, 6);
            if (preg_match('/^#?[a-f0-9]{6}$/i', $color)) {
                $return = '#'.$color;
            }
        }
        return $return;
    }

    /**
     * get_button_section
     *
     * @param stdclass $course
     * @param string $sectionvisible
     * @return string
     */
    protected function get_button_section($course, $sectionvisible)
    {
        global $PAGE;
        global $DB;
        global $CFG;
        global $USER;
        $context = context_course::instance($course->id);
        $html = '';
        $videocontainerhtml='';
        $modulevideohtml = '';
        $cardscontainerhtml= '';
        $modulecardhtml = '';
        $backnavhtml= '';
        $containerModule='';
        $containerModuleInternal='';
        $css = '';
        $container_home= '';
        $button_program_course= '';
        $button_proccess= '';
        $button_Forum= '';
        $button_meeting= '';
        $button_Doubts= '';
        $module = -1;
        $modules = -1;
        $sectionstyle = '';
        if ($colorcurrent = $this->get_color_config($course, 'colorcurrent')) {
            $css .=
            '#talentum-container-section .talentum-button-section.current {
                background: ' . $colorcurrent . ';
            }
            ';
        }
        if ($colorvisible = $this->get_color_config($course, 'colorvisible')) {
            $css .=
            '#talentum-container-section .buttonsection.sectionvisible {
                background: ' . $colorvisible . ';
            }
            ';
        }
        if ($css) {
            $html .= html_writer::tag('style', $css);
        }
        /*$withoutdivisor = true;
        for ($k = 1; $k <= 6; $k++) {
            if ($course->{'moduleimg' . $k}) {
                $withoutdivisor = false;
            }
        }
        if ($withoutdivisor) {
            $course->divisor1 = 999;
        }*/
        $img = 1;
        $divisorshow = false;
        $count = 1;
        $count1 = 1;
        $count2 = 1;
        $count3 = 1;
        $sec= 1;
        $currentdivisor = 1;
        $currentdivisor1 = 1;
        $currentdivisor2 = 1;
        $divisorvideos = 1;
        $modinfo = get_fast_modinfo($course);
        $inline = '';
        $container_home .= html_writer::start_tag('div',['class'=>'container-fluid','id' => 'talentum-modules-container']);
        $container_home .= html_writer::start_tag('div',['class'=>'row featurette']);
        /* cards */
        $cardscontainerhtml .= html_writer::start_tag('div', ['class' => "col-md-12 talentum-container-cards",'style' => "margin:1rem 0;display:flex"]);
        
        $percent = progress_percentage($course->id);

        $roleid = $DB->get_field('role', 'id', ['shortname' => 'editingteacher']);
        $iseditingteacheranywhere = $DB->record_exists('role_assignments', ['userid' => $USER->id, 'roleid' => $roleid]);

        $roleidteacher = $DB->get_field('role', 'id', ['shortname' => 'teacher']);
        $isteacheranywhere = $DB->record_exists('role_assignments', ['userid' => $USER->id, 'roleid' => $roleidteacher]);

       

       
        
        foreach ($modinfo->get_section_info_all() as $section => $thissection) {

            
            $module = $module + 1;
            if ($section == 0) {
                continue;
            }
            if ($section > $course->numsections) {
                continue;
            }
            if ($course->hiddensections && !(int)$thissection->visible) {
                continue;
            }
            if (isset($course->{'moduletitle' . $currentdivisor}) &&
                $count > $course->{'moduletitle' . $currentdivisor}) {
                $currentdivisor++;
                $count = 1;
            }
            if (isset($course->{'moduletitle' . $currentdivisor}) &&
                $course->{'moduletitle' . $currentdivisor} != 0 &&
                !isset($divisorshow[$currentdivisor])) {
                $currentdivisorhtml = $course->{'moduletitle' . $currentdivisor};
                $currentdivisorhtml = str_replace('[br]', '<br>', $currentdivisorhtml);
                $currentdivisorhtml = html_writer::tag('div', $currentdivisorhtml, ['class' => 'moduletitle']);
                
                $divisorshow[$currentdivisor] = true;
            }
            $id = 'talentum-button-section-' . $section;
                    
            if (isset($course->{'moduletitle' . $currentdivisor}) &&
                $course->{'moduletitle' . $currentdivisor} == 1) {
                $name = (string)$course->{'moduletitle' . ($currentdivisor-1)};
                $desc = (string)$course->{'modulename' . ($currentdivisor-1)};

            } else {
                $name = (string)$course->{'moduletitle' . ($currentdivisor-1)};
                $desc = (string)$course->{'modulename' . ($currentdivisor-1)};

            }
            
            /**
             * Se abre la tarjeta  que permitirá visualizar la descripción de un módulo
             */
            

            if($module == 1){
                /**
                 * Se cerifica si es Profesor para que vea todos los modulos
                 */
                $classvisibleifnotteacher = '';
                $coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);


                if(($iseditingteacheranywhere || $isteacheranywhere) || has_capability('moodle/site:config', $coursecontext)){
                    $classvisibleifnotteacher = 'flex';
                }else{
                    $classvisibleifnotteacher = 'none';
                }
                $modulecardhtml .= html_writer::tag('h5','Módulo profesor',['class' => "talentum-title-dashboard", 'style'=>'display:'.$classvisibleifnotteacher.'']);

                $modulecardhtml .= html_writer::start_tag('div', ['class' => "col-md-12 p-0 d-flex"]);


                $modulecardhtml .= html_writer::start_tag('div', ['class' => "card talentum-card-module   col-md-4 ",'style'=>'display:'.$classvisibleifnotteacher.';margin-left: 0.2rem;margin-right: 0.5rem;', 'id'=>'talentum-card-module-'.$module]);
                
                $act = $percent[$section]->section;
                $user = $USER->id;
                $the_Activities = activities($course->id,$act,$user);
                //$sec++;
                $class = 'talentum-button-section';
                
                
                //div con las imagenes de los modulos
                $onclick = 'M.format_talentum.show(' . $section . ',' . $course->id . ')';   
                
                if (!$thissection->available && !empty($thissection->availableinfo)) {
                        $class .= ' sectionhidden';
                } 
                // elseif (!$thissection->uservisible || !$thissection->visible) {
                //     $class .= ' sectionhidden';
                //     $onclick = false;
                // }
                
                if ($sectionvisible == $section) {
                    $class .= ' sectionvisible';

                }
                if ($PAGE->user_is_editing()) {
                    $onclick = false;
                }

                

                $modulecardhtml .= html_writer::start_tag('div', ['class' => "col-md-12 p-0 talentum-module-images-dashboard"]);
                $modulecardhtml .= html_writer::start_tag('a', ['onclick' => $onclick]);
                $talentum_images_module_1 = 'format/talentum/img/M1.png';
                $img_Talentum_1 =  '<img src="' . $talentum_images_module_1  . '" id="module-image-1" />';
                $modulecardhtml .=  $img_Talentum_1;
                $modulecardhtml .= html_writer::end_tag('a');
                $modulecardhtml .= html_writer::end_tag('div');
                //fin del div con las imagenes de los modulos
                
                   
                    

                    
                     //$module_tittle = get_string('Modulo','format_talentum');
                    // $btn_ir = get_string('ir','format_talentum');
                    /* inicio del texto de las cards del modulo */
                    $modulecardhtml .= html_writer::start_tag('div', ['id' => $id, 'class' => "card-body talentum-card-module-body"]);
                    $cant_char_name = strlen($name);
                    if($cant_char_name > 30){
                        $modulecardhtml .= html_writer::start_tag('a', ['onclick' => $onclick]);
                        $modulecardhtml .= html_writer::tag('h5',substr($name, 0, 30).'...',['class' => "card-title"]);
                        $modulecardhtml .= html_writer::end_tag('a');
                    }
                    else{
                        $modulecardhtml .= html_writer::start_tag('a', ['onclick' => $onclick]);
                        $modulecardhtml .= html_writer::tag('h5',$name,['class' => "card-title"]);
                        $modulecardhtml .= html_writer::end_tag('a');
                    }
                    
                    $cant_char =strlen($desc);
                    if($cant_char > 50){
                        $modulecardhtml .= html_writer::tag('p',substr($desc, 0, 50).'...',['class' => "card-text"]);
                    }
                    else{
                        $modulecardhtml .= html_writer::tag('p',$desc,['class' => "card-text"]);
                    }
                    if(!is_String($the_Activities)){
                        $modulecardhtml .= html_writer::start_tag('div',['class' => "progress",'style'=>"border-radius:.25rem;"] );
                        if($the_Activities == 0){
                        $modulecardhtml .= html_writer::start_tag('div',['class'=>"progress-bar progress-bar-striped bg-success",'style'=>"width:100%;background-color: #e9ecef !important;color:#70787D"]);
                        }
                        else{
                        $modulecardhtml .= html_writer::start_tag('div',['class'=>"progress-bar  bg-success-bar",'style'=>"width:".$the_Activities."%;"]);
                        }
                        $modulecardhtml .= html_writer::end_tag('div');
                        $modulecardhtml .= html_writer::end_tag('div');
                        $modulecardhtml .= html_writer::tag('p',$the_Activities.'% '.get_string('completado','format_talentum'),['class'=>"progress-bar-p"]);
                        }
                    $modulecardhtml .= html_writer::end_tag('div');

                    
                    $modulecardhtml .= html_writer::end_tag('div');
                    $modulecardhtml .= html_writer::end_tag('div');
                    $cardscontainerhtml  .= $modulecardhtml;
            }else{
                
                if($module == 2){
                
                    $modulecardhtml .= html_writer::start_tag('div', ['class' => "col-md-12 d-flex mt-4"]);
                    $modulecardhtml .= html_writer::tag('h5','Módulos estudiantes',['class' => "talentum-title-dashboard"]);
                    $modulecardhtml .= html_writer::end_tag('div');
                }
                
                $modulecardhtml .= html_writer::start_tag('div', ['class' => "card talentum-card-module   col-md-3 ",'style'=>'display:flex;margin-left: 0.2rem;margin-right: 1.5rem;', 'id'=>'talentum-card-module-'.$module]);
            
            
                $act = $percent[$section]->section;
                $user = $USER->id;
                $the_Activities = activities($course->id,$act,$user);
                //$sec++;
                $class = 'talentum-button-section';
                $onclick = 'M.format_talentum.show(' . $section . ',' . $course->id . ')';   
    
                if (!$thissection->available &&
                    !empty($thissection->availableinfo)) {
                    $class .= ' sectionhidden';
                } elseif (!$thissection->uservisible || !$thissection->visible) {
                    $class .= ' sectionhidden';
                    $onclick = false;
                }
             
                if ($sectionvisible == $section) {
                    $class .= ' sectionvisible';
    
                }
                if ($PAGE->user_is_editing()) {
                    $onclick = false;
                }
                
                $modulecardhtml .= html_writer::start_tag('div', ['class' => "col-md-12 p-0 talentum-module-images-dashboard"]);
                $modulecardhtml .= html_writer::start_tag('a', ['onclick' => $onclick]);
                $talentum_images_modules = 'format/talentum/img/M'.$module.'.png';
                $img_Talentum_modules = '<img src="' . $talentum_images_modules  . '" id="module-image-'.$module.'" />';
                $modulecardhtml .= $img_Talentum_modules;
                $modulecardhtml .= html_writer::end_tag('a');
                $modulecardhtml .= html_writer::end_tag('div');

                //$module_tittle = get_string('Modulo','format_talentum');
                // $btn_ir = get_string('ir','format_talentum');
                /* inicio del texto de las cards del modulo */
                $modulecardhtml .= html_writer::start_tag('div', ['id' => $id, 'class' => "card-body talentum-card-module-body"]);
                $cant_char_name = strlen($name);
                if($cant_char_name > 30){
                    $modulecardhtml .= html_writer::start_tag('a', ['onclick' => $onclick]);
                    $modulecardhtml .= html_writer::tag('h5',substr($name, 0, 30).'...',['class' => "card-title"]);
                    $modulecardhtml .= html_writer::end_tag('a');
                }
                else{
                    $modulecardhtml .= html_writer::start_tag('a', ['onclick' => $onclick]);
                    $modulecardhtml .= html_writer::tag('h5',$name,['class' => "card-title"]);
                    $modulecardhtml .= html_writer::end_tag('a');
                }
                
                $cant_char =strlen($desc);
                if($cant_char > 50){
                    $modulecardhtml .= html_writer::tag('p',substr($desc, 0, 50).'...',['class' => "card-text"]);
                }
                else{
                    $modulecardhtml .= html_writer::tag('p',$desc,['class' => "card-text"]);
                }
                if(!is_String($the_Activities)){
                    $modulecardhtml .= html_writer::start_tag('div',['class' => "progress",'style'=>"border-radius:.25rem;"] );
                    if($the_Activities == 0){
                    $modulecardhtml .= html_writer::start_tag('div',['class'=>"progress-bar progress-bar-striped bg-success",'style'=>"width:100%;background-color: #e9ecef !important;color:#70787D"]);
                    }
                    else{
                    $modulecardhtml .= html_writer::start_tag('div',['class'=>"progress-bar  bg-success-bar",'style'=>"width:".$the_Activities."%;"]);
                    }
                    $modulecardhtml .= html_writer::end_tag('div');
                    $modulecardhtml .= html_writer::end_tag('div');
                    $modulecardhtml .= html_writer::tag('p',$the_Activities.'% '.get_string('completado','format_talentum'),['class'=>"progress-bar-p"]);
                    }
                $modulecardhtml .= html_writer::end_tag('div');
                $modulecardhtml .= html_writer::end_tag('div');
                
                $cardscontainerhtml  .= $modulecardhtml;
                
                
            }
            $modulecardhtml = '';
            $courseimage = '';
            $count++;
            $img++;
            
           
     
           
        }
        /**
         * Se cierra la construcción del contenedor de tarjetas
         */
        $cardscontainerhtml .= html_writer::end_tag('div');
        $cardscontainerhtml .= html_writer::end_tag('div');   
        $cardscontainerhtml .= html_writer::end_tag('div');
        // $container_home .= $videocontainerhtml;
        $container_home .= $cardscontainerhtml;     
        $html .= $container_home;
        
        /**
         * Se construye la barra de navegación para regresar desde una sección incluyendo el evento para regresar de una sección
         */
        foreach ($modinfo->get_section_info_all() as $section => $thissection) {

            $modules = $modules + 1;
            if ($section == 0) {
                continue;
            }
            if ($section > $course->numsections) {
                continue;
            }
            if ($course->hiddensections && !(int)$thissection->visible) {
                continue;
            }
           
            $containerModule .= html_writer::start_tag('div',['id' => 'container-'.$modules.'','style'=>'margin-top:2em']);

            

            $containerModule .= html_writer::end_tag('div'); 

            $course_btn_back_lang = get_string('back', 'format_talentum');
            $onbackclick = 'M.format_talentum.back(' . $course->id . ')';
            $backnavhtml .= html_writer::start_tag('nav', ['class' => "navbar sticky-top navbar-light talentum-back-navbar", 'id' => 'talentum-back-nav-'.$modules]);
            $backnavhtml .= html_writer::start_tag('a',  ['class' => 'btn btn-primary btn-sm talentum-back-nav', 'onclick' => $onbackclick]);
            $backnavhtml .= html_writer::tag('i', '', ['class' => 'fa fa-arrow-left']);
            $backnavhtml .= $course_btn_back_lang;
            $backnavhtml .= html_writer::end_tag('a');
            $talentum_images_activities = 'format/talentum/img/activities-image.jpg';
            $img_Talentum = '<img src="' . $talentum_images_activities . '" id="module-image" />';
            $backnavhtml .= $img_Talentum;
            $backnavhtml .= html_writer::tag('h5','Actividades modulo ',['class' => "module-title"]);
            
            $backnavhtml .= html_writer::end_tag('nav');
            
            
            $backnavhtml .= $containerModule;

            $divisorvideos++;
            $containerModule ='';
            $count1++;
            // }
           }
           /**
         * Se cierra la construcción de los contenedores internos
         */
           $html .= $backnavhtml;
           $html = html_writer::tag('div', $html, ['id' => 'talentum-container-section']);
           if ($PAGE->user_is_editing()) {
               $load = 'M.format_talentum.showmodule()';
               $html .= html_writer::tag('div', get_string('editing', 'format_talentum'), ['class' => 'alert alert-warning alert-block fade in']);
               
           }
        return $html;
    }

    /**
     * number_to_roman
     *
     * @param integer $number
     * @return string
     */
    protected function number_to_roman($number)
    {
        $number = intval($number);
        $return = '';
        $romanarray = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];
        foreach ($romanarray as $roman => $value) {
            $matches = intval($number / $value);
            $return .= str_repeat($roman, $matches);
            $number = $number % $value;
        }
        return $return;
    }

    /**
     * number_to_alphabet
     *
     * @param integer $number
     * @return string
     */
    protected function number_to_alphabet($number)
    {
        $number = $number - 1;
        $alphabet = range("A", "Z");
        if ($number <= 25) {
            return $alphabet[$number];
        } elseif ($number > 25) {
            $dividend = ($number + 1);
            $alpha = '';
            while ($dividend > 0) {
                $modulo = ($dividend - 1) % 26;
                $alpha = $alphabet[$modulo] . $alpha;
                $dividend = floor((($dividend - $modulo) / 26));
            }
            return $alpha;
        }
    }

    /**
     * start_section_list
     *
     * @return string
     */
    protected function start_section_list()
    {   
        global $PAGE;
        if ($PAGE->user_is_editing()) {
            $class_editing ='talentum-editing';
            return html_writer::start_tag('ul', ['class' => 'buttons row '.$class_editing. '','id'=>'rowinit']);
        }
        return html_writer::start_tag('ul', ['class' => 'buttons row talentum-no-editing','id'=>'rowinit']);
    }
    /**
     * section_header
     *
     * @param stdclass $section
     * @param stdclass $course
     * @param bool $onsectionpage
     * @param int $sectionreturn
     * @return string
     */
    protected function section_header($section, $course, $onsectionpage, $sectionreturn = null, $contenttext)
    {
        global $PAGE, $CFG;
        $o = '';
        $containerText  = '';
        $currenttext = '';
        $sectionstyle = '';
        
        if ($section->section != 0) {
            if (!$section->visible) {
                $sectionstyle = ' hidden';
            } elseif (course_get_format($course)->is_section_current($section)) {
                $sectionstyle = ' current';
            }
        }
        $o .= html_writer::start_tag('li', ['id' => 'section-'.$section->section,
        'class' => 'section main clearfix col-sm-12'.$sectionstyle,
        'role' => 'region', 'aria-label' => get_section_name($course, $section),'style'=>'display:block']);
        $o .= html_writer::tag('span', $this->section_title($section, $course), ['class' => 'hidden sectionname']);
        $leftcontent = $this->section_left_content($section, $course, $onsectionpage);
        $o .= html_writer::tag('div', $leftcontent, ['class' => 'left side']);
        $rightcontent = $this->section_right_content($section, $course, $onsectionpage);
        $o .= html_writer::tag('div', $rightcontent, ['class' => 'right side']);
        $o .= html_writer::start_tag('div', ['class' => 'content']);
        $hasnamenotsecpg = (!$onsectionpage && ($section->section != 0 || !is_null($section->name)));
        $hasnamesecpg = ($onsectionpage && ($section->section == 0 && !is_null($section->name)));
        $classes = ' accesshide';
        if ($hasnamenotsecpg || $hasnamesecpg) {
            $classes = '';
        }
        $sectionname = html_writer::tag('span', $this->section_title($section, $course));
        if ($course->showdefaultsectionname) {
            $o .= $this->output->heading($sectionname, 3, 'sectionname' . $classes);
        }       
        $o .= html_writer::start_tag('div', ['class' => 'summary','id'=>'summary','style'=>'display:none']);
        //if ((int)$section != 0) {
        if ($section->section != 0) {
            $o .= $contenttext;
        }
        $o .= $this->format_summary_text($section);
        $context = context_course::instance($course->id);
        $o .= html_writer::end_tag('div');
        $o .= $this->section_availability_message($section, has_capability('moodle/course:viewhiddensections', $context));
        return $o;
    }

    /**
     * print_multiple_section_page
     *
     * @param stdclass $course
     * @param array $sections (argument not used)
     * @param array $mods (argument not used)
     * @param array $modnames (argument not used)
     * @param array $modnamesused (argument not used)
     */
    public function print_multiple_section_page($course, $sections, $mods, $modnames, $modnamesused)
    {
        global $PAGE;
        $modinfo = get_fast_modinfo($course);
        $course = course_get_format($course)->get_course();
        $context = context_course::instance($course->id);
        $completioninfo = new completion_info($course);
        $divisorshow = false;
        $count1 = 1;
        $count2 = 1;
        $currentdivisor1 = 1;
        $currentdivisor2 = 1;
        if (isset($_COOKIE['sectionvisible_'.$course->id])) {
            $sectionvisible = $_COOKIE['sectionvisible_'.$course->id];
        } elseif ($course->marker > 0) {
            $sectionvisible = $course->marker;
        } else {
            $sectionvisible = 1;
        }
        $htmlsection = false;
        
        foreach ($modinfo->get_section_info_all() as $section => $thissection) {
            
            $htmlsection[$section+1] = '';
            $contenttext ='';
            if ($section == 0) {
                $section0 = $thissection;
                continue;
            }
            if ($section > $course->numsections) {
                continue;
            }
            //format text
            //nombre de los modulos
            if (isset($course->{'moduletitle' . $currentdivisor1}) &&
            $count1 > $course->{'moduletitle' . $currentdivisor1}) {
            $currentdivisor1++;
            $count1 = 1;
        }
        if (isset($course->{'moduletitle' . $currentdivisor1}) &&
            $course->{'moduletitle' . $currentdivisor1} != 0 &&
            !isset($divisorshow[$currentdivisor1])) {
            $currentdivisor1html = $course->{'moduletitle' . $currentdivisor1};
            $currentdivisor1html = str_replace('[br]', '<br>', $currentdivisor1html);
            $currentdivisor1html = html_writer::tag('div', $currentdivisor1html, ['class' => 'moduletitle']);
            
            $divisorshow[$currentdivisor1] = true;
        }
    
        if (isset($course->{'moduletitle' . $currentdivisor1}) &&
            $course->{'moduletitle' . $currentdivisor1} == 1) {
            $name = (string)$course->{'moduletitle' . ($currentdivisor1-1)};
            $desc = (string)$course->{'modulename' . ($currentdivisor1-1)};

        } else {
            $name = (string)$course->{'moduletitle' . ($currentdivisor1-1)};
            $desc = (string)$course->{'modulename' . ($currentdivisor1-1)};

        }
        //objetivo interno de los modulos
        // if (isset($course->{'moduleobjetive' . $currentdivisor2}) &&
        //     $count2 > $course->{'moduleobjetive' . $currentdivisor2}) {
        //     $currentdivisor2++;
        //     $count2 = 1;
        // }
        // if (isset($course->{'moduleobjetive' . $currentdivisor2}) &&
        //     $course->{'moduleobjetive' . $currentdivisor2} != 0 &&
        //     !isset($divisorshow[$currentdivisor2])) {
        //     $currentdivisor2html = $course->{'moduleobjetive' . $currentdivisor2};
        //     $currentdivisor2html = str_replace('[br]', '<br>', $currentdivisor2html);
        //     $currentdivisor2html = html_writer::tag('div', $currentdivisor2html, ['class' => 'moduleobjetive']);
            
        //     $divisorshow[$currentdivisor2] = true;
        // }
        // if (isset($course->{'moduleobjetive' . $currentdivisor2}) &&
        //     $course->{'moduleobjetive' . $currentdivisor2} == 1) {
        //     $nameobj = (string)$course->{'moduleobjetive' . ($currentdivisor2-1)};
        // } else {
        //     $nameobj = (string)$course->{'moduleobjetive' . ($currentdivisor2-1)};
        // }
        // $course_Desempeno_lang = get_string('desempeno', 'format_talentum');
        // $contenttext .= html_writer::start_tag('div',['style'=> 'margin-top:2em']);
        // $contenttext .= html_writer::tag('h2',$name,['class'=>'featurette-heading']);
        // $contenttext .= html_writer::tag('h3',$desc,['class'=>'featurette-heading']);
        // $contenttext .= html_writer::tag('h5',$course_Desempeno_lang);   
        // $contenttext .= html_writer::tag('p',$nameobj,['class'=>'lead']);
        // $contenttext .= html_writer::end_tag('div');

        $count1++;
        $count2++;
            /* if is not editing verify the rules to display the sections */
            if (!$PAGE->user_is_editing()) {
                if ($course->hiddensections && !(int)$thissection->visible) {
                    continue;
                }
                if (!$thissection->available && !empty($thissection->availableinfo)) {
                    $htmlsection[$section] .= $this->section_header($thissection, $course, false, 0, $contenttext);
                    continue;
                }
                if (!$thissection->uservisible || !$thissection->visible) {
                    $htmlsection[$section] .= $this->section_hidden($section, $course->id);
                    continue;
                }
            }
            
            $htmlsection[$section] .= $this->section_header($thissection, $course, false, 0, $contenttext);
            if ($thissection->uservisible) {
                $htmlsection[$section] .= $this->courserenderer->course_section_cm_list($course, $thissection, 0);
                $htmlsection[$section] .= $this->courserenderer->course_section_add_cm_control($course, $section, 0);
            }
            $htmlsection[$section] .= $this->section_footer();
        }
       // echo "<p id='charge'>".get_string('cargando', 'format_talentum')."</p>";
        echo "<div id='charge' class='lds-roller'><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>";
        
        if ($section0->summary || !empty($modinfo->sections[0]) || !$PAGE->user_is_editing()) {
            $htmlsection0 = $this->section_header($section0, $course, false, 0, $contenttext);
            $htmlsection0 .= $this->courserenderer->course_section_cm_list($course, $section0, 0);
            $htmlsection0 .= $this->courserenderer->course_section_add_cm_control($course, 0, 0);
            $htmlsection0 .= $this->section_footer();
        }
        echo $completioninfo->display_help_icon();
        echo $this->output->heading($this->page_title(), 2, 'accesshide');
        echo $this->course_activity_clipboard($course, 0);        
        echo $this->start_section_list();  
        echo $this->get_button_section($course, 0);
        echo html_writer::tag('span', $htmlsection0, ['class' => 'above','id'=>'above', 'style'=>'width:100%']);
        
        

        foreach ($htmlsection as $current) {
            echo $current;
        }

        if ($PAGE->user_is_editing() and has_capability('moodle/course:update', $context)) {

            echo html_writer::tag('style', '#rowinit { display: block !important; }');            
            echo html_writer::tag('style', '.course-content ul.buttons #section-'.$sectionvisible.' { display: block;flex: 0 0 100%;max-width: 100%;  }');
            echo html_writer::tag('style', '.course-content ul.buttons li.section.main { max-width: 100%; }');
            echo html_writer::tag('style', '#charge { display: none; }');
            echo html_writer::tag('style', '#summary { display: block !important; }');
            foreach ($modinfo->get_section_info_all() as $section => $thissection) {
                if ($section <= $course->numsections or empty($modinfo->sections[$section])) {
                    continue;
                }
                echo $this->stealth_section_header($section);
                echo $this->courserenderer->course_section_cm_list($course, $thissection, 0);
                echo $this->stealth_section_footer();
            }
            echo $this->end_section_list();
            echo html_writer::start_tag('div', ['id' => 'changenumsections', 'class' => 'mdl-right']);
            $straddsection = get_string('increasesections', 'moodle');
            $url = new moodle_url('/course/changenumsections.php', ['courseid' => $course->id,
                'increase' => true, 'sesskey' => sesskey()]);
            $icon = $this->output->pix_icon('t/switch_plus', $straddsection);
            echo html_writer::link($url, $icon.get_accesshide($straddsection), ['class' => 'increase-sections']);
            if ($course->numsections > 0) {
                $strremovesection = get_string('reducesections', 'moodle');
                $url = new moodle_url('/course/changenumsections.php', ['courseid' => $course->id,
                    'increase' => false, 'sesskey' => sesskey()]);
                $icon = $this->output->pix_icon('t/switch_minus', $strremovesection);
                echo html_writer::link(
                    $url,
                    $icon.get_accesshide($strremovesection),
                ['class' => 'reduce-sections']
                );
            }
            echo html_writer::end_tag('div');
        } else {
            echo $this->end_section_list();
        }
        echo html_writer::tag('style', '.course-content ul.buttons #section-'.$sectionvisible.' { display: none; }');
        echo html_writer::tag('style', '#completionprogressid { display: none; }');
        echo html_writer::tag('style', '#rowinit { display: none; }');

        if (!$PAGE->user_is_editing()) {
            $PAGE->requires->js_init_call('M.format_talentum.init', [$course->numsections,$course->id]);
            echo html_writer::tag('style', '#summary { display: block !important; }');

        }
    }
  


}