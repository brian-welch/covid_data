$( document ).ready(function() {
    setPageName();

    $(".accordion-button").on('click',function () {
        returnToHere(document.getElementById("accordianMarker").offsetTop);
    });

    $(".main-menu-button").on("click", function(event){
        $(".layout-selector").addClass("hideMe");
        returnToHere(0);
        initiateGraphRender(event);
    });
    
    $(".menuClickable").on("click", function(){
        $(".menuSort").removeClass("show");
    });

    $(".sort-button").on('click', function(){
        sortingByBtnActions(this);
    });

    $("#filterMenuButton, #closefilters").on('click', (btn) => {
        resetFilters();
    });

    $("#applyFilters").on('click', function(event){
        $(".loading-graphs-wheel").removeClass("hideMe");
        $(".layout-selector").addClass("hideMe");
        $(".compare-country-toggle-switch").addClass("hideMe");
        applyFilters();
    });

});

