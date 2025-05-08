from flask import Flask, request, jsonify
import mysql.connector

app = Flask(__name__)

@app.route("/producto", methods=["GET"])
def get_producto():
    codigo = request.args.get("codigo")
    if not codigo:
            return jsonify({"status": 400, "producto_nombre": "", "producto_precio": "", "producto_imagen": ""}), 400

    try:
        conn = mysql.connector.connect(host="localhost", user="root", password="", database="pos")
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT producto_nombre, producto_precio, producto_imagen FROM productos WHERE producto_codigo = %s", (codigo,))
        row = cursor.fetchone()
        if row:
            return jsonify({"status": 200, **row}), 200
        else:
            return jsonify({"status": 404, "producto_nombre": "", "producto_precio": "", "producto_imagen": ""}), 404
    except:
        print("ERROR", e)
        return jsonify({"status": 500, "producto_nombre": "", "producto_precio": "", "producto_imagen": ""}), 500

if __name__ == "__main__":
    app.run(debug=True)
