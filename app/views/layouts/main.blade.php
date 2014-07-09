<!DOCTYPE html>
<html lang="en">
<head>    
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />  
  <!--[if gt IE 8]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />    
  <![endif]-->        
  <title>xamstore</title>
  <link rel="icon" type="image/ico" href="favicon.ico"/>
  
  {{ HTML::style('css/stylesheets.css') }}
  <!--[if lte IE 7]>
    {{ HTML::style('css/ie.css') }}
    {{ HTML::script('js/plugins/other/lte-ie7.js') }}
  <![endif]-->
    <script type='text/javascript' src='js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'></script>    
    
  
  {{ HTML::script('js/plugins/jquery/jquery-1.9.1.min.js') }}
  {{ HTML::script('js/plugins/jquery/jquery-ui-1.10.1.custom.min.js') }}
  {{ HTML::script('js/plugins/jquery/jquery-migrate-1.1.1.min.js') }}
  {{ HTML::script('js/plugins/jquery/globalize.js') }}
  {{ HTML::script('js/plugins/other/excanvas.js') }}

  {{ HTML::script('js/plugins/other/jquery.mousewheel.min.js') }}

  {{ HTML::script('js/plugins/bootstrap/bootstrap.min.js') }}

  {{ HTML::script('js/plugins/cookies/jquery.cookies.2.2.0.min.js') }}

  {{ HTML::script('js/plugins/uniform/jquery.uniform.min.js') }}
  {{ HTML::script('js/plugins/select/select2.min.js') }}
  {{ HTML::script('js/plugins/tagsinput/jquery.tagsinput.min.js') }}
  {{ HTML::script('js/plugins/maskedinput/jquery.maskedinput-1.3.min.js') }}
  {{ HTML::script('js/plugins/multiselect/jquery.multi-select.min.js') }}

  {{ HTML::script('js/plugins/jflot/jquery.flot.js') }}
  {{ HTML::script('js/plugins/jflot/jquery.flot.stack.js') }}
  {{ HTML::script('js/plugins/jflot/jquery.flot.pie.js') }}
  {{ HTML::script('js/plugins/jflot/jquery.flot.resize.js') }}

  {{ HTML::script('js/plugins/epiechart/jquery.easy-pie-chart.js') }}
  {{ HTML::script('js/plugins/sparklines/jquery.sparkline.min.js') }}

  {{ HTML::script('js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js') }}

  {{ HTML::script('js/plugins/uniform/jquery.uniform.min.js') }}

  {{ HTML::script('js/plugins/fullcalendar/fullcalendar.min.js') }}

  {{ HTML::script('js/plugins/shbrush/XRegExp.js') }}
  {{ HTML::script('js/plugins/shbrush/shCore.js') }}
  {{ HTML::script('js/plugins/shbrush/shBrushXml.js') }}
  {{ HTML::script('js/plugins/shbrush/shBrushJScript.js') }}
  {{ HTML::script('js/plugins/shbrush/shBrushCss.js') }}
  
  {{ HTML::script('js/plugins/fancybox/jquery.fancybox.pack.js') }}
  {{ HTML::script('js/plugins.js') }}
  <!-- HighCharts -->
  {{ HTML::script('js/highcharts.js') }}
  {{ HTML::script('js/highcharts-3d.js') }}
  {{ HTML::script('js/modules/exporting.js') }}
  
  {{ HTML::script('js/actions.js') }}
