<li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
    <a href="javascript:;" class="dropdown-toggle dropdown-header-name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="icon-envelope-open"></i>
        <span class="badge badge-default"> {{ count($contacts) }} </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-right">
        <li class="external">
            <h3>{!! clean(trans('plugins/contact::contact.new_msg_notice', ['count' => count($contacts)])) !!}</h3>
            <a href="{{ route('contacts.index') }}">{{ trans('plugins/contact::contact.view_all') }}</a>
        </li>
        <li>
            <ul class="dropdown-menu-list scroller" style="height: {{ count($contacts) * 70 }}px;" data-handle-color="#637283">
                @foreach($contacts as $contact)
                    <li>
                        <a href="{{ route('contacts.edit', $contact->id) }}">
                            <span class="photo">
                                <img src="{{ (new \Botble\Base\Supports\Avatar)->create($contact->name)->toBase64() }}" class="rounded-circle" alt="{{ $contact->name }}">
                            </span>
                            <span class="subject"><span class="from"> {{ $contact->name }} </span><span class="time">{{ Carbon\Carbon::parse($contact->created_at)->toDateTimeString() }} </span></span>
                            <span class="message"> {{ $contact->phone }} - {{ $contact->email }} </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
</li>
