<?php 



class  block_reporter_talentum  extends  block_base  { 
    
    public  function  init ()  { 
        $this -> title  =  get_string ( 'title' ,  'block_reporter_talentum' ); 
        
    } 
    // La etiqueta de PHP y el corchete para la definición de clase 
    // solo se cerrarán después de que se agregue otra función en la siguiente sección. 
    public function get_content() {
        global  $CFG;
        if ($this->content !== null) {
          return $this->content;
        }

        $urlReporter = $CFG->wwwroot.'/local/course_dev/pages/admin.php';
        $this->content         =  new stdClass;
        $isAdmin = is_siteadmin();
        if($isAdmin){
            $this->content->text .= html_writer::start_tag('li');
            $this->content->text .= html_writer::start_tag('a', ['href'=>$urlReporter]);
            $this->content->text .= get_string ( 'menuTitle' ,  'block_reporter_talentum' );
            $this->content->text .= html_writer::end_tag('a');
            $this->content->text .= html_writer::end_tag('li');
        }else{
            $this->content->text .= html_writer::start_tag('li');
            $this->content->text .= html_writer::start_tag('a', ['href'=>$urlReporter]);
            $this->content->text .= 'hello no admin';
            $this->content->text .= html_writer::end_tag('a');
            $this->content->text .= html_writer::end_tag('li');
        }
        
     
        return $this->content;
    }
}