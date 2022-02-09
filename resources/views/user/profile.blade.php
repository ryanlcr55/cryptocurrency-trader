<link href="/css/profile.css" rel="stylesheet">
<link href="/css/bootstrap.min.css" rel="stylesheet">

<div class="section">
  <nav class="navbar navbar-dark bg-dark">
    <a href="/user/profile/update">修改密碼</a>
    <form action="{{asset('/auth/logout')}}" method="post">
      @csrf
      <button type="submit" class="btn">登出</button>
    </form>
  </nav>

  <div class="container">
    <div class="row full-height">
      <div class="col-12 text-center align-self-center py-5">
        <H2>使用者資料</H2>
        <div class="section pb-5 pt-5 pt-sm-2 text-center">
          <div class="card-3d-wrap mx-auto">
            <div class="card-3d-wrapper">
              <div class="card-front">
                <div class="jumbotron jumbotron-fluid">
                  <div class="container">
                    <p class="display-4">{{$name}}</p>

                    <ul class="list-group">
                      <li class="list-group-item">帳號：{{$username}}</li>
                      <li class="list-group-item">api key：<textarea>{{$exchange_api_key}}</textarea></li>
                      <li class="list-group-item"><a href="/user/profile/log" class="btn">交易紀錄</a></li>

                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>


        </div>
      </div>
    </div>
  </div>