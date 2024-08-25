<?php

namespace App\Http\Controllers\Admine;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Services\CalendarService;
use Illuminate\Http\Request;


class CalendarController extends Controller
{
    public function index(CalendarService $calendarService,$classId)
    {
        $weekDays    = Lesson::WEEK_DAYS;
        $classId = $classId;
        $calendarData = $calendarService->generateCalendarData($weekDays,$classId);
        return  $calendarData;

}

}