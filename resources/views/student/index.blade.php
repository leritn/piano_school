@extends('app')


@section('htmlheader_title')
List of all Students
@endsection


@section('contentheader_title')
<h1>Student <small>List of all students</small></h1>
@endsection


@section('main-content')

<div class="box box-solid box-default">
	<div class="box-header">
		<div class="row">

			<form action="{{url('/student')}}" method="GET">
				@if (Entrust::can('create-student'))
				<div class="col-xs-12  text-left visible-xs " >
					<a href= "{{url('student/create')}}" class="btn btn-primary  custom-font" id="add_student_mobile" >
						<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add
					</a>
				</div>
				@endif
				<div class="row">
					<div class="col-xs-12" style="height:10px">
					</div>
				</div>

				<div class="col-xs-12 col-sm-6">
					<div class="input-group ">
					  <input type="text" class="form-control" name="search" placeholder="Search for..." value="@if(!is_null($searchResult['keyword'])){{$searchResult['keyword']}}@endif">
				      <span class="input-group-btn">
				        <button class="btn btn-default " type="submmit">
				        	 <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
				        	Search
				        </button>
				      </span>
					</div>
				</div>
				@if (Entrust::can('create-student'))
				<div class="col-sm-6  text-right hidden-xs" >
					<a href= "{{url('student/create')}}" class="btn btn-primary custom-font"  id="add_student" >
						<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add
					</a>
				</div>
				@endif
				
			</form>
		

		
		</div>
	</div><!-- /.box-header -->

	<div class="box-body">
		@if (isset($searchResult['keyword']) && !empty($searchResult['keyword']))
		<div class="row">
			<div class="col-xs-2 hidden-sm"></div>
		    <div class="col-xs-8 alert bg-gray color-palette">
		        <div class="col-xs-12">
		        	Search for {{$searchResult['keyword']}}, found {{$searchResult['count']}} results
		        </div>
		    </div>
		</div>
		@endif
		<div class="row">
			<div class="col-sm-12 col-md-11 col-md-offset-1 " id="schedule_list_table">
				<div class="row hidden-xs hidden-sm" id="table_header">
					
						<div class="col-sm-2 col-header vcenter">
							<span><strong>Profile Picture</strong></span>
						</div>
						<div class="col-sm-3 col-header vcenter">
							<span><strong>Name</strong></span>
						</div>
						<div class="col-sm-2 col-header vcenter">
							<span><strong>Student Tel.</strong></span>
						</div>
						<div class="col-sm-2 col-header vcenter">
							<span><strong>Parent Tel.</strong></span>
						</div>
						<div class="col-sm-3 col-header vcenter">
							<span><strong>Option</strong></span>
						</div>
				</div>

				@foreach ($students as $student)
				
				<div class="row  ">
				
					<div class="col-sm-2 col-xs-4">
						@if (!empty($student->picture))
						<img class="img-thumbnail table-profile-picture" src="{{url('/uploads/profile_pictures/').'/'.$student->picture}}"  width="70px">
						@else
						<img class="img-thumbnail table-profile-picture" src="{{url('/uploads/profile_pictures/')}}/default.jpg"  width="70px" />
						@endif       
					</div>
					<div class="col-sm-3 hidden-xs">
						{{$student->firstname."  ".$student->lastname}} ({{$student->nickname}})
					</div>
					<div class="col-sm-2 hidden-xs">
						{{ substr($student->student_phone  ,0,3)."-".substr($student->student_phone   ,3,3)."-".substr($student->student_phone  ,6)}}      
					</div>
					<div class="col-sm-2 hidden-xs">
						{{substr($student->parent_phone  ,0,3)."-".substr($student->parent_phone   ,3,3)."-".substr($student->parent_phone  ,6)}}      
					</div>
					<div class="col-xs-6 visible-xs">
						{{$student->nickname}} <br>
					
						<span><b>Student Tel.</b></span><br>
						{{substr($student->student_phone  ,0,3)."-".substr($student->student_phone   ,3,3)."-".substr($student->student_phone  ,6)}}<br>
						<span><b>Parent Tel.</b></span><br>
						{{substr($student->parent_phone  ,0,3)."-".substr($student->parent_phone   ,3,3)."-".substr($student->parent_phone  ,6)}} <br>
					</div>

					<form action="{{url('student/restore')}}" method="post">
						{!! csrf_field() !!}
						<!-- Single button -->
						<div class="col-sm-3 hidden-xs">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" name="id" id="delete_id" value="{{$student->id}}">
							@if (Entrust::can('view-student'))
							<a href= "{{url('student/'.$student->id)}}" class="btn btn-default btn-flat btn-sm">
								<i class="fa fa-eye"></i>
								View
							</a>
							@endif
								
							@if (Entrust::can('edit-student'))
							<a id="edit_student" href= "{{url('student/'.$student->id.'/edit')}}" class="btn btn-default btn-flat btn-sm" >
								<i class="fa fa-edit"></i>
								Edit
							</a>
							@endif

							@if (Entrust::can('delete-student'))
							<a class="btn btn-danger btn-flat btn-sm"
							   id="delete_student"
							   data-toggle="modal" 
							   data-target="#myModal" 
							   student_id="{{$student->id}}" 
							   student_name="{{$student->nickname . '(' . $student->firstname . ' ' . $student->lastname . ')'}}">
							   <i class="fa fa-trash"></i>
							   Delete
							</a>	
							@endif
						</div>
					</form>
					<form action="{{url('student/restore')}}" method="post">
							{!! csrf_field() !!}
						<div class="visible-xs col-xs-2 ">		
							<!-- Single button -->
							<div class="btn-group  " >
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="id" id="delete_id" value="{{$student->id}}">

								<button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<span class="glyphicon glyphicon-th"></span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right">
									@if (Entrust::can('view-student'))
									<li><a href= "{{url('student/'.$student->id)}}">View</a></li>
									@endif

									@if (Entrust::can('edit-student'))
									<li><a href= "{{url('student/'.$student->id.'/edit')}}">Edit</a></li>
									@endif

									@if (Entrust::can('delete-student'))
									<li><a 
										data-toggle="modal" 
										data-target="#myModal" 
										student_id="{{$student->id}}" 
										student_name="{{$student->nickname . ' (' . $student->firstname . ' ' . $student->lastname . ')'}}">
										Delete
									</a></li>
									@endif
								</ul>
							</div>
						</div>
					</form>
				
			</div>
				<div class="row visible-xs">
					<div class="col-xs-12" style="height:30px">
					</div>
				</div>
				<div class="row hidden-xs">
					<div class="col-xs-12" style="height:10px">
					</div>
				</div>
				@endforeach
			</div>	
			
		</div>
	</div><!--End Student List Table -->
	<div class="row">
		<div class="col-xs-12 col-sm-12 pagination-info vcenter  text-center">
			<span>{{App\helpers\TextHelper::paginationInfo($students)}}</span>
		</div>
		<div class="col-xs-12 text-center">
			@if (isset($searchResult))
				{!! $students->appends(['search' => $searchResult['keyword']])->render() !!}
			@else
				{!! $students->render() !!}
			@endif
		</div>
	</div>


</div>

<form action="" method="POST" id="confirm-delete"> 

				<div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="myModalLabel">Delete student</h4>
							</div>
							<div class="modal-body">
								Are you sure you want to delete <span id="delete_message"></span>? 
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

									<input type="hidden" name="_method" value="DELETE">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
									<button class="btn btn-danger" >
										<span class="glyphicon glyphicon-remove-sign" aria-hidden="true"> </span> Delete
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>

			</form>
	

<script type="text/javascript">

$('#myModal').on('shown.bs.modal',function(e){
	$('#myInput').focus();
	console.log(e);
	delete_student_id = e.relatedTarget.attributes.student_id.value;
	delete_student_name = e.relatedTarget.attributes.student_name.value;

	$("#delete_message").html(delete_student_name);
	$("#confirm-delete").attr("action", "{{url('student')}}"+"/"+delete_student_id);
});

</script>
@endsection