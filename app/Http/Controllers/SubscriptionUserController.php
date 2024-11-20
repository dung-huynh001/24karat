<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionUser;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\Common\FormValidationException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Common\Constants;
use App\Http\Requests\UpdateSubscriptionUserForm;
use App\Models\PostCodes;
use App\Models\Prefectures;

class SubscriptionUserController extends Controller
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
            ['title' => '契約ユーザー', 'url' => '/subscription_user/list', 'active' => true],
        ];

        return view('subscription_user.list', compact('breadcrumbs'));
    }

    //Register Manager GET
    public function add()
    {
        $breadcrumbs = [
            ['title' => 'ダッシュボード', 'url' => '/home', 'active' => false],
            ['title' => '契約ユーザー', 'url' => '/subscription_user/list', 'active' => false],
            ['title' => '契約ユーザー登録', 'url' => '/subscription_user/add', 'active' => true],
        ];

        $barcodeOptions = Constants::BARCODE_OPTIONS;

        $prefectures = Prefectures::select([
            'id',
            'code',
            'name',
        ])->get();

        return view('subscription_user.add', [
            'breadcrumbs' => $breadcrumbs,
            'barcodeOptions' => $barcodeOptions,
            'prefectures' => $prefectures
        ]);
    }

    public function create(Request $request)
    {
        $formData = $request
            ->only('company_name', 'sub_domain', 'barcode_type', 'pref_id', 'zip', 'address1', 'address2', 'tel', 'manager_mail');
        try {
            $validator = app(UpdateSubscriptionUserForm::class);
            $validator->validate($formData);

            SubscriptionUser::create([
                'company_name' => $formData['company_name'],
                'sub_domain' => $formData['sub_domain'],
                'barcode_type' => $formData['barcode_type'],
                'pref_id' => $formData['pref_id'],
                'zip' => $formData['zip'],
                'address1' => $formData['address1'],
                'address2' => $formData['address2'],
                'tel' => $formData['tel'],
                'manager_mail' => $formData['manager_mail'],
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
            ['title' => '契約ユーザー', 'url' => '/subscription_user/list', 'active' => false],
            ['title' => '契約ユーザー編集', 'url' => "/subscription_user/edit/$id", 'active' => true],
        ];

        $subscriptionUser = SubscriptionUser::select([
            'subscription_user_id',
            'company_name',
            'sub_domain',
            'barcode_type',
            'zip',
            'pref_id',
            'address1',
            'address2',
            'tel',
            'manager_mail'
        ])
            ->where('subscription_user_id', $id)
            ->first();

        $barcodeOptions = Constants::BARCODE_OPTIONS;

        $prefectures = Prefectures::select([
            'id',
            'code',
            'name',
        ])->get();

        return view('subscription_user.edit', [
            'breadcrumbs' => $breadcrumbs,
            'subscriptionUser' => $subscriptionUser,
            'barcodeOptions' => $barcodeOptions,
            'prefectures' => $prefectures
        ]);
    }

    public function update(Request $request, $id)
    {
        $formData = $request
            ->only('company_name', 'sub_domain', 'barcode_type', 'pref_id', 'zip', 'address1', 'address2', 'tel', 'manager_mail');
        try {
            $subscriptionUser = SubscriptionUser::findOrFail($id);
            $validator = app(UpdateSubscriptionUserForm::class);
            $validator->validate($formData);

            $subscriptionUser->update([
                'company_name' => $formData['company_name'],
                'sub_domain' => $formData['sub_domain'],
                'barcode_type' => $formData['barcode_type'],
                'pref_id' => $formData['pref_id'],
                'zip' => $formData['zip'],
                'address1' => $formData['address1'],
                'address2' => $formData['address2'],
                'tel' => $formData['tel'],
                'manager_mail' => $formData['manager_mail'],
            ]);

            return response()->json(Response::HTTP_OK);
        } catch (FormValidationException $e) {
            return response()->json($e->getErrors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }


    // GET Manager LIST
    public function getSubscriptionUsers()
    {
        $managers = SubscriptionUser::select([
            'subscription_users.subscription_user_id',
            'subscription_users.sub_domain',
            'subscription_users.barcode_type',
            'subscription_users.company_name',
            'subscription_users.zip',
            'subscription_users.address1',
            'subscription_users.address2',
            'prefectures.name',
            'subscription_users.tel',
            'subscription_users.manager_mail',
            'subscription_users.created_at',
            'subscription_users.updated_at',
        ])
            ->leftJoin('prefectures', 'subscription_users.pref_id', '=', 'prefectures.id')
            ->where('subscription_users.delete_flag', 0);

        return DataTables::of($managers)
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereRaw('LOWER(prefectures.name) LIKE ?', ["%{$keyword}%"]);
            })
            ->make(true);
    }


    //DELETE Manager
    public function delete($id)
    {
        $manager = SubscriptionUser::find($id);
        if ($manager) {
            $manager->delete_flag = 1;
            $manager->save();
            return response()->json('正常に削除されました', 200);
        } else {
            return response()->json('ユーザーが見つかりません', 404);
        }
    }


    public function autoFillAddress1($code)
    {
        $address = PostCodes::select('address')
            ->where('code', '=', $code)
            ->first();

        return $address?->address;
    }
}
