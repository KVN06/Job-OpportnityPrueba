<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Services\CommentService;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->middleware('auth');
        $this->commentService = $commentService;
    }

    /**
     * Store a new comment.
     */
    public function store(CommentRequest $request)
    {
        try {
            $comment = $this->commentService->create($request->validated(), Auth::user());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Comentario creado correctamente',
                    'comment' => $comment->load('user')
                ]);
            }

            return back()->with('success', 'Comentario creado correctamente.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
            return back()->with('error', 'Error al crear el comentario: ' . $e->getMessage());
        }
    }

    /**
     * Update a comment.
     */
    public function update(CommentRequest $request, Comment $comment)
    {
        try {
            $this->authorize('update', $comment);
            $comment = $this->commentService->update($comment, $request->validated());

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Comentario actualizado correctamente',
                    'comment' => $comment->load('user')
                ]);
            }

            return back()->with('success', 'Comentario actualizado correctamente.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
            return back()->with('error', 'Error al actualizar el comentario: ' . $e->getMessage());
        }
    }

    /**
     * Delete a comment.
     */
    public function destroy(Request $request, Comment $comment)
    {
        try {
            $this->authorize('delete', $comment);
            $this->commentService->delete($comment);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Comentario eliminado correctamente']);
            }

            return back()->with('success', 'Comentario eliminado correctamente.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
            return back()->with('error', 'Error al eliminar el comentario: ' . $e->getMessage());
        }
    }

    /**
     * Get comments for a specific item.
     */
    public function index(Request $request)
    {
        try {
            $comments = $this->commentService->getCommentsFor(
                $request->input('commentable_type'),
                $request->input('commentable_id'),
                $request->only(['per_page'])
            );

            if ($request->wantsJson()) {
                return response()->json($comments);
            }

            return view('comments.index', compact('comments'));
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
            return back()->with('error', 'Error al cargar los comentarios: ' . $e->getMessage());
        }
    }
}
