<?php
    ob_clean();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    header("Content-Type: application/json");

    require_once "conexion.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nit = $_POST["nit"];
        $nombre = $_POST["nombre"];
        $direccion = $_POST["direccion"];
        $telefono = $_POST["telefono"];
        $email = $_POST["email"];
        $pais = $_POST["pais"];
        $departamento = $_POST["departamento"];
        $ciudad = $_POST["ciudad"];
        $descripcion = $_POST["descripcion"];
        
        if (isset($_FILES["foto_perfil_colegio"]) && $_FILES["foto_perfil_colegio"]["error"] === 0) {
            $foto = $_FILES["foto_perfil_colegio"]["name"];
        } else {
            $foto = "";
        }

        // $foto = $_FILES["foto_perfil_colegio"];
    

        // $response = array($nit, $nombre, $direccion, $telefono, $email, $pais, $departamento, $ciudad, $descripcion, $foto);
        // echo json_encode($response);

        

        $nombreImagen = "defaultPerfilColegio.png";

        if (isset($_FILES["foto_perfil_colegio"]) && $_FILES["foto_perfil_colegio"]["error"] === UPLOAD_ERR_OK) {
            $archivoTmp = $_FILES["foto_perfil_colegio"]["tmp_name"];
            $nombreOriginal = basename($_FILES["foto_perfil_colegio"]["name"]);

            // Sanitizar nombre y evitar espacios raros
            $nombreImagen = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $nombreOriginal);
            $rutaDestino = __DIR__ . "/uploads/" . $nombreImagen;

            if (!move_uploaded_file($archivoTmp, $rutaDestino)) {
                $error =  "❌ Error al subir la imagen. Asegúrate de que la carpeta 'uploads/' exista y tenga permisos.";
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
        SELECT email FROM COLEGIOS WHERE telefono = ?
        ";
        $stmt4 = $pdo->prepare($sqlVerificarNumero);

        $response = [
            "errores" => []
        ];

        try {
            $stmt3->execute([$email, $email, $email, $email]);
            $stmt4->execute([$telefono, $telefono, $telefono, $telefono]);

            if (mb_strlen($nit, 'UTF-8') > 20) {
                $response["errores"][] = "El nit tiene una longitub mayor a lo permitido";
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
    } else {
        $response["success"] = false;
        $response["message"] = "❌ Método no permitido.";
        echo json_encode($response);
    }

    exit;
?>