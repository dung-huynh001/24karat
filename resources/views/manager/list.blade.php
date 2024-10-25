@extends('layouts.app')
@extends('layouts.breadcrumb')
@section('content')
<!-- DataTables CSS -->
<link rel="stylesheet" href="{{ asset('datatables/css/datatables.min.css') }}">

<div class="mb-4">
    <a role="button" href="{{route("manager.register")}}" class="btn btn-royal-blue">管理者登録</a>
</div>

<table id="managerTbl" class="display">
    <thead>
        <tr></tr>
    </thead>
</table>

<!-- DataTables JS -->
<script src="{{ asset('datatables/js/datatables.min.js') }}"></script>

<script>
    $(document).ready(function () {
        $('#managerTbl').DataTable({
            processing: true,
            serverSide: true,
            orderClasses: ['sorting_disabled', 'sorting', 'sorting_asc_custom', 'sorting_desc_custom'],
            language: {
                sLengthMenu: "_MENU_",
                sInfo: "_END_ 件 _PAGE_ ページ目",
                sProcessing: "処理中...",
                sZeroRecords: "データはありません。",
                sInfoEmpty: " 0 件中 0 から 0 まで表示",
                sInfoFiltered: "（全 _MAX_ 件より抽出）",
                sInfoPostFix: "",
                sSearch: "検索:",
                sUrl: "",
                oPaginate: {
                    "sFirst": "先頭",
                    "sPrevious": "前",
                    "sNext": "次",
                    "sLast": "最終"
                }
            },
            ajax: "{{ url('manager/get-managers') }}",
            columns: [
                { title: 'No.', data: 'admin_user_id', name: 'admin_user_id' },
                { title: '名前', data: 'name', name: 'name' },
                { title: 'メールアドレス', data: 'email', name: 'email' },
                { title: '契約ユーザー', data: 'company_name', name: 'company_name' },
                {
                    title: '作成日',
                    data: 'created_at',
                    name: 'created_at',
                    render: function (data, type, row) {
                        var date = new Date(data);
                        return date.toLocaleString('ja-JP', { timeZone: 'Asia/Tokyo' });
                    }
                },
                {
                    title: '更新日時',
                    data: 'updated_at',
                    name: 'updated_at',
                    render: function (data, type, row) {
                        var date = new Date(data);
                        return date.toLocaleString('ja-JP', { timeZone: 'Asia/Tokyo' });
                    }
                },
                {
                    // title: '操作',
                    // visible: false,
                    orderable: false,
                    data: 'admin_user_id',
                    name: 'admin_user_id',
                    render: function (data, type, row) {
                        var actions =
                            `<div class="dt-actions">
                                <div class="d-flex gap-2">
                                    <a href="/manager/edit/${data}" class="btn btn-emerald fs-8 d-flex align-items-center">
                                        <span>編集</span>
                                        <span class="ms-1 square-9 rounded-circle bg-white fs-10 text-emerald d-inline-flex justify-content-center align-items-center">
                                            <i class="fa-solid fa-pen p-0 m-0"></i>
                                        </span>
                                    </a>
                                    <button data-id="${data}" class="btn btn-sunset-orange fs-8 d-flex align-items-center">
                                        <span>削除</span>
                                        <span class="ms-1 square-9 rounded-circle bg-white fs-10 text-sunset-orange d-inline-flex justify-content-center align-items-center">
                                            <i class="fa-solid fa-trash p-0 m-0"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>`;
                        return actions;
                    }
                }
            ]
        });
    });
</script>
@endsection