@extends('layouts.app')
@section('content')
<div class="mb-4">
    <a role="button" href="{{route("subscription_user.add")}}" class="btn btn-royal-blue">契約ユーザー登録</a>
</div>

<div class="table-responsive">
    <table id="subscriptionUserTbl" class="display responsive">
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

<script>
    let deleteId;
    $(document).ready(function() {
        $.fn.dataTable.ext.errMode = 'none';

        if (localStorage.getItem('edit-success')) {
            $.toast({
                heading: '成功',
                text: '正常に更新されました',
                icon: 'success',
                position: 'top-right'
            })

            localStorage.removeItem('edit-success');
        }

        if (localStorage.getItem('register-success')) {
            $.toast({
                heading: '成功',
                text: 'マネージャー登録が成功しました',
                icon: 'success',
                position: 'top-right'
            })

            localStorage.removeItem('register-success');
        }

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
                    $('#subscriptionUserTbl').DataTable().ajax.reload();
                    $.toast({
                        heading: '成功',
                        text: '正常に削除されました',
                        icon: 'success',
                        position: 'top-right'
                    })
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



        const table = $('#subscriptionUserTbl').DataTable({
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
                }
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
                },
                {
                    responsivePriority: 1,
                    targets: 0
                },
                {
                    responsivePriority: 2,
                    targets: 1
                },
                {
                    responsivePriority: 3,
                    targets: 2
                },
                {
                    responsivePriority: 4,
                    targets: '_all'
                }
            ],
            order: [1, 'asc'],
            ajax: function(data, callback, settings) {
                const startTime = new Date().getTime();
                const timeout = 10000;
                const emptyResult = {
                    data: [],
                    recordsFiltered: 0,
                    recordsTotal: 0
                }

                $.ajax({
                    url: "{{ url('/subscription_user/get-subscription_users') }}",
                    type: 'GET',
                    dataType: 'json',
                    // data: update_column_filters_from_ajax_data,
                    success: function(response) {
                        const currentTime = new Date().getTime();
                        if (currentTime - startTime > timeout) {
                            console.warn('Request timeout!');
                            callback(emptyResult);
                        } else {
                            return callback({
                                data: response.data,
                                recordsFiltered: response.recordsFiltered,
                                recordsTotal: response.recordsTotal
                            });
                        }
                    },
                    error: function(xhr, error, code) {
                        console.error('API error!');
                        callback(emptyResult);
                    }
                });
            },
            columns: [{
                    className: 'dtr-control',
                    orderable: false,
                    targets: 0
                },
                {
                    title: 'No.',
                    data: 'subscription_user_id',
                    name: 'subscription_user_id'
                },
                {
                    title: 'サブドメイン',
                    data: 'sub_domain',
                    name: 'sub_domain',
                    render: function(data, type, row) {
                        const url = `https://${data}.members.local`;
                        return `<a class="link-primary" target="_blank" href="${url}">${url}</a>`
                    }
                },
                {
                    title: 'バーコード種類',
                    data: 'barcode_type',
                    name: 'barcode_type'
                },
                {
                    title: '契約ユーザー',
                    data: 'company_name',
                    name: 'company_name'
                },
                {
                    title: '郵便番号',
                    data: 'zip',
                    name: 'zip'
                },
                {
                    title: '都道府県',
                    data: 'name',
                    name: 'name'
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
                    // title: '操作',
                    // visible: false,
                    orderable: false,
                    data: 'subscription_user_id',
                    name: 'subscription_user_id',
                    render: function(data, type, row) {
                        var actions =
                            `<div class="dt-actions">
                                <div class="d-flex gap-2">
                                    <a href="/subscription_user/edit/${data}" class="btn btn-emerald fs-8 d-flex align-items-center">
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

        $('.dt-search>label').on('click', (event) => {
            const keyword = $('#dt-search-0').val();
            table.search(keyword).draw();
        });
    });
</script>
@endsection