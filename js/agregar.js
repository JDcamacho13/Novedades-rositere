
$(document).ready(function() {
    $("#submit").on('click', function(e) {
        e.preventDefault();
        var formData = new FormData();
        var files = $('#image')[0].files[0];
        var nombre =$('#nombre').val();
        var precio =parseFloat($('#precio').val());

        formData.append('file',files);
        formData.append('nombre',nombre);
        formData.append('precio',precio);
        $.ajax({
            url: './php/actAgregar.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend:function(){
                $('#submit').val("Subiendo...");
            }}).done(function(data) {

                $('#submit').val("Enviar");

                if (data == 1) {
                    alert("todo perfecto");
                } 
                if(data == 0) {
                    alert('Error');
                }
                
                
            })
        });
        
});

