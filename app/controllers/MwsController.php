<?php

class MwsController extends \BaseController {
  
  protected $service;
  private $mode;
  public function __construct(OrdersRepositoryInterface $OrdersRepoInterface) {
    $this->mode = "";
    $this->beforeFilter('csrf', array('on'=>'post'));
    $this->beforeFilter('auth');
    $this->service = $OrdersRepoInterface;
  }

  public function getIndex(){
    
    $resp = null;
    $orders = null;
    $orderlist = array();
    $orderitems = array();
    $products = array();
    $token = null;
    $accountid = Input::get('account');
    $status = Input::get('status');
    if($origDate = Input::get('from')){
      //Congiuring Account to use
      $configgured = $this->service->setService($this->keys($accountid));

      if($this->mode == "developer"){
        echo "<h1>Current Configuration Response</h1>";
        echo "<span>Class Name ".get_class($this)."</span>";
        echo "<pre>";
        print_r($this->service->showConfig());
        echo "</pre>";
      }
      //Getting Orders BY attributes
      $orderlist = $this->service->ordersByAttributes($origDate,$status);

      if($this->mode == "developer"){
        echo "<h1>Orders By Attrutes</h1>";
        echo "<span>Class Name ".get_class($this)."</span>";
        echo "<pre>";
        print_r($orderlist);
        echo "</pre>";
      }
      array_splice($orderlist, 25);
      $orderitems = array();
      $allasin     = array();
      $allasin     = array();
      $asinlist   = array();
      
      if($orderlist != null){
        foreach (array_chunk($orderlist, 10) as $orderlistchunk) {
          foreach ($orderlistchunk as $order) {
            $xmlresp = $this->service->itemsByOrdreId($order->AmazonOrderId);
            if($this->mode == "developer"){
              echo "<h1>Item By Order Id $order->AmazonOrderId</h1>";
              echo "<span>Class Name ".get_class($this)."</span>";
              echo "<pre>";
              print_r($xmlresp);
              echo "</pre>";
            }
            $resp = json_decode(json_encode($xmlresp), true);
            if(!isset($resp["ListOrderItemsResult"])){
              if($this->mode == "developer"){
                echo "<h1>ListOrderItemsResult Not Found in URL</h1>";
                echo "<span>Class Name ".get_class($this)."</span>";
                echo "<pre>";
                print_r($resp);
                echo "</pre>";
              }
              return;
            }
            $orderitems["".$order->AmazonOrderId] = $resp["ListOrderItemsResult"];
            foreach( $resp["ListOrderItemsResult"]["OrderItems"] as $orderitem ){
              if(isset($orderitem["ASIN"])){
                array_push($asinlist, $orderitem["ASIN"] );
              }else{
                foreach ($orderitem as $deeporderitem) {
                  array_push($asinlist, $deeporderitem["ASIN"] );
                }
              }
            }
          }
        }
        $uniqueasins = array_unique( $asinlist );
        foreach (array_chunk($uniqueasins, 10) as $asins) {
          $xmlresp = $this->service->productsByIds($asins);
          if($this->mode == "developer"){
            echo "<h1>Products By Ids  ".implode(',', $asins)."</h1>";
            echo "<span>Class Name ".get_class($this)."</span>";
            echo "<pre>";
            print_r($xmlresp);
            echo "</pre>";
          }
          foreach ($xmlresp as $matcheditem) {

            $currentasin = "";
            foreach ($matcheditem->attributes() as $key => $value) {
              if($key == "ASIN"){
                $currentasin = $value;
              }
            }
            if( isset( $matcheditem->Product ) ){
              $origsrc = $matcheditem->Product->AttributeSets->children('ns2', true)->ItemAttributes->SmallImage->URL;
              if( isset($origsrc) && !empty($origsrc) ){
                $image = preg_replace("/._(.*)?_./", ".", $origsrc);
                $smallimage = $origsrc;
                $height = (string) $matcheditem->Product->AttributeSets->children('ns2', true)->ItemAttributes->PackageDimensions->Height;
                $width = (string) $matcheditem->Product->AttributeSets->children('ns2', true)->ItemAttributes->PackageDimensions->Width;
                $length = (string) $matcheditem->Product->AttributeSets->children('ns2', true)->ItemAttributes->PackageDimensions->Length;
                $weight = (string) $matcheditem->Product->AttributeSets->children('ns2', true)->ItemAttributes->PackageDimensions->Weight;
              }else{
                $image = "http://dummyimage.com/450x250/000/fff&text=".$currentasin;
                $smallimage = "http://dummyimage.com/75x75/000/fff&text=".$currentasin;
                $height = "";
                $width = "";
                $length = "";
                $weight = ""; 
              }

              $products["".$currentasin] = array(
                       "image"  => $image
                      ,"smallimage" => $smallimage
                      ,"height" => $height
                      ,"width"  => $width
                      ,"length" => $length
                      ,"weight" => $weight
              );
            }else{
                $image = "http://dummyimage.com/450x250/fff/000&text=".$currentasin;
                $smallimage = "http://dummyimage.com/75x75/fff/000&text=".$currentasin;
                $height = "";
                $width = "";
                $length = "";
                $weight = ""; 
              $products["".$currentasin] = array(
                 "image"  => $image
                ,"smallimage" => $smallimage
                ,"height" => $height
                ,"width"  => $width
                ,"length" => $length
                ,"weight" => $weight
              );
            }
          }
        }
      }
      Session::put('orders', json_encode($orderlist, true));
      Session::put('orderitems',json_encode($orderitems, true));
      Session::put('products',json_encode($products, true));
    }
    return View::make('mws.index')
      ->with('orders',$orderlist)
      ->with('token',$token)
      ->with('orderitems',$orderitems)
      ->with('products',$products)
      ->withInput(Input::all());
  }


