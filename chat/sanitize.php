<?php
function sanitize($string) {
        $entity[0] = '/\&/';
        $entity[1] = '/</';
        $entity[2] = "/>/";
        $entity[3] = '/\n/';
        $entity[4] = '/"/';
        $entity[5] = "/'/";
        $entity[6] = "/%/";
        $entity[7] = '/\(/';
        $entity[8] = '/\)/';
        $entity[9] = '/\+/';
        $entity[10] = '/-/';
        $entity[11] = '/\//';
        $escaped[0] = '&amp;';
        $escaped[1] = '&lt;';
        $escaped[2] = '&gt;';
        $escaped[3] = '<br>';
        $escaped[4] = '&quot;';
        $escaped[5] = '&#x27;';
        $escaped[6] = '&#37;';
        $escaped[7] = '&#40;';
        $escaped[8] = '&#41;';
        $escaped[9] = '&#43;';
        $escaped[10] = '&#45;';
        $escaped[11] = '&#x2F;';
        return preg_replace($entity,$escaped,$string);
    }
?>
