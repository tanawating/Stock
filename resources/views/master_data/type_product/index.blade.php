@extends('layouts.master_layout')

@section('content')

        <div class="col-md-10">
            <div class="content-box-large">
            <h3>เพิ่มประเภทสินค้า
            <button id="create" type="button" class="btn btn-primary pull-right"><i class="fa fa-plus-square" aria-hidden="true"></i> เพิ่ม</button></h3>
            <hr>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>ชื่อประเภทสินค้า</th>
                            <th>วันที่เพิ่ม</th>
                            <th>วันที่แก้ไข</th>
                            <th style="width: 16%"></th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach($data_type_product as $key => $result)
                          <tr>
                            <td>{{ $result->name_type_product }}</td>
                            <td style="width: 15%">{{ $result->created_at }}</td>
                            <td style="width: 15%">{{ $result->updated_at }}</td>
                            <td style="width: 15%">
                            <a href="#" class="btn btn-success btn-sm" onclick="editData({{ $result->id}})"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> แก้ไข </a>
                            <a class="btn btn-danger btn-sm" onclick="deleteData({{ $result->id}})" href="#"><i class="fa fa-trash" aria-hidden="true"></i> ลบ</a></td>
                          </tr>
                        @endforeach
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
                <form id="form" class="form-horizontal" method="post" action="{!! URL::to('masterdata/add_type_product') !!}">

                    {!! csrf_field() !!}

                    <input type="hidden" id="state" name="state" value="create">
                    <input type="hidden" id="id" name="id" value="0">

                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">ชื่อประเภทสินค้า</label>
                            <div class="col-sm-8">
                                <input id="name" name="name" type="text" class="form-control">
                            </div>
                        </div>
                            
                    </div>
                    <div class="modal-footer">
                        <button id="btn_form" type="button" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> บันทึก</button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-undo" aria-hidden="true"></i> ยกเลิก</button>
                    </div>
                </form>
            </div>
        </div>
        </div>

@endsection

@section('script')

<script src="{{ URL::asset('assets/js/jquery.form.min.js') }}"></script>
<script type="text/javascript">
    
    $('#create').click(function(){
        $('#state').val('create');
        $('#modalLabel').text('เพิ่มประเภทสินค้า')
        $('#modal').modal('show')
    });

    $('#btn_form').click(function(){

            $('#form').submit();

    })

    $('#form').submit(function() {  
        $(this).ajaxSubmit({
            error: function() {
                
            },
            success:function(response){
                if (response['success'] == true)
                {  
                    // load_data_table();
                    // $('#modal').modal('hide')
                    // location.reload();
                    $('#modal').modal('hide')
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
                else
                {    

                    
                }                     
            }

        }); 
        
        return false; 
    });

    function editData(id)
    {
        $.ajax({
            url: "{!! URL::to('/masterdata/type_product/detail') !!}/"+id, 
            success: function(response)
            {
                $('#id').val(response.data_type_product.id);
                $('#name').val(response.data_type_product.name_type_product);
                $('#state').val('update');
                $('#modalLabel').text('แก้ไขผลิตภัณฑ์');
                $('#modal').modal('show');
            }
        });
    }
    
    function deleteData(id)
    {
        swal({
              title: 'ยืนยันการลบ!!',
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'ยืนยัน'
            }).then(function () 
            {
                $.ajax({
                    url: "{!! URL::to('masterdata/type_product/destroy/') !!}/"+id,
                    success: function(response)
                    {
                        swal(
                                'สำเร็จ!',
                                'ลบข้อมูลเรียบร้อย',
                                'success'
                            )

                        setTimeout(function()
                        {
                           window.location.reload(1);
                        }, 1000);
                    }
                });
            })
    }

</script>

{!! JsValidator::formRequest('App\Http\Requests\RequestMasterDataTypeProduct','#form') !!}

@endsection