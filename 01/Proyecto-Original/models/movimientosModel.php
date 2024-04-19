<?php

/*
    Modelo Movimientos Model
*/

class movimientosModel extends Model
{

    # Método get 
    # Consulta select sobre la tabla movimientos

    public function get()
    {
        try {

            $sql = "SELECT id,
            id_cuenta,
            fecha_hora,
            concepto,
            tipo,
            cantidad,
            saldo
            FROM movimientos order by id";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {

            require_once("template/partials/errorDB.php");
            exit();

        }

    }

    public function getMovCuenta($idCuenta)
    {
        try {
            $sql = " 
            SELECT 
                m.id,
                m.id_cuenta,
                m.fecha_hora,
                m.concepto,
                m.tipo,
                m.cantidad,
                m.saldo,
                cu.num_cuenta
            FROM 
                movimientos as m inner join cuentas as cu on m.id_cuenta=cu.id 
            WHERE
                cu.id=:id;";

            $conexion = $this->db->connect();

            $result = $conexion->prepare($sql);

            $result->bindParam(':id', $idCuenta);
            //Establez como quiero q devuelva el resultado 

            $result->setFetchMode(PDO::FETCH_OBJ);

            // ejecuto
            $result->execute();
            return $result;

        } catch (PDOException $e) {
            require_once("template/partials/errorDB.php");
            exit();
        }
    }

    public function getAllCuentas()
    {
        try {
            $sql = "SELECT id, num_cuenta FROM cuentas";
            $conexion = $this->db->connect();
            $result = $conexion->prepare($sql);
            $result->setFetchMode(PDO::FETCH_OBJ);
            $result->execute();

            return $result->fetchAll();
        } catch (PDOException $e) {
            require_once("template/partials/errorDB.php");
            exit();
        }
    }

    public function create($mov)
    {
        try {
            $conexion = $this->db->connect();

            // Obtener el saldo actual de la cuenta
            $sqlSaldoActual = "SELECT saldo FROM cuentas WHERE id = :id_cuenta";
            $pdoStSaldoActual = $conexion->prepare($sqlSaldoActual);
            $pdoStSaldoActual->bindParam(":id_cuenta", $mov->id_cuenta, PDO::PARAM_INT);
            $pdoStSaldoActual->execute();
            $saldoActual = $pdoStSaldoActual->fetchColumn();

            // Calcular el nuevo saldo
            $nuevoSaldo = $saldoActual + $mov->cantidad;

            // HACER QUE NO SE PUEDA SACAR MÁS DINERO DEL QUE HAY EN LA CUENTA
            if ($mov->cantidad < 0 && $saldoActual < -$mov->cantidad) {
                $mensajeError = "Cantidad a retirar mayor a la del saldo";
                
                header('Location: ' . URL . 'movimientos/nuevo/' . $mov->id_cuenta);
            }

            // Insertar en la tabla movimientos
            $sqlMovimientos = "INSERT INTO movimientos (id_cuenta, fecha_hora, concepto, tipo, cantidad) 
                            VALUES (:id_cuenta, :fecha_hora, :concepto, :tipo, :cantidad)";
            $pdoStMovimientos = $conexion->prepare($sqlMovimientos);

            $pdoStMovimientos->bindParam(":id_cuenta", $mov->id_cuenta, PDO::PARAM_INT);
            $pdoStMovimientos->bindParam(":fecha_hora", $mov->fecha_hora);
            $pdoStMovimientos->bindParam(":concepto", $mov->concepto, PDO::PARAM_STR);
            $pdoStMovimientos->bindParam(":tipo", $mov->tipo, PDO::PARAM_STR);
            $pdoStMovimientos->bindParam(":cantidad", $mov->cantidad, PDO::PARAM_INT);

            $pdoStMovimientos->execute();


            $sqlUpdateMovimientos = "UPDATE movimientos 
                                 SET saldo = :nuevo_saldo
                                 WHERE id_cuenta = :id_cuenta order by id desc limit 1";
            $pdoStUpdateMovimientos = $conexion->prepare($sqlUpdateMovimientos);

            $pdoStUpdateMovimientos->bindParam(":nuevo_saldo", $nuevoSaldo, PDO::PARAM_INT);
            $pdoStUpdateMovimientos->bindParam(":id_cuenta", $mov->id_cuenta, PDO::PARAM_INT);

            $pdoStUpdateMovimientos->execute();

            // Actualizar la cuenta
            $sqlUpdateCuenta = "UPDATE cuentas 
                            SET saldo = saldo + :cantidad,
                                num_movtos = num_movtos + 1,
                                fecha_ul_mov = NOW(),
                                update_at = NOW() 
                            WHERE id = :id";
            $pdoStUpdateCuenta = $conexion->prepare($sqlUpdateCuenta);

            $pdoStUpdateCuenta->bindParam(":cantidad", $mov->cantidad, PDO::PARAM_INT);
            $pdoStUpdateCuenta->bindParam(":id", $mov->id_cuenta, PDO::PARAM_INT);

            $pdoStUpdateCuenta->execute();

        } catch (PDOException $e) {
            require_once("template/partials/errorDB.php");
            exit();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    # Método order
    # Permite ordenar la tabla por cualquiera de las columnas de la tabla
    public function order(int $criterio)
    {
        try {

            $sql = " 
                SELECT 
                    id,
                    id_cuenta,
                    fecha_hora,
                    concepto,
                    tipo,
                    cantidad,
                    saldo
                FROM 
                    movimientos
                ORDER BY
                    :criterio ";

            $conexion = $this->db->connect();
            $pdoSt = $conexion->prepare($sql);
            $pdoSt->bindParam(':criterio', $criterio, PDO::PARAM_INT);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();
            return $pdoSt;

        } catch (PDOException $e) {
            require_once("template/partials/errorDB.php");
            exit();
        }
    }

    # Método filter
    # Permite filtrar la tabla movimientos a partir de una expresión de búsqueda o filtrado
    public function filter($expresion)
    {
        try {

            $sql = "
                    SELECT 
                        id,
                        id_cuenta,
                        fecha_hora,
                        concepto,
                        tipo,
                        cantidad,
                        saldo
                    FROM 
                        movimientos
                    WHERE 
                        concat_ws(  ' ',
                                    id,
                                    id_cuenta,
                                    fecha_hora,
                                    concepto,
                                    tipo,
                                    cantidad,
                                    saldo
                                )
                    LIKE
                        :expresion ";


            $conexion = $this->db->connect();

            $expresion = "%" . $expresion . "%";
            $pdoSt = $conexion->prepare($sql);

            $pdoSt->bindValue(':expresion', $expresion, PDO::PARAM_STR);
            $pdoSt->setFetchMode(PDO::FETCH_OBJ);
            $pdoSt->execute();

            return $pdoSt;
        } catch (PDOException $e) {
            require_once("template/partials/errorDB.php");
            exit();
        }
    }

}