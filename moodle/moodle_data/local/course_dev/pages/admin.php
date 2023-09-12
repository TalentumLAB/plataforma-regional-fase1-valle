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
 * Muestra el contenido de la página
 *
 * @package   local_decalogo
 * @copyright 2021 su nombre
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../config.php');
require_once('../db/conection.php');
require_once($CFG->dirroot.'/user/externallib.php');



global $DB, $OUTPUT, $PAGE, $CFG, $USER;

require_login();

$isAdmin = is_siteadmin();



$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/course_dev/js/index.js'));
$PAGE->requires->css(new moodle_url($CFG->wwwroot.'/local/course_dev/css/styles.css'));

mb_internal_encoding('UTF-8');

// Definimos nuestra página
$PAGE->set_url('/local/course_dev/pages/admin.php');
$PAGE->set_pagelayout('standard');

$settings_config = (array)get_config('local_course_dev');
foreach ($settings_config as $configname => $configvalue) {
    if($configname == 'test')$text_title = $configvalue;
}

if(!$isAdmin){
    $urltogo= $CFG->wwwroot.'/my/';

    echo '<script>';
    echo 'alert("No tienes permiso para acceder a los reportes");';
    echo 'window.location.href = "'.$urltogo.'"';
    echo '</script>';
    // redirect($urltogo);
}
// Determinar si es la primera vez que se accede (insertar) o 
// no es la primera vez (desplegar y actualizar).


echo $OUTPUT->header();
echo html_writer::tag('h2',"Reportes moodle");
echo html_writer::tag('p',"Aqui se cargan los reportes a la bd externa");
echo html_writer::tag('p',$text_title);
echo html_writer::start_tag('button', array('value'=>'test','class'=>'btn  btn-light','id'=>'btn-reports'));
echo 'Cargar reporte';
echo html_writer::end_tag('button');

/**
 * Cargar estudiantes en una categoria
 */

echo html_writer::tag('hr','');
echo html_writer::start_tag('div',['class'=>"container-fluid"]);
echo html_writer::start_tag('div',['class'=>"row"]);
echo html_writer::start_tag('div',['class'=>"col-sm-6"]);
echo html_writer::tag('h2',"Cargar estudiantes en todos los cursos de una categoria");
echo html_writer::tag('p',"Esta opcion matricula los estudiantes en todos los cursos de la categoria (grado) que aparece en la bd de simat donde estan los indicadores");

/**
 * Get the cities
 */
$cities = "SELECT id,name FROM towns";
$res_cities = $conn->query($cities);

?>

<div class="">
    <form method="post" >
    <div class="local-course-dev-form-controller-container">
        <label for="">Selecciona la ciudad</label>
        <select name="cities" id="" class="form-select" onChange="selectCity(this);">
            <option 
            <?php if (!isset($_GET['city'])){?>  
            selected 
            <?php } ?> 
            disabled>
            Seleccione una ciudad para cargar los estudiantes</option>
            <?php
            if ($res_cities) {
                while ($row_c = $res_cities->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $row_c['id'] ?>"
                        <?php if (isset($_GET['city'])){
                            if ($_GET['city'] == $row_c['id']){
                                ?>  selected <?php
                            }
                        }?>
                        ><?php echo $row_c['name'] ?></option>
            <?php            
                }
            }
            ?>
        </select>
    </div>
    <?php 
    
    if (isset($_GET['city'])) { 
        /**
         * Get the headquarters
         */
        $headquartes = "SELECT id,name FROM headquarters WHERE town_id=".$_GET['city'];
        $res_headquarter = $conn->query($headquartes);
        ?>
    <div class="local-course-dev-form-controller-container">
        <select name="headquarters" id="" class="form-select" onChange="activeButton()">
            <option selected disabled>Selecciones una sede</option>
            <?php
            if ($res_headquarter) {
                while ($row_h = $res_headquarter->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $row_h['id'] ?> "><?php echo $row_h['name'] ?></option>
            <?php            
                }
            }
            ?>
        </select>
    </div>
    <?php
        }
        ?>
    <div class="local-course-dev-form-controller-container">
        <button type="submit" name="buttonLoadStudents" id="buttonLoadStudents" disabled class="local-course-dev-btn btn btn-success" >Cargar estudiantes</button>
    </div>
</form>
</div>


