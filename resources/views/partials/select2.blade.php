<link rel="stylesheet" href="{{ asset('/assets/lib/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('/assets/lib/select2/css/select2-bootstrap-5-theme.min.css') }}">
<script src="{{ asset('/assets/lib/select2/js/select2.min.js') }}"></script>
<script>
    $('select').select2({
        theme: 'bootstrap-5'
    });

    // Responsive Select2
    $(window).resize(function() {
        $('select').select2('destroy').select2({
            theme: 'bootstrap-5'
        });
    });
</script>