</head>
<body>  
  <div id="loader">{{ HTML::image('img/loader.gif','Loader gif') }}</div>
  <div class="wrapper">
    
    <div class="sidebar">
      
      <div class="top">
        <a href="index.html" class="logo"></a>
        <div class="search">
          <div class="input-prepend">
            <span class="add-on orange"><span class="icon-search icon-white"></span></span>
            <input type="text" placeholder="search..."/>                            
          </div>      
        </div>
      </div>
      <div class="nContainer">
        <ul class="navigation">     
          <li>
            <a href="#" class="blyellow">Codes Generator</a>
            <div class="open"></div>
            <ul>
              <li>{{ HTML::linkAction('CodesController@index','View All Codes') }}</li>
              <li>{{ HTML::linkAction('CodesController@create','Create New Codes') }}</li>
            </ul>
          </li>
          <li>{{ HTML::link('users/login', 'Login',array('class'=>'blred')) }}</li>       
          <!-- <li class="active"><a href="index.html" class="blblue">Dashboard</a></li>
          <li>
            <a href="#" class="blgreen">Forms Stuff</a>
            <div class="open"></div>
            <ul>
              <li><a href="forms.html">Form Elements</a></li>
              <li><a href="validation.html">Validation</a></li>
              <li><a href="grid.html">Grid</a></li>
              <li><a href="editor.html">Editors</a></li>  
              <li><a href="wizard.html">Wizard</a></li>
            </ul>
          </li>
          <li>
            <a href="#" class="bldblue">Tables</a>
            <div class="open"></div>
            <ul>
              <li><a href="tables.html">Simple</a></li>
              <li><a href="tables_dynamic.html">Dynamic</a></li>          
            </ul>
          </li>
          <li>
            <a href="#" class="blpurple">Samples</a>
            <div class="open"></div>
            <ul>
              <li><a href="faq.html">FAQ</a></li>
              <li><a href="invoice.html">Invoice</a></li>
              <li><a href="mailbox.html">Mailbox</a></li>
              <li><a href="login.html">Login</a></li>
            </ul>          
          </li>
          <li>
            <a href="#" class="blorange">Other</a>
            <div class="open"></div>
            <ul>
              <li><a href="files.html">File handling</a></li>
              <li><a href="images.html">Images</a></li>
              <li><a href="typography.html">Typography</a></li>
              <li><a href="404.html">Error 404</a></li>
            </ul>
          </li> -->
        </ul>
        <a class="close">
          <span class="ico-remove"></span>
        </a>
      </div>
      <div class="widget">
        <div class="datepicker"></div>
      </div>
      
    </div>
    
    <div class="body">
      
      <ul class="navigation">
        <li>
          <a href="#" class="button yellow">
            <div class="arrow"></div>
            <div class="icon">
              <span class="ico-layout-7"></span>
            </div>          
            <div class="name">Code Generator</div>
          </a>      
          <ul class="sub">
            <li>{{ HTML::linkAction('CodesController@index','View All Codes') }}</li>
            <li>{{ HTML::linkAction('CodesController@create','Create New Codes') }}</li>
          </ul>
        </li>        
        <li>
          <a href="{{URL::to('/users/dashboard')}}" class="button orange">
            <div class="icon">
              <span class="ico-cloud"></span>
            </div>          
            <div class="name">Dashboard</div>
          </a>                    
        </li>        
        <li>
          <div class="user">
            <img src="img/examples/users/dmitry_m.jpg" align="left"/>
            <a href="#" class="name">
              <span>Dmitry Ivaniuk</span>
              <span class="sm">Administrator</span>
            </a>
          </div>
          <div class="buttons">
            <div class="sbutton green navButton">
              <a href="#"><span class="ico-align-justify"></span></a>
            </div>
            <div class="sbutton blue">
              <a href="#"><span class="ico-cogs"></span></a>
              <div class="popup">
                <div class="arrow"></div>
                <div class="row-fluid">
                  <div class="row-form">
                    <div class="span12"><strong>SETTINGS</strong></div>
                  </div>                  
                  <div class="row-form">
                    <div class="span4">Navigation:</div>
                    <div class="span8"><input type="radio" class="cNav" name="cNavButton" value="default"/> Default <input type="radio" class="cNav" name="cNavButton" value="bordered"/> Bordered</div>
                  </div>                  
                  <div class="row-form">
                    <div class="span4">Content:</div>
                    <div class="span8"><input type="radio" class="cCont" name="cContent" value=""/> Responsive <input type="radio" class="cCont" name="cContent" value="fixed"/> Fixed</div>
                  </div>                  
                </div>
              </div>
            </div>            
          </div>
        </li>        
      </ul>
      
      
      <div class="content">
        @yield('content')
      </div><!-- end content -->
      
    </div>
    
  </div>
  
  <div class="dialog" id="source" style="display: none;" title="Source"></div>
  
  <div id="fcAddEvent" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="fcAddEventLabel" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="fcAddEventLabel">Add new event</h3>
    </div>
    <div class="modal-body">
      <div class="row-fluid">
        <div class="span3">Title:</div>
        <div class="span9"><input type="text" id="fcAddEventTitle"/></div>
      </div>
    </div>
    <div class="modal-footer">      
      <button class="btn btn-primary" id="fcAddEventButton">Add</button>      
    </div>
  </div>
  <script type="text/javascript">
    @yield('scripts')
  </script>
</body>
</html>
