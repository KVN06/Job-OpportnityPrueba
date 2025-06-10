<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\CommentException;

class CommentService
{
    /**
     * Get filtered comments
     */
    public function getFilteredComments(array $filters = [])
    {
        $query = Comment::query()->with(['user', 'commentable']);

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['type'])) {
            $query->where('commentable_type', $filters['type']);
        }

        if (!empty($filters['commentable_id'])) {
            $query->where('commentable_id', $filters['commentable_id']);
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
     * Create a new comment
     */
    public function create(array $data, User $user): Comment
    {
        try {
            DB::beginTransaction();

            $comment = new Comment([
                'content' => $data['content'],
                'commentable_type' => $data['commentable_type'],
                'commentable_id' => $data['commentable_id'],
            ]);

            $comment->user()->associate($user);
            $comment->save();

            // Notify the owner of the commentable item if different from commenter
            $commentable = $comment->commentable;
            if (method_exists($commentable, 'user') && 
                $commentable->user && 
                $commentable->user->id !== $user->id) {
                // You can implement notification logic here
            }

            DB::commit();
            return $comment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new CommentException('Error creating comment: ' . $e->getMessage());
        }
    }

    /**
     * Update a comment
     */
    public function update(Comment $comment, array $data): Comment
    {
        try {
            DB::beginTransaction();

            $comment->update([
                'content' => $data['content'],
            ]);

            DB::commit();
            return $comment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new CommentException('Error updating comment: ' . $e->getMessage());
        }
    }

    /**
     * Delete a comment
     */
    public function delete(Comment $comment): bool
    {
        try {
            DB::beginTransaction();

            $result = $comment->delete();

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new CommentException('Error deleting comment: ' . $e->getMessage());
        }
    }

    /**
     * Get comments for a specific item
     */
    public function getCommentsFor(string $type, int $id, array $filters = [])
    {
        return Comment::where('commentable_type', $type)
                     ->where('commentable_id', $id)
                     ->with('user')
                     ->latest()
                     ->paginate($filters['per_page'] ?? 15);
    }
}
