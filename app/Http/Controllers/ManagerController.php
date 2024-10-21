<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManagerController extends Controller
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
            ['title' => 'ダッシュボード', 'url' => '/dashboard', 'active' => false],
            ['title' => '管理者', 'url' => '/manager/list', 'active' => true],
        ];

        return view('manager.list', compact('breadcrumbs'));
    }
}
