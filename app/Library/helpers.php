<?php

/**
 * AmPm date format to SQL supported DateTime format.
 *
 * @param string $ampm
 *
 * @return string
 */
function ampm_to_sql_datetime($ampm)
{
    $divider   = strpos($ampm, ' ');
    $date      = substr($ampm, 0, $divider);
    $time      = substr($ampm, $divider + 1);
    $strtotime = strtotime($time);
    $sql_time  = date('G:i:s', $strtotime);

    return $date . ' ' . $sql_time;
}
