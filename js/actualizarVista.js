var dato = 0;
var permisos = 0;

$(document).ready(()=>{
    
    permisos = $("#permiso").val();
    actualizarVista(permisos);
    
})

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

function actualizarVista(permisos){

    

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


                });

            });

                $.ajax({
                url: "php/actBuscar.php",
                method: "POST",
                data: {x:dato},
                cache: "false",
                dataType: "json",
                }).done(function(data){
                    
                    $("#resultado").html("");
                    
                    data.forEach(i => {

                        if(i[4] == 0){
                            var imagen = "https://source.unsplash.com/400x300/?wave";
                        }else{
                            var imagen = i[4];
                        }
                        
                        var resultado;

                        if(permisos==1){
                            resultado = `
                            
                                <div class="card">
                                <div class="card__image-holder">
                                <img class="card__image" src="${imagen}" alt="wave" width="400" height="300"/>
                                </div>
                                <div class="card-title">
                                <a class="toggle-info btn">
                                    <span class="left"></span>
                                    <span class="right"></span>
                                </a>
                                <h2>
                                    ${i[1]}
                                    <small>Precio en dolares: ${i[2]} $<br /></small>
                                    <small>Precio en Bolivares: ${formatNumber(i[2]*i[3])} Bs.S</small>
                                </h2>
                                </div>
                                <div class="card-flap flap1">
                                <div class="card-description">
                                    <p>Cambiar Nombre: <input type="text" id="nombre-${i[0]}" class="nuevoNombre"></p>
                                    <p>Cambiar Precio $: <input type="text" id="precio-${i[0]}" class="nuevoPrecio""></p>
                                </div>
                                <div class="card-flap flap2">
                                    <div class="card-actions">
                                        <input type="button" class="btn btn-editar" value="Editar" data-id="${i[0]}" />
                                    </div>
                                </div>
                                </div>
                        </div>
                        `;
                        }else{
                            resultado = `
                            
                                <div class="card">
                                <div class="card__image-holder">
                                <img class="card__image" src="${imagen}" alt="wave" width="400" height="300"/>
                                </div>
                                <div class="card-title">
                                <h2>
                                    ${i[1]}
                                    <small>Precio en dolares: ${i[2]} $<br /></small>
                                    <small>Precio en Bolivares: ${formatNumber(i[2]*i[3])} Bs.S</small>
                                </h2>
                                </div>
                                </div>
                        </div>
                        `;
                        }
                        
                        
                        $("#resultado").append(resultado);
                        
                    });
                    editar(permisos);
                    diseño();
                        
                    
                });
            
        });
}



function editar(permisos){

    $('.btn-editar').on('click', function(e){

        e.preventDefault();
        var id = $(this).data("id");
        var nombre = $(`#nombre-${id}`).val();
        var precio = $(`#precio-${id}`).val();
        var boton = $(this);

        var data = {
            id:id,
            nombre:nombre,
            precio:precio,
        }

        $.ajax({
            url: "php/actEditar.php",
            method: "POST",
            data: data,
            cache: "false",
            dataType: "json",
            beforeSend:function(){
                boton.val("Conectando...");
            }}).done(function(data){

                boton.val("Editar");
                

                if(data[0] == 0){
                    
                    alert("No se edito el producto, Ingrese los nuevos datos")
                    
                }else{ 

                    var zindex = 10;

                    e.preventDefault();
     
                    var isShowing = false;
                
                    if (boton.parents("div.card").hasClass("show")) {
                        
                        isShowing = true
                    }
                
                    if ($("div.cards").hasClass("showing")) {
                        // a card is already in view
                        $("div.card.show")
                        .removeClass("show");
                
                        if (isShowing) {
                        // this card was showing - reset the grid
                        $("div.cards")
                            .removeClass("showing");
                        } else {
                        // this card isn't showing - get in with it
                        boton.parents("div.card")
                            .css({zIndex: zindex})
                            .addClass("show");
                
                        }
                
                        zindex++;
                
                    } else {
                        // no cards in view
                        $("div.cards")
                        .addClass("showing");
                        boton.parents("div.card")
                        .css({zIndex:zindex})
                        .addClass("show");
                
                        zindex++;
                    }

                    var text = "";

                    for (var i = 1; i < data.length; i+=1) {
                        text += data[i];
                        text += "\n"; 
                    }

                    alert(text);
                    actualizarVista(permisos);

                }
                    
                

                
            });
    });
}