<?php

namespace memclutter\PhpTodo;

trait ActiveRecordTrait
{
    use ContainerTrait;

    private static $_pdo;

    /**
     * @param $id
     * @return static
     * @throws Exception
     */
    public static function find($id)
    {
        $pdo = self::getPdo();
        $tableName = self::tableName();
        $primaryKey = self::primaryKey();
        $statement = $pdo->prepare("SELECT * FROM {$tableName} WHERE {$primaryKey} = ? LIMIT 0,1");
        if (!$statement->execute([$id])) {
            $errorInfo = $statement->errorInfo();
            throw new Exception("PDO [{$errorInfo[0]}]: {$errorInfo[1]}.");
        }

        $activeRecord = new static();
        $record = $statement->fetch(\PDO::FETCH_ASSOC);
        foreach ($record as $name => $value) {
            $activeRecord->set($name, $value);
        }
        return $activeRecord;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function findAll()
    {
        $pdo = self::getPdo();
        $tableName = self::tableName();
        $statement = $pdo->prepare("SELECT * FROM {$tableName}");
        if (!$statement->execute()) {
            $errorInfo = $statement->errorInfo();
            throw new Exception("PDO [{$errorInfo[0]}]: {$errorInfo[1]}.");
        }

        $activeRecords = [];
        while ($record = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $activeRecord = new static();
            foreach ($record as $name => $value) {
                $activeRecord->set($name, $value);
            }
            $activeRecords[] = $activeRecord;
        }

        return $activeRecords;
    }

    public function save()
    {
        if (!$this->{$this->primaryKey()}) {
            $this->create();
        } else {
            $this->update();
        }
    }

    private function create()
    {
        $pdo = $this->getPdo();
        $fields = $this->toArray();
        $fieldsKeys = array_keys($fields);
        $into = implode(', ', $fieldsKeys);
        $placeholders = implode(', ', array_map(function($v) { return ':'.$v; }, $fieldsKeys));

        $tableName = $this->tableName();
        $statement = $pdo->prepare("INSERT INTO {$tableName} ($into) VALUES ($placeholders)");
        foreach ($fields as $field => $value) {
            $statement->bindValue(':'.$field, $value);
        }

        if (!$statement->execute()) {
            $errorInfo = $statement->errorInfo();
            throw new Exception("PDO [{$errorInfo[0]}]: {$errorInfo[1]}.");
        }

        $lastInsertId = $pdo->lastInsertId();
        if ($lastInsertId) {
            $this->{$this->primaryKey()} = $lastInsertId;
        }
    }

    private function update()
    {
        $primaryKey = $this->primaryKey();
        $primaryKeyValue = $this->{$primaryKey};

        $pdo = $this->getPdo();
        $fields = $this->toArray();
        $set = [];
        foreach ($fields as $field => $value) {
            if ($field != $primaryKey) {
                $set[] = "$field = :{$field}";
            }
        }
        $set = implode(', ', $set);

        $tableName = $this->tableName();
        $statement = $pdo->prepare("UPDATE {$tableName} {$set} WHERE {$primaryKey} = :primary_key");
        foreach ($fields as $field => $value) {
            $statement->bindValue(':'.$field, $value);
        }
        $statement->bindValue(':primary_key', $primaryKeyValue);

        if (!$statement->execute()) {
            $errorInfo = $statement->errorInfo();
            throw new Exception("PDO [{$errorInfo[0]}]: {$errorInfo[1]}.");
        }
    }

    public static function primaryKey()
    {
        return 'id';
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        $classComponents = explode('\\', get_called_class());
        $class = $classComponents[count($classComponents)-1];
        $class = preg_replace('/([A-Z])/', '_$1', $class);
        $tableName = strtolower(trim($class, '_'));
        return $tableName;
    }

    /**
     * @return \PDO
     */
    public static function getPdo()
    {
        if (!(self::$_pdo instanceof \PDO)) {
            self::$_pdo = Application::getInstance()->pdo;
        }
        return self::$_pdo;
    }
}