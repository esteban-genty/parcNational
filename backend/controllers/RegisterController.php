<?php
// === CORS headers ===
header("Access-Control-Allow-Origin: http://localhost:5173"); // React app
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "success" => false,
        "error" => "Seules les requêtes POST sont autorisées"
    ]);
    exit();
}

require_once "../models/Signin.php";

try {
    // Récupération des données JSON 
    $data = json_decode(file_get_contents("php://input"), true);


    //Si les données requises sont absentes, erreur
    if (!isset($data['nom'], $data['email'], $data['mot_de_passe'])) {
        throw new Exception("Données manquantes");
    }

    $nom = trim($data['nom']);
    $email = trim($data['email']);
    $motDePasse = $data['mot_de_passe'];
    $role = $data['role'] ?? "visiteur";

    $signin = new Singin();
    $result = $signin->register($nom, $email, $motDePasse, $role);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Inscription réussie"]);
    } else {
        echo json_encode(["success" => false, "error" => "Impossible d'enregistrer l'utilisateur"]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
