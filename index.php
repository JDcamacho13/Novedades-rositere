<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Novedades Rositere</title>
            <style>
                .producto{
                    border: solid black 1px;                    
                    background-color: #eeeeee;
                    width: 40%;
                    margin: auto;
                    margin-bottom: 1px;
                }
                h1{
                    text-align: center;
                }
                h3{
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <h1>Novedades Rositere</h1>
            
            <div id="dolar">
                <h2>Precio del Dolar: Cargando....</h2>
            </div>
            <div id="resultado"></div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script>
                var dato = 0;
                var dolar;

                $(document).ready(()=>{
                    $.ajax({
                        url: "php/actBuscarDolar.php",
                        method: "POST",
                        cache: "false",
                        dataType: "json",
                        }).done(function(data){
                            
                            
                            dolar = data[0]
                            $("#dolar").html(`<h2>Precio del Dolar: ${formatNumber(data[0])}<h2><h4>Cambiado el: ${data[1]}<h4>`)
                                
                                $.ajax({
                                url: "php/actBuscar.php",
                                method: "POST",
                                data: {x:dato},
                                cache: "false",
                                dataType: "json",
                                }).done(function(data){
                    
                                    data.forEach(i => {
                                        var resultado = `
                                            <div class="producto">
                                                <h3>${i[1]}</h3>
                                                Precio en dolares: ${i[2]}
                                                Precio en Bolivares: ${formatNumber(i[2]*dolar)}
                                            <div>
                                        `;

                                        $("#resultado").append(resultado);
                                    });
                                        
                                    
                    
                                    
                                });
                            
                        });

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
                
            </script>  
        </body>
        </html>
