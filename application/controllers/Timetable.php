<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Timetable extends CI_Controller {


	function __construct() {
		 parent::__construct();

			$this->load->model('yearsmodel');
			$this->load->model('subjectmodel');
			$this->load->model('teachermodel');
			$this->load->model('class_manage');
			$this->load->model('timetablemodel');
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
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 // Class section


	 	public function home(){
	 		 	$datas=$this->session->userdata();
  	 		$user_id=$this->session->userdata('user_id');
				$user_type=$this->session->userdata('user_type');
				$datas['getall_class']=$this->class_manage->getall_class();
				$datas['subres'] = $this->subjectmodel->getsubject();
				$datas['teacheres'] = $this->teachermodel->get_all_teacher();
  			$datas['years'] = $this->timetablemodel->getall_years();
				$datas['resterms'] = $this->yearsmodel->getall_terms();
			 if($user_type==1){
	 		 $this->load->view('header');
	 		 $this->load->view('timetable/add_timetable',$datas);
	 		 $this->load->view('footer');
	 		 }
	 		 else{
	 				redirect('/');
	 		 }
	 	}




		public function add_timetable_class(){
			$datas=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_type');
			if($user_type==1){
				$class_id=$this->input->post('class_id');
				$year_id=$this->input->post('year_id');
				$term_id=$this->input->post('term_id');
				$day_id=$this->input->post('day_id');
				if($day_id==1){$day_name="Monday";}
				else if($day_id==2){$day_name="Tuesday";}
				else if($day_id==3){$day_name="Wednesday";}
				else if($day_id==4){$day_name="Thursday";}
				else if($day_id==5){$day_name="Friday";}
				else if($day_id==6){$day_name="Saturday";}
				else if($day_id==7){$day_name="Sunday";}
				else{$day_name="No Day Is Selected";}
				$data=$this->timetablemodel->check_timetable_day($class_id,$year_id,$term_id,$day_id);
				if($data['status']=='success'){
					$data['class_id']=$class_id;$data['term_id']=$term_id;$data['day_id']=$day_id;$data['day_name']=$day_name;
					$this->session->set_flashdata('msg', 'Add Time Table here for '.$day_name.'');
					$data['years'] = $this->timetablemodel->getall_years();
					$class_sec_id=$class_id;

					$this->load->view('header');
					$this->load->view('timetable/add_timetable',$data);
					$this->load->view('footer');
				}else if($data['status']=='already'){
					$this->session->set_flashdata('msg', 'Time Table Already Created for '.$day_name.'');
 				redirect('timetable/selectdays');
				}else{
					$this->session->set_flashdata('msg', 'Something Went Wrong');
 				redirect('timetable/selectdays');
				}
			}else{

			}

		}

		public function select_term(){
				$datas=$this->session->userdata();
				$user_id=$this->session->userdata('user_id');
				$user_type=$this->session->userdata('user_type');
				$datas['getall_class']=$this->class_manage->getall_class();
				$datas['get_all_days'] = $this->timetablemodel->get_all_days();
				$datas['years'] = $this->timetablemodel->getall_years();
				$datas['resterms'] = $this->yearsmodel->getall_terms();
			 if($user_type==1){
			 $this->load->view('header');
			 $this->load->view('timetable/select_term',$datas);
			 $this->load->view('footer');
			 }
			 else{
					redirect('/');
			 }
		}
		public function view_term(){
				$datas=$this->session->userdata();
				$user_id=$this->session->userdata('user_id');
				$user_type=$this->session->userdata('user_type');
				$datas['getall_class']=$this->class_manage->getall_class();
				$datas['get_all_days'] = $this->timetablemodel->get_all_days();
				$datas['years'] = $this->timetablemodel->getall_years();
				$datas['resterms'] = $this->yearsmodel->getall_terms();
			 if($user_type==1){
			 $this->load->view('header');
			 $this->load->view('timetable/view_terms',$datas);
			 $this->load->view('footer');
			 }
			 else{
					redirect('/');
			 }
		}
		public function selectclass(){
				$datas=$this->session->userdata();
				$user_id=$this->session->userdata('user_id');
				$user_type=$this->session->userdata('user_type');
				$datas['getall_class']=$this->class_manage->getall_class();
				$datas['get_all_days'] = $this->timetablemodel->get_all_days();
			 if($user_type==1){
			 $this->load->view('header');
			 $this->load->view('timetable/select_class',$datas);
			 $this->load->view('footer');
			 }
			 else{
					redirect('/');
			 }
		}
		public function view_class(){
				$datas=$this->session->userdata();
				$user_id=$this->session->userdata('user_id');
				$user_type=$this->session->userdata('user_type');
				$datas['getall_class']=$this->class_manage->getall_class();
				$datas['get_all_days'] = $this->timetablemodel->get_all_days();
			 if($user_type==1){
			 $this->load->view('header');
			 $this->load->view('timetable/view_class',$datas);
			 $this->load->view('footer');
			 }
			 else{
					redirect('/');
			 }
		}


				public function select_day(){
						$datas=$this->session->userdata();
						$user_id=$this->session->userdata('user_id');
						$user_type=$this->session->userdata('user_type');
						$datas['getall_class']=$this->class_manage->getall_class();
						$datas['get_all_days'] = $this->timetablemodel->get_all_days();
						$class_id=base64_decode($this->uri->segment(3))/9876;
						$term_id=base64_decode($this->uri->segment(4))/9876;
						$class_sec_id=base64_decode($this->uri->segment(3))/9876;
						$datas['years'] = $this->timetablemodel->getall_years();
						$datas['get_name_class']=$this->class_manage->edit_cs($class_id);
						$datas['res_subject']=$this->timetablemodel->get_subject_class($class_sec_id);
						$datas['res_teacher']=$this->timetablemodel->get_teacher_class($class_sec_id);
						$datas['restime']=$this->timetablemodel->timetable_for_class($term_id,$class_id);
						 if($user_type==1){
						 $this->load->view('header');
						 $this->load->view('timetable/select_days',$datas);
						 $this->load->view('footer');
						 }
					 else{
							redirect('/');
					 }
				}
				public function view_day(){
						$datas=$this->session->userdata();
						$user_id=$this->session->userdata('user_id');
						$user_type=$this->session->userdata('user_type');
						$datas['getall_class']=$this->class_manage->getall_class();
						$datas['get_all_days'] = $this->timetablemodel->get_all_days();
						$class_id=base64_decode($this->uri->segment(3))/9876;
						$term_id=base64_decode($this->uri->segment(4))/9876;
						$class_sec_id=base64_decode($this->uri->segment(3))/9876;
						$datas['years'] = $this->timetablemodel->getall_years();
						$datas['get_name_class']=$this->class_manage->edit_cs($class_id);
						$datas['res_subject']=$this->timetablemodel->get_subject_class($class_sec_id);
						$datas['res_teacher']=$this->timetablemodel->get_teacher_class($class_sec_id);
						$datas['restime']=$this->timetablemodel->timetable_for_class($term_id,$class_id);
						 if($user_type==1){
						 $this->load->view('header');
						 $this->load->view('timetable/view_days',$datas);
						 $this->load->view('footer');
						 }
					 else{
							redirect('/');
					 }
				}

				public function view_timetable_day(){
					$datas=$this->session->userdata();
					$user_id=$this->session->userdata('user_id');
					$user_type=$this->session->userdata('user_type');
					$class_id=base64_decode($this->uri->segment(3))/9876;
				 	$term_id=base64_decode($this->uri->segment(4))/9876;
					$day_id=base64_decode($this->uri->segment(5));
					if($user_type==1){
					$datas['res']=$this->timetablemodel->view_timetable_day($term_id,$class_id,$day_id);
					$datas['get_name_class']=$this->class_manage->edit_cs($class_id);
					// print_r(	$datas['res']);
					// exit;
					$this->load->view('header');
					$this->load->view('timetable/view_timetable_day',$datas);
					$this->load->view('footer');
					}
				else{
					 redirect('/');
				}
				}

				public function edit_time_table(){
					$datas=$this->session->userdata();
					$user_id=$this->session->userdata('user_id');
					$user_type=$this->session->userdata('user_type');
					$table_id=base64_decode($this->uri->segment(3))/9876;
					$class_sec_id=base64_decode($this->uri->segment(4))/9876;
					$class_id=base64_decode($this->uri->segment(4))/9876;
						if($user_type==1){
						$datas['get_name_class']=$this->class_manage->edit_cs($class_id);
						$datas['res_subject']=$this->timetablemodel->get_subject_class($class_sec_id);
						$datas['res_teacher']=$this->timetablemodel->get_teacher_class($class_sec_id);
						$datas['res']=$this->timetablemodel->edit_time_table($table_id);
						$this->load->view('header');
						$this->load->view('timetable/edit_time_table',$datas);
						$this->load->view('footer');
						}
					else{
						 redirect('/');
					}
				}

				public function update_timetable_for_class(){
					$datas=$this->session->userdata();
					$user_id=$this->session->userdata('user_id');
					$user_type=$this->session->userdata('user_type');
					if($user_type==1){
						$subject_id=$this->input->post('subject_id');
						$teacher_id=$this->input->post('teacher_id');
						$table_id=$this->input->post('table_id');
						$is_break=$this->input->post('is_break');
						$break_name=$this->input->post('break_name');
						$datas=$this->timetablemodel->update_timetable_for_class($subject_id,$teacher_id,$table_id,$is_break,$break_name,$user_id);
					}
				else{
					 redirect('/');
				}
				}

		public function create_timetable_for_class()
		{
			$datas=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_type');
		    if($user_type==1){
			 $class_id=$this->input->post('class_id');
			 $year_id=$this->input->post('year_id');
			 $term_id=$this->input->post('term_id');
			 $subject_id=$this->input->post('subject_id');
			 $teacher_id=$this->input->post('teacher_id');
			 $day_id=$this->input->post('day_id');
			 $from_time=$this->input->post('from_time');
			 $to_time=$this->input->post('to_time');
			 $break_name=$this->input->post('break_name');

			 $time="00:05:00"; //5 minutes
			 if(strtotime($from_time)<=strtotime($to_time)) {
				 $period_id=$this->input->post('period_id');
				 $break_id=$this->input->post('is_break');
				 $datas=$this->timetablemodel->create_timetable($year_id,$term_id,$class_id,$subject_id,$teacher_id,$day_id,$period_id,$break_id,$from_time,$to_time,$break_name);

			 } else {
			  echo "lesser";
			 }
			 exit;

		 }
		 else{
			 redirect('/');
		 }
		}




		public function manage(){
			$datas=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_type');
		 if($user_type==1){
			 $datas['getall_class1']=$this->timetablemodel->view_class_timetable();
			 $datas['resterms'] = $this->yearsmodel->getall_terms();
			 $this->load->view('header');
			$this->load->view('timetable/manage',$datas);
			$this->load->view('footer');
		 }
		 else{
			 redirect('/');
		 }
		}



				public function termwise($term_id){
					$datas=$this->session->userdata();
					$user_id=$this->session->userdata('user_id');
					$user_type=$this->session->userdata('user_type');
					$datas['getall_class1']=$this->timetablemodel->termwise($term_id);
				 if($user_type==1){
					 $this->load->view('header');
		 			$this->load->view('timetable/termwise_timetable',$datas);
		 			$this->load->view('footer');
				 }
				 else{
					 redirect('/');
				 }
				}

		public function view($class_sec_id,$term_id){
			$datas=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_type');
			$class_sec_id=base64_decode($class_sec_id);
			$class_id=$class_sec_id;
			$datas['get_name_class']=$this->class_manage->edit_cs($class_id);
			$datas['restime']=$this->timetablemodel->view($class_sec_id,$term_id);
		 if($user_type==1){
			 	$this->load->view('header');
	 			$this->load->view('timetable/view',$datas);
	 			$this->load->view('footer');
		 }
		 else{
			 redirect('/');
		 }
		}

		public function edit($class_sec_id,$term_id){
			$datas=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_type');
		  $class_sec_id=base64_decode($class_sec_id);
			$datas['restime']=$this->timetablemodel->view($class_sec_id,$term_id);
			$datas['res_sub']=$this->timetablemodel->get_subject_class($class_sec_id);
			$datas['res_teacher']=$this->timetablemodel->get_teacher_class($class_sec_id);
			$datas['class_id']=$class_sec_id;
			$datas['term_id']=$term_id;
		 if($user_type==1){
			 $this->load->view('header');
 			$this->load->view('timetable/edit',$datas);
 			$this->load->view('footer');
		 }
		 else{
			 redirect('/');
		 }
		}

		public function update_timetable()
		{
			$datas=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_type');
		    if($user_type==1){
			 $class_id=$this->input->post('class_id');
			 $year_id=$this->input->post('year_id');
			 $term_id=$this->input->post('term_id');
			 $subject_id=$this->input->post('subject_id');
			 $teacher_id=$this->input->post('teacher_id');
			 $day_id=$this->input->post('day_id');
			 $period_id=$this->input->post('period_id');
			 $datas=$this->timetablemodel->update_timetable($year_id,$term_id,$class_id,$subject_id,$teacher_id,$day_id,$period_id);
			 if($datas['status']=='Already'){
				 $this->session->set_flashdata('msg', 'Time Table Already Assigned to this Class');
				 redirect('timetable/home');
			 }elseif($datas['status']=='success'){
				 $this->session->set_flashdata('msg', 'Updated Successfully');
				redirect('timetable/manage');
			 }
			 else{
				 redirect('timetable/manage');
			 }
		 }
		 else{
			 redirect('/');
		 }
		}



		public function reviewview(){
				$datas=$this->session->userdata();
				$user_id=$this->session->userdata('user_id');
				$user_type=$this->session->userdata('user_type');
				$datas['res']=$this->timetablemodel->view_review_all();
			 if($user_type==1){
			 $this->load->view('header');
			 $this->load->view('timetable/tablereview',$datas);
			 $this->load->view('footer');
			 }
			 else{
					redirect('/');
			 }
		}


		public function edit_review($timetable_id){
				$datas=$this->session->userdata();
				$user_id=$this->session->userdata('user_id');
				$user_type=$this->session->userdata('user_type');
				$datas['res']=$this->timetablemodel->edit_review_all($timetable_id);
			 if($user_type==1){
			 $this->load->view('header');
			 $this->load->view('timetable/update_review',$datas);
			 $this->load->view('footer');
			 }
			 else{
					redirect('/');
			 }
		}


		public function save_user_review(){
				$datas=$this->session->userdata();
				$user_id=$this->session->userdata('user_id');
				$user_type=$this->session->userdata('user_type');
			 if($user_type==1){
			 $timetable_id=$this->input->post('timetable_id');
			 $remarks=$this->input->post('remarks');
			 $datas=$this->timetablemodel->save_user_review($timetable_id,$remarks);
			 if($datas['status']=='failure'){
				$this->session->set_flashdata('msg', 'Somthing Went Wrong');
				redirect('timetable/reviewview');
			}elseif($datas['status']=='success'){
				$this->session->set_flashdata('msg', 'Updated Successfully');
			 redirect('timetable/reviewview');
			}
			else{
				redirect('timetable/reviewview');
			}

			 }
			 else{
					redirect('/');
			 }
		}



			public function getsubject(){
				  $class_sec_id=$this->input->post('class_id');
				$datas['res']=$this->timetablemodel->get_subject_class($class_sec_id);
				echo json_encode( $datas['res']);
			}

			public function getTeacher(){
				$class_sec_id=$this->input->post('class_id');
			  $datas['res']=$this->timetablemodel->get_teacher_class($class_sec_id);
			  echo json_encode( $datas['res']);
			}

		public function delete(){
			$datas=$this->session->userdata();
			$user_id=$this->session->userdata('user_id');
			$user_type=$this->session->userdata('user_type');
			 $class_id=$this->input->post('class_id');
			 $term_id=$this->input->post('term_id');
			 $day_id=$this->input->post('day_id');
			$datas=$this->timetablemodel->delete_time($class_id,$term_id,$day_id);
		 if($user_type==1){
			 if($datas['status']=="success"){
				 echo "success";

			}else{
				echo "failure";
			}
		 }
		 else{
			 redirect('/');
		 }
		}


















}
