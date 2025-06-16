function mostrarSeccion2(id) {
    document.getElementById('pagina1').style.display = 'none';
    document.getElementById('pagina2').style.display = 'none';

    document.getElementById(id).style.display = 'flex';

    localStorage.setItem('seccionActualCrearC', id);
}

document.addEventListener('DOMContentLoaded', function() {
    const seccionGuardada = localStorage.getItem('seccionActualCrearC');
    const idColegioInsertado = localStorage.getItem('idColegioInsertado');

    if (seccionGuardada === 'pagina2') {
        mostrarSeccion2('pagina2');
        if (idColegioInsertado) {
            document.getElementById('id_col').value = idColegioInsertado;
        }
    } else {
        mostrarSeccion2('pagina1');
    }
});