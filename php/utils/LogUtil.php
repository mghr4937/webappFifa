<?php

    function log_Error($tag, $e){
        error_log('['.$tag.']('.$e->getLine().') :::: ERROR :::: '.$msg ,0); 
    }

    function log_Info($tag, $msg){
        error_log('['.$tag.'] :::: INFO :::: '.$msg ,0); 
    }

 ?>