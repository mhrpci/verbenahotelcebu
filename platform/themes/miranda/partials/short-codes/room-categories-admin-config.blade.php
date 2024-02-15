<div class="form-group">
    <label class="control-label">Title</label>
    <input type="text" name="title" data-shortcode-attribute="title" class="form-control" placeholder="Title">
</div>

<div class="form-group">
    <label class="control-label">Sub Title</label>
    <input type="text" name="sub_title" data-shortcode-attribute="sub_title" class="form-control"
           placeholder="Sub Title">
</div>

<div class="form-group">
    <label class="control-label">Background Image</label>
    {!! Form::mediaImage('background_image', null, ['data-shortcode-attribute' => 'background_image']) !!}
</div>
