<div class="form-group">
    <label class="control-label">Youtube URL</label>
    <input name="url" data-shortcode-attribute="url" class="form-control"
           placeholder="https://www.youtube.com/watch?v=FN7ALfpGxiI">
</div>

<div class="form-group">
    <label class="control-label">Background Image</label>
    {!! Form::mediaImage('background_image', null, ['data-shortcode-attribute' => 'background_image']) !!}
</div>
