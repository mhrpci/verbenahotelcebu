class ContactPluginManagement {
    init() {
        $(document).on('click', '.answer-trigger-button', event =>  {
            event.preventDefault();
            event.stopPropagation();

            const answerWrapper = $('.answer-wrapper');
            if (answerWrapper.is(':visible')) {
                answerWrapper.fadeOut();
            } else {
                answerWrapper.fadeIn();
            }
        });

        $(document).on('click', '.answer-send-button', event =>  {
            event.preventDefault();
            event.stopPropagation();

            $(event.currentTarget).addClass('button-loading');

            let message = '';
            if (typeof tinymce != 'undefined') {
                message = tinymce.get('message').getContent();
            } else if (CKEDITOR.instances['message'] && typeof CKEDITOR.instances['message'] !== 'undefined') {
                message = CKEDITOR.instances['message'].getData();
            }

            $.ajax({
                type: 'POST',
                cache: false,
                url: route('contacts.reply', $('#input_contact_id').val()),
                data: {
                    message: message
                },
                success: res =>  {
                    if (!res.error) {
                        $('.answer-wrapper').fadeOut();
                        if (typeof tinymce != 'undefined') {
                            tinymce.get('message').setContent('');
                        } else if (CKEDITOR.instances['message'] && typeof CKEDITOR.instances['message'] !== 'undefined') {
                            CKEDITOR.instances['message'].setData('');
                        }
                        Botble.showSuccess(res.message);
                        $('#reply-wrapper').load(window.location.href + ' #reply-wrapper > *');
                    }

                    $(event.currentTarget).removeClass('button-loading');
                },
                error: res =>  {
                    $(event.currentTarget).removeClass('button-loading');
                    Botble.handleError(res);
                }
            });
        });
    };
}

$(document).ready(() => {
    new ContactPluginManagement().init();
});
