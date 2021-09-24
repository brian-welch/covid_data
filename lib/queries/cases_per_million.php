<?php
function myLoad($class) {
    $dir = __DIR__;
    foreach(['queries', 'componants', 'elements', 'forms', 'base', 'functions'] as $prefix) {
        if(file_exists("{$_SERVER['DOCUMENT_ROOT']}/covid_data/lib/$prefix/$class.php")) {
            include_once("{$_SERVER['DOCUMENT_ROOT']}/covid_data/lib/$prefix/$class.php");
        }
    }
}
spl_autoload_register('myLoad');
//=============================================================================

class CasesPerMillion {
    private $db_details;

    function __construct(){

    }
}
