@extends('layouts.main')

@section('content')
<div class="page-header">
  <div class="icon">
    <span class="ico-arrow-right"></span>
  </div>
  <h1>Create UPC-A Codes<small>Generate New Codes</small></h1>
</div><!-- end .page-header -->
<div class="row-fluid">
  <div class="span12">
    <div class="block">
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
{{ Form::open(array('url'=>'codes')) }}

{{ Form::label('Number of New UPCA codes required') }}
{{ Form::text('qty',null,array('type'=>'number')) }}
<!-- <h4>OR</h4>
<hr>
{{ Form::label('Generate against this specific number') }}
{{ Form::text('number',null,array('type'=>'number')) }} -->
<br>
{{ Form::submit('submit') }}
{{ Form::close() }}
    </div><!-- block -->
  </div><!-- span12 -->
</div><!-- row-fluid -->
@stop