  public function postExcel(){
    $boxc = ( Input::get('boxc') != null )? Input::get('boxc'): array();
    $pfc = ( Input::get('pfc') != null )? Input::get('pfc'): array();
    
    //Validation
    if(!empty($boxc) && !empty($pfc)){
      $dublicate = array_intersect($boxc,$pfc);
    }
    if(!empty($dublicate)) return "You Have Selected This Order for both pfc and boxc Hit Back From Top and fix it ".json_encode($dublicate);
    
    $mainRepo = $this->generateBoxc();
    $boxcCollection = array();
    $boxcFlatfile = array();
    $boxctemp = "";
    $pfcCollection = array();
    $pfcFlatFile = array();
    $pfctemp = "";
    $boxcfileurl = "";
    $boxcflaturl = "";
    $pfcfileurl = "";
    $pfcflaturl = "";

    foreach ($boxc as $key => $value) {
      array_push($boxcCollection,$mainRepo['boxc'][$value]);  
      array_push($boxcFlatfile,$mainRepo['flatfile'][$value]);
      $order = $mainRepo["word"][$value];
      $boxctemp .= '<tr>';
      $boxctemp .= "<td>".$order["OrderID"].'<p></p>'.$value."</td>";

      if(isset($order["image"])){
        $toarray = json_decode( json_encode($order["image"]) , true);
        $boxctemp .= "<td><img src=".$toarray[0]."></td>";
      }else{
        $boxctemp .= "<td>";
        foreach ($order["images"] as $image) {
          $toarray = json_decode( json_encode($image) , true);
          $boxctemp .= "<img src=".$toarray[0].">";
          $boxctemp .= "<p></p>";
        }
        $boxctemp .= "</td>";
      }
      $boxctemp .= "<td>".$order["Items"]."</td>";
      $boxctemp .= "<td>".$order["Name"]."</td>";
      $boxctemp .= "<td>".$order["Title"]."</td>";
      $boxctemp .= '</tr>';
    }
    foreach ($pfc as $key => $value) {
      array_push($pfcCollection, $mainRepo['pfc'][$value]);
      array_push($pfcFlatFile, $mainRepo['flatfile'][$value]);
      $order = $mainRepo["word"][$value];
      $pfctemp .= '<tr>';
      $pfctemp .= "<td>".$order["OrderID"].'<p></p>'.$value."</td>";

      if(isset($order["image"])){
        $toarray = json_decode( json_encode($order["image"]) , true);
        $pfctemp .= "<td><img src=".$toarray[0]."></td>";
      }else{
        $pfctemp .= "<td>";
        foreach ($order["images"] as $image) {
          $toarray = json_decode( json_encode($image) , true);
          $pfctemp .= "<img src=".$toarray[0].">";
          $pfctemp .= "<p></p>";
        }
        $pfctemp .= "</td>";
      }
      $pfctemp .= "<td>".$order["Items"]."</td>";
      $pfctemp .= "<td>".$order["Name"]."</td>";
      $pfctemp .= "<td>".$order["Title"]."</td>";
      $pfctemp .= '</tr>';
    }
  //Debugging
  if($this->mode == "developer"){
    echo "<h1>TABBER</h1>";
    echo "<pre>";
    print_r($mainRepo);
    echo "</pre>";

    
    echo "<h1>Input boxc</h1>";
    echo "<pre>";
    print_r($boxc);
    echo "</pre>";


    echo "<h1>BOXC boxCollection</h1>";
    echo "<pre>";
    print_r($boxcCollection);
    echo "</pre>";

    echo "<h1>BOXC Flatfile</h1>";
    echo "<pre>";
    print_r($boxcFlatfile);
    echo "</pre>";

    
    echo "<h1>Input pfc</h1>";
    echo "<pre>";
    print_r($pfc);
    echo "</pre>";
        
    echo "<h1>PFC pfcCollection</h1>";
    echo "<pre>";
    print_r($pfcCollection);
    echo "</pre>";
    echo "<h1>PFC Flatfile</h1>";
    echo "<pre>";
    print_r($pfcFlatFile);
    echo "</pre>";
  }//Debugging
    if(!empty($boxc)){
      $boxcfile = Excel::create( 'Boxc'.str_random(6), function($excel) use($boxcCollection){
        $excel->sheet('Boxc', function($sheet) use($boxcCollection){
          $sheet->fromArray($boxcCollection);
        });      
      })->store('csv', public_path().'/excel', true);
      $boxcfileurl = url('excel/'.$boxcfile['file'], $parameters = array(), $secure = null);

      $boxcflat = Excel::create( 'BoxcFlatFile'.str_random(6), function($excel) use($boxcFlatfile){
        $excel->sheet('shipmentconfirmation', function($sheet) use($boxcFlatfile){
          $sheet->fromArray($boxcFlatfile);
        });      
      })->store('csv', public_path().'/excel', true);
      $boxcflaturl = url('excel/'.$boxcflat['file'], $parameters = array(), $secure = null);
    }

    if(!empty($pfc)){
      $pfcfile = Excel::create( 'pfc'.str_random(6), function($excel) use($pfcCollection){
        $excel->sheet('pfc', function($sheet) use($pfcCollection){
          $sheet->fromArray($pfcCollection);
        });      
      })->store('xls', public_path().'/excel', true);    
      $pfcfileurl = url('excel/'.$pfcfile['file'], $parameters = array(), $secure = null);

      $pfcflat = Excel::create( 'PfcFlatFile'.str_random(6), function($excel) use($pfcFlatFile){
        $excel->sheet('shipmentconfirmation', function($sheet) use($pfcFlatFile){
          $sheet->fromArray($pfcFlatFile);
        });      
      })->store('xls', public_path().'/excel', true);
      $pfcflaturl = url('excel/'.$pfcflat['file'], $parameters = array(), $secure = null);
    }
    $headers = array(
      "Content-type"=>"text/html",
      "Content-Disposition"=>"attachment;Filename=myfile.doc"
    );
    $content  = '<html><head><meta charset="utf-8"></head><body style="font-size:10px;margin:0px;padding:0px;">';
    $content .= 'BOXC: '.$boxcfileurl;
    $content .= '<p></p>';
    $content .= 'BOXC-Flat: '.$boxcflaturl;
    $content .= '<p></p>';
    $content .= 'PFC: '.$pfcfileurl;
    $content .= '<p></p>';
    $content .= 'PFC-Flat: '.$pfcflaturl;
    $content .= '<p></p>';
    $content .= '<h4>Generated Time '.date('l jS \of F Y h:i:s A').'</h4>';
    if($boxctemp != ""){
      $content .= '<h2>BOXC ORDERS</h2>';
      $content .= "<table><thead><tr>";
      $content .= "<td>Orderid</td><td>image</td><td>QTY</td><td>Name</td><td>Title</td>";
      $content .= "</tr></thead><tbody>";
      $content .= $boxctemp;
      $content .= '</tbody></table>';
    }
    if($pfctemp != ""){
      $content .= '<h2>PFC ORDERS</h2>';
      $content .= "<table><thead><tr>";
      $content .= "<td>Orderid</td><td>image</td><td>QTY</td><td>Name</td><td>Title</td>";
      $content .= "</tr></thead><tbody>";
      $content .= $pfctemp;
      $content .= '</tbody></table>';
    }

    $content .= '</body></html>';

    return Response::make($content,200, $headers);
  }

