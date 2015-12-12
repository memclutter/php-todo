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

        Application::getInstance()
            ->logger
            ->d('ACTIVE RECORD', [
                'Find one record by {primary_key} {id}.',
                '{primary_key}' => $primaryKey,
                '{id}' => $id,
            ]);

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

        Application::getInstance()
            ->logger
            ->d('ACTIVE RECORD', 'Find all records.');

        if (!$statement->execute()) {
            $errorInfo = $statement->errorInfo();
            throw new Exception("PDO [{$errorInfo[0]}]: {$errorInfo[2]}.");
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

    public function delete()
    {
        $primaryKey = $this->primaryKey();
        $primaryKeyValue = $this->{$primaryKey};

        if ($primaryKeyValue) {
            $pdo = $this->getPdo();

            $tableName = $this->tableName();
            $statement = $pdo->prepare("DELETE FROM {$tableName} WHERE {$primaryKey} = :{$primaryKey}");
            $statement->bindValue(':' . $primaryKey, $primaryKeyValue);


            Application::getInstance()
                ->logger
                ->d('ACTIVE RECORD', [
                    'Delete record by {primary_key} {id}.',
                    '{primary_key}' => $primaryKey,
                    '{id}' => $primaryKeyValue,
                ]);

            if (false === $statement->execute()) {
                $errorInfo = $statement->errorInfo();
                throw new Exception("PDO [{$errorInfo[0]}]: {$errorInfo[2]}.");
            }
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

        Application::getInstance()
            ->logger
            ->d('ACTIVE RECORD', 'Create new record.');

        if (!$statement->execute()) {
            $errorInfo = $statement->errorInfo();
            throw new Exception("PDO [{$errorInfo[0]}]: {$errorInfo[2]}.");
        }

        $lastInsertId = $pdo->lastInsertId();
        if ($lastInsertId) {

            Application::getInstance()
                ->logger
                ->d('ACTIVE RECORD', ['Last auto generated insert id {id}', '{id}' => $lastInsertId]);

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
        $statement = $pdo->prepare("UPDATE {$tableName} SET {$set} WHERE {$primaryKey} = :{$primaryKey}");
        foreach ($fields as $field => $value) {
            if ($field != $primaryKey) {
                $statement->bindValue(':' . $field, $value);
            }
        };
        $statement->bindValue(':'.$primaryKey, $primaryKeyValue);


        Application::getInstance()
            ->logger
            ->d('ACTIVE RECORD', [
                'Update record by {primary_key} {id}.',
                '{primary_key}' => $primaryKey,
                '{id}' => $primaryKeyValue,
            ]);

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