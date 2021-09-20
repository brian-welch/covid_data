$( document ).ready(function() {
    setPageName();

    // loadGraphsFromURI_2();

    $(".main-menu_button").on("click", function(event){
        initiateGraphRender(event);
    });

});

