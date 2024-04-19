<?php
include_once("app/views/share/header.php");
?>
<?php

if (isset($errors)) {
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li class='text-danger'>". $error ."</li>";
    }
    echo "</ul>";
}
?>

<div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Sửa Người dùng</h1>
                            </div>
                            <form class="user" action="/chieu2/account/save" method="post" >
                            <input type="hidden" value="<?=$userInfo['id']?>" name="id">   
                            <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" value="<?=$userInfo['name']?>" name="name" placeholder="Nhập tên người dùng">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" value="<?=$userInfo['email']?>" name="email" placeholder="Nhập email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" value="<?=$userInfo['role']?>" name="role" placeholder="Cấp quyền">
                                </div>
                               
                              
                            <hr>
                              
                                <button class="btn btn-primary btn-user btn-block">
                                    Lưu chỉnh sửa
                                </button>
                            
                            </form>
                          
                        </div>
                    </div>
                </div>

<?php
include_once("app/views/share/footer.php");
?>