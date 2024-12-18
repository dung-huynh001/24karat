@extends('layouts.app')
@extends('layouts.breadcrumb')
@section('content')

<div class="mb-4">
    <a role="button" href="{{route("manager.register")}}" class="btn btn-royal-blue">管理者登録</a>
</div>

<div class="table-responsive">
    <table id="managerTbl" class="display responsive ">
        <thead>
            <tr></tr>
        </thead>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="deleteModalLabel">削除の確認</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                このマネージャーを削除してもよろしいですか？
            </div>
            <div class="modal-footer">
                <button id="btn_delete_confirm" type="button" class="btn btn-royal-blue">はい</button>
                <button id="btn_delete_cancel" type="button" class="btn btn-sunset-orange"
                    data-bs-dismiss="modal">いいえ</button>
            </div>
        </div>
    </div>
</div>

<!-- DataTables -->
@include('partials.datatables')
@include('partials.toast')

<script>
    let deleteId;

    $(document).ready(function() {
        $(document).on('click', '.btn_delete', function(event) {
            const btnId = $(this).data('id');
            deleteId = btnId;
            console.log(`Id bị xóa là: ${deleteId}`);
        });


        $('#btn_delete_confirm').on('click', function(event) {
            const urlDelete = `delete/${deleteId}`;
            $.ajax({
                url: urlDelete,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    $('#deleteModal').modal('hide');
                    $('#managerTbl').DataTable().ajax.reload();
                    localStorage.setItem('delete-success', 'true')
                },
                error: function(xhr) {
                    const errmsg = xhr.responseText;
                    $.toast({
                        heading: 'エラー',
                        text: errmsg,
                        icon: 'error',
                        position: 'top-right'
                    })
                    $('#deleteModal').modal('hide');
                }
            });
        });



        const table = $('#managerTbl').DataTable({
            processing: true,
            serverSide: true,
            colReorder: true,
            search: {
                return: true,
            },
            fixedHeader: {
                header: true,
                footer: true
            },
            responsive: true,
            orderClasses: ['sorting_disabled', 'sorting', 'sorting_asc_custom', 'sorting_desc_custom'],
            language: {
                sLengthMenu: "_MENU_",
                sInfo: "_END_ 件 _PAGE_ ページ目",
                sProcessing: "処理中...",
                sZeroRecords: "データはありません。",
                sInfoEmpty: " 0 件中 0 から 0 まで表示",
                sInfoFiltered: "（全 _MAX_ 件より抽出）",
                sInfoPostFix: "",
                sSearch: "検索",
                sSearchPlaceholder: `フリーワードを入力してください`,
                sUrl: "",
                oPaginate: {
                    "sFirst": "先頭",
                    "sPrevious": "前",
                    "sNext": "次",
                    "sLast": "最終"
                },
                emptyTable: "データはありません。",
            },
            layout: {
                topStart: 'search',
                topEnd: ['info', 'pageLength'],
                bottomEnd: 'paging',
                bottomStart: '',
            },
            columnDefs: [{
                className: 'dtr-control',
                orderable: false,
                targets: 0,
                width: '8px',
            }],
            order: [1, 'asc'],
            ajax: "/manager/get-managers",
            columns: [{
                    className: 'dtr-control',
                    orderable: false,
                    targets: 0
                },
                {
                    title: 'No.',
                    data: 'admin_user_id',
                    name: 'admin_user_id'
                },
                {
                    title: '名前',
                    data: 'name',
                    name: 'name'
                },
                {
                    title: 'メールアドレス',
                    data: 'email',
                    name: 'email'
                },
                {
                    title: '契約ユーザー',
                    data: 'company_name',
                    name: 'company_name'
                },
                {
                    // title: 'is_butterflydance_user',
                    data: 'is_butterflydance_user',
                    name: 'is_butterflydance_user',
                    orderable: false,
                    searchable: false,
                    visible: false,
                },
                {
                    title: '作成日',
                    data: 'created_at',
                    name: 'created_at',
                    render: function(data, type, row) {
                        var date = new Date(data);
                        return date.toLocaleDateString('ja-JP', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            timeZone: 'Asia/Tokyo'
                        });
                    }
                },
                {
                    title: '更新日時',
                    data: 'updated_at',
                    name: 'updated_at',
                    render: function(data, type, row) {
                        var date = new Date(data);
                        return date.toLocaleDateString('ja-JP', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            timeZone: 'Asia/Tokyo'
                        });
                    }
                },
                {
                    orderable: false,
                    data: 'admin_user_id',
                    name: 'admin_user_id',
                    render: function(data, type, row) {
                        const actions =
                            `<div class="dt-actions ${row.is_butterflydance_user ? "is_butterflydance_user": ""}">
                                <div class="d-flex gap-2">
                                    <a href="/manager/edit/${data}" class="btn btn-emerald fs-8 d-flex align-items-center">
                                        <span>編集</span>
                                        <span class="ms-1 square-9 rounded-circle bg-white fs-10 text-emerald d-inline-flex justify-content-center align-items-center">
                                            <i class="fa-solid fa-pen p-0 m-0"></i>
                                        </span>
                                    </a>
                                    <button id="btn_delete_${data}" data-id="${data}" class="btn btn-sunset-orange fs-8 d-flex align-items-center btn_delete" data-bs-toggle="modal" data-bs-target="#deleteModal">
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

        //Handle search button click
        $('.dt-search>label').on('click', (event) => {
            const keyword = $('#dt-search-0').val();
            table.search(keyword).draw();
        });
    });
</script>
@endsection