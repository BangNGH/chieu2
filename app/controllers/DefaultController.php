<?php
class DefaultController
{
    
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
     
    }
    public function Index()
    {
        if (!Auth::isLoggedIn()) {
            header('Location:/chieu2/account/login');
        } else {
            if (Auth::isAdmin()) {
                $products = $this->productModel->readAll();
                include_once 'app/views/share/index.php';
            } else {
                $products = $this->productModel->readAll();
                include_once 'app/views/share/shopping.php';
            }
        }
    }
 
}
