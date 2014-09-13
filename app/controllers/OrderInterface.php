<?php

interface OrdersRepositoryInterface{

	public function setService($config);
	public function showConfig();
	public function ordersByAttributes($from);
	public function itemsByOrdreId($orderId);
	public function productsByIds($codes);
}