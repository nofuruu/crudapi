// public/js/toast.js

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    showCloseButton: true,
    timer: 1500,
    timerProgressBar: true,
    background: "rgb(79, 81, 84)",
    color: "rgb(255, 255, 255)",
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
    showClass: {
        popup: "animate__animated animate__fadeInRight",
    },
    hideClass: {
        popup: "animate__animated animate__fadeOutRight",
    },
});

function showToast(type, message) {
    Toast.fire({ icon: type, title: message });
}
