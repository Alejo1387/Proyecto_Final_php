<?php

    ob_clean();
    header("Content-Type: application/json");
    ini_set('display_errors', 0);
    error_reporting(0);

    require_once "conexion.php";

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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $accion = $_POST["accion"];

        switch ($accion) {
            case 'GET':
                $funcion = $_POST["funcion"];
                switch ($funcion) {
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
            // Por si no llega la accion
            default:
                echo json_encode("Error");
        }
    }

?>