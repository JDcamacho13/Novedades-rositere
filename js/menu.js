$(document).ready(function(){

    var width;
    if(screen.width*0.4 > 375){
        width = 375;
    }else{
        if(screen.width*0.4 < 280){
            width = 280;
        }else{
            width = screen.width*0.4;
        }
    }
    $("#menu").css("right", `${-width}px`)

    $(window).resize(function (){
        width = $("#menu").width();

        $("#menu").css("right", `${-width}px`)
    });

    $(".usuario").click(function(){
        width = $("#menu").width();
        $("#menu").css("right", `0px`)

    });
    
    $(".exit").click(function(){
        width = $("#menu").width();
        $("#menu").css("right", `${-width}px`)

    });

});