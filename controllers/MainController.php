<?php

class MainController {
    protected $container;
    
    public function __construct($container) {
        $this->container = $container;
    }
    
    public function index(Slim\Http\Request $request, Slim\Http\Response $response) {
        $hostMapper  = new \Mind\db\HostMapper($this->container->db);
        //$imageMapper = new ImageMapper($this->container->db);
        $xconnMapper = new \Mind\db\XconnMapper($this->container->db);
       
        $image_path = $this->container->get('settings')['images']['base_path'];
        $file_ext = $this->container->get('settings')['images']['file_ext'];
        $tplData['images'] = [];
        
        $dir_iterator = new RecursiveDirectoryIterator($image_path);
        $iterator = new  RecursiveIteratorIterator($dir_iterator,
            RecursiveIteratorIterator::SELF_FIRST);
        
        foreach($iterator as $file) {
            if($file->isFile()){
                $tplData['images'][]['name'] = $file->getBasename();
                $tplData['images'][]['fullPath'] = $file->getPathName();
            }
        }
       
        
        //$tplData['imageData'] = 
        $tplData['title'] = 'VNLCG-NG';
        $tplData['devices'] = $hostMapper->getHosts();
        $tplData['xconns'] = $xconnMapper->getXconns();
        
        if(!$_SESSION['active_page']) {
            $_SESSION['active_page'] = "devices";
        }
        
        $tplData['active_page'] = $_SESSION['active_page'];
        
        return $response->write($this->container->view->render('siteLayout', $tplData)); 
    }
    
    public function addXconn(Slim\Http\Request $request, Slim\Http\Response $response) {
        $formData = $request->getParsedBody();
       
        $mapper = new \Mind\db\XconnMapper($this->container->db);
        if($mapper->saveConnection($formData)) {
            $newResp = $response->withHeader('Location', '/');
        }
        
        $_SESSION['active_page'] = 'connections';
        
        return $newResp;
    }
    
    public function delXconn(Slim\Http\Request $request, Slim\Http\Response $response, $args) {
        $dev_id = (int)$args['id'];
        
        $mapper = new \Mind\db\XconnMapper($this->container->db);
        if($mapper->delConnection($dev_id)) {
            $newResp  = $response->withHeader('Location', '/');
            $_SESSION['active_page'] = 'connections';
        }
        
        
        return $newResp;
    }
    
    public function addDev(Slim\Http\Request $request, Slim\Http\Response $response) {
        $formData = $request->getParsedBody();
        $mapper = new \Mind\db\HostMapper($this->container->db);
        
        if($mapper->addDevice($formData)) {
            $newResp = $response->withHeader('Location', '/');
            $_SESSION['active_page'] = 'devices';
            return $newResp;
        }
    }
    
    public function delDev(Slim\Http\Request $request, Slim\Http\Response $response, $args) {
        $dev_id = (int)$args['id'];
        
        $mapper = new \Mind\db\HostMapper($this->container->db);
        
        if($mapper->delDevice($dev_id)) {
            $newResp = $response->withHeader('Location', '/');
            $_SESSION['active_page'] = 'devices';
            return $newResp;
        }
        
    }
    
}