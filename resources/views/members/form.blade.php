<?php
/** member.blade.php
 *
 * Binds to the Member Model
 *
 * @param Member  $member
 * @author davidh
 * @package dk-appt
 *
 */


// Need id as well as member_id so clientside can determine curId as well as curMemberId for new records

?>
<form method="POST" action="{{ route('members.store') }}" accept-charset="UTF-8" class="form-horizontal"
      onsubmit="return false;">
    @csrf

    <input name="id" type="hidden" value="{{ $member->id }}">
    <input name="member_id" type="hidden" value="{{ $member->id }}">
    <input name="display_name" type="hidden" value="{{ $member->getFullName() }}">

<div class="row">
    <div class="col-md-6">
        <x-forms.text name="first_name" label="First Name" :value="$member->first_name" required autocomplete="off"/>
        <x-forms.text name="last_name" label="Last Name" :value="$member->last_name" required autocomplete="off"/>
        <x-forms.text name="phone" label="Phone Number" :value="$member->phone" required autocomplete="off"/>
        <x-forms.text name="dob" label="Date of Birth" :value="$member->dob" required/>
        <x-forms.selector name="gender" label="Gender" :options="$member->genderTypes" :value="$member->gender"/>
        <x-forms.selector name="honorific" label="Honorific" :options="$member->honorificTypes" :value="$member->honorific"/>
        <x-forms.lookup name="postcode" label="Postcode" button-label="Find Address" :value="$member->postcode"/>

        <div class="form-group row" id="address-selector" hidden>
            <label class="col-sm-4 form-control-label text-primary" for="addresses">Select Address</label>
            <div class="col-sm-8">
                <select class="form-control" name="Select Address" id="addresses">
                    <option value="" disabled selected hidden>Please Choose...</option>
                </select>
            </div>
        </div>

        <x-forms.text name="address_1" label="Address" :value="$member->address_1"/>
        <x-forms.text name="address_2" :value="$member->address_2"/>
        <x-forms.text name="address_3" :value="$member->address_3"/>
        <input name="address_4" type="hidden" value="{{ $member->address_4 }}"/>
        <x-forms.text name="town" label="Town" :value="$member->town"/>
        <x-forms.text name="county" label="County" :value="$member->county"/>
    </div><!-- column -->

    <div class="col-md-6">
        <x-forms.text name="membership" label="Membership Type" :value="$member->getMembershipType()" readonly/>
        <x-forms.text name="status" label="Membership Status" :value="$member->getMembershipState()" readonly/>

        <x-forms.text name="card_number" label="Membership Card" :value="$member->card_number"/>
        <x-forms.email name="email" label="Email Address" :value="$member->email" autocomplete="off"/>
        <x-forms.text name="emerg_contact" label="Emergency Contact" :value="$member->emerg_contact"/>
        <x-forms.text name="emerg_phone" label="Contact's Phone" :value="$member->emerg_phone"/>
        <x-forms.datepicker name="med_dec_date" label="Medical Declaration Date" :value="$member->med_dec_date"/>
    </div><!-- column -->
</div><!-- row -->
</form>
