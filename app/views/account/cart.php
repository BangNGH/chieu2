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

<style>
  @media (min-width: 1025px) {
    .h-custom {
      height: 100vh !important;
    }
  }

  .card-registration .select-input.form-control[readonly]:not([disabled]) {
    font-size: 1rem;
    line-height: 2.15;
    padding-left: .75em;
    padding-right: .75em;
  }

  .card-registration .select-arrow {
    top: 13px;
  }

  .bg-grey {
    background-color: #eae8e8;
  }

  @media (min-width: 992px) {
    .card-registration-2 .bg-grey {
      border-top-right-radius: 16px;
      border-bottom-right-radius: 16px;
    }
  }

  @media (max-width: 991px) {
    .card-registration-2 .bg-grey {
      border-bottom-left-radius: 16px;
      border-bottom-right-radius: 16px;
    }
  }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<section class="h-100 h-custom" style="background-color: #d2c9ff;">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12">
        <div class="card card-registration card-registration-2" style="border-radius: 15px;">
          <div class="card-body p-0">
            <div class="row g-0">
              <div class="col-lg-8">
                <div class="p-5">
                  <div class="d-flex justify-content-between align-items-center mb-5">
                    <h1 class="fw-bold mb-0 text-black">Shopping Cart</h1>

                  </div>
                  <hr class="my-4">

                  <?php
                  $totalPrice = 0;
                  foreach ($productInfos as $product) :
                    $subtotal = $product['quantity'] * $product['price'];
                    $totalPrice += $subtotal;
                  ?>
                    <div class="row mb-4 d-flex justify-content-between align-items-center">
                      <div class="col-md-2 col-lg-2 col-xl-2">
                        <?php if (empty($product['image']) || !file_exists($product['image'])) : ?>
                          Image Not Found!
                        <?php else : ?>
                          <img src="/chieu2/<?= $product['image'] ?>" class="img-fluid rounded-3" alt="product img">
                        <?php endif; ?>

                      </div>
                      <div class="col-md-3 col-lg-3 col-xl-3">

                        <h6 class="text-black mb-0"><?= $product['name'] ?></h6>
                      </div>
                      <div class="col-md-3 col-lg-3 col-xl-3 d-flex">
                        <input id="quantity<?php echo $product['id'] ?>" min="0" name="quantity" value="<?= $product['quantity'] ?>" type="number" class="form-control form-control-sm" />
                        <button onclick="updateCart(<?= $product['id'] ?>, document.getElementById('quantity<?php echo $product['id'] ?>').value)" class="btn btn-sm btn-primary ml-2">Update</button>

                      </div>

                      <script>
                        function updateCart(productId, quantity) {
                         
                          $.ajax({
                            type: "POST", 
                            url: "/chieu2/product/updateCart",
                            data: {
                              productId: productId,
                              quantity: quantity
                            }, 
                            success: function(data) {
                              location.reload();
                            },
                            error: function(xhr, status, error) {
                              console.error(xhr.responseText);
                            }
                          });
                        }
                      </script>


                      <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">

                        <h6 class="mb-0" name="price"><?= $product['price'] * $product['quantity'] ?></h6>
                      </div>
                      <div class="col-md-1 col-lg-1 col-xl-1 text-end">

                        <a href="/chieu2/product/deleteitem/<?= $product['id'] ?>" class="text-muted"><i style="color: red;" class="fas fa-times"></i></a>
                      </div>
                    </div>
                  <?php endforeach; ?>
                  <hr class="my-4">

                  <div class="pt-5">
                    <h6 class="mb-0"><a href="/chieu2" class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>Back to shop</a></h6>

                  </div>
                </div>
              </div>
              <div class="col-lg-4 bg-grey">
                <div class="p-5">
                  <h4 class="fw-bold mb-5 mt-2 pt-1">Đơn hàng của bạn</h4>
                
                  <div id="totalPrice">Tổng tiền: <strong></strong> vnd</div>
                  <script>
                    var priceElements = document.querySelectorAll('[name="price"]');

                    var totalPrice = 0;

                    priceElements.forEach(function(element) {
                      totalPrice += parseFloat(element.textContent);
                    });

                    document.getElementById('totalPrice').innerHTML ="Tổng tiền "+ totalPrice + " vnd";
                  </script>

                  <hr class="my-4">
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="inputEmail4">Địa chỉ</label>
                      <input type="text" class="form-control" id="address" placeholder="address">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="inputPassword4">Số điện thoại</label>
                      <input type="text" class="form-control" id="phone" placeholder="phone">
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="inputEmail4">Note</label>
                      <input type="text" class="form-control" id="note" placeholder="Ghi chú">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="inputPassword4">Tên người nhận</label>
                      <input type="text" class="form-control" id="name" placeholder="name">
                    </div>
                  </div>

                  <hr class="my-4">
                  <div style="display:flex; justify-content: space-between">
                    <a class="btn btn-warning btn-icon-split" onclick="submitForm(event, false)">
                      <span class="icon text-white-50">
                        <i class="fas fa-flag"></i>
                      </span>
                      <span class="text">COD</span>
                    </a>

                    <a class="btn btn-success btn-icon-split" onclick="submitForm(event, true)">
                      <span class="icon text-white-50">
                        <i class="fas fa-flag"></i>
                      </span>
                      <span class="text">VNPAY</span>
                    </a>
                  </div>


                  <script>
                    function submitForm(event, checkout) {
                      event.preventDefault();

                      var address = document.getElementById('address').value;
                      var phone = document.getElementById('phone').value;
                      var note = document.getElementById('note').value;
                      var name = document.getElementById('name').value;

                      var form = document.createElement('form');
                      form.setAttribute('method', 'post');
                      form.setAttribute('id', 'formSubmit');
                      form.setAttribute('action', '/chieu2/product/checkout');

                      var addressInputField = document.createElement('input');
                      addressInputField.setAttribute('type', 'hidden');
                      addressInputField.setAttribute('name', 'address');
                      addressInputField.setAttribute('value', address);

                      var phoneInputField = document.createElement('input');
                      phoneInputField.setAttribute('type', 'hidden');
                      phoneInputField.setAttribute('name', 'phone');
                      phoneInputField.setAttribute('value', phone);

                      var nameInputField = document.createElement('input');
                      nameInputField.setAttribute('type', 'hidden');
                      nameInputField.setAttribute('name', 'name');
                      nameInputField.setAttribute('value', name);

                      var noteInputField = document.createElement('input');
                      noteInputField.setAttribute('type', 'hidden');
                      noteInputField.setAttribute('name', 'note');
                      noteInputField.setAttribute('value', note);

                      var checkoutInputField = document.createElement('input');
                      checkoutInputField.setAttribute('type', 'hidden');
                      checkoutInputField.setAttribute('name', 'checkout');

                      if (checkout) {
                        checkoutInputField.setAttribute('value', true);
                      } else checkoutInputField.setAttribute('value', false);

                      form.appendChild(noteInputField);
                      form.appendChild(nameInputField);
                      form.appendChild(phoneInputField);
                      form.appendChild(addressInputField);
                      form.appendChild(checkoutInputField);
                      document.body.appendChild(form);
                      console.log(document.getElementById('formSubmit'));
                      form.submit();
                    }
                  </script>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
include_once("app/views/share/footer.php"); ?>