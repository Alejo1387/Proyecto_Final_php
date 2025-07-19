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
        switch (data.success) {
            case 'Coor':
                window.location.href = '../../Back-End/PHP/Cuentas/cuentaCoor.php';
                break;
            case 'Prof':
                window.location.href = '../../Back-End/PHP/Cuentas/cuentaProf.php';
                break;
            case 'Estu':
                window.location.href = '../../Back-End/PHP/Cuentas/cuentaEstu.php';
                break;
            case 'noLogin':
                document.getElementById('error').textContent = "Usuario o clave incorrectos.";
                break;
            default:
                alert('⚠️ Error inesperado en la respuesta');
        }
    });
});