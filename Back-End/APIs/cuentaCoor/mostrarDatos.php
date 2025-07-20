<?php

    ob_start();    

    header("Content-Type: application/json");
    ini_set('display_errors', 0);
    error_reporting(0);

    require_once "../conexion.php";

    $data = json_decode(file_get_contents("php://input"));

    $usuario = $data->usuario ?? '';

    // echo json_encode(["usuario" => $usuario]);

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
?>