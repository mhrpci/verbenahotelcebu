class Login {
    handleLogin() {

        $('.login-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                username: {
                    required: true
                },
                password: {
                    required: true
                },
                remember: {
                    required: false
                }
            },

            messages: {
                username: {
                    required: 'Username is required.'
                },
                password: {
                    required: 'Password is required.'
                }
            },

            invalidHandler: () => { //display error alert on form submit
                $('.alert-danger', $('.login-form')).show();
            },

            highlight: element => { // highlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: label => {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            errorPlacement: (error, element) => {
                error.insertAfter(element.closest('.form-control'));
            },

            submitHandler: form => {
                form.submit(); // form validation success, call ajax form submit
            }
        });

        $('.login-form input').keypress(e => {
            if (e.which === 13) {
                if ($('.login-form').validate().form()) {
                    $('.login-form').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    handleForgetPassword() {
        $('.forget-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: '',
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },

            messages: {
                email: {
                    required: 'Email is required.'
                }
            },

            invalidHandler: () => { //display error alert on form submit
                $('.alert-danger', $('.forget-form')).show();
            },

            highlight: (element) => { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: label => {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            errorPlacement: (error, element) => {
                error.insertAfter(element.closest('.form-control'));
            },

            submitHandler: form => {
                form.submit();
            }
        });

        $('.forget-form input').keypress(e => {
            if (e.which === 13) {
                if ($('.forget-form').validate().form()) {
                    $('.forget-form').submit();
                }
                return false;
            }
        });

    }

    init() {
        this.handleLogin();
        this.handleForgetPassword();
    }
}

$(document).ready(() => {
    new Login().init();
});
