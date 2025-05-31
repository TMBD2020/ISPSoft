$(document).on("focus change click blur",".tab-pane.active #sms_text_isp",function(){
    var customize_sms_message = $(this).val();
    var is_placeholder = true;
    var col_indexs = '';
    var colList=['due_amount','client_name','client_id'];
    while(is_placeholder)
    {
        var first_index = customize_sms_message.indexOf("{{");
        var last_index = customize_sms_message.indexOf("}}");
        var placeholder = customize_sms_message.substring(Number(first_index)+Number(2),last_index);
        customize_sms_message = customize_sms_message.substring(Number(last_index)+Number(2),customize_sms_message.length);

      //  console.log(placeholder)
        var col_index = 0;
        colList.forEach(function(e,val){
            //var value = $(span).html();

            if(e==placeholder)
            {
                if(col_indexs=='')
                {
                    col_indexs +=col_index;
                }
                else
                {
                    col_indexs +=","+col_index;
                }
                return false;
            }
            col_index++;

        });

        if(first_index<0 || last_index<0)
        {
            is_placeholder = false;
        }
    }
    $("#col_index").val(col_indexs);

});