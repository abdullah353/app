<?php
Class OrderRepository implements OrdersRepositoryInterface{

	private $config;
	private $url;
	private $keys;
	private $server;
	public function setService($config){
		$this->config = $config;
		if($this->config["host"] == "amazon"){
			if(isset($this->config["SecretKey"])){
				$this->server = new AmazonMws(
					 $config["SellerId"]
					,$config["MarketplaceId"]
					,$config["AWSAccessKeyId"]
					,$config["SecretKey"]
					,$config["url"]
				);
			}else{
				$this->server = new AmazonMws(
					 $config["SellerId"]
					,$config["MarketplaceId"]
				);
			}
		}elseif($this->config["host"] == "ebay"){

		}
	}
	public function ordersByAttributes($from,$status = "Unshipped"){
		return $this->server->ListOrders($from,$status);
	}
	public function itemsByOrdreId($orderId){
		return $this->server->ListOrderItems($orderId);
	}
	public function productsByIds($codes){
		return $this->server->productsByIds($codes);
	}
	public function showConfig(){
		return json_encode($this->config);
	}
}