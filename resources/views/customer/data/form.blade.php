<form id="ff" method="post" enctype="multipart/form-data">
    <p style="display: none;">
        <input name="id" id="id" class="easyui-textbox" data-options="label:'ID',width:500,required:false,labelAlign:'right',max:36,disabled:true,">
    </p>

    <table border="0" width="100%">
        <tr>
            <td width="50%" style="vertical-align: top;">
                <p>
                    <input name="code" id="code" class="easyui-textbox" data-options="label:'Code',width:800,labelAlign:'right',max:20,readonly:true,labelWidth:120,">
                </p>
                <p>
                    <input name="area_id" id="area_id" class="easyui-combobox" data-options="label:'Area',width:800,required:true,labelAlign:'right',disabled:true,labelWidth:120,">
                </p>    
                <p>
                    <input name="area_product_customer" id="area_product_customer" class="easyui-combogrid" data-options="label:'Product Customer',width:800,required:false,labelAlign:'right',disabled:true,labelWidth:120,">
                </p>
                <p>
                    <input name="member_at" id="member_at" class="easyui-datebox" data-options="label:'Member At',width:800,required:false,labelAlign:'right',disabled:true,labelWidth:120,">
                </p>
                <p>
                    <input name="suspend_at" id="suspend_at" class="easyui-datebox" data-options="label:'Suspend At',width:800,required:false,labelAlign:'right',readonly:true,labelWidth:120,">
                </p>
                <p>
                    <input name="terminate_at" id="terminate_at" class="easyui-datebox" data-options="label:'Terminate At',width:800,required:false,labelAlign:'right',readonly:true,labelWidth:120,">
                </p>
                <p>
                    <input name="dismantle_at" id="dismantle_at" class="easyui-datebox" data-options="label:'Dismantle At',width:800,required:false,labelAlign:'right',readonly:true,labelWidth:120,">
                </p>
                <p>
                    <input name="active" id="active" class="easyui-switchbutton" data-options="label:'Active',labelAlign:'right',disabled:true,labelWidth:120," value="1">
                </p>
            </td>

            <td>
                <p>
                    <input name="name" id="name" class="easyui-textbox" data-options="label:'Name',labelAlign:'right',disabled:true,labelWidth:120,width:800,required:true,">
                </p>
                <p>
                    <input name="gender" id="gender" class="easyui-combobox" data-options="label:'Gender',labelAlign:'right',disabled:true,labelWidth:120,width:800,required:false,">
                </p>
                <p>
                    <input name="email" id="email" class="easyui-textbox" data-options="label:'Email',labelAlign:'right',disabled:true,labelWidth:120,width:800,required:true,validType:'email',">
                </p>
                <p>
                    <input name="telp" id="telp" class="easyui-numberbox" data-options="label:'Telp',labelAlign:'right',disabled:true,labelWidth:120,width:800,required:false,">
                </p>
                <p>
                    <input name="handphone" id="handphone" class="easyui-numberbox" data-options="label:'Handphone',labelAlign:'right',disabled:true,labelWidth:120,width:800,required:true,">
                </p>
                <p>
                    <input name="fax" id="fax" class="easyui-textbox" data-options="label:'Fax',labelAlign:'right',disabled:true,labelWidth:120,width:800,required:false,">
                </p>
                <p>
                    <input name="address" id="address" class="easyui-textbox" data-options="label:'Address',labelAlign:'right',disabled:true,labelWidth:120,width:800,required:true,">
                </p>
                <p>
                    <input name="picture" id="picture" class="easyui-filebox" data-options="label:'Foto',labelAlign:'right',disabled:true,labelWidth:120,width:700,required:false,accept: 'image/*',">
                    <a id="btnPreview" class="easyui-linkbutton" href="javascript:void(0)">Preview</a>
                </p>
                <p>
                    <input name="birthdate" id="birthdate" class="easyui-datebox" data-options="label:'Birthdate',labelAlign:'right',disabled:true,labelWidth:120,width:800,required:false,">
                </p>
                <p>
                    <input name="marital_status" id="marital_status" class="easyui-combobox" data-options="label:'Marital Status',labelAlign:'right',disabled:true,labelWidth:120,width:800,required:false,">
                </p>
                <p>
                    <input name="work_type" id="work_type" class="easyui-textbox" data-options="label:'Work',labelAlign:'right',disabled:true,labelWidth:120,width:800,required:false,">
                </p>
                <p>
                    <input name="child" id="child" class="easyui-numberbox" data-options="label:'Child',labelAlign:'right',disabled:true,labelWidth:120,width:800,required:false,">
                </p>
            </td>
        </tr>
    </table>   
    
</form>

@include('customer.data.detail')