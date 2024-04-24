function confirmation(ev) {
    ev.preventDefault();
    const urlToRedirectBack = document.querySelector('link[rel="canonical"]').href;
    const urlToRedirect = ev.currentTarget.getAttribute('href');
    Swal.fire({
        title: "Desconectar da sua conta?",
        text: "Você tem certeza que deseja desconectar da sua conta? Isso encerrará sua sessão atual.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, sair!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = urlToRedirect;
        } else {
            window.location.href = urlToRedirectBack;
        }
    });
}
