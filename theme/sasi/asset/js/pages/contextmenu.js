$(function () {
    $.contextMenu({
        selector: '.sasi_sidemenu',
        callback: function (key, opt) {
            if (key == 'New Tab') {
                sobad_windows(opt.$trigger.attr("data-uri"));
            }
        },
        items: {
            "New Tab": {
                name: "New Tab", icon: function () {
                    return '';
                }
            }
        }
    });

    $('.context-menu-one').on('click', function (e) {
        console.log('clicked', this);
    })

    $.contextMenu({
        selector: '.sasi_childmenu',
        callback: function (key, opt) {
            if (key == 'New Tab') {
                sobad_windows(opt.$trigger.attr("data-uri"));
            }
        },
        items: {
            "New Tab": {
                name: "New Tab", icon: function () {
                    return '';
                }
            }
        }
    });

    $('.context-menu-one').on('click', function (e) {
        console.log('clicked', this);
    })

});