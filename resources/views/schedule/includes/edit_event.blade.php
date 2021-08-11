<x-modal modalId="modalEditEvent" modalTitle="{{ __('schedule.title.event.edit') }}" modalMethod="PUT">
    <div class="form-group row">
        <label for="game_day" class="col-sm-4 col-form-label">{{ __('game.game_day') }}</label>
        <div class="col-sm-6">
            <input type="text" readonly class="form-control" id="game_day" value="">
        </div>
    </div>
    <div class="form-group row">
        <label for="game_date" class="col-sm-4 col-form-label">{{ __('game.game_date') }}</label>
        <div class="col-sm-6">
            <div class="input-group date" id="game_date" data-target-input="nearest">
                <input type="text" name='game_date' id='game_date' class="form-control datetimepicker-input " data-target="#game_date" />
                <div class="input-group-append" data-target="#game_date" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row ">
        <label for="full_weekend" class="col-sm-4 col-form-label">{{ __('game.weekend') }}</label>
        <div class="col-sm-6">
            {{ Form::hidden('full_weekend', 0) }}
            {{ Form::checkbox('full_weekend', '1') }}
        </div>
    </div>
    <!--Modal: modalRelatedContent-->
</x-modal>
