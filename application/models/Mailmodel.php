<?php

Class Mailmodel extends CI_Model
{

 public function __construct()
  {
      parent::__construct();

  }

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

  function sendMailwithattachement($to,$subject,$email_message,$att)
    {

      $htmlContent = '
      <html>
      <head><title></title>
      </head>
      <body>
        <p style="margin-left:50px;">'.$email_message.'</p>
        <p style="margin-left:50px;"><a href="'. base_url().'assets/circular/'. $att.'">Download here</a></p>
      </body>
      </html>';
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      // Additional headers
      $headers .= 'From: ensyfi<info@ensyfi.com>' . "\r\n";
      $sent= mail($to,$subject,$htmlContent,$headers);
    }

    function sendMail($to,$subject,$email_message)
      {

        $htmlContent = '
        <html>
        <head><title></title>
        </head>
        <body>
          <p style="margin-left:50px;">'.$email_message.'</p>

        </body>
        </html>';
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        // Additional headers
        $headers .= 'From: ensyfi<info@ensyfi.com>' . "\r\n";
        $sent= mail($to,$subject,$htmlContent,$headers);
      }


  function send_mail_for_teacher_substitution($tname,$sub_teacher,$sub_tname,$leave_date,$cls_id,$period_id)
  {

	$sql="SELECT teacher_id,name,email FROM edu_teachers WHERE teacher_id='$sub_teacher'";
	$resultset=$this->db->query($sql);
	$res=$resultset->result();
	foreach($res as $cell){}
	$email_id=$cell->email;

	$sql1="SELECT cm.class_sec_id,cm.class,cm.section,c.class_id,c.class_name,s.sec_id,s.sec_name FROM edu_classmaster AS cm,edu_class AS c,edu_sections AS s WHERE cm.class_sec_id='$cls_id' AND cm.class=c.class_id AND cm.section=s.sec_id ";
	$resultset1=$this->db->query($sql1);
	$res1=$resultset1->result();
	foreach($res1 as $cls){}
	$cname=$cls->class_name;
	$sename=$cls->sec_name;

	$textmessage='This is to inform you that as '.$tname.' is on leave,'.$sub_tname.' will be the substitute teacher to fill in for '.$cname.'-'.$sename.',period ('.$period_id.') on '.$leave_date.' ';

		 $to=$email_id;
		 $subject="SUBSTITUTION";
		 $cnotes=$textmessage;
		 $htmlContent = '
			 <html>
			 <head><title></title>
			 </head>
			 <body>
			<p style="margin-left:50px;">'.$cnotes.'</p>
			 </body>
			 </html>';
	 $headers = "MIME-Version: 1.0" . "\r\n";
	 $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	 // Additional headers
	 $headers .= 'From: happysanz<info@happysanz.com>' . "\r\n";
	 $sent= mail($to,$subject,$htmlContent,$headers);

  }

  function send_circular_via_mail($title_id,$notes,$cdate,$tusers_id,$stusers_id,$pusers_id,$bmusers_id,$users_id)
  {
		$ssql = "SELECT * FROM edu_circular_master WHERE id ='$title_id'";
		$res = $this->db->query($ssql);
		$result =$res->result();
    if($res->num_rows()==0){
      echo "";
    }else{
		foreach($result as $rows)
		{ }
		$title = $rows->circular_title;
		$notes = $rows->circular_description;
    $att = $rows->circular_doc;
	  $user_type = $users_id;



	  //-----------Admin------------------------
	  if(!empty($user_type))
	   {
        // echo $user_type; echo $title; echo $notes;exit;
		 switch($user_type)
		 {

			case '2':

					$tsql="SELECT u.user_id,u.user_type,u.user_master_id,t.teacher_id,t.name,t.phone,t.email FROM edu_users AS u,edu_teachers AS t  WHERE u.user_type='$user_type' AND u.user_master_id=t.teacher_id AND u.status='Active'";
					$res=$this->db->query($tsql);
					$result1=$res->result();
					foreach($result1 as $rows)
					{
            $to=$rows->email;
            $subject=$title;
            $email_message=$notes.' '.$cdate;
            if(empty($att)){
              $this->sendMail($to,$subject,$email_message);
            }else{
              $this->sendMailwithattachement($to,$subject,$email_message,$att);
            }

          }

              //exit;
            break;
            case '5':

                $tsql="SELECT u.user_id,u.user_type,u.user_master_id,t.teacher_id,t.name,t.phone,t.email FROM edu_users AS u,edu_teachers AS t  WHERE u.user_type='$user_type' AND u.user_master_id=t.teacher_id AND u.status='Active'";
                $res=$this->db->query($tsql);
                $result1=$res->result();
                foreach($result1 as $rows)
                {
                  $to=$rows->email;
                  $subject=$title;
                  $email_message=$notes.' '.$cdate;
                  if(empty($att)){
                    $this->sendMail($to,$subject,$email_message);
                  }else{
                    $this->sendMailwithattachement($to,$subject,$email_message,$att);
                  }
                }

                  break;

			 case '3':

					$ssql="SELECT u.user_id,u.user_type,u.user_master_id,u.name,a.admission_id,a.name,a.mobile,a.email FROM edu_users AS u,edu_admission AS a  WHERE u.user_type='$user_type' AND u.user_master_id=a.admission_id AND u.name=a.name AND u.status='Active'";
					$res2=$this->db->query($ssql);
					$result2=$res2->result();
					foreach($result2 as $rows1)
					{
          $to=$rows1->email;
					$subject=$title;
          $email_message=$notes.' '.$cdate;
          if(empty($att)){
            $this->sendMail($to,$subject,$email_message);
          }else{
            $this->sendMailwithattachement($to,$subject,$email_message,$att);
          }
          }
            break;

			case '4':

					$psql="SELECT u.user_id,u.user_type,u.user_master_id,u.name,p.id,p.mobile,p.email FROM edu_users AS u,edu_parents AS p WHERE u.user_type='$users_id' AND u.user_master_id=p.id AND u.status='Active'";
					$pres2=$this->db->query($psql);
					$presult2=$pres2->result();
					foreach($presult2 as $prows1)
					{
            $to=$prows1->email;
            $subject=$title;
 					  $email_message=$notes.' '.$cdate;
            if(empty($att)){
              $this->sendMail($to,$subject,$email_message);
            }else{
              $this->sendMailwithattachement($to,$subject,$email_message,$att);
            }
          }

            break;

			default:
            echo "No result found";
            break;

		 }//Switch	close

	   }//admin close

	  //-----------------------------Teacher----------------------

			 if(!empty($tusers_id))
			 {
			     $countid=count($tusers_id);
			     //echo $countid;
				 for ($i=0;$i<$countid;$i++)
				 {
					$userid=$tusers_id[$i];

					$tesql="SELECT u.user_id,u.user_type,u.user_master_id,t.teacher_id,t.name,t.phone,t.email FROM edu_users AS u,edu_teachers AS t WHERE u.user_id='$userid' AND u.user_type='2' AND u.user_master_id=t.teacher_id";
					$tmail=$this->db->query($tesql);
					$tres=$tmail->result();
					foreach($tres as $trow)
					{
					 $to=$trow->email;
					 $subject=$title;
					 $email_message=$notes.' '.$cdate;
           if(empty($att)){
             $this->sendMail($to,$subject,$email_message);
           }else{
             $this->sendMailwithattachement($to,$subject,$email_message,$att);
           }
           }
        }

             }

             // Board Memebers
             if(!empty($bmusers_id))
             {
              $countid=count($bmusers_id);
               for ($i=0;$i<$countid;$i++)
               {
                $userid=$bmusers_id[$i];

                $tesql="SELECT u.user_id,u.user_type,u.user_master_id,t.teacher_id,t.name,t.phone,t.email FROM edu_users AS u,edu_teachers AS t WHERE u.user_id='$userid' AND u.user_type='5' AND u.user_master_id=t.teacher_id";
                $tmail=$this->db->query($tesql);
                $tres=$tmail->result();
                foreach($tres as $trow)
                {
                 $to=$trow->email;
                 $subject=$title;
                 $email_message=$notes.' '.$cdate;
                 if(empty($att)){
                   $this->sendMail($to,$subject,$email_message);
                 }else{
                   $this->sendMailwithattachement($to,$subject,$email_message,$att);
                 }
                 }

            }
          }
        }




			  //-----------------------------Students----------------------

			  if(!empty($stusers_id))
			 {
			     $scountid=count($stusers_id);
			      //echo $scountid; exit;
				 for ($i=0;$i<$scountid;$i++)
				 {
				  $clsid=$stusers_id[$i];

				 $sql1="SELECT e.enroll_id,e.admission_id,e.admisn_no,e.name,e.class_id,a.admission_id,a.admisn_no,a.name,a.mobile,a.email FROM edu_enrollment AS e,edu_admission AS a WHERE e.class_id='$clsid' AND e.admission_id=a.admission_id";
					$scell=$this->db->query($sql1);
					$res1=$scell->result();
					foreach($res1 as $row1)
					{
   				 $to=$row1->email;
           $subject=$title;
           $email_message=$notes.' '.$cdate;
           if(empty($att)){
             $this->sendMail($to,$subject,$email_message);
           }else{
             $this->sendMailwithattachement($to,$subject,$email_message,$att);
           }

				}
              }
            }



	  //-----------------------------Parents----------------------

			 if(!empty($pusers_id))
		     {
			   $pcountid=count($pusers_id);
			  //echo $pcountid;exit;
			  for ($i=0;$i<$pcountid;$i++)
			  {
				 $classid=$pusers_id[$i];

				 $pgid="SELECT e.enroll_id,e.admission_id,e.admisn_no,e.name,e.class_id FROM edu_enrollment AS e WHERE e.class_id='$classid'";
					 $pcell=$this->db->query($pgid);
				     $res2=$pcell->result();
				     foreach($res2 as $row2)
				     {
					  $stuid=$row2->admission_id;
					  $class="SELECT p.id,p.admission_id,p.email,p.primary_flag FROM edu_parents AS p WHERE FIND_IN_SET('$stuid',admission_id) AND p.primary_flag='Yes'";
					  $pcell1=$this->db->query($class);
					  $res3=$pcell1->result();
					  foreach($res3 as $row3)
					   {
       				 $to=$row3->email;
               $subject=$title;
               $email_message=$notes.' '.$cdate;
               if(empty($att)){
                 $this->sendMail($to,$subject,$email_message);
               }else{
                 $this->sendMailwithattachement($to,$subject,$email_message,$att);
               }
				      }
          }
        }
      }
    }







    // Group Mail
    function send_mail($group_id,$notes,$user_id,$members_id){
      $check_type="SELECT * FROM edu_grouping_members WHERE group_title_id='$group_id'";
      $get_type=$this->db->query($check_type);
        $res_type=$get_type->result();
      if($get_type->num_rows()==0){
        echo "";
      }else{


       foreach($res_type as $row_type){}
           $member_type=$row_type->member_type;
         $group_member_id=$row_type->group_member_id;
         if($member_type='3'){
           $sql1="SELECT egm.group_member_id,ep.email,ep.mobile FROM edu_grouping_members AS egm
          LEFT JOIN edu_users AS eu ON eu.user_id=egm.group_member_id LEFT JOIN edu_admission AS ea ON ea.admission_id=eu.user_master_id
          LEFT JOIN edu_parents AS ep ON FIND_IN_SET(ea.admission_id, ep.admission_id) WHERE  egm.group_title_id='$group_id'";

           $scell=$this->db->query($sql1);
           $res1=$scell->result();
           foreach($res1 as $row1)
           {
            $semail=$row1->email;
            $to=$semail;
            $subject="From Ensyfi";
            $email_message=$notes;
            $this->sendMail($to,$subject,$email_message);
         }
         }
         $check_type_staff="SELECT * FROM edu_grouping_members WHERE group_title_id='$group_id'";
         $get_type_staff=$this->db->query($check_type_staff);
         $res_type_staff=$get_type_staff->result();
         foreach($res_type_staff as $row_type_staff){}
          $member_type_staff=$row_type_staff->member_type;
         $group_member_id_staff=$row_type_staff->group_member_id;
         if($member_type='2' || $member_type='5'){
          $send_mail="SELECT * FROM edu_users AS A LEFT JOIN edu_teachers AS C ON A.teacher_id = C.teacher_id WHERE A.user_id = '$group_member_id_staff'";
           $get_mail=$this->db->query($send_mail);
           $res_mail=$get_mail->result();
           foreach($res_mail as $rows_mail){
             $get_mail=$rows_mail->email;
             $to=$get_mail;
             $subject="From Ensyfi";
             $email_message=$notes;
             $this->sendMail($to,$subject,$email_message);
           }

         }
       }




    }

	//-----------------------Send Home Work In Mail-----------------------------------------

     function send_mail_homework($user_id,$user_type,$createdate,$clssid)
		{
		   $year_id=$this->getYear();

		   $pcell="SELECT p.email FROM edu_parents AS p,edu_enrollment AS e WHERE e.class_id='$clssid' AND e.admit_year='$year_id' AND FIND_IN_SET( e.admission_id,p.admission_id) GROUP BY p.name";
		  $pcell1=$this->db->query($pcell);
		  $pcel2=$pcell1->result();
		  foreach($pcel2 as $res)
		  {  $pamail[]=$res->email;
		  }
		  $sms="SELECT h.title,h.hw_details,h.hw_type,h.test_date,s.subject_name FROM edu_homework AS h,edu_subject AS s WHERE h.class_id='$clssid' AND h.year_id='$year_id' AND DATE_FORMAT(h.created_at,'%Y-%m-%d')='$createdate' AND h.subject_id=s.subject_id";
		  $sms1=$this->db->query($sms);
		  $sms2= $sms1->result();
		  //return $sms2;
		  foreach ($sms2 as $value)
          {
            $hwtitle=$value->title;
		    $hwdetails=$value->hw_details;
			$subname=$value->subject_name;
			$ht=$value->hw_type;
			$tdat=$value->test_date;

			if($ht=='HW'){ $type="Home Work" ; }else{ $type="Class Test" ; }

			//$message="Title : " .$hwtitle. ",Type : " .$type. ", Details : " .$hwdetails .", Subject : ".$subname.", ";
			$message= " <br> Title : " .$hwtitle. " <br> Type : " .$type. " <br> Details : " .$hwdetails ." <br> Subject : ".$subname." <br> ";
			$home_work_details[]=$message;
		  }
			//print_r($home_work_details);
		     $hdetails=implode('',$home_work_details);
			 $pmail_to=implode(',',$pamail);
			 //echo $pmail_to;exit;
					 $to = $pmail_to;
					 $subject="HomeWork / ClassTest Details";
					 $cnotes=$hdetails;
					 $htmlContent = '
						 <html>
						 <head><title></title>
						 </head>
						 <body>
						 <p style="margin-left:50px;">'.$cnotes.'</p>
						 </body>
						 </html>';
				 $headers = "MIME-Version: 1.0" . "\r\n";
				 $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				 // Additional headers

				 $headers .= 'From: happysanz<info@happysanz.com>' . "\r\n";
                 //$send= mail($to,$subject,$htmlContent,$headers);
				  if(mail($to,$subject,$htmlContent,$headers))
				 {
                     $data= array("status"=>"success");
		             return $data;
                  }



	}


  function send_mail_attendance($attend_id){
    $query="SELECT ee.name,ep.mobile,ep.email,ee.admission_id,eah.abs_date,eah.student_id,eah.a_status,eah.attend_period,
    CASE WHEN attend_period = 0 THEN 'MORNING'  ELSE 'AFTERNOON' END  AS a_session,CASE WHEN a_status = 'L' THEN 'Leave' WHEN a_status = 'A' THEN 'Absent' ELSE 'OnDuty' END  AS abs_atatus FROM edu_attendance_history AS eah LEFT JOIN edu_enrollment AS ee ON ee.enroll_id=eah.student_id LEFT JOIN edu_parents AS ep ON ee.admission_id=ep.admission_id WHERE
     eah.attend_id='$attend_id' AND ep.primary_flag='Yes'";
   $result=$this->db->query($query);
   $res=$result->result();
   foreach($res as $rows){
      $st_name=$rows->name;
      $parents_email=$rows->email;
      $at_ses=$rows->a_session;
      $abs_date=$rows->abs_date;
      $abs_status=$rows->abs_atatus;
      $textmessage='Your child '.$st_name.' was marked '.$abs_status.' today, '.$abs_date.' ON '.$at_ses.' To Known more details login into http://bit.ly/2wLwdRQ';
      $to=$parents_email;
      $subject="From ENSYFI";

       $htmlContent = '
        <html>
        <head><title></title>
        </head>
        <body>
        <p style="margin-left:50px;">'.$textmessage.'</p>
        </body>
        </html>';
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    // Additional headers
    $headers .= 'From: happysanz<info@happysanz.com>' . "\r\n";
    $send= mail($to,$subject,$htmlContent,$headers);

   }
  }


}//end class
  ?>
