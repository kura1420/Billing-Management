@extends('layouts.default')

@section('addCss')
<link rel="stylesheet" href="{{ asset('assets/jquery-easyui/themes/mobile.css') }}">
<style>
canvas {
    border: 1px solid #ccc;
    border-radius: 0.5rem;
    width: 100%;
    height: 100px;
}
</style>
@endsection

@section('addJs')
<script src="{{ asset('assets/jquery-easyui/jquery.easyui.mobile.js') }}"></script>
@endsection

@section('content')
<div class="easyui-navpanel">
    <header>
        <div class="m-toolbar">
            <div class="m-title">{{ config('app.name', 'Laravel') }}</div>
        </div>
    </header>

    <div class="easyui-tabs" data-options="fit:true,border:false,tabHeight:35,border:false,">
        <!-- <div title="Home" style="padding: 10px;">
            <img src="{{ asset('logo.jpg') }}" style="width: 100%; height: 25%; align-items: center;">
        </div> -->
        <div title="Area & Layanan" style="padding: 10px;">
            @include('mobile.pages.area_layanan')
        </div>
        <div title="Input Customer" style="padding: 10px;">
            @include('mobile.pages.form_customer')
        </div>
        <div title="Setting">
            @include('mobile.pages.profile')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="{{ asset('assets/mobile/app.js') }}"></script>
@endsection