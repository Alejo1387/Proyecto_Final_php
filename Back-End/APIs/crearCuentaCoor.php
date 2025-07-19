<?php

    ob_clean();
    header("Content-Type: application/json");
    ini_set('display_errors', 0);
    error_reporting(0);

    require_once "conexion.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

        if (isset($_FILES["foto_coor"]) && $_FILES["foto_coor"]["error"] === 0) {
            $foto = $_FILES["foto_coor"]["name"];
        } else {
            $foto = "";
        }

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

?>