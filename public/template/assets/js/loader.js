window.addEventListener("load", function(){
    var load_screen = document.getElementById("load_screen");
    document.body.removeChild(load_screen);

    /* calling this event here to be sure that the loader
    ends before search the te element tooltip */
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();
});
