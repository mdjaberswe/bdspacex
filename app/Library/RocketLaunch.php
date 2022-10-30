<?php

namespace App\Library;

use Carbon\Carbon;

class RocketLaunch
{
    protected $rocket;
    protected $atmosphere_speed;
    protected $linear_speed;
    protected $accleration;

    /**
     *  Karman line as space beginning 100 kilometres above Earth
     */
    const ATMOSPHERE_DISTANCE = 100;

    /**
     * Empty Space Distance from Earth to Space Station in KM
     */
    const BEGIN_EMPTY_SPACE_DISTANCE = 308;

    /**
     * Empty Space Distance from Space Station to Earth in KM
     */
    const END_EMPTY_SPACE_DISTANCE = 380;

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
     * Create a new rocket launch instance
     *
     * @param string $rocket (a|b|c)
     *
     * @return void
     */
    public function __construct($rocket)
    {
        $this->rocket = $rocket;
        $this->atmosphere_speed = $this->rockets[$rocket]['atmosphere_speed'];
        $this->linear_speed = $this->rockets[$rocket]['linear_speed'];
        $this->accleration = $this->rockets[$rocket]['accleration'];
    }

    /**
     * Get estimated time when rocket will coming back to earth according to launch time
     *
     * @param string $launch_time
     *
     * @return \Carbon\Carbon
     */
    public function getEstimatedTime($launch_time)
    {
        return Carbon::parse($launch_time)->addMinutes($this->getJourneyMinutes());
    }

    /**
     * Get total journey time in Minutes
     *
     * @return numeric
     */
    public function getJourneyMinutes()
    {
        return ($this->getUpTime() + self::SPACE_STATION_TIME + $this->getDownTime());
    }

    /**
     * Get total up time in Minutes
     *
     * @return numeric
     */
    public function getUpTime()
    {
        return ($this->getUpAtmosphereTime() + $this->getUpEmptySpaceTime()) / 60;
    }

    /**
     * Get atmosphere time (seconds) when rocket is up to space station from earth.
     * Initial velocity u = 0; s = ut + 1/2 * (a * t * t) => t = sqrt(2s/a)
     *
     * @return numeric (unit: seconds)
     */
    public function getUpAtmosphereTime()
    {
        return sqrt((2 * self::ATMOSPHERE_DISTANCE) / $this->accleration);
    }

    /**
     * Get empty space time (seconds) when rocket is up to space station from earth.
     * In empty space accleration: a = 0; s = s = ut + 1/2 * (a * t * t) => t = s/u
     *
     * @return numeric
     */
    public function getUpEmptySpaceTime()
    {
        return self::BEGIN_EMPTY_SPACE_DISTANCE / $this->linear_speed;
    }

    /**
     * Get total down time in Minutes
     *
     * @return numeric
     */
    public function getDownTime()
    {
        return ($this->getDownEmptySpaceTime() + $this->getDownAtmosphereTime()) / 60;
    }

    /**
     * Get empty space time (seconds) when rocket is down to earth from space station.
     * In empty space accleration: a = 0; s = s = ut + 1/2 * (a * t * t) => t = s/u
     *
     * @return numeric
     */
    public function getDownEmptySpaceTime()
    {
        return self::END_EMPTY_SPACE_DISTANCE / $this->linear_speed;
    }

    /**
     * Get atmosphere time (seconds) when rocket is down to earth from space station.
     * Rocket fall into ocean so final velocity v = 0; v^2 = u^2 + 2 * (v-u/t) * s => t = 2s/u
     *
     * @return numeric
     */
    public function getDownAtmosphereTime()
    {
        return (2 * self::ATMOSPHERE_DISTANCE) / $this->linear_speed;
    }
}
