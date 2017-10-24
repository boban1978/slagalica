<?php
namespace classes;

class UserEntity
{
    protected $id;
    protected $msisdn;

    /**
     * Accept an array of data matching properties of this class
     * and create the class
     *
     * @param array $data The data to use to create
     */

    /*
    public function __construct($data) {
        // no id if we're creating
        if(isset($data['id'])) {
            $this->id = $data['id'];
        }
        if(isset($data['msisdn'])) {
            $this->msisdn = $data['msisdn'];
        }
    }*/
    public function getId() {
        return $this->id;
    }
    public function getMsisdn() {
        return $this->msisdn;
    }


    public function setMsisdn($msisdn) {
        $this->msisdn = $msisdn;
    }


}