<form id="ff" method="post" enctype="multipart/form-data">
    <p style="display: none;">
        <input name="id" id="id" class="easyui-textbox" data-options="label:'ID',width:500,required:false,labelAlign:'right',max:36,disabled:true,">
    </p>
    
    <p>
        <input name="code" id="code" class="easyui-textbox" data-options="label:'Code',width:720,required:true,labelAlign:'right',max:50,disabled:true,">
        <a id="btnQrcode" href="javascript:void(0)" class="easyui-linkbutton">QRCode</a>
    </p>
    <p>
        <input name="partner_id" id="partner_id" class="easyui-combobox" data-options="label:'Partner',width:800,required:true,labelAlign:'right',disabled:true,">
    </p>
    <p>
        <input name="unit_id" id="unit_id" class="easyui-combobox" data-options="label:'Unit',width:800,required:true,labelAlign:'right',disabled:true,">
    </p>
    <p>
        <input name="name" id="name" class="easyui-textbox" data-options="label:'Name',width:800,required:true,labelAlign:'right',max:255,disabled:true,">
    </p>
    <p>
        <input name="serial_numbers" id="serial_numbers" class="easyui-textbox" data-options="label:'SN',width:800,required:false,labelAlign:'right',max:255,disabled:true,">
    </p>
    <p>
        <input name="brand" id="brand" class="easyui-combo" data-options="label:'Brand',width:800,required:false,labelAlign:'right',disabled:true,">
    </p>
    <p>
        <input name="price" id="price" class="easyui-numberbox" data-options="label:'Price',width:800,required:true,labelAlign:'right',disabled:true,precision:2,decimalSeparator:'.',groupSeparator:',',">
    </p>
    <p>
        <input name="year" id="year" class="easyui-numberbox" data-options="label:'Year',width:800,required:false,labelAlign:'right',disabled:true,">
    </p>
    <p>
        <input name="picture" id="picture" class="easyui-filebox" data-options="label:'Picture',width:720,required:false,labelAlign:'right',disabled:true,">
        <a id="btnPicture" href="javascript:void(0)" class="easyui-linkbutton">Preview</a>
    </p>
    <p>
        <input name="spec" id="spec" class="easyui-textbox" data-options="label:'Spesifikasi',width:800,required:true,labelAlign:'right',max:255,disabled:true,multiline:true," style="height: 180px;">
    </p>
    <p>
        <input name="desc" id="desc" class="easyui-textbox" data-options="label:'Desckripsi',width:800,required:false,labelAlign:'right',max:255,disabled:true,multiline:true," style="height: 180px;">
    </p>
</form>