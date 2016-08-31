
var Document_width=($(window).width())/2;
var Profile_width=(1000)/2;
if(Document_width-Profile_width>=0)
{
    $('.alert_box').css('left', Document_width-250);
    $('#errors').css('left', Document_width-160);
}
$(window).resize(function()
{
    var Document_width=($(window).width())/2;    
    var Profile_width=(1000)/2;
    var alert_box_width=($('.alert_box').width())/2;
    if(Document_width-Profile_width>=0)
    {
        $('.alert_box').css('left', Document_width-alert_box_width);
        $('#errors').css('left', Document_width-160);
    }
    else
    {
        $('.alert_box').css('left', '217px');
        $('#errors').css('left', '300px');
    }
});


    $('#search_input_top').unbind('keypress').unbind('keydown').unbind('keyup');
    $('#search_input_top').keyup(function(e)
    {
        var key = (e.keyCode ? e.keyCode : e.which);
        if(key == '13')
        {
            search();
            $(this).val('');
        }
    });