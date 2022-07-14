<form id="ff" method="post">
    <p style="display: none;">
        <input name="id" id="id" class="easyui-textbox" data-options="label:'ID',width:500,required:false,labelAlign:'right',max:36,disabled:true,">
    </p>
    
    <p>
        <input name="name" id="name" class="easyui-textbox" data-options="label:'Name',width:800,required:true,labelAlign:'right',max:255,disabled:true,">
    </p>
    <p>
        <input name="host" id="host" class="easyui-textbox" data-options="label:'Host',width:800,required:true,labelAlign:'right',max:100,disabled:true,">
    </p>
    <p>
        <input name="username" id="username" class="easyui-textbox" data-options="label:'Username',width:800,required:true,labelAlign:'right',max:100,disabled:true,">
    </p>
    <p>
        <input name="password" id="password" class="easyui-passwordbox" data-options="label:'Password',width:800,required:true,labelAlign:'right',max:100,disabled:true,showEye:true,">
    </p>
    <p>
        <input name="active" id="active" class="easyui-switchbutton" data-options="label:'Active',labelAlign:'right',disabled:true" value="1">
    </p>
</form>