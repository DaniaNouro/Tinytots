<?php

namespace App\Http\Controllers;
use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    // public function index($conversationId)
    // {
    //     $conversation = Conversation::findOrFail($conversationId);
    //     return $conversation->messages()->with('sender')->get();
    // }

    // public function index($parentId)
    // {
    //     $conversation = Conversation::where('Parent_id',Auth::id())
    //                                 ->where('teacher_id', $parentId)
    //                                 ->firstOrFail();

    //     $messages = $conversation->messages()->with('sender')->get();

    //     return response()->json($messages);
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'conversation_id' => 'required|exists:conversations,id',
    //         'message' => 'required|string',
    //     ]);

    //     $message = Message::create([
    //         'conversation_id' => $request->conversation_id,
    //         'sender_id' => Auth::id(),
    //         'message' => $request->message,
    //     ]);



    //     MessageSent::dispatch($message);
    //     broadcast(new MessageSent($message))->toOthers();

    //     return response()->json($message);
  //  }



  public function index($conversationId)
  {
      $conversation = Conversation::findOrFail($conversationId);
  
      $messages = $conversation->messages()->with('sender')->get();
  
      $formattedMessages = $messages->map(function ($message) use ($conversation) {
          return [
              'id' => $message->id,
              'conversation_id' => $message->conversation_id,
              'sender_id' => $message->sender_id,
              'message' => $message->message,
              'created_at' => $message->created_at->toIso8601String(),
              'is_incoming' => $message->sender_id !== $conversation->user_id, // تحقق ما إذا كان المرسل ليس هو المستخدم
              'sender' => [
                  'id' => $message->sender->id,
                  'email' => $message->sender->email,
                  'image' => $message->sender->image, // يمكنك إضافة الصورة إذا كانت متاحة
              ],
          ];
      });
  
      return response()->json($formattedMessages);
  }




    
    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }
}

