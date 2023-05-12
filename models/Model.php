<?php
class Model
{

    private $driver = 'mysql';
    private $host = 'localhost';
    private $dbname = 'trabalhopw';
    private $port = '3306';
    private $user = 'root';
    private $password = null;
    protected $table;
    protected $conex;



    public function __construct()
    {
        $tbl = strtolower(get_class($this));
        $tbl .= 's';
        $this->table = $tbl;

        $this->conex = new PDO("{$this->driver}:host={$this->host};port={$this->port};dbname={$this->dbname}", $this->user, $this->password);
    }

    private function map_fields($data)
    {
        foreach (array_keys($data) as $field) {
            $sql_fields[] = "{$field} = :{$field}";
        }
        return $sql_fields;
    }


    private function sql_fields($data)
    {
        $sql_fields = $this->map_fields($data);
        return implode(', ', $sql_fields);
    }

    private function where_fields($data, $glue = 'AND')
    {
        $glue = $glue == 'OR' ? ' OR ' : ' AND ';
        $fields = $this->map_fields($data);
        return implode($glue, $fields);
    }

    public function getAll($where = false, $where_glue = 'AND')
    {
        if ($where) {
            $where_sql = $this->where_fields($where, $where_glue);

            $sql = $this->conex->prepare("SELECT * FROM {$this->table}");
            $sql->execute();
        } else {
            $sql = $this->conex->query("SELECT * FROM {$this->table}");
        }

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = $this->conex->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table}";

        $sql_fields = $this->sql_fields($data);

        $sql .= " SET {$sql_fields}";

        $insert = $this->conex->prepare($sql);

        $insert->execute($data);

        return $insert->errorInfo();
    }
}

