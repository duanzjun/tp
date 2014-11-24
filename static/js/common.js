$(function(){
    //侧边菜单收缩
    $('.col-sidebar h5').on('click',function(){
        var that=$(this);
        var spn=that.children('span');
        $(this).next('.list-group').slideToggle(function(){
            if(that.next('.list-group').is(':hidden'))
                spn.attr('class','glyphicon glyphicon-chevron-right');
            else
                spn.attr('class','glyphicon glyphicon-chevron-down');
        });
    })
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