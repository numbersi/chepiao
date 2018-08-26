<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>车票后台－订单列表</title>
    <link rel="shortcut icon" href="../favicon.ico"/>

    <link href="{{ URL::asset('/admin/css/index/iconfont/iconfont.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ URL::asset('/admin/css/index/index.css') }}" type="text/css" rel="stylesheet">

</head>
<body>
    <div class="banner-wrapper">
        <div class="logo">
            <img src="{{ URL::asset('/admin/imgs/logo.png') }}">
            <div class="logo-word">
                <div>车票后台</div>
                <div>Snack Petty Vendor</div>
            </div>
        </div>
        <div class="left-box">
            <a class="register" id="login-out" title="退出">退出</a>
        </div>
    </div>
    <div class="desktop-all-box">
        <div class="desktop item-wrapper">
            <div class="desktop-menu">
                <ul>
                    <li class="leftbar-item">
                        <a class="active">
                            <i class="iconfont icon-list"></i>
                            <span>订单列表</span>
                        </a>
                        <a class="active">
                            <i class="iconfont icon-fenlei"></i>
                            <span>订单列表</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="desktop-main">

                <div class="order-box">
                   <p style="color: black;border-bottom-style: solid " >总共卖出 {{$count_orders}} 张    共 {{$count_orders_money}} 元</p>
                   <h1 style="color: green;border-bottom-style: solid " > 已经验票 {{$count_orders_c}} 张    共 {{$count_orders_money_c}} 元</h1>
                   <h2 style="color: red;border-bottom-style: solid " > 没有检票 {{$count_orders_n}} 张    共 {{$count_orders_money_n}} 元</h2>
                    <table cellspacing="0" ng-show="docArr.length>0">
                        <thead>
                            <tr>
                                <td>订单号</td>
                                <td>商品名称</td>
                                <td>商品总数</td>
                                <td>商品总价格</td>
                                <td>订单状态</td>
                                <td>下单时间</td>
                                <td>操作</td>
                            </tr>
                        </thead>
                        <tbody id="order-table">

                        </tbody>
                    </table>
                    <div class="load-more">点击加载更多</div>
                    <div class="no-more">没有更多订单了</div>
                </div>
            </div>
        </div>
    </div>
    <div class="global-tips">
        <p></p>
    </div>
</body>
<script src="{{ URL::asset('/admin/js/common.js') }}" type="application/javascript"></script>
<script src="{{ URL::asset('/admin/js/jquery-1.8.2.min.js') }}" type="application/javascript"></script>
<script src="{{ URL::asset('/admin/js/index.js') }}" type="application/javascript"></script>

</html>