<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Models\MemberFields;
use App\Models\MemberFieldFormatMasters;
use App\Models\MemberFieldFormatTrns;

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

        $fieldFormatMasters = MemberFieldFormatMasters::select([
            'member_field_format_master_id',
            'member_field_format_master_name',
            'mode_display_option',
        ])
            ->get();

        return view('dynamic_field.add', [
            'breadcrumbs' => $breadcrumbs,
            'fieldFormatMasters' => $fieldFormatMasters,
        ]);
    }

    /* (API) GET Method: Return member fields as datatables response */
    public function getMemberFields()
    {
        try {
            $managers = MemberFields::select([
                'id',
                'field_name',
            ])->get();

            return DataTables::of($managers)->make(true);
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

    public function getDynamicFieldPartial($member_field_format_master_id)
    {
        $member_field_format_trns = null;
        $partialView = '';
        switch ($member_field_format_master_id) {
            case 1:
                $partialView = 'partials.dynamic_fields.textbox';
                $member_field_format_trns = MemberFieldFormatTrns::select([
                    'member_field_format_trn_id',
                    'member_field_format_master_id',
                    'member_field_format_trn_name',
                    'member_field_format_trn_value',
                ])
                    ->where('member_field_format_master_id', '=', $member_field_format_master_id)
                    ->get();
                break;
            case 2:
                $partialView = 'partials.dynamic_fields.calendar';
                break;
            case 3:
                $partialView = 'partials.dynamic_fields.radio';
                break;
            case 4:
                $partialView = 'partials.dynamic_fields.select';
                break;
            case 5:
                $partialView = 'partials.dynamic_fields.province';
                break;
            case 6:
                $partialView = 'partials.dynamic_fields.postcode';
                break;
            case 7:
                $partialView = 'partials.dynamic_fields.address';
                break;
            default:
                break;
        }

        return view($partialView, [
            'member_field_format_trns' => $member_field_format_trns
        ])->render();
    }

    /* GET Method: Return edit member field view */
    public function edit($id)
    {
        $breadcrumbs = [
            ['title' => 'ダッシュボード', 'url' => '/home', 'active' => false],
            ['title' => '契約ユーザー', 'url' => '/dynamic_field/list', 'active' => false],
            ['title' => '契約ユーザー編集', 'url' => "/dynamic_field/edit/$id", 'active' => true],
        ];

        $memberFields = MemberFields::select([
            'member_fields.id',
            'member_fields.member_field_format_trn_id',
            'member_field_format_trns.member_field_format_master_id',
            'member_fields.field_name',
            'member_fields.field_value',
            'member_fields.field_validation',
            'member_fields.field_config',
            'member_fields.used_by',
            'member_fields.csv_input_rule'
        ])
            ->leftJoin(
                'member_field_format_trns',
                'member_fields.member_field_format_trn_id',
                '=',
                'member_field_format_trns.member_field_format_trn_id'
            )
            ->where('id', $id)
            ->first();

        $fieldFormatMasters = MemberFieldFormatMasters::select([
            'member_field_format_master_id',
            'member_field_format_master_name',
            'mode_display_option',
        ])
            ->get();

        $fieldFormatTrns = MemberFieldFormatTrns::select([
            'member_field_format_trn_id',
            'member_field_format_master_id',
            'member_field_format_trn_name',
            'member_field_format_trn_value',
        ])
            ->where(
                'member_field_format_master_id',
                '=',
                $memberFields->member_field_format_master_id
            )
            ->get();

        return view('dynamic_field.edit', [
            'breadcrumbs' => $breadcrumbs,
            'memberFields' => $memberFields,
            'fieldFormatMasters' => $fieldFormatMasters,
            'fieldFormatTrns' => $fieldFormatTrns,
        ]);
    }
}
