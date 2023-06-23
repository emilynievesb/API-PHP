<?php
namespace APP;

class contact_info extends connect
{
    private $queryPost = 'INSERT INTO contact_info (id_staff, whatsapp, instagram, linkedin, email, address, cel_number) VALUES (:staff, :wpp, :ig, :linkedin, :email, :address, :number)';
    private $queryPut = 'UPDATE contact_info SET id_staff = :staff, whatsapp = :wpp, instagram = :ig, linkedin =:linkedin, email =:email, address =:address,cel_number=:number  WHERE  id = :id';
    private $queryGetAll = 'SELECT  id AS "id", id_staff AS "staff", whatsapp AS "wpp", instagram AS "ig", linkedin AS "linkedin", email AS "email", address AS "address", cel_number AS "number" FROM contact_info';
    private $queryDelete = 'DELETE FROM contact_info WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(private $id = 1, private $id_staff = 1, private $whatsapp = 1, private $instagram = 1, public $linkedin = 1, private $email = 1, private $address = 1, private $cel_number = 1)
    {
        parent::__construct();

    }
    public function postContactInfo()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("staff", $this->id_staff);
            $res->bindValue("wpp", $this->whatsapp);
            $res->bindValue("ig", $this->instagram);
            $res->bindValue("linkedin", $this->linkedin);
            $res->bindValue("email", $this->email);
            $res->bindValue("address", $this->address);
            $res->bindValue("number", $this->cel_number);
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

    public function updateContactInfo()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindValue("id", $this->id);
            $res->bindValue("staff", $this->id_staff);
            $res->bindValue("wpp", $this->whatsapp);
            $res->bindValue("ig", $this->instagram);
            $res->bindValue("linkedin", $this->linkedin);
            $res->bindValue("email", $this->email);
            $res->bindValue("address", $this->address);
            $res->bindValue("number", $this->cel_number);
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

    public function deleteContactInfo()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryDelete);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            $res->bindValue("id", $this->id);
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

    public function getAllContactInfo()
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