<?php
echo html_writer::end_tag('div');
$countedBad = 0;
$countedSuccess = 0;
if(isset($_POST['buttonLoadStudents'])) {
    /**
     * get headquarters_has_grade_has_group
     */
    $usersSimatHeadquarters = "SELECT id FROM headquarters_has_grade_has_group WHERE headquarters_id=".$_POST['headquarters'];

    $res_usersSimatHeadquarters = $conn->query($usersSimatHeadquarters);

    $users_before = array();

    if ($res_usersSimatHeadquarters) {
        while ($row_uSH = $res_usersSimatHeadquarters->fetch_assoc()) {
            /**
            * get headquarters_has_grade_has_group_simat
            */
            $usersSimatHeadquarters_uSimat = "SELECT student_id FROM headquarters_has_grade_has_group_has_user WHERE headquarters_has_grade_has_group_id =".$row_uSH['id'];

            $res_usersSimatHeadquarters_uSimat = $conn->query($usersSimatHeadquarters_uSimat);
            if ($res_usersSimatHeadquarters_uSimat) {
                while ($row_uSHUS = $res_usersSimatHeadquarters_uSimat->fetch_assoc()) {
                    /**
                     * get users
                     */
                    $users = "SELECT S.id, ST.simat_id, ST.id as student_id, ST.keycloak_sub_id as username,CONVERT(S.name USING utf8) as name, CONVERT(S.second_name USING utf8) as second_name, CONVERT(S.last_name USING utf8) as last_name, CONVERT(S.second_surname USING utf8) as second_surname FROM simat AS S JOIN students as ST WHERE ST.id=".$row_uSHUS['student_id']." AND ST.simat_id = S.id";

                    

                    $res_users = $conn->query($users);


                    if ($res_users) {
                        while ($row_users = $res_users->fetch_assoc()) {
                            
                            // createUsers($row_users['name'],$row_users['second_name'],$row_users['last_name'],$row_users['second_surname']);

                            $user_before = array(
                                'id' => $row_users['student_id'],
                                'name' => $row_users['name'],
                                'second_name' => $row_users['second_name'],
                                'last_name' => $row_users['last_name'],
                                'second_surname' => $row_users['second_surname'],
                                'username'=> $row_users['username']
                            );
                            array_push($users_before,$user_before);


                        }
                    }

                }
            }

        }
        createUsers($users_before, $conn);
    }
    
}

/** get all users */
// $sql1 = "SELECT u.id,u.firstname FROM mdl_user AS u JOIN mdl_user_enrolments AS uen where u.id = uen.userid";
// $users = $DB->get_records_sql($sql1); 
// var_dump($users);

// /** get all courses */
// $sql2 = "SELECT id FROM mdl_course";
// $courses = $DB->get_records_sql($sql2); 
// var_dump($courses);



