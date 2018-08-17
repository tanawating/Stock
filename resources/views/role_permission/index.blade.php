@extends('layouts.master_layout')

@section('content')

	    <div class="col-md-10">
	    	<div class="panel panel-primary">
			 	<div class="panel-heading"><b>Edit Role Permission</b></div>
			  	<div class="panel-body">
			  		<form action="{!! URL::to("/role_permission/store") !!}" method="post">

                    	{!! csrf_field() !!}
					
						<div class="col-md-12">
					    	<table class="table table-bordered table-striped">
					    		<thead>
					    			<tr>
					    				<th class="text-center">Role</th>
					    				@foreach( $pages as $key => $page )
						    				<th class="text-center"> {{ $page->page }} </th>
					    				@endforeach
					    			</tr>
					    		</thead>
					    		<tbody>
					    			@foreach( $roles as $key => $role )
						    			<tr>
						    				<td class="text-center"> <b>{{ $role->display_name }}</b> </td>
						    				@foreach( $pages as $sub_key => $page )
							    				<td class="text-left">
							    					@foreach( $permission_role as $permission_role_key => $value )
							    						@if( $value->page == $page->page && $role->id == $value->role_id)
								    						<div>
									    						<input id="{{ $value->permission_name }}[{{ $key }}][{{ $sub_key }}]" type="checkbox" name="permission_role[id][{{ $value->id }}]" value="{{ $value->id }},on" class="checkbox-on" onclick="changeState(this)" {{ $value->is_checked == true ? 'checked' : '' }}>
									    						<input type="hidden" name="permission_role[id][{{ $value->id }}]" value="{{ $value->id }},off" class="checkbox-off" {{ $value->is_checked == true ? 'disabled' : '' }}>
									    						<label for="{{ $value->permission_name }}[{{ $key }}][{{ $sub_key }}]" class="control-label label-checkbox"> {{ $value->display_name }} </label>
									    					</div>
								    					@endif
							    					@endforeach
							    				</td>
						    				@endforeach
						    			</tr>
					    			@endforeach
					    		</tbody>
					    	</table>
					    	<div class="form-group">
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
                                    <button class="btn btn-danger" onClick="window.close()"><i class="fa fa-times" aria-hidden="true"></i> Exit</button>
                                </div>
                            </div>
					    </div>

				    </form>
			  	</div>
			</div>
	    </div>



@endsection

@section('script')
	<script type="text/javascript">
		function changeState (element)
		{
			if ($(element).is(':checked')) 
			{
				console.log('a')
				$(element).parent().find('.checkbox-off').prop('disabled', true);
			}
			else
			{
				console.log('b')
				$(element).parent().find('.checkbox-off').prop('disabled', false);
			}
		}
	</script>
@endsection