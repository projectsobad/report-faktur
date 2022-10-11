$('#myTabs').on('click', '.nav-line a', function () {
    $(this).closest('.dropdown').addClass('dontClose');
})

$('#myDropDown').on('hide.bs.dropdown', function (e) {
    if ($(this).hasClass('dontClose')) {
        e.preventDefault();
    }
    $(this).removeClass('dontClose');
});