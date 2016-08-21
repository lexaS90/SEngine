<?php


namespace SEngine\Core;


use SEngine\Core\Libs\Singleton;

class Db
{
    use Singleton;

    protected $dbh;

    /**
     * Подключение к баззе данных
     * Db constructor.
     */
    protected function __construct(){
        try{
            $dbHost = Config::instance()->db->db_host;
            $dbName = Config::instance()->db->db_name;
            $dbUser = Config::instance()->db->db_user;
            $dbPass = Config::instance()->db->db_pass;

            $this->dbh = new \PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPass);
            $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }catch (\PDOException $ex){
            throw  new \SEngine\Core\Exceptions\Db($ex->getMessage());           
        }
    }

    /**
     * Выполнение запросса
     * @param $sql
     * @param array $params
     * @return bool
     * @throws Exceptions\Db
     */
    public function execute($sql, $params = [])
    {
        $sth = $this->dbh->prepare($sql);

        try{
            $res = $sth->execute($params);
        }catch (\PDOException $ex){
            throw new \SEngine\Core\Exceptions\Db($ex->getMessage());
        }

        return $res;
    }

    /**
     * Выполнение запросса с возвратом данных
     * @param $sql
     * @param $class
     * @param array $params
     * @return array
     * @throws Exceptions\Db
     */
    public function query($sql, $class, $params = [])
    {
        $sth = $this->dbh->prepare($sql);

        try{
            $res = $sth->execute($params);
        }catch (\PDOException $ex){
            throw new \SEngine\Core\Exceptions\Db($ex->getMessage());
        }

        if (false !== $res){
            return $sth->fetchAll(\PDO::FETCH_CLASS, $class);
        }
        return [];
    }


    /**
     * Возвращает ID последней вставленной строки
     * @return string
     */
    public function getNewId()
    {
        return $this->dbh->lastInsertId();
    }
}