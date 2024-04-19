<?php
class OrdersController
{
    private $orderModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->orderModel = new OrderModel($this->db);
    }
    public function readAll()
    {
        if (!Auth::isLoggedIn() && !Auth::isAdmin()) {
            header('Location:/chieu2/account/login');
        } else {

            $orders = $this->orderModel->readAll();
            include_once 'app/views/orders/index.php';
        }
    }
}
