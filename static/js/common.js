$(function(){
    //确认窗口
    $('*[data-type=d-confirm]').on('click',function(){
        var that=this;
        var d=dialog({
            title:'提示信息',
            content:$(this).attr('data-msg') ? $(this).attr('data-msg') : '您确定需要删除信息吗？',
            okValue:'确定',
            ok:function(){
                $.getJSON($(that).attr('data-uri'),function(rel){
                    var dd=dialog();
                    dd.content(rel.msg).showModal();
                    setTimeout(function(){dd.close().remove();},1000);
                })
            },
            cancelValue:'取消',
            cancel:function(){},
        }).showModal();
    });
    //弹出表单窗口
    $('*[data-type=d-form]').on('click',function(){
        var that=this;
        var d=dialog({
            okValue:'确 定',
            ok:function(){
                $('#myform').submit();
            }
        });
        d.title($(this).attr('data-title') ? $(this).attr('data-title') : '提示消息');
        $.get($(this).attr('data-uri'),function(rel){
            d.content(rel);
        });
        d.showModal();
    });
});