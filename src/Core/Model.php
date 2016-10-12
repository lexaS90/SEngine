<?php


namespace SEngine\Core;



use SEngine\Models\Author;

abstract class Model
{
    const TABLE = '';
    const RELATION = [];

    public $id;

    /**
     * Возвращает имя таблици
     * @return mixed
     */
    public static function getTable()
    {
        return static::TABLE;
    }

    /**
     * Вывод всех заначений
     * @return mixed
     */
    public static function findAll()
    {
        $db = Db::instance();
        return $db->query('SELECT * FROM '. static::TABLE,
            static::class);
    }

    /**
     * Вывод одной записи по id
     * @param $id
     * @return static
     */
    public static function findById($id)
    {
        $db = Db::instance();
        $sql = 'SELECT * FROM '. static::TABLE. ' WHERE id = :id';
        $res = $db->query($sql,
            static::class,
            [":id" => $id])[0];

        return $res ?: new static;
    }

    /**
     * Вставка новых данных
     * @return $this|void
     */
    public function insert()
    {
        if (!$this->isNew())
            return;

        $colums = [];
        $values = [];

        foreach($this as $k => $v)
        {
            if ('id' == $k)
                continue;

            $colums[] = $k;
            $values[':'.$k] = $v;
        }

        $sql = 'INSERT INTO '.static::TABLE. ' ('. implode(',', $colums) .') VALUES (' .implode(',', array_keys($values)). ')';
        $db = Db::instance();
        $db->execute($sql,$values);
        $this->id = $db->getNewId();
        return $this;
    }

    /**
     * Обновление данных
     * @return $this|void
     */
    public function update()
    {
        if ($this->isNew())
            return;

        $colums = [];
        $values = [];

        foreach($this as $k => $v)
        {
            if ('id' == $k)
                continue;

            $colums[] = $k. ' = :'. $k;
            $values[':'.$k] = ('' === $v) ? null : $v;
        }

        $values[':id'] = $this->id;

        $sql = 'UPDATE '.static::TABLE. ' SET '.implode(',', $colums). ' WHERE id = :id';
        $db = Db::instance();
        $db->execute($sql,$values);
        return $this;
    }

    /**
     * Обновление или вставка данных
     * @return $this|void
     */
    public function save()
    {
        $this->beforeSave();
        return ($this->isNew()) ? $this->insert() : $this->update();
    }

    /**
     *  Выхов перед сохранением
     */
    protected function beforeSave()
    {

    }

    /**
     * Удаление данных
     * @return $this|void
     */
    public function delete()
    {
        if ($this->isNew()) {
            return;
        }

        $sql = 'DELETE FROM '.static::TABLE.' WHERE id = :id';
        $db = Db::instance();
        $db->execute($sql,[':id' => $this->id]);
        return $this;

    }

    private function isNew()
    {
        return empty($this->id);
    }

    /**
     * Связанные записи
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (in_array($name, static::RELATION)){
            $model = 'SEngine\\Models\\'.ucfirst($name);
            $prop = $name.'_id';
            return $model::findById($this->$prop);
        }
    }


    /**
     * Заполнение данных
     * @param $data
     */
    public function fill($data)
    {
        $data = $this->beforeFill($data);

        foreach ($this as $k => $v){
            /*if ('id' == $k) {
                continue;
            }*/

            if (key_exists($k,$data))
                $this->$k = $data[$k];
        }

        $this->afterFill($data);
    }

    /**
     * Очистка данных
     */
    public function clear()
    {
        foreach ($this as $k => $v){
            if ('id' == $k) {
                continue;
            }

            $this->$k = '';
        }
    }

    /**
     * Выполнение перед заполнением данными
     * @param $data
     * @return $data
     */
    protected function beforeFill($data)
    {
        return $data;
    }

    /**
     * Выполнение после заполнением данными
     * @param $data
     */
    protected function afterFill($data)
    {

    }
}