
<?php
mysqli_report(MYSQLI_REPORT_OFF);

$host = getenv('MYSQL_HOST');
$user = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');

$conexion = mysqli_connect($host, $user, $password, "SG");

if (!$conexion) {
    error_log("Error de conexión MySQL: " . mysqli_connect_error());
    echo "<tr><td colspan='7'>No se pudo conectar a la base de datos. Revisa la configuración de MySQL.</td></tr>";
} else {
    $cadenaSQL = "SELECT name, credit_rating, address, city, state, country, zip_code FROM s_customer";
    $resultado = mysqli_query($conexion, $cadenaSQL);

    if (!$resultado) {
        error_log("Error en la consulta MySQL: " . mysqli_error($conexion));
        echo "<tr><td colspan='7'>No se pudieron cargar los clientes.</td></tr>";
    } else {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($fila['name'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($fila['credit_rating'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($fila['address'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($fila['city'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($fila['state'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($fila['country'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($fila['zip_code'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
            echo "</tr>";
        }
        mysqli_free_result($resultado);
    }

    mysqli_close($conexion);
}
?>
