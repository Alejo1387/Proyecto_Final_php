const formularioIniciarS = document.getElementById('form_iniciarS');

formularioIniciarS.addEventListener('submit', function(event) {
    event.preventDefault();

    const usuario = document.getElementById('usuario').value;
    const clave = document.getElementById('clave').value;

    fetch ('../../Back-End/APIs/iniciarS.php', {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ usuario, clave})
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = './cuentas/CuentaCoor.html';
        } else {
            document.getElementById('error').textContent = "Usuario o clave incorrectos.";
        }
    });
});