<div id="dates-calendar" class="dates-calendar"></div>

<div id="modal-calendar" class="modal fade">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form_modal_calendar form-horizontal" novalidate onsubmit="return false">
                    <div class="form-group">
                        <label >{{ trans('core/base::tables.status') }}</label>
                        <br>
                        <label ><input type="checkbox" true-value="1" false-value="0" v-model="form.active"> {{ trans('plugins/hotel::room.form.is_available') }}</label>
                    </div>
                    <div class="row">
                        <div class="col-md-6" v-show="form.active">
                            <div class="form-group">
                                <label for="price-input">{{ trans('plugins/hotel::room.form.price') }}</label>
                                <input type="number" id="price-input" v-model="form.price" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6" v-show="form.active">
                            <div class="form-group">
                                <label for="number-of-rooms">{{ trans('plugins/hotel::room.form.number_of_rooms') }}</label>
                                <input type="number" id="number-of-rooms" v-model="form.number_of_rooms" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('plugins/hotel::room.form.close') }}</button>
                <button type="button" class="btn btn-primary" @click="saveForm">{{ trans('plugins/hotel::room.form.save_changes') }}</button>
            </div>
        </div>
    </div>
</div>

<div data-get-room-availability-url="{{ route('room.availability', $object->id) }}"></div>