function createUsers($users, $conn){
    global $DB, $OUTPUT, $PAGE, $CFG, $USER;


    $sql1 = "SELECT u.id,u.username,u.email FROM mdl_user AS u ";
    $usersmoodle = $DB->get_records_sql($sql1); 
    $actualsUsernames = array ();
    $actualsEmails = array ();
    $lastID;
    foreach($usersmoodle as $um){
        $username_m = $um->username;
        $lastID = $um->id;
        $emails_m = $um->email;
        array_push($actualsUsernames, $username_m);
        array_push($actualsEmails, $emails_m);
            
    }
     /**
     * create token to load users
     */
    $token = $DB->get_record_select("external_tokens", "externalserviceid in (select externalserviceid from {external_services_functions} where functionname = 'core_user_create_users') and userid in (select value from {config} where name = 'siteadmins')",null,'token',MUST_EXIST)->token; // premade admin user token
    $url = $CFG->wwwroot . "/webservice/rest/server.php?wstoken=$token&wsfunction=core_user_create_users&moodlewsrestformat=json";

    

    foreach ($users as $user) {
        $first_name = strtolower($user['name'].' '.$user['second_name']);

        $last_name = mb_strtolower($user['last_name'],'UTF-8').' '.mb_strtolower($user['second_surname'],'UTF-8');
    
        $username = strtolower($user['username']);
    
        // $email = strtolower($user['name']).$lastID.'@localhost.com';
        $email = $username.'@localhost.com';

        $userId = $user['id'];
        /** 
        * REPLACE SPECIAL CHARACTERS
        */
        // $username = utf8_encode($username);
        
        // $username = str_replace("?","n",$username);

        // $username = str_replace(
        //     array('ñ'),
        //     array('n'),
        //     $username
        // );

        // $username = str_replace(
        //     array('á', 'à', 'ä', 'â', 'ª'),
        //     array('a', 'a', 'a', 'a', 'a'),
        //     $username
        // );
        
        // $username = str_replace(
        //     array('é', 'è', 'ë', 'ê'),
        //     array('e', 'e', 'e', 'e'),
        //     $username 
        // );
        
        // $username = str_replace(
        //     array('í', 'ì', 'ï', 'î',),
        //     array('i', 'i', 'i', 'i',),
        //     $username 
        // );
        
        // $username = str_replace(
        //     array('ó', 'ò', 'ö', 'ô',),
        //     array('o', 'o', 'o', 'o',),
        //     $username 
        // );
        
        // $username = str_replace(
        //     array('ú', 'ù', 'ü', 'û',),
        //     array('u', 'u', 'u', 'u',),
        //     $username
        // );
            
            
        // if(in_array($username,$actualsUsernames)){
        //     if(!in_array($email, $actualsEmails)){
        //         $username = $username.'_'.mb_strtolower($user['second_surname'],'UTF-8');
        //         $email = strtolower($user['name']).$lastID.'@localhost.com';
        //         if(in_array($username,$actualsUsernames)){
        //             $username = strtolower($name).'_'.mb_strtolower(substr($user['second_surname'],0,-3),'UTF-8').'.'.strtolower($last_name);
        //             $email = strtolower($user['name']).$lastID.'@localhost.com';
        //         }
        //     }
        // }
            
        $user = array(
            'createpassword' => 1,
            'username' => $username,
            'firstname' => $first_name,
            'lastname' => $last_name,
            'email' => $email,
            'auth' => 'oidc',
        );

        $lastID++;
            
        $users = array($user);
        
        $params = array(
            'users[0][createpassword]' => 1, 
            'users[0][username]' => $username,
            'users[0][firstname]'=> $first_name,
            'users[0][lastname]'=> $last_name,
            'users[0][email]'=> $email,
            'users[0][auth]' => 'oidc'
            
        );

        

        $curl = new curl;
        $resp = $curl->post($url, $params);
        $clone = json_decode($resp);

        
        
        if(isset($clone->debuginfo)){
            // si entra aqui esta parte es por s se genera un error en la api de moodle para la insercion de usuario

            // $sqlUsersCourses = "SELECT g.name as gradeName, hgs.student_id as studentId FROM grades as g JOIN headquarters_has_grade_has_group as hg JOIN headquarters_has_grade_has_group_has_user as hgs WHERE (g.id = hg.grade_id AND hgs.headquarters_has_grade_has_group_id = hg.id) AND hgs.student_id =". $userId;
            // $res_sqlUsersCourses= $conn->query($sqlUsersCourses);
            
            // //obtengo la categoria de los cursos en donde se debe matricular el usuario
            // $categoryName;
            // foreach($res_sqlUsersCourses as $usercourse){
            //     $categoryName = $usercourse['gradeName'];
            // }
            // $countedSuccess += 1;
            
            /**
             * Obtengo la categoria por el nombre del simat
             */
            // $categoryByName = "SELECT id FROM mdl_course_categories WHERE name= '". $categoryName."'";
            // $res_categoryCourse = $DB->get_records_sql($categoryByName); 

            // $categoryID;
            // foreach($res_categoryCourse as $categoryCourse){
            //     $categoryID = $categoryCourse->id;
            // }   

            /**
             * Otengo los cursos pertenecientes a esa categoria
             */
            // $coursesByCategory = "SELECT * FROM `mdl_course` WHERE category= ". $categoryID."";
            // $res_coursesByCategory = $DB->get_records_sql($coursesByCategory); 

            
            // $userExist = "SELECT id FROM mdl_user WHERE username = '". $username."'";
            // $res_userExist = $DB->get_records_sql($userExist); 

            // foreach($res_userExist as $userE){
            //     $userExist_id = $userE->id;
            //     /**
            //     * Enroll usesr into a course
            //     */
            //     foreach($res_coursesByCategory as $courseByCategory){
            //         $courseNameCat = $courseByCategory->shortname;


            //         // $res_check = check_enrol($userExist_id, strtolower($courseNameCat));  //descomentar esta opcion cuando se tenga los cursos para poder ejecutar la matriculacion
            //     }

                
            // }
            $countedBad += 1;
        }else{

            $sqlUsersCourses = "SELECT g.name as gradeName, hgs.student_id as studentId FROM grades as g JOIN headquarters_has_grade_has_group as hg JOIN headquarters_has_grade_has_group_has_user as hgs WHERE (g.id = hg.grade_id AND hgs.headquarters_has_grade_has_group_id = hg.id) AND hgs.student_id =". $userId;
            $res_sqlUsersCourses= $conn->query($sqlUsersCourses);
            
            //obtengo la categoria de los cursos en donde se debe matricular el usuario
            $categoryName;
            foreach($res_sqlUsersCourses as $usercourse){
                $categoryName = $usercourse['gradeName'];
            }
            $countedSuccess += 1;
            
            /**
             * Obtengo la categoria por el nombre del simat
             */
            $categoryByName = "SELECT id FROM mdl_course_categories WHERE name= '". $categoryName."'";
            $res_categoryCourse = $DB->get_records_sql($categoryByName); 

            $categoryID;
            foreach($res_categoryCourse as $categoryCourse){
                $categoryID = $categoryCourse->id;
            }   

            /**
             * Otengo los cursos pertenecientes a esa categoria
             */
            $coursesByCategory = "SELECT * FROM `mdl_course` WHERE category= ". $categoryID."";
            $res_coursesByCategory = $DB->get_records_sql($coursesByCategory); 

            
           
            foreach($res_coursesByCategory as $courseByCategory){
                
                $courseNameCat = $courseByCategory->shortname;
                /**
                * Enroll usesr into a course
                */
                // check_enrol($clone[0]->id, strtolower($courseNameCat)); //descomentar esta opcion cuando se tenga los cursos para poder ejecutar la matriculacion
            }


            
        }

        // var_dump($clone[0]->id);
        

    }
    \core\notification::Success('Total usuarios creados: '. $countedSuccess);
    \core\notification::Error('Total usuarios existentes: '. $countedBad);

}

