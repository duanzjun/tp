$(function(){
    $('.confirmurl').on('click',function(){
        var that=$(this);
        if(confirm(that.attr('data-msg'))){
            $.get(that.attr('data-uri'),function(data){
                if(data===true){
                    that.parent().parent().fadeOut('slow',function(){
                        $(this).remove();
                    });
                }else{
                    alert('删除失败');
                }
            })
        }
    });
    $('*[dia-type=\'dialog\']').on('click',function(){
        var that=$(this);
        var d=top.dialog({
            id:(that.attr('dia-id') ? that.attr('dia-id') : 'dialogid'),
            okValue:'提交',
            ok:function(){
                if(top.$('form').length>0){
                    top.$('form').submit();
                }
                return false;
            },
            cancelValue:'取消',
            cancel:function(){}
        }).showModal();
        d.title(that.attr('dia-title'));
        d.width(parseInt(that.attr('dia-width')));
        d.height(parseInt(that.attr('dia-height')));
        $.get(that.attr('dia-uri')+'&t='+(Math.random()),function(result){
            d.content(result);
        });
        return false;
    });
});
function demo_toggle(obj)
{
    var that = $(obj);
    var val=that.attr('d-val');
    var uri=that.attr('d-uri');
    var icon=that.children('i');
    if(uri==''){
        alert('请求地址不正确');
        return false;
    }
    //选择不对的图标
    if(icon.hasClass('glyphicon-eye-open') || icon.hasClass('glyphicon-eye-close')){
        var c=['glyphicon-eye-close','glyphicon-eye-open'];
    }else{
        var c=['glyphicon-remove','glyphicon-ok']
    }
    $.getJSON($(obj).attr('d-uri'),{'val':val},function(data){
        if(data.status==true){
            icon.attr('class','glyphicon '+c[data.rel.state]);
            that.attr('d-val',1-val);
        }else
            alert('操作失败！');
    });
}