<?php
/**
 * Filter and Count Text in File
 * @author Ismail <is.tmdg86@gmail.com>
 * @since 2017.12.23
 */

class FilterAndCountText
{
    const FIRST_COUNT = 1;

    public $pathFile;

    public $totalFiles;

    public $flag;

    public $idFile = [];

    private $_conn;

    public function __construct() {
        $servername = "localhost";
        $username = "root";
        $password = "qwe123";
        $dbname = "dsdir";

        $this->_conn = mysqli_connect($servername, $username, $password, $dbname);
        if ($this->_conn->connect_error) {
            die("Connection failed: " . $this->_conn->connect_error);
        }
    }

    public function show()
    {
        if ($this->flag) {
            foreach ($this->idFile as $key => $id) {
                $this->_update($id, 'actionFlag', $this->flag);
            }
        }
        $selectData = $this->_select();
        if ($selectData) {
            foreach ($selectData as $key => $value) {
                echo $value['content'] . ' ' . $value['counter'] . "\n";
            }
        }
        return null;
    }

    public function mapingData()
    {
        $filestring = file_get_contents($this->pathFile);
        $selectData = $this->_select(null, $filestring);
        if ($selectData == false) {
            return $this->_insert($filestring, self::FIRST_COUNT, $this->totalFiles);
        } else {
            $this->_filterAndCount($selectData, $filestring);
            return null;
        }
    }

    private function _filterAndCount($selectData, $filestring)
    {
        if($selectData['totalFile'] !== $this->totalFiles) {
            $this->_update($selectData['id'], 'totalFile', $this->totalFiles);

        }
        if ($selectData['content'] == $filestring && $selectData['actionFlag'] == 0 ||
            $selectData['totalFile'] != $this->totalFiles) {
            $this->_update($selectData['id'], 'counter', $selectData['counter'] + self::FIRST_COUNT);
        }
    }

    private function _insert($content, $counter, $totalFiles)
    {
        $sql = 'insert into directoryList(content, counter, totalFile)values("' .
            $content . '", ' . $counter . ', ' . $totalFiles . ')';
        $this->_conn->query($sql);
        return mysqli_insert_id($this->_conn);
    }

    private function _update($id, $coloumn, $value)
    {
        $sql = 'update directoryList set '. $coloumn . ' = ' . $value . ' where id = '. $id;
        $result = $this->_conn->query($sql);
        return $result;
    }

    private function _select($id = null, $content = null)
    {
        $sql = 'select * from directoryList';
        if ($id) {
            $sql .= ' where id = ' . $id . ' limit 1';
        } elseif ($content) {
            $sql .= ' where content = "' . $content . '" limit 1';
        }

        $result = $this->_conn->query($sql);
        if ($result && $id || $result && $content) {
            return mysqli_fetch_assoc($result);
        } else {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        return false;
    }
}
