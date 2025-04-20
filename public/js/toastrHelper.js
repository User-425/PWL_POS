function showToast(type, title, message) {
    const bgClass = {
        success: "bg-success",
        warning: "bg-warning",
        error: "bg-danger",
        info: "bg-info",
    };

    const toastId = "toast-" + Date.now();

    $(document).Toasts("create", {
        class: bgClass[type],
        title: title,
        body: message,
        id: toastId,
        autohide: true,
        delay: 3000,
        icon:
            "fas fa-" +
            (type === "success"
                ? "check"
                : type === "warning"
                ? "exclamation-triangle"
                : type === "error"
                ? "times-circle"
                : "info-circle"),
        close: true,
    });

    setTimeout(() => {
        $("#" + toastId).fadeOut("slow", function () {
            $(this).remove();
        });
    }, 3500);
}