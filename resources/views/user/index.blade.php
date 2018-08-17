@extends('layouts.master_layout')

@section('style')
    <link href="{{ URL::asset('assets/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
@endsection

@section('content')


        <div class="col-md-10">
            <div class="content-box-large">
                <div class="panel-body">

                    <h3>User <button id="create" class="btn btn-primary pull-right"><i class="fa fa-plus-square" aria-hidden="true"></i> Add User</button></h3>
                    <hr>
                    <table class="table table-bordered" id="user_table">
                        <thead>
                          <tr bgcolor="#008fed" >
                            <th style="width: 25%"> <font color="#FFFFFF">ชื่อ</font></th>
                            <th style="width: 30%"> <font color="#FFFFFF">อีเมล์</font></th>
                            <th style="width: 10%"> <font color="#FFFFFF">Role</font></th>
                            <th style="width: 10%"> <font color="#FFFFFF">Status</font></th>
                            <th style="width: 18%"> <font color="#FFFFFF"></font></th>
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
                <form id="form" class="form-horizontal" method="post" action="{!! URL::to('main/user/add_user') !!}">

                    {!! csrf_field() !!}

                    <input type="hidden" id="state" name="state" value="create">
                    <input type="hidden" id="id" name="id" value="0">
                    <input type="hidden" id="id_role" name="id_role" value="0">

                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">ชื่อ</label>
                            <div class="col-sm-8">
                                <input id="name" name="name" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">อีเมล์</label>
                            <div class="col-sm-8">
                                <input id="email" name="email" type="text" class="form-control">
                            </div>
                        </div>  
                        <div class="form-group form_role">
                            <label class="col-sm-3 control-label">Role</label>
                            <div class="col-sm-8">
                                <input id="role" readonly type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="col-sm-3 control-label" id="text_role"></label>
                            <div class="col-sm-8">
                                <select name="role" id="role2" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($role as $key => $sesult)
                                    <option value="{{$sesult->id}}">{{ $sesult->display_name}}</option>
                                    @endforeach
                                </select>
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
        $('#modalLabel').text('Add User')
        $('#text_role').text('Role')
        $('#modal').modal('show')
        $('#id').val('');
        $('#name').val('');
        $('#email').val('');
        $('#role').val('');
        $("#role2").val('');
        $('.form_role').addClass('hidden');
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
                          'เพิ่ม User เรียบร้อย!',
                          'success'
                        )
                    oTable.draw();
                }
                else if(response['edit_success'] == true)
                {    
                    $('#modal').modal('hide')
                    swal(
                          'สำเร็จ!',
                          'แก้ไขข้อมูลเรียบร้อย!',
                          'success'
                        )
                    oTable.draw();
                }
                else
                {
                    $('#modal').modal('hide')
                    swal(
                          'ผิดพลาด!',
                          'Email ซ้ำ!',
                          'warning'
                        )
                }                     
            }

        }); 
        
        return false; 
    });

    $('#form_search').on('submit', function(e) {
            oTable.draw();
            e.preventDefault();
    });

    var oTable = $('#user_table').DataTable({
    sPaginationType: "full_numbers",
    sDom: '<"top"l>rt<"bottom"ip><"clear">',
    processing: true,
    serverSide: true,
    ajax: {
        url: '{!! URL::to("main/user/objectData") !!}',
        method: 'POST',
        data: function (d) {
            d._token = "{{ csrf_token() }}";
        }
    },
    columns: [
        { data: 'name',       name: 'name'},
        { data: 'email',      name: 'email'},
        { data: 'role',      name: 'role'},
        { data: 'status',     name: 'status'},
        { data: 'edit',       name: 'edit'},

    ],
    });
        
    function editData(id)
    {
        $.ajax({
            url: "{!! URL::to('/main/user/detail/') !!}/"+id, 
            success: function(response)
            {
                $('#id').val(response.users.user_id);
                $('#id_role').val(response.users.role_id);
                $('#name').val(response.users.name);
                $('#email').val(response.users.email);
                $('#role').val(response.users.roles_display_name);
                $('#state').val('update');
                $('#modalLabel').text('Edit User');
                $('#text_role').text('Edit Role')
                $('#modal').modal('show');
                $('.form_role').removeClass('hidden');
            }
        });
    }

    </script>

@endsection
