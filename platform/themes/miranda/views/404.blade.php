@php
    SeoHelper::setTitle(__('404 - Not found'));
    Theme::fire('beforeRenderTheme', app(\Botble\Theme\Contracts\Theme::class));
@endphp

{!! Theme::partial('header') !!}
<style>
    .error-code {
        color: #22292f;
        font-size: 6rem;
    }

    .error-border {
        background-color: var(--color-1st);
        height: .25rem;
        width: 6rem;
        margin-bottom: 1.5rem;
    }

    .error-page a {
        color: var(--color-1st);
    }

    .error-page ul li {
        margin-bottom : 5px;
    }
</style>
<section class="blog-section contact-part pt-120 pb-120">
    <div class="container">
        <div class="page-content error-page">
            <br>
            <br>
            <div class="error-code">
                404
            </div>

            <div class="error-border"></div>

            <h4>{{ __('This may have occurred because of several reasons') }}:</h4>
            <ul>
                <li>{{ __('The page you requested does not exist.') }}</li>
                <li>{{ __('The link you clicked is no longer.') }}</li>
                <li>{{ __('The page may have moved to a new location.') }}</li>
                <li>{{ __('An error may have occurred.') }}</li>
                <li>{{ __('You are not authorized to view the requested resource.') }}</li>
            </ul>
            <br>

            <strong>{!! clean(__('Please try again in a few minutes, or alternatively return to the homepage by <a href=":link">clicking here</a>.', ['link' => route('public.single')])) !!}</strong>
        </div>
    </div>
</section>
{!! Theme::partial('footer') !!}


