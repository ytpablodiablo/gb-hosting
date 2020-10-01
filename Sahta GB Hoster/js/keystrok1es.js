// Sprečavanje,krađa postojećeg šablona //
// *****   Kreirao Nikola Radišić   ***** //

document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
});  // Sprečavanje desnog klika

$(document).keydown(function(event){
    if(event.keyCode==123){
        return false;  // Sprečavanje tipke F12
    }

    else if(event.ctrlKey && event.shiftKey && event.keyCode==73){        
        return false;  // Sprečavanje CTRL+SHIFT+I
    }

    else if(event.ctrlKey && event.keyCode==85){        
        return false;  // Sprečavanje CTRL+U
    }

    else if(event.ctrlKey && event.keyCode==83){        
        return false;  // Sprečavanje CTRL+S
    }

    else if(event.ctrlKey && event.keyCode==70){        
        return false;  // Sprečavanje CTRL+F
    }
});