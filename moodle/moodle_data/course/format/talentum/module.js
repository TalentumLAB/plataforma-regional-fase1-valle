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
 * Talentum_Format_renderer
 *
 * @package    Talentum_Format
 * @author     Talentum
 * @copyright  Talentum
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 M.format_talentum = M.format_talentum || {
    ourYUI: null,
    numsections: 0,
    nuActualSection: 0
};
M.format_talentum.locationurl = function(idcourse){
    url = window.location.hash;
    if(url){
    var sec = url.substr(9);
        if(sec!=0){
        url = '';
        this.show(sec,idcourse);
        }
    }
    
    
};
M.format_talentum.Organizar = function(){
    var arModules = document.getElementsByClassName("section img-text");
    var elModule = arModules[0];
    var arActvities = elModule.getElementsByClassName("activity");
    
    elModule.style.display="flex";
    elModule.style.flexWrap="wrap";
    elModule.style.margin="0";
    elModule.style.padding="0";
    
    
    for (var i = 0; i < arActvities.length;i++){
    var elActivitie = arActvities[i];
    var elActions =null;

    var laurl = document.querySelectorAll('.activityinstance a');

    laurl.forEach((function(x){x.setAttribute("target","_blank");}));         
    elActivitie.getElementsByClassName("activityinstance")[0].className="activityinstance btn btn-secondary";
    elActivitie.getElementsByClassName("activityinstance")[0].style.marginBottom="10px";
    elActivitie.getElementsByClassName("activityinstance")[0].style.marginTop="10px";
    elActivitie.getElementsByClassName("iconlarge activityicon")[0].style.display="none";
    elActions = elActivitie.getElementsByClassName("actions")[0];   
    if(elActions){
    elActions.style.display="none";
            }
    
    }
 };
