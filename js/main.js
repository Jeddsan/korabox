function loadNotification(title,message,type,icon){
    if(icon=='error'){
        icon = 'fa-exclamation-triangle';
    }else if(icon=='success'){
        icon = 'fa-check-circle';
    }
    $.notify({
            // options
            icon: 'fa '+icon,
            title: title,
            message: message,
        }, {
            // settings
            element: 'body',
            position: null,
            type: type,
            allow_dismiss: true,
            newest_on_top: true,
            showProgressbar: false,
            placement: {
                from: "top",
                align: "right"
            },
            offset: 20,
            spacing: 10,
            z_index: 9999,
            delay: 5000,
            timer: 1000,
            url_target: '_blank',
            mouse_over: null,
            animate: {
                enter: 'animated bounceInDown',
                exit: 'animated bounceOutUp'
            },
            onShow: null,
            onShown: null,
            onClose: null,
            onClosed: null,
            icon_type: 'class',
            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                    '<span data-notify="icon"></span> ' +
                    '<span data-notify="title"><b>{1}</b></span> ' +
                    '<span data-notify="message" style="font-weight:normal !important;">{2}</span>' +
                    '<div class="progress" data-notify="progressbar">' +
                    '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                    '</div>' +
                    '<a href="{3}" target="{4}" data-notify="url"></a>' +
                    '</div>'
        });
}
function confirmDialog(location){
  if(confirm("Bist du sicher, dass du dieses Element löschen möchtest.")){
    window.location.href = location;
  }
}
