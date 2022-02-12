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
                <H2>交易紀錄</H2>
                <div class="section pb-5 pt-5 pt-sm-2 text-center">
                    <div class="card-3d-wrap mx-auto">
                        <div class="card-3d-wrapper">
                            <div class="card-front">
                                <div class="jumbotron jumbotron-fluid">
                                    <div class="container">

                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">幣別</th>
                                                    <th scope="col">幣價</th>
                                                    <th scope="col">動作</th>
                                                    <th scope="col">時間</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($orders as $time => $val)
                                                <tr>
                                                    <th scope="row">{{$val['symbol']}}</th>
                                                    <td>{{$val['cummulativeQuoteQty']/$val['origQty']}}</td>
                                                    <td>{{$val['side']}}</td>
                                                    <td>{{date('Y-m-d H:i:s',$val['time']/1000)}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>