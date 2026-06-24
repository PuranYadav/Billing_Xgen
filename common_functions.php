<?php

function indian_currency($num)
{
    $num = sprintf("%.2f", $num);

    $parts = explode('.', $num);

    $integer = $parts[0];
    $decimal = $parts[1];

    if(strlen($integer) > 3)
    {
        $lastThree = substr($integer, -3);

        $restUnits = substr($integer, 0, -3);

        $restUnits = preg_replace(
            "/\B(?=(\d{2})+(?!\d))/",
            ",",
            $restUnits
        );

        $integer = $restUnits . "," . $lastThree;
    }

    return $integer . "." . $decimal;
}
?>
