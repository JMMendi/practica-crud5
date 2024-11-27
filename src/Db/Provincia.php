<?php

namespace App\Db;

use \PDO;
use \PDOException;

require __DIR__."/../../vendor/autoload.php";

class Provincia extends Conexion{
    private int $id;
    private string $nombre;
    private string $color;

    private static function executeQuery($q, $options=[], bool $devolverAlgo = false) {
        $stmt = parent::getConexion()->prepare($q);
        try {
            (count($options)) ? $stmt->execute($options) : $stmt->execute();
        } catch (PDOException $ex) {
            throw new PDOException("Error: " . $ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }
        if ($devolverAlgo) {
            return $stmt;
        }
    }

    public function create() : void {
        $q = "insert into provincias(nombre, color) values (:n, :c)";
        self::executeQuery($q, [':n' => $this->nombre, ':c' => $this->color]);
        
    }

    public static function getIdsProvincias() : array {
        $q = "select id from provincias";
        $stmt = self::executeQuery($q, [], true);
        $ids = [];
        while ($fila=$stmt->fetch(PDO::FETCH_OBJ)) {
            $ids[] = $fila->id;
        }
        return $ids;
    }

    public static function read() : array {
        $q = "select id, nombre from provincias order by nombre";
        $stmt= self::executeQuery($q, [], true);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // ---------------------------------------------------------------
    public static function crearProvinciasRandom() : void {
        $faker = \Faker\Factory::create("es_ES");

        for ($i = 0 ; $i < 8 ; $i++) {
            $nombre= $faker->unique()->state();
            $color = $faker->unique()->colorName();

            (new Provincia)
            ->setNombre($nombre)
            ->setColor($color)
            ->create();
        }
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of nombre
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of color
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * Set the value of color
     */
    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
}