  private function keys($id){
    $config = array(
      "1" => array(
       'host'=>"amazon"
      ,'SellerId' => "A013695218D3GFTNCCXMX"
      ,'MarketplaceId'=>"ATVPDKIKX0DER"
      )
      ,"2" => array(
       'host'=>"amazon"
      ,'SellerId' => "A1WBEEKNE10PYT"
      ,'MarketplaceId'=>"A2EUQ1WTGCTBG2"
      )
    );
    return $config[$id];
  }

  public function generateBoxc(){
    $boxcCollection = array();
    $boxc = array();
    $pfc= array();
    $flat = array();
    $wordDoc = array();
    $orderlist   = json_decode(Session::get('orders'));
    $orderitems = json_decode(Session::get('orderitems'));
    $products   = json_decode(Session::get('products'));
    foreach($orderlist as $el){
      $id = $el->AmazonOrderId;
      //Cart Single Item Or Multiple Items Checks
      foreach( $orderitems->$id->OrderItems as $orderitem ){
        $OrderedlistTitles = array();
        if(isset($orderitem->Title)){
          // Single Items Added in Cart
          $currentasin = $orderitem->ASIN;
          array_push($OrderedlistTitles,$orderitem->Title);
        }else{
          // Multiple Orders Added in Cart
          $currentasin = "";
          $asslinlist = array();
          $OrderedlistQTY = array();
          foreach( $orderitem as $suborderitem){
            array_push($asslinlist,$suborderitem->ASIN);
            array_push($OrderedlistQTY,$suborderitem->QuantityOrdered);
            array_push($OrderedlistTitles,$suborderitem->Title);
          }
        }
      }

      $orderitem = $orderitems->$id;
      $orders = array();
      $randOrder = str_random(8);
      $currentSinlgeASIN = ($currentasin != "")? $currentasin: $asslinlist[0];
      $boxc[$id]  = $this->fillboxc($randOrder,$el,$products,$orderitem,$currentSinlgeASIN);
      $pfc[$id]    = $this->fillpfc($randOrder,$el);
      $flat[$id] = $this->fillflatfile($randOrder,$el);
      if(!isset($OrderedlistQTY)){
        $wordDoc[$id] = $this->fillword($randOrder,$el,$currentasin,$products,$OrderedlistTitles);

      }else{
        $wordDoc[$id] = $this->fillword($randOrder,$el,$currentasin,$products,$OrderedlistTitles,$OrderedlistQTY,$asslinlist);
      }
    }//$el
    return array("boxc"=>$boxc,"pfc"=>$pfc,"flatfile"=>$flat,"word"=>$wordDoc);
  }

