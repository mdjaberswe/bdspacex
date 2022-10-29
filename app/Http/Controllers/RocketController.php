<?php

namespace App\Http\Controllers;

use App\Library\RocketLaunch;
use App\Models\Rocket;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RocketController extends Controller
{
    /**
     * Rocket launch form page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('rocket.launch');
    }

    /**
     * Estimate time of rocket launch.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function estimateTime(Request $request)
    {
        $data = $request->all();
        $validation = validator($data, [
            'rocket' => 'required|in:a,b,c',
            'time'  => 'required|date',
        ]);

        // Update posted data if validation passes.
        if ($validation->passes()) {
            $launch_time = $this->ampm_to_sql_datetime($request->time);
            $journey_minutes = RocketLaunch::getEstimatedTime($launch_time, $request->rocket);
            $estimate_time = \Carbon\Carbon::parse($launch_time)->addMinutes($journey_minutes);

            // Launch log data store in DB
            Rocket::create([
                'rocket' => $request->rocket,
                'launch_time' => $launch_time,
                'estimate_return_time' => $estimate_time,
            ]);

            return response()->json(['status' => true, 'message' => 'Estimate time of coming back: ' . $estimate_time, 'reset' => false]);
        }

        return response()->json([
            'status' => false,
            'errors' => $validation->getMessageBag()->toArray(),
        ]);
    }

    /**
     * AmPm date format to SQL supported DateTime format.
     *
     * @param string $ampm
     *
     * @return string
     */
    private function ampm_to_sql_datetime($ampm)
    {
        $divider   = strpos($ampm, ' ');
        $date      = substr($ampm, 0, $divider);
        $time      = substr($ampm, $divider + 1);
        $strtotime = strtotime($time);
        $sql_time  = date('G:i:s', $strtotime);

        return $date . ' ' . $sql_time;
    }
}
