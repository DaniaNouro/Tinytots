<?php

namespace App\Http\Controllers\Parent;

use App\Models\Message;
use App\Models\Student;
use App\Events\MessageSent;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        // إيجاد أو إنشاء محادثة جديدة بين الأهل والمعلمين
        $conversation = Conversation::firstOrCreate([
            'teacher_id' => $request->teacher_id,
            'parent_id' => Auth::id(),
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

 
/*___________________________________________________________________________*/
    
public function index($teacherId)
{
    // العثور على المحادثة بين المعلم والأهل
    $conversation = Conversation::where('teacher_id',$teacherId )
                                ->where('parent_id', Auth::id())
                                ->firstOrFail();

    // استرجاع جميع الرسائل في المحادثة مع معلومات المرسل
    $messages = $conversation->messages()->with('sender')->orderBy('created_at')->get();

    // إعداد الرد المثالي
    $response = [
        'channel_name' => 'conversation.' . $conversation->id,
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

/*___________________________________________________________________________________-*/
    public function getTeacher($studentId) {
      
        $student = Student::find($studentId);
    
      
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }
    
      
        $class = $student->class_rooms()->first();
    
     
        if (!$class) {
            return response()->json(['message' => 'Class not found for the student'], 404);
        }
    
       
        $teachers = $class->teachers()->get();
    
      
        if ($teachers->isEmpty()) {
            $response = [];
        } else {
            
            $response = $teachers->map(function ($teacher) {
                return [
                    'id' => $teacher->user->id,
                    'first_name' => $teacher->first_name,
                    'last_name' => $teacher->last_name,
                    'phone_number' => $teacher->phone_number
                ];
            });
        }
    
        return response()->json([
            'class_room_id' => $class->id,
            'teachers' => $response
        ]);
    }



      
















    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'teacher_id' => 'required|exists:users,id',
    //         'message' => 'required|string',
    //     ]);

    //     // Find or create a conversation between parent and teacher
    //     $conversation = Conversation::firstOrCreate([
    //         'teacher_id' => $request->teacher_id,
    //         'parent_id' => Auth::id(),
    //     ]);

    //     $message = Message::create([
    //         'conversation_id' => $conversation->id,
    //         'sender_id' => Auth::id(),
    //         'message' => $request->message,
    //     ]);

    //  broadcast(new MessageSent($message))->toOthers();

    //     return response()->json($message);
    // }
}
