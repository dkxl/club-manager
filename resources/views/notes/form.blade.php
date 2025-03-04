<form method="POST" action="{{ $action }}" accept-charset="UTF-8" class="form-horizontal" onsubmit="return false;">
    @csrf

    <input name="id" type="hidden" value="{{ $note->id }}">
    <input name="member_id" type="hidden" value="{{ $note->member_id }}">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group required">
                <label class="col-sm-4" for="topic">Topic</label>
                <div class="col-sm-8">
                    <select class="form-control" name="topic">
                        <option value="">-- select --</option>
                        @foreach($note->topics as $value => $label)
                        <option value="{{ $value }}" @selected($value === $note->topic)>
                            {{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <x-forms.text-area name="note" label="Note" :value="$note->note" required/>
        </div><!-- column -->

        <div class="col-md-6">
            <div class="form-group">
                <label class="col-sm-4" for="alert">Alert?</label>
                <div class="col-sm-8">
                    <select class="form-control" name="alert">
                        @foreach($note->alerts as $value => $label)
                            <option value="{{ $value }}" @selected($value === $note->alert)>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div><!-- column -->
    </div><!-- row -->
</form>
