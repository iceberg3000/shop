//Ajax表单提交
(function($){
    $.fn.ajaxForm = function(callback){
        var thisObj = this;
        thisObj.submit(function(e){
            e.preventDefault();             //阻止表单默认提交动作
            var data = thisObj.serialize(); //获取表单数据
            $.ajaxPostData(thisObj.attr("action"),data,callback);
        });
    };

    //Ajax提交数据并自动处理
    $.ajaxPostData = function(url,data,callback){
        $.post(url,data,function(data){
            //执行跳转
            if(data.target!==""){location.href=data.target;}
            //执行回调函数
            if(callback!==undefined){callback(data);}
            //显示信息提示
            if(data.msg!==""){$.showTip(data.msg);}
        },"JSON");
    };

    //显示提示
    $.showTip = function(msg){
        $(".tip").remove();             //如果已经显示，则移除
        var $obj = $('<div class="tip"><div class="tip-wrap">'+msg+'</div></div>');
        $("body").append($obj);
        //在屏幕中央显示
        $obj.css("margin-left","-"+($obj.width()/2)+"px");
        $obj.css("margin-top","-"+($obj.height()/2)+"px");
        //单击后隐藏
        $("body").one("click",function(){
            $obj.fadeOut(200,function(){        //以淡出动画效果隐藏
                $obj.remove();                  //彻底隐藏后移除元素
            });
        });
    };
})(jQuery);