<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use function PHPUnit\Framework\assertFalse;

class Employeedata extends CI_Model
{
    private $db3; //233
    private $db4; //234, MUST BE SELECT ONLY

    //private $cnf;

    function __construct()
    {
        parent::__construct();

    }
   
    public function get_emppass($empno)
    {
	$myactive = "";
        $data = array();
        $data['stat'] = intval(FALSE);
        $data['msg'] = "No Record Found";
        $data['empno'] = "";
        try {
            $encpass = "";
            $sqld = "SELECT `PassEncrypted`,EmpID FROM table WHERE fieldname=?";
            $qrys = $this->db->query($sqld, array($empno));
            if ($qrys->num_rows() > 0) {
                $rws = $qrys->result();
                
		$encpass= $rws[0]->PassEncrypted;

	
		$decrypted_pass = $this->encryption->decrypt($encpass);
                
		if($myactive=="Yes"){
			$data['stat'] = intval(TRUE);
                	$data['msg'] = $decrypted_pass;
                	$data['empno'] = $empno;
		}
		else{
			$data['msg'] = "Inactive Account";
		}

            } else {
                $data['msg'] = "No account found" . " " . $passwd;
            }
            
            return $data;
        } catch (Exception $e) {
            $data['msg'] = $e->getMessage();
            return $data;
        }
    }


    public function get_empdata($username, $passwd)
    {
	$myactive = "";
        $data = array();
        $data['stat'] = intval(FALSE);
        $data['msg'] = "No Record/Account Found";
        $data['empno'] = "";
        try {
            $encpass = "";
            $sqld = "SELECT `PassEncrypted`,EmpID,Active FROM table WHERE fieldname=?";
            $qrys = $this->db->query($sqld, array($username));
            if ($qrys->num_rows() > 0) {
                $rws = $qrys->result();
                
		$empnum = $rws[0]->EmpID;
		$myactive = $rws[0]->Active;
		$encpass = $rws[0]->PassEncrypted;

		$decrypted_pass = $this->encryption->decrypt($encpass);
                
		if($myactive=="Yes"){
			if($decrypted_pass==$passwd){
			    	$data['stat'] = intval(TRUE);
                		$data['msg'] = "Employee Account Active" . " " . $passwd;
                		$data['empno'] = $empnum;
			}		
		}
		else{
			$data['msg'] = "Inactive Account";
		}
            } 
            
            return $data;
        } catch (Exception $e) {
            $data['msg'] = $e->getMessage();
            return $data;
        }
    }

    public function save_readings($clientname, $reading)
    {
        try {
            $encpass = "";
            $sqld = "INSERT INTO `for_android_api`(`clientname`,`readingvalue`) VALUES(?,?)";
            $qrys = $this->db->query($sqld, array($clientname, $reading));
            if ($this->db->affected_rows() > 0) {
                // $rws = $qrys->result();
                $encpass = $clientname . " - Saved";
            }
            return $encpass;
        } catch (Exception $e) {
            $encpass = $e->getMessage();
            return $encpass;
        }
    }
}
