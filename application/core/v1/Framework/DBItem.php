<?php

class Core_Framework_DBItem {

    protected $conn = false;
    protected $keys;

    function __construct($KeyID = false) {
        $this->getVars();

        if ($KeyID) {
            $k = $this->key;
            $this->$k = $KeyID;
            $this->populate();
        }
    }

    //Returns an array with the key of vaule all public keys
    public function getVars() {
        $keys = Core_Helper_Utils::getPublicObjectVars($this);
        $this->keys = $keys;
        return $keys;
    }

    public function setKeys($array) {
        $this->keys = $array;
    }

    public function primaryKey() {
        return $this->{$this->key};
    }
    
    public function generateSQL($query) {
        if (@!$query['Query'] && @!$query['Order'] && @!$query['Limit'] && @!$query['Table'] && @!$query['Select'] && @!$query['Group']) {
            $query['Query'] = $query;
        }
        if (@!$query['Table']) {
            $query['Table'] = $this->table;
        }


        if (@$query['Select']) {
            $select = $query['Select'];
        } else {
            $select = '*';
        }
        $tsql = "SELECT " . $select . " FROM `" . $query['Table'] . "`";

        if (@$query['Query']) {
            $qstring = $this->makeQuery($query['Query']);
            $tsql .= " WHERE " . $qstring;
        }
        if (@$query['Group']) {
            $tsql .= ' GROUP BY ' . $query['Group'];
        }
        if (@$query['Order']) {
            $tsql .= ' ORDER BY ' . $query['Order'];
        }
        if (@$query['Limit']) {
            $tsql .= ' LIMIT ' . $query['Limit'];
        }

        return $tsql;
    }

    /**
     * @param array $query
     * @return array of queryed results
     */
    public function get($query) {
        $tsql = $this->generateSQL($query);
        return $this->get_from_sql($tsql);
    }

    public function get_from_sql($tsql) {
        $queryString = $this->query($tsql);
        $class = get_class($this);
        $enarray = array();
        while ($entities = mysql_fetch_object($queryString, $class)) {
            $enarray[] = Core_Helper_Sanitize::html($entities);
        }
        return $enarray;
    }

