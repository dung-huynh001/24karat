<div class="mb-4 row">
    <label for="" class="col-sm-3 col-form-label ps-4">textbox (テキストボックス) <span
            class="text-sunset-orange">*</span></label>
    <div class="col-sm-9">
        @foreach ($member_field_format_trns as $index => $fieldFormatTrn)
        <div class="form-check">
            <input class="form-check-input" type="radio"
                name="textbox_radio" id="member_field_format_trn_{{$index + 1}}"
                value="{{$fieldFormatTrn->member_field_format_trn_id}}"
                {{$index == 0 ? 'checked' : ''}}
                {{ isset($memberFields) && $fieldFormatTrn->member_field_format_trn_id == $memberFields->member_field_format_trn_id ? 'checked' : '' }}>
            <label class="form-check-label" for="member_field_format_trn_{{$index + 1}}">
                {{$fieldFormatTrn->member_field_format_trn_name}}
            </label>
        </div>
        @endforeach
        <div id="" class="text-sunset-orange validate-msg"></div>
    </div>
    <div class="col-sm-9 offset-sm-3 mt-3">
        <label for="textbox-preview" class="form-label">プレビュー</label>
        <div class="ms-4" id="preview">
            <input type="text" id="textbox-preview" class="form-control" autocomplete="off">
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
        $('input[name="textbox_radio"]').on('change', (event) => {
            const selectedValue = $(event.target).val();
            console.log(selectedValue);
            let previewHtml;
            if (selectedValue == 1) {
                previewHtml = `<input type="text" id="textbox-preview" class="form-control" autocomplete="off">`;
            } else if (selectedValue == 2) {
                previewHtml = `<textarea class="form-control" id="textbox-preview" rows="3"></textarea>`;
            } else {
                previewHtml = `<input id="textbox-preview" class="form-control" placeholder="email@example.com" autocomplete="off">`;
            }

            $('#preview').html(previewHtml);
        });
    });
</script>