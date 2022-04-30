<form id="ff" method="post">
    <p style="display: none;">
        <input name="id" id="id" class="easyui-textbox" data-options="label:'ID',width:500,required:false,labelAlign:'right',max:36,disabled:true,">
    </p>
    
    <p>
        <input name="name" id="name" class="easyui-textbox" data-options="label:'Name',width:800,required:true,labelAlign:'right',max:255,disabled:true,">
        <a id="btnPreview" href="javascript:void(0)" class="easyui-linkbutton">Preview</a>
    </p>
    <p>
        <input name="sender" id="sender" class="easyui-combobox" data-options="label:'Sender',width:800,required:true,labelAlign:'right',disabled:true,">
    </p>
    <p>
        <input name="type" id="type" class="easyui-combobox" data-options="label:'Type',width:800,required:true,labelAlign:'right',disabled:true,">
    </p>

    <p>
        <table width="100%">
            <tr>
                <td colspan="2" style="padding-left: 20px;"><b>Variable:</b></td>
            </tr>
            <tr>
                <td>
                    <ul>
                        <li> _invoice_code_ </li>
                        <li> _invoice_date_ </li>
                        <li> _invoice_due_ </li>
                    </ul>
                </td>
                <td>
                    <ul>
                        <li> _product_service_ </li>
                        <li> _customer_code_ </li>
                        <li> _customer_name_ </li>
                    </ul>
                </td>
                <td>
                    <ul>
                        <li> _price_sub_ </li>
                        <li> _price_ppn_ </li>
                        <li> _price_total_ </li>
                    </ul>
                </td>
            </tr>
        </table>
    </p>

    <div id="content" style="padding:20px;"></div>
</form>