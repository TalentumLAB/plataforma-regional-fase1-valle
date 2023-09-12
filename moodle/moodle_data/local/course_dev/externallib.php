<?php
/**
 * course_dev external file
 *
 * @package    local_course_dev
 * @category   external
 * @copyright  2022 talentum@andresrobin
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/externallib.php');




class local_course_dev_external extends external_api {

    
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function insertRecord_parameters() {
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_RAW,VALUE_OPTIONAL, 'The item id to operate on'),
            )    
        );
    }
    /**
     * 
     * @return external_description
     */
    public static function insertRecord_returns() {
        return new external_multiple_structure(
                new external_single_structure(
                    array(
                         'message' => new external_value(PARAM_RAW, 'insert response'),
                    ), 'message'
                )
        );
    }

    public static function insertRecord() {
        
        $Messages= array();
        $Message = array();
        $local_course_dev_external = new local_course_dev_external;

        // /** Obtener promedio de notas de usuario por curso */
        $get_grade_point_average = $local_course_dev_external->get_grade_point_average();
        // /** Fin del indicador de Promedio de calificaciones por usuario */
        
        // /** Obtener promedio de notas por curso general*/
        // $get_grade_point_averagec = $local_course_dev_external->get_grade_point_averagec();
        // /** Fin del indicador de Promedio de calificaciones por curso general */

        // /** Obtener Estudiantes que no se han conectado*/
        $get_last_signin = $local_course_dev_external->get_last_signin();
        // /** Fin del indicador de Estudiantes que no se han conectado*/

        // /** Indicador Cantidad de estudiantes por intitucion */
        // $get_quantity_students = $local_course_dev_external->get_quantity_students();
        // /** Fin del indicador de Estudiantes que no se han conectado*/
        
        // /** Indicador Cantidad de cursos por intitucion */
        // $get_quantity_courses = $local_course_dev_external->get_quantity_courses();
        // /** Fin del indicador de Cantidad de cursos por intitucion*/
        
        // /** Indicador Cantidad de Actividades vistas por estudiante */
        // $get_activities_views = $local_course_dev_external->get_activities_views();
        // /** Fin del indicador de Actividades vistas por estudiante*/
        
        // /** Indicador Total de tareas por curso*/
        // $get_count_of_activities = $local_course_dev_external->get_count_of_activities();
        // /** Fin del indicador de Total de tareas por curso*/
        
        // /** Indicador Progreso del curso por estudiante*/
        // $get_global_progress =  $local_course_dev_external->get_global_progress();
        // /** Fin del indicador de Progreso del curso por estudiante*/

        /** Indicador Progreso del curso general*/
        $get_global_c_progress = $local_course_dev_external->get_global_c_progress();
        /** Fin del indicador de Progreso del curso general*/



        // $Message['message']="Se cargo el reporte";
        array_push($Message, $get_grade_point_average[0]);
        array_push($Message, $get_last_signin[0]);
        array_push($Message, $get_global_c_progress[0]);
        // var_dump($Message);
        // $Message['message'] = $get_grade_point_average[0];
        // $Messages['messages']=$Message;
        return $Message;
    }

    public function get_grade_point_average(){
        require('db/conection.php');
        global $DB, $CFG;

         /** 1. Obtener id de los cursos */
         $grade_point_average_getCourses = "SELECT id FROM mdl_course WHERE id != 1";
         $grade_point_average_courses = $DB->get_records_sql($grade_point_average_getCourses);
 
         
         /** 2. obtener los items de calificacion de los cursos */
         foreach($grade_point_average_courses as $grade_point_average_cdb){
             $grade_point_average_getItemsGrades = "SELECT id FROM mdl_grade_items WHERE courseid = $grade_point_average_cdb->id AND itemtype='course'";
             $grade_point_average_itemsGrades = $DB->get_records_sql($grade_point_average_getItemsGrades);
             if(empty($grade_point_average_itemsGrades)){
                 continue;
             }else{
                 /** 3. obtener la calificacion final del curso por usuario */
                     $grade_point_average_getGrades = "SELECT finalgrade,userid FROM mdl_grade_grades WHERE itemid = 1";
                     $grade_point_average_grades = $DB->get_records_sql($grade_point_average_getGrades);
 
                     /** 4. Obtener el email o username del usuario para compararlo con la bd de simat */
                     foreach($grade_point_average_grades as $grade_point_average_gdb){
 
                         $grade_point_average_getUserGrade = "SELECT username FROM mdl_user WHERE id = $grade_point_average_gdb->userid";
                         $grade_point_average_userGrade = $DB->get_records_sql($grade_point_average_getUserGrade);
                         /** 5. obtener id de simat de acuerdo a su usuario e insertar el dato en la tabla de indicadores */
                         foreach($grade_point_average_userGrade as $grade_point_average_ugdb){
 
                             $grade_point_average_userDocument = explode("_", $grade_point_average_ugdb->username);
                             
                             
                             if (!is_numeric($grade_point_average_userDocument[1])) {
                                 continue;
                             }
                             
                             $grade_point_average_getUserIdSimat ="SELECT id FROM students WHERE keycloak_sub_id=".$grade_point_average_userDocument[1]."";
                             
                             if ($grade_point_average_resultUserIdSimat = $conn->query($grade_point_average_getUserIdSimat)) {
                                 while($grade_point_average_userIdSimat = $grade_point_average_resultUserIdSimat->fetch_object()){
 
                                     /** 6. Insertar el resultado en la tabla de indicadores */
 
                                     $grade_point_average_getUserIfExist ="SELECT user_id FROM student_indicators WHERE indicator_id=5 AND user_id=".$grade_point_average_userIdSimat->id."";
 
                                     if ($grade_point_average_resultUserIfExist = $conn->query($grade_point_average_getUserIfExist)) {
 
                                         if($grade_point_average_resultUserIfExist->num_rows == 0){
                                             $grade_point_average_insertLastAccess = "INSERT INTO student_indicators (`student_id`, `indicator_id`, `calc_indicator`) VALUES(".$grade_point_average_userIdSimat->id.",5,'".$grade_point_average_gdb->finalgrade."')";
                                             $grade_point_average_Messages = array();
                                             $grade_point_average_Message = array();
                                             if ($conn->query($grade_point_average_insertLastAccess) === TRUE) {
                                                 $grade_point_average_Message['message']= "get_grade_point_average / Se inserto el indicador de Promedio de notas por curso";
                                             } else {
                                                 $grade_point_average_Message['message']="get_grade_point_average / Error en la insercion";
                                             }
                                         }
                                         else{
                                             
                                             while($grade_point_average_userIfExist = $grade_point_average_resultUserIfExist->fetch_object()){
                                                 
                                                 
                                                 if($grade_point_average_userIfExist->user_id){
                                                     
                                                     $grade_point_average_updateLastAccess = "UPDATE student_indicators SET  `calc_indicator`= '".$grade_point_average_gdb->finalgrade."' WHERE `student_id`=".$grade_point_average_userIdSimat->id." AND indicator_id=5";
                                                     $grade_point_average_Messages = array();
                                                     $grade_point_average_Message = array();
                                                     if ($conn->query($grade_point_average_updateLastAccess) === TRUE) {
                                                         $grade_point_average_Message['message']= "get_grade_point_average / Se Actualizo el indicador de last login";
                                                     } else {
                                                         $grade_point_average_Message['message']="get_grade_point_average / Error en la actualizacion";
                                                     }
                                                 }
                                                 else{
                                                     $grade_point_average_insertLastAccess = "INSERT INTO student_indicators (`student_id`, `indicator_id`, `calc_indicator`) VALUES(".$grade_point_average_userIdSimat->id.",5,'".$grade_point_average_gdb->finalgrade."')";
                                                     $grade_point_average_Messages = array();
                                                     $grade_point_average_Message = array();
                                                     if ($conn->query($grade_point_average_insertLastAccess) === TRUE) {
                                                         $grade_point_average_Message['message']= "get_grade_point_average / Se inserto el indicador de Promedio de notas por curso";
                                                     } else {
                                                         $grade_point_average_Message['message']="get_grade_point_average / Error en la insercion";
                                                     }
                                                 }
                                                 $grade_point_average_Messages[]=$grade_point_average_Message;
                                             }
                                         }
                                             
                                             
                                     }      
                                 }
                                 
                             } else {
                                 $Message['message']="get_grade_point_average / Ocurrio un error encontrando el usuario";
                                 $Messages[]=$Message;
                                 return $Messages;
                             }
                         }
                     }
                     $Message['message']="get_grade_point_average / No hay calificaciones hasta el momento";

                     $Messages[]=$Message;
                     return $Messages;
             }
         }

         $Message['message']="get_grade_point_average / Ocurrio un error ningun curso tiene calificaciones hasta el momento";

         $Messages[]=$Message;                      
         return $Messages;
    }


    public function get_last_signin(){
        require('db/conection.php');
        global $DB, $CFG;

         /** 1. Obtener id del rol de moodle */
            /** 2. Obtener user id y enrol id para saber que usuarios son estudiantes */
            $last_signin_getMoodleUserStudent = "SELECT id,enrolid,userid FROM mdl_user_enrolments";
            $last_signin_moodleUserStudent = $DB->get_records_sql($last_signin_getMoodleUserStudent);
            foreach($last_signin_moodleUserStudent as $last_signin_musdb){
                $last_signin_enrolId = $last_signin_musdb->enrolid;
                $last_signin_userId = $last_signin_musdb->userid;
                
                /** 3. obtener el curso en donde el rol sea estudiante */
                $last_signin_getCourseRolStudent = "SELECT id FROM mdl_enrol WHERE roleid = 5";
                $last_signin_courseRolStudent = $DB->get_records_sql($last_signin_getCourseRolStudent);
                
                foreach($last_signin_courseRolStudent as $last_signin_crsdb){

                    /** 4. Verificamos si es un estudiante */
                    if($last_signin_enrolId == $last_signin_crsdb->id){
                        /** 5. obtener el ultimo acceso del estudiante */
                        $last_signin_getUserLastAccess = "SELECT lastaccess FROM mdl_user WHERE id = ".$last_signin_userId."";
                        $last_signin_userLastAccess = $DB->get_records_sql($last_signin_getUserLastAccess);

                        foreach($last_signin_userLastAccess as $last_signin_uladb){

                            // preguntar a fabian como se colocaria el indicador ya que son estudiantes que no se han conectado en un determinado tiempo

                            
                            
                            if($last_signin_uladb->last_signin_lastaccess == 0){
                                /** 6. insertar el indicador en la tabla donde nunca se ha conectado */
                                $last_signin_dateToIsert = strtotime($last_signin_uladb->lastaccess);
                                $last_signin_dateToIsertIndicator = date('d/m/Y', $last_signin_dateToIsert);
                                
                                $last_signin_getUserToInsertDate = "SELECT username FROM mdl_user WHERE id = $last_signin_userId";
                                $last_signin_userToInsertDate = $DB->get_records_sql($last_signin_getUserToInsertDate);
                                foreach($last_signin_userToInsertDate as $last_signin_utiddb){
                                    $last_signin_userDocumentToInsertDate = explode("_", $last_signin_utiddb->username);
                                    
                                    if (!is_numeric($last_signin_userDocumentToInsertDate[1])) {
                                        continue;
                                    }
                                    
                                    $last_signin_getUserIdSimatToInsertDate ="SELECT id FROM students WHERE keycloak_sub_id=".$last_signin_userDocumentToInsertDate[1]."";
                                    
                                    
                                    if ($last_signin_resultUserIdSimatToInsertDate = $conn->query($last_signin_getUserIdSimatToInsertDate)) {

                                        while($last_signin_userIdSimatToInsertDate = $last_signin_resultUserIdSimatToInsertDate->fetch_object()){
                                            
                                        
                                        /** 6. Insertar el resultado en la tabla de indicadores */

                                        $last_signin_getUserIfExist ="SELECT user_id FROM student_indicators WHERE indicator_id=2 AND user_id=".$last_signin_userIdSimatToInsertDate->id."";

                                        if ($last_signin_resultUserIfExist = $conn->query($last_signin_getUserIfExist)) {

                                            if($last_signin_resultUserIfExist->num_rows == 0){
                                                $last_signin_insertLastAccess = "INSERT INTO student_indicators (`student_id`, `indicator_id`, `calc_indicator`) VALUES(".$last_signin_userIdSimatToInsertDate->id.",2,'".$last_signin_dateToIsertIndicator."')";
                                                $last_signin_Messages = array();
                                                $last_signin_Message = array();
                                                if ($conn->query($last_signin_insertLastAccess) === TRUE) {
                                                    $last_signin_Message['message']= "get_last_signin / Se inserto el indicador de Promedio de notas por curso";
                                                } else {
                                                    $last_signin_Message['message']="get_last_signin / Error en la insercion";
                                                }
                                            }
                                            else{
                                                
                                                while($last_signin_userIfExist = $last_signin_resultUserIfExist->fetch_object()){
                                                    
                                                    
                                                    if($last_signin_userIfExist->user_id){
                                                        
                                                        $last_signin_updateLastAccess = "UPDATE student_indicators SET  `calc_indicator`= '".$last_signin_dateToIsertIndicator."' WHERE `student_id`=".$last_signin_userIdSimatToInsertDate->id." AND indicator_id=2";
                                                        $last_signin_Messages = array();
                                                        $last_signin_Message = array();
                                                        if ($conn->query($last_signin_updateLastAccess) === TRUE) {
                                                            $last_signin_Message['message']= "get_last_signin / Se Actualizo el indicador de last login";
                                                        } else {
                                                            $last_signin_Message['message']="get_last_signin / Error en la actualizacion";
                                                        }
                                                    }
                                                    else{
                                                        $last_signin_insertLastAccess = "INSERT INTO student_indicators (`student_id`, `indicator_id`, `calc_indicator`) VALUES(".$last_signin_userIdSimatToInsertDate->id.",2,'".$last_signin_dateToIsertIndicator."')";
                                                        $last_signin_Messages = array();
                                                        $last_signin_Message = array();
                                                        if ($conn->query($last_signin_insertLastAccess) === TRUE) {
                                                            $last_signin_Message['message']= "get_last_signin / Se inserto el indicador de Promedio de notas por curso";
                                                        } else {
                                                            $last_signin_Message['message']="get_last_signin / Error en la insercion";
                                                        }
                                                    }
                                                    $last_signin_Messages[]=$last_signin_Message;
                                                }
                                            }
                                                
                                                
                                        }                 
                                        }
                                                            
                                    } 
                                    
                                    
                                }
                            }
                        }
                    }
                }
                $last_signin_Message['message']="get_last_signin / Error en la insercion no se encuentran estudiantes";
                $last_signin_Messages[]=$last_signin_Message;
                return $last_signin_Messages;
        }
        $last_signin_Message['message']="get_last_signin / Error en la insercion";
        $last_signin_Messages[]=$last_signin_Message;
        return $last_signin_Messages;
    }

    public static function get_global_c_progress(){
        
        global $DB, $CFG;
        require('db/conection.php');
        require_once($CFG->libdir . '/completionlib.php');

        

        //1. obtengo todos los cursos de la plataforma
        $courseGeneral_getCoursesCant = "SELECT * FROM mdl_course WHERE id != 1";
        $courseGeneral_courses = $DB->get_records_sql($courseGeneral_getCoursesCant);
        //variable que cuenta el progreso totoal de un estudiante
        $progress_per_student = array();
        foreach($courseGeneral_courses as $courseGeneral_cdb){

            $courseGeneral_courseViewId = $courseGeneral_cdb->id;
            
            //2. obtengo el id de los usuarios para comprobar su porcentaje en el curso
            /**obtengo los id de los studiantes */
            $courseGeneral_getUserId = "SELECT ue.userid AS 'user_id' FROM mdl_user_enrolments AS ue JOIN mdl_enrol AS e WHERE e.roleid = 5 AND e.id=ue.enrolid";
            $courseGeneral_userId = $DB->get_records_sql($courseGeneral_getUserId);
            

            foreach($courseGeneral_userId as $courseGeneral_uiddb){
                $courseGeneral_userId=$courseGeneral_uiddb->user_id;

                //3. Obtengo el porcentaje de progreso del cursso

                //funcion para obtener el porcentage de progreso del curso por usuario

                        // Make sure we continue with a valid userid.
                        if (empty($courseGeneral_userId)) {
                            $userid = 0;
                        }

                        $completion = new \completion_info($courseGeneral_cdb);
                        

                        // First, let's make sure completion is enabled.
                        if (!$completion->is_enabled()) {
                            return null;
                        }

                        if (!$completion->is_tracked_user($courseGeneral_userId)) {
                            return null;
                        }

                        // Before we check how many modules have been completed see if the course has.
                        if ($completion->is_course_complete($courseGeneral_userId)) {
                            return 100;
                        }

                        // Get the number of modules that support completion.
                        $modules = $completion->get_activities();
                        $count = count($modules);
                        if (!$count) {
                            return null;
                        }

                        // Get the number of modules that have been completed.
                        $completed = 0;
                        foreach ($modules as $module) {
                            $data = $completion->get_data($module, true, $courseGeneral_userId);
                            $completed += $data->completionstate == COMPLETION_INCOMPLETE ? 0 : 1;
                        }
                        
                        $progress_std = array(
                            'id'=>$courseGeneral_userId,
                            'progress'=>($completed / $count) * 100
                        );

                        array_push($progress_per_student,$progress_std);
            }
        }

            //4. obtengo el id de los usuarios para comprobar su porcentaje en el curso
            /**obtengo los id de los studiantes */
            $courseGeneral_getUserId = "SELECT ue.userid AS 'user_id' FROM mdl_user_enrolments AS ue JOIN mdl_enrol AS e WHERE e.roleid = 5 AND e.id=ue.enrolid";
            $courseGeneral_userId = $DB->get_records_sql($courseGeneral_getUserId);
        foreach($courseGeneral_userId as $courseGeneral_uiddb){
            $courseGeneral_userId=$courseGeneral_uiddb->user_id;
            
            $cant_courses;
            $progress_count = 0;
            foreach($progress_per_student as  $prog){
                if($prog["id"] == $courseGeneral_userId){
                    $progress_count = $progress_count + $prog["progress"];
                }
                $cant_courses++;
            }

            //5 insercion en la bd el dato de progreso del curso
            $global_progress_getUserToInsertDate = "SELECT username FROM mdl_user WHERE id = $courseGeneral_userId";
            $global_progress_userToInsertDate = $DB->get_records_sql($global_progress_getUserToInsertDate);
            foreach($global_progress_userToInsertDate as $global_progress_utiddb){
                $global_progress_userDocumentToInsertDate = explode("_", $global_progress_utiddb->username);
                            
                if (!is_numeric($global_progress_userDocumentToInsertDate[1])) {
                    continue;
                }
                $courseGeneral_getCourseIfExist ="SELECT id FROM students WHERE keycloak_sub_id=".$global_progress_userDocumentToInsertDate[1]."";

                if ($courseGeneral_resultCourseIfExist = $conn->query($courseGeneral_getCourseIfExist)) {
                    if($courseGeneral_resultCourseIfExist->num_rows == 0){
                        $courseGeneral_insertNewCourse = "INSERT INTO student_indicators (`student_id`,`indicator_id`, `calc_indicator`) VALUES(".$courseGeneral_userId.",9,'".$progress_count/$cant_courses."')";
                        $courseGeneral_Messages = array();
                        $courseGeneral_Message = array();
                        if ($conn->query($courseGeneral_insertNewCourse) === TRUE) {
                            $courseGeneral_Message['message']= "get_global_c_progress / Se inserto el progreso general del curso";
                        } else {
                            $courseGeneral_Message['message']="get_global_c_progress / Error en la insercion";
                        }
                    }
                    else{
                        while($courseGeneral_courseIfExist = $courseGeneral_resultCourseIfExist->fetch_object()){  

                            if($courseGeneral_courseIfExist->id_course){
                                                                    
                                $courseGeneral_updateNewCourse = "UPDATE student_indicators SET  `student_id`= '".$courseGeneral_userId."',`calc_indicator`='".$progress_count/$cant_courses."' WHERE `user_id`=".$courseGeneral_userId." AND indicator_id = 9";
                                $courseGeneral_Messages = array();
                                $courseGeneral_Message = array();
                                if ($conn->query($courseGeneral_updateNewCourse) === TRUE) {
                                $courseGeneral_Message['message']= "get_global_c_progress / Se Actualizo el progreso general del curso";
                                } else {
                                $courseGeneral_Message['message']="get_global_c_progress / Error en la actualizacion";
                                }
                            }
                            else{
                                    $courseGeneral_insertNewCourse = "INSERT INTO student_indicators (`student_id`,`indicator_id`, `calc_indicator`) VALUES(".$courseGeneral_userId.",9,'".$progress_count/$cant_courses."')";
                                    $courseGeneral_Messages = array();
                                    $courseGeneral_Message = array();
                                    if ($conn->query($courseGeneral_insertNewCourse) === TRUE) {
                                        $courseGeneral_Message['message']= "get_global_c_progress / Se inserto el el progreso general del curso";
                                    } else {
                                        $courseGeneral_Message['message']="get_global_c_progress / Error en la insercion";
                                    }
                            }       
                    }
                    }
                }
            }
            $courseGeneral_Message['message']="get_global_c_progress / Error en la insercion";
                        
            $courseGeneral_Messages[]=$courseGeneral_Message;
            return $courseGeneral_Messages;  
        }
        $courseGeneral_Message['message']="get_global_c_progress / Error en la insercion";
                        
        $courseGeneral_Messages[]=$courseGeneral_Message;
                    
        return $courseGeneral_Messages;  
    }

    /**
     * Verificar la insercion en la bd debe ser en user indicators
     */
    // public function get_grade_point_averagec(){
    //     require('db/conection.php');
    //     global $DB, $CFG;

    //     /** 1. Obtener id de los cursos */
    //     $grade_point_averagec_getCourses = "SELECT id FROM mdl_course WHERE id != 1";
    //     $grade_point_averagec_courses = $DB->get_records_sql($grade_point_averagec_getCourses);

        
    //     /** 2. obtener los items de calificacion de los cursos */
    //     foreach($grade_point_averagec_courses as $grade_point_averagec_cdb){
    //         $grade_point_averagec_idCourse = $grade_point_averagec_cdb->id;
    //         $grade_point_averagec_getItemsGrades = "SELECT id FROM mdl_grade_items WHERE courseid = $grade_point_averagec_idCourse AND itemtype='course'";
    //         $grade_point_averagec_itemsGrades = $DB->get_records_sql($grade_point_averagec_getItemsGrades);
    //         if(empty($grade_point_averagec_itemsGrades)){
    //             continue;
    //         }else{
    //             /** 3. obtener la calificacion final del curso por usuario */
    //                 $grade_point_averagec_getGrades = "SELECT finalgrade FROM mdl_grade_grades WHERE itemid = 1";
    //                 $grade_point_averagec_grades = $DB->get_records_sql($grade_point_averagec_getGrades);

    //                 /** 4. Sumar las calificaciones y promediar la nota final suma total / cantidad de calificaciones */
    //                 $grade_point_averagec_countOfGrades = 0;
    //                 $grade_point_averagec_resultOfGrades = 0;
    //                 foreach($grade_point_averagec_grades as $grade_point_averagec_gdb){

    //                         $grade_point_averagec_countOfGrades++;
    //                         $grade_point_averagec_resultOfGrades = $grade_point_averagec_resultOfGrades + $grade_point_averagec_gdb->finalgrade;
    //                 }
    //                 if($grade_point_averagec_countOfGrades === 0){
    //                     $grade_point_averagec_resultOfGradeCourse = 0;
    //                 }else{
    //                     $grade_point_averagec_resultOfGradeCourse = $grade_point_averagec_resultOfGrades / $grade_point_averagec_countOfGrades;
    //                 }
                    

    //                 /** 5. Insertar el resultado en la tabla de indicadores */  
    //                 $grade_point_averagec_getCourseIfExist ="SELECT id_course FROM course_indicators WHERE id_course=$grade_point_averagec_idCourse  AND indicator_id = 11";

    //                 if ($grade_point_averagec_resultCourseIfExist = $conn->query($grade_point_averagec_getCourseIfExist)) {
    //                     if($grade_point_averagec_resultCourseIfExist->num_rows == 0){
    //                         $grade_point_averagec_insertNewCourse = "INSERT INTO course_indicators (`id_course`,`indicator_id`, `calc_indicator`) VALUES(".$grade_point_averagec_idCourse.",11,'".$grade_point_averagec_resultOfGradeCourse."')";
    //                         $grade_point_averagec_Messages = array();
    //                         $grade_point_averagec_Message = array();
    //                         if ($conn->query($grade_point_averagec_insertNewCourse) === TRUE) {
    //                             $grade_point_averagec_Message['message']= "Se inserto el progreso general del curso";
    //                         } else {
    //                             $grade_point_averagec_Message['message']="Error en la insercion";
    //                         }
    //                     }
    //                     else{
    //                         while($grade_point_averagec_courseIfExist = $grade_point_averagec_resultCourseIfExist->fetch_object()){  

    //                             if($grade_point_averagec_courseIfExist->id_course){
                                                                        
    //                                 $grade_point_averagec_updateNewCourse = "UPDATE course_indicators SET  `id_course`= '".$grade_point_averagec_idCourse."',`calc_indicator`='".$grade_point_averagec_resultOfGradeCourse."' WHERE `id_course`=".$grade_point_averagec_idCourse." AND indicator_id = 11";
    //                                 $grade_point_averagec_Messages = array();
    //                                 $grade_point_averagec_Message = array();
    //                                 if ($conn->query($grade_point_averagec_updateNewCourse) === TRUE) {
    //                                 $grade_point_averagec_Message['message']= "Se Actualizo el progreso general del curso";
    //                                 } else {
    //                                 $grade_point_averagec_Message['message']="Error en la actualizacion";
    //                                 }
    //                             }
    //                             else{
    //                                     $grade_point_averagec_insertNewCourse = "INSERT INTO course_indicators (`id_course`,`indicator_id`, `calc_indicator`) VALUES(".$grade_point_averagec_idCourse.",11,'".$grade_point_averagec_resultOfGradeCourse."')";
    //                                     $grade_point_averagec_Messages = array();
    //                                     $grade_point_averagec_Message = array();
    //                                     if ($conn->query($grade_point_averagec_insertNewCourse) === TRUE) {
    //                                         $grade_point_averagec_Message['message']= "Se inserto el el progreso general del curso";
    //                                     } else {
    //                                         $grade_point_averagec_Message['message']="Error en la insercion";
    //                                     }
    //                             }       
    //                     }
    //                     }
    //                 }

                    
    //                 $grade_point_averagec_Messages[]=$grade_point_averagec_Message;
                                    
    //                 return $grade_point_averagec_Messages;
    //         }
            
    //     }

    // }


    // public static function get_quantity_students(){
    //     require('db/conection.php');
    //     global $DB, $CFG;

    //     $quantity_students_studentCount = 0;

    //     /** 1. obtener todos los usuarios que son estudiantes*/
    //      $quantity_students_getCourseRolStudent = "SELECT user2.firstname AS Firstname, user2.lastname AS Lastname, user2.email AS Email, user2.city AS City, course.fullname AS Course ,(SELECT shortname FROM mdl_role WHERE id=5) as Role ,(SELECT name FROM mdl_role WHERE id=en.roleid ) as RoleName FROM mdl_course as course JOIN mdl_enrol AS en ON en.courseid = course.id JOIN mdl_user_enrolments AS ue ON ue.enrolid = en.id  JOIN mdl_user AS user2 ON ue.userid = user2.id;";
    //     $quantity_students_courseRolStudent = $DB->get_records_sql($quantity_students_getCourseRolStudent);
                
    //     foreach($quantity_students_courseRolStudent as $quantity_students_crsdb){
    //         /** 2. Hacemos un count para saber cuantos estudiantes hay por institucion */
    //                     $quantity_students_studentCount ++;
    //     }
    //     /** 3. Insertamos en la bd simat */

    //     $quantity_students_getCourseIfExist ="SELECT platform_id_platform FROM platform_indicators WHERE platform_id_platform=1 ";

    //         if ($quantity_students_resultCourseIfExist = $conn->query($quantity_students_getCourseIfExist)) {
    //             if($quantity_students_resultCourseIfExist->num_rows == 0){
    //                 $quantity_students_insertNewCourse = "INSERT INTO `platform_indicators` (`platform_id_platform`, `indicators_id`, `calc_indicator`) VALUES(1,10,".$quantity_students_studentCount.")";
    //                 $quantity_students_Messages = array();
    //                 $quantity_students_Message = array();
    //                 if ($conn->query($quantity_students_insertNewCourse) === TRUE) {
    //                     $quantity_students_Message['message']= "Se inserto el curso";
    //                 } else {
    //                     $quantity_students_Message['message']="Error en la insercion";
    //                 }
    //             }
    //             else{
    //                 while($quantity_students_courseIfExist = $quantity_students_resultCourseIfExist->fetch_object()){  

    //                     if($quantity_students_courseIfExist->platform_id_platform){
                                                                
    //                         $quantity_students_updateNewCourse = "UPDATE platform_indicators SET  `platform_id_platform`= 1,`calc_indicator`=".$quantity_students_studentCount." WHERE `platform_id_platform`= 1 AND indicators_id = 10";
    //                         $quantity_students_Messages = array();
    //                         $quantity_students_Message = array();
    //                         if ($conn->query($quantity_students_updateNewCourse) === TRUE) {
    //                         $quantity_students_Message['message']= "Se Actualizo la tabla de cursos";
    //                         } else {
    //                         $quantity_students_Message['message']="Error en la actualizacion";
    //                         }
    //                     }
    //                     else{
    //                             $quantity_students_insertNewCourse = "INSERT INTO `platform_indicators` (`platform_id_platform`, `indicators_id`, `calc_indicator`) VALUES(1,10,".$quantity_students_studentCount.")";
    //                             $quantity_students_Messages = array();
    //                             $quantity_students_Message = array();
    //                             if ($conn->query($quantity_students_insertNewCourse) === TRUE) {
    //                                 $quantity_students_Message['message']= "Se inserto el curso";
    //                             } else {
    //                                 $quantity_students_Message['message']="Error en la insercion";
    //                             }
    //                     }       
    //             }
    //             }
                                            
             
    //         }
    //          $quantity_students_Messages[]=$Message;
    //          return $quantity_students_Messages;
    // }

    // public static function get_quantity_courses(){
    //     require('db/conection.php');
    //     global $DB, $CFG;

        
    //     /** 1. Obtener la cantidad de cursos del moodle */
    //     $quantity_courses_courseCant = 0; 

    //     $quantity_courses_getCoursesCant = "SELECT id,fullname FROM mdl_course WHERE id != 1";
    //     $quantity_courses_courses = $DB->get_records_sql($quantity_courses_getCoursesCant);
    //     foreach($quantity_courses_courses as $quantity_courses_cdb){
    //         $quantity_courses_courseCant++;    

            
    //         /** 2. insertar el nombre y el id del curso en la tabla de simat */   
    //         $quantity_courses_getCourseIfExist ="SELECT id_course FROM course WHERE id_course=$quantity_courses_cdb->id ";

            

    //         if ($quantity_courses_resultCourseIfExist = $conn->query($quantity_courses_getCourseIfExist)) {
    //             if($quantity_courses_resultCourseIfExist->num_rows == 0){
    //                 $quantity_courses_insertNewCourse = "INSERT INTO course (`id_course`, `name`) VALUES(".$quantity_courses_cdb->id.",'".$quantity_courses_cdb->fullname."')";
    //                 $quantity_courses_Messages = array();
    //                 $quantity_courses_Message = array();
    //                 if ($conn->query($quantity_courses_insertNewCourse) === TRUE) {
    //                     $quantity_courses_Message['message']= "Se inserto el curso";
    //                 } else {
    //                     $quantity_courses_Message['message']="Error en la insercion";
    //                 }
    //             }
    //             else{
    //                 while($quantity_courses_courseIfExist = $quantity_courses_resultCourseIfExist->fetch_object()){  

    //                     if($quantity_courses_courseIfExist->id_course){
                                                                
    //                         $quantity_courses_updateNewCourse = "UPDATE course SET  `id_course`= '".$quantity_courses_cdb->id."',`name`='".$quantity_courses_cdb->fullname."' WHERE `id_course`=".$quantity_courses_cdb->id."";
    //                         $quantity_courses_Messages = array();
    //                         $quantity_courses_Message = array();
    //                         if ($conn->query($quantity_courses_updateNewCourse) === TRUE) {
    //                         $quantity_courses_Message['message']= "Se Actualizo la tabla de cursos";
    //                         } else {
    //                         $quantity_courses_Message['message']="Error en la actualizacion";
    //                         }
    //                     }
    //                     else{
    //                             $quantity_courses_insertNewCourse = "INSERT INTO course (`id_course`, `name`) VALUES(".$quantity_courses_cdb->id.",'".$quantity_courses_cdb->fullname."')";
    //                             $quantity_courses_Messages = array();
    //                             $quantity_courses_Message = array();
    //                             if ($conn->query($quantity_courses_insertNewCourse) === TRUE) {
    //                                 $quantity_courses_Message['message']= "Se inserto el curso";
    //                             } else {
    //                                 $quantity_courses_Message['message']="Error en la insercion";
    //                             }
    //                     }       
    //             }
    //             }
                                            
             
    //         }
    //         $quantity_courses_Messages[]=$quantity_courses_Message;
            
    //     }
    //     return $quantity_courses_Messages;
    // }

    // public static function get_activities_views(){
    //     require('db/conection.php');
    //     global $DB, $CFG;

    //     $activities_views_activities = 0;

    //     /** 1. Obtener el id del curso */

    //     $activities_views_getCoursesCant = "SELECT id FROM mdl_course WHERE id != 1";
    //     $activities_views_courses = $DB->get_records_sql($activities_views_getCoursesCant);

    //     foreach($activities_views_courses as $activities_views_cdb){

    //     $activities_views_courseViewId = $activities_views_cdb->id;


    //     /**obtengo los id de los studiantes */
    //     $activities_views_getUserId = "SELECT ue.userid AS 'user_id' FROM mdl_user_enrolments AS ue JOIN mdl_enrol AS e WHERE e.roleid = 5 AND e.id=ue.enrolid";
    //     $activities_views_userId = $DB->get_records_sql($activities_views_getUserId);
    //     foreach($activities_views_userId as $activities_views_uiddb){
    //         $activities_views_userId=$activities_views_uiddb->user_id;
            

    //         /** Obtiene las actividades vistas - no se sabe si las completo*/
    //         $activities_views_getcoursesActivities = "SELECT  CASE WHEN l.component = 'mod_url' THEN (SELECT u.name FROM mdl_url AS u WHERE u.id = l.objectid ) WHEN l.component = 'mod_resource' THEN (SELECT r.name FROM mdl_resource AS r WHERE r.id = l.objectid ) WHEN l.component = 'mod_forum' THEN (SELECT f.name FROM mdl_forum AS f WHERE f.id = l.objectid) WHEN l.component = 'mod_quiz' THEN (SELECT q.name FROM mdl_quiz AS q WHERE q.id = l.objectid ) END AS 'module_name' FROM mdl_logstore_standard_log AS l JOIN mdl_user AS u ON u.id = l.userid JOIN mdl_role_assignments AS ra ON ra.userid = l.userid  WHERE l.courseid = $activities_views_courseViewId AND l.component IN ('mod_url', 'mod_resource', 'mod_forum', 'mod_quiz') AND u.id = $activities_views_userId GROUP BY module_name";

    //         $activities_views_coursesActivities = $DB->get_records_sql($activities_views_getcoursesActivities);
    //         if(empty($activities_views_coursesActivities)){
    //             continue;
    //         }

    //         foreach($activities_views_coursesActivities as $activities_views_cadb){
                
    //             $activities_views_activities++;
    //         }
            
    //         /** Se inserta el dato en la bd simat */
    //         $activities_views_getUserToInsertDate = "SELECT username FROM mdl_user WHERE id = $activities_views_userId";
    //         $activities_views_userToInsertDate = $DB->get_records_sql($activities_views_getUserToInsertDate);
    //         foreach($activities_views_userToInsertDate as $activities_views_utiddb){

    //             $activities_views_userDocumentToInsertDate = explode("_", $activities_views_utiddb->username);
                     
    //             if (!is_numeric($activities_views_userDocumentToInsertDate[1])) {
    //                 continue;
    //             }
    //             $activities_views_getUserIdSimatToInsertDate ="SELECT id FROM simats WHERE number_document=".$activities_views_userDocumentToInsertDate[1]."";
                                    
    //             if ($activities_views_resultUserIdSimatToInsertDate = $conn->query($activities_views_getUserIdSimatToInsertDate)) {

    //                 while($activities_views_userIdSimatToInsertDate = $activities_views_resultUserIdSimatToInsertDate->fetch_object()){

    //                     $activities_views_getUserIfExist ="SELECT user_id FROM user_indicators WHERE user_id=$activities_views_userIdSimatToInsertDate->id AND indicator_id = 7";
    //                             if ($activities_views_resultCourseIfExist = $conn->query($activities_views_getUserIfExist)) {
    //                                     if($activities_views_resultCourseIfExist->num_rows == 0){
                                            
    //                                         $activities_views_insertNewCantOfActivities = "INSERT INTO `user_indicators` (`user_id`, `indicator_id`,`calc_indicator`) VALUES(".$activities_views_userIdSimatToInsertDate->id.",7,'".$activities_views_activities."')";
    //                                         $activities_views_Messages = array();
    //                                         $activities_views_Message = array();
    //                                         if ($conn->query($activities_views_insertNewCantOfActivities) === TRUE) {
    //                                             $activities_views_Message['message']= "Se inserto la cantidad de actividades";
    //                                         } else {
    //                                             $activities_views_Message['message']="Error en la insercion empty";
    //                                         }
    //                                     }
    //                                     else{
    //                                         while($activities_views_courseIfExist = $activities_views_resultCourseIfExist->fetch_object()){  

    //                                             if($activities_views_courseIfExist->user_id){
    //                                                 $activities_views_updateNewCantOfActivities = "UPDATE user_indicators SET  `user_id`= ".$activities_views_userIdSimatToInsertDate->id.",`calc_indicator`='".$activities_views_activities."' WHERE `user_id`=".$activities_views_userIdSimatToInsertDate->id." AND indicator_id = 7";
    //                                                 $activities_views_Messages = array();
    //                                                 $activities_views_Message = array();
    //                                                 if ($conn->query($activities_views_updateNewCantOfActivities) === TRUE) {
    //                                                 $activities_views_Message['message']= "Se Actualizo la cantidad de actividades";
    //                                                 } else {
    //                                                 $activities_views_Message['message']="Error en la actualizacion";
    //                                                 }
    //                                             }
    //                                             else{
    //                                                 $activities_views_insertNewCantOfActivities = "INSERT INTO user_indicators (`user_id `, `indicator_id`,`calc_indicator`) VALUES(".$activities_views_userIdSimatToInsertDate->id.",7,'".$activities_views_activities."')";
    //                                                     $activities_views_Messages = array();
    //                                                     $activities_views_Message = array();
    //                                                     if ($conn->query($activities_views_insertNewCantOfActivities) === TRUE) {
    //                                                         $activities_views_Message['message']= "Se inserto la cantidad de actividades";
    //                                                     } else {
    //                                                         $activities_views_Message['message']="Error en la insercion";
    //                                                     }
    //                                             }       
    //                                     }
    //                                 }
    //                             }
    //                 }
    //             }
    //             $activities_views_Messages[]=$activities_views_Message;
             
    //         }
    //          $activities_views_activities = 0;
    //         }
    //     }
    //     $activities_views_Message['message']= "Se Actualizo la cantidad de actividades";

    //     return $activities_views_Messages; 
    // }

    // public static function get_count_of_activities(){
    //     require('db/conection.php');
    //     global $DB, $CFG;

    //     /** 1. obtener el id del curso */
    //     $count_of_activities_getCoursesCant = "SELECT id FROM mdl_course WHERE id != 1";
    //     $count_of_activities_courses = $DB->get_records_sql($count_of_activities_getCoursesCant);

    //     foreach($count_of_activities_courses as $count_of_activities_cdb){

    //     $count_of_activities_courseViewId = $count_of_activities_cdb->id;
    //     /** Cuenta la cantidad de actividades */
    //     $count_of_activities_getAllActivities ="SELECT COUNT(instance) AS 'count' FROM mdl_course_modules WHERE course = $count_of_activities_courseViewId AND deletioninprogress !=1";
    //     $count_of_activities_allActivities = $DB->get_records_sql($count_of_activities_getAllActivities);
    //     /** Inserta dato en la bd simat */
    //         foreach($count_of_activities_allActivities as $count_of_activities_aadb){

    //                 $count_of_activities_getCourseIfExist ="SELECT id_course FROM course_indicators WHERE id_course=$count_of_activities_courseViewId AND indicator_id = 8";

                    

    //                 if ($count_of_activities_resultCourseIfExist = $conn->query($count_of_activities_getCourseIfExist)) {
    //                     if($count_of_activities_resultCourseIfExist->num_rows == 0){
    //                         $count_of_activities_insertNewCourse = "INSERT INTO course_indicators (`id_course`,`indicator_id`, `calc_indicator`) VALUES(".$count_of_activities_courseViewId.",8,'".$count_of_activities_aadb->count."')";
    //                         $count_of_activities_Messages = array();
    //                         $count_of_activities_Message = array();
    //                         if ($conn->query($count_of_activities_insertNewCourse) === TRUE) {
    //                             $count_of_activities_Message['message']= "Se inserto la cantidad de actividades";
    //                         } else {
    //                             $count_of_activities_Message['message']="Error en la insercion";
    //                         }
    //                     }
    //                     else{
    //                         while($count_of_activities_courseIfExist = $count_of_activities_resultCourseIfExist->fetch_object()){  

    //                             if($count_of_activities_courseIfExist->id_course){
                                                                        
    //                                 $count_of_activities_updateNewCourse = "UPDATE course_indicators SET  `id_course`= '".$count_of_activities_courseViewId."',`calc_indicator`='".$count_of_activities_aadb->count."' WHERE `id_course`=".$count_of_activities_courseViewId." AND indicator_id = 8";
    //                                 $count_of_activities_Messages = array();
    //                                 $count_of_activities_Message = array();
    //                                 if ($conn->query($count_of_activities_updateNewCourse) === TRUE) {
    //                                 $count_of_activities_Message['message']= "Se Actualizo la tabla de cursos";
    //                                 } else {
    //                                 $count_of_activities_Message['message']="Error en la actualizacion";
    //                                 }
    //                             }
    //                             else{
    //                                     $count_of_activities_insertNewCourse = "INSERT INTO course_indicators (`id_course`,`indicator_id`, `calc_indicator`) VALUES(".$count_of_activities_courseViewId.",8,'".$count_of_activities_aadb->count."')";
    //                                     $count_of_activities_Messages = array();
    //                                     $count_of_activities_Message = array();
    //                                     if ($conn->query($count_of_activities_insertNewCourse) === TRUE) {
    //                                         $count_of_activities_Message['message']= "Se inserto el curso";
    //                                     } else {
    //                                         $count_of_activities_Message['message']="Error en la insercion";
    //                                     }
    //                             }       
    //                     }
    //                     }
                                                    
                    
    //                 }
    //                 $count_of_activities_Messages[]=$count_of_activities_Message;
    //         } 
            
    //     }
    //     return $count_of_activities_Messages; 
    // }

    // public static function get_global_progress(){
    //     require('db/conection.php');
    //     global $DB, $CFG;

    //     /** Actividades vistas / cantidad de actividades 
    //      * Se llama el resultado del indicador de actividades vistas y el indicador de total de tareas
    //     */

    //     $global_progress_activities = 0;
    //     $global_progress_cantActivities = 0;

    //     /** 1. Obtener el indicador de actividades */

    //     $global_progress_getCoursesCant = "SELECT id FROM mdl_course WHERE id != 1";
    //     $global_progress_courses = $DB->get_records_sql($global_progress_getCoursesCant);

    //     foreach($global_progress_courses as $global_progress_cdb){

    //         $global_progress_courseViewId = $global_progress_cdb->id;


    //         /**obtengo los id de los studiantes */
    //         $global_progress_getUserId = "SELECT ue.userid AS 'user_id' FROM mdl_user_enrolments AS ue JOIN mdl_enrol AS e WHERE e.roleid = 5 AND e.id=ue.enrolid";
    //         $global_progress_userId = $DB->get_records_sql($global_progress_getUserId);
    //         foreach($global_progress_userId as $global_progress_uiddb){
    //             $global_progress_userId=$global_progress_uiddb->user_id;

    //             /** Obtiene las actividades vistas - no se sabe si las completo*/
    //             $global_progress_getcoursesActivities = "SELECT  CASE WHEN l.component = 'mod_url' THEN (SELECT u.name FROM mdl_url AS u WHERE u.id = l.objectid ) WHEN l.component = 'mod_resource' THEN (SELECT r.name FROM mdl_resource AS r WHERE r.id = l.objectid ) WHEN l.component = 'mod_forum' THEN (SELECT f.name FROM mdl_forum AS f WHERE f.id = l.objectid) WHEN l.component = 'mod_quiz' THEN (SELECT q.name FROM mdl_quiz AS q WHERE q.id = l.objectid ) END AS 'module_name' FROM mdl_logstore_standard_log AS l JOIN mdl_user AS u ON u.id = l.userid JOIN mdl_role_assignments AS ra ON ra.userid = l.userid  WHERE l.courseid = $global_progress_courseViewId AND l.component IN ('mod_url', 'mod_resource', 'mod_forum', 'mod_quiz') AND u.id = $global_progress_userId GROUP BY module_name";

    //             $global_progress_coursesActivities = $DB->get_records_sql($global_progress_getcoursesActivities);
    //             if(empty($global_progress_coursesActivities)){
    //                 continue;
    //             }
    //             foreach($global_progress_coursesActivities as $global_progress_cadb){
    //                 $global_progress_activities++;
    //             }

    //             /** 2. obtener el indicador de totla de tareas */

    //             /** Cuenta la cantidad de actividades */
    //             $global_progress_getAllActivities ="SELECT COUNT(instance) AS 'count' FROM mdl_course_modules WHERE course = $global_progress_courseViewId AND deletioninprogress !=1";
    //             $global_progress_allActivities = $DB->get_records_sql($global_progress_getAllActivities);
    //             foreach($global_progress_allActivities as $global_progress_aadb){
    //                 $global_progress_cantActivities =$global_progress_aadb->count;
    //             }

                
    //             /** Sacar porcentaje de progeso */
    //             $global_progress_progress = number_format((($global_progress_activities / $global_progress_cantActivities) * 100),2,',');

    //             /** Se inserta el dato en la bd simat */
    //             $global_progress_getUserToInsertDate = "SELECT username FROM mdl_user WHERE id = $global_progress_userId";
    //             $global_progress_userToInsertDate = $DB->get_records_sql($global_progress_getUserToInsertDate);
    //             foreach($global_progress_userToInsertDate as $global_progress_utiddb){
    //                 $global_progress_userDocumentToInsertDate = explode("_", $global_progress_utiddb->username);
                        
    //                 if (!is_numeric($global_progress_userDocumentToInsertDate[1])) {
    //                     continue;
    //                 }
    //                 $global_progress_getUserIdSimatToInsertDate ="SELECT id FROM students WHERE keycloak_sub_id=".$global_progress_userDocumentToInsertDate[1]."";
                                        
    //                 if ($global_progress_resultUserIdSimatToInsertDate = $conn->query($global_progress_getUserIdSimatToInsertDate)) {

    //                     while($global_progress_userIdSimatToInsertDate = $global_progress_resultUserIdSimatToInsertDate->fetch_object()){
    //                         $global_progress_getUserIfExist ="SELECT user_id FROM student_indicators WHERE user_id=$global_progress_userIdSimatToInsertDate->id AND indicator_id = 1";
    //                                 if ($global_progress_resultCourseIfExist = $conn->query($global_progress_getUserIfExist)) {
    //                                         if($global_progress_resultCourseIfExist->num_rows == 0){
                                                
    //                                             $global_progress_insertNewCantOfActivities = "INSERT INTO `student_indicators` (`student_id`, `indicator_id`,`calc_indicator`) VALUES(".$global_progress_userIdSimatToInsertDate->id.",1,'".$global_progress_progress."')";
    //                                             $global_progress_Messages = array();
    //                                             $global_progress_Message = array();
    //                                             if ($conn->query($global_progress_insertNewCantOfActivities) === TRUE) {
    //                                                 $global_progress_Message['message']= "Se inserto el progreso del estudiante";
    //                                             } else {
    //                                                 $global_progress_Message['message']="Error en la insercion empty";
    //                                             }
    //                                         }
    //                                         else{
    //                                             while($global_progress_courseIfExist = $global_progress_resultCourseIfExist->fetch_object()){  

    //                                                 if($global_progress_courseIfExist->user_id){
                                                                                            
    //                                                     $global_progress_updateNewCantOfActivities = "UPDATE student_indicators SET  `student_id`= ".$global_progress_userIdSimatToInsertDate->id.",`calc_indicator`='".$global_progress_progress."' WHERE `student_id`=".$global_progress_userIdSimatToInsertDate->id." AND indicator_id = 1";
    //                                                     $global_progress_Messages = array();
    //                                                     $global_progress_Message = array();
    //                                                     if ($conn->query($global_progress_updateNewCantOfActivities) === TRUE) {
    //                                                     $global_progress_Message['message']= "Se Actualizo el progreso del estudiante";
    //                                                     } else {
    //                                                     $global_progress_Message['message']="Error en la actualizacion";
    //                                                     }
    //                                                 }
    //                                                 else{
    //                                                     $global_progress_insertNewCantOfActivities = "INSERT INTO student_indicators (`student_id `, `indicator_id`,`calc_indicator`) VALUES(".$global_progress_userIdSimatToInsertDate->id.",1,'".$global_progress_progress."')";
    //                                                         $global_progress_Messages = array();
    //                                                         $global_progress_Message = array();
    //                                                         if ($conn->query($global_progress_insertNewCourse) === TRUE) {
    //                                                             $global_progress_Message['message']= "Se inserto el progreso del estudiante";
    //                                                         } else {
    //                                                             $global_progress_Message['message']="Error en la insercion";
    //                                                         }
    //                                                 }       
    //                                         }
    //                                     }
    //                                 }
    //                     }
    //                 }
    //             }
    //             $global_progress_Messages[]=$global_progress_Message;
    //         }
            
    //     }
    //     return $global_progress_Messages; 
    // }


    

}