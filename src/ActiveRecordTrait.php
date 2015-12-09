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