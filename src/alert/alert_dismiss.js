function dismissAlert(alertId) {
    const alertElement = document.getElementById(alertId);
    if (alertElement) {
        alertElement.style.opacity = 0;
        setTimeout(() => {
            alertElement.style.display = "none";
        }, 300);
    }
}

setTimeout(function() {
    const alertElement = document.getElementById("toast-alert");
    if (alertElement) {
        alertElement.style.opacity = 0;
        setTimeout(() => {
            alertElement.style.display = "none";
        }, 300);
    }
}, 5000);

if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
