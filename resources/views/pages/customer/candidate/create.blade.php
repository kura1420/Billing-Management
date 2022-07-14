<form id="ffCreate" method="post" enctype="multipart/form-data">

    <p>
        <input name="c_provinsi_id" id="c_provinsi_id" class="easyui-combobox" data-options="label:'Provinsi',width:800,required:true,labelAlign:'right',disabled:true,">
    </p>
    <p>
        <input name="c_city_id" id="c_city_id" class="easyui-combobox" data-options="label:'Kota',width:800,required:true,labelAlign:'right',disabled:true,valueField:'id',textField:'name',">
    </p>
    <p>
        <input name="c_customer_segment_id" id="c_customer_segment_id" class="easyui-combogrid" data-options="label:'Segment',width:800,required:true,labelAlign:'right',disabled:true,">
    </p>
    <p>
        <input name="c_product_service_id" id="c_product_service_id" class="easyui-combogrid" data-options="label:'Layanan',width:800,required:true,labelAlign:'right',disabled:true,">
    </p>
    <p>
        <input class="easyui-textbox" id="c_fullname" name="c_fullname" data-options="label:'Fullname',width:800,required:true,max:255,labelAlign:'right',disabled:true," />
    </p>
    <p>
        <input class="easyui-textbox" id="c_email" name="c_email" data-options="label:'Email',width:800,required:true,validType:'email',max:100,labelAlign:'right',disabled:true," />
    </p>
    <p>
        <input class="easyui-numberbox" id="c_handphone" name="c_handphone" data-options="label:'Handphone',width:800,required:true,labelAlign:'right',disabled:true," />
    </p>
    <p>
        <input name="c_file" id="c_file" class="easyui-filebox" data-options="label:'File',labelAlign:'right',disabled:true,width:800,required:true,">
    </p>
    <p>
        <input name="c_file_type" id="c_file_type" class="easyui-combobox" data-options="label:'File Identity Type',width:800,required:true,labelAlign:'right',labelWidth:140,disabled:true,">
    </p>
    <p>
        <input name="c_file_number" id="c_file_number" class="easyui-textbox" data-options="label:'File Identity Number',width:800,required:true,labelAlign:'right',max:255,labelWidth:140,disabled:true,">
    </p>
    <p>
        <input class="easyui-textbox" id="c_address" name="c_address" style="height: 100px;" data-options="label:'Address',width:800,required:true,max:255,multiline:true,labelAlign:'right',disabled:true," />
    </p>
    <p>
        <b>Update Location:</b>
        <a id="btnPositionUpdate" class="easyui-linkbutton" href="javascript:void(0)">Update</a>
        <a id="btnPositionManual" class="easyui-linkbutton" href="javascript:void(0)">Entry Manual</a>
    </p>
    <p>
        <input class="easyui-textbox" id="c_latitude" name="c_latitude" data-options="label:'Latitude',width:800,required:true,labelAlign:'right',readonly:true," />
    </p>
    <p>
        <input class="easyui-textbox" id="c_longitude" name="c_longitude" data-options="label:'Longitude',width:800,required:true,labelAlign:'right',readonly:true," />
    </p>
</form>