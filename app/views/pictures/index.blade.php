@extends('layouts.main')

@section('content')
<div class="page-header">
  <div class="icon">
    <span class="ico-arrow-right"></span>
  </div>
  <h1>pictures Uploaded</h1>
</div><!-- end .page-header -->

<div class="row-fluid content">
	<div class="block-fluid">
		<div class="row-form">
			<h3>{{ HTML::linkAction('PicturesController@create','upload pictures') }}</h3>
		</div>
		@foreach(array_chunk($pictures ,3) as $ppictures)
			<div class="row-form">
				@foreach($ppictures as $picture)
					<div class="span4">
						<div class="block" id="sWidget_2" style="position: relative;">
							<div class="head orange">
								<strong>{{ $picture['name'] }}</strong>                                               
							</div>
							<div class="data dark">
								<span>pictureId: {{ $picture['id'] }}</span><br>
								<span>{{ Form::text('url',$picture['url']) }}</span><br>
								<hr>
								<img style=" height: 190px; " src="{{ $picture['url'] }}">
							</div><!-- .data .dark -->
						</div><!-- .block -->
					</div>
				@endforeach
			</div>
		@endforeach
	</div><!-- .block-fluid -->
</div><!-- .content -->

@stop
