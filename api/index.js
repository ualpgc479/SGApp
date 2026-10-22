import mysql from 'mysql2/promise';

export default async function handler(req, res) {
  try {
    const connection = await mysql.createConnection({
      host: process.env.MYSQL_HOST,
      user: process.env.MYSQL_USER,
      password: process.env.MYSQL_PASSWORD,
      database: 'SG'
    });

    const [rows] = await connection.execute('SELECT * FROM s_customer LIMIT 20');
    await connection.end();

    let html = `
      <!DOCTYPE html>
      <html>
      <head>
        <title>SGApp - Datos MySQL</title>
        <style>
          body { font-family: Arial, sans-serif; margin: 30px; background: #f4f4f9; }
          h1 { color: #333; }
          table { border-collapse: collapse; width: 100%; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
          th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
          th { background-color: #0070f3; color: white; }
          tr:hover { background-color: #f1f1f1; }
        </style>
      </head>
      <body>
        <h1>Catálogo SGApp - Google Cloud SQL</h1>
        <table>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Teléfono</th>
          </tr>`;

    rows.forEach(row => {
      html += `
        <tr>
          <td>${row.id || row.ID || ''}</td>
          <td>${row.name || row.NAME || ''}</td>
          <td>${row.phone || row.PHONE || ''}</td>
        </tr>`;
    });

    html += `
        </table>
      </body>
      </html>`;

    res.setHeader('Content-Type', 'text/html');
    return res.status(200).send(html);
  } catch (error) {
    return res.status(500).send(`<h1>Error conectando a la base de datos</h1><p>${error.message}</p>`);
  }
}
