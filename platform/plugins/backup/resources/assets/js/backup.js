class BackupManagement {
    init() {
        let table_backup = $('#table-backups');
        table_backup.on('click', '.deleteDialog', event => {
            event.preventDefault();

            $('.delete-crud-entry').data('section', $(event.currentTarget).data('section'));
            $('.modal-confirm-delete').modal('show');
        });

        table_backup.on('click', '.restoreBackup', event => {
            event.preventDefault();
            $('#restore-backup-button').data('section', $(event.currentTarget).data('section'));
            $('#restore-backup-modal').modal('show');
        });

        $('.delete-crud-entry').on('click', event =>  {
            event.preventDefault();
            $('.modal-confirm-delete').modal('hide');

            let deleteURL = $(event.currentTarget).data('section');

            $.ajax({
                url: deleteURL,
                type: 'DELETE',
                success: data => {
                    if (data.error) {
                        Botble.showError(data.message);
                    } else {
                        table_backup.find('a[data-section="' + deleteURL + '"]').closest('tr').remove();
                        Botble.showSuccess(data.message);
                    }
                },
                error: data => {
                    Botble.handleError(data);
                }
            });
        });

        $('#restore-backup-button').on('click', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            _self.addClass('button-loading');

            $.ajax({
                url: _self.data('section'),
                type: 'GET',
                success: data => {
                    _self.removeClass('button-loading');
                    _self.closest('.modal').modal('hide');

                    if (data.error) {
                        Botble.showError(data.message);
                    } else {
                        Botble.showSuccess(data.message);
                        window.location.reload();
                    }
                },
                error: data => {
                    _self.removeClass('button-loading');
                    Botble.handleError(data);
                }
            });
        });

        $(document).on('click', '#generate_backup', event => {
            event.preventDefault();
            $('#name').val('');
            $('#description').val('');
            $('#create-backup-modal').modal('show');
        });

        $('#create-backup-modal').on('click', '#create-backup-button', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            _self.addClass('button-loading');

            let name = $('#name').val();
            let description = $('#description').val();
            let error = false;
            if (name === '' || name === null) {
                error = true;
                Botble.showError('Backup name is required!');
            }
            if (description === '' || description === null) {
                error = true;
                Botble.showError('Backup description is required!');
            }

            if (!error) {
                $.ajax({
                    url: $('div[data-route-create]').data('route-create'),
                    type: 'POST',
                    data: {
                        name: name,
                        description: description
                    },
                    success: data => {
                        _self.removeClass('button-loading');
                        _self.closest('.modal').modal('hide');

                        if (data.error) {
                            Botble.showError(data.message);
                        } else {
                            table_backup.find('.no-backup-row').remove();
                            table_backup.find('tbody').append(data.data);
                            Botble.showSuccess(data.message);
                        }
                    },
                    error: data => {
                        _self.removeClass('button-loading');
                        Botble.handleError(data);
                    }
                });
            } else {
                _self.removeClass('button-loading');
            }
        });
    }
}

$(document).ready(() => {
    new BackupManagement().init();
});
