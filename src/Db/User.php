<?php

namespace App\Db;

use \PDO;
use \PDOException;

require __DIR__."/../../vendor/autoload.php";

class User extends Conexion {
    private int $id;
    private string $username;
    private string $email;
    private string $imagen;
    private int $provincia_id;

    public function create() : void {
        $q = "insert into users(username, email, imagen, provincia_id) values (:u, :e, :i, :p)";
        $stmt = parent::getConexion()->prepare($q);

        try {
            $stmt->execute([
                ':u' => $this->username,
                ':e' => $this->email,
                ':i' => $this->imagen,
                ':p' => $this->provincia_id
            ]);
        } catch (PDOException $ex) {
            throw new PDOException("Error en el create: " . $ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }
    }

    public static function read(?int $id = null) : array {
        $q = ($id === null) ? "select users.*, nombre, color from users,provincias where provincia_id=provincias.id order by users.id desc" : "select users.*, provincias.id as provid from users, provincias where users.id=:i";
        $stmt = parent::getConexion()->prepare($q);

        try {
            ($id === null) ? $stmt->execute() : $stmt->execute([':i' => $id]);
        } catch (PDOException $ex) {
            throw new PDOException("Error en el create: " . $ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getUserById(int $id) : bool | User {
        $q = "select users.* from users where id=:i";

        $stmt = parent::getConexion()->prepare($q);

        try {
            $stmt->execute([':i' => $id]);
        } catch (PDOException $ex) {
            throw new PDOException("Error en el create: " . $ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }
        $stmt -> setFetchMode(PDO::FETCH_CLASS, User::class);
        $usuario = $stmt->fetch(PDO::FETCH_CLASS, self::class);
        return (count($usuario)) ? $usuario[0] : false;
    }
            

    public static function getImagenById(int $id) : string {
        $q = "select imagen from users where id=:i";
        $stmt = parent::getConexion()->prepare($q);

        try {
            $stmt->execute([':i' => $id]);
        } catch (PDOException $ex) {
            throw new PDOException("Error en el getImagenById: " . $ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }
        if(!$fila = $stmt->fetch(PDO::FETCH_OBJ)) {
            return false;
        }
        return $fila->imagen;
    }

    public static function delete(int $id) : void {
        $q = "delete from users where id=:i";
        $stmt = parent::getConexion()->prepare($q);

        try {
            $stmt->execute([
                ':i' => $id
            ]);
        } catch (PDOException $ex) {
            throw new PDOException("Error en el delete: " . $ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }
    }

    // -----------------------------------------------------

    public static function esCampoUnico(string $nomCampo, string $valor, ?int $id=null) : bool {
        $q = ($id === null) ? "select count(*) as total from users where $nomCampo = :v" : "select count(*) as total from users where $nomCampo = :v AND id !=:i";
        $stmt = parent::getConexion()->prepare($q);

        try {
            ($id === null) ? $stmt->execute([':v' => $valor]) : $stmt->execute([':v' => $valor, ':i' => $id]);
        } catch (PDOException $ex) {
            throw new PDOException("Error en el create: " . $ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }

        return $stmt->fetch(PDO::FETCH_OBJ)->total;
    }

    public static function crearRegistrosRandom (int $cantidad) : void {
        $faker = \Faker\Factory::create("es_ES");
        $faker->addProvider(new \Mmo\Faker\FakeimgProvider($faker));

        for ($i = 0 ; $i < $cantidad ; $i++){
            $username = $faker->unique()->userName();
            $email = $username."@".$faker->freeEmailDomain();
            $text = strtoupper(substr($username,0,3));
            $imagen = "img/".$faker->fakeImg(dir: "./../public/img", width: 640, height: 480, fullPath: false, text: $text, backgroundColor: \Mmo\Faker\FakeimgUtils::createColor(random_int(0,255), random_int(0,255), random_int(0,255)));
            $provincia_id = $faker->randomElement(Provincia::getIdsProvincias());

            (new User)
            ->setUsername($username)
            ->setEmail($email)
            ->setImagen($imagen)
            ->setProvinciaId($provincia_id)
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
     * Get the value of username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of imagen
     */
    public function getImagen(): string
    {
        return $this->imagen;
    }

    /**
     * Set the value of imagen
     */
    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get the value of provincia_id
     */
    public function getProvinciaId(): int
    {
        return $this->provincia_id;
    }

    /**
     * Set the value of provincia_id
     */
    public function setProvinciaId(int $provincia_id): self
    {
        $this->provincia_id = $provincia_id;

        return $this;
    }
}