function check_enrol($userid, $shortname, $roleid = 5 , $enrolmethod = 'manual') {
    global $DB;
    $user = $DB->get_record('user', array('id' => $userid, 'deleted' => 0), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('shortname' => $shortname), '*', MUST_EXIST);
    if(!$course){
        return false;
    }
    $context = context_course::instance($course->id);
    if (!is_enrolled($context, $user)) {
        $enrol = enrol_get_plugin($enrolmethod);
        if ($enrol === null) {
            return false;
        }
        $instances = enrol_get_instances($course->id, true);
        $manualinstance = null;
        foreach ($instances as $instance) {
            if ($instance->name == $enrolmethod) {
                $manualinstance = $instance;
                break;
            }
        }
        if ($manualinstance !== null) {
            $instanceid = $enrol->add_default_instance($course);
            if ($instanceid === null) {
                $instanceid = $enrol->add_instance($course);
            }
            $instance = $DB->get_record('enrol', array('id' => $instanceid));
        }
        $enrol->enrol_user($instance, $userid, $roleid);
    }
    return true;
}


/**
 * Cargar estudiantes en todos los cursos
 */

 echo html_writer::start_tag('div',['class'=>"col-sm-6"]);

 echo html_writer::tag('h2',"Cargar estudiantes en todos los cursos de la plataforma");
 echo html_writer::tag('p',"Esta opcion es para cargar los estudiantes en todos los cursos que hay en la plataforma");
 
 /**
 * Get the cities
 */
$cities = "SELECT id,name FROM towns";
$res_cities = $conn->query($cities);

?>

<div class="">
    <form method="post" >
    <div class="local-course-dev-form-controller-container">
        <label for="">Selecciona la ciudad</label>
        <select name="cities" id="" class="form-select" onChange="selectCity(this);">
            <option 
            <?php if (!isset($_GET['city'])){?>  
            selected 
            <?php } ?> 
            disabled>
            Seleccione una ciudad para cargar los estudiantes</option>
            <?php
            if ($res_cities) {
                while ($row_c = $res_cities->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $row_c['id'] ?>"
                        <?php if (isset($_GET['city'])){
                            if ($_GET['city'] == $row_c['id']){
                                ?>  selected <?php
                            }
                        }?>
                        ><?php echo $row_c['name'] ?></option>
            <?php            
                }
            }
            ?>
        </select>
    </div>
    <?php 
    
    if (isset($_GET['city'])) { 
        /**
         * Get the headquarters
         */
        $headquartes = "SELECT id,name FROM headquarters WHERE town_id=".$_GET['city'];
        $res_headquarter = $conn->query($headquartes);
        ?>
    <div class="local-course-dev-form-controller-container">
        <select name="headquarters" id="" class="form-select" onChange="activeButtonAll()">
            <option selected disabled>Selecciones una sede</option>
            <?php
            if ($res_headquarter) {
                while ($row_h = $res_headquarter->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $row_h['id'] ?> "><?php echo $row_h['name'] ?></option>
            <?php            
                }
            }
            ?>
        </select>
    </div>
    <?php
        }
        ?>
    <div class="local-course-dev-form-controller-container">
        <button type="submit" name="buttonLoadStudentsAll" id="buttonLoadStudentsAll" disabled class="local-course-dev-btn btn btn-success" >Cargar estudiantes</button>
    </div>
