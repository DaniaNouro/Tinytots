<?php

namespace App\Http\Controllers\Teacher;
use App\Services\CalendarService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lesson;
use App\Models\Student;

class CalendarController extends Controller
{
    private $calendarService;
    public function  __construct(CalendarService $calendarService){
      $this->calendarService=$calendarService;
    }
    public function index($day)
    { 
      $weekDays    = Lesson::WEEK_DAYS;
      $data=$this->calendarService->getLessonsForTeacherOnDay($weekDays,$day);
      return $data;
   }
}