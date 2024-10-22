@extends('layouts.app')
@extends('layouts.breadcrumb')
@section('content')
<!-- DataTables CSS -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"> -->
<link rel="stylesheet" href="{{ asset('datatables/css/datatables.min.css') }}">

<table id="managerTbl" class="display">
    <thead>
        <tr>
            <!-- <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Created At</th>
            <th>Updated At</th> -->
        </tr>
    </thead>
</table>

<!-- jQuery -->
<script src="{{ asset('datatables/js/jquery-3.6.0.min.js') }}"></script>
<!-- DataTables JS -->
<script src="{{ asset('datatables/js/datatables.min.js') }}"></script>

<script>
    $(document).ready(function () {
        $('#managerTbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('manager/get-managers') }}",
            columns: [
                { title: 'ID', data: 'admin_user_id', name: 'admin_user_id' },
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
                }
            ]
        });
    });
</script>
@endsection