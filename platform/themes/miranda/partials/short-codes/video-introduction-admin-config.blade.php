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
    <label class="control-label">Description</label>
    <textarea name="description" data-shortcode-attribute="description" class="form-control" placeholder="Description"
              rows="3"></textarea>
</div>

<div class="form-group">
    <label class="control-label">Background Image</label>
    {!! Form::mediaImage('background_image', null, ['data-shortcode-attribute' => 'background_image']) !!}
</div>

<div class="form-group">
    <label class="control-label">Video Image</label>
    {!! Form::mediaImage('video_image', null, ['data-shortcode-attribute' => 'video_image']) !!}
</div>

<div class="form-group">
    <label class="control-label">Video URL</label>
    <input type="text" name="video_url" data-shortcode-attribute="video_url" class="form-control"
           placeholder="Video URL">
</div>

<div class="form-group">
    <label class="control-label">Button text</label>
    <input type="text" name="button_text" data-shortcode-attribute="button_text" class="form-control"
           placeholder="Button text">
</div>

<div class="form-group">
    <label class="control-label">Button URL</label>
    <input type="text" name="button_url" data-shortcode-attribute="button_url" class="form-control"
           placeholder="Button URL">
</div>
