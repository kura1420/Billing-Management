<div data-options="region:'center',border:false," style="padding: 10px;">
    <form id="ffLogin" method="post">
        <div style="margin-bottom: 20px;">
            <input class="easyui-textbox" name="email" id="email" style="width: 100%;" data-options="label:'Email:',required:true,validType:'email'" />
        </div>
        <div style="margin-bottom: 20px;">
            <input class="easyui-passwordbox" name="password" id="password" style="width: 100%;" data-options="label:'Password:',required:true,showEye:true," />
        </div>
    </form>
</div>
<div data-options="region:'south',border:false" style="text-align: right; padding: 5px 0 0;">
    <a id="btnLogin" class="easyui-linkbutton" href="javascript:void(0)" style="width: 80px;">Login</a>
    <a id="btnForgot" class="easyui-linkbutton" href="javascript:void(0)" style="width: 80px;">Forgot</a>
</div>