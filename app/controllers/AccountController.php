<?php
class AccountController
{
    private $accountModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }
    public function login()
    {
        include_once 'app/views/account/authentication.php';
    }
    public function logout()
    {
        $this->accountModel->logout();
        include_once 'app/views/account/authentication.php';
    }
    public function register()
    {
        include_once 'app/views/account/authentication.php';
    }
    public function debug_to_console($data)
    {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);

        echo "<script>console.log('" . $output . "' );</script>";
    }

    public function readAll()
    {
        if (!Auth::isLoggedIn()) {
            header('Location:/chieu2/account/login');
        } else {
            if (Auth::isAdmin()) {
                $users = $this->accountModel->readAll();
                include_once 'app/views/account/index.php';
            } else {
                header('Location:/chieu2/account/login');
            }
        }
    }
    public function checkLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $plainTextPassword = $_POST['password'] ?? '';

            if ($username == '' || $plainTextPassword == '') {
                $errors['nulldata'] = 'Vui lòng nhập điền đủ dữ liệu!';
                include 'app/views/account/authentication.php';
            } else {
                $isLoginSuccess = $this->accountModel->login($username, $plainTextPassword);
                if ($isLoginSuccess !=null) {
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $isLoginSuccess;
                    
                    header('Location: /chieu2');
                } else {
                    $errors['denied'] = 'Sai tài khoản hoặc mật khẩu';
                    include 'app/views/account/authentication.php';
                }
            }
        }
    }
    public function edit($id){
       $userInfo = $this->accountModel->get_user_info($id);
       if(empty($userInfo)){
        include_once 'app/views/share/not-found.php';
        }else{
            include_once 'app/views/account/edit.php';
        }
    }
    public function delete($id){
        $userInfo = $this->accountModel->get_user_info($id);
        if(empty($userInfo)){
         include_once 'app/views/share/not-found.php';
         }else{
            $result = $this->accountModel->deleteUserById($id);
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
     public function save(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $role = $_POST['role'] ?? '';
            $email = $_POST['email'] ?? '';

            if(isset($_POST['id'])){
                //update
                $id = $_POST['id'];                
            }

                $result = $this->accountModel->updateUser($id, $name, $email, $role);

            if (is_array($result)) {
                // Có lỗi, hiển thị lại form với thông báo lỗi
                $errors = $result;
                include 'app/views/product/add.php';
            } else {
                // Không có lỗi, chuyển hướng ve trang chu hoac trang danh sach
                header('Location: /chieu2/account/readAll');
            }
        }
    }
}