</form>
</div>


<?php
echo html_writer::end_tag('div');
echo html_writer::end_tag('div');
echo html_writer::end_tag('div');
$countedBad = 0;
$countedSuccess = 0;
if(isset($_POST['buttonLoadStudentsAll'])) {
    /**
     * get headquarters_has_grade_has_group
     */
    $usersSimatHeadquarters = "SELECT id FROM headquarters_has_grade_has_group WHERE headquarters_id=".$_POST['headquarters'];

    $res_usersSimatHeadquarters = $conn->query($usersSimatHeadquarters);

    $users_before = array();

    if ($res_usersSimatHeadquarters) {
        while ($row_uSH = $res_usersSimatHeadquarters->fetch_assoc()) {
            /**
            * get headquarters_has_grade_has_group_simat
            */
            $usersSimatHeadquarters_uSimat = "SELECT student_id FROM headquarters_has_grade_has_group_has_user WHERE headquarters_has_grade_has_group_id =".$row_uSH['id'];

            $res_usersSimatHeadquarters_uSimat = $conn->query($usersSimatHeadquarters_uSimat);
            if ($res_usersSimatHeadquarters_uSimat) {
                while ($row_uSHUS = $res_usersSimatHeadquarters_uSimat->fetch_assoc()) {
                    /**
                     * get users
                     */
                    $users = "SELECT S.id, ST.simat_id, ST.id as student_id, ST.keycloak_sub_id as username,CONVERT(S.name USING utf8) as name, CONVERT(S.second_name USING utf8) as second_name, CONVERT(S.last_name USING utf8) as last_name, CONVERT(S.second_surname USING utf8) as second_surname FROM simat AS S JOIN students as ST WHERE ST.id=".$row_uSHUS['student_id']." AND ST.simat_id = S.id";

                    

                    $res_users = $conn->query($users);


                    if ($res_users) {
                        while ($row_users = $res_users->fetch_assoc()) {
                            
                            // createUsers($row_users['name'],$row_users['second_name'],$row_users['last_name'],$row_users['second_surname']);

                            $user_before = array(
                                'id' => $row_users['student_id'],
                                'name' => $row_users['name'],
                                'second_name' => $row_users['second_name'],
                                'last_name' => $row_users['last_name'],
                                'second_surname' => $row_users['second_surname'],
                                'username'=> $row_users['username']
                            );
                            array_push($users_before,$user_before);


                        }
                    }

                }
            }

        }
        createUsersAll($users_before, $conn);
    }
    
}

/** get all users */
// $sql1 = "SELECT u.id,u.firstname FROM mdl_user AS u JOIN mdl_user_enrolments AS uen where u.id = uen.userid";
// $users = $DB->get_records_sql($sql1); 
// var_dump($users);

// /** get all courses */
// $sql2 = "SELECT id FROM mdl_course";
// $courses = $DB->get_records_sql($sql2); 
// var_dump($courses);



