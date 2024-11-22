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
use Illuminate\Support\Facades\Log;


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
    /* GET Method: Return subscription user list view */
    public function index()
    {
        $breadcrumbs = [
            ['title' => 'ダッシュボード', 'url' => '/home', 'active' => false],
            ['title' => '契約ユーザー', 'url' => '/subscription_user/list', 'active' => true],
        ];

        return view('subscription_user.list', compact('breadcrumbs'));
    }

    /* GET Method: Return add new subscription user view */
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

    /* (API) POST Method: create new subscription user */
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

    /* GET Method: Return edit subscription user view */
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

    /* (API) PATCH Method: Update subscription user */
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

    /* (API) GET Method: Return subscription users as datatables response */
    public function getSubscriptionUsers()
    {
        try {
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

            $sub_domain = env('APP_URL');

            return DataTables::of($managers)
                ->editColumn('sub_domain', function ($manager) use ($sub_domain) {
                    // Thực hiện thay đổi sub_domain
                    return 'https://' . $manager->sub_domain . '.' . $sub_domain;
                })
                ->filterColumn('name', function ($query, $keyword) {
                    $query->whereRaw('LOWER(prefectures.name) LIKE ?', ["%{$keyword}%"]);
                })
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


    /* (API) DELETE Method: Delete subscription user by id */
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

    /* (API) GET Method: Return address1 by code */
    public function autoFillAddress1($code)
    {
        $address = PostCodes::select('address')
            ->where('code', '=', $code)
            ->first();

        return $address?->address;
    }
}
