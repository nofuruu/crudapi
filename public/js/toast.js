const Toast = Swal.mixin({
  toast: true,
  position: "top-start", // Lebih alami untuk notifikasi non-interupsi
  showConfirmButton: false,
  showCloseButton: true,
  background: "#1f2937", // Warna abu gelap (Tailwind gray-800)
  color: "#ffffff", // Putih abu muda (Tailwind gray-50)
  timer: 1000,
  timerProgressBar: true,
  iconColor: "#0044ff", // Warna kuning default (Tailwind yellow-400)
  customClass: {
    popup: "rounded-xl shadow-md p-4 border border-gray-700",
  },
  showClass: {
    popup: "animate__animated animate__fadeInUp",
  },
  hideClass: {
    popup: "animate__animated animate__fadeOutDown",
  },
  didOpen: (toast) => {
    toast.addEventListener("mouseenter", Swal.stopTimer);
    toast.addEventListener("mouseleave", Swal.resumeTimer);
  },
});

function showToast(type = "info", message = "Something happened") {
  const iconColors = {
    success: "#22c55e", // Tailwind green-500
    error: "#ef4444", // Tailwind red-500
    warning: "#f59e0b", // Tailwind amber-500
    info: "#3b82f6", // Tailwind blue-500
  };

  Toast.fire({
    icon: type,
    title: message,
    iconColor: iconColors[type] || "#3b82f6",
  });
}
