@extends('layouts.master')

@section('title','Profile')
@section('body_id', 'profile')

@section('banner')
    <div id="action-buttons" class="btn-group-sm pull-right">
        <button id="btnBack" type="button" class="btn btn-success" onclick="window.history.back()">Back</button>
        <button id="btnClose" type="button" class="btn btn-info" onclick="self.close()">Close</button>
    </div>
    <h1 id="page-title">Profile</h1>
@endsection


@section('content')
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                @include('profile.update-information')
            </div><!-- column -->

            <div class="col-md-6">
                @include('profile.update-password')
            </div><!-- column -->

        </div><!-- row -->
    </div>
@endsection
