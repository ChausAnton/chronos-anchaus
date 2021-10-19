<?php

namespace App\Http\Controllers;

use App\Models\Events;
use Illuminate\Http\Request;
use DB;

class EventController extends Controller
{

    public function getEventsForCalendar($id)
    {
        $calendar = DB::select("select * from calendars where id = $id;");

        if(!$calendar) 
            return response("not found", 404);

        if(auth()->user() && auth()->user()->id == $calendar[0]->calendar_author_id) {
            return DB::select("select * from events where event_calendar_id = $id;");
        }

        return response("Forbidden", 403);

    }

    public function createEventForCalendar(Request $request, $id)
    {

        $calendar = DB::select("select * from calendars where id = $id;");

        if(!$calendar) 
            return response(["message" => "not found"], 404);

        $validated = $request->validate([
            'event_content' => 'required|string',
            'event_title' => 'required|string',
            'event_date' => 'required|date|date_format:Y-m-d',
            'event_category' => 'required|in:arrangement,reminder,task',
            'event_duration' => 'required|date|date_format:Y-m-d'
        ]);

        if(auth()->user() && auth()->user()->id == $calendar[0]->calendar_author_id) {
            $data = [
                'event_author_id' => auth()->user()->id,
                'event_calendar_id' => $id,
                'event_content' => $validated['event_content'],
                'event_title' => $validated['event_title'],
                'event_date' => $validated['event_date'],
                'event_category' => $validated['event_category'],
                'event_duration' => $validated['event_duration']
            ];
            return Events::create($data);
        }
        return response(["message" => "Forbidden"], 403);

    }


    public function getEventForCalendar($id, $eventId)
    {
        $calendar = DB::select("select * from calendars where id = $id;");

        if(!$calendar) 
            return response("not found", 404);

        if(auth()->user() && auth()->user()->id == $calendar[0]->calendar_author_id) {
            return DB::select("select * from events where id = $eventId;");
        }

        return response("Forbidden", 403);
    }

    public function DeleteEventFromCalendar($id, $eventId)
    {
        $calendar = DB::select("select * from calendars where id = $id;");

        if(!$calendar) 
            return response("not found", 404);

        if(auth()->user() && auth()->user()->id == $calendar[0]->calendar_author_id) {
            return Events::destroy($eventId);
        }

        return response("Forbidden", 403);
    }
}
