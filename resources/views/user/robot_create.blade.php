<link href="{{ asset('/css/login.css') }}" rel="stylesheet">
<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">

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
                                        <form action={{asset('/user/profile/robot/update')}} method="post">
                                            @csrf
                                            <div class="input-group mb-3">
                                                選擇訊號：
                                                <select class="form-select" name="signal_id" aria-label="Default select example">
                                                    <option selected>下拉選擇訊號</option>
                                                    @if(sizeof($signals) > 0)
                                                    @foreach($signals as $val)
                                                    <option value={{$val->id}}>{{$val->name}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="input-group mb-3">
                                                單次下單金額：
                                                <input type="number" name="unit_percent"></input>
                                            </div>
                                            <div class="input-group mb-3">
                                                止盈%數：
                                                <input type="number" name="limit_percent"></input>
                                            </div>
                                            <div class="input-group mb-3">
                                                止損%數：
                                                <input type="number" name="stop_percent"></input>
                                            </div>


                                            <!-- /.col -->
                                            <button type="submit" class="btn mt-4">新增</button>
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