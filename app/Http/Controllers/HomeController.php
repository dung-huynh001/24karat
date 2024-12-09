<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $breadcrumbs = [
            ['title' => 'ダッシュボード', 'url' => '/dashboard', 'active' => true],
        ];
        $user = Auth::user();
        if ($user instanceof AdminUser) {
            $avatarUrl = $user->getAvatarUrl();
        } else {
            $avatarUrl = asset('/assets/images/default-avatar.png');
        }
        return view('home', compact('breadcrumbs', 'avatarUrl'));
    }
}
