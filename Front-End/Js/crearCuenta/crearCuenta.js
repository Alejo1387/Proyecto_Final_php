const formularioCrearCuentaColegio = document.getElementById('formulario_crearColegio');

formularioCrearCuentaColegio.addEventListener('submit', function(event) {
    event.preventDefault();

    const nit = document.getElementById('nit_Colegio').value;
    const nombre = document.getElementById('nombre_Colegio').value;
    const direccion = document.getElementById('direccion_Colegio').value;
    const telefono = document.getElementById('telefono_Colegio').value;
    const email = document.getElementById('email_Colegio').value;
    const pais = document.getElementById('pais_Colegio').value;
    const departamento = document.getElementById('departamento_Colegio').value;
    const ciudad = document.getElementById('ciudad_Colegio').value;
    const descripcion = document.getElementById('descripcion_Colegio').value;
    const foto_perfil_colegio = document.getElementById('foto_Colegio').files[0];

    const formData = new FormData();
    formData.append('nit', nit);
    formData.append('nombre', nombre);
    formData.append('direccion', direccion);
    formData.append('telefono', telefono);
    formData.append('email', email);
    formData.append('pais', pais);
    formData.append('departamento', departamento);
    formData.append('ciudad', ciudad);
    formData.append('descripcion', descripcion);
    formData.append('foto_perfil_colegio', foto_perfil_colegio);

    fetch('../../Back-End/APIs/crearCuenta.php', {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(resultado => {
        const errores = resultado.errores;

        if (Array.isArray(errores) && errores.length > 0) {
            document.getElementById('mensaje_Col').textContent = "❌ " + errores[0];
            document.getElementById('mensaje_Col2').textContent = "❌ " + errores[0];

            document.getElementById('mensaje_Col').style.background = '#39ff14';
            document.getElementById('mensaje_Col2').style.background = '#39ff14';
        } else {
            const id_colegio = resultado.idColegio;

            document.getElementById('mensaje_Col').style.background = "none";
            document.getElementById('mensaje_Col2').style.background = "none";
            document.getElementById('mensaje_Col').textContent = "";
            document.getElementById('mensaje_Col2').textContent = "";

            formularioCrearCuentaColegio.reset();

            // Si en PHP guardaste en localStorage que debe ir a página 2:
            localStorage.setItem('seccionActualCrearC', 'pagina2');
            localStorage.setItem('idColegioInsertado', id_colegio);
            window.location.href = window.location.href;
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

    const form = event.target;
    const formData = new FormData(form);

    fetch('../../../Back-End/APIs/crearCuentaCoor.php', {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(resultado => {
        console.log(resultado);
    });
});