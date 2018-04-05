$(function(){

    if(!window.base.getLocalStorage('token')){
        window.location.href = 'login';
    }

    var pageIndex=1,
        moreDataFlag=true;
    getOrders(pageIndex);

    /*
    * 获取数据 分页
    * params:
    * pageIndex - {int} 分页下表  1开始
    */

    function getOrders(pageIndex){
        var params={
            url:'order/paginate',
            data:{page:pageIndex,size:20},
            tokenFlag:true,
            sCallback:function(res) {
                var str = getOrderHtmlStr(res);
                $('#order-table').append(str);
            }
        };
        window.base.getData(params);
    }

    /*拼接html字符串*/
    function getOrderHtmlStr(res){
        console.log(res)
        var data = res.data;
        if (data){
            var len = data.length,
                str = '', item;
            if(len>0) {
                for (var i = 0; i < len; i++) {
                    item = data[i];
                    str += '<tr>' +
                        '<td>' + item.order_no + '</td>' +
                        '<td>' + item.ticket.title + '</td>' +
                        '<td>' + item.t_count + '</td>' +
                        '<td>￥' + item.total_price + '</td>' +
                        '<td>' + getOrderStatus(item.status) + '</td>' +
                        '<td>' + item.pay_at + '</td>' +
                        '<td data-id="' + item.id + '">' + getBtns(item.status) + '</td>' +
                        '</tr>';
                }
            }
            else{
                ctrlLoadMoreBtn();
                moreDataFlag=false;
            }
            return str;
        }
        return '';
    }

    /*根据订单状态获得标志*/
    function getOrderStatus(status){
        var arr=[
            // {
        //     cName:'unpay',
        //     txt:'未付款'
        // },
            {
            cName:'payed',
            txt:'已付款'
        },{
            cName:'done',
            txt:'已经验票'
        },
        //     {
        //     cName:'unstock',
        //     txt:'缺货'
        // }
        ];
        if (arr[status]){
            return '<span class="order-status-txt '+arr[status].cName+'">'+arr[status].txt+'</span>';

        }else {
            return status

        }
    }

    /*根据订单状态获得 操作按钮*/
    function getBtns(status){
        var arr=[{
            cName:'done',
            txt:'验票'
        },{
            cName:'unstock',
            txt:'已经验证'
        },
            {
                cName:'unpay',
                txt:'退票'
            }
            ];
        if(status==0 || status==1){
          var index=0;
                       if(status==1){
                         index=1;
                         return   ''
                     }

            return   '<span class="order-btn '+arr[index].cName+'">'+arr[index].txt+'</span> '+
                         '<span class="order-btn '+arr[index+2].cName+'">'+arr[index+2].txt+'</span> ' ;
        }else{
            return '';
        }
    }

    /*控制加载更多按钮的显示*/
    function ctrlLoadMoreBtn(){
        if(moreDataFlag) {
            $('.load-more').hide().next().show();
        }
    }

    /*加载更多*/
    $(document).on('click','.load-more',function(){
        if(moreDataFlag) {
            pageIndex++;
            getOrders(pageIndex);
        }
    });
    /*发货*/
    $(document).on('click','.order-btn.done',function(){
        var $this=$(this),
            $td=$this.closest('td'),
            $tr=$this.closest('tr'),
            id=$td.attr('data-id'),
            $tips=$('.global-tips'),
            $p=$tips.find('p');
        var params={
            url:'order/check',
            type:'put',
            data:{id:id},
            tokenFlag:true,
            sCallback:function(res) {

                if(res.status){

                   $tr.find('.order-status-txt')
                       .removeClass('payed').addClass('done')
                       .text('已经验票');
                    $this.remove();
                    $p.text('操作成功');
                }else{
                    $p.text('操作失败');
                }
                $tips.show().delay(1500).hide(0);
            },
            eCallback:function(){
                $p.text('操作失败');
                $tips.show().delay(1500).hide(0);
            }
        };
        window.base.getData(params);
    });

    /*退出*/
    $(document).on('click','#login-out',function(){
        window.base.deleteLocalStorage('token');
        window.location.href = 'login';
    });
});