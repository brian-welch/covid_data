<?php

function myLoad($class) {
    $dir = __DIR__;
    foreach(['queries', 'componants', 'elements', 'forms', 'base', 'functions'] as $prefix) {
        if(file_exists("{$_SERVER['DOCUMENT_ROOT']}/lib/$prefix/$class.php")) {
            include_once("{$_SERVER['DOCUMENT_ROOT']}/lib/$prefix/$class.php");
        }
    }
}

spl_autoload_register('myLoad');

include 'page_blocks/header_blocks/header_index.php';


include 'page_blocks/body_blocks/body_index.php';


include 'page_blocks/footer_blocks/footer_index.php';


/*
$_SERVER['DOCUMENT_ROOT'] needed??
 */
