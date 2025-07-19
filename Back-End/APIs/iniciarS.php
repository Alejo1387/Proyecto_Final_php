<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    header("Content-Type: application/json");
    require_once "./conexion.php";

    $data = json_decode(file_get_contents("php://input"));

    $usuario = $data->usuario ?? '';
    $clave = $data->clave ?? '';

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
?>