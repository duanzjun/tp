$(function(){

});
function demo_toggle(obj)
{
    var that = $(obj);
    var val=$(obj).attr('d-val');
    $.getJSON($(obj).attr('d-uri'),{'val':val},function(data){
        if(data.status==true){
            if(data.rel.status==1)
                that.children('i').attr('class','glyphicon glyphicon-ok');
            else
                that.children('i').attr('class','glyphicon glyphicon-remove');
            that.attr('d-val',1-val);
        }else
            alert('操作失败！');
    });
}