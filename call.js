function refresh()
{
    setInterval(function ()
    {
        $.ajax({
            url: '/admin/calls/ajax',
            type: 'POST',
            success: function (data, textStatus) {
                $(".myTable").replaceWith(data);
            },
            error: function () {
                alert('Falhou!!!');
            }
        })
    }, 10000)
}
$(document).ready(function () {
    refresh();
});