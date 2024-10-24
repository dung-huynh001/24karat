<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

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
            ['title' => 'ダッシュボード', 'url' => '/home', 'active' => false],
            ['title' => '管理者', 'url' => '/manager/list', 'active' => true],
        ];

        return view('manager.list', compact('breadcrumbs'));
    }

    public function register()
    {
        $breadcrumbs = [
            ['title' => 'ダッシュボード', 'url' => '/home', 'active' => false],
            ['title' => '管理者', 'url' => '/manager/list', 'active' => false],
            ['title' => '管理者登録', 'url' => '/manager/register', 'active' => true],
        ];
        return view('manager.register', compact('breadcrumbs'));
    }

    public function getManagers()
    {
        $managers = AdminUser::select([
            'admin_users.admin_user_id',
            'admin_users.name',
            'admin_users.email',
            'admin_users.created_at',
            'admin_users.updated_at',
            'subscription_users.company_name'
        ])
            ->leftJoin('subscription_users', 'admin_users.subscription_user_id', '=', 'subscription_users.subscription_user_id');

        return DataTables::of($managers)->make(true);
    }
}