  public function fillboxc($randOrder,$el,$products,$orderitem,$ASIN){
    $boxc = array();
    $boxc["CompanyID"] = 838;
    $boxc["OrderID"] =  $randOrder ;
    $boxc["SKU"] = "";
    $boxc["Service"] = "";
    $boxc["Name"] =  $el->ShippingAddress->Name ;
    $boxc["Phone"] =  $el->ShippingAddress->Phone ;
    $boxc["Street1"] =  $el->ShippingAddress->AddressLine1 ;
    if(isset($el->ShippingAddress->AddressLine2)){
      $boxc["Street2"] =  $el->ShippingAddress->AddressLine2 ;
    }else{
      $boxc["Street2"] = "";
    }
    $boxc["City"] =  $el->ShippingAddress->City ;
    $boxc["State"] =  (isset($el->ShippingAddress->StateOrRegion))?$el->ShippingAddress->StateOrRegion: "";
    $boxc["PostalCode"] =  $el->ShippingAddress->PostalCode ;
    $boxc["Contents"] = "";
    $boxc["Items"] =  $el->NumberOfItemsUnshipped ;
    if(isset($orderitem->OrderItems->OrderItem->ItemPrice->Amount)){
      $boxc["Value"] =  $orderitem->OrderItems->OrderItem->ItemPrice->Amount;
    }else{
      $boxc["Value"] =  $orderitem->OrderItems->OrderItem[0]->ItemPrice->Amount;
    }
    $boxc["SignatureConfirmation"] = 0;
    $boxc["Units"] = "Metric";
    $boxc["Weight"] =  $products->$ASIN->weight;
    $boxc["Height"] =  $products->$ASIN->height;
    $boxc["Width"] =  $products->$ASIN->width;
    $boxc["Depth"] = 1;
    return $boxc;
  }

