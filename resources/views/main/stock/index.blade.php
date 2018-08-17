@extends('layouts.master_layout')

@section('style')
    <link href="{{ URL::asset('assets/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
@endsection

@section('content')


        <div class="col-md-10">
            <div class="content-box-large">
                <div class="panel-body">

                    <h3>Stock All</h3>
                    <hr>
                    <table class="table table-bordered" id="stock_table">
                        <thead>
                          <tr bgcolor="#008fed" >
                            <th  style="width: 20%"><font color="#FFFFFF">วันที่รับเข้าคลัง</font></th>
                            <th>                    <font color="#FFFFFF">ผลิตภัณฑ์</font></th>
                            <th>                    <font color="#FFFFFF">คลัง</font></th>
                            <th  style="width: 10%"><font color="#FFFFFF">คงเหลือ</font></th>
                          </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>


@endsection

@section('script')

    <script src="{{ URL::asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script type="text/javascript">

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
        url: '{!! URL::to("main/stock/objectData") !!}',
        method: 'POST',
        data: function (d) {
            d._token = "{{ csrf_token() }}";
            d.select = $('.select').val();
        }
    },
    fnDrawCallback: function () {
        var rows = this.fnGetData();
        if ( rows.length === 0 ) {
             $('#modal').modal('show')  
             $('#account').parents().addClass('hidden');    
        }
        if($('input[name="id"]').val() != ''){
            $('#account').parents().removeClass('hidden');
        }
    },
    columns: [
        { data: 'time_stock',       name: 'time_stock'},
        { data: 'type_product',     name: 'type_product'},
        { data: 'treasury',         name: 'treasury'  },
        { data: 'total',            name: 'total'  },

    ],
    });
        

    </script>

@endsection
