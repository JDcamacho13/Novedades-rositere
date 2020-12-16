var dato = 0;
var permisos = 0;

$(document).ready(()=>{

    permisos = $("#permiso").val();
    actualizarDolar(permisos)

});

function actualizarDolar(permisos){
    $.ajax({
        url: "php/actBuscarDolar.php",
        method: "POST",
        cache: "false",
        dataType: "json",
        }).done(function(data){
            dolar = data[0]
            if(permisos==1){
                $("#dolar").html(`Precio del Dolar: ${formatNumber(data[0])}<br />Cambiado el: ${data[1]}<button id="cambio">Cambiar</button>`)
            }else{
                $("#dolar").html(`Precio del Dolar: ${formatNumber(data[0])}`)
            }
            
                

            $("#cambio").click(()=>{
        
                $("#dolar").html(`Nuevo precio del dolar: <input type="number" id="Nprecio"><br /><button id="cambio">Cambiar</button>`);
                $("#cambio").click(()=>{

                    var precio = $("#Nprecio").val();
                    var hoy = new Date();
                    var fecha = hoy.getFullYear() + '-' + ( hoy.getMonth() + 1 ) + '-' + hoy.getDate();
                    var hora = hoy.getHours() + ':' + hoy.getMinutes() + ':' + hoy.getSeconds();
                    var fechaHora = fecha + ' ' + hora;
                    $.ajax({
                        url: "php/actualizarDolar.php",
                        method: "POST",
                        data: {precio:precio,
                                fechaHora:fechaHora},
                        cache: "false",
                        dataType: "json",
                        }).done(function(data){
            
                            actualizarVista();
                            
                        });

                    })

            });
        })
}

function formatNumber(num) {
    if (!num || num == 'NaN') return '-';
    if (num == 'Infinity') return '&#x221e;';
    num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num))
        num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    cents = num % 100;
    num = Math.floor(num / 100).toString();
    if (cents < 10)
        cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3) ; i++)
        num = num.substring(0, num.length - (4 * i + 3)) + '.' + num.substring(num.length - (4 * i + 3));
    return (((sign) ? '' : '-') + num + ',' + cents);
}