  public function fillpfc($randOrder,$el){
    return array(
       "Sales Record Number"   => $randOrder
      ,"Buyer Fullname"        => $el->ShippingAddress->Name
      ,"Buyer Company"         => ""
      ,"Buyer Address 1"       => $el->ShippingAddress->AddressLine1
      ,"Buyer Address 2"       => (isset($el->ShippingAddress->AddressLine2))?$el->ShippingAddress->AddressLine2:""
      ,"Buyer City"            => $el->ShippingAddress->City
      ,"Buyer State"           => (isset($el->ShippingAddress->StateOrRegion))?$el->ShippingAddress->StateOrRegion: ""
      ,"Buyer Zip"             => $el->ShippingAddress->PostalCode
      ,"Buyer Phone Number"    => $el->ShippingAddress->Phone
      ,"Buyer Country"         => $el->ShippingAddress->CountryCode
      ,"SKU"                   => ""
      ,"Description EN"        => ""
      ,"Description CN"        => ""
      ,"HS Code"               => ""
      ,"Quantity"              => ""
      ,"Sale Price"            => ""
      ,"Country of Manufacture"=> ""
      ,"Mark"                  => ""
      ,"weight"                => ""
      ,"Length"                => ""
      ,"Width"                 => ""
      ,"Height"                => ""
      ,"Shipping Service"      => ""
      ,"TrackingNo"            => ""
    );
  }
  public function fillflatfile($randOrder,$el){
    return array(
       "order-id"       => $el->AmazonOrderId
      ,"order-item-id"  => ""
      ,"quantity"       => ""
      ,"ship-date"      => date('Y-m-d')
      ,"carrier-code"   => ""
      ,"carrier-name"   => "usps"
      ,"tracking-number"=> $randOrder
      ,"ship-method"    => ""
    );
  }
  public function fillword($randOrder,$el,$currentasin,$products,$OrderedlistTitles,$OrderedlistQTY = array(),$asslinlist = array()){
    $wordDoc = array();
    $wordDoc["OrderID"] = $randOrder;
    if(empty($OrderedlistQTY)){
      $wordDoc["image"] = $products->$currentasin->smallimage;
      $wordDoc["Items"] = $el->NumberOfItemsUnshipped;
    }else{
      foreach( $asslinlist as $singleasin ){
        $wordDoc["images"][] = $products->$singleasin->smallimage;
      }
      $wordDoc["Items"] = implode('/',$OrderedlistQTY);
    }
    $wordDoc["Title"] = implode('<p></p>',$OrderedlistTitles);
    $wordDoc["Name"] =  $el->ShippingAddress->Name;
    return $wordDoc;
  }
}

