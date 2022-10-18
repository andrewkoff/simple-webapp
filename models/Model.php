<?php

class Model {

    protected $db;
    public $table;
    public $fields = [];
    public $meta = [];
    protected $data = array();

    function __construct($db) {
        $this->db = $db;
        $this->table = strtolower(get_class($this));
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    public function __get($name) {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
                'Undefined property via __get(): ' . $name .
                ' in ' . $trace[0]['file'] .
                ' on line ' . $trace[0]['line'],
                E_USER_NOTICE);
        return null;
    }

    public function __isset($name) {
        return isset($this->data[$name]);
    }

    public function __unset($name) {
        unset($this->data[$name]);
    }

    public function init() {
        $this->getMeta($this->table);
        return $this;
    }

    private function getMeta($table) {
        $query = "SELECT COLUMN_NAME, ORDINAL_POSITION, DATA_TYPE, "
                . "CHARACTER_MAXIMUM_LENGTH, NUMERIC_PRECISION,NUMERIC_SCALE, COLUMN_COMMENT "
                . "FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' order by ORDINAL_POSITION";
        $this->meta = array_values($this->db->query($query)->fetchAll(PDO::FETCH_ASSOC));
        $this->getFields($table);
    }

    private function getFields($table) {
        $this->fields = [];
        foreach ($this->meta as $field) {
            $this->fields[] = $field["COLUMN_NAME"];
        }
    }

    public function get($id) {
        $data = $this->db->get($this->table, $this->fields, ["id" => $id]);
        if ($data) {
            foreach ($this->fields as $field) {
                $this->$field = $data[$field];
            }
        } else {
            return [];
        }
        //  Now you would be able to call table attributes as class properties, 
        //  e.g. table column 'id' can be accessed by $model->id
        return $data;
    }

    public function select($where = []) {
        $data = $this->db->select($this->table, $this->fields, $where);
        return $data ?? [];
    }

    public function save($data = []) {
        $data = Utils::cleanArrayByKeys($data, $this->fields);
        if (!isset($data["id"])) {
            return false;
        }
        if ($data["id"] == 0) {
            unset($data["id"]);
            return $this->insert($data);
        } else {
            $id = $data["id"];
            unset($data["id"]);
            return $this->update($data, $id);
        }
    }

    private function insert($data) {
        $this->db->insert($this->table, $data);
        if ($this->db->error) {
            return false;
        } else {
            return $this->db->id();
        }
    }

    private function update($data, $id) {
        $this->db->update($this->table, $data, ['id' => $id]);
        if ($this->db->error) {
            return false;
        } else {
            return $id;
        }
    }

    public function delete($id) {
        $this->db->delete($this->table, ['id' => $id]);
        if ($this->db->error) {
            return false;
        } else {
            return $id;
        }
    }

}
