<?php
require_once __DIR__ . '/../config/db.php';
$db = getDB();
echo "--- DEUDAS DUPLICADAS POR VENTA_ID ---\n";
$res = $db->query("SELECT venta_id, count(*) as c FROM deudas GROUP BY venta_id HAVING c > 1")->fetchAll(PDO::FETCH_ASSOC);
print_r($res);

echo "\n--- TODAS LAS DEUDAS (ÚLTIMAS 10) ---\n";
$res2 = $db->query("SELECT * FROM deudas ORDER BY id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
print_r($res2);
?>