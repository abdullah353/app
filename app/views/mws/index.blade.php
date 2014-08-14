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
			<div class="span6"><!-- new form column -->
				<span class="top">Fetch Orders From</span><!-- top text line -->
				<input name="from" type="text" class="datepicker" value="{{ date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-14, date("Y"))) }}" />	 
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
										AmountPaid: {{ $el->OrderTotal->Amount }}<br>
										Shipped: {{ $el->NumberOfItemsShipped }} :: Unshipped: {{ $el->NumberOfItemsUnshipped }}<br>
										@foreach( $orderitems["".$el->AmazonOrderId]["OrderItems"] as $orderitem )
											<h6>{{ $orderitem["Title"] }}</h6>
											<h6>Item Price {{ $orderitem["ItemPrice"]["Amount"] }}</h6>
											<?php $currentasin = $orderitem["ASIN"]; ?>
										@endforeach
										<img src={{ $products["".$currentasin]["image"] }} >
										<a href="#{{ $el->AmazonOrderId }}" role="button" class="btn" data-toggle="modal">OverView</a>
									</div>
								@endif
						</div>
					</div>
					<div id="{{ $el->AmazonOrderId }}" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
						<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
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
								 	<pre>
								 		{{ print_r( $products["".$currentasin] ) }}
								 	</pre>
								 	<?php $randOrder = str_random(6); ?>
<input type="hidden" name="order[{{ $randOrder }}][CompanyID]" value="838">
<input type="hidden" name="order[{{ $randOrder }}][OrderID]" value="{{ $randOrder }}">
<input type="hidden" name="order[{{ $randOrder }}][SKU]" value="">
<input type="hidden" name="order[{{ $randOrder }}][Service]" value="">
<input type="hidden" name="order[{{ $randOrder }}][Name]" value="{{ $el->ShippingAddress->Name }}">
<input type="hidden" name="order[{{ $randOrder }}][Phone]" value="{{ $el->ShippingAddress->Phone }}">
<input type="hidden" name="order[{{ $randOrder }}][Street1]" value="{{ $el->ShippingAddress->AddressLine1 }}">
<input type="hidden" name="order[{{ $randOrder }}][Street2]" value="{{ $el->ShippingAddress->AddressLine2 }}">
<input type="hidden" name="order[{{ $randOrder }}][City]" value="{{ $el->ShippingAddress->City }}">
<input type="hidden" name="order[{{ $randOrder }}][State]" value="{{ $el->ShippingAddress->StateOrRegion }}">
<input type="hidden" name="order[{{ $randOrder }}][PostalCode]" value="{{ $el->ShippingAddress->PostalCode }}">
<input type="hidden" name="order[{{ $randOrder }}][Contents]" value="">
<input type="hidden" name="order[{{ $randOrder }}][Items]" value="{{ $el->NumberOfItemsUnshipped }}">
<input type="hidden" name="order[{{ $randOrder }}][Value]" value="{{ $orderitem['ItemPrice']['Amount'] }}">
<input type="hidden" name="order[{{ $randOrder }}][SignatureConfirmation]" value="0">
<input type="hidden" name="order[{{ $randOrder }}][Units]" value="Metric">
<input type="hidden" name="order[{{ $randOrder }}][Weight]" value="{{ $products[''.$currentasin]['weight'] }}">
<input type="hidden" name="order[{{ $randOrder }}][Height]" value="{{ $products[''.$currentasin]['height'] }}">
<input type="hidden" name="order[{{ $randOrder }}][Width]" value="{{ $products[''.$currentasin]['width'] }}">
<input type="hidden" name="order[{{ $randOrder }}][Depth]" value="1">


<input type="hidden" name="maporder[{{ $randOrder }}][OrderID]" value="{{ $randOrder }}">
<input type="hidden" name="maporder[{{ $randOrder }}][image]" value="{{ $products["".$currentasin]['image'] }}">
<input type="hidden" name="maporder[{{ $randOrder }}][Items]" value="{{ $el->NumberOfItemsUnshipped }}">
<input type="hidden" name="maporder[{{ $randOrder }}][PostalCode]" value="{{ $el->ShippingAddress->PostalCode }}">
<input type="hidden" name="maporder[{{ $randOrder }}][State]" value="{{ $el->ShippingAddress->StateOrRegion }}">
<input type="hidden" name="maporder[{{ $randOrder }}][City]" value="{{ $el->ShippingAddress->City }}">
<input type="hidden" name="maporder[{{ $randOrder }}][Street2]" value="{{ $el->ShippingAddress->AddressLine2 }}">
<input type="hidden" name="maporder[{{ $randOrder }}][Street1]" value="{{ $el->ShippingAddress->AddressLine1 }}">
<input type="hidden" name="maporder[{{ $randOrder }}][Phone]" value="{{ $el->ShippingAddress->Phone }}">
<input type="hidden" name="maporder[{{ $randOrder }}][Name]" value="{{ $el->ShippingAddress->Name }}">


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

