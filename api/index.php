
<?php
mysqli_report(MYSQLI_REPORT_OFF);

$host = getenv('MYSQL_HOST');
$user = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');
$database = 'SG';

$conexion = false;

if ($host && $user && $password !== false && $password !== '') {
    $conexion = mysqli_init();

    if ($conexion) {
        mysqli_ssl_set($conexion, null, null, null, null, null);

        if (!mysqli_real_connect(
            $conexion,
            $host,
            $user,
            $password,
            $database,
            3306,
            null,
            MYSQLI_CLIENT_SSL
        )) {
            error_log('Error de conexión MySQL: ' . mysqli_connect_error());
            $conexion = false;
        }
    }
} else {
    error_log('Faltan variables de entorno MySQL.');
}

$resultado = false;

if ($conexion) {
    $sql = "SELECT name, credit_rating, address, city, state, country, zip_code
            FROM s_customer";
    $resultado = mysqli_query($conexion, $sql);

    if (!$resultado) {
        error_log('Error SQL: ' . mysqli_error($conexion));
    }
}

function h($valor) {
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Catalog</title>
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="jumbotron mt-4">
        <h1 class="display-4">Customer Catalog</h1>
        <p class="lead">Customer Catalog Sample Application</p>
        <hr class="my-4">
        <p>PHP sample application connected to a MySQL database to list a customer catalog</p>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
            <tr>
                <th>Name</th>
                <th>Credit Rating</th>
                <th>Address</th>
                <th>City</th>
                <th>State</th>
                <th>Country</th>
                <th>Zip</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$conexion): ?>
                <tr>
                    <td colspan="7">No se pudo conectar a la base de datos.</td>
                </tr>
            <?php elseif (!$resultado): ?>
                <tr>
                    <td colspan="7">No se pudieron cargar los clientes.</td>
                </tr>
            <?php else: ?>
                <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?= h($fila['name']) ?></td>
                        <td><?= h($fila['credit_rating']) ?></td>
                        <td><?= h($fila['address']) ?></td>
                        <td><?= h($fila['city']) ?></td>
                        <td><?= h($fila['state']) ?></td>
                        <td><?= h($fila['country']) ?></td>
                        <td><?= h($fila['zip_code']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
<?php
if ($resultado) {
    mysqli_free_result($resultado);
}
if ($conexion) {
    mysqli_close($conexion);
}
?>
