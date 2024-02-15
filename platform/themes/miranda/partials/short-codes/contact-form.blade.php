<div class="contact-form">
{!! Form::open(['route' => 'public.send.contact', 'class' => 'form--contact', 'method' => 'POST']) !!}

    <div class="row">
        <div class="col-md-6">
            <div class="input-group mb-30">
                <span class="icon"><i class="far fa-user"></i></span>
                <input type="text" name="name" value="{{ old('name') }}" id="contact_name"
                       placeholder="{{ __('Name') }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group mb-30">
                <span class="icon"><i class="far fa-envelope"></i></span>
                <input type="email" name="email" value="{{ old('email') }}" id="contact_email"
                       placeholder="{{ __('Email') }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group mb-30">
                <span class="icon"><i class="fal fa-map-marker-alt"></i></span>
                <input type="text" name="address" value="{{ old('address') }}" id="contact_address"
                       placeholder="{{ __('Address') }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group mb-30">
                <span class="icon"><i class="far fa-phone"></i></span>
                <input type="text" name="phone" value="{{ old('phone') }}" id="contact_phone"
                       placeholder="{{ __('Phone') }}">
            </div>
        </div>
        <div class="col-md-12">
            <div class="input-group mb-30">
                <span class="icon"><i class="far fa-book"></i></span>
                <input type="text" name="subject" value="{{ old('subject') }}" id="contact_subject"
                       placeholder="{{ __('Subject') }}">
            </div>
        </div>
        <div class="col-md-12">
            <div class="input-group textarea mb-30">
                <span class="icon"><i class="far fa-pen"></i></span>
                <textarea name="content" id="contact_content" rows="5" placeholder="{{ __('Message') }}">{{ old('content') }}</textarea>
            </div>
        </div>
        @if (setting('enable_captcha') && is_plugin_active('captcha'))
            <div class="col-md-12">
                <div class="input-group mb-30">
                    {!! Captcha::display() !!}
                </div>
            </div>
        @endif

        <div class="col-12">
            <div class="input-group mb-30">
                <button type="submit" class="main-btn btn-filled">{{ __('Get Free Quote') }}</button>
            </div>
        </div>

        <div class="col-12">
            <div class="input-group">
                <div class="contact-message contact-success-message" style="display: none"></div>
                <div class="contact-message contact-error-message" style="display: none"></div>
            </div>
        </div>

    </div>

{!! Form::close() !!}
</div>
