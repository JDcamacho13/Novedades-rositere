$(document).ready(function(){

    var usuario;
    var password;

    $('#submit').on('click', function(){

        usuario = $("#usuario").val();
        password = $("#password").val();

        $.ajax({
            url: "php/actLogin.php",
            method: "POST",
            data: {usuario:usuario,
                    password:password},
            cache: "false",
            dataType: "json",
            beforeSend:function(){
                $('#submit').val("Conectando...");
            }}).done(function(data){

                if(data == 0){
                    if(data == 0){
                        $(location).attr('href','index.php');
                    }
                    
                }else{

                    $('#errores').html(data);

                    $('#submit').html("Ingresar");

                }
                    
                

                
            });
    });

});