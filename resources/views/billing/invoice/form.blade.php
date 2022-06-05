<form id="ff" method="post" enctype="multipart/form-data">
    <p style="display: none;">
        <input name="id" id="id" class="easyui-textbox" data-options="label:'ID',width:500,required:false,labelAlign:'right',max:36,disabled:true,">
    </p>

    <table width="100%" border="0">
        <tr>
            <td style="width: 50%;">
                <p>
                    <input name="billing_type_id" id="billing_type_id" class="easyui-textbox" data-options="label:'Billing Type',width:800,labelAlign:'right',readonly:true,labelWidth:150,">
                </p>
                <p>
                    <input name="code" id="code" class="easyui-textbox" data-options="label:'Billing Code',width:800,labelAlign:'right',readonly:true,labelWidth:150,">
                </p>
                <p>
                    <input name="status" id="status" class="easyui-textbox" data-options="label:'Status',width:800,labelAlign:'right',readonly:true,labelWidth:150,">
                </p>
                <p>
                    <input name="price_sub" id="price_sub" class="easyui-numberbox" data-options="label:'Sub.',width:800,labelAlign:'right',readonly:true,labelWidth:150,precision:2,decimalSeparator:'.',groupSeparator:',',">
                </p>
                <p>
                    <input name="price_ppn" id="price_ppn" class="easyui-numberbox" data-options="label:'PPN',width:800,labelAlign:'right',readonly:true,labelWidth:150,precision:2,decimalSeparator:'.',groupSeparator:',',">
                </p>
                <p>
                    <input name="price_discount" id="price_discount" class="easyui-numberbox" data-options="label:'Discount %',width:800,labelAlign:'right',readonly:true,labelWidth:150,precision:2,decimalSeparator:'.',groupSeparator:',',">
                </p>
                <p>
                    <input name="price_total" id="price_total" class="easyui-numberbox" data-options="label:'Total',width:800,labelAlign:'right',readonly:true,labelWidth:150,precision:2,decimalSeparator:'.',groupSeparator:',',">
                </p>
                <p>
                    <input name="payment_by" id="payment_by" class="easyui-textbox" data-options="label:'Payment By',width:800,labelAlign:'right',readonly:true,labelWidth:150,">
                </p>
                <p>
                    <input name="verif_by_user_id" id="verif_by_user_id" class="easyui-textbox" data-options="label:'Verif Payment By User',width:800,labelAlign:'right',readonly:true,labelWidth:150,">
                </p>
                <p>
                    <input name="verif_payment_at" id="verif_payment_at" class="easyui-textbox" data-options="label:'Verif Payment At',width:800,labelAlign:'right',readonly:true,labelWidth:150,">
                </p>
                <p>
                    <input name="notif_at" id="notif_at" class="easyui-textbox" data-options="label:'Invoice At',width:800,labelAlign:'right',readonly:true,labelWidth:150,">
                </p>
                <p>
                    <input name="file_payment" id="file_payment" class="easyui-filebox" data-options="label:'File Payment',width:800,labelAlign:'right',labelWidth:150,disabled:true,required:true,">
                </p>
                <p>
                    <a id="btnFileInvoice" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:false," style="margin-left:16%;">File Invoice</a>
                    <a id="btnFilePayment" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:false," style="margin-left:1%;">File Payment</a>
                </p>
            </td>
            
            <td width="50%" style="vertical-align: top;">
                <p>
                    <input name="product_type_name" id="product_type_name" class="easyui-textbox" data-options="label:'Product Type',width:800,labelAlign:'right',readonly:true,labelWidth:150,">
                </p>
                <p>
                    <input name="product_service_name" id="product_service_name" class="easyui-textbox" data-options="label:'Product Service',width:800,labelAlign:'right',readonly:true,labelWidth:150,">
                </p>
                <p>
                    <input name="customer_data_code" id="customer_data_code" class="easyui-textbox" data-options="label:'Customer Code',width:800,labelAlign:'right',readonly:true,labelWidth:150,">
                </p>
                <p>
                    <input name="name" id="name" class="easyui-textbox" data-options="label:'Name',width:800,labelAlign:'right',readonly:true,labelWidth:150,">
                </p>
                <p>
                    <input name="email" id="email" class="easyui-textbox" data-options="label:'Email',width:800,labelAlign:'right',readonly:true,labelWidth:150,">
                </p>
                <p>
                    <input name="telp" id="telp" class="easyui-textbox" data-options="label:'Telp',width:800,labelAlign:'right',readonly:true,labelWidth:150,">
                </p>
                <p>
                    <input name="handphone" id="handphone" class="easyui-textbox" data-options="label:'Handphone',width:800,labelAlign:'right',readonly:true,labelWidth:150,">
                </p>
                <p>
                    <input name="member_at" id="member_at" class="easyui-textbox" data-options="label:'Member At',width:800,labelAlign:'right',readonly:true,labelWidth:150,">
                </p>
                <p>
                    <input name="suspend_at" id="suspend_at" class="easyui-textbox" data-options="label:'Suspend At',width:800,labelAlign:'right',readonly:true,labelWidth:150,">
                </p>
                <p>
                    <input name="terminate_at" id="terminate_at" class="easyui-textbox" data-options="label:'Terminated At',width:800,labelAlign:'right',readonly:true,labelWidth:150,">
                </p>
            </td>
        </tr>
    </table>    
    
</form>