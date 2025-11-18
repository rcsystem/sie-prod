<?php


function changeEmail($email)
{
    if (site_url() == 'http://sie.walworth.es/') {
        $email = "hrivas@walworth.com.mx";
    } else {
        if ($email == "nflores@grupowalworth.com") {
            $email = "nflores@walworth.com.mx";
        } elseif ($email == "krubio@grupowalworth.com") {
            $email = "krubio@walworth.com.mx";
        } elseif ($email == "arodriguez@grupowalworth.com") {
            $email = "arodriguez@walworth.com.mx";
        } elseif ($email == "rgalvez@grupowalworth.com") {
            $email = "rgalvez@walworth.com.mx";
        } elseif ($email == "mflores@grupowalworth.com") {
            $email = "mflores@walworth.com.mx";
        } elseif ($email == "ibarreto@grupowalworth.com") {
            $email = "ibarreto@walworth.com.mx";
        } elseif ($email == "jwaisburd@grupowalworth.com") {
            $email = "jwaisburd@walworth.com.mx";
        } elseif ($email == "ocharfen@grupowalworth.com") {
            $email = "ocharfen@walworth.com.mx";
        } elseif ($email == "meritxellfe@grupowalworth.com") {
            $email = " meritxellfe@walworth.com.mx";
        } elseif ($email == "msanchez@grupowalworth.com") {
            $email = " msanchez@walworth.com.mx";
        } elseif ($email == "hmgarcia@grupowalworth.com") {
            $email = "hmgarcia@walworth.com.mx";
        } elseif ($email == "aramirez@grupowalworth.com") {
            $email = "aramirez@walworth.com.mx";
        } elseif ($email == "jcalvirez@grupowalworth.com") {
            $email = "jcalvirez@walworth.com.mx";
        } elseif ($email == "jhernandez@grupowalworth.com") {
            $email = "	jhernandez@biascg.com.mx";
        } elseif ($email == "acarrasco@grupowalworth.com") {
            $email = "acarrasco@walworth.com.mx";
        }
    }
    return $email;
}
