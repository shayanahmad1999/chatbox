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
    // public function chat($conversationId) {
    //     $existingConversation = Conversation::where(function ($query) use ($conversationId) {
    //         $query->where('user1_id', Auth::id())
    //             ->where('user2_id', $conversationId);
    //     })->orWhere(function ($query) use ($conversationId) {
    //         $query->where('user1_id', $conversationId)
    //             ->where('user2_id', Auth::id());
    //     })->first();
    
    //     $conversations = Conversation::where(function ($query) use ($conversationId) {
    //         $query->where('user1_id', Auth::id())
    //             ->where('user2_id', $conversationId);
    //     })->orWhere(function ($query) use ($conversationId) {
    //         $query->where('user1_id', $conversationId)
    //             ->where('user2_id', Auth::id());
    //     })->first();

    //     $user = User::find($conversationId);

    //     $messages = Message::where('conversation_id', $existingConversation->id)
    //     ->with('conversation', 'sender')
    //     ->get();
        
    //     // $authImage = User::find(Auth::id())->first();
    //     // $receiverImage = User::find($conversationId)->first();
    //     // dd($user->conversationId);

    //     // return response()->json($receiverImage);

    //     return Inertia::render('Chat', [
    //         'conversations' => $conversations,
    //         'user' => $user,
    //         'messages' => $messages,
    //         // 'authImage' => asset($authImage->profileImage),
    //         // 'receiverImage' => asset($receiverImage->profileImage),
    //     ]);
    // }
    public function chat($conversationId) {
        $existingConversation = Conversation::where(function ($query) use ($conversationId) {
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
    
        $userProp = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'profileImage' => asset($user->profileImage),
        ];

        $messageProps = [];

foreach ($messages as $message) {
    $messageProps[] = [
        'id' => $message->id,
        'content' => $message->content,
        'created_at' => $message->created_at,
        'name' => $message->sender->name,
        'uploadImage' =>asset( $message->uploadImage),
        'userImage' => asset($message->sender->profileImage),
    ];
}
    // dd($messageProps);
        return Inertia::render('Chat', [
            'conversations' => $existingConversation,
            'user' => $userProp,
            'messages' => $messageProps,
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

    public function insertImage(Request $request)
    {
        $request->validate([
            'image' => 'required',
        ]);
        $id = $request->conversationId;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            // $image->move(public_path('images/users'), $imageName);
            $imageSave = $image->move('uploads/message', $imageName);
    
            Message::create([
                'conversation_id' => $id,
                'sender_id' => Auth::id(),
                'uploadImage' => $imageSave,
            ]);
        }

        return redirect()->back();
    }

    public function destroy(Message $message){
        if(!is_null($message)){
            $message->delete();
            return redirect()->back();
        }
    }
    
}
