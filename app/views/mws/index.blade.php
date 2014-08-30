@extends('layouts.main')

@section('content')
<div class="page-header">
	<div class="icon">
		<span class="ico-arrow-right"></span>
	</div>
	<h1>Orders/Transaction Page<small>Generate New orders</small></h1>
</div><!-- end .page-header -->

{{ Form::open(array('action' => 'MwsController@getIndex', 'method' => 'get')) }}
<div class="row-fluid">
	<div class="row-form">
		<div class="span12">
			<h1>Fetch Your Inventory From Server</h1>
		</div>
	</div>
	<div class="row-form"><!-- new form row -->
			<div class="span3"><!-- new form column -->
				<span class="top">Account</span><!-- top text line -->
				<select name="account">
					<option value="1">ghassanbashir2000@gmail.com</option>
					<option value="2">xamz2000@stufff.dii</option>
				</select>
			</div>
			<div class="span3"><!-- new form column -->
				<span class="top">Fetch Orders From</span><!-- top text line -->
				<input name="from" type="text" class="datepicker" value="{{ date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y"))) }}" />	 
			</div>
			<div class="span6"><!-- new form column -->
				<span class="top">Other Details</span><!-- top text line --> 
				<select name="status">
					<option value="all">All</option>
					<option value="PendingAvailability">PendingAvailability</option>
					<option value="Pending">Pending</option>
					<option value="Unshipped">Unshipped</option>
					<option value="PartiallyShipped">PartiallyShipped</option>
					<option value="Shipped">Shipped</option>
					<option value="InvoiceUnconfirmed">InvoiceUnconfirmed</option>
					<option value="Canceled">Canceled</option>
					<option value="Unfulfillable">Unfulfillable</option>
				</select>
			</div>
	</div>
	<div class="row-form">
		<div class="span12">
			{{ Form::button('<span class="icon-book icon-white"></span> Fetch Now', array('type'=>'submit','class'=>'btn btn-large btn-block btn-success')) }}
		</div>
</div>
{{ Form::close() }}

@if($orders != null)
{{ Form::open(array('action' => 'MwsController@postExcel')) }}
<div class="row-fluid content">
	<div class="block-fluid">
	<?php $i = 0; ?>
		@foreach(array_chunk($orders,4) as $order)
			<div class="row-form">
				@foreach($order as $el)
				<?php $i++; ?>
					
					<div class="span3">
						<div class="block" id="sWidget_2" style="position: relative;">
								<div class="head dblue">
									<h6>MWS OrderId: {{ $el->AmazonOrderId }}</h6>
								</div>
								@if(!empty($el->OrderTotal))
									<div class="data dark">
										<input type="checkbox" name="boxc[]" value="{{ $el->AmazonOrderId }}">ADD BOXC<br>
										<input type="checkbox" name="pfc[]" value="{{ $el->AmazonOrderId }}">ADD PFC<br>
										
										
										AmountPaid: {{ $el->OrderTotal->Amount }}<br>
										Shipped: {{ $el->NumberOfItemsShipped }} :: Unshipped: {{ $el->NumberOfItemsUnshipped }}<br>
										@foreach( $orderitems["".$el->AmazonOrderId]["OrderItems"] as $orderitem )
											<?php
												$OrderedlistTitles = array();
											?>
											@if(isset($orderitem["Title"]))
												<!-- Single Items Added in Cart -->
												<h6>{{ $orderitem["Title"] }}</h6>
												<h6>Item Price {{ $orderitem["ItemPrice"]["Amount"] }}</h6>
												<?php 
													$currentasin = $orderitem["ASIN"];
													array_push($OrderedlistTitles,$orderitem["Title"]);
												?>
												<img src={{ $products["".$currentasin]["image"] }} >
											@else
												<!-- Multiple Orders Added in Cart -->
												<?php 
													$currentasin = "";
													$asslinlist = array();
													$OrderedlistQTY = array();
												?>
												@foreach( $orderitem as $suborderitem)
													<h6>{{ $suborderitem["Title"] }}</h6>
													<h6>Item Price {{ $suborderitem["ItemPrice"]["Amount"] }}</h6>
													<h6>QuantityOrdered {{ $suborderitem["QuantityOrdered"] }}</h6>
													<?php
													 array_push($asslinlist,$suborderitem["ASIN"]);
													 array_push($OrderedlistQTY,$suborderitem["QuantityOrdered"]);
													 array_push($OrderedlistTitles,$suborderitem["Title"]);
													?>
												@endforeach
													@foreach( $asslinlist as $singleasin)
														<img src={{ $products["".$singleasin]["image"] }} >
													@endforeach
											@endif
										@endforeach
										<a href="#{{ $el->AmazonOrderId }}" role="button" class="btn" data-toggle="modal">OverView</a>
									</div>
								@endif
						</div>
					</div>
					<div id="{{ $el->AmazonOrderId }}" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
						<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								<h3 id="myModalLabel">{{ $el->AmazonOrderId }}</h3>
						</div>
						<div class="modal-body">
								Shipped: {{ $el->NumberOfItemsShipped }} :: Unshipped: {{ $el->NumberOfItemsUnshipped }}<br>
								@if( !empty($el->ShippingAddress) )
									Name: {{ $el->ShippingAddress->Name }}<br>
									CityName: {{ $el->ShippingAddress->City }}<br>
								 	Country: {{ $el->ShippingAddress->Country }}<br>
								 	CountryCode: {{ $el->ShippingAddress->CountryCode }}<br>
								 	StateOrRegion: {{ $el->ShippingAddress->StateOrRegion }}<br>
								 	Phone: {{ $el->ShippingAddress->Phone }}<br>
								 	PostalCode: {{ $el->ShippingAddress->PostalCode }}<br>
								 	AddressLine1: {{ $el->ShippingAddress->AddressLine1 }}<br>
								 	AddressLine2: {{ $el->ShippingAddress->AddressLine2 }}<br>
								 	<hr>
								 	<pre>
								 		{{ print_r( $el ) }}
								 	</pre><hr>
								 	<pre>
								 		{{ print_r( $orderitems["".$el->AmazonOrderId] ) }}
								 	</pre><hr>
								 	@if($currentasin != "")
									 	<pre>
									 		{{ print_r( $products["".$currentasin] ) }}
									 	</pre>
									@else
										@foreach( $asslinlist as $singleasin)
											<pre>
										 		{{ print_r( $products["".$singleasin] ) }}
										 	</pre>
										@endforeach
									@endif
							 	@endif
						</div>
				</div>
				@endforeach
			</div>
		@endforeach
		<div class="row-form">
			<div class="span6">
				<input type="checkbox" name="format" value="boxc">Boxc<br>
				<input type="checkbox" name="format" value="pfc">PFC Format 
			</div>
			<div class="span6">
				{{ Form::button('<span class="icon-book icon-white"></span> EXCEL IMPORT', array('type'=>'submit','class'=>'btn btn-large btn-block btn-success')) }}
			</div>
		</div>
	</div><!-- .block-fluid -->
</div><!-- .content -->
@endif

{{ Form::close() }}
@stop

