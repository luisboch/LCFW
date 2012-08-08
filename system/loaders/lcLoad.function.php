<?php 

function lcImport($file, $path = LC_APPLICATION_DIR){
    require $path.$file;
}


function lcLoad($file){
    lcImport($file);
}

function lcSystemLoad($file, $path = LC_APPLICATION_DIR){
    lcImport($file, LC_SYSTEM_DIR);
}
?>