    /**
     * Save the currrent entry
     */
    public function save() {
        //print_r($this);
        $primaryKey = $this->key;

        if (isset($this->$primaryKey) && $this->$primaryKey != '') {
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    //Insert an entity into the database 
    public function insert() {
        $insQ = $this->classToInsert();

        $tsql = "INSERT INTO `$this->table` (" . $insQ['key'] . ") VALUES (" . $insQ['value'] . ")";

        $queryID = $this->query($tsql);
        $keyID = $this->lastInsertId();

        //Popuplate the object
        $key = $this->key;
        $this->$key = $keyID;
        $this->populate();
        return 'Inserted';
    }

    /**
     * Pull from $this->map($index)
     * @param string $index
     * @param string $key
     * @return string 
     */
    public function fromMap($index, $key) {
        $k = $this->map($index);
        return $this->$k[$key];
    }

    /**
     * @param input Class $class
     * @return array of Key and Value for Insert
     */
    protected function classToInsert() {
        $returnArray = array();
        $this->getVars();
        foreach ($this->keys as $objectKey => $value) {
            if ($objectKey && $objectKey != $this->key) {
                $returnArray['key'][] = $this->cleanup($objectKey);
                $returnArray['value'][] = $this->cleanup($this->$objectKey);
            }
        }

        $returnArray['value'] = "'" . implode("', '", $returnArray['value']) . "'";
        $returnArray['key'] = '`' . implode('`' . ", " . '`', $returnArray['key']) . '`';
        return $returnArray;
    }

    public function update() {
        $primaryKey = $this->key;

        if ($this->$primaryKey) {

            $set = $this->classToUpdate();
            $tsql = "UPDATE `$this->table` SET " . $set . " WHERE " . $primaryKey . " = " . $this->$primaryKey;
            $this->query($tsql);
        }
        return 'Updated';
    }

    public function delete() {
        $primaryKey = $this->key;
        if ($this->$primaryKey) {

            $tsql = "DELETE FROM `$this->table` WHERE " . $primaryKey . " = " . $this->$primaryKey;
            $this->query($tsql);
        }
    }

    public function populate() {
        $primaryKey = $this->key;
        if ($this->$primaryKey) {
            $query = array($primaryKey => $this->$primaryKey);
        } else {
            $query = $this->getQuery();
        }
        $return = $this->get($query);

        if (is_array($return) && @is_object($return[0])) {
            $this->setVars($return[0]);
        } else {
            unset($this->$primaryKey);
        }
    }

    public function getQuery() {
        $returnArray = array();
        foreach ($this->keys as $objectKey => $evalue) {
            if (isset($this->$objectKey)) {
                $returnArray[$objectKey] = $this->$objectKey;
            }
        }
        return $returnArray;
    }

    protected function classToUpdate() {
        $returnArray = array();
        foreach ($this->keys as $objectKey => $evalue) {
            if ($objectKey) {
                $returnArray[] = '`' . $objectKey . "` = '" . $this->cleanup($this->$objectKey) . "'";
            }
        }
        $returnArray = implode(", ", $returnArray);
        return $returnArray;
    }

    public function connect() {
        $this->conn = Core_Framework_Singleton::db()->connection;
    }

    public function query($string) {

        if ($this->conn == false) {
            $this->connect();
        }

        $query = mysql_query($string, $this->conn);
        if (!$query) {
            throw new Exception(mysql_error() . "\n With String:\n " . $string);
        }
        return $query;
    }

    protected function lastInsertId() {
        $lastid = mysql_insert_id($this->conn);
        return $lastid;
    }

    //Maps objects and arrays onto the class
    public function setVars($vars) {
        foreach ($vars as $key => $value) {
            $this->$key = $this->mapObject($value);
        }
    }

    //Returns an object from an array
    public function mapObject($array) {
        if (is_array($array)) {
            $return = new stdClass();
            foreach ($array as $key => $value) {
                $return->$key = $this->mapObject($value);
            }
        } else {
            $return = $array;
        }
        return $return;
    }

    //Converts array to querable string and cleans everything up
    protected function makeQuery($query, $Separator = 'AND') {

        $string = array();
        foreach ($query as $key => $value) {
            if (is_array($value)) {
                //Look for a query in a query
                if ($value['Separator']) {
                    $ContainedSeparator = $value['Separator'];
                    unset($value['Separator']);
                    $string[] = '(' . $this->makeQuery($value, $ContainedSeparator) . ')';
                } else {
                    // $value[Operator] = value
                    $opeartorKeys = array_keys($value);
                    $operator = $opeartorKeys[0];
                    if ($value['no_quotes']) {
                        $value = ' ' . $value[$operator] . '';
                        $string[] = ' ' . $key . ' ' . $operator . $value;
                    } else {
                        $value = ' \'' . $value[$operator] . '\'';
                        $string[] = ' `' . $key . '` ' . $operator . $value;
                    }
                    
                }
            } else {
                $operator = '=';
                $string[] = ' `' . $key . '` ' . $operator . ' \'' . $value . '\'';
            }
        }
        return implode(' ' . $Separator . ' ', $string);
    }

    //Cleanup function makes strings and arrays safe
    public function cleanup($data) {
        if (is_array($data)) {
            $cleanArray = array();
            foreach ($data as $key => $value) {
                $key = $this->cleanup($key);
                $value = $this->cleanup($value);
                $cleanArray[$key] = $value;
            }
            return $cleanArray;
        } else {
            return $this->makeSafe($data);
        }
    }

    //General Generate Functions   
    public function GUID() {
        $varGUID = str_replace('.', '', uniqid($_SERVER['REMOTE_ADDR'], TRUE)) . '-' . rand(0, 9);
        return $varGUID;
    }

    public function UTC() {
        return date("Y-m-d\TH:i:s\Z");
    }

    //The function used by cleanup to escape strings
    protected function makeSafe($string) {
        if (@$this->conn == false) {
            $this->connect();
        }
        return mysql_real_escape_string(Core_Helper_Sanitize::reverseHtml($string));
    }

    function clear() {
        foreach ($this->keys as $objectKey => $evalue) {
            unset($this->$objectKey);
        }
    }

}