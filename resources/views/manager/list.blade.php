@extends('layouts.app')
@extends('layouts.breadcrumb')
@section('content')
<!-- DataTables CSS -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"> -->
<link rel="stylesheet" href="{{ asset('datatables/css/datatables.min.css') }}">



<table id="myTable" class="display">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Ngày tạo</th>
        </tr>
    </thead>
    <tbody>
        <?php
// Giả sử bạn có một array chứa dữ liệu người dùng
$users = [
    ['id' => 1, 'name' => 'Nguyen Van A', 'email' => 'a@example.com', 'created_at' => '2024-01-01'],
    ['id' => 2, 'name' => 'Le Thi B', 'email' => 'b@example.com', 'created_at' => '2024-01-05'],
    // Thêm nhiều dữ liệu khác
];

foreach ($users as $user) {
    echo "<tr>
                    <td>{$user['id']}</td>
                    <td>{$user['name']}</td>
                    <td>{$user['email']}</td>
                    <td>{$user['created_at']}</td>
                  </tr>";
}
        ?>
    </tbody>
</table>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="{{ asset('datatables/js/datatables.min.js') }}"></script>

<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
</script>
@endsection