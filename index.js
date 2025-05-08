const express = require('express');
const mysql = require('mysql2');
const app = express();

app.get('/producto', (req, res) => {
    const codigo = req.query.codigo;

    if (!codigo) {
        return sendResponse(res, 600, null);
    }

    const conexion = mysql.createConnection({
        host: 'localhost',
        user: 'root',
        password: '',
        database: 'pos'
    });

    conexion.connect((err) => {
        if (err) {
            return sendResponse(res, 601, null);
        }

        const consulta = 'SELECT producto_nombre, producto_precio, producto_imagen FROM productos WHERE producto_codigo = ?';
        conexion.query(consulta, [codigo], (err, resultados) => {
            if (err) {
                return sendResponse(res, 602, null);
            }

            if (resultados.length > 0) {
                const producto = resultados[0];
                return sendResponse(res, 603, producto);
            } else {
                return sendResponse(res, 604, null);
            }
        });
    });
});

function sendResponse(res, status, data) {
    res.status(200).json({
        status: status,
        producto_nombre: data?.producto_nombre || "",
        producto_precio: data?.producto_precio || "",
        producto_imagen: data?.producto_imagen || ""
    });
}

app.listen(3000, () => {
    console.log('API escuchando en http://localhost:3000');
});
