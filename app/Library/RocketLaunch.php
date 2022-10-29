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
    const EMPTY_SPACE_DISTANCE = 308;

    /**
     * Return Empty Space Distance between Space Station and Earth in KM
     */
    const RETURN_EMPTY_SPACE_DISTANCE = 380;

    /**
     * Space Station staying minutes
     */
    const SPACE_STATION_TIME = 360;

    /**
     * Rockets information array (Units: velocity Km/s, accleration Km/s*2)
     *
     * @var array
     */
    protected $rockets = [
        'a' => ['atmosphere_speed' => 2.77, 'linear_speed' => 3.56, 'accleration' => 0.05],
        'b' => ['atmosphere_speed' => 3.8, 'linear_speed' => 3, 'accleration' => 0.05],
        'c' => ['atmosphere_speed' => 3, 'linear_speed' => 4, 'accleration' => 0.05],
    ];

    /**
     * Get estimated time of coming back three rockets
     *
     * @param DateTime $launch_time
     * @param string $rocket_type a|b|c
     *
     * @return string
     */
    public static function getEstimatedTime($launch_time, $rocket_name)
    {
        $rockets = with(new static)->rockets;

        if (! array_key_exists($rocket_name, $rockets)) {
            return 0;
        }

        // Rocket journey time from earth to space station
        $up_time = self::getUpTime($rockets[$rocket_name]['accleration'], $rockets[$rocket_name]['linear_speed']);

        // Rocket journey time from space station to earth
        $down_time = self::getDownTime($rockets[$rocket_name]['linear_speed']);

        // Journey time in Minutes
        $total_time = $up_time + self::SPACE_STATION_TIME + $down_time;

        return $total_time;
    }

    /**
     * Get Rocket journey time from earth to space station
     *
     * @param Numeric $accleration (Km/s*2)
     * @param Numeric $linear_speed (Km/s)
     *
     * @return Numeric (Minute)
     */
    public static function getUpTime($accleration, $linear_speed)
    {
        // Start journey time in atmosphere
        $atmosphere_time = sqrt((2 * self::KARMAN_LINE) / $accleration); // t = sqrt(2s/a)

        // Start journey time in empty space
        $empty_space_time = self::EMPTY_SPACE_DISTANCE / $linear_speed; // t = s/u

        // Total up time
        $up_time = ($atmosphere_time + $empty_space_time) / 60;

        return $up_time;
    }

    /**
     * Get rocket journey time from space station to earth
     *
     * @param Numeric $linear_speed (Km/s)
     *
     * @return Numeric (Minute)
     */
    public static function getDownTime($linear_speed)
    {
        // Return journey time in empty space and atmosphere
        $return_empty_space_time = self::RETURN_EMPTY_SPACE_DISTANCE / $linear_speed; // t = s/u

        // Return journey time in empty space and atmosphere
        $return_atmosphere_time = ($linear_speed * 1000) / 9.8; // t = u/g

        // Total down time
        $down_time = ($return_atmosphere_time + $return_atmosphere_time) / 60;

        return $down_time;
    }
}
