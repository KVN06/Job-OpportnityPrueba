<?php

namespace App\Services;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MessageService
{
    public function getUserConversations(User $user, int $perPage = 10): LengthAwarePaginator
    {
        // Obtener IDs únicos de usuarios con los que se ha tenido conversación
        $contactIds = collect();
        
        // Usuarios a los que el usuario actual ha enviado mensajes
        $sentTo = Message::where('sender_id', $user->id)
            ->distinct()
            ->pluck('receiver_id');
        
        // Usuarios que han enviado mensajes al usuario actual
        $receivedFrom = Message::where('receiver_id', $user->id)
            ->distinct()
            ->pluck('sender_id');
        
        $contactIds = $sentTo->merge($receivedFrom)->unique();
        
        // Obtener el último mensaje con cada contacto
        $conversations = collect();
        
        foreach ($contactIds as $contactId) {
            $lastMessage = Message::where(function($query) use ($user, $contactId) {
                $query->where([
                    ['sender_id', $user->id],
                    ['receiver_id', $contactId]
                ])->orWhere([
                    ['sender_id', $contactId],
                    ['receiver_id', $user->id]
                ]);
            })
            ->with(['sender', 'receiver'])
            ->orderByDesc('created_at')
            ->first();
            
            if ($lastMessage) {
                $conversations->push($lastMessage);
            }
        }
        
        // Ordenar por fecha de creación descendente
        $conversations = $conversations->sortByDesc('created_at');
        
        // Crear paginación manual
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $itemsForCurrentPage = $conversations->slice($offset, $perPage)->values();
        
        return new LengthAwarePaginator(
            $itemsForCurrentPage,
            $conversations->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );
    }

    public function getConversation(User $user1, User $user2, int $perPage = 20): LengthAwarePaginator
    {
        return Message::where(function ($query) use ($user1, $user2) {
            $query->where([
                ['sender_id', $user1->id],
                ['receiver_id', $user2->id]
            ])->orWhere([
                ['sender_id', $user2->id],
                ['receiver_id', $user1->id]
            ]);
        })
        ->with(['sender', 'receiver'])
        ->orderByDesc('created_at')
        ->paginate($perPage);
    }

    public function sendMessage(User $sender, User $receiver, array $data): Message
    {
        DB::beginTransaction();
        try {
            $message = new Message([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'subject' => $data['subject'] ?? null,
                'content' => $data['content'],
                'parent_id' => $data['parent_id'] ?? null
            ]);

            $message->save();

            // Mark previous message as read if this is a reply
            if ($message->parent_id) {
                Message::where('id', $message->parent_id)
                    ->where('receiver_id', $sender->id)
                    ->update(['read_at' => now()]);
            }

            // Create notification for receiver (commented out for now)
            // $receiver->notifications()->create([
            //     'type' => 'new_message',
            //     'data' => [
            //         'message_id' => $message->id,
            //         'sender_name' => $sender->name,
            //         'subject' => $message->subject,
            //         'preview' => str_limit($message->content, 100)
            //     ]
            // ]);

            DB::commit();
            return $message;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function markAsRead(Message $message, User $user): bool
    {
        if ($message->receiver_id !== $user->id) {
            throw new \Exception('Unauthorized action.');
        }

        if (!$message->read_at) {
            $message->read_at = now();
            return $message->save();
        }

        return false;
    }

    public function markAllAsRead(User $user): int
    {
        return Message::where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function deleteMessage(Message $message, User $user): bool
    {
        if ($message->sender_id === $user->id) {
            $message->deleted_by_sender = true;
        } elseif ($message->receiver_id === $user->id) {
            $message->deleted_by_receiver = true;
        } else {
            throw new \Exception('Unauthorized action.');
        }

        $message->save();

        // If both users have deleted the message, remove it from database
        if ($message->deleted_by_sender && $message->deleted_by_receiver) {
            return $message->delete();
        }

        return true;
    }

    public function getUnreadCount(User $user): int
    {
        return Message::where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->count();
    }

    public function searchMessages(User $user, string $query): EloquentCollection
    {
        return Message::where(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
              ->orWhere('receiver_id', $user->id);
        })
        ->where(function ($q) use ($query) {
            $q->where('subject', 'like', "%{$query}%")
              ->orWhere('content', 'like', "%{$query}%");
        })
        ->with(['sender', 'receiver'])
        ->orderByDesc('created_at')
        ->get();
    }

    public function getRecentContacts(User $user, int $limit = 5): Collection
    {
        // Obtener IDs únicos de usuarios con los que se ha tenido conversación
        $sentTo = Message::where('sender_id', $user->id)
            ->distinct()
            ->pluck('receiver_id');
        
        $receivedFrom = Message::where('receiver_id', $user->id)
            ->distinct()
            ->pluck('sender_id');
        
        $contactIds = $sentTo->merge($receivedFrom)->unique();
        
        // Obtener usuarios con la fecha del último mensaje
        $contacts = collect();
        
        foreach ($contactIds as $contactId) {
            $lastMessageDate = Message::where(function($query) use ($user, $contactId) {
                $query->where([
                    ['sender_id', $user->id],
                    ['receiver_id', $contactId]
                ])->orWhere([
                    ['sender_id', $contactId],
                    ['receiver_id', $user->id]
                ]);
            })
            ->max('created_at');
            
            $contact = User::find($contactId);
            if ($contact) {
                $contact->last_message_date = $lastMessageDate;
                $contacts->push($contact);
            }
        }
        
        return $contacts->sortByDesc('last_message_date')->take($limit)->values();
    }
}