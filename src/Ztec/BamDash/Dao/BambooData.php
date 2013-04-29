<?php
namespace Ztec\BamDash\Dao ;
class BambooData {

    private $url = "" ;

    public function __construct($url){
        $this->url = $url ;
    }

    public function getAllPlans(){
        $url = $this->url.'plan.json';
        $json =json_decode(file_get_contents($url));
        return $json ;
    }

    public function getBuilds($planId){
        $url = $this->url.'result/'.$planId.'.json';
        $json =json_decode(file_get_contents($url));
        return $json ;
    }

    public function getBranches($planId){
        $url = $this->url.'plan/'.$planId.'/branch.json';
        $json =json_decode(file_get_contents($url));
        return $json ;
    }

    public function getBranchStatus($branchkey){
        $url = $this->url.'plan/'.$branchkey.'.json';
        $json =json_decode(file_get_contents($url));
        return $json ;
    }
}