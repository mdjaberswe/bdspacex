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
    public function launch()
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
            // Instantiate a new rocket launch
            $rocket_launch = new RocketLaunch($request->rocket);

            $launch_time = ampm_to_sql_datetime($request->time);
            $estimate_time = $rocket_launch->getEstimatedTime($launch_time);

            // Launch log data store in DB
            Rocket::create([
                'rocket' => $request->rocket,
                'launch_time' => $launch_time,
                'estimate_return_time' => $estimate_time,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Estimate time of coming back ' . $estimate_time,
                'reset' => false,
            ]);
        }

        return response()->json([
            'status' => false,
            'errors' => $validation->getMessageBag()->toArray(),
        ]);
    }
}
