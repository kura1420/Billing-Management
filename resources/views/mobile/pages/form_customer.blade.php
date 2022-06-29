<form id="ffCustomer" method="post" enctype="multipart/form-data">

    <div style="display: none;">
        <div style="margin-bottom: 10px;">
            <input class="easyui-textbox" id="user_id" name="user_id" label="User:" prompt="User" style="width: 100%;" data-options="readonly:true," value="{{ $user_id }}" />
        </div>
        <div style="margin-bottom: 10px;">
            <input class="easyui-textbox" id="from" name="from" label="From:" prompt="From" style="width: 100%;" data-options="readonly:true," value="user" />
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
        <input class="easyui-textbox" id="address" name="address" label="Alamat:" prompt="Alamat Lengkap" style="width: 100%;height: 70px;" data-options="required:true,max:255,multiline:true," />
    </div>
    <div style="margin-bottom: 10px;">
        <b>Update Location:</b>
        <a id="btnPositionUpdate" class="easyui-linkbutton" href="javascript:void(0)">Update</a>
        <a id="btnPositionManual" class="easyui-linkbutton" href="javascript:void(0)">Entry Manual</a>
    </div>
    <div style="margin-bottom: 10px;">
        <input class="easyui-textbox" id="latitude" name="latitude" prompt="Latitude" style="width: 100%;" data-options="readonly:true," />
    </div>
    <div style="margin-bottom: 10px;">
        <input class="easyui-textbox" id="longitude" name="longitude" prompt="Longitude" style="width: 100%;" data-options="readonly:true," />
    </div>

    <div style="margin-bottom: 10px;">
        <b>Tanda Tangan Pelanggan:</b> 
        <a id="btnSignatureClear" class="easyui-linkbutton" href="javascript:void(0)">Clear TTD</a>
        <br> <br>
        <canvas id="canvasSignaturePad" class="canvasSignaturePad" width="400" height="400" style="border: 1px solid #000;"></canvas>
    </div>
    <div style="text-align: center; margin-top: 30px;">
        <a id="btnSubmitCustomer" class="easyui-linkbutton" href="javascript:void(0)" style="width: 30%; height: 40px;">Daftarkan</a>
        <a id="btnResetCustomer" class="easyui-linkbutton c5" href="javascript:void(0)" style="width: 30%; height: 40px; margin-left:5%;">Clear Form</a>
    </div>
</form>