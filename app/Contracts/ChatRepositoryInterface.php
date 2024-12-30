<?php

namespace App\Contracts;

interface ChatRepositoryInterface
{
    public function getAllUsers();

    public function getMessagesByUserId($userId);

    public function fetchMessages($userId, $contactId);

    public function saveMessage(array $data);
}
