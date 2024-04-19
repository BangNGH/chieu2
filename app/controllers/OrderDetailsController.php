<?php

class orderdetailsController {
    private $orderDetailsModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->orderDetailsModel = new OrderDetailsModel($this->db);
    }
    public function readAll()
    {
        if (!Auth::isLoggedIn() && !Auth::isAdmin()) {
            header('Location:/chieu2/account/login');
        } else {
          
                $orderDetails = $this->orderDetailsModel->readAll();
                include_once 'app/views/orders/OrderDetails.php';
           
        }
    }
  
}