function createUsersAll($users, $conn){
    global $DB, $OUTPUT, $PAGE, $CFG, $USER;


    $sql1 = "SELECT u.id,u.username,u.email FROM mdl_user AS u ";
    $usersmoodle = $DB->get_records_sql($sql1); 
    $actualsUsernames = array ();
    $actualsEmails = array ();
    $lastID;
    foreach($usersmoodle as $um){
        $username_m = $um->username;
        $lastID = $um->id;
        $emails_m = $um->email;
        array_push($actualsUsernames, $username_m);
        array_push($actualsEmails, $emails_m);
            
    }
     /**
     * create token to load users
     */
    $token = $DB->get_record_select("external_tokens", "externalserviceid in (select externalserviceid from {external_services_functions} where functionname = 'core_user_create_users') and userid in (select value from {config} where name = 'siteadmins')",null,'token',MUST_EXIST)->token; // premade admin user token
    $url = $CFG->wwwroot . "/webservice/rest/server.php?wstoken=$token&wsfunction=core_user_create_users&moodlewsrestformat=json";

    

    foreach ($users as $user) {
        $first_name = strtolower($user['name'].' '.$user['second_name']);

        $last_name = mb_strtolower($user['last_name'],'UTF-8').' '.mb_strtolower($user['second_surname'],'UTF-8');
    
        $username = strtolower($user['username']);
    
        // $email = strtolower($user['name']).$lastID.'@localhost.com';
        $email = $username.'@localhost.com';

        $userId = $user['id'];
        
        $user = array(
            'createpassword' => 1,
            'username' => $username,
            'firstname' => $first_name,
            'lastname' => $last_name,
            'email' => $email,
            'auth' => 'oidc',
        );

        $lastID++;
            
        $users = array($user);
        
        $params = array(
            'users[0][createpassword]' => 1, 
            'users[0][username]' => $username,
            'users[0][firstname]'=> $first_name,
            'users[0][lastname]'=> $last_name,
            'users[0][email]'=> $email,
            'users[0][auth]' => 'oidc'
            
        );

        

        $curl = new curl;
        $resp = $curl->post($url, $params);
        $clone = json_decode($resp);

        
        
        if(isset($clone->debuginfo)){
            // si entra aqui esta parte es por s se genera un error en la api de moodle para la insercion de usuario

            // $sqlUsersCourses = "SELECT g.name as gradeName, hgs.student_id as studentId FROM grades as g JOIN headquarters_has_grade_has_group as hg JOIN headquarters_has_grade_has_group_has_user as hgs WHERE (g.id = hg.grade_id AND hgs.headquarters_has_grade_has_group_id = hg.id) AND hgs.student_id =". $userId;
            // $res_sqlUsersCourses= $conn->query($sqlUsersCourses);
            
            /**
             * Otengo los cursos pertenecientes a esa categoria
             */
            // $coursesByCategory = "SELECT * FROM `mdl_course` WHERE category= ". $categoryID."";
            // $res_coursesByCategory = $DB->get_records_sql($coursesByCategory); 

            
            // $userExist = "SELECT id FROM mdl_user WHERE username = '". $username."'";
            // $res_userExist = $DB->get_records_sql($userExist); 

            // foreach($res_userExist as $userE){
            //     $userExist_id = $userE->id;
            //     /**
            //     * Enroll usesr into a course
            //     */
            //     foreach($res_coursesByCategory as $courseByCategory){
            //         $courseNameCat = $courseByCategory->shortname;


            //         // $res_check = check_enrol($userExist_id, strtolower($courseNameCat));  //descomentar esta opcion cuando se tenga los cursos para poder ejecutar la matriculacion
            //     }

                
            // }
            $countedBad += 1;
        }else{

            $sqlUsersCourses = "SELECT g.name as gradeName, hgs.student_id as studentId FROM grades as g JOIN headquarters_has_grade_has_group as hg JOIN headquarters_has_grade_has_group_has_user as hgs WHERE (g.id = hg.grade_id AND hgs.headquarters_has_grade_has_group_id = hg.id) AND hgs.student_id =". $userId;
            $res_sqlUsersCourses= $conn->query($sqlUsersCourses);
            
            /**
             * Otengo los cursos pertenecientes a esa categoria
             */
            $coursesByCategory = "SELECT * FROM `mdl_course` WHERE id != 1";
            $res_coursesByCategory = $DB->get_records_sql($coursesByCategory); 

            
           
            foreach($res_coursesByCategory as $courseByCategory){
                
                $courseNameCat = $courseByCategory->shortname;
                /**
                * Enroll usesr into a course
                */
                // check_enrol_all($clone[0]->id, strtolower($courseNameCat)); //descomentar esta opcion cuando se tenga los cursos para poder ejecutar la matriculacion
            }


            
        }

        // var_dump($clone[0]->id);
        

    }
    \core\notification::Success('Total usuarios creados: '. $countedSuccess);
    \core\notification::Error('Total usuarios existentes: '. $countedBad);

}

function check_enrol_all($userid, $shortname, $roleid = 5 , $enrolmethod = 'manual') {
    global $DB;
    $user = $DB->get_record('user', array('id' => $userid, 'deleted' => 0), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('shortname' => $shortname), '*', MUST_EXIST);
    if(!$course){
        return false;
    }
    $context = context_course::instance($course->id);
    if (!is_enrolled($context, $user)) {
        $enrol = enrol_get_plugin($enrolmethod);
        if ($enrol === null) {
            return false;
        }
        $instances = enrol_get_instances($course->id, true);
        $manualinstance = null;
        foreach ($instances as $instance) {
            if ($instance->name == $enrolmethod) {
                $manualinstance = $instance;
                break;
            }
        }
        if ($manualinstance !== null) {
            $instanceid = $enrol->add_default_instance($course);
            if ($instanceid === null) {
                $instanceid = $enrol->add_instance($course);
            }
            $instance = $DB->get_record('enrol', array('id' => $instanceid));
        }
        $enrol->enrol_user($instance, $userid, $roleid);
    }
    return true;
}


