<?php

namespace App\Http\Controllers;

use App\Models\User;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Endpoint GET / (Tantangan Tugas 3)
     * Mengembalikan status service
     */
    public function getServiceInfo()
    {
        return response()->json([
            "service_name" => "PHP Service App",
            "status" => "Running"
        ], 200);
    }

    /**
     * Endpoint GET /users (Tantangan Tugas 4 / Database)
     * Mengambil daftar user dari database beserta jumlah post
     */
    public function index()
    {
        $users = User::withCount('posts')->orderBy('id', 'ASC')->get();

        return response()->json([
            "message" => "List users dari database",
            "count" => $users->count(),
            "results" => $users
        ], 200);
    }

    /**
     * Endpoint GET /users/{userId}
     * Mengembalikan data user berdasarkan userId dari database
     */
    public function show($userId)
    {
        $user = User::withCount('posts')->find($userId);

        if ($user) {
            return response()->json($user, 200);
        }

        return response()->json(["message" => "User not found"], 404);
    }
}
