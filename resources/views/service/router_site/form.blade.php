<form id="ff" method="post">
    <p style="display: none;">
        <input name="id" id="id" class="easyui-textbox" data-options="label:'ID',width:500,required:false,labelAlign:'right',max:36,disabled:true,">
    </p>
    
    <p>
        <input name="site" id="site" class="easyui-textbox" data-options="label:'Site',width:800,required:true,labelAlign:'right',max:255,disabled:true,">
    </p>
    <p>
        <input name="host" id="host" class="easyui-textbox" data-options="label:'Host',width:800,required:true,labelAlign:'right',max:100,disabled:true,">
    </p>
    <p>
        <input name="port" id="port" class="easyui-numberbox" data-options="label:'Port',width:800,required:true,labelAlign:'right',disabled:true,min:0">
    </p>
    <p>
        <input name="user" id="user" class="easyui-textbox" data-options="label:'User',width:800,required:true,labelAlign:'right',max:100,disabled:true,">
    </p>
    <p>
        <input name="password" id="password" class="easyui-passwordbox" data-options="label:'Password',width:800,required:true,labelAlign:'right',max:100,disabled:true,showEye:true,">
    </p>
    <p>
        <input name="active" id="active" class="easyui-switchbutton" data-options="label:'Active',labelAlign:'right',disabled:true" value="1">
    </p>
    <p>
        <input name="command_trigger_list" id="command_trigger_list" class="easyui-textbox" data-options="label:'Command Trigger List',width:800,required:true,labelAlign:'right',max:255,disabled:true,labelWidth:200,">

        <a id="btnTriggerList" href="javascript:void(0)" class="easyui-linkbutton">Test List</a>
    </p>
    <p>
        <input name="command_trigger_comment" id="command_trigger_comment" class="easyui-textbox" data-options="label:'Command Trigger Comment',width:800,required:true,labelAlign:'right',max:255,disabled:true,labelWidth:200,">
        
        <a id="btnTriggerComment" href="javascript:void(0)" class="easyui-linkbutton">Test Comment</a>
    </p>
    <p>
        <input name="command_trigger_terminated" id="command_trigger_terminated" class="easyui-textbox" data-options="label:'Command Trigger Terminated',width:800,required:true,labelAlign:'right',max:255,disabled:true,labelWidth:200,">
        
        <a id="btnTriggerTerminated" href="javascript:void(0)" class="easyui-linkbutton">Test Terminated</a>
    </p>
    <p>
        <input name="desc" id="desc" class="easyui-textbox" data-options="label:'Desc',width:800,required:false,labelAlign:'right',disabled:true,multiline:true," style="height: 180px;">
    </p>
</form>