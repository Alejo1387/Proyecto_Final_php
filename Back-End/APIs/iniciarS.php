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
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$usuario, $clave]);

        $resultado = $stmt->fetch();

        if ($resultado) {
            $response["success"] = true;
        }
    } catch (PDOException $e) {
        $response["error"] = $e->getMessage();
    }

    echo json_encode($response);
?>