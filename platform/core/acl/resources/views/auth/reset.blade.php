@extends('core/acl::auth.master')
@section('content')
    <p>{{ trans('core/acl::auth.reset_password') }}:</p>
    {!! Form::open(['route' => 'access.password.reset.post', 'method' => 'POST', 'class' => 'login-form']) !!}
        <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}" id="emailGroup">
            <label>{{ trans('core/acl::auth.reset.email') }}</label>
            {!! Form::text('email', old('email', $email), ['class' => 'form-control', 'placeholder' => trans('core/acl::auth.reset.email')]) !!}
        </div>

        <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}" id="passwordGroup">
            <label>{{ trans('core/acl::auth.reset.new_password') }}</label>
            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => trans('core/acl::auth.reset.new_password')]) !!}
        </div>

        <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}" id="passwordConfirmationGroup">
            <label>{{ trans('core/acl::auth.password_confirmation') }}</label>
            {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('core/acl::auth.reset.password_confirmation')]) !!}
        </div>

        <button type="submit" class="btn btn-block login-button">
            <input type="hidden" name="token" value="{{ $token }}"/>
            <span class="signin">{{ trans('core/acl::auth.reset.update') }}</span>
        </button>
        <div class="clearfix"></div>
    {!! Form::close() !!}
@stop

@push('footer')
    <script>
        var email = document.querySelector('[name="email"]');
        var password = document.querySelector('[name="password"]');
        var passwordConfirmation = document.querySelector('[name="password_confirmation"]');
        email.focus();
        document.getElementById('emailGroup').classList.add('focused');

        // Focus events for email and password fields
        email.addEventListener('focusin', function(){
            document.getElementById('emailGroup').classList.add('focused');
        });
        email.addEventListener('focusout', function(){
            document.getElementById('emailGroup').classList.remove('focused');
        });

        password.addEventListener('focusin', function(){
            document.getElementById('passwordGroup').classList.add('focused');
        });
        password.addEventListener('focusout', function(){
            document.getElementById('passwordGroup').classList.remove('focused');
        });

        passwordConfirmation.addEventListener('focusin', function(){
            document.getElementById('passwordConfirmationGroup').classList.add('focused');
        });
        passwordConfirmation.addEventListener('focusout', function(){
            document.getElementById('passwordConfirmationGroup').classList.remove('focused');
        });
    </script>
@endpush
