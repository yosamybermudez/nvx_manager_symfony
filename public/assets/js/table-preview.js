$(document).ready(function() {
    let tr = $('table.preview tr');

    $(tr).on('click', function () {
        let url = $(this).data('location');
        if (window.location.pathname !== url) {
            window.location.href = url;
        }
    })

});