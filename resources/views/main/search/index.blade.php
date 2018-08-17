@extends('layouts.master_layout')

@section('style')
    <link href="{{ URL::asset('assets/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
@endsection

@section('content')


        <div class="col-md-10">
            <div class="content-box-large">
                <div class="panel-body">

                    <h3>Search Product</h3>
                    <hr>
                    <div class="col-md-12">
                        <form class="form-horizontal" id="form_search">
                            <div class="form-group col-sm-12 ">
                                <label class="col-sm-4 control-label"> เลขที่ใบรับ </label>
                                <div class="col-sm-3">
                                    <input type="input" class="form-control get_no" name="get_no">
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> ค้นหา</button>
                                </div>
                            </div>
                        </form>

                    <table id="table_search" class="table table-bordered">
                        <thead>
                          <tr bgcolor="#008fed">
                            <th style="width: 20%"><font color="#FFFFFF">วันที่รับสินค้า</font></th>
                            <th>                   <font color="#FFFFFF">เลขที่ใบรับ</font></th>
                            <th style="width: 20%"><font color="#FFFFFF">ผลิตภัณฑ์</font></th>
                            <th style="width: 10%"></th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="modal fade" id="modal_detail" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
            <div class="modal-dialog">
            
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title"><p id="from_get_no"></p></h4>
                </div>
                <div class="modal-body">
                    <table id="table_detail" class="table table-bordered">
                        <thead>
                          <tr bgcolor="#F5F5F5">
                            <th><center>เครื่อง</center></th>
                            <th><center>Serial Number</center></th>
                            <th><center>สถานะ</center></th>
                            <th><center>จัดส่งไปที่</center></th>
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

    <script src="{{ URL::asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">

    $('#form_search').on('submit', function(e) {
            oTable.draw();
            e.preventDefault();
    });

    var oTable = $('#table_search').DataTable({
    sPaginationType: "full_numbers",
    sDom: '<"top"l>rt<"bottom"ip><"clear">',
    processing: true,
    serverSide: true,
    ajax: {
        url: '{!! URL::to("main/search/objectData") !!}',
        method: 'POST',
        data: function (d) {
            d._token = "{{ csrf_token() }}";
            d.get_no = $('.get_no').val();
        }
    },
    columns: [
        { data: 'created_at',           name: 'created_at'},
        { data: 'get_no',               name: 'get_no'},
        { data: 'name_type_product',    name: 'name_type_product'  },
        { data: 'detail',               name: 'detail'  },

    ],
        });
        
    function detail(id)
    {

        $.ajax({
            url: "{!! URL::to('/main/search/detail') !!}/"+id, 
            success: function(response)
            {
                num = 1;
                $.each(response.detail, function(i,item) 

                {
                    if(item.is_status==true)
                    {
                        $status = '<font color="#3300FF">อยู่ในสต็อก</font>'
                    }
                    else
                    {
                        $status = '<font color="#FF6600">ตัดสต็อก</font>'
                    }

                    if(item.send_form==null)
                    {
                        $send_form = '-'
                    }
                    else
                    {
                        $send_form = item.send_form
                    }

                    var result =    
                                    '<tr>'+
                                    '<td><center>'+ num +'</center></td>'+
                                    '<td><center>'+ item.serial_number  +'</center></td>'+
                                    '<td><center>'+ $status  +'</center></td>'+
                                    '<td><center>'+ $send_form  +'</center></td>'+
                                    '</tr>';
                                               
                    $('#table_detail').find('tbody').append(result);

                num++
                });

                var get_no =  '#'+response.get_no.get_no 
                $('#from_get_no').append(get_no);

                $('#modal_detail').modal('show'); 
            }
        });

    }

    function remove_app(element)
    {
        $('#table_detail').find('tbody').empty();
        $('#from_get_no').empty();
    }

    </script>

@endsection