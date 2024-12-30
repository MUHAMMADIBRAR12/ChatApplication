<?php

namespace App\Repositories;

use App\Models\Message;
use App\Models\User;
use App\Http\Controllers\ChatController;
use App\Contracts\ChatRepositoryInterface;

class ChatRepository implements ChatRepositoryInterface
{

    public function getAllUsers()
    {
        return User::all();
    }

    public function getMessagesByUserId($userId)
    {
        return Message::where('user_id', $userId)->get();
    }

    public function fetchMessages($userId, $contactId)
    {
        return Message::where(function ($query) use ($userId, $contactId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $contactId);
        })->orWhere(function ($query) use ($userId, $contactId) {
            $query->where('sender_id', $contactId)
                  ->where('receiver_id', $userId);
        })->orderBy('created_at', 'asc')
        ->with(['sender:id,name', 'receiver:id,name'])
        ->get(['id', 'sender_id', 'receiver_id',  'message', 'created_at']);
    }

    public function saveMessage($data)
    {
        return Message::create($data);
    }
}
