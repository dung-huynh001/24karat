<link rel="stylesheet" href="{{ asset('/assets/lib/datatables/css/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('/assets/lib/datatables/css/responsive.dataTables.min.css') }}">
<script src="{{ asset('/assets/lib/datatables/js/datatables.min.js') }}"></script>
<script src="{{ asset('/assets/lib/datatables/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('/assets/lib/datatables/js/responsive.dataTables.min.js') }}"></script>
<script src="{{ asset('/assets/lib/datatables/js/dataTables.fixedHeader.min.js') }}"></script>
<script src="{{ asset('/assets/lib/datatables/js/fixedHeader.dataTables.min.js') }}"></script>
<script src="{{ asset('/assets/lib/datatables/js/dataTables.colReorder.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $.fn.dataTable.ext.errMode = 'none';
    })
</script>