@extends('layouts.main')

@section('content')
<div class="page-header">
  <div class="icon">
    <span class="ico-arrow-right"></span>
  </div>
  <h1>Orders/Transaction Page<small>Generate New orders</small></h1>
</div><!-- end .page-header -->
<?php $trans = (array) $orders->TransactionArray; ?>
<!--pre>
    {{ print_r( $trans["Transaction"] ) }}
</pre-->

<div class="row-fluid content">
	<div class="block-fluid">
		@foreach(array_chunk($trans["Transaction"],4) as $porders)
			<div class="row-form">
				@foreach($porders as $order)
					
					<div class="span3">
						<div class="block" id="sWidget_2" style="position: relative;">
								<div class="head dblue">
						    	<h6>TransactionID: {{ $order->TransactionID }}</h6>
								</div>
								<div class="data dark">
									AmountPaid: {{ $order->AmountPaid }}<br>
									QuantityPurchased: {{ $order->QuantityPurchased }}<br>
									<a href="#{{ $order->TransactionID }}" role="button" class="btn" data-toggle="modal">OverView</a>
								</div>
						</div>
					</div>
					<div id="{{ $order->TransactionID }}" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">{{ $order->TransactionID }}</h3>
            </div>
            <div class="modal-body">
                QuantityPurchased: {{ $order->QuantityPurchased }}<br>
                Name: {{ $order->Buyer->BuyerInfo->ShippingAddress->Name }}<br>
                CityName: {{ $order->Buyer->BuyerInfo->ShippingAddress->CityName }}<br>
               	Country: {{ $order->Buyer->BuyerInfo->ShippingAddress->Country }}<br>
               	CountryName: {{ $order->Buyer->BuyerInfo->ShippingAddress->CountryName }}<br>
               	Phone: {{ $order->Buyer->BuyerInfo->ShippingAddress->Phone }}<br>
               	PostalCode: {{ $order->Buyer->BuyerInfo->ShippingAddress->PostalCode }}<br>
               	StateOrProvince: {{ $order->Buyer->BuyerInfo->ShippingAddress->StateOrProvince }}<br>
               	Street1: {{ $order->Buyer->BuyerInfo->ShippingAddress->Street1 }}<br>
               	Street2: {{ $order->Buyer->BuyerInfo->ShippingAddress->Street2 }}<br>
            </div>
        </div>
				@endforeach
			</div>
		@endforeach
	</div><!-- .block-fluid -->
</div><!-- .content -->
@stop
