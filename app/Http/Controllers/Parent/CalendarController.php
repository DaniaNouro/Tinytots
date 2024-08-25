<?php

namespace App\Http\Controllers\Parent;
use App\Models\Lesson;
use App\Services\CalendarService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index(CalendarService $calendarService,Request $request)
    {   
        $weekDays    = Lesson::WEEK_DAYS;
       
        $day=$request['day'];
        $calendarData = $calendarService->generateCalendarDataToParent($weekDays,$day);
        return  $calendarData;
       
}


}


