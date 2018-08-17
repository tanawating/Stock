@extends('layouts.master_layout')

@section('style')
    <link href="{{ URL::asset('assets/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
@endsection

@section('content')


        <div class="col-md-10">
            <div class="content-box-large">
                <div class="panel-body">
                    <div>
                        <h3>Cut Stock</h3>
                    </div>
                    <table id="stock_table" class="table table-bordered">
                        <thead>
                          <tr bgcolor="#008fed">
                            <th style="width: 15%"><font color="#FFFFFF">วันที่รับเข้า</font></th>
                            <th style="width: 15%"><font color="#FFFFFF">เลขที่ใบรับ</font></th>
                            <th>                   <font color="#FFFFFF">ผลิตภัณฑ์</font></th>
                            <th>                   <font color="#FFFFFF">คลัง</font></th>
                            <th style="width: 15%"><font color="#FFFFFF">จำนวนที่นำเข้า</font></th>
                            <th style="width: 10%"><font color="#FFFFFF">คงเหลือ</font></th>
                            <th style="width: 27%"><font color="#FFFFFF"></font></th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalLabel"></h4>
                    </div>
                    <form id="form" class="form-horizontal" method="post" action="{!! URL::to('main/export/cut_stock') !!}">

                        {!! csrf_field() !!}

                        <input type="hidden" id="state" name="state" value="create">
                        <input type="hidden" id="id" name="id" value="0">

                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">ผลิตภัณฑ์</label>
                                <div class="col-sm-5">
                                    <input id="product" readonly type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">คลัง</label>
                                <div class="col-sm-5">
                                    <input id="treasury" readonly  type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">สินค้าที่นำเข้า</label>
                                <div class="col-sm-2">
                                    <input id="qty_start" readonly type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">สินค้าคงเหลือ</label>
                                <div class="col-sm-2">
                                    <input id="name" name="device_total" readonly type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">ส่งไปให้</label>
                                <div class="col-sm-5">
                                    <input type="text" name="sent_form" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">รายละเอียด</label>
                                <div class="col-sm-5">
                                    <textarea class="form-control" name="send_detail"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">นำสินค้าออก</label>
                                <div class="col-sm-5">
                                    <select class="form-control select_cut" name="select_cuts" onchange="select_cut(this)">
                                        <option value="">เลือก</option>
                                        <option value="Auto">Auto</option>
                                        <option value="Manual">Manual</option>
                                        <option value="Import_File">Import File</option>
                                    </select>
                                </div>
                            </div>
                            <!-- select auto -->
                            <div class="form-group form_select_auto hidden"></div>
                            <div class="form_get_device"></div>

                            <!-- select manual -->
                            <div class="form-group form_select_manual hidden"></div>
                            <div class="form_get_device_manual"></div>

                            <!-- select import file -->
                            <div class="form-group form_select_import_file hidden"></div>
                            <div class="form_get_import_file"></div>
                        </div>
                        <div class="modal-footer">
                            <button id="btn_form" type="button" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> บันทึก</button>
                            <button type="button" class="btn btn-warning" onclick="remove_app_all(this)" data-dismiss="modal"><i class="fa fa-undo" aria-hidden="true"></i> ยกเลิก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal_log" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
            <div class="modal-dialog">
            
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">ประวัติการตัดสต็อก</h4>
                </div>
                <div class="modal-body">
                    <table id="table_log" class="table table-bordered">
                        <thead>
                          <tr>
                            <th><center>ครั้งที่</center></th>
                            <th><center>จำนวนที่เอาออก</center></th>
                            <th><center>จัดส่งไปที่</center></th>
                            <th><center>เวลาตัดสต็อก</center></th>
                          </tr>
                        </thead>
                        <tbody id="app_result">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-warning" onclick="remove_app(this)" data-dismiss="modal"><i class="fa fa-undo" aria-hidden="true"></i> Close</button>
                </div>
              </div>
              
            </div>
        </div>

@endsection

