require(['core/ajax','core/notification'], function(ajax,notification) {
    
    $("#btn-reports").click(function(){
        var promises = ajax.call([{
                methodname: 'local_course_dev_insert_users', 
                args: {},
                done: notification.success,
                fail: notification.exception
            }]);
        promises[0].done(function(response) {
             notification.addNotification({
                message: response[0].message,
                type: "info"
              });
        }).fail(function(ex) {  
            notification.addNotification({
                message: "Hubo un inconveniente en la insericion",
                type: "Error"
              });
        });
       
    });
});

function selectCity(city){
       
        var actualUrl = window.location.href;
        
        const valores = window.location.search;
        if(valores ===""){
            window.location.href = actualUrl + `?city=${city.value}`;
        }else{
            var cleanUrl = actualUrl.slice( 0, actualUrl.indexOf('?') );
            window.location.href = cleanUrl + `?city=${city.value}`;
        }
        
        // alert(actualUrl + `?city=${city.value}`);
    
        // window.location.href = actualUrl + `?city=${city.value}`;

}

function activeButton(){
    var buttonLoad = document.querySelector('#buttonLoadStudents');

    buttonLoad.removeAttribute('disabled');
}

function activeButtonAll(){
    var buttonLoad = document.querySelector('#buttonLoadStudentsAll');

    buttonLoad.removeAttribute('disabled');
}