@extends('layouts.main')

@section('content')
<div class="page-header">
  <div class="icon">
    <span class="ico-arrow-right"></span>
  </div>
  <h1>Check For New Items<small>Generate New Items</small></h1>
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
			<div class="row-form">
				<div class="span12">
					{{ Form::open(array('url'=>'items', 'files' => true,'class'=>'form-additem')) }}
					<h2 class="form-signup-heading">Add Itemid to start track</h2>

					<ul>
						@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div><!-- .span12 -->
				<div class="span9">
					<div>
						{{ Form::text('source_id', null, array('class'=>'input-medium', 'placeholder'=>'ItemId')) }}
						{{ Form::text('source', null, array('class'=>'input-medium', 'placeholder'=>'ebay')) }}
					</div>
				</div><!-- .span12 -->
				<div class="span9">
					<div>{{ Form::text('name', null, array('class'=>'input-block-level', 'placeholder'=>'Item Name')) }}</div>
				</div><!-- .span12 -->
				<div class="span9">
					<div>{{ Form::text('description', null, array('class'=>'input-block-level', 'placeholder'=>'Item Description')) }}</div>
				</div><!-- .span12 -->
				<div class="span9">
					<div class="top">Select Image Or Provide Image Url</div>
					<div>
					
						{{ Form::text('imageurl', null, array('class'=>'input-block-level', 'placeholder'=>'Item Image Url')) }}
						{{ Form::file('image') }}
					</div>
				</div><!-- .span12 -->
				<div class="span9">
					{{ Form::submit('Add Item To Track', array('class'=>'btn btn-large btn-primary btn-block'))}}
					{{ Form::close() }}
				</div><!-- .span12 -->
      </div><!-- .row-form -->
    </div><!-- block -->
  </div><!-- span12 -->
</div><!-- row-fluid -->

@stop