/**
 * 
 * Duplicate courses
 */

echo html_writer::tag('hr','');

echo html_writer::tag('h2',"Reiniciar cursos");
echo html_writer::tag('p',"Esta opcion es para reiniciar todos los cursos a su estado inicial solo con contenidos");

?>

<div class="local-course-dev-form-container">
    <form method="post" >
        <div class="local-course-dev-form-controller-container">
            <!-- <button type="submit" name="buttonLoadCourses" id="buttonLoadCourses" class="local-course-dev-btn btn btn-info" >Reiniciar Cursos</button> -->
            <button type="button" class="local-course-dev-btn btn btn-info" >Reiniciar Cursos</button>
        </div>
    </form>
</div>
<?php 
// $countedCoursesBad = 0;
// $countedCoursesSuccess = 0;
// if(isset($_POST['buttonLoadCourses'])) {

//     $getAllCategories ="SELECT id,name FROM mdl_course_categories";
//     $res_getAllCategories = $DB->get_records_sql($getAllCategories);
//     foreach($res_getAllCategories as $coursesCat){
//         //se crrea una categoria nueva
//     $catNameInsert = createCategories($coursesCat->name,$coursesCat->id);
//     if($catNameInsert == ''){
//         continue;
//     }
    
//     $catNameInsertID = "SELECT id FROM `mdl_course_categories` WHERE name='".$catNameInsert."'";
//     $res_getCatNameID = $DB->get_records_sql($catNameInsertID); 
//     $catID_;
//     foreach($res_getCatNameID as $catID){
//         $catID_ = $catID->id;
//     }
    
//     $getAllCourses = "SELECT course.id,course.fullname FROM mdl_course as course JOIN mdl_course_categories as coursecat WHERE coursecat.name = '".$coursesCat->name."' AND course.category = coursecat.id";

//     $res_getAllCourses = $DB->get_records_sql($getAllCourses); 


//      /**
//      * create token to duplicate course
//      */
//     $token = $DB->get_record_select("external_tokens", "externalserviceid in (select externalserviceid from {external_services_functions} where functionname = 'core_course_duplicate_course') and userid in (select value from {config} where name = 'siteadmins')",null,'token',MUST_EXIST)->token; // premade admin user token
//     $url = $CFG->wwwroot . "/webservice/rest/server.php?wstoken=$token&wsfunction=core_course_duplicate_course&moodlewsrestformat=json";

    
    
//     foreach($res_getAllCourses as $coursesAE){
        
//         $courseId = $coursesAE->id;
//         $courseName = $coursesAE->fullname;
//         /**
//          * Duplicate course
//          * 
//          */
//         $name = $courseName." Duplicated";
//         $courseid = $courseId;
//         $fullname = $name.' '.date('M d, Y H:i:s');
//         $shortname = $name.' '.rand(1000,1000000);

        

//         $params = array(
//         'courseid' => $courseid, // Course id to be duplicated 
//         'fullname' => $fullname, // New course full name
//         'shortname' => $shortname, // New course shortname
//         'categoryid' => $catID_, // New course category id 
//         'visible' => 1, // Make the course visible after duplicating
//         );

        

//         $curl = new curl;
//         $resp = $curl->post($url, $params);
//         $clone = json_decode($resp);
        
//         if(isset($clone->debuginfo)){
//             \core\notification::Success('ocurrio un error: '. $clone->debuginfo);
//             $countedCoursesBad += 1;
//         }else{
//             $countedCoursesSuccess += 1;

//         }

//         // unenrollUsers($courseId);
//     }
//     }
//     \core\notification::Error('Total de cursos no duplicados: '. $countedCoursesBad);
//     \core\notification::Success('Total de cursos duplicados: '. $countedCoursesSuccess);
// }

/*
*
* Create category  
* 
*/
// function createCategories($category,$categoryId){
//     global $DB, $CFG;

//     $getAllCategories ="SELECT id,name FROM mdl_course_categories";
//     $res_getAllCategories = $DB->get_records_sql($getAllCategories);
    
