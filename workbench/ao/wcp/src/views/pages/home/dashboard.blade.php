@extends('wcp::layout.master')
@section('jumbotron_breadcrumb')
	<ul class="breadcrumb">
	    <li>
	      <p><i class="fa fa-home"></i></p>
	    </li>
	    <li><a href="{{ URL::to('admin') }}" class="active">Dashboard</a>
	    </li>
	</ul>
@stop
@section('jumbotron')
	@include('wcp::_partials.html.jumbotron')
	
@stop
@section('content')
	
@stop