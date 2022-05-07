<form id="ff" method="post" enctype="multipart/form-data">
    <p style="display: none;">
        <input name="id" id="id" class="easyui-textbox" data-options="label:'ID',width:500,required:false,labelAlign:'right',max:36,readonly:true,">
    </p>
    
    <p>
        <input name="name" id="name" class="easyui-textbox" data-options="label:'Name',width:800,required:true,labelAlign:'right',max:255,">
    </p>
    <p>
        <input name="shortname" id="shortname" class="easyui-textbox" data-options="label:'Shortname',width:800,required:true,labelAlign:'right',max:50,">
    </p>
    <p>
        <input name="telp" id="telp" class="easyui-maskedbox" mask="(999) 9999-9999" data-options="label:'Telp',width:800,required:true,labelAlign:'right',max:20,min:0,">
    </p>
    <p>
        <input name="email" id="email" class="easyui-textbox" data-options="label:'Email',width:800,required:true,labelAlign:'right',max:100,validType:'email',">
    </p>
    <p>
        <input name="fax" id="fax" class="easyui-numberbox" data-options="label:'Fax',width:800,required:false,labelAlign:'right',">
    </p>
    <p>
        <input name="website" id="website" class="easyui-textbox" data-options="label:'Website',width:800,required:false,labelAlign:'right',max:100,">
    </p>
    <p>
        <input name="address" id="address" class="easyui-textbox" data-options="label:'Address',width:800,required:false,labelAlign:'right',multiline:true," style="height: 180px;">
    </p>
    <p>
        <input name="logo" id="logo" accept="image/*" class="easyui-filebox" data-options="label:'Logo:',width:400,labelAlign:'right',">
        <a id="btnPreview" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true">Preview</a>
    </p>
    <p>
        <input name="secret" id="secret" class="easyui-textbox" data-options="label:'API Secret',width:400,required:false,labelAlign:'right',max:100,readonly:true,">
        <a id="btnResetSecret" href="javascript:void(0)" class="easyui-linkbutton">Reset Secret</a>
    </p>
</form>