$(document).ready(function(){

    var nombre;
    var apellido;
    var usuario;
    var password;

    $('#submit').on('click', function(e){

        e.preventDefault();

        nombre = $("#nombre").val();
        apellido = $("#apellido").val();
        usuario = $("#usuario").val();
        password = $("#password").val();

        $.ajax({
            url: "php/actRegister.php",
            method: "POST",
            data: {nombre:nombre,
                    apellido:apellido,
                    usuario:usuario,
                    password:password},
            cache: "false",
            dataType: "json",
            beforeSend:function(){
                $('#submit').val("Conectando...");
            }}).done(function(data){

                if(data == 0){
                    if(data == 0){
                        $(location).attr('href','login.php');
                    }
                    
                }else{

                    $('#errores').html(data);
                    $("#login-page").css("padding-top" ,"40px");
                    $('#submit').val("Enviar");

                }
                    
                

                
            });
    });

});