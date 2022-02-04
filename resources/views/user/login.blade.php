<link href="{{ asset('/css/login.css') }}" rel="stylesheet">
<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css">

<form action={{asset('/auth/authenticate')}} method="post">
<div class="section">
    <div class="container">
      <div class="row full-height">
        <div class="col-12 text-center align-self-center py-5">
          <div class="section pb-5 pt-5 pt-sm-2 text-center">
                  <label for="reg-log"></label>
            <div class="card-3d-wrap mx-auto">
              <div class="card-3d-wrapper">
                <div class="card-front">
                  <div class="center-wrap">
                    <div class="section text-center">
                      <h4 class="mb-4 pb-3">會員登入</h4>
                      <div class="form-group">
                        <input type="name" name="name" class="form-style" placeholder="帳號" id="logemail" autocomplete="off">
                        <i class="input-icon uil uil-user"></i>
                      </div>  
                      <div class="form-group mt-2">
                        <input type="password" name="password" class="form-style" placeholder="密碼" id="password" autocomplete="off">
                        <i class="input-icon uil uil-lock-alt"></i>
                      </div>
                      <a href="#" class="btn mt-4">送出</a>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
      </div>
  </div>

</form>
