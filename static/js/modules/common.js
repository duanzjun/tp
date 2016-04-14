define('common',function(){/*empty*/});
//弹窗
define('common/dialog',function(require,exports){
    $('*[dia-type=\'dialog\']').on('click',function(){
        var dialog = require('dialog');
        var that=$(this);
        var d=dialog({
            id:(that.attr('dia-id') ? that.attr('dia-id') : 'dialogid'),
            okValue:'提交',
            ok:function(){
                if($('form').length>0){
                    $('form').submit();
                }
                return false;
            },
            onclose:function(){},
            cancelValue:'取消',
            cancel:function(){}
        }).show();
        d.title(that.attr('dia-title'));
        that.attr('dia-width') && d.width(parseInt(that.attr('dia-width')));
        that.attr('dia-height') && d.height(parseInt(that.attr('dia-height')));
        $.get(that.attr('dia-uri')+'&t='+(Math.random()),function(result){
            d.content(result);
        });
        return false;
    });
});
define('common/confirm',function(require,exports){
    $('.confirmurl').on('click',function(){
        var that=$(this);
        if(confirm(that.attr('dia-msg'))){
            $.get(that.attr('dia-uri'),function(data){
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
});


    // innerHTML:
    //     '<div i="dialog" class="ui-dialog">'
    //     +       '<div class="ui-dialog-arrow-a"></div>'
    //     +       '<div class="ui-dialog-arrow-b"></div>'
    //     +       '<table class="ui-dialog-grid">'
    //     +           '<tr>'
    //     +               '<td i="header" class="ui-dialog-header">'
    //     +                   '<button i="close" class="ui-dialog-close">&#215;</button>'
    //     +                   '<div i="title" class="ui-dialog-title"></div>'
    //     +               '</td>'
    //     +           '</tr>'
    //     +           '<tr>'
    //     +               '<td i="body" class="ui-dialog-body">'
    //     +                   '<div i="content" class="ui-dialog-content"></div>'
    //     +               '</td>'
    //     +           '</tr>'
    //     +           '<tr>'
    //     +               '<td i="footer" class="ui-dialog-footer">'
    //     +                   '<div i="statusbar" class="ui-dialog-statusbar"></div>'
    //     +                   '<div i="button" class="ui-dialog-button"></div>'
    //     +               '</td>'
    //     +           '</tr>'
    //     +       '</table>'
    //     +'</div>'