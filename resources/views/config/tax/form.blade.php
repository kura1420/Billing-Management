<form id="ff" method="post">
    <p style="display: none;">
        <input name="id" id="id" class="easyui-textbox" data-options="label:'ID',width:500,required:false,labelAlign:'right',max:36,disabled:true,">
    </p>
    
    <p>
        <input name="name" id="name" class="easyui-textbox" data-options="label:'Name',width:800,required:true,labelAlign:'right',max:255,disabled:true,">
    </p>
    <p>
        <input name="value" id="value" class="easyui-numberbox" data-options="label:'Value',width:800,required:true,labelAlign:'right',max:100,disabled:true,min:0">
    </p>
    <p>
        <input name="desc" id="desc" class="easyui-textbox" data-options="label:'Desc',width:800,required:false,labelAlign:'right',disabled:true,multiline:true," style="height: 180px;">
    </p>
    <p>
        <input name="type" id="type" class="easyui-combobox" data-options="label:'Type',width:800,required:true,labelAlign:'right',disabled:true,">
    </p>
</form>