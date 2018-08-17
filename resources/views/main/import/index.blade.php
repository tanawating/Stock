@extends('layouts.master_layout')

@section('content')


        <div class="col-md-10">
            <div class="content-box-large">
                <div class="panel-body">
                    <div>
                        <h3>Add Stock</h3>
                        <hr>
                    </div>
                    <div class="col-md-12">
                        <form class="form-horizontal" enctype="multipart/form-data" method="post" id="form_add" action="{{ URL::to('main/import/add_stock') }}">
                        {!! csrf_field() !!}
                        <div class="panel panel-default">
                        <div class="panel-body">
                            <br>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-4 control-label"> วันที่รับ </label>
                                <div class="col-sm-7">
                                    <input type="input" readonly="" class="form-control" value="{{ date('Y-m-d') }}" name="get_time">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-4 control-label"> เลขที่ใบรับ </label>
                                <div class="col-sm-7">
                                    <input type="input" class="form-control" name="get_no">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-4 control-label"> ผลิตภัณฑ์ </label>
                                <div class="col-sm-7">
                                    <select class="form-control" name="product">
                                            <option value=""> เลือก </option>
                                        @foreach($data_type_product as $result)
                                            <option value="{{ $result->id }}"> {{ $result->name_type_product }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-4 control-label"> คลัง </label>
                                <div class="col-sm-7">
                                    <select class="form-control" name="treasury">
                                            <option value=""> เลือก </option>
                                        @foreach($data_treasury as $result)
                                            <option value="{{ $result->id }}"> {{ $result->name_treasury }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-4 control-label"> ชื่อผู้รับ </label>
                                <div class="col-sm-7">
                                    <input type="input" class="form-control" name="get_name_form">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-4 control-label"> รับจาก </label>
                                <div class="col-sm-7">
                                    <input type="input" class="form-control" name="get_form">
                                </div>
                            </div>
                            
                            <div class="form-group col-sm-6">
                                <label class="col-sm-4 control-label"> หมายเหตุ </label>
                                <div class="col-sm-7">
                                    <textarea name="detail" rows="4" class="form-control"></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label class="col-sm-4 control-label"> จำนวน </label>
                                <div class="col-sm-7">
                                    <input type="input" class="form-control" onkeypress = "return isNumber(event)" id="qty_number" name="qty">
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-sm-4 control-label"> Serial number </label>
                                <div class="col-sm-7">
                                    <select class="form-control" id="type_change" name="change_serial_numbers" onchange="change_serial_number(this)">
                                            <option value=""> เลือก </option>
                                            <option value="Auto"> Run Auto </option>
                                            <option value="Import_File"> Import File </option>
                                            <option value="Manual"> Manual </option>
                                    </select>
                                </div>
                            </div>
                            <div class="result_app">
                            </div>
                        </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-5">
                                <button type="button" id="submit_add" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> บันทึก</button>
                            </div>
                        </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>


@endsection

@section('script')

<script src="{{ URL::asset('assets/js/jquery.form.min.js') }}"></script>
<script type="text/javascript">

    $("#submit_add").click(function(){
        // if($('#qty_number').val()=='')
        // {
        //     swal(
        //         'Error!',
        //         'กรุณาระบุจำนวน',
        //         'warning'
        //         )
        // }
        $('#form_add').submit();
    })

    $('#form_add').submit(function() {  
        $(this).ajaxSubmit({
            success:function(response){
                if (response['success'] == true)
                {  
                    swal(
                          'สำเร็จ!',
                          'เพิ่มข้อมูลเรียบร้อย!',
                          'success'
                        )
                    setTimeout(function()
                    {
                       window.location.reload(1);
                    }, 1000);

                }
                if (response['success_excel'] == true)
                {  
                    swal(
                          'สำเร็จ!',
                          'GG Excel!',
                          'success'
                        )
                    setTimeout(function()
                    {
                       window.location.reload(1);
                    }, 1000);

                }   
                if (response['excel_none'] == true)
                {
                    swal(
                        'Error!',
                        'ไม่มี Column serial_number',
                        'warning'
                        )
                }
                if (response['excel_null'] == true)
                {
                    swal(
                        'Error!',
                        'Column serial_number ผิดหรือมีค่าว่าง',
                        'warning'
                        )
                }                
            }

        }); 
        
        return false; 

    });
   
    function change_serial_number(element) 
    {
        $('#qty_number').prop('readonly', false);
        result = $('#type_change').val();
        qty = $('#qty_number').val();

        if(result == 'Auto')
        {
            $('.app_import').remove();
            $('.app_manual').remove();
            $('.result_app').append('<div class="form-group app_auto">'+
                                        '<label class="col-sm-3 control-label"></label>'+
                                        '<div class="col-sm-3">'+
                                        '</div>'+
                                        '<label class="col-sm-1 control-label">Prefix</label>'+
                                        '<div class="col-sm-3">'+
                                            '<select class="form-control" id="prefix" onchange="prefix_start_serial_number(this)">'+
                                                '<option value=""> ไม่มี </option>'+
                                                '<option value="SN">SN</option>'+
                                             '</select>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="form-group app_auto">'+
                                        '<label class="col-sm-2 control-label">เริ่มต้น</label>'+
                                        '<div class="col-sm-3">'+
                                            '<input type="input" class="form-control start_serial_number" id="start_serial_number">'+
                                        '</div>'+
                                        '<div class="col-sm-1">'+
                                        '<button class="btn btn-success" type="button" onclick="run_serial_number(this)" >Run</button>'+
                                        '</div>'+
                                        '<label class="col-sm-1 control-label">สิ้นสุด</label>'+
                                        '<div class="col-sm-3">'+
                                            '<input type="input" class="form-control end_serial_number" id="end_serial_number" name="serial_number">'+
                                        '</div>'+
                                    '</div>')
        }
        else if (result == 'Import_File')
        {
            $('#qty_number').val('');
            $('#qty_number').prop('readonly', true);
            $('.app_auto').remove();
            $('.app_manual').remove();
            $('.result_app').append('<div class="form-group app_import">'+
                                        '<label class="col-sm-2 control-label"></label>'+
                                            '<div class="col-sm-3">'+
                                            '</div>'+
                                        '<label class="col-sm-2 control-label"></label>'+
                                            '<div class="col-sm-3">'+
                                                '<input class="form-control" type="file" id="excel_file" name="excel_file">'+
                                            '</div>'+
                                    '</div>')
        }
        else if (result == 'Manual')
        {
            $('#qty_number').prop('readonly', false);
            $('.app_auto').remove();
            $('.app_import').remove();
            for (var i=0; i<qty; i++) 
            {
              $('.result_app').append(' <div class="form-group app_manual">'+
                                        '<label class="col-sm-2 control-label"></label>'+
                                            '<div class="col-sm-3">'+
                                            '</div>'+
                                        '<label class="col-sm-2 control-label"></label>'+
                                        '<div class="col-sm-3">'+
                                            '<input type="input" class="form-control" name="input['+i+'][serial_number]">'+
                                        '</div>'+
                                    '</div>')
            }
        }
        else
        {
            $('#qty_number').prop('readonly', false);
            $('.app_manual').remove();
            $('.app_auto').remove();
            $('.app_import').remove();
        }
    }

    function prefix_start_serial_number(element)
    {
        prefix = $('#prefix').val()
        $('#start_serial_number').val(prefix)
    }

    function run_serial_number(element)
    {
        qty = $('#qty_number').val();

        var num_start = $('#start_serial_number').val()
        cut_num_start = num_start.slice(0,-1)

        for (var i=1; i<=qty; i++)
        {
          sum = cut_num_start + i
          $('#end_serial_number').val(sum)

          $('.result_app').append(' <div class="form-group app_auto hidden">'+
                                        '<label class="col-sm-2 control-label"></label>'+
                                        '<div class="col-sm-3">'+
                                        '</div>'+
                                        '<label class="col-sm-2 control-label"></label>'+
                                        '<div class="col-sm-3">'+
                                            '<input type="input" class="form-control" value="'+ sum +'" name="input['+i+'][serial_number]">'+
                                        '</div>'+
                                    '</div>')
        }

    }

    function isNumber(evt) 
    {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

</script>

{!! JsValidator::formRequest('App\Http\Requests\RequestImport') !!}
@endsection

