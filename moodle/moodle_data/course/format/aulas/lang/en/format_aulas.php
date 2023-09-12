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
 * aulas_Format_renderer
 *
 * @package    aulas_Format
 * @author     aulas
 * @copyright  aulas
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['modulename'] = 'Module Description - {$a}';
$string['modulename_help'] = 'Module Description - {$a} to contextualize the student <br> Example: Care and environment';
//-------//
$string['completado'] = 'Completed';
$string['cargando'] = 'Loading...';
$string['objetivo'] = 'Main objetive';
$string['proceso'] = 'Evaluative process';
$string['programa'] = 'Course Program';
$string['anuncios'] = 'Announcements';
$string['meetings'] = 'Synchronous encounters';
$string['doubts'] = 'Doubts and concerns';
$string['Modulo'] = 'Module';
$string['ir'] = 'Go';
$string['back'] = 'Back';
$string['desempeno'] = 'Expected performance';
$string['numsections']  = 'Select the number of modules in your course';
$string['Modulevideohome']  = 'Place the id of the youtube video';
$string['Modulevideohome_help']  = 'Id of the video that will appear at the beginning of the course, the id of youtube is that it appears in the url after v =';
$string['MainObjective']  = 'Main objective of the course';
$string['MainObjective_help']  = 'Write the general objective of the course';
$string['Coursecontent']  = 'Course program link';
$string['Coursecontent_help']  = 'Place the link of the course program PDF';
$string['Courseprocess']  = 'Link of the evaluation process';
$string['Courseprocess_help']  = 'Place the link of the PDF of the evaluation process';
$string['Forum']  = 'Ad forum button link';
$string['Forum_help']  = 'Place the ad forum button link';
$string['Meeting']  = 'Button link of the meetings';
$string['Meeting_help']  = 'Place the button link of the meetings';
$string['doubtstext']  = 'Link of the button of doubts';
$string['doubtstext_help']  = 'Place the link of the doubts button';
$string['moduletitle']  = 'Title of module - {$a}';
$string['moduletitle_help']  = 'Title of module - {$a} to help the student';
$string['moduleimage']  = 'Image link of module';
$string['moduleimage_help']  = 'Image link of module, this image is used in the container of the module';
$string['textselect']  = 'Link of the module video - {$a}';
$string['textselect_help']  = 'Link of the module video - {$a} that you want to appear in the module {$a}';
$string['modulevideo']  = 'Link of the module video - {$a}';
$string['modulevideo_help']  = 'Link of the module video - {$a} that you want to appear in the module {$a}';
$string['moduleobjetive']  = 'Objective of the module - {$a}';
$string['moduleobjetive_help']  = 'Objective of the module - {$a} that you want to appear in the module {$a}';
$string['pluginname'] = 'aulas Format';
$string['currentsection'] = 'This topic';
$string['editsection'] = 'Edit topic';
$string['deletesection'] = 'Delete topic';
$string['sectionname'] = 'Topic';
$string['section0name'] = 'General';
$string['hidefromothers'] = 'Hide topic';
$string['showfromothers'] = 'Show topic';
$string['showdefaultsectionname'] = 'Show the default sections name';
$string['showdefaultsectionname_help'] = 'If no name is set for the section will not show anything.<br>
By definition an unnamed topic is displayed as <strong>Topic [N]</strong>.';
$string['yes'] = 'Yes';
$string['no'] = 'No';
$string['sectionposition'] = 'Section zero position';
$string['sectionposition_help'] = 'The section 0 will appear together the visible section.<br><br>
<strong>Above the list buttons</strong><br>Use this option if you want to add some text or resource before the buttons list.
<i>Example: Define a picture to illustrate the course.</i><br><br><strong>Below the visible section</strong><br>
Use this option if you want to add some text or resource after the visible section.
<i>Example: Resources or links to be displayed regardless of the visible section.</i><br><br>';
$string['above'] = 'Above the list buttons';
$string['below'] = 'Below the visible section';
$string['divisor'] = 'Number of sections to group - {$a}';
$string['divisortext'] = 'Title of module - {$a}';
$string['divisortext_help'] = 'The grouping sections is used to separate section by type or modules.
<i>Example: The course has 10 sections divided into two modules: Theoretical (with 5 sections) and Practical (with 5 sections).<br>
Define the title like "Teorical" and set the number of sections to 5.</i><br><br>
Tip: if you want to use the tag <strong>&lt;br&gt;</strong>, type [br].';
$string['colorcurrent'] = 'Color of the current section button';
$string['colorcurrent_help'] = 'The current section is the section marked with highlight.<br>Define a color in hexadecimal.
<i>Example: #fab747</i><br>If you want to use the default color, leave empty.';
$string['colorvisible'] = 'Color of the visible section button';
$string['colorvisible_help'] = 'The visible section is the selected section.<br>Define a color in hexadecimal.
<i>Example: #747fab</i><br>If you want to use the default color, leave empty.';
$string['editing'] = 'The buttons are disabled while the edit mode is active.';
$string['sequential'] = 'Sequential';
$string['notsequentialdesc'] = 'Each new group begins counting sections from one.';
$string['sequentialdesc'] = 'Count the section numbers ignoring the grouping.';
$string['sectiontype'] = 'List style';
$string['numeric'] = 'Numeric';
$string['roman'] = 'Roman numerals';
$string['alphabet'] = 'Alphabet';
$string['buttonstyle'] = 'Button style';
$string['buttonstyle_help'] = 'Define the shape style of the buttons.';
$string['circle'] = 'Circle';
$string['square'] = 'Square';
$string['inlinesections'] = 'Inline sections';
$string['inlinesections_help'] = 'Give each section a new line.';
$string['moduleimg'] = 'Upload image of module - {$a}';


$string['button_modules'] = 'Start';