@extends('layouts.main')

@section('content')
{{ HTML::script('js/plugins/datatables/jquery.dataTables.min.js') }}
<div class="page-header">
  <div class="icon">
    <span class="ico-arrow-right"></span>
  </div>
  <h1>Codes Page <small>Your Codes</small></h1>
</div><!-- end .page-header -->
@if(Session::get('recentexcel'))
<div class="row-fluid">
  <div class="span12">
      <div class="left tar">
        <a class="btn btn-large btn-block btn-success" href="excel/{{ Session::get('recentexcel')['file'] }}"><span class="icon-download-alt icon-white"></span>Download Excel files</a>
      </div>
  </div>
</div>
@endif
<div class="row-fluid">
  <div class="span12">
    <div class="block">
      <div class="head dblue">                                
            
          <h2>UPC-A CODES</h2>
      </div>
      <table width="100%" class="table fpTable"><!-- set .lcnp to remove padding from last column-->
        <thead>
          <tr>
            <th>Code</th>
            <th>Created Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($codes as $code)
            <tr>
              <td>{{ $code['code'] }}</td>        
              <td>{{ $code['created_at'] }}</td>        
              <td>Monitoring</td>        
            </tr>
          @endforeach 
        </tbody>
      </table>
    </div><!-- .block -->
  </div><!-- .span12 -->
</div><!-- .row-fluid -->
@stop