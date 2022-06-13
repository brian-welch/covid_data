<?php

function myLoad($class) {
    foreach(['queries', 'componants', 'elements', 'forms', 'base', 'functions'] as $prefix) {
        if(file_exists("{$_SERVER['DOCUMENT_ROOT']}/covid_data/lib/$prefix/$class.php")) {
            include_once("{$_SERVER['DOCUMENT_ROOT']}/covid_data/lib/$prefix/$class.php");
        }
    }
}
spl_autoload_register('myLoad');

GlobalVariables::errorBundle();


include "{$_SERVER['DOCUMENT_ROOT']}/covid_data/page_blocks/header_blocks/header_index.php";

include "{$_SERVER['DOCUMENT_ROOT']}/covid_data/page_blocks/body_blocks/body_index.php";

include "{$_SERVER['DOCUMENT_ROOT']}/covid_data/page_blocks/footer_blocks/footer_index.php";
