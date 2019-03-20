<?php

class kobliDB
{

    //--------Database bağlama Constructor kısmı--------
    function __construct($dbType, $host, $port, $dbname, $user, $pass)
    {
        $this->reportSTR     = "";
        $this->connectionSTR = "";
        $this->connectionSTR = $dbType . ":host=";
        $this->connectionSTR .= $host;
        //$this->connectionSTR .= ";port=".$port;
        $this->connectionSTR .= ";dbname=" . $dbname;
        $this->connectionSTR .= ";charset=utf8";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->db = new PDO($this->connectionSTR, $user, $pass, $options);
            $this->reportSTR .= "Bağlantı Başarılı. \n";
        } catch (PDOException $e) {
            $this->reportSTR .= $e->getMessage();
        }
    }
    //--------Database bağlama Constructor kısmı--------


    //--------Durum Raporlama--------
    public function reportStatus()
    {
        print $this->reportSTR;
    }
    //--------Durum Raporlama--------

    public function valueCont($tableName, $params)
    {
        try {
            $querySTR = "Select * From {$tableName} WHERE";
            $i = 0;
            $len = count($params);
            foreach ($params as $key => $value) {
                    $querySTR .= " {$key} = '{$value}'" . (($i == $len - 1) ? "" : " AND ");
                    $i++;
            }
            $query = $this->db->query($querySTR)->fetchAll();
            if ($query) {
                if (count($query) > 0)
                    return true;
                else
                    return false;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            $this->reportSTR .= $e->getMessage() . "\n";
            $this->reportStatus();
            return false;
        }
    }

    //--------Veri Çekme--------
    public function valueOf($tableName, $columnName, $value)
    {
        try {
            $query = $this->db->query("SELECT * FROM {$tableName} WHERE {$columnName} = '{$value}'")->fetchAll();
            if ($query) {
                if (count($query) > 1)
                    return $query; //birden fazla bulunan veri varsa hepsini geri yolla
                else
                    return $query[0];
            } else {
                $this->reportSTR .= "Veri bulunamadı. Kolon = " . $columnName . " Veri = " . $value + "\n";
                $this->reportStatus();
                return false;
            }
        } catch (PDOException $e) {
            $this->reportSTR .= $e->getMessage() . "\n";
            $this->reportStatus();
        }
    }
    //--------Veri Çekme--------

    //--------VeriLERİ çekme--------
    public function getValues($tableName, $columnName)
    {
        $query = $this->db->query("SELECT * FROM " . $tableName, PDO::FETCH_ASSOC);
        $qRows = array();
        if ($query->rowCount()) {
            foreach ($query as $row) {
                $qRows[] = $row[$columnName];
            }
        }
        $query = null;
        return $qRows;
    }
    //--------VeriLERİ çekme--------

    //--------Tabloyu çek--------
    public function getTable($tableName)
    {
        $tableColumns = $this->getColumns($tableName);
        for ($i = 0; $i < count($tableColumns); $i++) {
                $tableSelf[$i][0] = $tableColumns[$i];
                $columnsValues = $this->getValues($tableName, $tableColumns[$i]);
                for ($j = 1; $j < count($columnsValues) + 1; $j++) {
                        $tableSelf[$i][$j] = $columnsValues[$j - 1];
                    }
            }
        var_dump($tableSelf);
    }
    //--------Tabloyu çek--------

    //--------Yeni değer--------
    public function newValue($tableName, $tableColumns, $newThings)
    {
        $querySTR = "INSERT INTO {$tableName} SET";
        $i = 0;
        $len = count($tableColumns);
        foreach ($tableColumns as $Column) {
                $querySTR .= " {$Column} = ?" . (($i == $len - 1) ? "" : ",");
                $i++;
            }
        $query = $this->db->prepare($querySTR);
        $insert = $query->execute($newThings);
        if ($insert) {
            $last_id = $this->db->lastInsertId();
            $this->reportSTR .= "Insert işlemi başarılı.";
        } else {
            $this->reportSTR .= "Insert işlemi başarısız.";
        }
        return $this;
    }
    //--------Yeni değer--------

    //--------Değer değiştir--------
    public function changeValue($tableName, $tableColumns, $updateThings, $whereIs)
    {
        $querySTR = "UPDATE {$tableName} SET";
        $i = 0;
        $len = count($tableColumns);
        foreach ($tableColumns as $Column) {
                $querySTR .= " {$Column} = ?" . (($i == $len - 1) ? "" : ",");
                $i++;
            }
        $querySTR .= " WHERE " . $whereIs[0] . " = '{$whereIs[1]}'";
        $query = $this->db->prepare($querySTR);
        $update = $query->execute($updateThings);
        if ($update) {
            $this->reportSTR .= "Update işlemi başarılı.";
        } else {
            $this->reportSTR .= "Update işlemi başarısız.";
        }
        return $this;
    }
    //--------Değer değiştir--------

    //--------Kolon Değerlerini AL--------
    public function getColumns($tableName)
    {
        $q = $this->db->prepare("DESCRIBE " . $tableName);
        $q->execute();
        $table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
        return $table_fields;
    }
    //--------Kolon Değerlerini AL--------

    //--------Her şeyi SİL--------
    public function deleteALL($tableName)
    {
        $delete = $this->db->exec("DELETE FROM {$tableName}");
        $this->reportSTR .= 'Toplam ' . $delete . ' üye silindi! \n';
        return $this;
    }
    //--------Her şeyi SİL--------

    //--------Belli bir yeri sil--------
    public function deleteThis($tableName, $columnNames, $values)
    {
        $querySTR = "DELETE FROM {$tableName} WHERE";
        for ($i = 0; $i < count($columnNames); $i++) {
                $querySTR .= " " . $columnNames[$i] . " = ? " . (($i == count($columnNames) - 1) ? "" : ",");
            }
        $query = $this->db->prepare($querySTR);
        $delete = $query->execute($values);
        $this->reportSTR .= "Verilen değer silindi";
        return $this;
    }
    //--------Belli bir yeri sil--------



    //--------Database kapatma gerekli pointerlar burada silenecek!--------
    function __destruct()
    {
        $this->db = null;
        $this->reportSTR = null;
    }
    //--------Database kapatma gerekli pointerlar burada silenecek!--------
}
 