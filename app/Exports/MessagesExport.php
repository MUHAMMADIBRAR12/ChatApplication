<?php

namespace App\Exports;

use App\Models\Message;
use App\Repositories\ChatRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MessagesExport implements FromCollection , WithHeadings
{
    protected $userId;
    protected $contactId;

    /**  
    * @return \Illuminate\Support\Collection
    */
      public function __construct($userId, $contactId)
    {
        $this->userId = $userId;
        $this->contactId = $contactId;
    }

    public function collection()
    {
        $chatRepo = new ChatRepository();
        return $chatRepo->fetchMessages($this->userId, $this->contactId);
    }

    public function headings(): array
    {
        return ['ID','Name', 'Message', 'Created At'];
    }
}
