function refresh()
{
    setInterval(function ()
    {
        $.ajax({
            url: '/admin/calls/ajax',
            type: 'POST',
            success: function (data, textStatus) {
                alert('Sucesso');
            },
            error: function () {
                alert('Falhou!!!');
            }
        })
    }, 1000)
}
$(document).ready(function () {
    refresh();
});