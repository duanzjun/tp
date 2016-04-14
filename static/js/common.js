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
            onclose:function(){
                console.info(this);
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
    $.getJSON($(obj).attr('d-uri'),{'val':val},function(data){
        if(data.status==true){
            if(icon.is('.glyphicon-eye-open')){
                icon.removeClass('glyphicon-eye-open').addClass('glyphicon-eye-close');
            }else if(icon.is('.glyphicon-eye-close')){
                icon.removeClass('glyphicon-eye-close').addClass('glyphicon-eye-open');
            }else if(icon.is('.glyphicon-ok')){
                icon.removeClass('glyphicon-ok').addClass('glyphicon-remove');
            }else if(icon.is('.glyphicon-remove')){
                icon.removeClass('glyphicon-remove').addClass('glyphicon-ok');
            }
            that.attr('d-val',1-val);
        }else
            alert('操作失败！');
    });
}