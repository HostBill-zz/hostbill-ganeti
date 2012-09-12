<?php

require 'lib/php-ganeti-rapi/ganeti-rapi.php';

use ganeti\rapi;

class ganeti extends HostingModule {
    protected $modname = 'Ganeti Provisionning';
    protected $version = '0.1';

    protected $options = array(
        'package' => array(
            'name' => 'Package type',
            'value' => '',
            'type' => 'select', //html select element
            'default' => array('package A', 'package B', 'package C'),
        ),
        'memory' => array(
            'name' => 'Memory',
            'value' => '',
            'type' => 'select',
            'default' => array('1024MB', '2048MB')
        ),
        'subdomain' => array(
            'name' => 'Subdomain prefix',
            'value' => '',
            'type' => 'input',
            'default' => "subdomain.",
        ),
        'reseller' => array(
            'name' => 'Is this reseller?',
            'value' => false,
            'type' => 'check', //html input type='checkbox'
            'default' => false,
        )
    );


     /**
      * You can choose which fields to display in Settings->Apps section
      * by defining this variable
      * @var array
      */
     protected $serverFields = array( // 
        'hostname' => true,
        'ip' => true,
        'maxaccounts' => false,
        'status_url' => false,
        'field1' => true,
        'username' => true,
        'password' => true,
        'hash' => false,
        'ssl' => false,
        'nameservers' => false,
    );

     /**
      * HostBill will replace default labels for server fields
      * with this variable configured
      * @var array
      */
    protected $serverFieldsDescription = array( 
        'username' => 'RAPI Username',
        'password' => 'RAPI User Password',
        'field1'=>'Port',
    );


    private $connection = array();

    public function connect($app_details) {
        $this->connection['ip'] = $app_details['ip'];
        $this->connection['host'] = $app_details['host'];
        $this->connection['username'] = $app_details['username'];
        $this->connection['password'] = $app_details['password'];
        $this->connection['port'] = $app_details['field1'];
 
        //is "use ssl" option enabled? (True/false)
        //$this->connection['secure'] = $app_details['secure'];
    }

    public function testConnection() {
        //$this->addInfo('Testing connection...');
        if($this->connection['username']!='') {
            try {
                $cli = new ganeti\rapi\GanetiRapiClient(
                            $this->connection['ip'],
                            $this->connection['port'],
                            $this->connection['username'],
                            $this->connection['password']
                            );
                $rapiVersion = $cli->getVersion();
                $this->addInfo('RAPI version is:'.$rapiVersion);
                return true;
            } catch (\Exception $e) {
                $this->addError(
                    'Something went wrong while running getVersion: '.
                    $e->getMessage() );
                return false;
            }
        }
        else {
            $this->addError('Username cannot be blank!');
            return false;
        }
 
    }


    public function install() {
        //$this->db->exec("CREATE TABLE ....");
        return true;
    }
    public function upgrade($previous_version) {
        if($previous_version=='3.1') {
            // change db scheme
            //$this->db->exec('ALTER TABLE ...');
        }
        return true;
    }
    public function uninstall() {
        // $this->db->exec("DROP TABLE ....");
        return true;
    }
}
