@extends('app')


@section('htmlheader_title')
Sample page
@endsection


@section('contentheader_title')

@endsection


@section('main-content')
<div class="box">
    <div class="box-header">
    	<div class="row">
    		<div class="col-xs-6">
    			<h3 class="box-title">Class Rooms List</h3>
    		</div>
      		<div class="col-xs-6 text-right">
      			<a href= "{{url('room/add')}}" class="btn btn-default" >
				  <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add
				</a>
      			<!-- <button type="button" class="btn btn-default" >
				  <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add
				</button> -->
      		</div>
      	</div>
      
    </div><!-- /.box-header -->
    <div class="box-body">
      <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
      	<div class="row">
      		<div class="col-sm-12">
      			<table 
      				id="example2" 
      				class="table table-bordered table-hover dataTable" 
      				role="grid" aria-describedby="example2_info">
			        <thead>
				         <tr role="row">
				          	<th 
				          		class="sorting_asc" 
				          		tabindex="0" 
				          		aria-controls="example2" 
				          		rowspan="1" colspan="1" 
				          		aria-label="Rendering engine: activate to sort column descending" 
				          		aria-sort="ascending"> Name </th>

				          	<th class="sorting" 
				          		tabindex="0" 
				          		aria-controls="example2" 
				          		rowspan="1" colspan="1"
				          		aria-label="Browser: activate to sort column ascending">Location</th>
				          	<th class="sorting" 
				              	tabindex="0" 
				              	aria-controls="example2" 
				              	rowspan="1" colspan="1" 
				              	aria-label="Platform(s): activate to sort column ascending">Description</th>
				            <th class="sorting" 
				              	tabindex="0" 
				              	aria-controls="example2" 
				              	rowspan="1" colspan="1" 
				              	aria-label="Platform(s): activate to sort column ascending">Option</th>
			          	
			        	</tr>
			        </thead>
        			<tbody>
        				@foreach ($roomview as $room)
        					<tr role="row" class="odd">
					            <td class="sorting_1">{{$room->name}}</td>
					            <td>{{$room->location}}</td>
					            <td>{{$room->description}}</td>
					            <td>
					            	<a href= "{{url('room/edit/'.$room->id)}}" class="btn btn-default" >
									  <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit
									</a>
									<a href= "{{url('room/destroy/'.$room->id)}}" class="btn btn-default btn-delete">
									  <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"> </span> Delete
									</a>
                  <script type="text/javascript">
                      $(".btn-delete").click(function(e){
                        return confirm("Do you want to Delete");
                        
                      });
                  </script>

					            </td>    
          					</tr>
        				@endforeach




				      
      				</tbody>
       
      			</table>
      		</div>
      	</div>
      	<!-- <div class="row">
      		<div class="col-sm-5">
	      		<button class="btn btn-block btn-primary">Add ClassRoom</button>
      		</div>
      		<div class="col-sm-7">
      			<div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
      				<ul class="pagination">
      					<li class="paginate_button previous disabled" id="example2_previous">
      						<a href="#" aria-controls="example2" data-dt-idx="0" tabindex="0">Previous</a>
      					</li>
      					<li class="paginate_button active">
      						<a href="#" aria-controls="example2" data-dt-idx="1" tabindex="0">1</a>
      					</li>
      					<li class="paginate_button ">
      						<a href="#" aria-controls="example2" data-dt-idx="2" tabindex="0">2</a>
      					</li>
      					<li class="paginate_button ">
      						<a href="#" aria-controls="example2" data-dt-idx="3" tabindex="0">3</a>
      					</li>
      					<li class="paginate_button ">
      						<a href="#" aria-controls="example2" data-dt-idx="4" tabindex="0">4</a>
      					</li>
      					<li class="paginate_button ">
      						<a href="#" aria-controls="example2" data-dt-idx="5" tabindex="0">5</a>
      					</li>
      					<li class="paginate_button ">
      						<a href="#" aria-controls="example2" data-dt-idx="6" tabindex="0">6</a>
      					</li>
      					<li class="paginate_button next" id="example2_next">
      						<a href="#" aria-controls="example2" data-dt-idx="7" tabindex="0">Next</a>
      					</li>
      				</ul>
      			</div>
      		</div> -->
      	</div>
      </div>
    </div><!-- /.box-body -->
 </div>
@endsection