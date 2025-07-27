const formularioIniciarS = document.getElementById('form_iniciarS');

formularioIniciarS.addEventListener('submit', function(event) {
    event.preventDefault();

    const usuario = document.getElementById('usuario').value;
    const clave = document.getElementById('clave').value;

    const formData = new FormData();
    formData.append('accion', 'GET');
    formData.append('funcion', 'iniciarSesion');
    formData.append('usuario', usuario);
    formData.append('clave', clave);

    fetch ('../../Back-End/APIs/controladores-APIs.php', {
        method: "POST",
        body: formData
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