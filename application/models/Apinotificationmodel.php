<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apinotificationmodel extends CI_Model {

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
	    echo $gcm_key;
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

		$sqlYear = "SELECT * FROM edu_academic_year WHERE NOW() >= from_month AND NOW() <= to_month AND status = 'Active'";
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

//#################### Exam Nofification ####################//

	public function exam_notification()
	{

	    $sQuery = "SELECT A.teacher_id,B.exam_name,C.subject_name,D.name,D.phone,D.email,CONCAT(A.exam_date,' ',A.times) AS exam_date_time, CONCAT(F.class_name,' ',G.sec_name) AS class_section
FROM
    `edu_exam_details` A,
    `edu_examination` B,
    `edu_subject` C,
    `edu_teachers` D,
    `edu_classmaster` E,
    `edu_class` F,
    `edu_sections` G
WHERE
    A.exam_id = B.exam_id AND A.subject_id = C.subject_id AND A.teacher_id = D.teacher_id AND A.classmaster_id = E.class_sec_id AND E.class = F.class_id AND E.section = G.sec_id AND `exam_date` =(CURDATE() + INTERVAL 1 DAY)";
		$sresult = $this->db->query($sQuery);
		$ress = $sresult->result();
		
		if($sresult->num_rows() != 0)
    		{
    		    $subject = 'Reminder for Exam Duty';
                foreach ($sresult->result() as $rows)
    			{
    				  $exam_name = $rows->exam_name;
    				  $subject_name = $rows->subject_name;
    				  $exam_date_time = $rows->exam_date_time;
    				  $class_section = $rows->class_section;
    				  $mobile_no = $rows->phone;
					  $email = $rows->email;
					  $mobile_message = 'Exam Duty Reminder on - '.$exam_date_time.' for '. $class_section;
					  $email_message = 'Exam Duty Reminder on - '.$exam_date_time.' for '. $class_section;

				  $this->sendSMS($mobile_no,$mobile_message);
                  $this->sendMail($email,$subject,$email_message);
    			}
			}
	

		    $sQuery1 = "SELECT A.teacher_id,H.user_id,B.exam_name,C.subject_name,D.name,D.phone,D.email,CONCAT(A.exam_date,' ',A.times) AS exam_date_time, CONCAT(F.class_name,' ',G.sec_name) AS class_section, I.gcm_key, I.mobile_type
FROM
    `edu_exam_details` A,
    `edu_examination` B,
    `edu_subject` C,
    `edu_teachers` D,
    `edu_classmaster` E,
    `edu_class` F,
    `edu_sections` G,
	`edu_users` H,
	`edu_notification` I
WHERE
    A.exam_id = B.exam_id AND A.subject_id = C.subject_id AND A.teacher_id = D.teacher_id AND A.classmaster_id = E.class_sec_id AND E.class = F.class_id AND E.section = G.sec_id AND H.user_type = '2' AND A.teacher_id = H.user_master_id AND H.user_id = I.user_id AND `exam_date` =(CURDATE() + INTERVAL 1 DAY)";
		$sresult1 = $this->db->query($sQuery1);
		$ress1 = $sresult1->result();
		if($sresult1->num_rows() > 0)
    		{
    		    $title = 'Reminder for Exam Duty';
                foreach ($sresult1->result() as $rows1)
    			{
                    $exam_name = $rows1->exam_name;
                    $subject_name = $rows1->subject_name;
                    $exam_date_time = $rows1->exam_date_time;
                    $class_section = $rows1->class_section;
                    $mobile_no = $rows1->phone;
                    $email = $rows1->email;
                    $message = 'Exam Duty Reminder on - '.$exam_date_time.' for '. $class_section;
                    $gcm_key = $rows1->gcm_key;
                    $mobile_type = $rows1->mobile_type;
                    $this->sendNotification($gcm_key,$title,$message,$mobile_type);
    			}
			}
	}
//#################### Exam Nofification End ####################//


}

?>
