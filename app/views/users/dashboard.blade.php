@extends('layouts.main')

@section('content')
<div class="page-header">
  <div class="icon">
    <span class="ico-arrow-right"></span>
  </div>
  <h1>Fetch Your Inventory From Server <small>You can only fetch your items from inventory by date And the date should not exceed 120 days limit</small></h1>
</div><!-- end .page-header -->


<div class="row-fluid">
	<div class="row-form">
		<div class="span12">
			<div class="block">
				<div class="head blue">
					
					<h5><span class="icon ico-pen-2"></span> Overview Of Previous Month</h5>
				</div>
				<div class="data-fluid">
					<table class="table">
						<thead>
							<tr>
								<th>Total Orders</th>
								<th>Pending Orders</th>
								<th>Completed Orders</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>{{ $totalOrders }}</td>
								<td><span class="label label-important">{{ $incomplete }}</span></td>
								<td><span class="label label-success">{{ $completed }}</span></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row-form">
		<div class="span12">
			<div id="container"></div>
		</div>
	</div>
</div>
<hr>

{{ Form::open(array('action' => 'UsersController@getDashboard', 'method' => 'get')) }}
<div class="row-fluid">
	<div class="row-form">
		<div class="span12">
			<h1>Fetch Your Inventory From Server</h1>
		</div>
	</div>
	<div class="row-form"><!-- new form row -->
			<div class="span6"><!-- new form column -->
				<span class="top">Fetch Items From</span><!-- top text line -->
				<input name="from" type="text" class="datepicker" value="2014-04-01" />
				<span class="bottom">Don't exceed limit of 120 days</span><!-- bottom text line -->                
			</div>
			<div class="span6"><!-- new form column -->
				<span class="top">To</span><!-- top text line -->
				<input name="to" type="text" class="datepicker" value="2014-06-29" />       
			</div>
	</div>
	<div class="row-form">
		<div class="span12">
			{{ Form::button('<span class="icon-book icon-white"></span> Fetch Now', array('type'=>'submit','class'=>'btn btn-large btn-block btn-success')) }}
		</div>
	</div>
</div>
{{ Form::close() }}
@if($hasitems)
	<?php //$itemarr = (array) $items->ItemArray; ?>

	<!--pre>
	    {{-- print_r( array_chunk((array) $itemarr["Item"],3) ) --}}
	</pre-->

	<div class="row-fluid content">
		<div class="block-fluid">
				<div class="row-form">
						<div class="span12">
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
						</div>
				</div>
		</div>
	</div>
	<div class="row-fluid content">
		<div class="block-fluid">
			@foreach(array_chunk($items ,3) as $pitems)
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
												<li>
													{{ Form::open(array('url'=>'items')) }}
													{{ Form::hidden('source', 'ebay') }}
													{{ Form::hidden('source_id', $item->ItemID) }}
													{{ Form::hidden('name', $item->Title) }}
													{{ Form::hidden('description', 'Not Provided') }}
													{{ Form::hidden('imageurl', $item->PictureDetails->PictureURL) }}
													{{ Form::button('<span class="ico-plus icon-white"></span> Add to Your Store', array('type'=>'submit','class'=>'btn btn-mini btn-primary')) }}
													{{ Form::close() }}
												</li>
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
@endif
@stop


@section('scripts')
$(function () {
    // Set up the chart
    var chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container',
            type: 'column',
            margin: 75,
            options3d: {
                enabled: true,
                alpha: 5,
                beta: 15,
                depth: 50,
                viewDistance: 25
            }
        },
        title: {
            text: 'Displaying Items Soled in Last Month'
        },
        plotOptions: {
            column: {
                depth: 25
            }
        },
        series: {{ $series }}
    });
});
@stop