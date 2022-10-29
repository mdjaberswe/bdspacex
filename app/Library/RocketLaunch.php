<?php

namespace App\Library;

class RocketLaunch
{
    /**
     *  Kármán line as space beginning 100 kilometres above Earth
     */
    const KARMAN_LINE = 100;

    /**
     * Empty Space Distance between Earth and Space Station in KM
     */
    const START_EMPTY_SPACE_DISTANCE = 308;

    /**
     * Return Empty Space Distance between Space Station and Earth in KM
     */
    const RETURN_EMPTY_SPACE_DISTANCE = 380;

    /**
     * Space Station staying hours
     */
    const SPACE_STATION_TIME = 6;

    /**
     * Get estimated time of coming back three rockets
     *
     * @param DateTime $launch_time
     * @param string $rocket_type a|b|c
     *
     * @return string
     */
    public static function estimatedTimeToComeBack($launch_time, $rocket_type)
    {
        $atmosphere_accleration = 50;

        // Atmospheric speed (Km/s)
        $atmosphere_speed = 0;

        // Linear speed (Km/s) in Empty Space
        $linear_speed = 0;

        if ($rocket_type == 'a') {
            $atmosphere_speed = 2.77;
            $linear_speed = 3.56;
        } elseif ($rocket_type == 'b') {
            $atmosphere_speed = 3.8;
            $linear_speed = 3;
        } elseif ($rocket_type == 'c') {
            $atmosphere_speed = 3;
            $linear_speed = 4;
        }

        // Begin journey time in atmosphere and empty space
        $start_atmospheric_time = sqrt((2 * self::KARMAN_LINE) / $atmosphere_accleration); // t = sqrt(2s/a)
        $start_empty_space_time = self::START_EMPTY_SPACE_DISTANCE / $linear_speed; // t = s/u

        // Return journey time in empty space and atmosphere
        $return_empty_space_time = self::RETURN_EMPTY_SPACE_DISTANCE / $linear_speed; // t = s/u
        $return_atmospheric_time = ($linear_speed * 1000) / 9.8; // t = u/g

        // Journey time in Minutes
        $journey_time = ($start_atmospheric_time + $start_empty_space_time + $return_empty_space_time + $return_atmospheric_time) / 60;
        $total_time = $journey_time + self::SPACE_STATION_TIME * 60;

        return $total_time;
    }
}
