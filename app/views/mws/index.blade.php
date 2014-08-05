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
<input type="hidden" name="order[{{ $i }}][CompanyID]" value="">
<input type="hidden" name="order[{{ $i }}][OrderID]" value="{{ $i }}">
<input type="hidden" name="order[{{ $i }}][SKU]" value="">
<input type="hidden" name="order[{{ $i }}][Service]" value="">
<input type="hidden" name="order[{{ $i }}][Name]" value="{{ $el->ShippingAddress->Name }}">
<input type="hidden" name="order[{{ $i }}][Phone]" value="{{ $el->ShippingAddress->Phone }}">
<input type="hidden" name="order[{{ $i }}][Street1]" value="{{ $el->ShippingAddress->AddressLine1 }}">
<input type="hidden" name="order[{{ $i }}][Street2]" value="{{ $el->ShippingAddress->AddressLine2 }}">
<input type="hidden" name="order[{{ $i }}][City]" value="{{ $el->ShippingAddress->City }}">
<input type="hidden" name="order[{{ $i }}][State]" value="{{ $el->ShippingAddress->StateOrRegion }}">
<input type="hidden" name="order[{{ $i }}][PostalCode]" value="{{ $el->ShippingAddress->PostalCode }}">
<input type="hidden" name="order[{{ $i }}][Contents]" value="">
<input type="hidden" name="order[{{ $i }}][Items]" value="{{ $el->NumberOfItemsUnshipped }}">
<input type="hidden" name="order[{{ $i }}][Value]" value="">
<input type="hidden" name="order[{{ $i }}][SignatureConfirmation]" value="">
<input type="hidden" name="order[{{ $i }}][Units]" value="">
<input type="hidden" name="order[{{ $i }}][Weight]" value="">
<input type="hidden" name="order[{{ $i }}][Height]" value="">
<input type="hidden" name="order[{{ $i }}][Width]" value="">
<input type="hidden" name="order[{{ $i }}][Depth]" value="">
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

