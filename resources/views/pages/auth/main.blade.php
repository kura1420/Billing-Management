<div id="wAuth" class="easyui-window" title="Login" data-options="modal:true,closed:false,collapsible:false,minimizable:false,maximizable:false,closable:false,closed:false,draggable:false,resizable:false," style="width:500px;height:250px;padding:10px;">
    <div id="layoutLogin" class="easyui-layout" data-options="fit:true,border:false,">
        @include('pages.auth.login')
    </div>

    <div id="layoutForgot" class="easyui-layout" data-options="fit:true,border:false,">
        @include('pages.auth.forgot')
    </div>
</div>

<script src="{{ asset('assets/pages/auth.js') }}"></script>