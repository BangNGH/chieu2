<?php
class ProductModel
{
    private $conn;
    private $table_name = "products";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function readAll()
    {
        $query = "SELECT id, name, description, price, image FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    function get_product_info($product_id)
    {
        $stmt = $this->conn->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute(array('id' => $product_id));
        $productInfo = $stmt->fetch(PDO::FETCH_ASSOC);

        return $productInfo;
    }

    function createProduct($name, $description, $price, $uploadResult)
    {
        // Kiểm tra ràng buộc đầu vào
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }
        if (empty($description)) {
            $errors['description'] = 'Mô tả không được để trống';
        }
        if (!is_numeric($price) || $price < 0) {
            $errors['price'] = 'Giá sản phẩm không hợp lệ';
        }

        if ($uploadResult == false) {
            $errors['image'] = 'Vui lòng chọn hình ảnh hợp lệ!';
        }

        if (count($errors) > 0) {
            return $errors;
        }

        // Truy vấn tạo sản phẩm mới

        $query = "INSERT INTO " . $this->table_name . " (name, description, price, image) VALUES (:name, :description, :price, :image)";
        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));

        // Gán dữ liệu vào câu lệnh
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $uploadResult);

        // Thực thi câu lệnh
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    function updateProduct($id, $name, $description, $price, $uploadResult)
    {
        if ($uploadResult) {
            $query = "UPDATE " . $this->table_name . " SET name=:name, description=:description, price=:price, image=:image WHERE id=:id";
        } else {
            $query = "UPDATE " . $this->table_name . " SET name=:name, description=:description, price=:price WHERE id=:id";
        }
        $stmt = $this->conn->prepare($query);
        // Làm sạch dữ liệu
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        // Gán dữ liệu vào câu lệnh
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        if ($uploadResult) {
            $stmt->bindParam(':image', $uploadResult);
        }
        // Thực thi câu lệnh
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getProductById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " where id = $id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    public function deleteProductById($id)
    {
        $query = "DELETE FROM " . $this->table_name . " where id = $id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    public function deleteFromCartById($id)
    {

        if (isset($_SESSION['shoppingcart'][$id])) {
            unset($_SESSION['shoppingcart'][$id]);
        }
    }
    public function checkout($checkout, $address, $note, $name, $phone)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (empty($_SESSION['shoppingcart'])) {
            echo "Giỏ hàng của bạn đang trống. Không thể thanh toán.";
            return;
        }

        $this->conn->beginTransaction();

        try {

            $username = $_SESSION['username'];
            $usernameId = $this->getIdUser($username);
            $total_amount = 0;

            foreach ($_SESSION['shoppingcart'] as $product_id => $quantity) {
                $productInfo = $this->get_product_info($product_id);
                $total_amount += $productInfo['price'] * $quantity;
            }

            $stmt = $this->conn->prepare('INSERT INTO orders (account_id,total_amount, is_paid, address, phone, name, note) VALUES (?, ?, false, ?, ?,?,?)');
            $stmt->execute([$usernameId, $total_amount, $address, $phone, $name, $note]);
            $order_id = $this->conn->lastInsertId();

            foreach ($_SESSION['shoppingcart'] as $product_id => $quantity) {
                $productInfo = $this->get_product_info($product_id);
                $price = $productInfo['price'];
                $stmt = $this->conn->prepare('INSERT INTO orderdetails (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
                $stmt->execute([$order_id, $product_id, $quantity, $price]);
            }

            $this->conn->commit();

            unset($_SESSION['shoppingcart']);
            if ($checkout == "true") {
                $stmt = $this->conn->prepare('UPDATE orders set is_paid =true where order_id=? ');
                $stmt->execute([$order_id]);
                $this->conn->lastInsertId();
                header("Location: /chieu2/app/vnpay_php/vnpay_pay.php?total_amount=$total_amount");
            }else      
                header("Location: /chieu2?success=". $order_id);
        } catch (PDOException $e) {
            $this->conn->rollBack();
            echo "Lỗi trong quá trình thanh toán: " . $e->getMessage();
        }
    }
    function getIdUser($username)
    {

        $stmt = $this->conn->prepare('SELECT id FROM account WHERE email = ?');
        $stmt->execute([$username]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $row['id'];
        } else {
            return null;
        }
    }
}
