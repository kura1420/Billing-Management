<form id="ff" method="post">
    <p style="display: none;">
        <input name="id" id="id" class="easyui-textbox" data-options="label:'ID',width:500,required:false,labelAlign:'right',max:36,readonly:true,">
    </p>
    
    <p>
        <input name="departement_id" id="departement_id" class="easyui-combobox"
            data-options="label:'Departement',width:800,required:true,labelAlign:'right',disabled:true,labelWidth:100">
    </p>
    <p>
        <input name="name" id="name" class="easyui-textbox" data-options="label:'Name',width:800,required:true,labelAlign:'right',max:255,disabled:true,labelWidth:100">
    </p>
    <p>
        <input name="email" id="email" class="easyui-textbox" data-options="label:'Email',width:800,required:true,labelAlign:'right',max:255,disabled:true,validType:'email',labelWidth:100">
    </p>
    <p>
        <input name="username" id="username" class="easyui-textbox" data-options="label:'Username',width:800,required:true,labelAlign:'right',max:100,disabled:true,labelWidth:100">
    </p>
    <p>
        <input name="password" id="password" class="easyui-passwordbox" data-options="label:'Password',width:800,required:false,labelAlign:'right',min:6,disabled:true,showEye:true,labelWidth:100">
    </p>
    <!-- <p>
        <input name="telp" id="telp" class="easyui-maskedbox" mask="(999) 9999-9999" data-options="label:'Telp',width:800,required:false,labelAlign:'right',max:20,min:0,disabled:true,labelWidth:100">
    </p>
    <p>
        <input name="handphone" id="handphone" class="easyui-maskedbox" mask="(9999) 9999-9999" data-options="label:'Handphone',width:800,required:true,labelAlign:'right',max:20,min:0,disabled:true,labelWidth:100">
    </p> -->
    <p>
        <input name="active" id="active" class="easyui-switchbutton" data-options="label:'Active',labelAlign:'right',disabled:true,labelWidth:100" value="1">
    </p>
</form>