<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apiadminmodel extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }


//#################### Email ####################//

	public function sendMail($email,$subject,$email_message)
	{
		// Set content-type header for sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		// Additional headers
		$headers .= 'From: Webmaster<hello@happysanz.com>' . "\r\n";
		mail($email,$subject,$email_message,$headers);
	}

//#################### Email End ####################//


//#################### SMS ####################//

	public function sendSMS($Phoneno,$Message)
	{
        //Your authentication key
        $authKey = "191431AStibz285a4f14b4";
        
        //Multiple mobiles numbers separated by comma
        $mobileNumber = "$Phoneno";
        
        //Sender ID,While using route4 sender id should be 6 characters long.
        $senderId = "EDUAPP";
        
        //Your message to send, Add URL encoding here.
        $message = urlencode($Message);
        
        //Define route 
        $route = "transactional";
        
        //Prepare you post parameters
        $postData = array(
            'authkey' => $authKey,
            'mobiles' => $mobileNumber,
            'message' => $message,
            'sender' => $senderId,
            'route' => $route
        );
        
        //API URL
        $url="https://control.msg91.com/api/sendhttp.php";
        
        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
            //,CURLOPT_FOLLOWLOCATION => true
        ));
        
        
        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        
        //get response
        $output = curl_exec($ch);
        
        //Print error if any
        if(curl_errno($ch))
        {
            echo 'error:' . curl_error($ch);
        }
        
        curl_close($ch);
	}

//#################### SMS End ####################//


//#################### Notification ####################//

	public function sendNotification($gcm_key,$title,$message,$mobiletype)
	{
		if ($mobiletype =='1'){

		    require_once 'assets/notification/Firebase.php';
            require_once 'assets/notification/Push.php'; 
            
            $device_token = explode(",", $gcm_key);
            $push = null; 
        
        //first check if the push has an image with it
		    $push = new Push(
					$title,
					$message,
					null
				);

// 			//if the push don't have an image give null in place of image
// 			 $push = new Push(
// 			 		'HEYLA',
// 		     		'Hi Testing from maran',
// 			 		'http://heylaapp.com/assets/notification/images/event.png'
// 			 	);

    		//getting the push from push object
    		$mPushNotification = $push->getPush(); 
    
    		//creating firebase class object 
    		$firebase = new Firebase(); 

    	foreach($device_token as $token) {
    		 $firebase->send(array($token),$mPushNotification);
    	}

		} else {
            
			$device_token = explode(",", $gcm_key);
			$passphrase = 'hs123';
		    $loction ='assets/notification/happysanz.pem';
		   
			$ctx = stream_context_create();
			stream_context_set_option($ctx, 'ssl', 'local_cert', $loction);
			stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
			
			// Open a connection to the APNS server
			$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
			
			if (!$fp)
				exit("Failed to connect: $err $errstr" . PHP_EOL);

			$body['aps'] = array(
				'alert' => array(
					'body' => $message,
					'action-loc-key' => 'EDU App',
				),
				'badge' => 2,
				'sound' => 'assets/notification/oven.caf',
				);
			$payload = json_encode($body);

			foreach($device_token as $token) {
			
				// Build the binary notification
    			$msg = chr(0) . pack("n", 32) . pack("H*", str_replace(" ", "", $token)) . pack("n", strlen($payload)) . $payload;
        		$result = fwrite($fp, $msg, strlen($msg));
			}
							
				fclose($fp);
		}
		
	}

//#################### Notification End ####################//


//#################### Current Year ####################//

	public function getYear()
	{
		$sqlYear = "SELECT * FROM edu_academic_year WHERE CURDATE() >= from_month AND CURDATE() <= to_month AND status = 'Active'";
		$year_result = $this->db->query($sqlYear);
		$ress_year = $year_result->result();

		if($year_result->num_rows()==1)
		{
			foreach ($year_result->result() as $rows)
			{
			    $year_id = $rows->year_id;
			}
			return $year_id;
		}
	}
//#################### Current Year End ####################//



//#################### Current Term ####################//

	public function getTerm()
	{
	    $year_id = $this->getYear();
		$sqlTerm = "SELECT * FROM edu_terms WHERE CURDATE() >= from_date AND CURDATE() <= to_date AND year_id = '$year_id' AND status = 'Active'";
		$term_result = $this->db->query($sqlTerm);
		$ress_term = $term_result->result();
		
		if($term_result->num_rows()==1)
		{
			foreach ($term_result->result() as $rows)
			{
			    $term_id = $rows->term_id;
			}
			return $term_id;
		}
	}

//#################### Current Term End ####################//

