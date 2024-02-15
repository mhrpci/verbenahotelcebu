@php
    Assets::addScriptsDirectly([
        'vendor/core/core/base/libraries/tinymce/tinymce.min.js',
        'vendor/core/core/base/js/editor.js',
    ]);

    $attributes['class'] = Arr::get($attributes, 'class', '') . ' form-control editor-tinymce';
    $attributes['id'] = Arr::get($attributes, 'id', $name);
    $attributes['rows'] = Arr::get($attributes, 'rows', 4);
@endphp

{!! Form::textarea($name, $value, $attributes) !!}

@once
    @push('footer')
        <script>
            'use strict';
            function setImageValue(file) {
                $('.mce-btn.mce-open').parent().find('.mce-textbox').val(file);
            }
        </script>
        <iframe id="form_target" name="form_target" style="display:none"></iframe>
        <form id="tinymce_form" action="{{ route('media.files.upload.from.editor') }}" target="form_target" method="post" enctype="multipart/form-data" style="width:0;height:0;overflow:hidden;display: none;">
            @csrf
            <input name="upload" id="upload_file" type="file" onchange="$('#tinymce_form').submit();this.value='';">
            <input type="hidden" value="tinymce" name="upload_type">
        </form>
    @endpush
@endonce
