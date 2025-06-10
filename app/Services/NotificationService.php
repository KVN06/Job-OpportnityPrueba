<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\NotificationException;

class NotificationService
{
    /**
     * Get filtered notifications for a user
     */
    public function getFilteredNotifications(User $user, array $filters = [])
    {
        $query = $user->notifications()->with('user');

        if (!empty($filters['read'])) {
            $query->whereNotNull('read_at');
        }

        if (!empty($filters['unread'])) {
            $query->whereNull('read_at');
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Create a new notification
     */
    public function create(array $data): Notification
    {
        try {
            DB::beginTransaction();

            $notification = Notification::create($data);

            DB::commit();
            return $notification;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new NotificationException('Error creating notification: ' . $e->getMessage());
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification, User $user): bool
    {
        try {
            if ($notification->user_id !== $user->id) {
                throw new NotificationException('User not authorized to mark this notification as read');
            }

            return $notification->update(['read_at' => now()]);
        } catch (\Exception $e) {
            throw new NotificationException('Error marking notification as read: ' . $e->getMessage());
        }
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(User $user): bool
    {
        try {
            DB::beginTransaction();

            $user->notifications()
                 ->whereNull('read_at')
                 ->update(['read_at' => now()]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new NotificationException('Error marking all notifications as read: ' . $e->getMessage());
        }
    }

    /**
     * Delete a notification
     */
    public function delete(Notification $notification, User $user): bool
    {
        try {
            if ($notification->user_id !== $user->id) {
                throw new NotificationException('User not authorized to delete this notification');
            }

            return $notification->delete();
        } catch (\Exception $e) {
            throw new NotificationException('Error deleting notification: ' . $e->getMessage());
        }
    }

    /**
     * Delete all read notifications for a user
     */
    public function deleteAllRead(User $user): bool
    {
        try {
            DB::beginTransaction();

            $user->notifications()
                 ->whereNotNull('read_at')
                 ->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new NotificationException('Error deleting read notifications: ' . $e->getMessage());
        }
    }

    /**
     * Send notification to user
     */
    public function sendNotification(User $user, string $type, array $data): Notification
    {
        return $this->create([
            'user_id' => $user->id,
            'type' => $type,
            'data' => $data,
        ]);
    }

    /**
     * Send notification to multiple users
     */
    public function sendBulkNotifications(array $userIds, string $type, array $data): bool
    {
        try {
            DB::beginTransaction();

            foreach ($userIds as $userId) {
                $this->create([
                    'user_id' => $userId,
                    'type' => $type,
                    'data' => $data,
                ]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new NotificationException('Error sending bulk notifications: ' . $e->getMessage());
        }
    }
}
