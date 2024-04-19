<?php
class ProductController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }
    public function listProducts()
    {

        $stmt = $this->productModel->readAll();

        include_once 'app/views/product_list.php';
    }
    public function add()
    {
        include_once 'app/views/product/add.php';
    }

    public function edit($id)
    {
        $product = $this->productModel->getProductById($id);
        if (empty($product)) {
            include_once 'app/views/share/not-found.php';
        } else {
            include_once 'app/views/product/edit.php';
        }
    }
    public function delete($id)
    {
        $product = $this->productModel->getProductById($id);
        if (empty($product)) {
            include_once 'app/views/share/not-found.php';
        } else {
            $result = $product = $this->productModel->deleteProductById($id);
            if (is_array($result)) {
                // Có lỗi, hiển thị lại form với thông báo lỗi
                $errors = $result;
                include 'app/views/share/index.php';
            } else {
                // Không có lỗi, chuyển hướng ve trang chu hoac trang danh sach
                header('Location: /chieu2?delete=true');
            }
        }
    }
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';

            if (isset($_POST['id'])) {
                //update
                $id = $_POST['id'];
            }

            $uploadResult = false;
            //kiểm tra để lưu hình ảnh
            if (!empty($_FILES["image"]['size'])) {
                //luu hinh
                $uploadResult = $this->uploadImage($_FILES["image"]);
            }

            //lưu sản phẩm
            if (!isset($id))
                $result = $this->productModel->createProduct($name, $description, $price, $uploadResult);
            else
                $result = $this->productModel->updateProduct($id, $name, $description, $price, $uploadResult);

            if (is_array($result)) {
                // Có lỗi, hiển thị lại form với thông báo lỗi
                $errors = $result;
                include 'app/views/product/add.php';
            } else {
                // Không có lỗi, chuyển hướng ve trang chu hoac trang danh sach
                header('Location: /chieu2');
            }
        }
    }
    public function addtocart()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = $_POST['product'] ?? '';

            if ($product_id == '') {
            } else {
                if (!isset($_SESSION)) {
                    session_start();
                }
                if (!isset($_SESSION['shoppingcart'])) {
                    $_SESSION['shoppingcart'] = array();
                }

                if (isset($_POST['add_to_cart'])) {
                    if (isset($_SESSION['shoppingcart'][$product_id])) {
                        $_SESSION['shoppingcart'][$product_id]++;
                    } else {
                        $_SESSION['shoppingcart'][$product_id] = 1;
                    }
                }
            }
        }
        header('Location: /chieu2/product/cart');
    }
    public function deleteitem($id)
    {
        $product = $this->productModel->getProductById($id);
        if (empty($product)) {
            include_once 'app/views/share/not-found.php';
        } else {
            $this->productModel->deleteFromCartById($id);

            header('Location: /chieu2/product/cart?delete=true');
        }
    }

    public function cart()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $cartContent = $_SESSION['shoppingcart'] ?? array();

        $productInfos = array();

        foreach ($cartContent as $productId => $quantity) {
            $productInfo = $this->productModel->get_product_info($productId);
            $productInfos[] = array(
                'id' => $productId,
                'name' => $productInfo['name'],
                'image' => $productInfo['image'],
                'price' => $productInfo['price'],
                'quantity' => $quantity
            );
        }
        include_once 'app/views/account/cart.php';
    }




    public function uploadImage($file)
    {
        $targetDirectory = "uploads/";
        $targetFile = $targetDirectory . basename($file["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Kiểm tra xem file có phải là hình ảnh thực sự hay không
        $check = getimagesize($file["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        // Kiểm tra kích thước file
        if ($file["size"] > 500000) { // Ví dụ: giới hạn 500KB
            $uploadOk = 0;
        }

        // Kiểm tra định dạng file
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $uploadOk = 0;
        }

        // Kiểm tra nếu $uploadOk bằng 0
        if ($uploadOk == 0) {
            return false;
        } else {
            if (move_uploaded_file($file["tmp_name"], $targetFile)) {
                return $targetFile;
            } else {
                return false;
            }
        }
    }

    public function checkout()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $address = $_POST['address'] ?? '';
        $note = $_POST['note'] ?? '';
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $checkout = $_POST['checkout'];

        if (empty($_SESSION['shoppingcart'])) {
            echo "Giỏ hàng của bạn đang trống. Không thể thanh toán.";
            return;
        }
        if (isset($checkout)) {
            $this->productModel->checkout($checkout, $address, $note, $name, $phone);
        }
        $this->productModel->checkout($checkout, $address, $note, $name, $phone);
    }



    public function updateCart()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $productId = $_POST['productId'];
        $quantity = $_POST['quantity'];

        $cartContent = $_SESSION['shoppingcart'] ?? array();

        if (isset($cartContent[$productId])) {
            if ($quantity <= 0) {
                unset($cartContent[$productId]);
            } else {
                $cartContent[$productId] = $quantity;
            }
        }

        $_SESSION['shoppingcart'] = $cartContent;

     
    }
    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
    
        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }
}
