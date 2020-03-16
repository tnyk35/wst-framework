<?php
namespace model;

use core\Database;
 
class Model
{
    protected $DB;
    protected $table;

    public function __construct()
    {
        $database = new Database();
        $this->DB = $database->DBConnect();

        if (!isset($this->table)) {
            $this->table = strtolower(preg_replace("/model\\\(.+)Model/", "$1", get_class($this)));
        }
    }

    // SELECT
    public function select($id = '')
    {
        $sql = 'SELECT * FROM ' . $this->table;

        if ($id !== '') {
            $id = $_GET['id'];
            $sql .= ' WHERE id = :id';
        }

        $sql = $this->_checkSoftDelete($sql);

        try {
            $stmt = $this->DB->prepare($sql);

            if ($id !== '') {
                $stmt->bindValue(':id', $id);
            }

            $stmt->execute();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
        return $stmt->fetchAll();
    }

    // INSERT
    public function insert($datas)
    {
        $cols = $this->_createInsertColumns($datas);

        $sql = 'INSERT INTO ' . $this->table . $cols;

        $this->_run($sql, $datas);

        return true;
    }

    // UPDATE
    public function update($datas)
    {
        $cols = $this->_createUpdateColumns($datas);

        $sql = 'UPDATE ' . $this->table . ' SET ' . $cols . ' WHERE id = :id';

        $this->_run($sql, $datas);
    }

    // DELETE
    public function delete($id)
    {
        $data = array('id' => $id);

        if (IS_SOFT_DELETE) {
            $sql = 'UPDATE ' . $this->table . ' SET delete_flg = 1 WHERE id = :id';
        } else {
            $sql = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        }
        $this->_run($sql, $data);
    }

    // SELECT LOGIN USER
    public function getUser($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $sql = $this->_checkSoftDelete($sql);

        try {
            $stmt = $this->DB->prepare($sql);

            $stmt->bindValue(':email', $email);

            $stmt->execute();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
        return $stmt->fetch();
    }

    // 全部で何件か取得
    public function getTotalNum()
    {
        $sql = 'SELECT count(*) FROM ' . $this->table;
        $sql = $this->_checkSoftDelete($sql);

        try {
            $stmt = $this->DB->query($sql);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
        return (int) $stmt->fetch()['count(*)'];
    }

    // SELECT（ページング用）
    public function paging($start)
    {
        $sql = 'SELECT * FROM ' . $this->table;
        $sql = $this->_checkSoftDelete($sql);
        $sql .= ' LIMIT ' . $start . ', ' . MAX_COUNT_PER_PAGE;

        try {
            $stmt = $this->DB->query($sql);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
        return $stmt->fetchAll();
    }

    // INSERT, UPDATE, DELETEを実行
    private function _run($sql, $datas)
    {
        $this->DB->beginTransaction();
        try {
            $stmt = $this->DB->prepare($sql);

            foreach ($datas as $key => $val) {
                $stmt->bindValue(':' . $key, $val);
            }
            $stmt->execute();

            $this->DB->commit();
        } catch (\PDOException $e) {
            $this->DB->rollback();

            exit($e->getMessage());
        }
    }

    // INSERT用カラムを作成
    private function _createInsertColumns($datas)
    {
        $length = count($datas);
        $cnt = 0;
        $col1 = '(';
        $col2 = '(';

        foreach ($datas as $key => $val) {
            $col1 .= $key;
            $col2 .= ':' . $key;
            $cnt++;

            if ($cnt < $length) {
                $col1 .= ', ';
                $col2 .= ', ';
            }
        }
        $col1 .= ')';
        $col2 .= ')';

        return $col1 . ' VALUES ' . $col2;
    }

    // UPDATE用カラムを作成
    private function _createUpdateColumns($datas)
    {
        $length = count($datas);
        $cnt = 0;
        $col = '';

        foreach ($datas as $key => $val) {

            $cnt++;
            if ($key !== 'id') {
                $col .= $key . ' = :' . $key;

                if ($cnt < $length) {
                    $col .= ', ';
                }
            }
        }

        return $col;
    }

    private function _checkSoftDelete($sql)
    {
        if (IS_SOFT_DELETE) {
            if (strpos($sql, 'WHERE') !== false) {
                $sql .= ' AND delete_flg = 0';
            } else {
                $sql .= ' WHERE delete_flg = 0';
            }
        }
        return $sql;
    }
}