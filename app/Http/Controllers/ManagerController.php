<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Models\SubscriptionUser;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Common\FormValidationException;
use App\Http\Requests\RegisterManagerForm;
use App\Http\Requests\UpdateManagerForm;
use Illuminate\Http\Response;

class ManagerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $registerManagerForm;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    //Managers LIST
    public function index()
    {
        $breadcrumbs = [
            ['title' => 'ダッシュボード', 'url' => '/home', 'active' => false],
            ['title' => '管理者', 'url' => '/manager/list', 'active' => true],
        ];

        return view('manager.list', compact('breadcrumbs'));
    }

    //Register Manager GET
    public function register()
    {
        $breadcrumbs = [
            ['title' => 'ダッシュボード', 'url' => '/home', 'active' => false],
            ['title' => '管理者', 'url' => '/manager/list', 'active' => false],
            ['title' => '管理者登録', 'url' => '/manager/register', 'active' => true],
        ];
        $subscriptionUsers = SubscriptionUser::select([
            'subscription_users.subscription_user_id',
            'subscription_users.company_name'
        ])->get();
        return view('manager.register', compact('breadcrumbs', 'subscriptionUsers'));
    }

    //Register Manager POST
    public function store()
    {
        $formData = request()->only('subscription_user', 'name', 'email', 'password', 'confirm_password');
        try {
            $validator = app(RegisterManagerForm::class);
            $validator->validate($formData);

            AdminUser::create([
                'subscription_user_id' => intval($formData['subscription_user']),
                'name' => $formData['name'],
                'email' => $formData['email'],
                'password' => bcrypt($formData['password']),
            ]);
            return Redirect::route('manager.list')->with('success', '管理者登録が成功しました');
        } catch (FormValidationException $e) {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }
    }

    public function registerAPI(Request $request)
    {
        $formData = $request->only('subscription_user', 'name', 'email', 'password', 'confirm_password');

        try {
            $validator = app(RegisterManagerForm::class);
            $validator->validate($formData);

            if (AdminUser::where('email', $formData['email'])->exists()) {
                return response()->json(
                    '電子メールが使用されました',
                    Response::HTTP_BAD_REQUEST
                );
            }

            AdminUser::create([
                'subscription_user_id' => intval($formData['subscription_user']),
                'name' => $formData['name'],
                'email' => $formData['email'],
                'password' => bcrypt($formData['password']),
            ]);
            return response()->json(Response::HTTP_OK);
        } catch (FormValidationException $e) {
            return response()->json($e->getErrors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    //Edit Manager GET
    public function edit($id)
    {
        $breadcrumbs = [
            ['title' => 'ダッシュボード', 'url' => '/home', 'active' => false],
            ['title' => '管理者', 'url' => '/manager/list', 'active' => false],
            ['title' => '管理者編集', 'url' => "/manager/edit/$id", 'active' => true],
        ];
        $manager = AdminUser::select([
            'admin_users.admin_user_id',
            'admin_users.name',
            'admin_users.email',
            'admin_users.created_at',
            'admin_users.updated_at',
            'subscription_users.subscription_user_id',
            'subscription_users.company_name'
        ])
            ->where('admin_users.admin_user_id', $id)
            ->leftJoin('subscription_users', 'admin_users.subscription_user_id', '=', 'subscription_users.subscription_user_id')
            ->first();
        $subscriptionUsers = SubscriptionUser::select([
            'subscription_users.subscription_user_id',
            'subscription_users.company_name'
        ])->get();
        return view('manager.edit', [
            'breadcrumbs' => $breadcrumbs,
            'manager' => $manager,
            'subscriptionUsers' => $subscriptionUsers,
        ]);
    }

    public function update(Request $request, $id)
    {
        $formData = $request->only('subscription_user', 'name', 'password', 'confirm_password', 'chk_change_password');
        try {
            $adminUser = AdminUser::findOrFail($id);
            if (!empty($formData['chk_change_password']) && $formData['chk_change_password'] === 'true') {
                $validator = app(UpdateManagerForm::class);
                $validator->validate($formData);

                $adminUser->update([
                    'subscription_user_id' => intval($formData['subscription_user']),
                    'name' => $formData['name'],
                    'password' => bcrypt($formData['password']),
                ]);

                return response()->json(Response::HTTP_OK);
            }
            if ($formData['name'] == null) {
                return response()->json(['name' => ['必須フィールドに入力してください']], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $adminUser->update([
                'subscription_user_id' => $formData['subscription_user'] == null ? null : intval($formData['subscription_user']),
                'name' => $formData['name'],
            ]);

            return response()->json(Response::HTTP_OK);
        } catch (FormValidationException $e) {
            return response()->json($e->getErrors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }


    // GET Manager LIST
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
            ->leftJoin('subscription_users', 'admin_users.subscription_user_id', '=', 'subscription_users.subscription_user_id')
            ->where('admin_users.delete_flag', 0);

        return DataTables::of($managers)
            ->filterColumn('company_name', function ($query, $keyword) {
                $query->whereRaw('LOWER(subscription_users.company_name) LIKE ?', ["%{$keyword}%"]);
            })
            ->make(true);
    }


    //DELETE Manager
    public function delete($id)
    {
        $manager = AdminUser::find($id);
        if ($manager) {
            $manager->delete_flag = 1;
            $manager->save();
            return response()->json('正常に削除されました', 200);
        } else {
            return response()->json('ユーザーが見つかりません', 404);
        }
    }
}
