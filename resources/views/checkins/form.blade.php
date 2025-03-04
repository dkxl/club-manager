<form method="POST" action="{{ route('members.checkins.store', ['member' => $member->id]) }}"
      accept-charset="UTF-8" class="form-horizontal" onsubmit="return false;">
    @csrf

    <input name="id" type="hidden" value="">
    <input name="member_id" type="hidden" value="{{ $member->id }}">
    <input name="card_number" type="hidden" value="Manual">

    <div class="row">
        <div class="col-md-6">
            <h3>Manual Check In</h3>
            <div class="alert alert-success">Click Save to process a manual Check In attempt</div>
        </div><!-- column -->
        <div class="col-md-6">
        </div><!-- column -->
    </div><!-- row -->
</form>
