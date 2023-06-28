<?php
namespace APP\working_info;

use APP\db\connect;
use APP\getInstance;

class working_info extends connect
{
    private $queryPost = 'INSERT INTO working_info (id_staff, years_exp, months_exp, id_work_reference, id_personal_ref, start_contract, end_contract)
    VALUES (:staff_fk, :n_years, :n_months, :work_reference_fk, :personal_ref_fk, :start_contract, :end_contract)';
    private $queryPut = 'UPDATE working_info SET id_staff = :staff_fk, years_exp = :n_years, months_exp = :n_months, id_work_reference =:work_reference_fk, id_personal_ref =:personal_ref_fk, start_contract =:start_contract,end_contract=:end_contract  WHERE  id = :id';
    private $queryGetAll = 'SELECT  working_info.id AS "id",
    working_info.id_staff AS "staff",
    working_info.years_exp AS "n_years",
    working_info.months_exp AS "n_months",
    working_info.id_work_reference AS "work_reference_fk",
    working_info.id_personal_ref AS "personal_ref_fk",
    working_info.start_contract AS "start_contract",
    working_info.end_contract AS "end_contract"
    personal_ref.full_name as "name_personal_ref_fk",
    work_reference.full_name as "name_work_reference_fk",
    FROM working_info
    INNER JOIN personal_ref ON working_info.id_personal_ref =  personal_ref.id
    INNER JOIN work_reference ON working_info.id_work_reference = work_reference.id';
    private $queryDelete = 'DELETE FROM working_info WHERE id = :id';
    private $message;

    use getInstance;
    //*Se definen el tipo de dato: static, private, public
    function __construct(private $id = 1, private $id_staff = 1, private $years_exp = 1, private $months_exp = 1, public $id_work_reference = 1, private $id_personal_ref = 1, private $start_contract = 1, private $end_contract = 1)
    {
        parent::__construct();

    }
    public function post_working_info()
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPost);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asn_monthsna valores al alias que puse en el queryPost */
            $res->bindValue("staff_fk", $this->id_staff);
            $res->bindValue("n_years", $this->years_exp);
            $res->bindValue("n_months", $this->months_exp);
            $res->bindValue("work_reference_fk", $this->id_work_reference);
            $res->bindValue("personal_ref_fk", $this->id_personal_ref);
            $res->bindValue("start_contract", $this->start_contract);
            $res->bindValue("end_contract", $this->end_contract);
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

    public function update_working_info($id)
    {
        /*Prepare es literalmente preparar el query */
        $res = $this->conx->prepare($this->queryPut);
        /**Todas las solicitudes, así sea un connect deben intentarse dentro de un try-catch */
        try {
            /**El bindValue le asn_monthsna valores al alias que puse en el queryPut */
            $res->bindParam("id", $id);
            $res->bindValue("staff_fk", $this->id_staff);
            $res->bindValue("n_years", $this->years_exp);
            $res->bindValue("n_months", $this->months_exp);
            $res->bindValue("work_reference_fk", $this->id_work_reference);
            $res->bindValue("personal_ref_fk", $this->id_personal_ref);
            $res->bindValue("start_contract", $this->start_contract);
            $res->bindValue("end_contract", $this->end_contract);
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

    public function delete_working_info($id)
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

    public function getAll_working_info()
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