<?php

    session_start();
    if(!isset($_SESSION["usuario"])){
        header("Location: ../../../Front-End/HTML/informacion.html");
        exit();
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor academico</title>
    <link rel="shortcut icon" href="../../../Front-End/IMG/iconGestor.png" type="image/x-icon">
    <link rel="stylesheet" href="../../../Front-End/CSS/estilos.css">
</head>
<body>
    <!-- Esto es para guardar el usuario y enviarlo al archivo de js -->
    <div id="session-data" data-usuario="<?php echo $_SESSION['usuario']; ?>"></div>

    <section id="PrincipalCuentaCoor">
        <div id="PrincipalCoor">
            <aside class="lateralDiv1">
                <img src="" id="fotoPerfil" class="forma">
                <br><br>
                <hr>
                <div class="centrar-lateral">
                    <div class="centrarOccion">
                        <a href="" class="enlOccion" onclick="event.preventDefault(); mostrarSeccion('datosPersonales')">
                            <img src="../../../Front-End/IMG/fotoOccionCoor.png" width="20px" class="redondear centrarIMG"> 
                            Datos Personales
                        </a>
                    </div>
                    <div class="centrarOccion">
                        <button class="enlOccion2" onclick="window.location.href='../logout.php'">
                            <img src="../../../Front-End/IMG/logoLogout.png" width="20px" class="redondear centrarIMG"> 
                            Cerrar Sesión
                        </button>
                    </div>
                </div>
            </aside>
            <main class="contenidoCoor">
                <div class="barraSuperior">
                    <div class="espTituloBS">
                        <h1 id="Nombree"></h1>
                        <h4>Coordinador</h4>
                    </div>
                    <div class="espTituloBS">
                        <h1 translate="no">Gestor académico</h1>
                    </div>
                </div>
                <div class="contenidoPrincipalCoor">
                    <div class="tablaOcciones">
                        <div class="contenedorOpciones">
                            <div class="filaOpciones">
                                <button class="opcionTabla">
                                    <img src="../../../Front-End/IMG/logoColegio.png" class="iconoOpcion">
                                    <h1>Colegio</h1>
                                </button>
                                <button class="opcionTabla">
                                    <img src="../../../Front-End/IMG/logoCoordinator.png" class="iconoOpcion">
                                    <h1>Coordinadores</h1>
                                </button>
                            </div>

                            <div class="filaOpciones">
                                <button class="opcionTabla">
                                    <img src="../../../Front-End/IMG/logoCede.png" class="iconoOpcion">
                                    <h1>Cedes</h1>
                                </button>
                                <button class="opcionTabla">
                                    <img src="../../../Front-End/IMG/logoGrade.png" class="iconoOpcion">
                                    <h1>Grados</h1>
                                </button>
                            </div>

                            <div class="filaOpciones filaCentro">
                                <button class="opcionTabla">
                                    <img src="../../../Front-End/IMG/logoTeacher.png" class="iconoOpcion">
                                    <h1>Profesores</h1>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </section>

    <section id="datosPersonales">
        <header class="barra-superior">
            <img src="" alt="perfil" width="200px" height="200px" id="imagenPerfil">
            <div>
                <h1 id="nombre1" class="centrar"></h1>
                <h4>Coordinador</h4>
            </div>
            <h1 class="centrar">Gestor Académico</h1>
        </header>

        <main class="contenedor-datos">
            <div class="fila">
                <div class="campo">
                    <label>TIPO DE DOCUMENTO:</label>
                    <p id="tipoD"></p>
                </div>
                <div class="campo">
                    <label>NUMERO DE DOCUMENTO:</label>
                    <p id="numberD"></p>
                </div>
                <div class="campo">
                    <label>FECHA DE NACIMIENTO:</label>
                    <p id="datee"></p>
                </div>
                <div class="campo">
                    <label>GENERO:</label>
                    <p id="genero"></p>
                </div>
            </div>
            
            <div class="fila">
                <div class="campo">
                    <label>EMAIL:</label>
                    <p id="email"></p>
                </div>
                <div class="campo">
                    <label>TELEFONO:</label>
                    <p id="tell"></p>
                </div>
                <div class="campo">
                    <label>DIRECCIÓN:</label>
                    <p id="direction"></p>
                </div>
                <div class="campo">
                    <label>USUARIO:</label>
                    <p id="user"></p>
                </div>
            </div>
            
            <div class="fila-descripcion">
                <div class="campo-descripcion">
                    <label>DESCRIPCIÓN:</label>
                    <p id="description"></p>
                </div>
            </div>
            
            <div class="fila-boton">
                <a href="" class="boton-editar" onclick="event.preventDefault(); mostrarSeccion('PrincipalCuentaCoor')">ATRAS</a>
                <a href="" class="boton-editar espacio" onclick="event.preventDefault(); abrirEditarCoor()">EDITAR</a>
            </div>
        </main>
    </section>

    <section id="editarCoorPerso" class="oculto">
        <form id="formUpdateCoor">
            <h3>Actualizar Datos personales</h3>
            <input type="text" id="idCoorUpdate">
            <select id="t_d_CoorUpdate" required>
                <option value="">--- Seleccione el tipo de documento ---</option>
                <option value="CC" >Cédula de Ciudadania = CC</option>
                <option value="CE" >Cédula de Extranjería = CE</option>
                <option value="PA" >Pasaporte = PA</option>
                <option value="NIT" >Número de Identificación Tributaria = NIT</option>
                <option value="PEP" >Permiso Especial de Permanencia = PEP</option>
                <option value="DNI" >Documento Nacional de Identidad = DNI</option>
                <option value="NIUP" >Número Único de Identificación Personal = NIUP</option>
            </select>

            <input type="text" id="n_d_CoorUpdate" placeholder="NUMERO DE DOCUMENTO" required/>
            <input type="text" id="nom_CoorUpdate" placeholder="NOMBRE" spellcheck="false" required/>
            <input type="text" id="ape_CoorUpdate" placeholder="APELLIDOS" spellcheck="false" required/>
            <input type="date" id="f_n_CoorUpdate" placeholder="FECHA DE NACIMIENTO" required/>

            <select id="ge_CoorUpdate" required>
                <option value="">--- Seleccione un genero ---</option>
                <option value="Masculino" >Masculino</option>
                <option value="Femenino" >Femenino</option>
            </select>

            <input type="email" id="em_CoorUpdate" placeholder="EMAIL" spellcheck="false" required/>
            <input type="text" id="tel_CoorUpdate" placeholder="TELEFONO" required/>
            <input type="text" id="dir_CoorUPdate" placeholder="DIRECCION" spellcheck="false" required/>
            <input type="text" id="des_CoorUpdate" placeholder="DESCRIPCION" required/>
            <input type="text" id="usu_CoorUpdate" placeholder="USUARIO" spellcheck="false" required/>
            <input type="password" id="clave_CoorUpdate" placeholder="CLAVE" required/>
            <label for="foto" class="text-P-Foto">Foto de perfil:</label>
            <input type="file" id="foto_coor" accept="image/*">

            <div class="panel-botones">
                <button type="button" onclick="cerrarEditarCoor()">Cerrar</button>
                <button type="submit" class="btn-registrar">Actualizar</button>
            </div>
        </form>
    </section>

    <script>
        function mostrarSeccion(id) {
            document.getElementById('PrincipalCuentaCoor').style.display = 'none';
            document.getElementById('datosPersonales').style.display = 'none';

            document.getElementById(id).style.display = 'block';

            localStorage.setItem('seccionCuentaCoor', id);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const seccionGuardada = localStorage.getItem('seccionCuentaCoor');
            if (seccionGuardada) {
                mostrarSeccion(seccionGuardada);
            } else {
                mostrarSeccion('PrincipalCuentaCoor');
            }
        });

        function abrirEditarCoor() {
            document.getElementById('editarCoorPerso').classList.remove('oculto');
            document.getElementById('datosPersonales').classList.add('panel-opaco');
        }

        function cerrarEditarCoor() {
            document.getElementById('editarCoorPerso').classList.add('oculto');
            document.getElementById('datosPersonales').classList.remove('panel-opaco');
        }

    </script>

    <script src="../../../Front-End/Js/cuentaCoor/mostrarDatos.js"></script>
</body>
</html>