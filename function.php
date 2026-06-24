<?php

function amountInWords($number)
{
    $f = new NumberFormatter(
        "en",
        NumberFormatter::SPELLOUT
    );

    return ucfirst(
        $f->format($number)
    );
}