<?php

namespace Mind\db;

class XconnMapper {
    protected $pdo;
    
    public function __construct($db) {
        $this->pdo = $db;
    }
    
    public function getXconns() {
        $sql = "SELECT * FROM XCONNECT";
        $result = [];
        foreach($this->pdo->query($sql) as $row) {
            $result[] = $row;
        }
        
        return $result;
    }
    
    public function saveConnection($post) {
        $sql = "insert into XCONNECT (DEV_LISTEN, DEV_CONNECT) VALUES (? , ?)";
        $stmt = $this->pdo->prepare($sql);
        
        if($stmt->execute(array($post['DEV_LISTEN'], $post['DEV_CONNECT']))) {
            return true;
        }
        return false;
    }
    
    public function delConnection($id) {
        $sql = "DELETE FROM XCONNECT WHERE VLAN_ID=?";
        $stmt = $this->pdo->prepare($sql);
        
        if($stmt->execute(array($id))) {
            return true;
        }
        return false;
    }
}