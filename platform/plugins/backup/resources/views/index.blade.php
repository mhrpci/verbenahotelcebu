@extends('core/base::layouts.master')
@section('content')
    <div class="clearfix"></div>
    <p><button class="btn btn-primary" id="generate_backup">{{ trans('plugins/backup::backup.generate_btn') }}</button></p>
    <table class="table table-striped" id="table-backups">
        <thead>
            <tr>
                <th>{{ trans('core/base::tables.name') }}</th>
                <th>{{ trans('core/base::tables.description') }}</th>
                <th>{{ trans('plugins/backup::backup.size') }}</th>
                <th>{{ trans('core/base::tables.created_at') }}</th>
                <th>{{ trans('core/table::table.operations') }}</th>
            </tr>
        </thead>
        <tbody>
            @if (count($backups) > 0)
                @foreach($backups as $key => $backup)
                    @include('plugins/backup::partials.backup-item', ['data' => $backup, 'key' => $key, 'odd' => $loop->index % 2 == 0 ? true : false])
                @endforeach
            @else
                <tr class="text-center no-backup-row">
                    <td colspan="5">{{ trans('plugins/backup::backup.no_backups') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
    {!! Form::modalAction('create-backup-modal', trans('plugins/backup::backup.create'), 'info', view('plugins/backup::partials.create')->render(), 'create-backup-button', trans('plugins/backup::backup.create_btn')) !!}
    {!! Form::modalAction('restore-backup-modal', trans('plugins/backup::backup.restore'), 'info', trans('plugins/backup::backup.restore_confirm_msg'), 'restore-backup-button', trans('plugins/backup::backup.restore_btn')) !!}
    <div data-route-create="{{ route('backups.create') }}"></div>

    @include('core/table::modal')
@stop
