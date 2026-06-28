<?php

session_start();
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if (empty($_SESSION['is_admin'])) {
    echo json_encode(['erro' => 'Não autorizado']);
    exit;
}

$tipo = $_GET['tipo'] ?? 'mais_vendidas';

if ($tipo === 'faturamento') {
    $sql = "SELECT p.nome, SUM(i.quantidade * i.preco_unitario) AS total
            FROM itens_pedido i
            JOIN produtos p ON p.id = i.id_produto
            GROUP BY p.nome
            ORDER BY total DESC";
} else {
    $sql = "SELECT p.nome, SUM(i.quantidade) AS total
            FROM itens_pedido i
            JOIN produtos p ON p.id = i.id_produto
            GROUP BY p.nome
            ORDER BY total DESC";
}

$linhas = $pdo->query($sql)->fetchAll();


$labels = [];
$valores = [];
foreach ($linhas as $linha) {
    $labels[]  = $linha['nome'];
    $valores[] = (float)$linha['total'];
}

echo json_encode(['labels' => $labels, 'valores' => $valores]);
