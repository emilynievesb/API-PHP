<?php
namespace APP;

class personal_ref extends connect
{
    private $queryPost = 'INSERT INTO personal_ref ( full_name, cel_number, relationship, occupation) VALUES ( :name, :cel, :relationship, :occupation)';
    private $queryPut = 'UPDATE personal_ref SET full_name = :area_id, cel_number = :cel, relationship = :relationship, occupation = :occupation WHERE  id = :id';
    private $queryGetAll = 'SELECT  id AS "id", full_name AS "name", cel_number AS "cel", relationship AS "relationship", occupation AS "occupation" FROM personal_ref';
    private $queryDelete = 'DELETE FROM personal_ref WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(public $id = 1, public $full_name = 1, public $cel_number = 1, public $relationship = 1, public $occupation = 1)
    {
        parent::__construct();

    }
    public function postPersonalRef()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPost */
            $res->bindValue("name", $this->full_name);
            $res->bindValue("cel", $this->cel_number);
            $res->bindValue("relationship", $this->relationship);
            $res->bindValue("occupation", $this->occupation);

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

    public function updatePersonalRef()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asigna valores al alias que puse en el queryPut */
            $res->bindValue("id", $this->id);
            $res->bindValue("name", $this->full_name);
            $res->bindValue("cel", $this->cel_number);
            $res->bindValue("relationship", $this->relationship);
            $res->bindValue("occupation", $this->occupation);
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

    public function deletePersonalRef()
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

    public function getAllPersonalRef()
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