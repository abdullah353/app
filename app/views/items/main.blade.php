@extends('layouts.main')

@section('content')
<div class="page-header">
  <div class="icon">
    <span class="ico-arrow-right"></span>
  </div>
  <h1>Your Local Invenotry <small>This is Your invenotory at application</small></h1>
</div><!-- end .page-header -->

<div class="row-fluid content">
	<div class="block-fluid">
		@foreach(array_chunk($items ,3) as $pitems)
			<div class="row-form">
				@foreach($pitems as $item)
					<div class="span4">
						<div class="block" id="sWidget_2" style="position: relative;">
							<div class="head orange">
								<strong>{{ $item['name'] }}</strong>                 
								<ul class="buttons">                                    
									<li>
										<a href="#"><div class="icon"><span class="ico-cog"></span></div></a>
										<ul class="dropdown-menu">
											<li><a href="{{ URL::to('orders/' . $item['source_id']) }}">GetOrdersList</a></li>
											<li class="divider"></li>
											<li><a href="#">Overview</a></li>
										</ul>
									</li>                                    
								</ul>                                
							</div>
							<div class="data dark">
								<span>ItemId: {{ $item['source_id'] }}</span><br>
								<span>Description: {{ $item['description'] }}</span><br>
								<img src="{{ $item['images'][0]['origurl'] }}">
							</div><!-- .data .dark -->
						</div><!-- .block -->
					</div>
				@endforeach
			</div>
		@endforeach
	</div><!-- .block-fluid -->
</div><!-- .content -->

@stop
