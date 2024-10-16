<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    public function index(){
        $stats = Cache::remember('stats','',function(){
            $allUsers = User::count();
            $allPosts = Post::count();
            $usersWithoutPosts = User::doesntHave('posts')->count();

            return [
                'Total_users' => $allUsers,
                'Total_posts' => $allPosts,
                'users_without_posts' => $usersWithoutPosts,
            ];
        });
        return response()->json(['Stats' => $stats]);
    }
}
