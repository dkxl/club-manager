@extends('layouts.master')

@section('title','Event Editor')
@section('body_id', 'events')

@section('banner')
    <div id="action-buttons" class="btn-group-sm pull-right">
        <button id="btnClose" type="button" class="btn btn-info" onclick="self.close()">Close</button>
    </div>
    <div id="task-buttons" class="btn-group-sm pull-right">
        <button id="btnSave" type="button" class="btn btn-success" >Save</button>
        <button id="btnNew" type="button" class="btn btn-info" >New</button>
        <button id="btnEdit" type="button" class="btn btn-primary" >Edit</button>
        <button id="btnDel" type="button" class="btn btn-danger" >Delete</button>
        <button id="btnCanx" type="button" class="btn btn-warning" >Cancel</button>
    </div>

    <h1 id="page-title">@yield('title', 'Page Title') <small id="sub-title">@yield('subTitle')</small></h1>
@endsection

@section('scripts')
    <script type="text/javascript" src="/js/events.js"></script>
@endsection

@section('content')
    <div class="panel-body">
        <div id="status"></div>
        <div id="editor">@yield('editor')</div>
    </div>
@endsection
