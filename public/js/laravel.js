$('[data-method]').append(function(){
    return "\n"+
    "<form action='"+$(this).attr('href')+"' method='POST' name='delete_item' style='display:none'>\n"+
    "   <input type='hidden' name='_method' value='"+$(this).attr('data-method')+"'>\n"+
    "   <input type='hidden' name='_token' value='"+$(this).attr('data-token')+"'>\n"+
    "</form>\n"
})
    .removeAttr('href')
    .attr('style','cursor:pointer;')
    .attr('onclick','$(this).find("form").submit();');

/*
 Generic are you sure dialog
 */
// $('form[name=delete_item]').submit(function(){
//     return confirm('Apakah anda yakin ingin menghapus data ini ?');
// });
