<script>
    /* Disable autofill input */
    $(document).ready(() => {
        $('input').attr('readonly', true).on('focus', function () {
            $(this).removeAttr('readonly');
        });
    });
</script>