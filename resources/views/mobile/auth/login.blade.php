@extends('layouts.default')

@section('addCss')
<link rel="stylesheet" href="{{ asset('assets/jquery-easyui/themes/mobile.css') }}">
@endsection

@section('addJs')
<script src="{{ asset('assets/jquery-easyui/jquery.easyui.mobile.js') }}"></script>
@endsection

@section('content')
<div class="easyui-navpanel">
    <header>
        <div class="m-toolbar">
            <span class="m-title">{{ config('app.name', 'Laravel') }}</span>
        </div>
    </header>
    <div style="margin: 20px auto; width: 100px; height: 100px; border-radius: 100px; overflow: hidden;">
        <img src="{{ asset('logo.jpg') }}" style="margin: 0; width: 100%; height: 100%;" />
    </div>
    <div style="padding: 0 20px;">
        <form id="ffLogin" method="post">
            <div style="margin-bottom: 10px;">
                <input class="easyui-textbox" name="username" id="username" style="width: 100%;height: 38px;" data-options="prompt:'Username:',required:true," />
            </div>
            <div>
                <input class="easyui-passwordbox" name="password" id="password" style="width: 100%;height: 38px;" data-options="prompt:'Password:',required:true,showEye:true," />
            </div>
            <div style="text-align: center; margin-top: 30px;">
                <a id="btnLogin" class="easyui-linkbutton c6" href="javascript:void(0)" style="width: 100%; height: 40px;">Login</a>
                <!-- <a id="btnForgot" class="easyui-linkbutton" href="javascript:void(0)" style="width: 30%; height: 40px; margin-left:5%;">Forgot</a> -->
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('assets/mobile/auth.js') }}"></script>
@endsection