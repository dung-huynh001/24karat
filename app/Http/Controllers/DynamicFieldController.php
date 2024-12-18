<?php

namespace App\Http\Controllers;

use App\Models\DynamicFields;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Common\Constants;
use App\Models\Prefectures;

class DynamicFieldController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* GET Method: Return dynamic field list view */
    public function index()
    {
        $breadcrumbs = [
            ['title' => 'ダッシュボード', 'url' => '/home', 'active' => false],
            ['title' => 'フィールド一覧', 'url' => '/dynamic_field/list', 'active' => true],
        ];

        return view('dynamic_field.list', compact('breadcrumbs'));
    }

    /* GET Method: Return add new field view */
    public function add()
    {
        $breadcrumbs = [
            ['title' => 'ダッシュボード', 'url' => '/home', 'active' => false],
            ['title' => 'フィールド一覧', 'url' => '/dynamic_field/list', 'active' => false],
            ['title' => 'フィールド登録', 'url' => '/dynamic_field/add', 'active' => true],
        ];

        $barcodeOptions = Constants::BARCODE_OPTIONS;

        $prefectures = Prefectures::select([
            'id',
            'code',
            'name',
        ])->get();

        return view('dynamic_field.add', [
            'breadcrumbs' => $breadcrumbs,
            'barcodeOptions' => $barcodeOptions,
            'prefectures' => $prefectures
        ]);
    }

    /* (API) GET Method: Return subscription users as datatables response */
    public function getFields()
    {
        try {
            $managers = DynamicFields::select([
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

}
