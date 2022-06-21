<form id="ff" method="post">
    <p style="display: none;">
        <input name="id" id="id" class="easyui-textbox" data-options="label:'ID',width:500,required:false,labelAlign:'right',max:36,readonly:true,">
    </p>
    
    <p>
        <input name="text" id="text" class="easyui-textbox" data-options="label:'Text',width:800,required:true,labelAlign:'right',max:255,disabled:true,">
    </p>
    <p>
        <input name="title" id="title" class="easyui-textbox" data-options="label:'Title',width:800,required:false,labelAlign:'right',disabled:true,">
    </p>
    <p>
        <input name="url" id="url" class="easyui-textbox" data-options="label:'URL',width:800,required:false,labelAlign:'right',max:255,disabled:true,">
    </p>
    <p>
        <input name="parent" id="parent" class="easyui-combotree" data-options="label:'Parent',width:800,required:false,labelAlign:'right',disabled:true,">
    </p>
    <p>
        <input name="active" id="active" class="easyui-switchbutton" data-options="label:'Active',labelAlign:'right',disabled:true" value="1">
    </p>
</form>