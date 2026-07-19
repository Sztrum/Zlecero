import Toastify from 'toastify-js'

window.toastify = Toastify;

window.toastSuccess = function (message) {
    window.toastify({
        text: message,
        duration: 5000,
        close: true,
        gravity: "top",
        position: "right",
        stopOnFocus: true,
        className: "toastify-success",
    }).showToast();
}

window.toastDanger = function (message) {
    window.toastify({
        text: message,
        duration: 5000,
        close: true,
        gravity: "top",
        position: "right",
        stopOnFocus: true,
        className: "toastify-danger",
    }).showToast();
}

window.toastWarning = function (message) {
    window.toastify({
        text: message,
        duration: 5000,
        close: true,
        gravity: "top",
        position: "right",
        stopOnFocus: true,
        className: "toastify-warning",
    }).showToast();
}

