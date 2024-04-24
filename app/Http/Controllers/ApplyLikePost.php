<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\UserLikes;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplyLikePost extends Controller
{
    public function giveLikePost(Request $request): JsonResponse
    {
        try {
            $postId = $request->input('postId');
            $userId = Auth::id();

            $userLikePost = UserLikes::findUserPostLike($userId, $postId);
            if ($userLikePost) {
                UserLikes::deleteUserPostLike($userId, $postId);
                $likesPost = Post::decrementLikes($postId);
            } else {
                UserLikes::createUserPostLike($userId, $postId);
                $likesPost = Post::incrementLikes($postId);
            }
            return response()->json(['likes' => $likesPost]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
