const formularioCrearCuentaColegio = document.getElementById('form_iniciarS');

formularioCrearCuentaColegio.addEventListener('submit', function(event) {
    event.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    fetch('../../../Back-End/APIs/crearCuenta.php', {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(resultado => {
        console.log(resultado);
        const mensajeElemento = document.getElementById('mensaje');

        if (resultado.includes("Correo ya existente")) {
            mensajeElemento.textContent = "❌ Correo ya existente.";
        } else if (resultado.includes("❌")) {
            mensajeElemento.textContent = "❌ Hubo un error al crear la cuenta.";
        } else {
            mensajeElemento.textContent = "✅ Colegio registrado correctamente.";
            form.reset();

            // Si en PHP guardaste en localStorage que debe ir a página 2:
            localStorage.setItem('seccionActualCrearC', 'pagina2');

            // Extraer el ID del colegio desde el HTML de respuesta si viene:
            const idMatch = resultado.match(/id=["']?idColegioInsertado["']? value=["']?(\d+)["']?/);
            if (idMatch) {
                localStorage.setItem('idColegioInsertado', idMatch[1]);
            }

            window.location.reload();
        }
    })
    .catch(err => {
        console.error("Error al enviar el formulario:", err);
        document.getElementById('mensaje').textContent = "❌ Error inesperado al enviar el formulario.";
    });
});


const formularioCrearCuentaCoor = document.getElementById('form_iniciarS');

formularioCrearCuentaCoor.addEventListener('submit', function(event) {
    event.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    fetch('../../../Back-End/APIs/crearCuentaCoor.php', {
        method: "POST",
        body: formData
    })
    
});