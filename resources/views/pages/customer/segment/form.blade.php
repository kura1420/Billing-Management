<form id="ff" method="post">
    <p style="display: none;">
        <input name="id" id="id" class="easyui-textbox" data-options="label:'ID',width:500,required:false,labelAlign:'right',max:36,disabled:true,">
    </p>
    
    <p>
        <input name="customer_type_id" id="customer_type_id" class="easyui-combobox" data-options="label:'Type',width:800,required:true,labelAlign:'right',disabled:true,">
    </p>
    <p>
        <input name="code" id="code" class="easyui-textbox" data-options="label:'Code',width:800,required:true,labelAlign:'right',max:20,readonly:true,">
    </p>
    <p>
        <input name="name" id="name" class="easyui-textbox" data-options="label:'Name',width:800,required:true,labelAlign:'right',max:255,disabled:true,">
    </p>
    <p>
        <input name="desc" id="desc" class="easyui-textbox" data-options="label:'Desc',width:800,required:false,labelAlign:'right',disabled:true,multiline:true," style="height: 180px;">
    </p>
    <p>
        <input name="custom_price" id="custom_price" class="easyui-switchbutton" data-options="label:'Custom Price',labelWidth:100,labelAlign:'right',disabled:true" value="1">
    </p>
    <p>
        <input name="active" id="active" class="easyui-switchbutton" data-options="label:'Active',labelWidth:100,labelAlign:'right',disabled:true" value="1">
    </p>
</form>