
<?php
mysqli_report(MYSQLI_REPORT_OFF);

$host = getenv('MYSQL_HOST');
$user = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');
$database = 'SG';

function mostrarError($mensaje) {
    echo "<tr><td colspan='7'>" .
         htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') .
         "</td></tr>";
}

// Comprobar las variables de entorno.
if (!$host || !$user || $password === false || $password === '') {
    error_log("Faltan variables de entorno de conexión MySQL.");
    mostrarError("Falta configuración de conexión a la base de datos.");
} else {
    // Inicializar MySQLi.
    $conexion = mysqli_init();

    if (!$conexion) {
        error_log("No se pudo inicializar MySQLi.");
        mostrarError("No se pudo inicializar la conexión a la base de datos.");
    } else {
        // Solicitar conexión TLS.
        mysqli_ssl_set($conexion, null, null, null, null, null);

        $conectado = mysqli_real_connect(
            $conexion,
            $host,
            $user,
            $password,
            $database,
            3306,
            null,
            MYSQLI_CLIENT_SSL
        );

        if (!$conectado) {
            error_log(
                "Error de conexión MySQL: " . mysqli_connect_error()
            );
            mostrarError(
                "No se pudo conectar a la base de datos. Revisa la configuración de MySQL."
            );
        } else {
            $cadenaSQL = "
                SELECT
                    name,
                    credit_rating,
                    address,
                    city,
                    state,
                    country,
                    zip_code
                FROM s_customer
            ";

            $resultado = mysqli_query($conexion, $cadenaSQL);

            if (!$resultado) {
                error_log(
                    "Error en la consulta MySQL: " .
                    mysqli_error($conexion)
                );
                mostrarError("No se pudieron cargar los clientes.");
            } else {
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>";

                    echo "<td>" .
                        htmlspecialchars(
                            $fila['name'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) .
                        "</td>";

                    echo "<td>" .
                        htmlspecialchars(
                            $fila['credit_rating'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) .
                        "</td>";

                    echo "<td>" .
                        htmlspecialchars(
                            $fila['address'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) .
                        "</td>";

                    echo "<td>" .
                        htmlspecialchars(
                            $fila['city'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) .
                        "</td>";

                    echo "<td>" .
                        htmlspecialchars(
                            $fila['state'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) .
                        "</td>";

                    echo "<td>" .
                        htmlspecialchars(
                            $fila['country'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) .
                        "</td>";

                    echo "<td>" .
                        htmlspecialchars(
                            $fila['zip_code'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) .
                        "</td>";

                    echo "</tr>";
                }

                mysqli_free_result($resultado);
            }

            mysqli_close($conexion);
        }
    }
}
?>
