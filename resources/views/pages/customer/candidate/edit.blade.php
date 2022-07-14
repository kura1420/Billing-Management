<form id="ff" method="post">
    <p style="display: none;">
        <input name="id" id="id" class="easyui-textbox" data-options="label:'ID',width:500,required:false,labelAlign:'right',max:36,disabled:true,">
    </p>

    <table>
        <tr>
            <td width="50%" style="vertical-align: top;">
                <p>
                    <input name="provinsi_id" id="provinsi_id" class="easyui-textbox" data-options="label:'Provinsi',width:800,readonly:true,labelAlign:'right',">
                </p>
                <p>
                    <input name="city_id" id="city_id" class="easyui-textbox" data-options="label:'City',width:800,readonly:true,labelAlign:'right',">
                </p>
                <p>
                    <input name="product_service_id" id="product_service_id" class="easyui-textbox" data-options="label:'Product Service',width:800,readonly:true,labelAlign:'right',labelWidth:120,">
                </p>
                <p>
                    <input name="customer_segment_id" id="customer_segment_id" class="easyui-textbox" data-options="label:'Segment',width:800,readonly:true,labelAlign:'right',">
                </p>
                <p>
                    <input name="from" id="from" class="easyui-textbox" data-options="label:'Register By',width:800,readonly:true,labelAlign:'right',">
                </p>
                <p>
                    <input name="user_id" id="user_id" class="easyui-textbox" data-options="label:'User',width:800,readonly:true,labelAlign:'right',">
                </p>
            </td>
            <td>
                <p>
                    <input name="fullname" id="fullname" class="easyui-textbox" data-options="label:'Fullname',width:800,readonly:true,labelAlign:'right',">
                </p>
                <p>
                    <input name="email" id="email" class="easyui-textbox" data-options="label:'Email',width:800,readonly:true,labelAlign:'right',">
                </p>
                <p>
                    <input name="handphone" id="handphone" class="easyui-textbox" data-options="label:'Handphone',width:800,readonly:true,labelAlign:'right',">
                </p>
                <p>
                    <input name="file_type" id="file_type" class="easyui-combobox" data-options="label:'File Identity Type',width:800,required:true,labelAlign:'right',labelWidth:140,">
                </p>
                <p>
                    <input name="file_number" id="file_number" class="easyui-textbox" data-options="label:'File Identity Number',width:800,required:true,labelAlign:'right',max:255,labelWidth:140,">
                </p>
                <p>
                    <input name="status" id="status" class="easyui-combobox" data-options="label:'Status',width:800,required:true,labelAlign:'right',">
                </p>
                <p>
                    <input name="address" id="address" class="easyui-textbox" data-options="label:'Address',width:800,readonly:true,labelAlign:'right',multiline:true," style="height: 100px;">
                </p>
                <p>
                    <a id="btnFile" href="javascript:void(0)" class="easyui-linkbutton">File Identity</a>
                    <a id="btnSignature" href="javascript:void(0)" style="margin-left: 10px;" class="easyui-linkbutton">Signature</a>
                </p>
            </td>
        </tr>
    </table>
</form>