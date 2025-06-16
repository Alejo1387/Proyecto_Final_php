<?php

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
        $foto = $_POST["foto"];

        $nombreImagen = "defaultPerfilColegio.png";

        if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
            $archivoTmp = $_FILES["foto"]["tmp_name"];
            $nombreOriginal = basename($_FILES["foto"]["name"]);

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
        ";
        $stmt3 = $pdo->prepare($sqlVerificarEmail);

        try {
            $stmt3->execute([$email, $email, $email]);

            if($stmt3->rowCount() > 0) {
                $error = "Correo ya existente!";
            } else {
                $stmt->execute([$nit, $nombre, $direccion, $telefono, $email, $pais, $departamento, $ciudad, $descripcion, $nombreImagen]);

                $idColegio = $pdo->lastInsertId();

                echo "
                <script>
                    localStorage.setItem('seccionActualCrearC', 'pagina2');
                    localStorage.setItem('idColegioInsertado', '$idColegio');
                    window.location.href = window.location.href;
                </script>
                ";
            }
        } catch (Exception $e) {
            echo "❌ Error al insertar en la base de datos: " . $e->getMessage();
        }
    } else {
        echo "❌ Método no permitido.";
    }
?>