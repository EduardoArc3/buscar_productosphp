<?php
header("Content-Type: application/json");

if (!isset($_GET["codigo"]) || empty($_GET["codigo"])) {
    enviarRespuesta(600, null);    
} else {

    $bd = mysqli_connect("localhost", "root", "", "pos");

    if (!$bd) {
        enviarRespuesta(601, null);    
    }

    $sql = "SELECT producto_nombre, producto_precio, producto_imagen
            FROM productos WHERE producto_codigo = '" . $_GET["codigo"] . "'";

    $res = mysqli_query($bd, $sql);

    if (!$res) {
        enviarRespuesta(602, null);
    }

    if (mysqli_num_rows($res) > 0) {
        while ($filaProducto = mysqli_fetch_assoc($res)) {
            $producto["nombre"] = $filaProducto["producto_nombre"];
            $producto["precio"] = $filaProducto["producto_precio"];
            $producto["imagen"] = $filaProducto["producto_imagen"];
        }
        enviarRespuesta(603, $producto);
    } else {
        enviarRespuesta(604, null);
    }
}

function enviarRespuesta($codigoEstado, $infoProducto) {
    header("HTTP/1.1 " . $codigoEstado);

    $respuesta["estado"] = $codigoEstado;
    $respuesta["nombre"] = empty($infoProducto["nombre"]) ? "" : $infoProducto["nombre"];
    $respuesta["precio"] = empty($infoProducto["precio"]) ? "" : $infoProducto["precio"];
    $respuesta["imagen"] = empty($infoProducto["imagen"]) ? "" : $infoProducto["imagen"];

    echo json_encode($respuesta);
}
