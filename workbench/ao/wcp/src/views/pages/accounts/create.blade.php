@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <a href="{{ URL::to('admin') }}"><i class="fa fa-home"></i></a>
	    </li>
	    <li>
	      <a href="{{ URL::to('admin/accounts') }}">Accounts</a>
	    </li>
	    <li><a href="{{ Request::url() }}" class="active">Create</a>
	    </li>
	</ul>
@stop
@section('jumbotron')
	@include('wcp::_partials.html.jumbotron')
@stop
@section("stylesheet")

<style type="text/css">
	.nopadding{
		padding: 0px 0px !important;
	}
</style>

@stop
@section('content')

	<div class="content-fluid">

		{{ Form::open(["url"=>"admin/accounts"]) }}

			<div class="col-md-12">

				<div class="panel">
					<ul class="nav nav-tabs nav-tabs-simple">
					    <li class="active">
					      <a data-toggle="tab" href="#basicinfo">Create A New Account</a>
					    </li>
					</ul>
					<div class="tab-content">
						{{-- Begin Basic Info Tab --}}
					    <div class="tab-pane active" id="basicinfo">
					      <div class="row column-seperation">
					      	{{-- Begin Column --}}
					        <div class="col-md-6">

					          <div class="form-group form-group-default">
					          	{{ Form::label("first_name", "First Name") }}
					          	{{ Form::text("first_name", "", ["class"=>"form-control", "placeholder"=>"John"]) }}
					          </div>

					          <div class="form-group form-group-default">
					          	{{ Form::label("last_name", "Last Name") }}
					          	{{ Form::text("last_name", "", ["class"=>"form-control", "placeholder"=>"Doe"]) }}
					          </div>

					          <div class="form-group form-group-default">
					          	{{ Form::label("group", "Group") }}
					          	{{ Form::select("group", $groups, "", ["class"=>"form-control"]) }}
					          </div>

					          <div class="form-group form-group-default">
								{{ Form::label("activate", "Activate now?") }}
								<input type="checkbox" name="activate" value="1" class="switchery" data-color="#f8d053" checked>
							</div>

					        </div>
					        {{-- End Column --}}
					        {{-- Begin Column --}}
					        <div class="col-md-6">
					         <div class="form-group form-group-default">
					         	{{ Form::label("email", "Email") }}
					          	{{ Form::text("email", "", ["class"=>"form-control", "placeholder"=>"johndoe@email.com"]) }}
					          </div>

					          <div class="form-group form-group-default">
					          	{{ Form::label("password", "Password") }}
					          	<input type="password" name="password" class="form-control" id="password" placeholder="&#149;&#149;&#149;&#149;&#149;&#149;&#149;&#149;">
					          </div>

					          <div class="form-group form-group-default">
					          	{{ Form::label("password_confirmation", "Repeat Password") }}
					          	<input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="&#149;&#149;&#149;&#149;&#149;&#149;&#149;&#149;">
					          </div>

					          	<div class="form-group text-center">
				        			<button type="submit" class="btn btn-success btn-lg btn-block m-t-30"><i class="pg-plus"></i>&nbsp;Create</button>
				        		</div>

					        </div>
					        {{-- End Column --}}

					        {{-- Begin Form Footer --}}
				        	<div class="col-md-12">
				        		
				        	</div>
					        {{-- End Form Footer --}}
					      </div>
					    </div>
					    {{-- End Basic Info Tab --}}
					</div>
				</div>
				
			</div>

		{{ Form::close() }}

	</div>
	
@stop

@section("javascript")	<script type="text/javascript">
		$(function(){

			$(".master_checkbox").click(function(event){

				var checked = 0;

				if ($(this).is(":checked")) {

					$(".per_checkbox").prop("checked", true);
					$(".panel_master_checkbox").prop("checked", true);
				}
				else{

					$(".per_checkbox").prop("checked", false);
					$(".panel_master_checkbox").prop("checked", false);
				}

				$(".per_checkbox").each(function(){

					if ($(this).is(":checked")) {
						checked ++;
					};

				});

				if (checked > 0) {
					// There are checked
				}
				else{
					// No checked
				}
				
			});

			$(".per_checkbox").click(function(){

				var item_id = $(this).val(),
					checked = 0;

				if (!$(this).is(":checked")) {

					$(".master_checkbox").prop("checked", false);
				}
				else{

				}

				$(".per_checkbox").each(function(){

					if ($(this).is(":checked")) {
						checked ++;
					};

				});

				if (checked > 0) {
					// There are checked
				}
				else{
					// No checked
				}
			});

		});
	</script>
@stop