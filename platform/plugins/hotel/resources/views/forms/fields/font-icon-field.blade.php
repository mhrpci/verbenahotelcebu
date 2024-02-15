@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        <div {!! $options['wrapperAttrs'] !!}>
    @endif
@endif

@if ($showLabel && $options['label'] !== false && $options['label_show'])
    {!! Form::customLabel($name, $options['label'], $options['label_attr']) !!}
@endif

@if ($showField)
    @php
        $icons = [
            'flaticon-menu',
            'flaticon-call',
            'flaticon-phone',
            'flaticon-envelope',
            'flaticon-message',
            'flaticon-clipboard',
            'flaticon-checklist',
            'flaticon-calendar',
            'flaticon-event',
            'flaticon-man',
            'flaticon-user',
            'flaticon-group',
            'flaticon-teamwork',
            'flaticon-team',
            'flaticon-back',
            'flaticon-arrow',
            'flaticon-tick',
            'flaticon-close',
            'flaticon-accept',
            'flaticon-menu-1',
            'flaticon-upload',
            'flaticon-next',
            'flaticon-download',
            'flaticon-back-1',
            'flaticon-back-3',
            'flaticon-back-2',
            'flaticon-bread',
            'flaticon-breakfast',
            'flaticon-coffee',
            'flaticon-airplane',
            'flaticon-marker',
            'flaticon-location-pin',
            'flaticon-barbecue',
            'flaticon-hotel',
            'flaticon-pixels',
            'flaticon-air-freight',
            'flaticon-world',
            'flaticon-fast-food',
            'flaticon-swimming',
            'flaticon-paper-plane',
            'flaticon-facebook',
            'flaticon-twitter',
            'flaticon-behance',
            'flaticon-youtube',
            'flaticon-linkedin',
            'flaticon-rating',
            'flaticon-clock',
            'flaticon-clock-1',
            'flaticon-credit-card',
            'flaticon-discount',
            'flaticon-user-1',
            'flaticon-like',
            'flaticon-suitcase',
            'flaticon-bed',
            'flaticon-wifi',
            'flaticon-car',
            'flaticon-coffee-cup',
            'flaticon-dice',
            'flaticon-serving-dish',
            'flaticon-expand',
            'flaticon-double-angle-pointing-to-right',
            'flaticon-double-left-chevron',
            'flaticon-bath',
            'flaticon-cut',
            'flaticon-housekeeping',
            'flaticon-groceries',
            'flaticon-shopping-cart',
            'flaticon-kitchen',
            'flaticon-hamburger',
            'flaticon-towel',
            'flaticon-key',
            'flaticon-shield',
            'flaticon-headphones',
            'flaticon-pizza',
            'flaticon-cake',
            'flaticon-boiled',
            'flaticon-cookie',
            'flaticon-cocktail',
            'flaticon-witness',
            'flaticon-chat',
            'flaticon-quote',
            'flaticon-quote-1',
            'flaticon-search',
            'flaticon-grid',
            'flaticon-back-4',
            'flaticon-pen',
            'flaticon-globe',
            'flaticon-home',
            'flaticon-notebook',
        ];

     Arr::set($options['attr'], 'class', Arr::get($options['attr'], 'class') . ' icon-select');

    @endphp
    {!! Form::select($name, array_combine($icons, $icons), $options['value'] ? $options['value'] : $options['default_value'], $options['attr']) !!}
    @include('core/base::forms.partials.help-block')
@endif

@include('core/base::forms.partials.errors')

@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        </div>
    @endif
@endif

@once
    @push('footer')
        <link media="all" type="text/css" rel="stylesheet" href="{{ Theme::asset()->url('css/flaticon.css') }}">
        <script>
            $(document).ready(function () {
                $('.icon-select').select2({
                    templateResult: function (state) {
                        if (!state.id) {
                            return state.text;
                        }
                        return $('<span><i class="' + state.id + '"></i></span>&nbsp; ' + state.text + '</span>');
                    },
                    templateSelection: function (state) {
                        if (!state.id) {
                            return state.text;
                        }
                        return $('<span><i class="' + state.id + '"></i></span>&nbsp; ' + state.text + '</span>');
                    },
                });
            });
        </script>

    @endpush
@endonce
