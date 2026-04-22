<?php
$conn = new mysqli("localhost:3307", "root", "", "cadastro");
if ($conn->connect_error) {
    die("Erro de conexão: " . $con->connect_error);
}
?>