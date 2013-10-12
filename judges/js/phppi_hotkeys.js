$(document).keydown(function(e){
    if (e.keyCode == 37 && phppi_enable_hotkeys == 1) 
    { 
       if (phppi_prev_image != '') { document.location = phppi_prev_image; }
       return false;
    }
    if (e.keyCode == 38 && phppi_enable_up_hotkey == 1) 
    { 
       document.location = phppi_up_folder;
       return false;
    }
    if (e.keyCode == 39 && phppi_enable_hotkeys == 1) 
    { 
       if (phppi_next_image != '') { document.location = phppi_next_image; }
       return false;
    }
});