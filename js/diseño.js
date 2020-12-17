$(document).ready(function(){
    diseño();
  });

  function diseño(){
    var zindex = 10;
    
    $("a.toggle-info").click(function(e){
      e.preventDefault();
     
      var isShowing = false;

      console.log($(this).parents("div.card").hasClass("show"));
  
      if ($(this).parents("div.card").hasClass("show")) {
        
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
          $(this).parents("div.card")
            .css({zIndex: zindex})
            .addClass("show");
  
        }
  
        zindex++;
  
      } else {
        // no cards in view
        $("div.cards")
          .addClass("showing");
        $(this).parents("div.card")
          .css({zIndex:zindex})
          .addClass("show");
  
        zindex++;
      }
      
    });
  }