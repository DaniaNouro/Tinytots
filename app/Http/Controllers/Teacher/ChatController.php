<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Message;
use App\Models\Student;
use App\Events\MessageSent;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Models\ClassRoomStudent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        // إيجاد أو إنشاء محادثة جديدة بين المعلمين والأهل
        $conversation = Conversation::firstOrCreate([
            'teacher_id' => Auth::id(),
            'parent_id' => $request->parent_id,
        ]);

        // إنشاء رسالة جديدة
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }
/*____________________________________________________________________________*/

public function index($parentId)
{
    // العثور على المحادثة بين المعلم والأهل
    $conversation = Conversation::where('teacher_id', Auth::id())
                                ->where('parent_id', $parentId)
                                ->firstOrFail();

    // استرجاع جميع الرسائل في المحادثة مع معلومات المرسل
    $messages = $conversation->messages()->with('sender')->orderBy('created_at')->get();

    // إعداد الرد المثالي
    $response = [
        'conversation_id' => $conversation->id,
        'teacher_id' => $conversation->teacher_id,
        'parent_id' => $conversation->parent_id,
        'messages' => $messages->map(function ($message) {
            return [
                'id' => $message->id,
                'type' => $message->sender_id == Auth::id() ? 'sent' : 'received', // تحديد نوع الرسالة
                'sender_id' => $message->sender_id,
                'message' => $message->message,
                'created_at' => $message->created_at->toIso8601String(),
                'sender' => [
                    'id' => $message->sender->id,
                    'email' => $message->sender->email,
                    'image' => $message->sender->image,
                ],
            ];
        })
    ];

    return response()->json($response);
}
   /*______________________________________________________________________________*/
    public function getParentsForTeacher()
    {
        $teacher = Auth::user()->teacher;
        if (!$teacher) {
            return response()->json(['data' => [], 'message' => 'Teacher not found'], 404);
        }
    
        $classRooms = $teacher->class_rooms()->with('students')->get();
    
        if ($classRooms->isEmpty()) {
            return response()->json(['data' => [], 'message' => 'No classrooms found for this teacher'], 404);
        }
    
        $students = collect([]);
        foreach ($classRooms as $classRoom) {
            $studentsInClassRoom = $classRoom->students;
            $students = $students->merge($studentsInClassRoom);
        }
    
        if ($students->isEmpty()) {
            return response()->json(['data' => [], 'message' => 'No students found in the classrooms'], 404);
        }
    
        $parentData = $students->map(function ($student) {
            $parent = $student->parent;
            return [
                'id' => $parent->user->id,
                'father_name' => $parent->father_first_name . ' ' . $parent->father_last_name,
                'mother_name' => $parent->mother_first_name . ' ' . $parent->mother_last_name,
            ];
        })->unique('id');
    
        return response()->json(['data' => $parentData, 'message' => 'Parent data retrieved successfully']);
    }
}

