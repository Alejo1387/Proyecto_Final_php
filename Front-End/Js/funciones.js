// FUNCIONES

// Funcion de iniciar sesion
function formularioIniciarS() {
    const formularioIniciarS = document.getElementById('form_iniciarS');

    if(!formularioIniciarS) return;

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
}

// Funcion de formularioCrearCuentaColegio
function formularioCrearCuentaColegio() {
    const formularioCrearCuentaColegio = document.getElementById('formulario_crearColegio');

    if(!formularioCrearCuentaColegio) return;

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
        formData.append('accion', 'INSERT');
        formData.append('funcion', 'crearCuentaColegio');
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

        fetch('../../Back-End/APIs/controladores-APIs.php', {
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
}

// Funcion de formularioCrearCuentaCoor
function formularioCrearCuentaCoor() {
    const formularioCrearCuentaCoor = document.getElementById('formulario_crearCuentaCoor_pagina2');

    if(!formularioCrearCuentaCoor) return;

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
        formData.append('accion', 'INSERT');
        formData.append('funcion', 'formularioCrearCuentaCoor');
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

        fetch('../../Back-End/APIs/controladores-APIs.php', {
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
}

// Funcion para mostrar datos de coor
function mostrarDatosCoor() {
    // Esto es para obtener el usuario
    const div = document.getElementById("session-data");
    const usuario = div.dataset.usuario;

    const formData = new FormData();
    formData.append('accion', 'SELECT')
    formData.append('funcion', 'mostrarDatosCoor');
    formData.append('usuario', usuario);

    fetch ('../../../Back-End/APIs/controladores-APIs.php', {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        // console.log(data);
        if (data.error) {
            alert(data.error)
            alert("Error al encontrar el usuario, vuelve a iniciar sesion!.")
        } else {
            document.getElementById("Nombree").textContent = data.nombres + ' ' + data.apellidos;
            document.getElementById("fotoPerfil").src = "../../uploads/" + data.foto;

            document.getElementById("imagenPerfil").src = "../../uploads/" + data.foto;
            document.getElementById("nombre1").textContent = data.nombres + ' ' + data.apellidos;
            document.getElementById("tipoD").textContent = data.tipo_documento;
            document.getElementById("numberD").textContent = data.numero_documento;
            document.getElementById("datee").textContent = data.fecha_nacimiento;
            document.getElementById("genero").textContent = data.genero;
            document.getElementById("email").textContent = data.email;
            document.getElementById("tell").textContent = data.telefono;
            document.getElementById("direction").textContent = data.direccion;
            document.getElementById("user").textContent = data.usuario;
            document.getElementById("description").textContent = data.descripcion;

            document.getElementById("idCoorUpdate").value = data.id;
            document.getElementById("t_d_CoorUpdate").value = data.tipo_documento;
            document.getElementById("n_d_CoorUpdate").value = data.numero_documento;
            document.getElementById("nom_CoorUpdate").value = data.nombres;
            document.getElementById("ape_CoorUpdate").value = data.apellidos;
            document.getElementById("f_n_CoorUpdate").value = data.fecha_nacimiento;
            document.getElementById("ge_CoorUpdate").value = data.genero;
            document.getElementById("em_CoorUpdate").value = data.email;
            document.getElementById("tel_CoorUpdate").value = data.telefono;
            document.getElementById("dir_CoorUPdate").value = data.direccion;
            document.getElementById("des_CoorUpdate").value = data.descripcion;
            document.getElementById("usu_CoorUpdate").value = data.usuario;
            document.getElementById("clave_CoorUpdate").value = data.clave;
        }
    })
    .catch(error => {
        console.log("Error:", error);
    });
}