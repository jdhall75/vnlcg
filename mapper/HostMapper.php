<?php 

namespace Mind\db;

class HostMapper {
    
    protected $pdo;
    
    public function __construct($db) {
        $this->pdo = $db;
    }
    
    public function getHosts() {
        $sql = "SELECT * FROM DEVICE";
        $result = [];
        foreach($this->pdo->query($sql) as $row) {
            $result[] = $row;
        }
        return $result;
    }
    
    public function addDevice($data) {
        $sql = "INSERT INTO DEVICE (DEVNAME, DEVTYPE) VALUES ( ?, ? )";
        $stmt = $this->pdo->prepare($sql);
        if($stmt->execute(array($data['DEVNAME'], $data['DEVTYPE']))) {
            return true;
        }
        return false;
    }
    
    public function delDevice($id) {
        $sql = "DELETE FROM DEVICE WHERE DEV_ID=?";
        $stmt = $this->pdo->prepare($sql);
        
        if($stmt->execute(array($id))) {
            return true;
        }
        
        return false;
    }
}

?>