@section('script')
<script src="{{ URL::asset('assets/js/jquery.form.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">

    var oTable = $('#stock_table').DataTable({
    sPaginationType: "full_numbers",
    sDom: '<"top"l>rt<"bottom"ip><"clear">',
    processing: true,
    serverSide: true,
    ajax: {
        url: '{!! URL::to("main/export/objectData") !!}',
        method: 'POST',
        data: function (d) {
            d._token = "{{ csrf_token() }}";
            d.select = $('.select').val();
        }
    },
    columns: [
        { data: 'time_stock',            name: 'time_stock'},
        { data: 'get_no',                name: 'get_no'},
        { data: 'name_type_product',     name: 'name_type_product'},
        { data: 'name_treasury',         name: 'name_treasury'  },
        { data: 'qty_start',             name: 'qty_start'  },
        { data: 'qty_total',             name: 'qty_total'  },
        { data: 'detail',                name: 'detail'  }

    ]
    });

    $('#btn_form').click(function(){
        $('#form').submit();
    });

    $('#form').submit(function() {  
        $(this).ajaxSubmit(
        {
            success:function(response){
                if (response['success'] == true)
                {  
                    // load_data_table();
                    $('#modal').modal('hide')
                    swal(
                          'สำเร็จ!',
                          'ตัดสต็อกเรียบร้อย!',
                          'success'
                        )
                    
                    oTable.draw();
                    remove_app_all();

                }
                else if (response['chk_qty'] == true)
                {    
                    swal({
                         title: "จำนวนไม่เท่ากัน",
                         type: "warning"
                    });
                }
                else if (response['chk_sn'] == true)
                {    
                    swal({
                         title: "Serial Number ไม่มีในระบบ",
                         type: "warning"
                    });
                }                     
            }

        }); 
        
        return false; 

    });

    function editData(element,id)
    {
        $.ajax({
            url: "{!! URL::to('/main/export/detail') !!}/"+id, 
            success: function(response)
            {
                $('#id').val(response.main_stock.main_stock_id);
                $('#name').val(response.main_stock.qty_total);
                $('#product').val(response.main_stock.name_type_product);
                $('#treasury').val(response.main_stock.name_treasury);
                $('#qty_start').val(response.main_stock.qty_start);
                $('#state').val('update');
                $('#modalLabel').text('ตัดสต็อก');
                $('#modal').modal('show');

                var stock_id = id
                var device_cut = response.main_device

                //auto
                var result_select = '<label class="col-sm-4 control-label">จำนวนนำออก (Auto)</label>'+
                                    '<div class="col-sm-5">'+
                                        '<select class="form-control select_auto" name="qty_device_cut_auto" onchange="select_auto('+stock_id+')">'+
                                            '<option value="0">เลือก</option>'+
                                        '</select>'+
                                    '</div>';
                $('.form_select_auto').append(result_select);                

                for (var i = 1; i <= device_cut; i++) 
                {
                    var result_option = '<option value="'+i+'">'+i+'</option>'
                    $('.select_auto').append(result_option);
                }

                // manual
                var result_select_manual = '<label class="col-sm-4 control-label">จำนวนนำออก (Manual)</label>'+
                                            '<div class="col-sm-5">'+
                                                '<select class="form-control select_manual" name="qty_device_cut_manual" onchange="select_manual('+stock_id+')">'+
                                                    '<option value="0">เลือก</option>'+
                                                '</select>'+
                                            '</div>';
                $('.form_select_manual').append(result_select_manual);                

                for (var i = 1; i <= device_cut; i++) 
                {
                    var result_option_manual = '<option value="'+i+'">'+i+'</option>'
                    $('.select_manual').append(result_option_manual);
                }

                // import file
                var result_select_import_file = '<label class="col-sm-4 control-label">จำนวนนำออก (Import File)</label>'+
                                                '<div class="col-sm-5">'+
                                                    '<select class="form-control select_import_file" name="qty_device_cut_import_file" onchange="select_import_file('+stock_id+')">'+
                                                        '<option value="0">เลือก</option>'+
                                                    '</select>'+
                                                '</div>';
                $('.form_select_import_file').append(result_select_import_file);  

                for (var i = 1; i <= device_cut; i++) 
                {
                    var result_option_import_file = '<option value="'+i+'">'+i+'</option>'
                    $('.select_import_file').append(result_option_import_file);
                }             

            }
        });
    }

    function select_auto(id)
    {
        $('.input_get_manual').remove();
        $('.input_get_file').remove();
        $('.form_get_device').empty();
        $('.form_get_import_file').empty();
        $.ajax({
            url: "{!! URL::to('/main/export/get_device') !!}/"+id, 
            method: 'get',
            data: {num : $('.select_auto').val()},
            success: function(response)
            {
                
                $.each(response.main_device, function(i, main_device) 
                    {

                        var result_get_device = '<div class="form-group input_get_auto">'+
                                                    '<label class="col-sm-4 control-label"></label>'+
                                                    '<div class="col-sm-5">'+
                                                        '<input class="form-control" readonly value="'+main_device.serial_number+'" type="" name="input['+i+'][serial_number]">'+
                                                    '</div>'+
                                                '</div>';
                                          
                        $(result_get_device).appendTo('.form_get_device');     
            });
            }
        });        
    }

    function select_manual(id)
    {
        $('.input_get_auto').remove();
        $('.input_get_file').remove();
        $('.form_get_device_manual').empty();
        $('.form_get_import_file').empty();
        $.ajax({
            url: "{!! URL::to('/main/export/get_device') !!}/"+id, 
            method: 'get',
            data: {num : $('.select_manual').val()},
            success: function(response)
            {
                
                $.each(response.main_device, function(i, main_device) 
                    {

                        var result_get_device_manual = '<div class="form-group input_get_manual">'+
                                                            '<label class="col-sm-4 control-label"></label>'+
                                                            '<div class="col-sm-5">'+
                                                                '<input class="form-control" value="" type="" name="input['+i+'][serial_number]">'+
                                                            '</div>'+
                                                        '</div>';
                                          
                        $(result_get_device_manual).appendTo('.form_get_device_manual');         
            });
            }
        });   
    }

    function select_import_file(id)
    {
        $('.input_get_auto').remove();
        $('.input_get_manual').remove();
        $('.form_get_device').empty();
        $('.form_get_device_manual').empty();

        var result_get_device_import_file = '<div class="form-group input_get_import_file">'+
                                            '<label class="col-sm-4 control-label"></label>'+
                                                '<div class="col-sm-5">'+
                                                    '<input class="form-control input_get_file" type="file" id="excel_file" name="excel_file">'
                                                '</div>'+
                                            '</div>';
                                          
        $(result_get_device_import_file).appendTo('.form_get_import_file');  
    }

    function select_cut(element)
    {
        var result = $('.select_cut').val()
        if (result == 'Auto')
        {
            $('.form_select_manual').addClass('hidden')
            $('.form_select_import_file').addClass('hidden')
            $('.form_select_auto').removeClass('hidden')
            $('.input_get_manual').remove();
            $('.input_get_import_file').remove();
            $('.select_manual').each(function() { this.selectedIndex = 0 });
            $('.select_import_file').each(function() { this.selectedIndex = 0 });
        }
        else if (result == 'Manual')
        {
            $('.form_select_auto').addClass('hidden')
            $('.form_select_import_file').addClass('hidden')
            $('.form_select_manual').removeClass('hidden')
            $('.input_get_auto').remove();
            $('.input_get_import_file').remove();
            $('.select_auto').each(function() { this.selectedIndex = 0 });
            $('.select_import_file').each(function() { this.selectedIndex = 0 });
        }
        else if(result == 'Import_File')
        {
            $('.form_select_auto').addClass('hidden')
            $('.form_select_manual').addClass('hidden')
            $('.form_select_import_file').removeClass('hidden')
            $('.input_get_auto').remove();
            $('.input_get_manual').remove();
            $('.select_auto').each(function() { this.selectedIndex = 0 });
            $('.select_manual').each(function() { this.selectedIndex = 0 });
        }
    }

    function chk_log(id)
    {
        $.ajax({
            url: "{!! URL::to('/main/export/stock_log') !!}/"+id, 
            success: function(response)
            {
                num = 1;
                $.each(response.main_stock_log, function(i,item) {
                    var result =    
                                    '<tr>'+
                                    '<td><center>'+ num +'</center></td>'+
                                    '<td><center>'+ item.sent_qty +'</center></td>'+
                                    '<td><center>'+ item.sent_form +'</center></td>'+
                                    '<td><center>'+ item.created_at +'</center></td>'+
                                    '</tr>';
                                               
                    $('#table_log').find('tbody').append(result);
                    $('#modal_log').modal('show'); 
                num++
                });
            }
        });
    }

    function remove_app(element)
    {
        $('#table_log').find('tbody').empty();
    }

    function remove_app_all(element)
    {

        $('.select_cut').each(function() { this.selectedIndex = 0 });

        $('.form_select_auto').empty();
        $('.form_select_auto').addClass('hidden');
        $('.select_auto').empty();
        $('.input_get_auto').remove();

        $('.form_select_manual').empty();
        $('.form_select_manual').addClass('hidden');
        $('.select_manual').empty();
        $('.input_get_manual').remove();

        $('.form_select_import_file').empty();
        $('.form_select_import_file').addClass('hidden');
        $('.select_import_file').empty();
        $('.input_get_import_file').remove();

    }

</script>

{!! JsValidator::formRequest('App\Http\Requests\RequestExport','#form') !!}

@endsection