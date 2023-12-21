<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserManager extends Controller
{
    // Menampilkan halaman kelola user
    public function index() {
        return view('admin.user_manager.list');
    }
}
