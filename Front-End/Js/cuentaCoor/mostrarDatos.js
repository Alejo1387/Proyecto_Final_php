document.addEventListener("DOMContentLoaded", () => {

    // Esto es para obtener el usuario
    const div = document.getElementById("session-data");
    const usuario = div.dataset.usuario;

    fetch ('../../../Back-End/APIs/cuentaCoor/mostrarDatos.php', {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ usuario })
    })
    .then(res => res.json())
    .then(data => {
        // console.log(data);
        if (data.error) {
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
});