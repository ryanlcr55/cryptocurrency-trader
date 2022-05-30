<link href="/css/profile.css" rel="stylesheet">
<link href="/css/bootstrap.min.css" rel="stylesheet">

<div class="section">
    <nav class="navbar navbar-dark bg-dark">
        <a href="/user/profile" class="btn">回上一頁</a>
        <form action="{{asset('/auth/logout')}}" method="post">
            @csrf
            <button type="submit" class="btn">登出</button>
        </form>
    </nav>

    <div class="container">
        <div class="row full-height">
            <div class="col-12 text-center align-self-center py-5">
                <H2>機器人交易紀錄</H2>
                <div class="section pb-5 pt-5 pt-sm-2 text-center">
                    <div class="card-3d-wrap mx-auto" style="width:800px!important;">
                        <div class="card-3d-wrapper">
                            <div class="card-front">
                                <div class="jumbotron jumbotron-fluid">
                                    <div class="container">

                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">幣別</th>
                                                    <th scope="col">動作</th>
                                                    <th scope="col">價格</th>
                                                    <th scope="col">數量</th>
                                                    <th scope="col">下單時間</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(sizeof($data) > 0)
                                                @foreach($data as $val)
                                                <tr>
                                                    <th scope="row">{{$val->symbol}}</th>
                                                    <td>{{$val->action}}</td>
                                                    <td>{{round($val->price, 5)}}</td>
                                                    <td>{{round($val->quantity, 5)}}</td>
                                                    <td>{{$val->order_created_at}}</td>
                                                </tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                        <a href="/user/profile/log" class="btn">交易紀錄</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>