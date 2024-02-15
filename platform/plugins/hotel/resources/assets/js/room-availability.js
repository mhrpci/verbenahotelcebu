import Vue from 'vue';

$(document).ready(function () {
    let calendarEl;
    let calendar;
    let formModal;

    calendarEl = document.getElementById('dates-calendar');
    if (calendar) {
        calendar.destroy();
    }
    calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'title',
        },
        navLinks: true, // can click day/week names to navigate views
        editable: false,
        dayMaxEvents: false, // allow "more" link when too many events
        events: {
            url: $('div[data-get-room-availability-url]').data('get-room-availability-url'),
        },
        loading: isLoading => {
            if (!isLoading) {
                $(calendarEl).removeClass('loading');
            } else {
                $(calendarEl).addClass('loading');
            }
        },
        select: arg => {
            formModal.show({
                start_date: moment(arg.start).format('YYYY-MM-DD'),
                end_date: moment(arg.end).format('YYYY-MM-DD'),
            });
        },
        eventClick: info => {
            let form = Object.assign({}, info.event.extendedProps);
            form.start_date = moment(info.event.start).format('YYYY-MM-DD');
            form.end_date = moment(info.event.start).format('YYYY-MM-DD');
            formModal.show(form);
        },
        eventRender: info => {
            $(info.el).find('.fc-title').html(info.event.title);
        }
    });
    calendar.render();

    formModal = new Vue({
        el: '#modal-calendar',
        data: {
            form: {
                id: '',
                price: '',
                start_date: '',
                end_date: '',
                enable_person: 0,
                min_guests: 0,
                max_guests: 0,
                active: 0,
                number_of_rooms: 1
            },
            formDefault: {
                id: '',
                price: '',
                start_date: '',
                end_date: '',
                enable_person: 0,
                min_guests: 0,
                max_guests: 0,
                active: 0,
                number_of_rooms: 1
            },
            onSubmit: false
        },
        methods: {
            show: function (form) {
                $(this.$el).modal('show');
                this.onSubmit = false;

                if (typeof form != 'undefined') {
                    this.form = Object.assign({}, form);

                    if (form.start_date) {
                        $('.modal-title').text(moment(form.start_date).format('MM/DD/YYYY'));
                    }
                }
            },
            hide: function () {
                $(this.$el).modal('hide');
                this.form = Object.assign({}, this.formDefault);
            },
            saveForm: function () {
                let _self = this;
                if (this.onSubmit) {
                    return;
                }

                if (!this.validateForm()) {
                    return;
                }

                $(_self.$el).find('.btn-primary').addClass('button-loading');

                this.onSubmit = true;
                $.ajax({
                    url: $('div[data-get-room-availability-url]').data('get-room-availability-url'),
                    data: this.form,
                    dataType: 'json',
                    method: 'POST',
                    success: res => {
                        if (!res.error) {
                            if (calendar) {
                                calendar.refetchEvents();
                            }
                            _self.hide();
                            Botble.showSuccess(res.message);
                        } else {
                            Botble.showError(res.message);
                        }
                        _self.onSubmit = false;
                        $(_self.$el).find('.btn-primary').removeClass('button-loading');
                    },
                    error: () => {
                        _self.onSubmit = false;
                        $(_self.$el).find('.btn-primary').removeClass('button-loading');
                    }
                });
            },
            validateForm: function () {
                if (!this.form.start_date) {
                    return false;
                }

                return this.form.end_date;
            },
        },
        created: function () {
            let _self = this;
            this.$nextTick(function () {
                $(_self.$el).on('hide.bs.modal', function () {
                    this.form = Object.assign({}, this.formDefault);
                });
            })
        },
    });
});
