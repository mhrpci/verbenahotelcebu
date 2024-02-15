{!! $dataTable->table(compact('id', 'class'), false) !!}
@push('footer')
    {!! $dataTable->scripts() !!}
@endpush
