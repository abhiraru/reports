<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\UserApppUsage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request){
        $date = $request->date ?? null;
        $final = [];
        if(!$date)
        {
            return view('report',compact('final'));
        }
        $startDate = Carbon::parse($date)->startOfDay();
        $endDate = Carbon::parse($date)->endOfDay();
        $interval = 5;
        $data = collect();
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        while ($startDate <= $endDate) {
            $dataQuery = UserApppUsage::select(
                'productivity_level',
                'start_time',
                'app_name',
                'duration',
            )
            ->where('usage_date', $startDate->format('Y-m-d'))
            ->whereBetween('start_time', [
                $startDate->format('Y-m-d H:i:s'),
                $startDate->addMinutes($interval)->format('Y-m-d H:i:s')
            ])
            ->get();
            if(!$dataQuery->isEmpty())
            {
                    $appname = [];
                    foreach ($dataQuery as $data) {
                        if (isset($appname[$data->app_name])) {
                            $appname[$data->app_name]++;
                        } else {
                            $appname[$data->app_name] = 1;
                        }
                    }
                    $totalDuration = $dataQuery->sum('duration');
                    $unproductive = $dataQuery->where('productivity_level', 0)->sum('duration');
                    $neutral = $dataQuery->where('productivity_level', 1)->sum('duration');
                    $productive = $dataQuery->where('productivity_level', 2)->sum('duration');
                        $final []=[
                            'start' => $dataQuery[0]->start_time ?? 0,
                            'appname' => $appname,
                            'unproductive' => ($unproductive != 0 && $unproductive != 0)?($unproductive/$totalDuration) * 100 : 0,
                            'neutral' => ($neutral != 0 && $unproductive != 0)?($neutral/$totalDuration) * 100 :0,
                            'productive' => ($productive != 0 && $unproductive != 0)?($productive/$totalDuration) * 100:0,

                        ];
                $startDate->addMinutes($interval);
            }
        }
        return view('report',compact('final'));
    }

    public function report(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required',
        ]);
        $data = UserApppUsage::where('usage_date',$request->date)->get();
    }
}
