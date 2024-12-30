<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Events\SentNotification; 
use App\Jobs\StoreMessageJob;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth; 
use App\Contracts\ChatRepositoryInterface;
use App\Notifications\MessageNotification;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MessagesExport;
class ChatController extends Controller
{
    protected $chatRepository;

    public function __construct(ChatRepositoryInterface $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }

    public function index()
    {
        $users = User::all();
        return view('chat.index', compact('users'));
    }

    public function fetchMessages($contactId)
    {
        $userId = auth()->id();  
        $messages = $this->chatRepository->fetchMessages($userId, $contactId);
        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $data = [
            'sender_id' => auth()->user()->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ];

        StoreMessageJob::dispatch($data); 
        broadcast(new MessageSent($data))->toOthers();
        $receiver = User::find($request->receiver_id);
        $receiver->notify(new MessageNotification($request->message));
        broadcast(new SentNotification($data))->toOthers();
        return response()->json(['success' => true]);
    }

    public function markAsRead(){
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }

    public function exportMessages($contactId)
    {
        return Excel::download(new MessagesExport(auth()->id(), $contactId), 'chat_messages.xlsx');
    }
}


