<?php
namespace APP\emergency_contact;

use APP\db\connect;
use APP\getInstance;

class emergency_contact extends connect
{
    private $queryPost = 'INSERT INTO emergency_contact ( id_staff , cel_number, relationship, full_name, email) VALUES ( :staff_id_fk, :cel, :relationship,:full_name, :email)';
    private $queryPut = 'UPDATE emergency_contact SET id_staff = :staff_id_fk, cel_number = :cel,relationship  = :relationship, full_name = :full_name, email = :email WHERE  id = :id';
    private $queryGetAll = 'SELECT  emergency_contact.id AS "id",
    emergency_contact.id_staff  AS "staff_id_fk",
    staff.first_name AS "name_staff_fk",
    emergency_contact.cel_number AS "cel",
    emergency_contact.relationship AS "relationship",
    emergency_contact.full_name AS "full_name",
    emergency_contact.email AS "email"
    FROM emergency_contact
    INNER JOIN staff ON emergency_contact.id_staff = staff.id';
    private $queryDelete = 'DELETE FROM emergency_contact WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(private $id = 1, private $id_staff = 1, private $cel_number = 1, private $relationship = 1, private $full_name = 1, private $email = 1)
    {
        parent::__construct();

    }
    public function post_emergency_contact()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("staff_id_fk", $this->id_staff);
            $res->bindValue("cel", $this->cel_number);
            $res->bindValue("relationship", $this->relationship);
            $res->bindValue("full_name", $this->full_name);
            $res->bindValue("email", $this->email);
            /**Execute es para ejecutar */
            $res->execute();
            $this->message = ["Code" => 200 + $res->rowCount(), "Message" => "Inserted data", "res" => $res];
        } catch (\PDOException $e) {
            /**Message es un array asociativo */
            $this->message = ["Code" => $e->getCode(), "Message" => $res->errorInfo()[2]];

        } finally {
            print_r($this->message);
        }
    }

    public function update_emergency_contact($id)
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindParam("id", $id);
            $res->bindValue("staff_id_fk", $this->id_staff);
            $res->bindValue("cel", $this->cel_number);
            $res->bindValue("relationship", $this->relationship);
            $res->bindValue("full_name", $this->full_name);
            $res->bindValue("email", $this->email);
            /**Execute es para ejecutar */
            $res->execute();
            if ($res->rowCount() > 0) {
                $this->message = ["Code" => 200 + $res->rowCount(), "Message" => "Data updated"];
            } else {
                $this->message = ["Code" => 404, "Message" => "Data not founded"];

            }
        } catch (\PDOException $e) {
            /**Message es un array asociativo */
            $this->message = ["Code" => $e->getCode(), "Message" => $res->errorInfo()[2]];
        } finally {
            print_r($this->message);
        }
    }

    public function delete_emergency_contact($id)
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryDelete);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            $res->bindParam("id", $id);
            /**Execute es para ejecutar */
            $res->execute();

            if ($res->rowCount() > 0) {
                $this->message = ["Code" => 200 + $res->rowCount(), "Message" => "Data deleted"];
            } else {
                $this->message = ["Code" => 404, "Message" => "Data not founded"];
            }
        } catch (\PDOException $e) {
            /**Message es un array asociativo */
            $this->message = ["Code" => $e->getCode(), "Message" => $res->errorInfo()[2]];
        } finally {
            print_r($this->message);
        }
    }

    public function getAll_emergency_contact()
    {
        try {
            $res = $this->conx->prepare($this->queryGetAll);
            $res->execute();
            $this->message = ["Code" => 200, "Message" => $res->fetchAll(\PDO::FETCH_ASSOC)];
        } catch (\PDOException $e) {
            $this->message = ["Code" => $e->getCode(), "Message" => $res->errorInfo()[2]];
        } finally {
            print_r($this->message);
        }
    }
}
?>