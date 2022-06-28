<form id="ffCustomer" method="post" enctype="multipart/form-data">
    <div style="margin-bottom: 10px;">
        <input name="provinsi_id" id="provinsi_id" class="easyui-combobox" prompt="Pilih Provinsi" style="width: 100%;" data-options="label:'Provinsi',required:true,">
    </div>
    <div style="margin-bottom: 10px;">
        <input name="city_id" id="city_id" class="easyui-combobox" prompt="Pilih Kota" style="width: 100%;" data-options="label:'Kota',required:true,valueField:'id',textField:'name',">
    </div>
    <div style="margin-bottom: 10px;">
        <input name="customer_segment_id" id="customer_segment_id" prompt="Pilih Segment Customer" class="easyui-combogrid" style="width: 100%;" data-options="label:'Segment',required:true,">
    </div>
    <div style="margin-bottom: 10px;">
        <input name="product_service_id" id="product_service_id" prompt="Pilih Layanan" class="easyui-combogrid" style="width: 100%;" data-options="label:'Layanan',required:true,">
    </div>

    <div style="display: none;">
        <div style="margin-bottom: 10px;">
            <input class="easyui-textbox" id="user_id" name="user_id" label="User:" prompt="User" style="width: 100%;" data-options="readonly:true," value="{{ $user_id }}" />
        </div>
        <div style="margin-bottom: 10px;">
            <input class="easyui-textbox" id="from" name="from" label="From:" prompt="From" style="width: 100%;" data-options="readonly:true," value="user" />
        </div>
        <div style="margin-bottom: 10px;">
            <input class="easyui-textbox" id="latitude" name="latitude" label="Lat:" prompt="Lat" style="width: 100%;" data-options="readonly:true," />
        </div>
        <div style="margin-bottom: 10px;">
            <input class="easyui-textbox" id="longitude" name="longitude" label="Log:" prompt="Log" style="width: 100%;" data-options="readonly:true," />
        </div>
    </div>

    <div style="margin-bottom: 10px;">
        <input class="easyui-textbox" id="fullname" name="fullname" label="Nama:" prompt="Nama Lengkap" style="width: 100%;" data-options="required:true,max:255," />
    </div>
    <div style="margin-bottom: 10px;">
        <input class="easyui-textbox" id="email" name="email" label="Email:" prompt="Email" style="width: 100%;" data-options="required:true,validType:'email'," />
    </div>
    <div style="margin-bottom: 10px;">
        <input class="easyui-numberbox" id="handphone" name="handphone" label="No. HP:" prompt="Nomor Handphone" style="width: 100%;" data-options="required:true," />
    </div>
    <div style="margin-bottom: 10px;">
        <b>Identitas:</b> <input type="file" id="file" name="file" style="padding-left: 10px;" (change)="getFile($event)" />
    </div>
    <div style="margin-bottom: 10px;">
        <input class="easyui-textbox" id="address" name="address" label="Alamat:" prompt="Alamat Lengkap" style="width: 100%;height: 100px;" data-options="required:true,max:255,multiline:true," />
    </div>
    <div style="margin-bottom: 10px;">
        <b>Tanda Tangan Pelanggan:</b> <br>
        <canvas id="canvasSignaturePad" class="canvasSignaturePad" width="400" height="300"></canvas>
    </div>
    <div style="text-align: center; margin-top: 30px;">
        <a id="btnSubmitCustomer" class="easyui-linkbutton" href="javascript:void(0)" style="width: 30%; height: 40px;">Daftarkan</a>
        <a id="btnResetCustomer" class="easyui-linkbutton c5" href="javascript:void(0)" style="width: 30%; height: 40px; margin-left:5%;">Clear Form</a>
    </div>
</form>