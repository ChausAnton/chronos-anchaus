<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use DB;


class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCalendarsForUser()
    {
        if(!auth()->user())
            return response("Forbidden", 403);

        $user_id = auth()->user()->id;
        return DB::select("select * from calendars where calendar_author_id = $user_id;");

    }

    public function getCalendarsWithEvents()
    {
        if(!auth()->user())
            return response("Forbidden", 403);

        $user_id = auth()->user()->id;
        $calendars = DB::select("select * from calendars where calendar_author_id = $user_id;");
        $data = [];
        foreach($calendars as $calendar) {
            $temp = [
                'calendar' => $calendar,
                'events' => []
            ];

            $event_calendar_id = $calendar->id;
            $Events = DB::select("select * from events where event_calendar_id = $event_calendar_id;");
            foreach($Events as $event) { 
                array_push($temp['events'], $event);
            }
            array_push($data, $temp);
        }
        return  $data;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function CreateCalendar(Request $request)
    {
        $validated = $request->validate([
            'calendar_title'=> 'required|string'
        ]);
        if(auth()->user()) {
            $data = [
                'calendar_author_id' => auth()->user()->id,
                'calendar_title' => $validated['calendar_title'],
            ];
            return Calendar::create($data);
        }
        return response("Forbidden", 403);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function getCalendar($id)
    {
        $calendar = Calendar::find($id);

        if(!$calendar) 
            return response("not found", 404);

        if(auth()->user() && auth()->user()->id == $calendar->calendar_author_id) {
            return $calendar;
        }
        
        return response("Forbidden", 403);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calendar $calendar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function DeleteCalendar(Calendar $calendar, $id)
    {

        $calendar = Calendar::find($id);

        if(!$calendar) 
            return response("not found", 404);

        if(auth()->user() && auth()->user()->id == $calendar->calendar_author_id) {
            return Calendar::destroy($id);
        }

        return response("Forbidden", 403);
        
    }
}
