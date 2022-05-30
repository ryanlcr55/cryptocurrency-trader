<link href="{{ asset('/css/login.css') }}" rel="stylesheet">
<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css">

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

                    <form action={{asset('/auth/authenticate')}} method="post">
                      @csrf
                      <div class="input-group mb-3">
                        <input type="account" name="username" class="form-control" placeholder="帳號">
                        <i class="input-icon uil uil-user"></i>
                      </div>
                      <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="密碼">
                        <i class="input-icon uil uil-lock-alt"></i>
                      </div>
                      <!-- /.col -->
                      <button type="submit" class="btn mt-4">登入</button>
                      <!-- /.col -->
                    </form>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>