<?php

    function log_Error($tag, $msg){
        error_log('['.$tag.'] :::: '.$msg ,0); 
    }


 ?>