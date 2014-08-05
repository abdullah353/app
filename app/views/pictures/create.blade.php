@extends('layouts.main')

@section('content')
<div class="page-header">
  <div class="icon">
    <span class="ico-arrow-right"></span>
  </div>
  <h1>Add New pictures</h1>
</div><!-- end .page-header -->

<div class="row-fluid content">
	<div class="block-fluid">
		<div class="row-form">
			@if($errors->has())
			  <div id="form-errors">
			    <p>Following Errors Occured</p>
			    <ul>
			      @foreach($errors->all() as $error)
			        <li>{{ $error }}</li>
			      @endforeach
			    </ul>
			  </div> <!-- end form-errors -->
			@endif
			{{ Form::open(array('url' => 'pictures', 'files' => true)) }}
			{{ Form::file('pictures[]', array('multiple'=>true)) }}
			{{ Form::submit('Upload Files') }}
			{{ Form::close() }}
		</div>
	</div><!-- .block-fluid -->
</div><!-- .content -->

@stop
