<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apiadmin extends CI_Controller {

	function __construct() {
		 parent::__construct();

        $this->load->model('apiadminmodel');
        $this->load->helper('url');
        $this->load->library('session');
}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
    public function index()
    {
    	$this->load->view('welcome_message');
    }
    
    
    public function checkMethod()
    {
    	if($_SERVER['REQUEST_METHOD'] != 'POST')
    	{
    		$res = array();
    		$res["scode"] = 203;
    		$res["message"] = "Request Method not supported";
    
    		echo json_encode($res);
    		return FALSE;
    	}
    	return TRUE;
    }
    
    
    // GET ALL CLASS
    public function get_all_classes()
    {
    
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    	 $user_id=$this->input->post('user_id');
    	$data['result']=$this->apiadminmodel->get_classes($user_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    // GET SECTION
    public function get_all_sections()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    	  $class_id=$this->input->post('class_id');
    
    
    	$data['result']=$this->apiadminmodel->get_all_sections($class_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    // GET ALL STUDENTS IN CLASSES
    public function get_all_students_in_classes()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG  ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    	  $class_id=$this->input->post('class_id');
    		$section_id=$this->input->post('section_id');
    
    
    	$data['result']=$this->apiadminmodel->get_all_students_in_classes($class_id,$section_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    
    // GET ALL STUDENTS DETAILS
    public function get_student_details()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    	   $student_id=$this->input->post('student_id');
    	  
    	$data['result']=$this->apiadminmodel->get_student_details($student_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    // GET ALL HOMEWORK DETAILS
    public function get_all_howework_details()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG  ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    		$student_id=$this->input->post('student_id');
    
    
    
    	$data['result']=$this->apiadminmodel->get_all_howework_details($student_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    // GET ALL HOMEWORK DETAILS
    public function get_howework_details()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG  ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    		$hw_id=$this->input->post('hw_id');
    
    	$data['result']=$this->apiadminmodel->get_howework_details($hw_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    
    // GET ALL CLASSTEST DETAILS
    public function get_all_classtest_details()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG  ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    		$student_id=$this->input->post('student_id');
    
    	$data['result']=$this->apiadminmodel->get_all_classtest_details($student_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    // GET ALL CLASSTEST  DETAILS
    public function get_classtest_details()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    	$hw_id=$this->input->post('ct_id');
    	$data['result']=$this->apiadminmodel->get_classtest_details($hw_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    // GET ALL EXAM  DETAILS
    public function get_all_exam_details()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    
    	$data['result']=$this->apiadminmodel->get_all_exam_details();
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    
    // GET ALL INDIVIDUAL EXAM  DETAILS
    public function get_exam_details()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    	$student_id=$this->input->post('student_id');
    	$exam_id=$this->input->post('exam_id');
    	$data['result']=$this->apiadminmodel->get_exam_details($student_id,$exam_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    // GET ALL TEACHERS
    public function get_all_teachers()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    
    	$data['result']=$this->apiadminmodel->get_all_teachers();
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    
    // GET  TEACHER DETAIlS
    public function get_teacher()
    {
    	 //$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    
    	$teacher_id=$this->input->post('teacher_id');
    	$data['result']=$this->apiadminmodel->get_teacher($teacher_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    // GET  TEACHER CLASS DETAIlS
    public function get_teacher_class_details()
    {
     $_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    	$teacher_id=$this->input->post('teacher_id');
    	$data['result']=$this->apiadminmodel->get_teacher_class_details($teacher_id);
    	$response = $data['result'];
    
    echo json_encode($response);
    }
    
    
    
    // GET  LIST OF PARENTS
    public function get_list_of_parents()
    {
    
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    	$class_id=$this->input->post('class_id');
    	$section_id=$this->input->post('section_id');
    	$data['result']=$this->apiadminmodel->get_list_of_parents($class_id,$section_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    
    // GET  PARENT DETAILS
    public function get_parent_details()
    {
        
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
     	  $admission_id=$this->input->post('admission_id');
     	
    	$data['result']=$this->apiadminmodel->get_parent_details($admission_id);
        $response = $data['result'];
        // print_r($response);
        echo json_encode($response);
    }
    
    
    
    // GET  PARENT DETAILS
    public function get_parent_student_list()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
     	$parent_id=$this->input->post('parent_id');
    	$data['result']=$this->apiadminmodel->get_parent_student_list($parent_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    // GET  LIST OF TEACHER FOR A CLASS
    public function list_of_teachers_for_class()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    	$class_id=$this->input->post('class_id');
    	$section_id=$this->input->post('section_id');
    	$data['result']=$this->apiadminmodel->list_of_teachers_for_class($class_id,$section_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    // GET  LIST OF EXAM FOR A CLASS
    public function list_of_exams_class()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    	$class_id=$this->input->post('class_id');
    	$section_id=$this->input->post('section_id');
    	$data['result']=$this->apiadminmodel->list_of_exams_class($class_id,$section_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    // GET  Timetable FOR A CLASS
    public function get_timetable_for_class()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    	$class_id=$this->input->post('class_id');
    	$section_id=$this->input->post('section_id');
    	$data['result']=$this->apiadminmodel->get_timetable_for_class($class_id,$section_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    // GET  FEES MASTER FOR A CLASS
    public function get_fees_master_class()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    	$class_id=$this->input->post('class_id');
    	$section_id=$this->input->post('section_id');
    	$data['result']=$this->apiadminmodel->get_fees_master_class($class_id,$section_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    // GET  FEES MASTER FOR A CLASS
    public function get_fees_details()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    	$fees_id=$this->input->post('fees_id');
    	$data['result']=$this->apiadminmodel->get_fees_details($fees_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    // GET  FEES STATUS FOR A CLASS
    public function get_fees_status()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    	$class_id=$this->input->post('class_id');
    	$section_id=$this->input->post('section_id');
    	$fees_id=$this->input->post('fees_id');
    	$data['result']=$this->apiadminmodel->get_fees_status($class_id,$section_id,$fees_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    
    // GET  LIST OF EXAM  FOR A CLASS
    public function get_list_exam_class()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    	$class_id=$this->input->post('class_id');
    	$section_id=$this->input->post('section_id');
    
    	$data['result']=$this->apiadminmodel->get_list_exam_class($class_id,$section_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    
    // GET   EXAM  DETAILS FOR A CLASS
    public function get_exam_details_class()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    	$exam_id=$this->input->post('exam_id');
    	$class_id=$this->input->post('class_id');
    
    	$data['result']=$this->apiadminmodel->get_exam_details_class($exam_id,$class_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    
    
    // GET   EXAM  MARKS FOR A CLASS
    public function get_exam_marks_class()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    	$exam_id=$this->input->post('exam_id');
    	$class_id=$this->input->post('class_id');
    	$section_id=$this->input->post('section_id');
    
    	$data['result']=$this->apiadminmodel->get_exam_marks_class($exam_id,$class_id,$section_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    // GET  TEACHERS OD VIEW
    public function get_teachers_od_view()
    {
        $_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    
    	$user_id=$this->input->post('user_id');
    	$data['result']=$this->apiadminmodel->get_teachers_od_view($user_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    // GET  TEACHERS OD VIEW
    public function update_teachers_od()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    
    	$od_id=$this->input->post('od_id');
    	$status=$this->input->post('status');
    	
    	$data['result']=$this->apiadminmodel->update_teachers_od($od_id,$status);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    // GET  STUDENTS OD VIEW
    public function get_students_od_view()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    
    	$user_id=$this->input->post('user_id');
    	$data['result']=$this->apiadminmodel->get_students_od_view($user_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    
    // GET  TEACHERS LEAVE
    public function get_teachers_leaves()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    
    	$user_id=$this->input->post('user_id');
    	$data['result']=$this->apiadminmodel->get_teachers_leaves($user_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    // UPDATE  TEACHERS LEAVE
    public function update_teachers_leaves()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    
    	$leave_id=$this->input->post('leave_id');
    	$status=$this->input->post('status');
    	
    	$data['result']=$this->apiadminmodel->update_teachers_leaves($leave_id,$status);
    	$response = $data['result'];
    	echo json_encode($response);
    }
    
    
    
    // GET ALL Cricular 
    public function get_all_circular_view()
    {
    	$_POST = json_decode(file_get_contents("php://input"), TRUE);
    
    	if(!$this->checkMethod())
    	{
    		return FALSE;
    	}
    
    	if($_POST == FALSE)
    	{
    		$res = array();
    		$res["opn"] = "SOMETHING WENT WRONG ";
    		$res["scode"] = 204;
    		$res["message"] = "Input error";
    
    		echo json_encode($res);
    		return;
    	}
    
    	$user_id=$this->input->post('user_id');
    	$data['result']=$this->apiadminmodel->get_all_circular_view($user_id);
    	$response = $data['result'];
    	echo json_encode($response);
    }




//-----------------------------------------------//
/*
	public function disp_timetabledays()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Attendence Days";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$class_id = '';
		$class_id = $this->input->post("class_id");


		$data['result']=$this->apiadminmodel->dispTimetable_days($class_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function disp_timetable()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Attendence Display";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$class_id = '';
		$day_id = '';
		$class_id = $this->input->post("class_id");
		$day_id = $this->input->post("day_id");

		$data['result']=$this->apiadminmodel->dispTimetable($class_id,$day_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//
*/

//-----------------------------------------------//

	public function list_class_section()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "View Class Sections";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$user_id = '';
		$user_id = $this->input->post("user_id");


		$data['result']=$this->apiadminmodel->listClasssection($user_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function add_timetableremarks()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Timetable Review";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		$review_id = '';
		$remarks = '';
		$review_id = $this->input->post("review_id");
		$remarks = $this->input->post("remarks");

		$data['result']=$this->apiadminmodel->addTimetableremarks($review_id,$remarks);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//



//-----------------------------------------------//

	public function add_groupmaster()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Create Group Master";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		
		$user_id = '';
		$group_title = '';
		$group_lead = '';
		$status = '';
		$user_id = $this->input->post("user_id");
		$group_title = $this->input->post("group_title");
		$group_lead = $this->input->post("group_lead_id");
		$status = $this->input->post("status");

		$data['result']=$this->apiadminmodel->addGroupmaster($user_id,$group_title,$group_lead,$status);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function list_groupmaster()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "List Group Master";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		
		$user_id = '';
		$user_id = $this->input->post("user_id");

		$data['result']=$this->apiadminmodel->listGroupmaster($user_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function view_groupmaster()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "View Group Master";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		
		$group_id = '';
		$group_id = $this->input->post("group_id");

		$data['result']=$this->apiadminmodel->viewGroupmaster($group_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function update_groupmaster()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Update Group Master";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		
		$user_id = '';
		$group_id = '';
		$group_title = '';
		$group_lead = '';
		$status = '';
		$user_id = $this->input->post("user_id");
		$group_id = $this->input->post("group_id");
		$group_title = $this->input->post("group_title");
		$group_lead = $this->input->post("group_lead_id");
		$status = $this->input->post("status");

		$data['result']=$this->apiadminmodel->updateGroupmaster($user_id,$group_id,$group_title,$group_lead,$status);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function get_allteachersuserid()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "All Techers with User id";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		
		$user_id = '';
		$user_id = $this->input->post("user_id");

		$data['result']=$this->apiadminmodel->getAllteachersuserid($user_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function get_allstaffsuserid()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "All Staffs with User id";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		
		$user_id = '';
		$user_id = $this->input->post("user_id");

		$data['result']=$this->apiadminmodel->getAllstaffsuserid($user_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function get_allstudentuserid()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "All Staffs with User id";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		
		$class_id = '';
		$class_id = $this->input->post("class_id");

		$data['result']=$this->apiadminmodel->getAllstudentuserid($class_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function gn_stafflist()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "All Staffs with User id";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		
		$group_id = '';
		$group_user_type = '';
		$group_id = $this->input->post("group_id");
		$group_user_type = $this->input->post("group_user_type");

		$data['result']=$this->apiadminmodel->gnStafflist($group_id,$group_user_type);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function gn_studentlist()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "All Student with User id";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		
		$group_id = '';
		$group_user_type = '';
		$class_id = '';
		$group_id = $this->input->post("group_id");
		$group_user_type = $this->input->post("group_user_type");
		$class_id = $this->input->post("class_id");

		$data['result']=$this->apiadminmodel->gnStudentlist($group_id,$group_user_type,$class_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function add_gn_members()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Add Members for Group Notification";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		
		$user_id = '';
		$group_id = '';
		$group_member_id = '';
		$group_user_type = '';
		$status = '';
		$user_id = $this->input->post("user_id");
		$group_id = $this->input->post("group_id");
		$group_member_id = $this->input->post("group_member_id");
		$group_user_type = $this->input->post("group_user_type");
		$class_sec_id = $this->input->post("class_sec_id");
		$status = $this->input->post("status");

		$data['result']=$this->apiadminmodel->addgnMembers($user_id,$group_id,$group_member_id,$group_user_type,$class_sec_id,$status);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function list_gn_members()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "List Members for Group Notification";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		
		$group_id = '';
		$group_id = $this->input->post("group_id");

		$data['result']=$this->apiadminmodel->listgnMembers($group_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//
	public function group_msg_send()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Send Group Message";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		
		$user_id = '';
		$group_id = '';
		$notes = '';
		$circular_type = '';
		
		$user_id = $this->input->post("user_id");
		$group_id = $this->input->post("group_id");
		$notes = $this->input->post("notes");
		$snotes     = $this->db->escape_str($this->input->post('notes'));
		$notification_type = $this->input->post('notification_type');
		
		$cir = explode(',',$notification_type);
	    $cir_cnt = count($cir);

				
			if($cir_cnt==3)	{
				$data = $this->apiadminmodel->gn_send_mail($group_id,$notes,$user_id);
				$data = $this->apiadminmodel->gn_send_message($group_id,$notes,$user_id);
				$data = $this->apiadminmodel->gn_send_notification($group_id,$notes,$user_id);
			 }
				 
			 if($cir_cnt==2)  {
		 		  	$ct1=$cir[0];
		 	    	$ct2=$cir[1];

		 		  if($ct1=='SMS' && $ct2=='Mail')
		 		  {
					 $data = $this->apiadminmodel->gn_send_mail($group_id,$notes,$user_id);
 					 $data = $this->apiadminmodel->gn_send_message($group_id,$notes,$user_id);
		 		  }
		 		  if($ct1=='SMS' && $ct2=='Notification')
		 		  {
					 $data = $this->apiadminmodel->gn_send_message($group_id,$notes,$user_id);
 					 $data = $this->apiadminmodel->gn_send_notification($group_id,$notes,$user_id);
		 		  }
		 		  if($ct1=='Mail' && $ct2=='Notification')
		 		  {
 					 $data = $this->apiadminmodel->gn_send_mail($group_id,$notes,$user_id);
 					 $data = $this->apiadminmodel->gn_send_notification($group_id,$notes,$user_id);
		 		  }

		 	  }
			 if($cir_cnt==1) {
				  $ct=$cir[0];
				  if($ct=='SMS')
				  {
						$data = $this->apiadminmodel->gn_send_message($group_id,$notes,$user_id);
				  }
				  if($ct=='Notification')
				  {
						 $data = $this->apiadminmodel->gn_send_notification($group_id,$notes,$user_id);
				  }
				  if($ct=='Mail')
				  {
						$data = $this->apiadminmodel->gn_send_mail($group_id,$notes,$user_id);
				  }
			  }
				$data['result']= $this->apiadminmodel->save_group_history($group_id,$notification_type,$snotes,$user_id);
				$response = $data['result'];
				echo json_encode($response);
	}
	//-----------------------------------------------//
	
	//-----------------------------------------------//

	public function add_circular()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);
		
		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Add Circular";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

		 $user_id = $this->input->post("user_id");
		 $circular_title = $this->input->post("circular_title");
		 $circular_description = $this->input->post("circular_description");
		 $status  = $this->input->post("status");
		
		$data['result']=$this->apiadminmodel->addCircular($user_id,$circular_title,$circular_description,$status);
		$response = $data['result'];
		echo json_encode($response);
	}
//-----------------------------------------------//

//-----------------------------------------------//

	public function list_circular()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "List Circular";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		
		$user_id = '';
		$user_id = $this->input->post("user_id");

		$data['result']=$this->apiadminmodel->listCircular($user_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function view_circular()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "View Circular";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		
		$circular_id = '';
		$circular_id = $this->input->post("circular_id");

		$data['result']=$this->apiadminmodel->viewCircular($circular_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function update_circular()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);
		
		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Update Circular";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		
		$user_id = $this->input->post("user_id");
		$circular_id = $this->input->post("circular_id");
		$circular_title = $this->input->post("circular_title");
		$circular_description = $this->input->post("circular_description");
		$status  = $this->input->post("status");

		$data['result']=$this->apiadminmodel->updateCircular($user_id,$circular_id,$circular_title,$circular_description,$status);
	
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function update_circular_doc()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);
		
		$user_id = $this->input->post("user_id");
		$circular_id = $this->input->post("circular_id");

		$profile = $_FILES["circular_doc"]["name"];
		$userFileName = time().'-'.$profile;
		$uploadPicdir = 'assets/circular/';
		$profilepic = $uploadPicdir.$userFileName;
		move_uploaded_file($_FILES['circular_doc']['tmp_name'], $profilepic);

		$data['result']=$this->apiadminmodel->updateCirculardoc($user_id,$circular_id,$userFileName);
	
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//

//-----------------------------------------------//

	public function list_roles()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "List Roles";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}
		
		$user_id = '';
		$user_id = $this->input->post("user_id");

		$data['result']=$this->apiadminmodel->listRoles($user_id);
		$response = $data['result'];
		echo json_encode($response);
	}

//-----------------------------------------------//


//-----------------------------------------------//
	public function circular_msg_send()
	{
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if(!$this->checkMethod())
		{
			return FALSE;
		}

		if($_POST == FALSE)
		{
			$res = array();
			$res["opn"] = "Send Circular Message";
			$res["scode"] = 204;
			$res["message"] = "Input error";

			echo json_encode($res);
			return;
		}

			$user_id=$this->input->post('user_id');
			$all_id=$this->input->post('allusers');
			$tusers_id=$this->input->post('tusers');
			$musers_id=$this->input->post('musers');
			$susers_id=$this->input->post('susers');
			$pusers_id=$this->input->post('pusers');
			$circular_id=$this->input->post('circular_id'); 
			$circular_date=$this->input->post('circular_date');
			//$dateTime = new DateTime($cdate);
			//$circulardate=date_format($dateTime,'Y-m-d' );
			$circular_type=$this->input->post('circular_type');
			$status=$this->input->post('status'); 
		  
			 $cir = explode(',',$circular_type);
			 //print_r($cir);
			 $cir_cnt = count($cir);

			if($cir_cnt==3)	{
				$data = $this->apiadminmodel->send_circular_sms($circular_id,$all_id,$tusers_id,$musers_id,$susers_id,$pusers_id);
				$data = $this->apiadminmodel->send_circular_email($circular_id,$all_id,$tusers_id,$musers_id,$susers_id,$pusers_id);
				$data = $this->apiadminmodel->send_circular_notification($circular_id,$all_id,$tusers_id,$musers_id,$susers_id,$pusers_id);
			 }
				 
			 if($cir_cnt==2)  {
		 		  	$ct1=$cir[0];
		 	    	$ct2=$cir[1];

		 		  if($ct1=='SMS' && $ct2=='Mail')
		 		  {
					 $data = $this->apiadminmodel->send_circular_sms($circular_id,$all_id,$tusers_id,$musers_id,$susers_id,$pusers_id);
 					$data = $this->apiadminmodel->send_circular_email($circular_id,$all_id,$tusers_id,$musers_id,$susers_id,$pusers_id);
		 		  }
		 		  if($ct1=='SMS' && $ct2=='Notification')
		 		  {
					 $data = $this->apiadminmodel->send_circular_sms($circular_id,$all_id,$tusers_id,$musers_id,$susers_id,$pusers_id);
 					 $data = $this->apiadminmodel->send_circular_notification($circular_id,$all_id,$tusers_id,$musers_id,$susers_id,$pusers_id);
		 		  }
		 		  if($ct1=='Mail' && $ct2=='Notification')
		 		  {
 					$data = $this->apiadminmodel->send_circular_email($circular_id,$all_id,$tusers_id,$musers_id,$susers_id,$pusers_id);
 					$data = $this->apiadminmodel->send_circular_notification($circular_id,$all_id,$tusers_id,$musers_id,$susers_id,$pusers_id);
		 		  }

		 	  }
			 if($cir_cnt==1) {
				  $ct=$cir[0];
				  if($ct=='SMS')
				  {
						$data = $this->apiadminmodel->send_circular_sms($circular_id,$all_id,$tusers_id,$musers_id,$susers_id,$pusers_id);
				  }
				  if($ct=='Mail')
				  {
						$data = $this->apiadminmodel->send_circular_email($circular_id,$all_id,$tusers_id,$musers_id,$susers_id,$pusers_id);
				  }
				  if($ct=='Notification')
				  {
						 $data = $this->apiadminmodel->send_circular_notification($circular_id,$all_id,$tusers_id,$musers_id,$susers_id,$pusers_id);
				  }
			  }
			 
				$data['result']= $this->apiadminmodel->save_circular_history($circular_id,$circular_date,$circular_type,$all_id,$tusers_id,$musers_id,$susers_id,$pusers_id,$status,$user_id);
				$response = $data['result'];
				echo json_encode($response);
	}
	//-----------------------------------------------//
	
	
    //-----------------------------------------------//
	// GET ALL Cricular class wise
		public function get_class_circular_view()
		{
			$_POST = json_decode(file_get_contents("php://input"), TRUE);

			if(!$this->checkMethod())
			{
				return FALSE;
			}

			if($_POST == FALSE)
			{
				$res = array();
				$res["opn"] = "SOMETHING WENT WRONG ";
				$res["scode"] = 204;
				$res["message"] = "Input error";

				echo json_encode($res);
				return;
			}

			$user_type=$this->input->post('user_type');
			$data['result']=$this->apiadminmodel->get_class_circular_view($user_type);
			$response = $data['result'];
			echo json_encode($response);
		}
	//-----------------------------------------------//

}
