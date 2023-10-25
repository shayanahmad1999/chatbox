<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ConversationController extends Controller
{
    //     public function create(Request $request, $conversationId)
    // {
    //     $existingConversation = Conversation::where(function ($query) use ($conversationId) {
    //         $query->where('user1_id', Auth::id())
    //             ->where('user2_id', $conversationId);
    //     })->orWhere(function ($query) use ($conversationId) {
    //         $query->where('user1_id', $conversationId)
    //             ->where('user2_id', Auth::id());
    //     })->first();

    //     if ($existingConversation) {
    //         $conversations = Conversation::where('user1_id', Auth::id())
    //             ->orWhere('user2_id', $conversationId)
    //             ->first();
    //         return redirect('/chat')->with('conversations', $conversations);
    //     } else {
    //         $data = Conversation::create([
    //             'user1_id' => Auth::id(),
    //             'user2_id' => $conversationId,
    //         ]);

    //         $conversation = Conversation::where('user1_id', Auth::id())
    //             ->where('user2_id', $conversationId)
    //             ->first();
    //         return redirect('/chat/'. $conversationId)->with('conversations', $conversation);
    //     }
    // }


    public function create(Request $request, $conversationId)
    {
        $existingConversation = Conversation::where(function ($query) use ($conversationId) {
            $query->where('user1_id', Auth::id())
                ->where('user2_id', $conversationId);
        })->orWhere(function ($query) use ($conversationId) {
            $query->where('user1_id', $conversationId)
                ->where('user2_id', Auth::id());
        })->first();

        if ($existingConversation) {
            $messages = Message::where('conversation_id', $existingConversation->id)
            ->with('conversation', 'sender')
            ->get();
    
            return redirect('/chat/' . $conversationId)->with('messages', $messages);
        } else {
            $data = Conversation::create([
                'user1_id' => Auth::id(),
                'user2_id' => $conversationId,
            ]);

            $messages = Message::where('conversation_id', $existingConversation->id)
        ->with('conversation', 'sender')
        ->get();

            return redirect('/chat/' . $conversationId)->with('messages', $messages);
        }
    }
}
