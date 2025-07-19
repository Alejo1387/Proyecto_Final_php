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
        alert("❌ Error inesperado al enviar el formulario.");
    });
});


const formularioCrearCuentaCoor = document.getElementById('formulario_crearCuentaCoor_pagina2');

formularioCrearCuentaCoor.addEventListener('submit', function(event) {
    event.preventDefault();

    const id_col = document.getElementById('id_col').value;
    const tipo_documento = document.getElementById('tipo_documento').value;
    const numero_documento = document.getElementById('numero_documento').value;
    const nombres = document.getElementById('nombres_coor').value;
    const apellidos = document.getElementById('apellidos').value;
    const fecha_nacimiento = document.getElementById('fecha_nacimiento').value;
    const generoo = document.getElementById('generoo').value;
    const email = document.getElementById('email_coor').value;
    const telefono = document.getElementById('telefono_coor').value;
    const direccion = document.getElementById('direccion_coor').value;
    const descripcion = document.getElementById('descripcion_coor').value;
    const usuario = document.getElementById('usuario').value;
    const clave = document.getElementById('clave').value;
    const foto_coor = document.getElementById('foto_coor').files[0];

    const formData = new FormData();
    formData.append('id_col', id_col);
    formData.append('tipo_documento', tipo_documento);
    formData.append('numero_documento', numero_documento);
    formData.append('nombres', nombres);
    formData.append('apellidos', apellidos);
    formData.append('fecha_nacimiento', fecha_nacimiento);
    formData.append('generoo', generoo);
    formData.append('email', email);
    formData.append('telefono', telefono);
    formData.append('direccion', direccion);
    formData.append('descripcion', descripcion);
    formData.append('usuario', usuario);
    formData.append('clave', clave);
    formData.append('foto_coor', foto_coor);

    fetch('../../Back-End/APIs/crearCuentaCoor.php', {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(resultado => {
        const errores = resultado.errores;

        if (Array.isArray(errores) && errores.length > 0) {
            document.getElementById('mensaje_coor').textContent = "❌ " + errores[0];
            document.getElementById('mensaje_coor2').textContent = "❌ " + errores[0];

            document.getElementById('mensaje_coor').style.background = '#39ff14';
            document.getElementById('mensaje_coor').style.background = '#39ff14';
        } else {
            document.getElementById('mensaje_coor').style.background = "none";
            document.getElementById('mensaje_coor2').style.background = "none";
            document.getElementById('mensaje_coor').textContent = "";
            document.getElementById('mensaje_coor2').textContent = "";

            formularioCrearCuentaCoor.reset();

            // Si en PHP guardaste en localStorage que debe ir a página 2:
            localStorage.setItem('seccionActualCrearC', 'pagina1');
            localStorage.setItem('idColegioInsertado', 0);
            window.location.href = "../HTML/iniciarS.html";
        }
    })
    .catch(error => {
        console.error("❌ Error al enviar el formulario del coordinador:", error);
        alert("❌ Error inesperado. Revisa consola (F12).");
    });
});