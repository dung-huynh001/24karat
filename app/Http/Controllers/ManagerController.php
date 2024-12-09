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
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;


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
    /* GET Method: Return manager list view */
    public function index()
    {
        $breadcrumbs = [
            ['title' => 'ダッシュボード', 'url' => '/home', 'active' => false],
            ['title' => '管理者', 'url' => '/manager/list', 'active' => true],
        ];

        return view('manager.list', compact('breadcrumbs'));
    }

    /* GET Method: Return register new manager view */
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

    /* POST Method: Register new manager */
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

    /* (API) POST Method: Register new manager */
    public function registerAPI(Request $request)
    {
        $formData = $request->only('subscription_user', 'name', 'email', 'password', 'confirm_password', 'avatar_data');

        try {
            $validator = app(RegisterManagerForm::class);
            $validator->validate($formData);

            $avatarPath = 'uploads/avatar/defaut-user.png';
            if($formData['avatar_data'] != null) {
                $avatarPath = $this->saveImageToFolder($formData['name'], $formData['avatar_data']);
            }

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
                'avatar' => $avatarPath,
            ]);
            return response()->json(Response::HTTP_OK);
        } catch (FormValidationException $e) {
            return response()->json($e->getErrors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    /* Save $base64Iamge to folder */
    public function saveImageToFolder(string $username, string $base64Image) {
        preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type);
        $type = strtolower($type[1]); // Get extension file

        $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
        $base64Image = str_replace(' ', '+', $base64Image);

        $fileName = $username.uniqid() . '.' . $type; // Create unique file name
        $filePath = "uploads/avatars/$fileName";
        Storage::disk('public')->put($filePath, base64_decode($base64Image));
        return $filePath;
    }

    /* GET Method: Return edit manager view */
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

    /* (API) PATCH Method: Update manager */
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

    /* (API) GET Method: Return managers as datatables response */
    public function getManagers()
    {
        try {
            $managers = AdminUser::query()
                ->select([
                    'admin_users.admin_user_id',
                    'admin_users.name',
                    'admin_users.email',
                    'admin_users.created_at',
                    'admin_users.updated_at',
                    'subscription_users.company_name',
                ])
                ->leftJoin('subscription_users', 'admin_users.subscription_user_id', '=', 'subscription_users.subscription_user_id')
                ->where('admin_users.delete_flag', 0);

            return DataTables::of($managers)
                ->filterColumn(
                    'company_name',
                    fn($query, $keyword) =>
                    $query->whereRaw('LOWER(subscription_users.company_name) LIKE ?', ["%{$keyword}%"])
                )
                ->make(true);
        } catch (\Exception $e) {
            // Write log
            Log::error('Error fetching managers: ' . $e->getMessage());

            // Return empty data when exception occurs
            return response()->json([
                'data' => [],
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'errors' => $e
            ]);
        }
    }



    /* (API) DELETE Method: Delete manager by id */
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
