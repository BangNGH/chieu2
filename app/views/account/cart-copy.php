<?php
include_once("app/views/share/userheader.php");

?>

<?php
if (!isset($_SESSION)) {
    session_start();
}
if (isset($errors)) {
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li class='text-danger'>" . $error . "</li>";
    }
    echo "</ul>";
}
if (isset($_GET['delete'])) {
    echo "
    <div class='alert alert-success'>
  <strong>Success!</strong> Xoá thành công
</div>";
}
?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Giỏ hàng của bạn</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
              
                <div style="display:flex;justify-content: space-between;">
                    <a href="/chieu2" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-arrow"></i>
                        </span>
                        <span class="text">Về trang chủ</span>
                    </a>
                    <div>
                    <a href="/chieu2/product/checkout" class="btn btn-warning btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-flag"></i>
                        </span>
                        <span class="text">Thanh toán khi nhận hàng</span>
                    </a>
                    <a href="/chieu2/product/checkout?vnpay=true" class="btn btn-success btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-flag"></i>
                        </span>
                        <span class="text">Thanh toán VNPAY</span>
                    </a>
                </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">
                            <thead>
                                <tr role="row">
                                
                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 379.19px;">Name</th>

                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 85.4398px;">
                                        Image</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Start date: activate to sort column ascending" style="width: 163.565px;">Price</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Start date: activate to sort column ascending" style="width: 163.565px;">Quantity</th>
                                    <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" aria-label="Salary: activate to sort column ascending" style="width: 135px;">
                                        Action (Delete)</th>
                                </tr>
                            </thead>

                            <tbody>

                            <tbody>
                                <?php foreach ($productInfos as $product) : ?>
                                    <tr class="even">
                                        <td><?= $product['name'] ?></td>
                                        <td>
                                            <?php if (empty($product['image']) || !file_exists($product['image'])) : ?>
                                                Image Not Found!
                                            <?php else : ?>
                                                <img src="/chieu2/<?= $product['image'] ?>" alt="product img">
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $product['price'] ?></td>
                                        <td><input type="number" name="quantity" value="<?= $product['quantity'] ?>"></td>
                                        <td>
                                            <a href="/chieu2/product/deleteitem/<?= $product['id'] ?>" class="text-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">

                    <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                            <ul class="pagination">
                                <li class="paginate_button page-item previous disabled" id="dataTable_previous"><a href="#" aria-controls="dataTable" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li>
                                <li class="paginate_button page-item active"><a href="#" aria-controls="dataTable" data-dt-idx="1" tabindex="0" class="page-link">1</a></li>
                                <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="2" tabindex="0" class="page-link">2</a></li>
                                <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="3" tabindex="0" class="page-link">3</a></li>
                                <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="4" tabindex="0" class="page-link">4</a></li>
                                <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="5" tabindex="0" class="page-link">5</a></li>
                                <li class="paginate_button page-item "><a href="#" aria-controls="dataTable" data-dt-idx="6" tabindex="0" class="page-link">6</a></li>
                                <li class="paginate_button page-item next" id="dataTable_next"><a href="#" aria-controls="dataTable" data-dt-idx="7" tabindex="0" class="page-link">Next</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once("app/views/share/footer.php"); ?>