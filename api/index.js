export default function handler(req, res) {
  const host = process.env.MYSQL_HOST || 'No configurado';
  const user = process.env.MYSQL_USER || 'No configurado';

  const html = `
    <!DOCTYPE html>
    <html lang="es">
    <head>
      <meta charset="UTF-8">
      <title>SGApp - Despliegue en Vercel</title>
      <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f9; text-align: center; }
        .card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); display: inline-block; }
        h1 { color: #0070f3; }
        p { color: #555; font-size: 16px; }
        .status { color: #28a745; font-weight: bold; }
      </style>
    </head>
    <body>
      <div class="card">
        <h1>Conexión SGApp - Google Cloud SQL</h1>
        <p>Estado del Despliegue Serverless: <span class="status">ACTIVO (Ready)</span></p>
        <hr>
        <p><strong>Host MySQL:</strong> ${host}</p>
        <p><strong>Usuario MySQL:</strong> ${user}</p>
      </div>
    </body>
    </html>
  `;

  res.setHeader('Content-Type', 'text/html; charset=utf-8');
  return res.status(200).send(html);
}