//#################### GET ALL ClASS ####################//

  function get_classes($user_id){
    $sql="SELECT ec.class_name,ec.class_id FROM edu_classmaster AS ecm LEFT JOIN edu_class AS ec ON ec.class_id=ecm.class GROUP BY ec.class_name";
    $res=$this->db->query($sql);
    if($res->num_rows()==0){
        $data=array("status"=>"error","msg"=>"nodata");
        return $data;
    }else{
      $result=$res->result();
      $data=array("status"=>"success","msg"=>"success","data"=>$result);
      return $data;
    }
  }


  //#################### GET ALL SECTIONS ####################//

    function get_all_sections($class_id){
     $sql="SELECT es.sec_name,es.sec_id FROM edu_classmaster AS ecm LEFT JOIN edu_sections AS es ON ecm.section=es.sec_id WHERE ecm.class='$class_id'";
      $res=$this->db->query($sql);
      if($res->num_rows()==0){
          $data=array("status"=>"error","msg"=>"nodata");
          return $data;
      }else{
        $result=$res->result();
        $data=array("status"=>"success","msg"=>"success","data"=>$result);
        return $data;
      }
    }


        //#################### GET ALL Students in class ####################//

          function get_all_students_in_classes($class_id,$section_id){
            $sql="SELECT class_sec_id FROM edu_classmaster WHERE class='$class_id' AND section='$section_id'";
            $res=$this->db->query($sql);
              $result=$res->result();
              foreach($result as $rows){   }
              $classid=$rows->class_sec_id;
              $year_id=$this->getYear();
            $stu_list="SELECT eer.name,eer.enroll_id,eer.admisn_no,ea.sex,ea.admisn_year,eer.class_id FROM edu_enrollment AS eer LEFT JOIN edu_admission AS ea ON ea.admission_id=eer.admission_id WHERE eer.class_id='$classid' AND eer.admit_year='$year_id' AND eer.status='Active' order by eer.enroll_id asc";
            $res_stu=$this->db->query($stu_list);
              $result_stud=$res_stu->result();
            if($res->num_rows()==0){
                $data=array("status"=>"error","msg"=>"nodata");
                return $data;
            }else{
              $result=$res->result();
              $data=array("status"=>"success","msg"=>"success","data"=>$result_stud);
              return $data;
            }
          }



        //#################### GET STUDENT & PARENTS DETAILS ####################//

          function get_student_details($student_id){
               $year_id=$this->getYear();
              $sql="SELECT er.admission_id,ea.* FROM edu_enrollment AS er LEFT JOIN edu_admission AS ea ON er.admission_id=ea.admission_id WHERE er.enroll_id='$student_id'";
            $res_stu=$this->db->query($sql);
            	$admis= $res_stu->result();
            	foreach($admis as $admis_id){}
            	$ad_id=$admis_id->admission_id;
            $student_query = "SELECT * from edu_admission WHERE admission_id='$ad_id' AND status = 'Active'";
						$student_res = $this->db->query($student_query);
						$student_profile= $student_res->result();

							foreach($student_profile as $rows){
								$admit_id = $rows->admission_id;
								  $parent_id = $rows->parnt_guardn_id;
							}
   
                	 	 $father_query = "SELECT * from edu_parents WHERE id IN ($parent_id) AND relationship = 'Father' AND status = 'Active'";
						$father_res = $this->db->query($father_query);
						$father_profile = $father_res->result();

						foreach($father_profile as $rows){ 
								$admisson_id = $rows->admission_id;
								$relationship = $rows->relationship;
						}
                        
					    $fatherProfile  = array(
                            "id" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->id,
                            "name" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->name,
                            "occupation" =>(!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->occupation,
                            "income" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->income,
                            "home_address" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->home_address ,
                            "email" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->email,
                            "mobile" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->mobile,
                            "home_phone" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->home_phone,
                            "office_phone" =>(!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->office_phone,
                            "relationship" =>(!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->relationship,
                            "user_pic" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->user_pic
                          );
                						
						$mother_query = "SELECT * from edu_parents WHERE id IN ($parent_id) AND relationship = 'Mother' AND status = 'Active'";
						$mother_res = $this->db->query($mother_query);
						$mother_profile = $mother_res->result();

						foreach($mother_profile as $rows){ 
								$admisson_id = $rows->admission_id;
						}

				      $motherProfile  = array(
                             "id" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->id,
                            "name" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->name,
                            "occupation" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->occupation,
                            "income" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->income,
                            "home_address" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->home_address ,
                            "email" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->email,
                            "mobile" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->mobile,
                            "home_phone" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->home_phone,
                            "office_phone" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->office_phone,
                            "relationship" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->relationship,
                            "user_pic" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->user_pic
                          );
						
						$guardian_query = "SELECT * from edu_parents WHERE id IN ($parent_id) AND relationship = 'Guardian' AND status = 'Active'";
						$guardian_res = $this->db->query($guardian_query);
						$guardian_profile = $guardian_res->result();

						foreach($guardian_profile as $rows){ 
								$admisson_id = $rows->admission_id;
						}

					 $guardianProfile  = array(
                         "id" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->id,
                        "name" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->name,
                        "occupation" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->occupation,
                        "income" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->income,
                        "home_address" =>(!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->home_address ,
                        "email" =>(!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->email,
                        "mobile" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->mobile,
                        "home_phone" =>(!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->home_phone,
                        "office_phone" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->office_phone,
                        "relationship" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->relationship,
                        "user_pic" =>(!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->user_pic
                      );
            						
						$enroll_query = "SELECT A.enroll_id AS registered_id,A.admission_id,A.admisn_no AS admission_no,A.class_id,A.name,C.class_name,D.sec_name 
						from edu_enrollment A, edu_classmaster B, edu_class C, edu_sections D WHERE A.class_id = B.class_sec_id AND 
						B.class = C.class_id AND B.section = D.sec_id AND A.admit_year ='$year_id' AND A.admission_id = '$admit_id'";
						$enroll_res = $this->db->query($enroll_query);
						$stu_enroll_res= $enroll_res->result();
						
						$parentProfile = array("fatherProfile" =>$fatherProfile,"motherProfile" =>$motherProfile,"guardianProfile" =>$guardianProfile);
						
				  		$response = array("status" => "success", "msg" => "userdetailfound", "studentData" =>$student_profile,"parentProfile" =>$parentProfile,"registeredDetails"=>$stu_enroll_res, "year_id" => $year_id);
						return $response;
          }


      //#################### GET STUDENT & ALL HOMEWORK DETAILS ####################//

        function get_all_howework_details($student_id){
          $sql="SELECT class_id FROM edu_enrollment WHERE enroll_id='$student_id'";
          $res=$this->db->query($sql);
          $result=$res->result();
          foreach($result as $rows){   }
          $classid=$rows->class_id;
          $year_id=$this->getYear();
          $get_all_hw="SELECT eh.hw_type,eh.hw_id,eh.subject_id,eh.title,es.subject_name,eh.test_date FROM edu_homework AS eh LEFT JOIN edu_subject AS es ON es.subject_id=eh.subject_id WHERE eh.class_id='$classid' AND eh.year_id='$year_id' AND eh.status='Active' AND hw_type='HW' ORDER BY eh.test_date DESC";
          $result_hw=$this->db->query($get_all_hw);
          if($result_hw->num_rows()==0){
              $data=array("status"=>"error","msg"=>"nodata");
              return $data;
          }else{
            $result_home=$result_hw->result();
            $data=array("status"=>"success","msg"=>"success","data"=>$result_home);
            return $data;
          }

        }

      //#################### GET STUDENT &  HOMEWORK DETAILS ####################//

        function get_howework_details($hw_id){
          $get_all_hw="SELECT eh.title,eh.hw_type,eh.subject_id,es.subject_name,eh.hw_details,eh.test_date FROM edu_homework AS eh LEFT JOIN edu_subject AS es ON es.subject_id=eh.subject_id WHERE eh.hw_id='$hw_id'";
          $result_hw=$this->db->query($get_all_hw);
          if($result_hw->num_rows()==0){
              $data=array("status"=>"error","msg"=>"nodata");
              return $data;
          }else{
            $result_home=$result_hw->result();
            $data=array("status"=>"success","msg"=>"success","data"=>$result_home);
            return $data;
          }

        }


        //#################### GET STUDENT & ALL CLASSTEST DETAILS ####################//

          function get_all_classtest_details($student_id){
            $sql="SELECT class_id FROM edu_enrollment WHERE enroll_id='$student_id'";
            $res=$this->db->query($sql);
            $result=$res->result();
            foreach($result as $rows){   }
            $classid=$rows->class_id;
            $year_id=$this->getYear();
            $get_all_hw="SELECT eh.hw_type,eh.hw_id,eh.subject_id,eh.title,es.subject_name,eh.test_date FROM edu_homework AS eh LEFT JOIN edu_subject AS es ON es.subject_id=eh.subject_id WHERE eh.class_id='$classid' AND eh.year_id='$year_id' AND eh.status='Active' AND hw_type='HT' ORDER BY eh.test_date DESC";
            $result_hw=$this->db->query($get_all_hw);
            if($result_hw->num_rows()==0){
                $data=array("status"=>"error","msg"=>"nodata");
                return $data;
            }else{
              $result_home=$result_hw->result();
              $data=array("status"=>"success","msg"=>"success","data"=>$result_home);
              return $data;
            }

          }

          //#################### GET STUDENT &  CLASSTEST DETAILS ####################//

            function get_classtest_details($hw_id){
              $get_all_hw="SELECT eh.title,eh.hw_type,eh.subject_id,es.subject_name,eh.hw_details,eh.test_date,eh.mark_status FROM edu_homework AS eh LEFT JOIN edu_subject AS es ON es.subject_id=eh.subject_id WHERE eh.hw_id='$hw_id'";
              $result_hw=$this->db->query($get_all_hw);
              if($result_hw->num_rows()==0){
                  $data=array("status"=>"error","msg"=>"nodata");
                  return $data;
              }else{
                $result_home=$result_hw->result();
                $data=array("status"=>"success","msg"=>"success","data"=>$result_home);
                return $data;
              }

            }


            //#################### GET ALL EXAM DETAILS ####################//

            function get_all_exam_details(){
              $year_id=$this->getYear();
              $sql="SELECT exam_id,exam_name FROM edu_examination WHERE exam_year='$year_id' AND STATUS='Active'";
              $result=$this->db->query($sql);
              if($result->num_rows()==0){
                  $data=array("status"=>"error","msg"=>"nodata");
                  return $data;
              }else{
                $exam_result=$result->result();
                $data=array("status"=>"success","msg"=>"success","data"=>$exam_result);
                return $data;
              }


            }


              //#################### GET  EXAM DETAILS ####################//
            function get_exam_details($student_id,$exam_id){
              $sql="SELECT class_id FROM edu_enrollment WHERE enroll_id='$student_id'";
              $res=$this->db->query($sql);
              $result=$res->result();
              foreach($result as $rows){   }
              $classid=$rows->class_id;
               $exam_sql="SELECT eed.subject_id,es.subject_name,DATE_FORMAT(eed.exam_date,'%d-%m-%Y')AS exam_date,eed.times FROM edu_exam_details AS eed LEFT JOIN edu_subject AS es ON es.subject_id=eed.subject_id WHERE eed.classmaster_id='$classid' AND eed.exam_id='$exam_id' AND eed.status='Active' ORDER BY exam_date ASC";
              $ex_result=$this->db->query($exam_sql);
              if($ex_result->num_rows()==0){
                  $data=array("status"=>"error","msg"=>"nodata");
                  return $data;
              }else{
                $exam_result=$ex_result->result();
                $data=array("status"=>"success","msg"=>"success","data"=>$exam_result);
                return $data;
              }

            }


                //#################### GET  ALL TEACHERS ####################//

            function get_all_teachers(){
              $sql="SELECT et.name,et.sex,et.age,et.class_teacher,c.class_name,s.sec_name,et.subject,esu.subject_name,et.teacher_id FROM edu_teachers
              AS et LEFT JOIN edu_classmaster AS cm ON et.class_teacher=cm.class_sec_id LEFT JOIN edu_class AS c ON cm.class=c.class_id LEFT JOIN edu_sections AS s ON  cm.section=s.sec_id LEFT JOIN edu_subject AS esu ON et.subject=esu.subject_id WHERE et.status='Active' order by et.teacher_id asc";
              $res=$this->db->query($sql);
              if($res->num_rows()==0){
                  $data=array("status"=>"error","msg"=>"nodata");
                  return $data;
              }else{
                $result=$res->result();
                $data=array("status"=>"success","msg"=>"success","data"=>$result);
                return $data;
              }

            }


            //#################### GET   TEACHER DETAILS  ####################//
            function get_teacher($teacher_id){
              $sql="SELECT et.name,et.sex,et.age,et.class_teacher,c.class_name,s.sec_name,et.subject,esu.subject_name,et.teacher_id,et.profile_pic FROM edu_teachers  AS et INNER JOIN edu_classmaster AS cm ON et.class_teacher=cm.class_sec_id INNER JOIN edu_class AS c ON cm.class=c.class_id
              INNER JOIN edu_sections AS s ON cm.section=s.sec_id INNER JOIN edu_subject AS esu ON et.subject=esu.subject_id WHERE et.status='Active' AND et.teacher_id='$teacher_id'";
              $res=$this->db->query($sql);
              if($res->num_rows()==0){
                  $data=array("status"=>"error","msg"=>"nodata");
                  return $data;
              }else{
                $result=$res->result();
                $data=array("status"=>"success","msg"=>"success","data"=>$result);
                return $data;
              }

            }


            //#################### GET   TEACHER CLASS DETAILS  ####################//
            function get_teacher_class_details($teacher_id){
                $year_id = $this->getYear();
                $term_id = $this->getTerm();
                
                $teacher_query = "SELECT t.teacher_id,t.name,t.sex,t.age,t.nationality,t.religion,t.community_class, t.community,t.address,t.email,t.phone,t.sec_email,t.sec_phone,t.profile_pic,t.update_at,t.subject,t.class_name AS class_taken,t.class_teacher FROM edu_teachers AS t WHERE t.teacher_id = '$teacher_id'";
				$teacher_res = $this->db->query($teacher_query);
				$teacher_profile = $teacher_res->result();
/*
                $get_teacher_details="SELECT et.name,et.sex,et.age,et.class_teacher,et.religion,et.community_class,et.address,et.email,et.sec_email,et.phone,et.sec_phone,et.qualification,c.class_name,s.sec_name,et.subject,esu.subject_name,et.teacher_id,et.profile_pic,et.class_name as class_taken,et.update_at,et.teacher_id
                FROM edu_teachers  AS et LEFT JOIN edu_classmaster AS cm ON et.class_teacher=cm.class_sec_id LEFT JOIN edu_class AS c ON cm.class=c.class_id
                LEFT JOIN edu_sections AS s ON cm.section=s.sec_id LEFT JOIN edu_subject AS esu ON et.subject=esu.subject_id
                WHERE et.status='Active' AND et.teacher_id='$teacher_id'";
                $res_detail=$this->db->query($get_teacher_details);
                $teacherProfile=$res_detail->result();
*/
                $class_sub_query = "SELECT
    								class_master_id,
    								teacher_id,
    								class_name,
    								sec_name,
    								subject_name
    							FROM
    								edu_teacher_handling_subject A,
    								edu_classmaster B,
    								edu_subject C,
    								edu_class D,
    								edu_sections E
    							WHERE
    								A.class_master_id = B.class_sec_id AND B.class = D.class_id AND B.section = E.sec_id AND A.subject_id = C.subject_id AND A.teacher_id = '$teacher_id' ORDER by class_master_id";
						$class_sub_res = $this->db->query($class_sub_query);

					    if($class_sub_res->num_rows()==0){
							 $class_sub_result = array("status" => "Class_section", "msg" => "Class and Section not found");

						}else{
							$class_sub_result = $class_sub_res->result();
						}

                        $sqldays = "SELECT A.day_id, B.list_day FROM `edu_timetable` A, `edu_days` B WHERE A.day_id = B.d_id AND A.teacher_id = '$teacher_id' AND A.year_id = '$year_id' AND A.term_id = '$term_id' GROUP BY day_id ORDER BY A.day_id";
						$day_res = $this->db->query($sqldays);

						if($day_res->num_rows()==0){
							 $day_result = array("status" => "error", "msg" => "TimeTable days not found");

						}else{
							 $day_result = array("status" => "success", "msg" => "TimeTable Days","data"=> $day_res->result());
						}
						
						
						$timetable_query = "SELECT
									tt.table_id,
									tt.class_id,
									c.class_name,
									ss.sec_name,
									tt.subject_id,
									tt.teacher_id,
									tt.day_id,
									tt.period,
									t.name,
									s.subject_name,
									tt.from_time,
									tt.to_time,
									tt.is_break
								FROM
									edu_timetable AS tt
								LEFT JOIN edu_subject AS s
								ON
									tt.subject_id = s.subject_id
								LEFT JOIN edu_teachers AS t
								ON
									tt.teacher_id = t.teacher_id
								INNER JOIN edu_classmaster AS cm
								ON
									tt.class_id = cm.class_sec_id
								INNER JOIN edu_class AS c
								ON
									cm.class = c.class_id
								INNER JOIN edu_sections AS ss
								ON
									cm.section = ss.sec_id
								WHERE
									tt.teacher_id = '$teacher_id' AND tt.year_id = '$year_id' AND tt.term_id = '$term_id'
								ORDER BY
									tt.day_id,
									tt.period";
						$timetable_res = $this->db->query($timetable_query);

						 if($timetable_res->num_rows()==0){
							 $timetable_result = array("status" => "timetable", "msg" => "TimeTable not found");

						}else{
							$timetable_result= $timetable_res->result();
						}


						$data = array("status" => "success", "msg" => "Class and Sections",'teacherProfile'=>$teacher_profile,"class_name"=>$class_sub_result,"timeTabledays"=>$day_result,"timeTable"=>$timetable_result);
						return $data;
                }





                    //#################### GET   LIST OF PARENTS   ####################//
              function get_list_of_parents($class_id,$section_id){
                $sql="SELECT class_sec_id FROM edu_classmaster WHERE class='$class_id' AND section='$section_id'";
                $res=$this->db->query($sql);
                $result=$res->result();
                foreach($result as $rows){   }
                 $classid=$rows->class_sec_id;
                $year_id=$this->getYear();
               //echo  $stu_list="SELECT eer.enroll_id AS student_id,eer.name,eer.admisn_no,ea.sex,ea.admisn_year,ep.name,ep.id,ea.parnt_guardn_id FROM edu_enrollment AS eer LEFT JOIN edu_admission AS ea ON ea.admisn_no=eer.admisn_no LEFT JOIN edu_parents AS ep ON  ea.parnt_guardn_id=ep.parent_id  WHERE eer.class_id='$classid' AND eer.admit_year='$year_id' AND eer.status='Active'";
               $stu_list="select ee.enroll_id as student_id,ee.admission_id as admisn_no,ea.name,ea.admisn_year,ea.sex,ea.parnt_guardn_id,ee.class_id,IFNULL(ep.name,'') as father_name,IFNULL(ep.name,'') as mother_name,IFNULL(ep.name,'') as guardn_name,IFNULL(ep.id,'') as parent_id from edu_enrollment as ee left join edu_admission as ea on ee.admission_id=ea.admission_id left join edu_parents as ep on ea.parnt_guardn_id=ep.id WHERE ee.class_id='$classid' AND ee.admit_year='$year_id' AND ee.status='Active'";
            
               $res_stu=$this->db->query($stu_list);
                if($res_stu->num_rows()==0){
                    $data=array("status"=>"error","msg"=>"nodata");
                    return $data;
                }else{
                  $result_stud=$res_stu->result();
                  $data=array("status"=>"success","msg"=>"success","data"=>$result_stud);
                  return $data;
                }
              }


              //#################### GET   PARENT DETAILS  ####################//
              function get_parent_details($admission_id){
                 $year_id=$this->getYear();
                 
                         $student_query = "SELECT * from edu_admission WHERE admission_id='$admission_id' AND status = 'Active'";
						$student_res = $this->db->query($student_query);
						$student_profile= $student_res->result();

							foreach($student_profile as $rows){ 
								$admit_id = $rows->admission_id;
								$parent_id = $rows->parnt_guardn_id;
							}
							
						$father_query = "SELECT * from edu_parents WHERE find_in_set ($admission_id,admission_id) AND relationship = 'Father' AND status = 'Active'";
						$father_res = $this->db->query($father_query);
						$father_profile = $father_res->result();

						foreach($father_profile as $rows){ 
								$admisson_id = $rows->admission_id;
								$relationship = $rows->relationship;
						}
                        
					    $fatherProfile  = array(
            "id" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->id,
            "name" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->name,
            "occupation" =>(!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->occupation,
            "income" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->income,
            "home_address" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->home_address ,
            "email" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->email,
            "mobile" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->mobile,
            "home_phone" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->home_phone,
            "office_phone" =>(!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->office_phone,
            "relationship" =>(!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->relationship,
            "user_pic" => (!isset($father_profile[0]) || is_null($father_profile[0])) ? '' : $father_profile[0]->user_pic
          );

       $mother_query = "SELECT * from edu_parents WHERE find_in_set ($admission_id,admission_id) AND relationship = 'Mother' AND status = 'Active'";
         $mother_res = $this->db->query($mother_query);
          $mother_profile = $mother_res->result();

          foreach($mother_profile as $rows){
              $admisson_id = $rows->admission_id;
          }

          $motherProfile  = array(
              "id" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->id,
            "name" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->name,
            "occupation" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->occupation,
            "income" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->income,
            "home_address" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->home_address,
            "email" => (!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->email,
            "mobile" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->mobile,
            "home_phone" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->home_phone,
            "office_phone" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->office_phone,
            "relationship" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->relationship,
            "user_pic" =>(!isset($mother_profile[0]) || is_null($mother_profile[0])) ? '' : $mother_profile[0]->user_pic
          );

          $guardian_query = "SELECT * from edu_parents WHERE find_in_set ($admission_id,admission_id) AND relationship = 'Guardian' AND status = 'Active'";
          $guardian_res = $this->db->query($guardian_query);
          $guardian_profile = $guardian_res->result();

          foreach($guardian_profile as $rows){
              $admisson_id = $rows->admission_id;
          }

          $guardianProfile  = array(
             "id" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->id,
            "name" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->name,
            "occupation" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->occupation,
            "income" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->income,
            "home_address" =>(!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->home_address ,
            "email" =>(!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->email,
            "mobile" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->mobile,
            "home_phone" =>(!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->home_phone,
            "office_phone" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->office_phone,
            "relationship" => (!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->relationship,
            "user_pic" =>(!isset($guardian_profile[0]) || is_null($guardian_profile[0])) ? '' : $guardian_profile[0]->user_pic 
          );
						
						$enroll_query = "SELECT A.enroll_id AS registered_id,A.admission_id,A.admisn_no AS admission_no,A.class_id,A.name,C.class_name,D.sec_name 
						from edu_enrollment A, edu_classmaster B, edu_class C, edu_sections D WHERE A.class_id = B.class_sec_id AND 
						B.class = C.class_id AND B.section = D.sec_id AND A.admit_year ='$year_id' AND A.admission_id = '$admit_id'";
						$enroll_res = $this->db->query($enroll_query);
						$stu_enroll_res= $enroll_res->result();
						
						$parentProfile = array("fatherProfile" =>$fatherProfile,"motherProfile" =>$motherProfile,"guardianProfile" =>$guardianProfile);
					
				  		$response = array("status" => "success", "msg" => "userdetailfound", "studentProfile" =>$student_profile,"parentProfile" =>$parentProfile,"registeredDetails"=>$stu_enroll_res, "year_id" => $year_id);
						return $response;
                 
                 
          

              }

                              //#################### GET   PARENT SUDENT LIST  ####################//
              function get_parent_student_list($parent_id){
                  $year_id=$this->getYear();
                  $father_query = "SELECT * from edu_parents WHERE id='$parent_id' AND status = 'Active'";
                  $father_res = $this->db->query($father_query);
                  $father_profile = $father_res->result();

                  foreach($father_profile as $rows){
                      $admisson_id = $rows->admission_id;
                  }

                   $enroll_query = "SELECT A.enroll_id,A.admission_id,A.admisn_no,A.class_id,A.name,C.class_name,D.sec_name,EA.sex,A.admit_year FROM edu_enrollment A, edu_classmaster B, edu_class C, edu_sections D ,edu_admission EA WHERE A.class_id = B.class_sec_id AND B.class = C.class_id AND B.section = D.sec_id AND EA.admission_id=A.admission_id AND A.admit_year ='$year_id' AND A.admission_id IN ($admisson_id)";
                  $enroll_res = $this->db->query($enroll_query);
                  $stu_enroll_res= $enroll_res->result();


                $response = array("status" => "success", "msg" => "studentdetailsfound","data"=>$stu_enroll_res);
                    return $response;

              }



                //#################### GET LIST OF TEACHER FOR A CLASS  ####################//
              function list_of_teachers_for_class($class_id,$section_id){
                $sql="SELECT class_sec_id FROM edu_classmaster WHERE class='$class_id' AND section='$section_id'";
                $res=$this->db->query($sql);
                $result=$res->result();
                foreach($result as $rows){   }
                $class_master_id=$rows->class_sec_id;
                $year_id=$this->getYear();
              //  $query="SELECT eths.teacher_id,et.name,et.sex,c.class_name,s.sec_name,esu.subject_name,et.class_teacher,et.subject FROM edu_teacher_handling_subject AS eths  LEFT JOIN edu_teachers AS et ON eths.teacher_id=et.teacher_id LEFT JOIN edu_classmaster AS cm ON et.class_teacher=cm.class_sec_id LEFT JOIN edu_class AS c ON cm.class=c.class_id  LEFT JOIN edu_sections AS s ON cm.section=s.sec_id LEFT JOIN edu_subject AS esu ON et.subject=esu.subject_id WHERE eths.class_master_id='$class_master_id' AND eths.status='Active' GROUP BY eths.teacher_id";
                  $query="SELECT eths.subject_id,eths.teacher_id,et.name,esu.subject_name FROM edu_teacher_handling_subject AS eths LEFT JOIN edu_teachers AS et ON eths.teacher_id=et.teacher_id LEFT JOIN edu_subject AS esu ON eths.subject_id=esu.subject_id
WHERE eths.class_master_id='$class_master_id' AND eths.status='Active' order by eths.teacher_id asc";
                $result_query=$this->db->query($query);
                if($result_query->num_rows()==0){
                    $data=array("status"=>"error","msg"=>"nodata");
                    return $data;
                }else{
                  $result=$result_query->result();
                  $data=array("status"=>"success","msg"=>"success","data"=>$result);
                  return $data;
                }
              }




                //#################### GET LIST OF EXAM FOR CLASS  ####################//
              function list_of_exams_class($class_id,$section_id){
                $sql="SELECT class_sec_id FROM edu_classmaster WHERE class='$class_id' AND section='$section_id'";
                $res=$this->db->query($sql);
                $result=$res->result();
                foreach($result as $rows){   }
                $classid=$rows->class_sec_id;
                $year_id=$this->getYear();
                  $query="SELECT ex.exam_id,ed.classmaster_id,ex.exam_name,ex.exam_flag AS is_internal_external,COALESCE(DATE_FORMAT(MIN(ed.exam_date), '%d/%b/%y'),'') AS Fromdate, COALESCE(DATE_FORMAT(MAX(ed.exam_date), '%d/%b/%y'),'') AS Todate,
			CASE WHEN ems.status='Publish' THEN 1 ELSE 0 END AS MarkStatus
			FROM edu_examination ex
			RIGHT JOIN edu_exam_details ed on ex.exam_id = ed.exam_id and ed.classmaster_id='$classid'
			LEFT JOIN edu_exam_marks_status ems ON ems.exam_id = ex.exam_id and ems.classmaster_id = ed.classmaster_id
			WHERE ex.exam_year ='$year_id' and ex.status = 'Active' and ed.classmaster_id='$classid'
			GROUP by ex.exam_name
			
			UNION ALL
			
			SELECT ex.exam_id,ed.classmaster_id,ex.exam_name,ex.exam_flag AS is_internal_external,COALESCE(DATE_FORMAT(MIN(ed.exam_date), '%d/%b/%y'),'') AS Fromdate,
			COALESCE(DATE_FORMAT(MAX(ed.exam_date), '%d/%b/%y'),'') AS Todate,
			CASE WHEN ems.status='Publish' THEN 1 ELSE 0 END AS MarkStatus
			FROM edu_examination ex
			LEFT JOIN edu_exam_details ed on ed.exam_id = ex.exam_id and ed.classmaster_id='$classid'
			LEFT JOIN edu_exam_marks_status ems ON ems.exam_id = ex.exam_id and ems.classmaster_id = ed.classmaster_id
			WHERE ex.exam_year ='$year_id' and ex.status = 'Active' and ex.exam_id NOT IN (SELECT DISTINCT exam_id FROM edu_exam_details where
			classmaster_id = '$classid')
			GROUP by ex.exam_name";
                $result_query=$this->db->query($query);
                if($result_query->num_rows()==0){
                    $data=array("status"=>"error","msg"=>"nodata");
                    return $data;
                }else{
                  $result=$result_query->result();
                  $data=array("status"=>"success","msg"=>"success","Exams"=>$result);
                  return $data;
                }
              }

              //#################### GET TimeTable FOR CLASS  ####################//
            function get_timetable_for_class($class_id,$section_id){
              $sql="SELECT class_sec_id FROM edu_classmaster WHERE class='$class_id' AND section='$section_id'";
              $res=$this->db->query($sql);
              $result=$res->result();
              foreach($result as $rows){   }
              $classid=$rows->class_sec_id;
              $year_id=$this->getYear();
              $term_id = $this->getTerm();
              $query="SELECT tt.table_id,tt.class_id,tt.subject_id,COALESCE(s.subject_name,' ') AS subject_name,tt.teacher_id,COALESCE(t.name,' ') AS teacher_name,tt.day_id,COALESCE(ed.list_day,' ') AS w_days,tt.period FROM edu_timetable AS tt LEFT JOIN edu_subject AS s ON tt.subject_id=s.subject_id LEFT JOIN edu_teachers AS t ON tt.teacher_id=t.teacher_id LEFT JOIN edu_days AS ed  ON tt.day_id=ed.d_id WHERE tt.class_id='$classid' AND tt.year_id='$year_id' AND tt.term_id='$term_id' ORDER BY tt.table_id ASC";
              $result_query=$this->db->query($query);
              if($result_query->num_rows()==0){
                  $data=array("status"=>"error","msg"=>"nodata");
                  return $data;
              }else{
                $result=$result_query->result();
                $data=array("status"=>"success","msg"=>"success","data"=>$result);
                return $data;
              }
            }



              //#################### GET FEES MASTER FOR CLASS  ####################//
            function get_fees_master_class($class_id,$section_id){
              $sql="SELECT class_sec_id FROM edu_classmaster WHERE class='$class_id' AND section='$section_id'";
              $res=$this->db->query($sql);
              $result=$res->result();
              foreach($result as $rows){   }
              $classid=$rows->class_sec_id;
              $year_id=$this->getYear();
            //   $query="SELECT efm.id,efm.term_id,DATE_FORMAT(efm.due_date_from,'%d-%m-%Y')AS due_date,DATE_FORMAT(efm.due_date_to,'%d-%m-%Y')AS to_date,
            //   DATE_FORMAT(eac.from_month,'%Y')AS from_year,DATE_FORMAT(eac.to_month,'%Y')AS to_year FROM edu_fees_master AS efm LEFT JOIN edu_academic_year AS eac ON efm.year_id=eac.year_id WHERE efm.class_master_id='$classid' AND efm.year_id='$year_id' AND efm.status='Active'";
              $query="SELECT efm.id AS fees_id,DATE_FORMAT(efm.due_date_from,'%d-%m-%Y')AS due_date_from,et.term_name,DATE_FORMAT(efm.due_date_to,'%d-%m-%Y')AS due_date_to,
DATE_FORMAT(eac.from_month,'%Y')AS from_year,DATE_FORMAT(eac.to_month,'%Y')AS to_year FROM edu_fees_master AS efm LEFT JOIN edu_academic_year AS eac ON efm.year_id=eac.year_id
LEFT JOIN edu_terms AS et ON  efm.term_id=et.term_id WHERE efm.class_master_id='$classid' AND efm.year_id='$year_id' AND efm.status='Active'";
              $result_query=$this->db->query($query);
              if($result_query->num_rows()==0){
                  $data=array("status"=>"error","msg"=>"nodata");
                  return $data;
              }else{
                $result=$result_query->result();
                $data=array("status"=>"success","msg"=>"success","data"=>$result);
                return $data;
              }
            }


              //#################### GET FEES DETAILS  ####################//
            function get_fees_details($fees_id){
              $query="SELECT efm.id,efm.term_id,DATE_FORMAT(efm.due_date_from,'%d-%m-%Y')AS due_date,DATE_FORMAT(efm.due_date_to,'%d-%m-%Y')AS to_date,eq.quota_name,ss.sec_name,c.class_name,efm.notes,eu.name AS created_by,DATE_FORMAT(eac.from_month,'%Y')AS from_year,DATE_FORMAT(eac.to_month,'%Y')AS to_year FROM edu_fees_master AS efm LEFT JOIN edu_academic_year AS eac ON efm.year_id=eac.year_id
              LEFT JOIN edu_quota AS eq ON eq.id=efm.quota_id INNER JOIN edu_classmaster AS cm ON efm.class_master_id=cm.class_sec_id INNER JOIN edu_class AS c ON cm.class=c.class_id INNER JOIN edu_sections AS ss ON cm.section=ss.sec_id INNER JOIN edu_users AS eu ON eu.user_id=efm.created_by WHERE efm.class_master_id=6 AND efm.id='$fees_id'";
              $result_query=$this->db->query($query);
              if($result_query->num_rows()==0){
                  $data=array("status"=>"error","msg"=>"nodata");
                  return $data;
              }else{
                $result=$result_query->result();
                $data=array("status"=>"success","msg"=>"success","data"=>$result);
                return $data;
              }
            }


            //#################### GET FEES DETAILS  ####################//
          function get_fees_status($class_id,$section_id,$fees_id){
            $sql="SELECT class_sec_id FROM edu_classmaster WHERE class='$class_id' AND section='$section_id'";
            $res=$this->db->query($sql);
            $result=$res->result();
            foreach($result as $rows){   }
            $classid=$rows->class_sec_id;
            $year_id=$this->getYear();
             $query="SELECT etfs.id,eer.name,etfs.student_id,etfs.status,etfs.paid_by,etfs.updated_at,eer.quota_id,eq.quota_name
            FROM edu_term_fees_status AS etfs LEFT JOIN edu_enrollment AS eer ON eer.enroll_id=etfs.student_id LEFT JOIN edu_quota AS eq ON eer.quota_id=eq.id  WHERE etfs.fees_id='$fees_id' AND etfs.class_master_id='$classid'";
            $result_query=$this->db->query($query);
            if($result_query->num_rows()==0){
                $data=array("status"=>"error","msg"=>"nodata");
                return $data;
            }else{
              $result=$result_query->result();
              $data=array("status"=>"success","msg"=>"success","data"=>$result);
              return $data;
            }
          }

            //#################### GET LIST EXAM FOR CLASS  ####################//
          function get_list_exam_class($class_id,$section_id){
            $sql="SELECT class_sec_id FROM edu_classmaster WHERE class='$class_id' AND section='$section_id'";
            $res=$this->db->query($sql);
            $result=$res->result();
            foreach($result as $rows){   }
            $classid=$rows->class_sec_id;
            $year_id=$this->getYear();
            $query="SELECT eed.exam_id,ee.exam_name,ee.exam_year,eac.from_month as Fromdate,eac.to_month as Todate,eed.classmaster_id,CASE WHEN ems.status='Publish' THEN 0 ELSE 0 END AS MarkStatus FROM edu_exam_details AS eed LEFT JOIN edu_examination AS ee ON ee.exam_id=eed.exam_id LEFT JOIN edu_exam_marks_status AS ems ON ems.exam_id=eed.exam_id LEFT JOIN edu_academic_year AS eac ON ee.exam_year=eac.year_id WHERE eed.classmaster_id='$classid' GROUP BY ee.exam_id";
                $result_query=$this->db->query($query);
                if($result_query->num_rows()==0){
                    $data=array("status"=>"error","msg"=>"nodata");
                    return $data;
                }else{
                  $result=$result_query->result();
                  $data=array("status"=>"success","msg"=>"success","Exams"=>$result);
                  return $data;
                }
          }


          //#################### GET  EXAM FOR CLASS  ####################//
          function get_exam_details_class($exam_id,$class_id){
            $query="SELECT eed.exam_id,eed.subject_id,es.subject_name,DATE_FORMAT(eed.exam_date,'%d-%m-%Y')AS exam_date,eed.times,eed.teacher_id,et.name
            FROM edu_exam_details AS eed LEFT JOIN edu_teachers AS et ON et.teacher_id=eed.teacher_id LEFT JOIN edu_subject AS es ON es.subject_id=eed.subject_id WHERE eed.classmaster_id='$class_id' AND eed.exam_id='$exam_id' AND eed.status='Active'";
            $result_query=$this->db->query($query);
            if($result_query->num_rows()==0){
                $data=array("status"=>"error","msg"=>"nodata");
                return $data;
            }else{
              $result=$result_query->result();
              $data=array("status"=>"success","msg"=>"success","data"=>$result);
              return $data;
            }
          }


  //#################### GET  EXAM MARKS FOR CLASS  ####################//
          function get_exam_marks_class($exam_id,$class_id,$section_id){
            $sql="SELECT class_sec_id FROM edu_classmaster WHERE class='$class_id' AND section='$section_id'";
            $res=$this->db->query($sql);
            $result=$res->result();
            foreach($result as $rows){   }
            $class_mas_id=$rows->class_sec_id;
            $year_id=$this->getYear();
       $query="SELECT en.enroll_id,en.name,en.admisn_no,en.class_id,m.subject_id,m.classmaster_id,m.internal_mark,m.internal_grade,m.external_mark,m.external_grade,m.total_marks,m.total_grade FROM edu_enrollment AS en,edu_exam_marks AS m WHERE en.class_id='$class_mas_id' AND en.enroll_id=m.stu_id AND m.exam_id='$exam_id'";
            $result_query=$this->db->query($query);
            if($result_query->num_rows()==0){
                $data=array("status"=>"error","msg"=>"nodata");
                return $data;
            }else{
              $result=$result_query->result();
              $data=array("status"=>"success","msg"=>"success","data"=>$result);
              return $data;
            }
          }


          // Teachers OD form view
          function get_teachers_od_view($user_id){
            $year_id=$this->getYear();
            $query="SELECT eod.id,eod.od_for,eu.user_master_id,et.name,eod.from_date,eod.to_date,eod.notes,eod.status FROM edu_on_duty AS eod
            LEFT JOIN edu_users AS eu ON eu.user_id=eod.user_id LEFT JOIN edu_teachers AS et ON et.teacher_id=eu.user_master_id WHERE eod.user_type='2' AND eod.year_id='$year_id' ORDER BY eod.id DESC";
            $result_query=$this->db->query($query);
            if($result_query->num_rows()==0){
                $data=array("status"=>"error","msg"=>"nodata");
                return $data;
            }else{
              $result=$result_query->result();
              $data=array("status"=>"success","msg"=>"odviewfound","ondutyDetails"=>$result);
              return $data;
            }
          }


          // Students OD FORM view
          function get_students_od_view($user_id){
            $year_id=$this->getYear();
            $query="SELECT du.id,du.od_for,du.notes,du.from_date,du.to_date,du.status,u.user_id,u.name,u.user_master_id,c.class_name,s.sec_name FROM edu_on_duty AS du,edu_enrollment AS en,edu_classmaster AS cm,edu_class AS c,edu_sections AS s,edu_users AS u WHERE du.user_type=3 AND du.user_id=u.user_id AND u.user_master_id=en.admission_id AND u.name=en.name AND cm.class_sec_id=en.class_id AND cm.class=c.class_id AND cm.section=s.sec_id AND du.year_id='$year_id' ORDER BY du.id DESC";
            $result_query=$this->db->query($query);
            if($result_query->num_rows()==0){
                $data=array("status"=>"error","msg"=>"nodata");
                return $data;
            }else{
              $result=$result_query->result();
              $data=array("status"=>"success","msg"=>"odviewfound","ondutyDetails"=>$result);
              return $data;
            }
          }


// Update Teacher OD
          function update_teachers_od($od_id,$status){
            $year_id=$this->getYear();
						
           	$update_sql = "UPDATE edu_on_duty SET status = '$status' WHERE id='$od_id'";
			$update_result = $this->db->query($update_sql);

			$response = array("status" => "sucess", "msg" => "Onduty Updated");
			return $response;
          }


            // GET Teacher Leaves
          function get_teachers_leaves($user_id){
            $year_id=$this->getYear();
            $query="SELECT eul.leave_id,eu.user_id,et.name,eulm.leave_title,eulm.leave_type,DATE_FORMAT(eul.from_leave_date,'%d-%m-%Y') AS from_leave_date,DATE_FORMAT(eul.to_leave_date,'%d-%m-%Y')AS to_leave_date,eul.leave_description,eul.status,eul.frm_time,eul.to_time FROM edu_user_leave  AS eul LEFT JOIN edu_users AS eu ON eu.user_id=eul.user_id LEFT JOIN edu_teachers AS et ON et.teacher_id=eu.user_master_id LEFT JOIN edu_user_leave_master AS eulm ON eulm.id=eul.leave_master_id WHERE eul.user_type='2' AND eul.year_id='$year_id' ORDER BY eul.leave_id DESC";
            $result_query=$this->db->query($query);
            if($result_query->num_rows()==0){
                $data=array("status"=>"error","msg"=>"nodata");
                return $data;
            }else{
              $result=$result_query->result();
              $data=array("status"=>"success","msg"=>"leavesfound","leaveDetails"=>$result);
              return $data;
            }
          }


  // Update Teacher Leaves
          function update_teachers_leaves($leave_id,$status){
            $year_id=$this->getYear();
						
           	$update_sql = "UPDATE edu_user_leave SET status = '$status' WHERE leave_id='$leave_id'";
			$update_result = $this->db->query($update_sql);

			$response = array("status" => "sucess", "msg" => "Leave Updated");
			return $response;
          }



          function get_all_circular_view($user_id){
          $query="SELECT ecm.id,ecm.circular_title,ecm.circular_description,ec.circular_type,ecm.status,ecm.created_at as circular_date FROM edu_circular_master AS ecm,edu_circular as ec GROUP by id ORDER BY ecm.id DESC";
            $result_query=$this->db->query($query);
            if($result_query->num_rows()==0){
                $data=array("status"=>"error","msg"=>"nodata");
                return $data;
            }else{
              $result=$result_query->result();
              $data=array("status"=>"success","msg"=>"circularfound","circularDetails"=>$result);
              return $data;
            }
          }
		  
/*		  
	//#################### Timetable days ####################//

	public function dispTimetable_days($class_id)
	{
	    $year_id = $this->getYear();
		$term_id = $this->getTerm();
		
		$sqldays = "SELECT A.day, B.list_day FROM `edu_timetable` A, `edu_days` B WHERE A.day = B.d_id AND A.class_id = '$class_id' AND A.year_id = '$year_id' AND A.term_id = '$term_id' GROUP BY DAY ORDER BY A.day";
		
			$day_res = $this->db->query($sqldays);
			$day_result= $day_res->result();
			$day_count = $day_res->num_rows();

		if($day_count>0)
		{
			 $response = array("status" => "success", "msg" => "Timetable Days", "timetableDays"=>$day_result);
		} else {
			$response = array("status" => "error", "msg" => "No Records Found");
		}
		return $response;
	}

	//#################### Timetable days End ####################//
	
	//#################### Timetable ####################//

	public function dispTimetable($class_id,$day_id)
	{
	    $year_id = $this->getYear();
		$term_id = $this->getTerm();
		
		$sqltimetable = "SELECT A.class_id, A.day, A.period, B.subject_name, C.name, A.from_time, A.to_time, A.is_break FROM edu_timetable AS A LEFT JOIN edu_teachers AS C ON A.teacher_id = C.teacher_id LEFT JOIN edu_subject AS B ON A.subject_id = B.subject_id WHERE A.year_id = '$year_id' AND A.term_id = '$term_id' AND A.class_id = '$class_id' AND A.DAY = '$day_id' ORDER BY A.period";
		
			$timetable_res = $this->db->query($sqltimetable);
			$timetable_result= $timetable_res->result();
			$timetable_count = $timetable_res->num_rows();

		if($timetable_count>0)
		{
			 $response = array("status" => "success", "msg" => "Timetable Days", "timeTable"=>$timetable_result);
		} else {
			$response = array("status" => "error", "msg" => "No Records Found");
		}
		return $response;
	}

	//#################### Timetable End ####################//	  
*/
	
		//#################### Timetable days ####################//

	public function listClasssection($user_id)
	{
	    $year_id = $this->getYear();
		
		$sqlcs = "SELECT
					B.class_sec_id,
					CONCAT(C.class_name, ' ', D.sec_name) AS class_section
				FROM
					edu_classmaster B,
					edu_class C,
					edu_sections D
				WHERE
					B.class = C.class_id AND B.section = D.sec_id
				ORDER BY
					B.class_sec_id";
			$cs_res = $this->db->query($sqlcs);
			$cs_result= $cs_res->result();
			$cs_count = $cs_res->num_rows();

		if($cs_count>0)
		{
			 $response = array("status" => "success", "msg" => "Class and Sections", "listClasssection"=>$cs_result);
		} else {
			$response = array("status" => "error", "msg" => "No Records Found");
		}
		return $response;
	}

	//#################### Timetable days End ####################//
	
	//#################### Timetable Review ####################//

	public function addTimetableremarks($review_id,$remarks)
	{
	    	$year_id = $this->getYear();
		
			$review_update_query = "UPDATE edu_timetable_review SET remarks ='$remarks' WHERE timetable_id ='$review_id'";
			$review_update_res = $this->db->query($review_update_query);

			if($review_update_res) {
			    $response = array("status" => "success", "msg" => "Review Updated");
			} else {
			    $response = array("status" => "error");
			}
			
		return $response;
	}
	//#################### Timetable Review End ####################//	 
	

	
	//#################### Group Master Add ####################//

	public function addGroupmaster($user_id,$group_title,$group_lead,$status)
	{
	    	$year_id = $this->getYear();
		
			$sqlgroup = "SELECT * FROM edu_grouping_master WHERE group_title = '$group_title'";
			$group_res = $this->db->query($sqlgroup);
			$group_count = $group_res->num_rows();

			if($group_count>0)
			{
				 $response = array("status" => "error", "msg" => "Group Already Exist");
			} else {
				$sql="INSERT INTO edu_grouping_master(group_title,group_lead_id,year_id,status,created_by,created_at) VALUES ('$group_title','$group_lead','$year_id','$status','$user_id',NOW())";
             $resultset=$this->db->query($sql);
			 $response = array("status" => "success", "msg" => "Group Master Added");
			}
			return $response;

	}
	//#################### Group Master End ####################//	


	//#################### Group Master List ####################//

	public function listGroupmaster($user_id)
	{
	    	$year_id = $this->getYear();
		
			$sqlgroup = "SELECT A.*,B.name AS lead_name FROM edu_grouping_master A, edu_users B WHERE A.group_lead_id = B.user_id AND A.year_id='$year_id' ORDER BY id";
			$group_res = $this->db->query($sqlgroup);
			$group_result= $group_res->result();
			$group_count = $group_res->num_rows();
		
			if($group_count>0)
			{
				$response = array("status" => "success", "msg" => "Group List", "groupList"=>$group_result);
			} else {
				$response = array("status" => "error", "msg" => "No Records Found");
			}
			return $response;

	}
	//#################### Group Master End ####################//
	
	//#################### Group Master List ####################//

	public function viewGroupmaster($group_id)
	{
	    	$year_id = $this->getYear();
		
			$sqlgroup = "SELECT * FROM edu_grouping_master WHERE id='$group_id'";
			$group_res = $this->db->query($sqlgroup);
			$group_result= $group_res->result();
			$group_count = $group_res->num_rows();
		
			if($group_count>0)
			{
				$response = array("status" => "success", "msg" => "Group List View", "groupView"=>$group_result);
			} else {
				$response = array("status" => "error", "msg" => "No Records Found");
			}
			return $response;

	}
	//#################### Group Master End ####################//
	
	//#################### Group Master Update ####################//

	public function updateGroupmaster($user_id,$group_id,$group_title,$group_lead,$status)
	{
	    	$year_id = $this->getYear();
		
			$sqlgroup = "SELECT * FROM edu_grouping_master WHERE group_title='$group_title' and group_lead_id='$group_lead' and status='$status'";
			$group_res = $this->db->query($sqlgroup);
			$group_count = $group_res->num_rows();

			if($group_count>0)
			{
				 $response = array("status" => "error", "msg" => "Group Already Exist");
			} else {
				$sql="UPDATE  edu_grouping_master SET group_title='$group_title',group_lead_id='$group_lead',status='$status',updated_by='$user_id',updated_at=NOW() where id='$group_id'";
             $resultset=$this->db->query($sql);
			 $response = array("status" => "success", "msg" => "Group Master Updated");
			}
			return $response;

	}
	//#################### Group Master End ####################//	
	
	//#################### All Techers with User Id ####################//

	public function getAllteachersuserid($user_id)
	{
	    	$year_id = $this->getYear();
		
			$sqlteacher = "SELECT user_id,name FROM `edu_users` WHERE user_type ='2' AND status = 'Active' ORDER BY `edu_users`.`name` ASC ";
			$teacher_res = $this->db->query($sqlteacher);
			$teacher_result= $teacher_res->result();
			$teacher_count = $teacher_res->num_rows();

			if($teacher_count>0)
			{
				 $response = array("status" => "success", "msg" => "Teacher Details", "teacherList"=>$teacher_result);
			} else {
				$response = array("status" => "error", "msg" => "No Records Found");
			}
			return $response;

	}
	//####################  All Techers with User Id End ####################//	
	
	//#################### All Staff with User Id ####################//

	public function getAllstaffsuserid($user_id)
	{
	    	$year_id = $this->getYear();
		
			$sqlstaff = "SELECT user_id,name FROM `edu_users` WHERE user_type ='5' AND status = 'Active' ORDER BY `edu_users`.`name` ASC ";
			$staff_res = $this->db->query($sqlstaff);
			$staff_result= $staff_res->result();
			$staff_count = $staff_res->num_rows();

			if($staff_count>0)
			{
				 $response = array("status" => "success", "msg" => "Teacher Details", "staffList"=>$staff_result);
			} else {
				$response = array("status" => "error", "msg" => "No Records Found");
			}
			return $response;

	}
	//####################  All Staff with User Id End ####################//	
	
	//#################### All Students with User Id ####################//

	public function getAllstudentuserid($class_id)
	{
	    	$year_id = $this->getYear();
		
			$sqlstud = "SELECT eu.user_id,ee.name,ee.enroll_id FROM edu_users AS eu LEFT JOIN edu_admission AS ea ON eu.user_master_id=ea.admission_id AND eu.user_type='3' LEFT JOIN edu_enrollment AS ee ON ee.admission_id=ea.admission_id WHERE  ee.class_id='$class_id' AND ee.admit_year='$year_id' AND ee.status='Active'";
			$stud_res = $this->db->query($sqlstud);
			$stud_result= $stud_res->result();
			$stud_count = $stud_res->num_rows();

			if($stud_count>0)
			{
				 $response = array("status" => "success", "msg" => "Teacher Details", "studentList"=>$stud_result);
			} else {
				$response = array("status" => "error", "msg" => "No Records Found");
			}
			return $response;

	}
	//####################  All Students with User Id End ####################//	
	
	
	//#################### All Staff Details for GN ####################//

	public function gnStafflist($group_id,$group_user_type)
	{
	    	$year_id = $this->getYear();
		
			$sqlstaff = "SELECT ex.user_id,ex.name,
						CASE WHEN ems.group_member_id !='' THEN 1 ELSE 0 END AS Status
						FROM edu_users ex
						LEFT JOIN edu_grouping_members ems ON ems.group_member_id = ex.user_id
						WHERE ex.user_type ='$group_user_type' and ems.group_title_id = '$group_id'
						GROUP by ex.user_id
						
						UNION ALL
						
						SELECT ex.user_id,ex.name,
						CASE WHEN ems.group_member_id !='' THEN 1 ELSE 0 END AS Status
						FROM edu_users ex
						LEFT JOIN edu_grouping_members ems ON ems.group_member_id = ex.user_id and ems.group_title_id = '$group_id'
						WHERE ex.user_type ='$group_user_type' and ex.user_id not in (SELECT ex.user_id
						FROM edu_users ex
						LEFT JOIN edu_grouping_members ems ON ems.group_member_id = ex.user_id
						WHERE ex.user_type ='$group_user_type' and ems.group_title_id = '$group_id')
						GROUP by ex.user_id";
			$staff_res = $this->db->query($sqlstaff);
			$staff_result= $staff_res->result();
			$staff_count = $staff_res->num_rows();

			if($staff_count>0)
			{
				$response = array("status" => "sucess", "msg" => "Records Found", "gnMemberlist"=>$staff_result);
			} else {
				$response = array("status" => "error", "msg" => "No Records Found");
			}
			return $response;

	}
	//####################  All Staff Details for GN End ####################//
		
	
	//#################### All Student Details for GN ####################//

	public function gnStudentlist($group_id,$group_user_type,$class_id)
	{
	    	$year_id = $this->getYear();
			$sqlstud = "SELECT ex.user_id, ex.name, 
							CASE WHEN ems.group_member_id != '' THEN 1 ELSE 0 END AS Status 
							FROM edu_users ex 
							LEFT JOIN edu_grouping_members ems ON ems.group_member_id = ex.user_id 
							LEFT JOIN edu_enrollment AS en ON en.admission_id = ex.user_master_id 
							WHERE ex.user_type = '$group_user_type' AND ems.group_title_id = '$group_id' AND en.admit_year = '$year_id' AND en.class_id='$class_id' GROUP BY ex.user_id 
							
							UNION ALL 
							
							SELECT ex.user_id, ex.name, 
							CASE WHEN ems.group_member_id != '' THEN 1 ELSE 0 END AS Status 
							FROM edu_users ex 
							LEFT JOIN edu_grouping_members ems ON ems.group_member_id = ex.user_id AND ems.group_title_id = '$group_id' 
							LEFT JOIN edu_enrollment AS en ON en.admission_id = ex.user_master_id 
							WHERE ex.user_type = '$group_user_type' AND en.admit_year = '$year_id' AND en.class_id='$class_id' AND ex.user_id 
							NOT IN( SELECT ex.user_id FROM edu_users ex LEFT JOIN edu_grouping_members ems ON ems.group_member_id = ex.user_id LEFT JOIN edu_enrollment AS en ON en.admission_id = ex.user_master_id WHERE ex.user_type = '$group_user_type' AND ems.group_title_id = '$group_id' AND en.admit_year = '$year_id' AND en.class_id='$class_id' ) 
							GROUP BY ex.user_id";
			$stud_res = $this->db->query($sqlstud);
			$stud_result= $stud_res->result();
			$stud_count = $stud_res->num_rows();

			if($stud_count>0)
			{
				$response = array("status" => "sucess", "msg" => "Records Found", "gnMemberlist"=>$stud_result);
			} else {
				$response = array("status" => "error", "msg" => "No Records Found");
			}
			return $response;

	}
	//####################  All Student Details for GN End ####################//
	
	
	//#################### Group Notification Mail ####################//

	public function gn_send_mail($group_id,$notes,$user_id)
	{
	    	$year_id = $this->getYear();

			$sqlgroup = "SELECT * FROM `edu_grouping_members` WHERE group_title_id ='$group_id'";
			$group_res = $this->db->query($sqlgroup);
			$group_result= $group_res->result();
			$group_count = $group_res->num_rows();
			if($group_count>0)
			{
				foreach ($group_res->result() as $rows)
				{
			    	 $member_type = $rows->member_type;
					 $user_id = $rows->group_member_id;
					
					if ($member_type =='2' || $member_type == '5'){
						$sqlgroup = "SELECT * FROM edu_users AS A LEFT JOIN edu_teachers AS C ON A.teacher_id = C.teacher_id WHERE A.user_id = '$user_id'";
						$group_res = $this->db->query($sqlgroup);
						$group_result= $group_res->result();
						$group_count = $group_res->num_rows();
							if($group_count>0) {
								foreach ($group_res->result() as $rows)
								{
									$semail = $rows->email;
									$subject = 'Group Notification';
									$this->sendMail($semail,$subject,$notes);
								}
							}
					}
					
					if ($member_type =='3'){
						 $sqlgroup = "SELECT egm.group_member_id,ep.email,ep.mobile FROM edu_grouping_members AS egm LEFT JOIN edu_users AS eu ON eu.user_id=egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id=eu.user_master_id LEFT JOIN edu_parents AS ep ON FIND_IN_SET(ea.admission_id,ep.admission_id) WHERE egm.group_title_id='$group_id' and egm.member_type='$member_type' and ep.mobile <>''";
						$group_res = $this->db->query($sqlgroup);
						$group_result= $group_res->result();
						$group_count = $group_res->num_rows();
							if($group_count>0) {
								foreach ($group_res->result() as $rows)
								{
									$semail = $rows->email;
									$subject = 'Group Notification';
									$this->sendMail($semail,$subject,$notes);
								}
							}
					}
					
				}
			}

	}
	//####################  Group Notification Mail End ####################//	
	
	//#################### Group Notification SMS ####################//

	public function gn_send_message($group_id,$notes,$user_id)
	{
	    	$year_id = $this->getYear();

			$sqlgroup = "SELECT * FROM `edu_grouping_members` WHERE group_title_id ='$group_id'";
			$group_res = $this->db->query($sqlgroup);
			$group_result= $group_res->result();
			$group_count = $group_res->num_rows();
			if($group_count>0)
			{
				foreach ($group_res->result() as $rows)
				{
			    	$member_type = $rows->member_type;
					$user_id = $rows->group_member_id;
					
					if ($member_type =='2' || $member_type = '5'){
						$sqlgroup = "SELECT * FROM edu_users AS A LEFT JOIN edu_teachers AS C ON A.teacher_id = C.teacher_id WHERE A.user_id = '$user_id'";
						$group_res = $this->db->query($sqlgroup);
						$group_result= $group_res->result();
						$group_count = $group_res->num_rows();
							if($group_count>0) {
								foreach ($group_res->result() as $rows)
								{
									$phone = $rows->phone;
									$this->sendSMS($phone,$notes);
								}
							}
					}
					
					if ($member_type =='3'){
						$sqlgroup = "SELECT egm.group_member_id,ep.email,ep.mobile FROM edu_grouping_members AS egm LEFT JOIN edu_users AS eu ON eu.user_id=egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id=eu.user_master_id LEFT JOIN edu_parents AS ep ON FIND_IN_SET(ea.admission_id,ep.admission_id) WHERE egm.group_title_id='$group_id' and egm.member_type='$member_type' and ep.mobile <>''";
						$group_res = $this->db->query($sqlgroup);
						$group_result= $group_res->result();
						$group_count = $group_res->num_rows();
							if($group_count>0) {
								foreach ($group_res->result() as $rows)
								{
									$phone = $rows->mobile;
									$this->sendSMS($phone,$notes);
								}
							}
					}
				}

			}

	}
	//####################  Group Notification SMS End ####################//
	
	
		
	//#################### Group Notification  ####################//

	public function gn_send_notification($group_id,$notes,$user_id)
	{
	    	$year_id = $this->getYear();

			$sqlgroup = "SELECT * FROM `edu_grouping_members` WHERE group_title_id ='$group_id'";
			$group_res = $this->db->query($sqlgroup);
			$group_result= $group_res->result();
			$group_count = $group_res->num_rows();
			if($group_count>0)
			{
				foreach ($group_res->result() as $rows)
				{
			    	$member_type = $rows->member_type;
					$user_id = $rows->group_member_id;
					
					if ($member_type =='2' || $member_type = '5'){
						$sqlgroup = "SELECT * FROM edu_notification WHERE user_id = '$user_id'";
						$group_res = $this->db->query($sqlgroup);
						$group_result= $group_res->result();
						$group_count = $group_res->num_rows();
							if($group_count>0) {
								foreach ($group_res->result() as $rows)
								{
									$gcm_key = $rows->gcm_key;
									$mobile_type = $rows->mobile_type;
									$subject = 'Group Notification';
									$this->sendNotification($gcm_key,$subject,$notes,$mobile_type);
								}
							}
					}
					
					if ($member_type =='3'){
						$sqlgroup = "SELECT egm.group_member_id,ep.email,ep.mobile,ep.id FROM edu_grouping_members AS egm
           LEFT JOIN edu_users AS eu ON eu.user_id=egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id=eu.user_master_id
           LEFT JOIN edu_parents AS ep ON FIND_IN_SET(ea.admission_id, ep.admission_id) LEFT JOIN edu_notification AS en ON en.user_id=eu.user_id
           WHERE  egm.group_title_id='$group_id' AND ep.primary_flag='yes'";
						$group_res = $this->db->query($sqlgroup);
						$group_result= $group_res->result();
						$group_count = $group_res->num_rows();
							if($group_count>0) {
								foreach ($group_res->result() as $rows)
								{
									$parent_id=$result->id;
									
									$sql="SELECT eu.user_id,en.gcm_key,en.mobile_type FROM edu_users as eu left join edu_notification as en on eu.user_id=en.user_id WHERE user_type='4' and user_master_id='$parent_id'";
           							$sgsm=$this->db->query($sql);
           							$res=$sgsm->result();
           							foreach($res as $row){
           									$gcm_key=$row->gcm_key;
											$mobile_type=$row->mobile_type;
											$subject = 'Group Notification';
											$this->sendNotification($gcm_key,$subject,$notes,$mobile_type);
		  							}
								}
							}
					}
				}

			}

	}
	//####################  Group Notification End ####################//


//####################  Group Members Add ####################//
	public function addgnMembers($user_id,$group_id,$group_member_id,$group_user_type,$class_sec_id,$status)
	{
	
		if ($group_user_type=='3'|| $group_user_type =='4') {
			$gnMembsql = "SELECT * FROM edu_grouping_members WHERE member_type = '$group_user_type' AND group_title_id ='$group_id' AND class_sec_id = '$class_sec_id'";
		} else {
			$gnMembsql = "SELECT * FROM edu_grouping_members WHERE member_type = '$group_user_type' AND group_title_id ='$group_id'";
		}
			$gnMembres = $this->db->query($gnMembsql);
			$gnMembresult= $gnMembres->result();
			$gnMembcount = $gnMembres->num_rows();
		
			if($gnMembcount>0)
			{
				if ($group_user_type=='3'|| $group_user_type =='4') {
					$squery = "DELETE FROM `edu_grouping_members` WHERE member_type = '$group_user_type' AND group_title_id ='$group_id' AND class_sec_id = '$class_sec_id'";
				} else {
					$squery = "DELETE FROM `edu_grouping_members` WHERE member_type = '$group_user_type' AND group_title_id ='$group_id'";
				}
				$resultset=$this->db->query($squery);
			}
		
			$smember_id = explode(",", $group_member_id);
			
			foreach($smember_id as $member_id)
			{
				
				$sql = "INSERT INTO edu_grouping_members(group_title_id,group_member_id,member_type,class_sec_id,status,created_by,created_at) VALUES ('$group_id','$member_id','$group_user_type','$class_sec_id','$status','$user_id',NOW())";
				$resultset=$this->db->query($sql);
			}				
			
			$response = array("status" => "success", "msg" => "Group Members Added");
			return $response;
	}
	//#################### Group Members End  ####################//
	
// 	//####################  Group Members Add ####################//
// 	public function addgnMembers($user_id,$group_id,$group_member_id,$group_user_type,$status)
// 	{
// 			$gnMembsql = "SELECT * FROM edu_grouping_members WHERE member_type = '$group_user_type' AND group_title_id ='$group_id'";
// 			$gnMembres = $this->db->query($gnMembsql);
// 			$gnMembresult= $gnMembres->result();
// 			$gnMembcount = $gnMembres->num_rows();
		
// 			if($gnMembcount>0)
// 			{
// 				$squery = "DELETE FROM `edu_grouping_members` WHERE member_type = '$group_user_type' AND group_title_id ='$group_id'";
// 				$resultset=$this->db->query($squery);
// 			}
		
// 			$smember_id = explode(",", $group_member_id);
// 				foreach($smember_id as $member_id)
// 				{
					
// 					$sql = "INSERT INTO edu_grouping_members(group_title_id,group_member_id,member_type,status,created_by,created_at) VALUES ('$group_id','$member_id','$group_user_type','$status','$user_id',NOW())";
// 					$resultset=$this->db->query($sql);
// 				}				
			
// 			$response = array("status" => "success", "msg" => "Group Members Added");
// 			return $response;
// 	}
// 	//#################### Group Members End  ####################//
	
	
	//####################  List Group Members ####################//
	public function listgnMembers($group_id)
	{
	        $year_id = $this->getYear();
	        
			$sqlstaff = "SELECT A.id,B.name,C.user_type_name FROM `edu_grouping_members` A, edu_users B, edu_role C WHERE A.group_member_id = B.user_id AND A.member_type = C.role_id AND `group_title_id` = '$group_id' ORDER by A.member_type";
			$staff_res = $this->db->query($sqlstaff);
			$staff_result= $staff_res->result();
			$staff_count = $staff_res->num_rows();

			if($staff_count>0)
			{
				 $response = array("status" => "success", "msg" => "Group Member Details", "memberList"=>$staff_result);
			} else {
				$response = array("status" => "error", "msg" => "No Records Found");
			}
			return $response;
	}
	//#################### List Group Members End  ####################//


	//####################  Group Notification History ####################//
	public function save_group_history($group_id,$notification_type,$snotes,$user_id){
            $query="INSERT INTO  edu_grouping_history (group_title_id,notes,notification_type,status,created_at,created_by) VALUES('$group_id','$snotes','$notification_type','Active',NOW(),'$user_id')";
            $res=$this->db->query($query);
            if($res){
              $response = array("status" => "sucess", "msg" => "Group Notification Send Sucessfully");
            }else{
              	$response = array("status" => "error", "msg" => "Sorry! Not Added");
            }
			return $response;
          }
	//####################  Group Notification History End ####################//

		
	//####################  Circular Master Add ####################//
	public function addCircular($user_id,$circular_title,$circular_description,$status){
			
			$year_id = $this->getYear();
		
             $query="INSERT INTO  edu_circular_master (academic_year_id,circular_title,circular_description,status,created_at,created_by) VALUES('$year_id','$circular_title','$circular_description','$status',NOW(),'$user_id')";
            $res=$this->db->query($query);
            if($res){
              	$response = array("status" => "sucess", "msg" => "Circular Master Added");
            }else{
              	$response = array("status" => "error", "msg" => "Sorry! Not Added");
            }
			return $response;
          }

	//#################### Circular Master End ####################//
	
	//#################### Circular Master List ####################//

	public function listCircular($user_id)
	{
	    	$year_id = $this->getYear();
		
			$sqlcircular = "SELECT * FROM edu_circular_master ORDER BY id";
			$circular_res = $this->db->query($sqlcircular);
			$circular_result= $circular_res->result();
			$circular_count = $circular_res->num_rows();
		
			if($circular_count>0)
			{
				$response = array("status" => "success", "msg" => "Circular List", "circularList"=>$circular_result);
			} else {
				$response = array("status" => "error", "msg" => "No Records Found");
			}
			return $response;

	}
	//#################### Circular Master End ####################//
	
	//#################### Circular Master View ####################//

	public function viewCircular($circular_id)
	{
	    	$year_id = $this->getYear();
		
			$sqlcircular = "SELECT * FROM edu_circular_master WHERE id='$circular_id'";
			$circular_res = $this->db->query($sqlcircular);
			$circular_result= $circular_res->result();
			$circular_count = $circular_res->num_rows();
		
			if($circular_count>0)
			{
				$response = array("status" => "success", "msg" => "Circular View", "circularView"=>$circular_result);
			} else {
				$response = array("status" => "error", "msg" => "No Records Found");
			}
			return $response;

	}
	//#################### Circular Master End ####################//
	
	//#################### Circular Master Update ####################//

	public function updateCircular($user_id,$circular_id,$circular_title,$circular_description,$status)
	{
	    	$year_id = $this->getYear();
			
			$sql="UPDATE  edu_circular_master SET circular_title ='$circular_title',circular_description ='$circular_description',status='$status',updated_by='$user_id',updated_at=NOW() where id='$circular_id'";

             $resultset=$this->db->query($sql);
			 $response = array("status" => "success", "msg" => "Circular Master Updated");

			return $response;

	}
	//#################### Circular Master End ####################//	
	
	
	//#################### Circular Master Update ####################//

	public function updateCirculardoc($user_id,$circular_id,$userFileName)
	{
	    	$year_id = $this->getYear();

			$sql="UPDATE  edu_circular_master SET circular_doc = '$userFileName',updated_by='$user_id',updated_at=NOW() where id='$circular_id'";
            $resultset=$this->db->query($sql);
			$response = array("status" => "success", "msg" => "Circular Document Updated");

			return $response;

	}
	//#################### Circular Master End ####################//	
	
	//#################### Role Master List ####################//
	public function listRoles($user_id)
	{
	    	$year_id = $this->getYear();
		
			$sqlrole = "SELECT * FROM edu_role WHERE role_id !='1' ORDER BY role_id";
			$role_res = $this->db->query($sqlrole);
			$role_result= $role_res->result();
			$role_count = $role_res->num_rows();
		
			if($role_count>0)
			{
				$response = array("status" => "success", "msg" => "Role List", "roleList"=>$role_result);
			} else {
				$response = array("status" => "error", "msg" => "No Records Found");
			}
			return $response;

	}
	//#################### Roles End ####################//
	
	//#################### Circular Send SMS  ####################//
	
	function send_circular_sms($circular_id,$all_id,$tusers_id,$musers_id,$susers_id,$pusers_id)
	{	

		$ssql = "SELECT * FROM edu_circular_master WHERE id ='$circular_id'";
		$res = $this->db->query($ssql);
		$result =$res->result();
			foreach($result as $rows){ }
			 $title = $rows->circular_title;
			 $notes = $rows->circular_description;
			 $circular_doc = $rows->circular_doc;
			 
	
	
		if ($all_id != '') {
			//------------------------Teacher----------------------
				if($all_id==2)
				{
					$tsql = "SELECT u.user_id,t.name,t.phone FROM edu_users AS u,edu_teachers AS t  WHERE u.user_type='$all_id' AND u.user_master_id=t.teacher_id AND u.status='Active'";
					$res=$this->db->query($tsql);
					$result=$res->result();
					foreach($result as $rows)
					{
						$phone = $rows->phone;
						$this->sendSMS($phone,$notes);
				    }
				}

				//------------------------Staffs----------------------
				if($all_id==5)
				{
					$tsql = "SELECT u.user_id,t.name,t.phone FROM edu_users AS u,edu_teachers AS t  WHERE u.user_type='$all_id' AND u.user_master_id=t.teacher_id AND u.status='Active'";
					$res=$this->db->query($tsql);
					$result=$res->result();
					foreach($result as $rows)
					{
						$phone = $rows->phone;
						$this->sendSMS($phone,$notes);
				    }
				}
				
				//---------------------------Students----------------------
				if($all_id==3)
				{
					$ssql="SELECT u.user_id,u.name,a.mobile FROM edu_users AS u,edu_admission AS a  WHERE u.user_type='$all_id' AND u.user_master_id=a.admission_id AND u.name=a.name AND u.status='Active'";
					$res=$this->db->query($ssql);
					$result=$res->result();
					foreach($result as $rows)
					{
					    
						$phone = $rows->mobile;
						$this->sendSMS($phone,$notes);

				    }
				}

				//---------------------------Parents--------------------------------------------
				if($all_id==4)
				{
					$psql="SELECT u.user_id,u.name,p.mobile FROM edu_users AS u,edu_parents AS p WHERE u.user_type='$all_id' AND u.user_master_id=p.id AND u.status='Active'";
					$res=$this->db->query($psql);
					$result=$res->result();
					foreach($result as $rows)
					{
						$phone = $rows->mobile;
						$this->sendSMS($phone,$notes);

				    }
				}

			}
	
	//------------------------for Teachers Only----------------------
				
	if ($tusers_id != ''){
				
				$t_id = explode(',',$tusers_id);		
				$countid = count($t_id);

				 for ($i=0;$i<$countid;$i++)
				 {
					 $userid = $t_id[$i];
					 $sql="SELECT u.user_id,u.user_type,u.user_master_id,t.teacher_id,t.name,t.phone FROM edu_users AS u,edu_teachers AS t WHERE u.user_id='$userid' AND u.user_type='2' AND u.user_master_id=t.teacher_id";
					$tcell=$this->db->query($sql);
					$res=$tcell->result();
					foreach($res as $row){ } 
						 $phone = $row->phone;
						$this->sendSMS($phone,$notes);
                }
	}
	
	
	//------------------------for Members Only----------------------
				
	if ($musers_id != ''){
	
				$m_id = explode(',',$musers_id);		
				$countid = count($m_id);

				 for ($i=0;$i<$countid;$i++)
				 {
					$userid = $m_id[$i];
					$sql="SELECT u.user_id,u.user_type,u.user_master_id,t.teacher_id,t.name,t.phone FROM edu_users AS u,edu_teachers AS t WHERE u.user_id='$userid' AND u.user_type='5' AND u.user_master_id=t.teacher_id";
					$tcell=$this->db->query($sql);
					$res=$tcell->result();
					foreach($res as $row){ } 
						$phone = $row->phone;
						$this->sendSMS($phone,$notes);
                }
	}
	
	
	//------------------------for Students Only----------------------
	
	if($susers_id != '')
	{
		$s_id = explode(',',$susers_id);		
		$scountid = count($s_id);

		 for ($i=0;$i<$scountid;$i++)
		 {
			$clsid = $s_id[$i];
			
			$sql1 = "SELECT e.enroll_id,e.admission_id,e.admisn_no,e.name,e.class_id,a.admission_id,a.admisn_no,a.name,a.mobile FROM edu_enrollment AS e,edu_admission AS a WHERE e.class_id='$clsid' AND e.admission_id=a.admission_id ";
			$scell = $this->db->query($sql1);
			$res1 = $scell->result();
				foreach($res1 as $row1)
				{
					$phone=$row1->mobile;
					$this->sendSMS($phone,$notes);
				}
		}
    }
	
	//------------------------for Parents Only----------------------
	
	if($pusers_id!='')
	{
			 
			$p_id = explode(',',$pusers_id);		
			$pcountid = count($p_id);
			 
			 for ($i=0;$i<$pcountid;$i++)
			 {
				$classid = $p_id[$i];
				$pgid = "SELECT e.enroll_id,e.admission_id,e.admisn_no,e.name,e.class_id FROM edu_enrollment AS e WHERE e.class_id='$classid'";
				$pcell = $this->db->query($pgid);
				$res2 = $pcell->result();
					foreach($res2 as $row2)
					{
					  $stuid=$row2->admission_id;
					  $class="SELECT id,mobile,admission_id,primary_flag FROM edu_parents WHERE FIND_IN_SET('$stuid',admission_id) AND primary_flag='Yes'";
					  $pcell1=$this->db->query($class);
					  $res3=$pcell1->result();
						foreach($res3 as $row3)
						{
							 $phone=$row3->mobile;
							 $this->sendSMS($phone,$notes);
						}
					}
			 }
		}

	
	}
	//#################### Circular Send SMS END ####################//
	
	
	
	//#################### Circular Send Email  ####################//
	
	function send_circular_email($circular_id,$all_id,$tusers_id,$musers_id,$susers_id,$pusers_id)
	{	

	     $ssql = "SELECT * FROM edu_circular_master WHERE id ='$circular_id'";
		$res = $this->db->query($ssql);
		$result =$res->result();
			foreach($result as $rows){ }
			 $title = $rows->circular_title;
			 $notes = $rows->circular_description;
			 $circular_doc = $rows->circular_doc;
	

	
		if ($all_id != '') {
			//------------------------Teacher----------------------
				if($all_id==2)
				{
					$tsql = "SELECT u.user_id,u.user_type,u.user_master_id,t.teacher_id,t.name,t.phone,t.email FROM edu_users AS u,edu_teachers AS t  WHERE u.user_type='$all_id' AND u.user_master_id=t.teacher_id AND u.status='Active'";
					$res=$this->db->query($tsql);
					$result=$res->result();
					foreach($result as $rows)
					{
						$semail = $rows->email;
						$this->sendMail($semail,$title,$notes);
				    }
				}

				//------------------------Staffs----------------------
				if($all_id==5)
				{
					$tsql = "SELECT u.user_id,u.user_type,u.user_master_id,t.teacher_id,t.name,t.phone,t.email FROM edu_users AS u,edu_teachers AS t  WHERE u.user_type='$all_id' AND u.user_master_id=t.teacher_id AND u.status='Active'";
					$res=$this->db->query($tsql);
					$result=$res->result();
					foreach($result as $rows)
					{
						$semail = $rows->email;
						$this->sendMail($semail,$title,$notes);
				    }
				}
				
				//---------------------------Students----------------------
				if($all_id==3)
				{
					$ssql="SELECT u.user_id,u.user_type,u.user_master_id,u.name,a.admission_id,a.name,a.mobile,a.email FROM edu_users AS u,edu_admission AS a  WHERE u.user_type='$all_id' AND u.user_master_id=a.admission_id AND u.name=a.name AND u.status='Active'";
					$res=$this->db->query($ssql);
					$result=$res->result();
					foreach($result as $rows)
					{
						$semail = $rows->email;
						$this->sendMail($semail,$title,$notes);

				    }
				}

				//---------------------------Parents--------------------------------------------
				if($all_id==4)
				{
					$psql="SELECT u.user_id,u.user_type,u.user_master_id,u.name,p.id,p.mobile,p.email FROM edu_users AS u,edu_parents AS p WHERE u.user_type='$all_id' AND u.user_master_id=p.id AND u.status='Active'";
					$res=$this->db->query($psql);
					$result=$res->result();
					foreach($result as $rows)
					{
						$semail = $rows->email;
						$this->sendMail($semail,$title,$notes);
				    }
				}

			}
	
	//------------------------for Teachers Only----------------------
				
	if ($tusers_id != ''){
				
				$t_id = explode(',',$tusers_id);		
				$countid = count($t_id);

				 for ($i=0;$i<$countid;$i++)
				 {
					 $userid = $t_id[$i];
					 $sql="SELECT u.user_id,u.user_type,u.user_master_id,t.teacher_id,t.name,t.phone,t.email FROM edu_users AS u,edu_teachers AS t WHERE u.user_id='$userid' AND u.user_type='2' AND u.user_master_id=t.teacher_id";
					$tcell=$this->db->query($sql);
					$res=$tcell->result();
					foreach($res as $row){ } 
						$semail = $row->email;
						$this->sendMail($semail,$title,$notes);
                }
	}
	
	
	//------------------------for Members Only----------------------
				
	if ($musers_id != ''){
	
				$m_id = explode(',',$musers_id);		
				$countid = count($m_id);

				 for ($i=0;$i<$countid;$i++)
				 {
					$userid = $m_id[$i];
					$sql="SELECT u.user_id,u.user_type,u.user_master_id,t.teacher_id,t.name,t.phone,t.email FROM edu_users AS u,edu_teachers AS t WHERE u.user_id='$userid' AND u.user_type='5' AND u.user_master_id=t.teacher_id";
					$tcell=$this->db->query($sql);
					$res=$tcell->result();
					foreach($res as $row){ } 
						$semail = $row->email;
						$this->sendMail($semail,$title,$notes);
                }
	}
	
	
	//------------------------for Students Only----------------------
	
	if($susers_id != '')
	{
		$s_id = explode(',',$susers_id);		
		$scountid = count($s_id);

		 for ($i=0;$i<$scountid;$i++)
		 {
			$clsid = $s_id[$i];
			
			$sql1 = "SELECT e.enroll_id,e.admission_id,e.admisn_no,e.name,e.class_id,a.admission_id,a.admisn_no,a.name,a.mobile,a.email FROM edu_enrollment AS e,edu_admission AS a WHERE e.class_id='$clsid' AND e.admission_id=a.admission_id";
			$scell = $this->db->query($sql1);
			$res1 = $scell->result();
				foreach($res1 as $row1)
				{
					$semail = $row1->email;
					$this->sendMail($semail,$title,$notes);
				}
		}
    }
	
	//------------------------for Parents Only----------------------
	
	if($pusers_id!='')
	{
			 
			$p_id = explode(',',$pusers_id);		
			$pcountid = count($p_id);
			 
			 for ($i=0;$i<$pcountid;$i++)
			 {
				$classid = $p_id[$i];
				$pgid = "SELECT e.enroll_id,e.admission_id,e.admisn_no,e.name,e.class_id FROM edu_enrollment AS e WHERE e.class_id='$classid'";
				$pcell = $this->db->query($pgid);
				$res2 = $pcell->result();
					foreach($res2 as $row2)
					{
					  $stuid=$row2->admission_id;
					  $class="SELECT p.id,p.admission_id,p.email,p.primary_flag FROM edu_parents AS p WHERE FIND_IN_SET('$stuid',admission_id) AND p.primary_flag='Yes'";
					  $pcell1=$this->db->query($class);
					  $res3=$pcell1->result();
						foreach($res3 as $row3)
						{
							 $semail=$row3->email;
							 $this->sendMail($semail,$title,$notes);
						}
					}
			 }
		}

	}
	//#################### Circular Send SMS END ####################//


	//#################### Circular Send Notification  ####################//
	
	function send_circular_notification($circular_id,$all_id,$tusers_id,$musers_id,$susers_id,$pusers_id)
	{	

		$ssql = "SELECT * FROM edu_circular_master WHERE id ='$circular_id'";
		$res = $this->db->query($ssql);
		$result =$res->result();
			foreach($result as $rows){ }
			 $title = $rows->circular_title;
			 $notes = $rows->circular_description;
			 $circular_doc = $rows->circular_doc;
	
	
		if ($all_id != '') {
			//------------------------Teacher----------------------
				if($all_id==2)
				{
					$tsql="SELECT u.user_id,u.user_type,u.user_master_id,t.teacher_id,t.name,t.phone FROM edu_users AS u,edu_teachers AS t  WHERE u.user_type='$all_id' AND u.user_master_id=t.teacher_id AND u.status='Active'";
					$tres=$this->db->query($tsql);
					$tresult1=$tres->result();
					foreach($tresult1 as $trows)
					{
						$userid=$trows->user_id;

					    $sql="SELECT * FROM edu_notification WHERE user_id='$userid'";
						$tgsm=$this->db->query($sql);
						$tres1=$tgsm->result();
						foreach($tres1 as $trow)
					    {
						   $gsmkey= $trow->gcm_key;
						   $mobile_type = $row->mobile_type;
						   $this->sendNotification($gcm_key,$title,$notes,$mobile_type);
					    }
				 	 }
				}

				//------------------------Staffs----------------------
				if($all_id==5)
				{
					$tsql="SELECT u.user_id,u.user_type,u.user_master_id,t.teacher_id,t.name,t.phone FROM edu_users AS u,edu_teachers AS t  WHERE u.user_type='$all_id' AND u.user_master_id=t.teacher_id AND u.status='Active'";
					$tres=$this->db->query($tsql);
					$tresult1=$tres->result();
					foreach($tresult1 as $trows)
					{
						$userid=$trows->user_id;

					    $sql="SELECT * FROM edu_notification WHERE user_id='$userid'";
						$tgsm=$this->db->query($sql);
						$tres1=$tgsm->result();
						foreach($tres1 as $trow)
					    {
						   $gsmkey= $trow->gcm_key;
						   $mobile_type = $row->mobile_type;
						   $this->sendNotification($gcm_key,$title,$notes,$mobile_type);
					    }
				 	 }
				}
				
				//---------------------------Students----------------------
				if($all_id==3)
				{
					$ssql="SELECT u.user_id,u.user_type,u.user_master_id,u.name,a.admission_id,a.name FROM edu_users AS u,edu_admission AS a  WHERE u.user_type='$all_id' AND u.user_master_id=a.admission_id AND u.name=a.name AND u.status='Active'";
					$sres2=$this->db->query($ssql);
					$sresult2=$sres2->result();
					foreach($sresult2 as $srows1)
					{
					   $suserid=$srows1->user_id;

					    $sql="SELECT * FROM edu_notification WHERE user_id='$suserid'";
						$sgsm=$this->db->query($sql);
						$sres1=$sgsm->result();
						foreach($sres1 as $srow)
					    {
						   $gsmkey=array($srow->gcm_key);
						   $mobile_type = $row->mobile_type;
						    $this->sendNotification($gcm_key,$title,$notes,$mobile_type);
					    }

				   }
				}

				//---------------------------Parents--------------------------------------------
				if($all_id==4)
				{
					$psql="SELECT u.user_id,u.user_type,u.user_master_id,u.name,p.id FROM edu_users AS u,edu_parents AS p WHERE u.user_type='$all_id' AND u.user_master_id=p.id AND u.status='Active'";
					$pres2=$this->db->query($psql);
					$presult2=$pres2->result();
					foreach($presult2 as $prows1)
					{
					     $puserid=$prows1->user_id;

					    $sql="SELECT * FROM edu_notification WHERE user_id='$puserid'";
						$pgsm=$this->db->query($sql);
						$pres1=$pgsm->result();
						foreach($pres1 as $prow)
					    {
						   $gsmkey=array($prow->gcm_key);
						   $mobile_type = $row->mobile_type;
						   $this->sendNotification($gcm_key,$title,$notes,$mobile_type);
					    }

				   	}

				}

			}
	
	//------------------------for Teachers Only----------------------
				
	if ($tusers_id != ''){
				
				$t_id = explode(',',$tusers_id);		
				$countid = count($t_id);

				 for ($i=0;$i<$countid;$i++)
				 {
					 $userid = $t_id[$i];
					 
					$sql="SELECT * FROM edu_notification WHERE user_id='$userid'";
					$tcell=$this->db->query($sql);
					$res=$tcell->result();
					foreach($res as $row){ 
						$gcm_key = $row->gcm_key;
						$mobile_type = $row->mobile_type;
						$this->sendNotification($gcm_key,$title,$notes,$mobile_type);
					} 
                }
	}
	
	
	//------------------------for Members Only----------------------
				
	if ($musers_id != ''){
	
				$m_id = explode(',',$musers_id);		
				$countid = count($m_id);

				 for ($i=0;$i<$countid;$i++)
				 {
					$userid = $m_id[$i];
					$sql="SELECT * FROM edu_notification WHERE user_id='$userid'";
					$tcell=$this->db->query($sql);
					$res=$tcell->result();
					foreach($res as $row){ } 
						$gcm_key = $row->gcm_key;
						$mobile_type = $row->mobile_type;
						$this->sendNotification($gcm_key,$title,$notes,$mobile_type);
                }
	}
	
	
	//------------------------for Students Only----------------------
	
	if($susers_id != '')
	{
		$s_id = explode(',',$susers_id);		
		$scountid = count($s_id);

		 for ($i=0;$i<$scountid;$i++)
		 {
			$clsid = $s_id[$i];
			
			$sql1="SELECT u.user_id,u.user_type,u.user_master_id,u.student_id,e.enroll_id,e.admission_id,e.admisn_no,e.name,e.class_id,a.admission_id,a.admisn_no,a.name,a.mobile FROM edu_enrollment AS e,edu_admission AS a,edu_users AS u  WHERE e.class_id='$clsid' AND e.admission_id=a.admission_id  AND u.user_type=3 AND a.admission_id=u.user_master_id AND
            a.admission_id=u.student_id";
					$scell=$this->db->query($sql1);
					$res1=$scell->result();
					foreach($res1 as $row1)
					 {
					    $userid=$row1->user_id;
						$sql="SELECT * FROM edu_notification WHERE user_id='$userid'";
           				$sgsm=$this->db->query($sql);
						$res=$sgsm->result();
						foreach($res as $row)
					    {
						   $gsm_key= $row->gcm_key;
						   $mobile_type = $row->mobile_type;
							$this->sendNotification($gcm_key,$title,$notes,$mobile_type);
					    }
					}
				 }
    }
	
	//------------------------for Parents Only----------------------
	
	if($pusers_id!='')
	{
			 
			$p_id = explode(',',$pusers_id);		
			$pcountid = count($p_id);
			 
			 for ($i=0;$i<$pcountid;$i++)
			 {
				$classid = $p_id[$i];
				
				$pgid="SELECT e.enroll_id,e.admission_id,e.admisn_no,e.name,e.class_id FROM edu_enrollment AS e WHERE e.class_id='$classid'";
				$pcell=$this->db->query($pgid);
				$res2=$pcell->result();
					foreach($res2 as $row2)
					{
						$stuid=$row2->admission_id;

						$class="SELECT p.id,p.admission_id,u.user_id,u.user_type,u.user_master_id,u.parent_id FROM edu_parents AS p,edu_users AS u WHERE FIND_IN_SET('$stuid',admission_id) AND u.parent_id=p.id AND u.user_master_id=p.id AND u.user_type='4' AND u.status='Active'";
						$pcell1=$this->db->query($class);
						$res3=$pcell1->result();
						foreach($res3 as $row3)
						{
							$userid=$row3->user_id;
							$sql="SELECT * FROM edu_notification WHERE user_id='$userid'";
							$pgsm=$this->db->query($sql);
							$pres=$pgsm->result();
								foreach($pres as $prow)
								{
									$gsmkey=array($prow->gcm_key);
								}
						}
					}
			 }
		}
		
	}
	//#################### Circular Send Notification END ####################//


	//####################  Circular History ####################//
	public function save_circular_history($circular_id,$circular_date,$circular_type,$all_id,$tusers_id,$musers_id,$susers_id,$pusers_id,$status,$user_id)
	{

		//------------------------All----------------------
		if ($all_id != '') {
		
				$sql="SELECT * FROM edu_users WHERE user_type='$all_id' AND status='Active'";
				$res=$this->db->query($sql);
				$result=$res->result();
					foreach($result as $rows)
					{
						$userid = $rows->user_id;
						$query = "INSERT INTO edu_circular(user_type,user_id,circular_master_id,circular_type,circular_date,status,created_by,created_at) VALUES ('$all_id','$userid','$circular_id','$circular_type','$circular_date','$status','$user_id',NOW())";
						$resultset = $this->db->query($query);
					}
		}


//------------------------for Teachers Only----------------------
				
	if ($tusers_id != ''){
				
				$t_id = explode(',',$tusers_id);		
				$countid = count($t_id);

				 for ($i=0;$i<$countid;$i++)
				 {
					 $userid = $t_id[$i];
					 
					$query = "INSERT INTO edu_circular(user_type,user_id,circular_master_id,circular_type,circular_date,status,created_by,created_at) VALUES ('2','$userid','$circular_id','$circular_type','$circular_date','$status','$user_id',NOW())";
					$resultset = $this->db->query($query);
                }
	}
	
	//------------------------for Members Only----------------------
				
	if ($musers_id != ''){
				
				$m_id = explode(',',$musers_id);		
				$countid = count($m_id);

				 for ($i=0;$i<$countid;$i++)
				 {
					 $userid = $m_id[$i];
					 
					 $query = "INSERT INTO edu_circular(user_type,user_id,circular_master_id,circular_type,circular_date,status,created_by,created_at) VALUES ('5','$userid','$circular_id','$circular_type','$circular_date','$status','$user_id',NOW())";
					$resultset = $this->db->query($query);
                }
	}
	
	//------------------------for Students Only----------------------
	
	if($susers_id != '')
	{
		$s_id = explode(',',$susers_id);		
		$scountid = count($s_id);

		 for ($i=0;$i<$scountid;$i++)
		 {
			$clsid = $s_id[$i];
			
			$sql1="SELECT u.user_id,u.user_type,u.user_master_id,u.student_id,e.enroll_id,e.admission_id,e.admisn_no,e.name,e.class_id,a.admission_id,a.admisn_no,a.name,a.mobile FROM edu_enrollment AS e,edu_admission AS a,edu_users AS u  WHERE e.class_id='$clsid' AND e.admission_id=a.admission_id  AND u.user_type=3 AND a.admission_id=u.user_master_id AND
            a.admission_id=u.student_id";
					$scell=$this->db->query($sql1);
					$res1=$scell->result();
					foreach($res1 as $row1)
					 {
					    $userid=$row1->user_id;
						$query = "INSERT INTO edu_circular(user_type,user_id,circular_master_id,circular_type,circular_date,status,created_by,created_at) VALUES ('$susers_id','$userid','$circular_id','$circular_type','$circular_date','$status','$user_id',NOW())";
						$resultset = $this->db->query($query);
					}
				 }
    }
	
	//------------------------for Parents Only----------------------
	
	if($pusers_id!='')
	{
			 
			$p_id = explode(',',$pusers_id);		
			$pcountid = count($p_id);
			 
			 for ($i=0;$i<$pcountid;$i++)
			 {
				$classid = $p_id[$i];
				
				$pgid="SELECT e.enroll_id,e.admission_id,e.admisn_no,e.name,e.class_id FROM edu_enrollment AS e WHERE e.class_id='$classid'";
				$pcell=$this->db->query($pgid);
				$res2=$pcell->result();
					foreach($res2 as $row2)
					{
						$stuid=$row2->admission_id;

						$class="SELECT p.id,p.admission_id,u.user_id,u.user_type,u.user_master_id,u.parent_id FROM edu_parents AS p,edu_users AS u WHERE FIND_IN_SET('$stuid',admission_id) AND u.parent_id=p.id AND u.user_master_id=p.id AND u.user_type='4' AND u.status='Active'";
						$pcell1=$this->db->query($class);
						$res3=$pcell1->result();
						foreach($res3 as $row3)
						{
							$userid=$row3->user_id;
							 $query = "INSERT INTO edu_circular(user_type,user_id,circular_master_id,circular_type,circular_date,status,created_by,created_at) VALUES ('$pusers_id','$userid','$circular_id','$circular_type','$circular_date','$status','$user_id',NOW())";
						$resultset = $this->db->query($query);
							
						}
					}
			 }
		}

          $response = array("status" => "sucess", "msg" => "Circular Send Sucessfully");
		  return $response;
           
     }
	//####################  Circular History End ####################//
	
	function get_class_circular_view($user_type){
			$year_id=$this->getYear();
			if ($user_type =='2'){
				 $query="SELECT c.id,c.user_type,c.user_id,c.circular_master_id,c.circular_date,c.circular_type,cm.*,u.user_id,u.name FROM edu_circular AS c,edu_users AS u,edu_circular_master AS cm WHERE c.user_type='2' AND  cm.id=c.circular_master_id AND c.user_id=u.user_id AND cm.academic_year_id = '$year_id' AND  cm.status='Active' ORDER BY c.id DESC";
			}
			if ($user_type =='3'){
				$query="SELECT cm.*, c.circular_date, c.circular_type, c.user_type, e.class_id, cl.class_name, se.sec_name FROM edu_circular AS c, edu_users AS u, edu_admission AS a, edu_enrollment AS e, edu_circular_master AS cm, edu_classmaster AS clm, edu_class AS cl, edu_sections AS se WHERE c.user_type = '$user_type' AND u.user_type = c.user_type AND cm.id = c.circular_master_id AND c.user_id = u.user_id AND u.user_master_id = a.admission_id AND u.student_id = a.admission_id AND a.admission_id = e.admission_id AND clm.class_sec_id = e.class_id AND clm.class = cl.class_id AND clm.section = se.sec_id AND cm.academic_year_id = '$year_id' AND cm.status = 'Active' GROUP BY e.class_id, cm.circular_title, c.circular_type, c.circular_date ORDER BY c.id DESC";
			}
			if ($user_type =='4'){
				$query="SELECT cm.*, c.circular_date, c.circular_type, c.user_type, e.class_id, cl.class_name, se.sec_name FROM edu_circular AS c, edu_users AS u, edu_admission AS a, edu_enrollment AS e, edu_circular_master AS cm, edu_classmaster AS clm, edu_class AS cl, edu_sections AS se WHERE c.user_type = '$user_type' AND u.user_type = c.user_type AND cm.id = c.circular_master_id AND c.user_id = u.user_id AND u.user_master_id = a.parnt_guardn_id AND a.admission_id = e.admission_id AND clm.class_sec_id = e.class_id AND clm.class = cl.class_id AND clm.section = se.sec_id AND cm.academic_year_id = '$year_id' AND cm.status = 'Active' GROUP BY e.class_id, cm.circular_title, c.circular_type, c.circular_date ORDER BY c.id DESC";
			}
			if ($user_type =='5'){
				 $query="SELECT c.id,c.user_type,c.user_id,c.circular_master_id,c.circular_date,c.circular_type,cm.*,u.user_id,u.name FROM edu_circular AS c,edu_users AS u,edu_circular_master AS cm WHERE c.user_type='5' AND  cm.id=c.circular_master_id AND c.user_id=u.user_id AND cm.academic_year_id = '$year_id' AND  cm.status='Active' ORDER BY c.id DESC";
			}
            $result_query=$this->db->query($query);
            if($result_query->num_rows()==0){
                $data=array("status"=>"error","msg"=>"nodata");
                return $data;
            }else{
              $result=$result_query->result();
              $data=array("status"=>"success","msg"=>"circularfound","circularDetails"=>$result);
              return $data;
            }
          }

}

?>