M.format_talentum.init = function (Y, numsections,idcourse) {
    this.ourYUI = Y;
    this.numsections = parseInt(numsections);
    document.getElementById('rowinit').style.display = 'flex';
    document.getElementById('charge').style.display = 'none';
    var section = document.getElementById('talentum-container-section');
    section.setAttribute('class', 'col-sm-12');
    section.style.display = 'block';
    // document.getElementById('section-0').style.display = 'block';
    document.getElementById('talentum-modules-container').style.display = 'block';
    document.getElementById('above').style.display='none !important';

    
    for(let i=1; i <= this.numsections; i++){
        var moduleElement = document.getElementById('module-image-'+ i );
        moduleElement.addEventListener('mouseenter', e => {
            document.getElementById('talentum-button-section-'+i).classList.add('after-class-'+i);

        });
        moduleElement.addEventListener('mouseleave', e => {
            document.getElementById('talentum-button-section-'+i).classList.remove('after-class-'+i);

        });
    }
    

    this.hide();
    this.hideback();    
    // this.Organizar();
    this.locationurl(idcourse);
};
M.format_talentum.hide = function () {
    for (var i = 1; i <= this.numsections; i++) {
        if (document.getElementById('talentum-button-section-' + i) != undefined) {
            var buttonsection = document.getElementById('talentum-button-section-' + i);
            buttonsection.setAttribute('class', buttonsection.getAttribute('class').replace('sectionvisible', ''));
            document.getElementById('section-' + i).style.display = 'none';
            document.getElementById('container-'+ i).style.display='none'; 
        }
    }
};
M.format_talentum.fullhide = function () {
    this.hide();
    if (document.getElementById('talentum-button-section-' + 0) != undefined) {
        var buttonsection = document.getElementById('talentum-button-section-' + 0);
        this.hidehome();
    }
};
M.format_talentum.show = function (id, courseid) {
    this.fullhide();
    this.nuActualSection = id;
    var buttonsection = document.getElementById('talentum-button-section-' + id);
    var currentsection = document.getElementById('section-' + id); 
    // var nonvideo = document.querySelector('#container-'+id+">#nonvideo");
    // var sectioninternal = document.getElementById('talentum-container-section');
    document.getElementById('above').style.display='none !important';
    buttonsection.setAttribute('class', buttonsection.getAttribute('class') + ' sectionvisible');
    // if (nonvideo){
    //     console.log('nonvideo hay '+ nonvideo);
    //     sectioninternal.setAttribute('class', 'col-sm-12');
    //     currentsection.setAttribute('class', 'col-sm-12');
    //     currentsection.style.marginTop = '0rem';
    // }
    // else{
    // console.log('nonvideo  no hay '+ nonvideo);
    // sectioninternal.setAttribute('class', 'col-sm-12');
    // currentsection.style.marginTop = '4rem';
    // }
    currentsection.style.display = 'block';
    
    currentContainer = document.getElementById('container-'+ id);
    currentContainer.style.display='block';    
    document.cookie = 'sectionvisible_' + courseid + '=' + id + '; path=/';
    M.format_talentum.h5p();
    document.getElementById('summary').style.display='block !important';
    var actmodules = document.querySelectorAll('#section-' + id + ' .activityinstance');
    var actmodulesA = document.querySelectorAll('#section-' + id + ' .activityinstance a');
    var actmodulesIMG = document.querySelectorAll('#section-' + id + ' .activityinstance img');
    var actmodulesSPAN = document.querySelectorAll('#section-' + id + ' .activityinstance span');

    document.getElementById("section-" + id).appendChild(currentContainer);

    var ActmoduleT = document.querySelectorAll('#section-' + id + ' .section li>div');
    var ActmoduleT1 = document.querySelectorAll('#section-' + id + ' .section li');
    var imageAct = document.getElementById('module-image');

    
    for (let i = 0; i < ActmoduleT.length; i++) {
        
        var idAct = ActmoduleT1[i].getAttribute('id');
        var exist = document.querySelector('#'+idAct+'>div #module-image');
        if(!exist){
            ActmoduleT[i].appendChild(imageAct.cloneNode(true));
        }
        else{
            exist.parentNode.removeChild(exist);
            ActmoduleT[i].appendChild(imageAct.cloneNode(true));
        }
        
    }


    var ActmoduleFinish = document.querySelectorAll('.activity-information .btn-outline-success');

    ActmoduleFinish.forEach((function(x){
        x.innerHTML = '<i class="fa fa-check" aria-hidden="true"></i>';       
    }));
    


    actmodules.forEach((function(x){
        x.className="activityinstance activities";
    }));
    actmodulesA.forEach((function(x){
        x.classList.add('formattalentumiconlink');
    }));
    actmodulesIMG.forEach((function(x){
        x.classList.add('formattalentumiconimage');
    }));
    actmodulesSPAN.forEach((function(x){
        x.classList.add('formattalentumiconspan');
    }));
    this.hidehome();
    this.showback(id);
    window.scrollTo({ top: 0, behavior: 'smooth' });
};
M.format_talentum.showmodule= function(){
    document.getElementById('charge').style.display='none';
    document.getElementById('above').style.display='none !important';
    document.getElementById('rowinit').style.display='block';
    document.getElementById('summary').style.display='block';
    
    document.getElementsByClassName(course-content)[0].style.display="block";
    for (var i = 1; i <= this.numsections; i++) {
    var currentsection = document.getElementById('section-' + i).classList.remove("col-sm-6");
    var currentsection = document.getElementById('section-' + i).classList.add("col-sm-12");
    var currentsection = document.getElementById('section-' + i).style.display='block';
    currentsection.setAttribute('class', 'col-sm-12');
    }
}
M.format_talentum.hidehome = function () {
    document.getElementById('section-' + 0).style.display = 'none';
    document.getElementById('talentum-modules-container').style.display = 'none';
}
M.format_talentum.hideback = function () {
    for (var i = 1; i <= this.numsections; i++) {
        document.getElementById('talentum-back-nav-'+i).style.display = 'none';
        document.getElementById('container-'+ i).style.display= 'none';    
    }
}
M.format_talentum.showback = function (id) {
    if(this.nuActualSection != id){
    document.getElementById('talentum-back-nav-'+id).style.display = 'none';
    }else{
    document.getElementById('talentum-back-nav-'+id).style.display = 'block';
    // document.getElementById('talentum-back-nav-'+id).style.marginBottom = '3rem';
    document.getElementById('talentum-back-nav-'+id).style.padding = '.5rem 0rem';
    }
}
M.format_talentum.back = function (courseid) {
    this.hide();
    document.getElementById('section-' + 0).style.display = 'block';
    document.getElementById('talentum-modules-container').style.display = 'block';
    var sectioninternal = document.getElementById('talentum-container-section');
    sectioninternal.setAttribute('class', 'col-sm-12');
    this.hideback();
};
M.format_talentum.h5p = function () {
    window.h5pResizerInitialized = false;
    var iframes = document.getElementsByTagName('iframe');
    var ready = {
        context: 'h5p',
        action: 'ready'
    };
    for (var i = 0; i < iframes.length; i++) {
        if (iframes[i].src.indexOf('h5p') !== -1) {
            iframes[i].contentWindow.postMessage(ready, '*');
        }
    }
};
M.format_talentum.Coursecontent = function($url){
    window.open(""+$url+"");
};
M.format_talentum.Courseprocess = function($url){
    window.open(""+$url+"");
};
M.format_talentum.Forum = function($url){
    window.open(""+$url+"");
};
M.format_talentum.Meeting = function($url){
    window.open(""+$url+"");
};
M.format_talentum.Doubts = function($url){
    window.open(""+$url+"");
};