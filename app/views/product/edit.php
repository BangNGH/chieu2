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
                                <h1 class="h4 text-gray-900 mb-4">Sửa Sản Phẩm</h1>
                            </div>
                            <form class="user" action="/chieu2/product/save" method="post" enctype="multipart/form-data">
                            <input type="hidden" value="<?=$product->id?>" name="id">   
                            <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" value="<?=$product->name?>" name="name" placeholder="Nhập tên sản phẩm">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control form-control-user" value="<?=$product->price?>" name="price" placeholder="Nhập giá sản phẩm">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" value="<?=$product->description?>" name="description" placeholder="Nhập mô tả sản phẩm">
                                </div>
                               
                                <div class="form-group">
                                <?php
                                            if(empty($product->image)||!file_exists($product->image)){
                                                echo "Image Not Found!";
                                            }else{
                                                echo "<img src ='/chieu2/".$product->image ."' alt='product img'/>";
                                            }
                                        ?>
                                <input type="file" class="form-control form-control-user" value="<?=$product->image?>" name="image">
                                </div>
                            <hr>
                              
                                <button class="btn btn-primary btn-user btn-block">
                                    Lưu sản phẩm
                                </button>
                            
                            </form>
                          
                        </div>
                    </div>
                </div>

<?php
include_once("app/views/share/footer.php");
?>