<?php

    ob_clean();
    header("Content-Type: application/json");
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once "conexion.php";

    // FUNCIONES

    // Funcion de iniciar sesion
    function iniciarSesion($usuario, $clave) {
        global $pdo;

        $response = ["success" => false];

        $sql = "SELECT id FROM COORDINADORES WHERE usuario = ? AND clave = ?";
        $sql2 = "SELECT id FROM PROFESORES WHERE usuario = ? AND clave = ?";
        $sql3 = "SELECT id FROM ESTUDIANTES WHERE usuario = ? AND clave = ?";

        $stmt = $pdo->prepare($sql);
        $stmt2 = $pdo->prepare($sql2);
        $stmt3 = $pdo->prepare($sql3);

        try {
            $stmt->execute([$usuario, $clave]);

            if ($stmt->rowCount() > 0) {
                $response["success"] = "Coor";
            } else {
                $stmt2->execute([$usuario, $clave]);

                if ($stmt2->rowCount() > 0) {
                    $response["success"] = "Prof";
                } else {
                    $stmt3->execute([$usuario, $clave]);
                    
                    if ($stmt3->rowCount() > 0) {
                        $response["success"] = "Estu";
                    } else {
                        $response["success"] = "noLogin";
                    }
                }
            }
        } catch (PDOException $e) {
            $response["error"] = $e->getMessage();
        }

        session_start();
        $_SESSION["usuario"] = $usuario;
        
        echo json_encode($response);
        exit;
    }

    // Funcion de crear cuenta colegio
    function crearCuentaColegio() {
        global $pdo;

        $nit = $_POST["nit"];
        $nombre = $_POST["nombre"];
        $direccion = $_POST["direccion"];
        $telefono = $_POST["telefono"];
        $email = $_POST["email"];
        $pais = $_POST["pais"];
        $departamento = $_POST["departamento"];
        $ciudad = $_POST["ciudad"];
        $descripcion = $_POST["descripcion"];
        
        // if (isset($_FILES["foto_perfil_colegio"]) && $_FILES["foto_perfil_colegio"]["error"] === 0) {
        //     $foto = $_FILES["foto_perfil_colegio"]["name"];
        // } else {
        //     $foto = "";
        // }

        // $response = array($nit, $nombre, $direccion, $telefono, $email, $pais, $departamento, $ciudad, $descripcion, $foto);
        // echo json_encode($response);

        $nombreImagen = "defaultPerfilColegio.png";

        if (isset($_FILES["foto_perfil_colegio"]) && $_FILES["foto_perfil_colegio"]["error"] === UPLOAD_ERR_OK) {
            $archivoTmp = $_FILES["foto_perfil_colegio"]["tmp_name"];
            $nombreOriginal = basename($_FILES["foto_perfil_colegio"]["name"]);

            // Sanitizar nombre y evitar espacios raros
            $nombreImagen = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $nombreOriginal);
            $rutaDestino = __DIR__ . "/../uploads/" . $nombreImagen;

            if (!move_uploaded_file($archivoTmp, $rutaDestino)) {
                $response["errores"][] =  "❌ Error al subir la imagen. Asegúrate de que la carpeta 'uploads/' exista y tenga permisos.";
                echo json_encode($response);
                exit;
            }
        }

        $sql = "INSERT INTO COLEGIOS (nit, nombre, direccion, telefono, email, pais, departamento, ciudad, descripcion, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        $sqlVerificarEmail = "
        SELECT email FROM COORDINADORES WHERE email = ?
        UNION
        SELECT email FROM ESTUDIANTES WHERE email = ?
        UNION
        SELECT email FROM PROFESORES WHERE email = ?
        UNION
        SELECT email FROM COLEGIOS WHERE email = ?
        ";
        $stmt3 = $pdo->prepare($sqlVerificarEmail);

        $sqlVerificarNumero = "
        SELECT telefono FROM COORDINADORES WHERE telefono = ?
        UNION
        SELECT telefono FROM ESTUDIANTES WHERE telefono = ?
        UNION
        SELECT telefono FROM PROFESORES WHERE telefono = ?
        UNION
        SELECT telefono FROM COLEGIOS WHERE telefono = ?
        ";
        $stmt4 = $pdo->prepare($sqlVerificarNumero);

        $sqlVerificarNit = "SELECT nit FROM COLEGIOS WHERE nit = ?";
        $stmt5 = $pdo->prepare($sqlVerificarNit);

        $response = [
            "errores" => []
        ];

        try {
            $stmt3->execute([$email, $email, $email, $email]);
            $stmt4->execute([$telefono, $telefono, $telefono, $telefono]);
            $stmt5->execute([$nit]);

            if (mb_strlen($nit, 'UTF-8') > 20) {
                $response["errores"][] = "El nit tiene una longitub mayor a lo permitido";
            }
            if ($stmt5->rowCount() > 0) {
                $response["errores"][] = "Nit ya existente!";
            }
            if (mb_strlen($nombre, 'UTF-8') > 100) {
                $response["errores"][] = "El nombre tiene una longitub mayor a lo permitido";
            }
            if (mb_strlen($direccion, 'UTF-8') > 150) {
                $response["errores"][] = "La dirección tiene una longitub mayor a lo permitido";
            }
            if (mb_strlen($telefono, 'UTF-8') > 15) {
                $response["errores"][] = "El telefono tiene una longitub mayor a lo permitido";
            }
            if ($stmt4->rowCount() > 0) {
                $response["errores"][] = "Telefono ya existente!";
            }
            if (mb_strlen($email, 'UTF-8') > 100) {
                $response["errores"][] = "El email tiene una longitub mayor a lo permitido";
            }
            if($stmt3->rowCount() > 0) {
                $response["errores"][] = "Correo ya existente!";
            }
            if (mb_strlen($pais, 'UTF-8') > 50) {
                $response["errores"][] = "El pais tiene una longitub mayor a lo permitido";
            }
            if (mb_strlen($departamento, 'UTF-8') > 50) {
                $response["errores"][] = "El departamento tiene una longitub mayor a lo permitido";
            }
            if (mb_strlen($ciudad, 'UTF-8') > 50) {
                $response["errores"][] = "La ciudad tiene una longitub mayor a lo permitido";
            }

            if (!empty($response["errores"])) {
                echo json_encode($response);
                exit;
            }

            $stmt->execute([$nit, $nombre, $direccion, $telefono, $email, $pais, $departamento, $ciudad, $descripcion, $nombreImagen]);

            $idColegio = $pdo->lastInsertId();

            $response["idColegio"] = $idColegio;
            echo json_encode($response);
            exit;
        } catch (Exception $e) {
            $response["success"] = false;
            $response["message"] = "❌ Error al insertar en la base de datos: " . $e->getMessage();
            echo json_encode($response);
        }
        $response["errores"] = [];

        exit;
    }

    // Funcion para crear un coor
    function formularioCrearCuentaCoor() {
        global $pdo;

        $id_col =  $_POST["id_col"];
        $tipo_documento = $_POST["tipo_documento"];
        $numero_documento = $_POST["numero_documento"];
        $nombres = $_POST["nombres"];
        $apellidos = $_POST["apellidos"];
        $fecha_nacimiento = $_POST["fecha_nacimiento"];
        $generoo = $_POST["generoo"];
        $email = $_POST["email"];
        $telefono = $_POST["telefono"];
        $direccion = $_POST["direccion"];
        $descripcion = $_POST["descripcion"];
        $usuario = $_POST["usuario"];
        $clave = $_POST["clave"];

        // if (isset($_FILES["foto_coor"]) && $_FILES["foto_coor"]["error"] === 0) {
        //     $foto = $_FILES["foto_coor"]["name"];
        // } else {
        //     $foto = "";
        // }

        $nombreImagen = "defaultPerfilColegio.png";

        if (isset($_FILES["foto_coor"]) && $_FILES["foto_coor"]["error"] === UPLOAD_ERR_OK) {
            $archivoTmp = $_FILES["foto_coor"]["tmp_name"];
            $nombreOriginal = basename($_FILES["foto_coor"]["name"]);

            $nombreImagen = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $nombreOriginal);
            $rutaDestino = __DIR__ . "/../uploads/" . $nombreImagen;

            if (!move_uploaded_file($archivoTmp, $rutaDestino)) {
                $response["errores"][] =  "❌ Error al subir la imagen del coordinador.";
                echo json_encode($response);
                exit;
            }
        }

        $sql = "INSERT INTO COORDINADORES(colegio_id, tipo_documento, numero_documento, nombres, apellidos, fecha_nacimiento, genero, email, telefono, direccion, usuario, clave, descripcion, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        $sqlVerificarEmail = "
        SELECT email FROM COORDINADORES WHERE email = ?
        UNION
        SELECT email FROM ESTUDIANTES WHERE email = ?
        UNION
        SELECT email FROM PROFESORES WHERE email = ?
        UNION
        SELECT email FROM COLEGIOS WHERE email = ?
        ";
        $stmt3 = $pdo->prepare($sqlVerificarEmail);

        $sqlVerificarNumero = "
        SELECT telefono FROM COORDINADORES WHERE telefono = ?
        UNION
        SELECT telefono FROM ESTUDIANTES WHERE telefono = ?
        UNION
        SELECT telefono FROM PROFESORES WHERE telefono = ?
        UNION
        SELECT email FROM COLEGIOS WHERE telefono = ?
        ";
        $stmt4 = $pdo->prepare($sqlVerificarNumero);

        $sqlVerificarUsuario = "
        SELECT usuario FROM COORDINADORES WHERE usuario = ?
        UNION
        SELECT usuario FROM ESTUDIANTES WHERE usuario = ?
        UNION
        SELECT usuario FROM PROFESORES WHERE usuario = ?
        ";
        $stmt5 = $pdo->prepare($sqlVerificarUsuario);

        $sqlVerificarNumeroDocumento = "SELECT numero_documento FROM COORDINADORES WHERE numero_documento = ?";
        $stmt6 = $pdo->prepare($sqlVerificarNumeroDocumento);

        $response = [
            "errores" => []
        ];

        try {
            $stmt3->execute([$email, $email, $email, $email]);
            $stmt4->execute([$telefono, $telefono, $telefono, $telefono]);
            $stmt5->execute([$usuario ,$usuario ,$usuario]);
            $stmt6->execute([$numero_documento]);

            if (mb_strlen($numero_documento, 'UTF-8') > 20) {
                $response["errores"][] = "El numero de documento tiene una longitub mayor a lo permitido";
            }
            if ($stmt6->rowCount() > 0) {
                $response["errores"][] = "Numero de documento ya existente!";
            }
            if (mb_strlen($nombres, 'UTF-8') > 60) {
                $response["errores"][] = "El nombre tiene una longitub mayor a lo permitido";
            }
            if (mb_strlen($apellidos, 'UTF-8') > 60) {
                $response["errores"][] = "El apellido tiene una longitub mayor a lo permitido";
            }
            if (mb_strlen($email, 'UTF-8') > 100) {
                $response["errores"][] = "El email tiene una longitub mayor a lo permitido";
            }
            if ($stmt3->rowCount() > 0) {
                $response["errores"][] = "Correo ya existente!";
            }
            if (mb_strlen($telefono, 'UTF-8') > 15) {
                $response["errores"][] = "El telefono tiene una longitub mayor a lo permitido";
            }
            if ($stmt4->rowCount() > 0) {
                $response["errores"][] = "Telefono ya existente!";
            }
            if (mb_strlen($direccion, 'UTF-8') > 150) {
                $response["errores"][] = "La dirección tiene una longitub mayor a lo permitido";
            }
            if (mb_strlen($usuario, 'UTF-8') > 50) {
                $response["errores"][] = "El usuario tiene una longitub mayor a lo permitido";
            }
            if ($stmt5->rowCount() > 0) {
                $response["errores"][] = "Usuario ya existente!";
            }
            if (mb_strlen($clave, 'UTF-8') > 255) {
                $response["errores"][] = "La clave tiene una longitub mayor a lo permitido";
            }

            if (!empty($response["errores"])) {
                echo json_encode($response);
                exit;
            }

            $stmt->execute([$id_col, $tipo_documento, $numero_documento, $nombres, $apellidos, $fecha_nacimiento, $generoo, $email, $telefono, $direccion, $usuario, $clave, $descripcion, $nombreImagen]);

            echo json_encode($response);
            exit;
        } catch (Exception $e) {
            $response["success"] = false;
            $response["message"] = "❌ Error al insertar en la base de datos: " . $e->getMessage();
            echo json_encode($response);
        }
        $response["errores"] = [];
    }

    // Funcion para mostrarDatosCoor
    function mostrarDatosCoor($usuario) {
        global $pdo;

        $sql = "SELECT * FROM COORDINADORES WHERE usuario = ?";

        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([$usuario]);

            // Esto es para obtener los datos en un array asosiativo
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if($resultado) {
                echo json_encode($resultado);
            } else {
                echo json_encode(["error" => "Usuario no encontrado"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }

        exit;
    }


    // CONDICIONALES PARA SABER LA ACCION Y LA FUNCION
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $accion = $_POST["accion"];

        // Condicional para saber la accion
        switch ($accion) {
            // Casos GET
            case 'GET':
                $funcion = $_POST["funcion"];

                // Condicional para saber la funcion
                switch ($funcion) {
                    
                    // Funcion para iniciar sesion
                    case 'iniciarSesion':
                        $usuario = $_POST["usuario"];
                        $clave = $_POST["clave"];
                        iniciarSesion($usuario, $clave);
                        break;
                    
                    // Por si no llega la funcion
                    default:
                        echo json_encode("Error");
                }
                break;

            // Casos INSERT
            case 'INSERT':
                $funcion = $_POST["funcion"];

                // Condicional para saber la funcion
                switch ($funcion) {

                    // Funcion para insertar un nuevo colegio
                    case 'crearCuentaColegio':
                        crearCuentaColegio();
                        break;
                    
                    // funcion para insertar un nuevo coordinador
                    case 'formularioCrearCuentaCoor':
                        formularioCrearCuentaCoor();
                        break;
                    
                    // Por si no llega la funcion
                    default:
                        echo json_encode("Error");
                }
                break;
            
            // Casos SELECT
            case 'SELECT':
                $funcion = $_POST["funcion"];
                switch ($funcion) {
                    case 'mostrarDatosCoor':
                        $usuario = $_POST["usuario"];
                        mostrarDatosCoor($usuario);
                        break;
                    
                    // Pro si no llega la funcion
                    default:
                        echo json_encode("Error");
                }
                break;
            // Por si no llega la accion
            default:
                echo json_encode("Error");
        }
    }

?>