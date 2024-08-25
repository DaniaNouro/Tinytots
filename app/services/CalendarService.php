<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;

class CalendarService
{
    public function generateCalendarData($weekDays, $classId = null)
    {
        $calendarData = [];
        $timeRange = (new TimeService)->generateTimeRange(config('app.calendar.start_time'), config('app.calendar.end_time'));
        $lessonsQuery   = Lesson::with('class', 'teacher');

        if ($classId) {
            $lessonsQuery->where('class_id', $classId);
        }
        $lessons = $lessonsQuery
            ->CalendarByRoleOrClassId()
            ->get();

        foreach ($timeRange as $time) {
            $timeText = $time['start'] . ' - ' . $time['end'];

            $calendarData[$timeText] = [];

            foreach ($weekDays as $day => $index) {


                $lessonsAtTime = $lessons->filter(function ($lesson) use ($day, $time) {
                    return $lesson->weekday == $day &&
                        $lesson->start_time == $time['start'];
                });

                if ($lessonsAtTime->isNotEmpty()) {
                    foreach ($lessonsAtTime as $lesson) {

                        $calendarData[$timeText][] = [
                            'class_name'   => $lesson->class->class_name,
                            'teacher_name' => $lesson->teacher->first_name,
                            'subject_name' => $lesson->subject->name,
                            'rowspan'      => $lesson->difference / 30 ?? ''
                        ];
                    }
                } else if (!$lessons->where('weekday', $day)->where('start_time', '<', $time['start'])->where('end_time', '>=', $time['end'])->count()) {

                    array_push($calendarData[$timeText], 1);
                } else {
                    array_push($calendarData[$timeText], 0);
                }
            }
        }

        return $calendarData;
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////// 
    public function generateCalendarDataToParent($weekDays, $classId = null, $selectedDay = null)
    {
        $calendarData = [];
        $timeRange = (new TimeService)->generateTimeRange(config('app.calendar.start_time'), config('app.calendar.end_time'));

        // التحقق من اليوم المحدد في بداية التابع
        if ($selectedDay && !array_key_exists($selectedDay, $weekDays)) {
            return $calendarData;
        }

        $lessonsQuery = Lesson::with('class', 'teacher', 'subject');

        if ($classId) {
            $lessonsQuery->where('class_id', $classId);
        }

        // الحصول على الدروس لليوم المحدد فقط
        if ($selectedDay) {
            $lessons = $lessonsQuery
                ->CalendarByRoleOrClassId()
                ->where('weekday', $selectedDay)
                ->get();
        } else {
            $lessons = $lessonsQuery->CalendarByRoleOrClassId()->get();
        }

        foreach ($timeRange as $time) {
            foreach ($weekDays as $day => $index) {
                if ($selectedDay && $selectedDay != $day) {
                    continue;
                }

                $lessonsAtTime = $lessons->filter(function ($lesson) use ($day, $time) {
                    return $lesson->weekday == $day && $lesson->start_time == $time['start'];
                });

                foreach ($lessonsAtTime as $lesson) {
                    $calendarData[] = [
                        'subject' => $lesson->subject->name,
                        'start' => $this->formatDateTime($day, $time['start']),
                        'end' => $this->formatDateTime($day, $time['end']),
                        'className' => $lesson->class->class_name,
                        'teacherName' => $lesson->teacher->first_name . ' ' . $lesson->teacher->last_name
                    ];
                }
            }
        }

        return $calendarData;
    }

    private function formatDateTime($day, $time)
    {
        // تحويل يوم الأسبوع والوقت إلى تنسيق تاريخ ووقت كامل
        $date = now()->startOfWeek()->addDays($day)->format('Y-m-d');
        return $date . 'T' . $time;
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////
    public function getLessonsForTeacherOnDay($weekDays, $selectedDay)
    {
        $user = Auth::user();

        // التحقق من أن المستخدم هو أستاذ
        if (!$user->hasRole('teacher')) {
            abort(403, 'Unauthorized action.');
        }

        $calendarData = [];
        $timeRange = (new TimeService)->generateTimeRange(config('app.calendar.start_time'), config('app.calendar.end_time'));

        // التحقق من اليوم المحدد في بداية التابع
        if ($selectedDay && !array_key_exists($selectedDay, $weekDays)) {
            return $calendarData;
        }

        $lessonsQuery = Lesson::with('class', 'teacher', 'subject');


        // الحصول على الدروس لليوم المحدد فقط
        if ($selectedDay) {
            $lessons = $lessonsQuery
                ->where('weekday', $selectedDay)
                ->get();
        } else {
            $lessons = $lessonsQuery->CalendarByRoleOrClassId()->get();
        }

        foreach ($timeRange as $time) {
            foreach ($weekDays as $day => $index) {
                if ($selectedDay && $selectedDay != $day) {
                    continue;
                }

                $lessonsAtTime = $lessons->filter(function ($lesson) use ($day, $time) {
                    return $lesson->weekday == $day && $lesson->start_time == $time['start'];
                });

                foreach ($lessonsAtTime as $lesson) {
                    $calendarData[] = [
                        'subject' => $lesson->subject->name,
                        'start' => $this->formatDateTime($day, $time['start']),
                        'end' => $this->formatDateTime($day, $time['end']),
                        'className' => $lesson->class->class_name,
                        'teacherName' => $lesson->teacher->first_name . ' ' . $lesson->teacher->last_name
                    ];
                }
            }
        }

        return  $calendarData;
    }
}

 ///////////////////////////////////////////////////////////////////////////////////////////////////
    // public function generateCalendarDataToParent($weekDays, $classId = null, $selectedDay = null)
    // {
    //     $calendarData = [];
    //     $timeRange = (new TimeService)->generateTimeRange(config('app.calendar.start_time'), config('app.calendar.end_time'));
    //     $lessonsQuery = Lesson::with('class', 'teacher', 'subject');
    
    //     if ($classId) {
    //         $lessonsQuery->where('class_id', $classId);
    //     }
    
    //     // الحصول على الدروس لليوم المحدد فقط
    //     if ($selectedDay) {
    //         $lessons = $lessonsQuery
    //             ->CalendarByRoleOrClassId()
    //             ->where('weekday', $selectedDay)
    //             ->get();
    //     } else {
    //         $lessons = $lessonsQuery->CalendarByRoleOrClassId()->get();
    //     }
    
    //     foreach ($timeRange as $time) {
    //         foreach ($weekDays as $day => $index) {
    //             //  التحقّق  من  وجود  الِ  يوم  الِ  مُحدد  داخل  مُصفوفة  الِ  أيام  الِ  أسبوع 
    //             if ($selectedDay && !array_key_exists($selectedDay, $weekDays)) {
    //                 continue;
    //             }
    
    //             $lessonsAtTime = $lessons->filter(function ($lesson) use ($day, $time) {
    //                 return $lesson->weekday == $day && $lesson->start_time == $time['start'];
    //             });
    
    //             foreach ($lessonsAtTime as $lesson) {
    //                 $calendarData[] = [
    //                     'subject' => $lesson->subject->name,
    //                     'start' => $this->formatDateTime($day, $time['start']),
    //                     'end' => $this->formatDateTime($day, $time['end']),
    //                     'className' => $lesson->class->class_name,
    //                     'teacherName' => $lesson->teacher->first_name . ' ' . $lesson->teacher->last_name
    //                 ];
    //             }
    //         }
    //     }
    
    //     return $calendarData;
    // }
    
    // private function formatDateTime($day, $time)
    // {
    //     // تحويل يوم الأسبوع والوقت إلى تنسيق تاريخ ووقت كامل
    //     $date = now()->startOfWeek()->addDays($day)->format('Y-m-d');
    //     return $date . 'T' . $time;
    // }
    
   



  // public function generateCalendarDataToParent($weekDays, $classId = null)
    // {
    //     $calendarData = [];
    //     $timeRange = (new TimeService)->generateTimeRange(config('app.calendar.start_time'), config('app.calendar.end_time'));
    //     $lessonsQuery = Lesson::with('class', 'teacher', 'subject');
       
    //     if ($classId) {
    //         $lessonsQuery->where('class_id', $classId);
    //     }

    //     $lessons = $lessonsQuery->CalendarByRoleOrClassId()->get();

    //     foreach ($timeRange as $time) {
    //         foreach ($weekDays as $day => $index) {
    //             $lessonsAtTime = $lessons->filter(function ($lesson) use ($day, $time) {
    //                 return $lesson->weekday == $day && $lesson->start_time == $time['start'];
    //             });

    //             foreach ($lessonsAtTime as $lesson) {
    //                 $calendarData[] = [
    //                     'subject' => $lesson->subject->name,
    //                     'start' => $this->formatDateTime($day, $time['start']),
    //                     'end' => $this->formatDateTime($day, $time['end']),
    //                     'className' => $lesson->class->class_name,
    //                     'teacherName' => $lesson->teacher->first_name . ' ' . $lesson->teacher->last_name
    //                 ];
    //             }
    //         }
    //     }
        
    //     return $calendarData;
    // }

    // private function formatDateTime($day, $time)
    // {
    //     // تحويل يوم الأسبوع والوقت إلى تنسيق تاريخ ووقت كامل
    //     $date = now()->startOfWeek()->addDays($day)->format('Y-m-d');
    //     return $date . 'T' . $time;
    // }