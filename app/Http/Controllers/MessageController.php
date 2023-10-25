<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function chat($conversationId) {
        $existingConversation = Conversation::where(function ($query) use ($conversationId) {
            $query->where('user1_id', Auth::id())
                ->where('user2_id', $conversationId);
        })->orWhere(function ($query) use ($conversationId) {
            $query->where('user1_id', $conversationId)
                ->where('user2_id', Auth::id());
        })->first();
    
        $conversations = Conversation::where(function ($query) use ($conversationId) {
            $query->where('user1_id', Auth::id())
                ->where('user2_id', $conversationId);
        })->orWhere(function ($query) use ($conversationId) {
            $query->where('user1_id', $conversationId)
                ->where('user2_id', Auth::id());
        })->first();

        $user = User::find($conversationId);

        $messages = Message::where('conversation_id', $existingConversation->id)
        ->with('conversation', 'sender')
        ->get();

        // return response()->json($messages);

        return Inertia::render('Chat', [
            'conversations' => $conversations,
            'user' => $user,
            'messages' => $messages,
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'content' => 'required',
        ]);
        $content = $request->content;
        $id = $request->conversationId;
        Message::create([
            'conversation_id' => $id,
            'sender_id' => Auth::id(),
            'content' => $content,
        ]);

        return redirect()->back();
    }

    public function destroy(Message $message){
        if(!is_null($message)){
            $message->delete();
            return redirect()->back();
        }
    }
    
}
