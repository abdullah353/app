@extends('layouts.main')

@section('content')
<div class="page-header">
  <div class="icon">
    <span class="ico-arrow-right"></span>
  </div>
  <h1>Check For New Items<small>Generate New Items</small></h1>
</div><!-- end .page-header -->
<!--pre>
    {{-- print_r( array_chunk((array) $items->ItemArray,4) ) --}}
</pre-->
<div class="row-fluid content">
	<div class="block-fluid">
		@foreach(array_chunk((array) $items->ItemArray,3) as $pitems)
			<div class="row-form">
				@foreach($pitems as $item)
					<!--pre>
					    {{ print_r( $item->Title ) }}
					</pre-->
					<div class="span4">
						<div class="block" id="sWidget_2" style="position: relative;">
							<div class="head orange">
								<strong>{{ $item->Title }}</strong>                 
								<ul class="buttons">                                    
									<li>
										<a href="#"><div class="icon"><span class="ico-cog"></span></div></a>
										<ul class="dropdown-menu">
											<li><a href="{{ URL::to('orders/' . $item->ItemID) }}">GetOrdersList</a></li>
											<li><a href="#">AddTo Your Store</a></li>
											<li class="divider"></li>
											<li><a href="#">Overview</a></li>
										</ul>
									</li>                                    
								</ul>                                
							</div>
							<div class="data dark">
								<span>ItemId: {{ $item->ItemID }}</span><br>
								<span>Quantity: {{ $item->Quantity }}</span><br>
								<span attribute-category="{{ $item->PrimaryCategory->CategoryID }}">Category: {{ $item->PrimaryCategory->CategoryName }}</span><br>
								<span attribute-category="{{ $item->ConditionID }}">Condition: {{ $item->ConditionDisplayName }}</span><br>
								<img src="{{ $item->PictureDetails->PictureURL }}">
							</div><!-- .data .dark -->
						</div><!-- .block -->
					</div>
				@endforeach
			</div>
		@endforeach
	</div><!-- .block-fluid -->
</div><!-- .content -->
@stop