//     $token = $DB->get_record_select("external_tokens", "externalserviceid in (select externalserviceid from {external_services_functions} where functionname = 'core_course_create_categories') and userid in (select value from {config} where name = 'siteadmins')",null,'token',MUST_EXIST)->token; // premade admin user token
//     $url = $CFG->wwwroot . "/webservice/rest/server.php?wstoken=$token&wsfunction=core_course_create_categories&moodlewsrestformat=json";

//     $catNameN;
//     $catIdN;
//     $catName = $category;
//     $catId = $categoryId;
    
    
    
//         if($catName == 'courses'){
//             $catNameN = $catName;
//             $catIdN = $catId;
//         }
        
//     if(strpos($category, "_")){
//         return '';
//     }
//     if (isset($catIdN)) {
//         return $catIdN;
//     }else{
//             $params= array(
//                 'categories[0][name]' => $category.'_', // Name of category
//             );
//             $curl = new curl;
//             $resp = $curl->post($url, $params);
//             $clone = json_decode($resp);
                    
//             if(isset($clone->debuginfo)){
//                 \core\notification::Error('ocurrio un error: '. $clone->debuginfo);
//             }else{
//                 \core\notification::Success('Se creo la categoria: '.$clone[0]->name);
//                 return $clone[0]->name;
//             }
            
//     }


    
// }

/**
 * 
 * Unenroll users of seed courses
 */
// function unenrollUsers($courseid){
//     global $DB, $CFG;


//     $context = context_course::instance($courseid);
//     $userEn = get_enrolled_users( $context);

//      /**
//      * create token to unenroll users
//      */
//     $token = $DB->get_record_select("external_tokens", "externalserviceid in (select externalserviceid from {external_services_functions} where functionname = 'enrol_manual_unenrol_users') and userid in (select value from {config} where name = 'siteadmins')",null,'token',MUST_EXIST)->token; // premade admin user token
//     $url = $CFG->wwwroot . "/webservice/rest/server.php?wstoken=$token&wsfunction=enrol_manual_unenrol_users&moodlewsrestformat=json";

//     foreach($userEn as $userUnenroolId){
//        $uid = $userUnenroolId->id;
       
//        $params = array(
//         'enrolments[0][userid]' => $uid, // user id to be unenroll
//         'enrolments[0][courseid]' => $courseid, // course id to be unenroll users
//         );

//         $curl = new curl;
//         $resp = $curl->post($url, $params);
//         $clone = json_decode($resp);
        
//     }

    

//     return true;
// }

/**
 * 
 * Delete courses
 */

// echo html_writer::tag('hr','');

// echo html_writer::tag('h2',"Eliminar cursos");
// echo html_writer::tag('p',"Esta opcion es para todos los cursos a excepcion de los semilla");

?>

<!-- <div class="local-course-dev-form-container">
    <form method="post" >
        <div class="local-course-dev-form-controller-container">
            <button type="submit" name="buttonDeleteCourses" id="buttonDeleteCourses" class="local-course-dev-btn btn btn-danger" >Eliminar Cursos </button>
        </div>
    </form>
</div> -->

<?php
// $countedCoursesDeleteBad = 0;
// $countedCoursesDeleteSuccess = 0;
// if(isset($_POST['buttonDeleteCourses'])) {
//     global $DB, $CFG;
    
//     $getCoursesCategory ="SELECT id,name FROM mdl_course_categories WHERE name='courses'";
//     $res_getCoursesCategory = $DB->get_records_sql($getCoursesCategory); 

//     $token = $DB->get_record_select("external_tokens", "externalserviceid in (select externalserviceid from {external_services_functions} where functionname = 'core_course_delete_categories') and userid in (select value from {config} where name = 'siteadmins')",null,'token',MUST_EXIST)->token; // premade admin user token
//     $url = $CFG->wwwroot . "/webservice/rest/server.php?wstoken=$token&wsfunction=core_course_delete_categories&moodlewsrestformat=json";

//     $idCAtegory;
//     foreach($res_getCoursesCategory as $coursesCategory){
//         $idCAtegory = $coursesCategory->id;
//     }

//     $params= array(
//         'categories[0][id]' => $idCAtegory, // Name off category
//         'categories[0][recursive]' => 1 //deleta all content inside category
//     );

//     $curl = new curl;
//     $resp = $curl->post($url, $params);
//     $clone = json_decode($resp);
            
//     if(isset($clone->debuginfo)){
//         \core\notification::Error('ocurrio un error: '. $clone->debuginfo . 'posiblemente no exista la categoria a eliminar');
//     }else{
//         \core\notification::Success('Se Eliminaron los cursos');
//     }
// }



echo $OUTPUT->footer();


