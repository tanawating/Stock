@extends('layouts.master_layout')

@section('style')
    <link href="{{ URL::asset('assets/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
@endsection

@section('content')


        <div class="col-md-10">
            <div class="content-box-large">
                <div class="panel-body">

                    <h3>Role <button id="create" class="btn btn-primary pull-right"><i class="fa fa-plus-square" aria-hidden="true"></i>  Add Role</button></h3>
                    <hr>
                    <table class="table table-bordered" id="stock_table">
                        <thead>
                          <tr bgcolor="#008fed" >
                            <th style="width: 80%"> <font color="#FFFFFF">Role</font></th>
                            <th style="width: 18%"> <font color="#FFFFFF"></font></th>
                          </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <hr>

                    <h3>Role Permission <a target="_blank" href="{!! URL::to('/role_permission') !!}" class="btn btn-primary pull-right"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit Premission</a></h3>
                    <hr>
                    <table class="table table-bordered" id="stock_table">

                        <thead>
                          <tr bgcolor="#008fed" >
                            <th><font color="#FFFFFF">Role</font></th>
                            @foreach($pages as $kay => $result)
                            <th><font color="#FFFFFF">{{$result->page}}</font></th>
                            @endforeach
                          </tr>
                        </thead>
                        <tbody>
                            @foreach( $roles as $key => $role )
                                <tr>
                                    <td> {{ $role->display_name }} </td>
                                    @foreach( $pages as $sub_key => $page )
                                        <td>
                                            @foreach( $permission_role as $permission_role_key => $value )
                                                @if( $value->page == $page->page && $role->id == $value->role_id)
                                                    @if( $value->is_checked == true )
                                                        <div>{{ $value->display_name }}</div>
                                                    @else
                                                        <div>-</div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </td>
                                    @endforeach
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
                <form id="form" class="form-horizontal" method="post" action="{!! URL::to('main/role/add_role') !!}">

                    {!! csrf_field() !!}

                    <input type="hidden" id="state" name="state" value="create">
                    <input type="hidden" id="id" name="id" value="0">

                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Name Role</label>
                            <div class="col-sm-8">
                                <input id="name" name="display_name" type="text" class="form-control">
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
    <script src="{{ URL::asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script type="text/javascript">

    $('#create').click(function()
    {
        $('#state').val('create');
        $('#modalLabel').text('Add Role')
        $('#modal').modal('show')
        $('#name').val('')
    });

    $('#btn_form').click(function(){

        console.log('GG');
        $('#form').submit();
    })

    $('#form').submit(function() {  
        $(this).ajaxSubmit({
            error: function() {
                
            },
            success:function(response){
                if (response['success'] == true)
                {  
                    $('#modal').modal('hide')
                    swal(
                          'สำเร็จ!',
                          'เพิ่ม Role เรียบร้อย!',
                          'success'
                        )
                    oTable.draw();
                }
                else
                {    
                    $('#modal').modal('hide')
                    swal(
                          'สำเร็จ!',
                          'แก้ไข Role เรียบร้อย!',
                          'success'
                        )
                    oTable.draw()
                }                     
            }

        }); 
        
        return false; 
    });

    $('#form_search').on('submit', function(e) {
            oTable.draw();
            e.preventDefault();
    });

    var oTable = $('#stock_table').DataTable({
    sPaginationType: "full_numbers",
    sDom: '<"top"l>rt<"bottom"ip><"clear">',
    processing: true,
    serverSide: true,
    ajax: {
        url: '{!! URL::to("main/role/objectData") !!}',
        method: 'POST',
        data: function (d) {
            d._token = "{{ csrf_token() }}";
        }
    },
    columns: [
        { data: 'display_name',       name: 'display_name'},
        { data: 'edit',     name: 'edit'},

    ],
    });
        
    function editData(id)
    {
        $.ajax({
            url: "{!! URL::to('/main/role/detail/') !!}/"+id, 
            success: function(response)
            {
                $('#id').val(response.roles.id);
                $('#name').val(response.roles.display_name);
                $('#state').val('update');
                $('#modalLabel').text('Edit Role');
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
                    url: "{!! URL::to('/main/role/delete/') !!}/"+id,
                    success: function(response)
                    {
                        oTable.draw();
                        swal(
                                'สำเร็จ!',
                                'ลบข้อมูลเรียบร้อย',
                                'success'
                            )
                    }
                });
              
            })
    }
    </script>

@endsection
