<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-clockpicker.min.css">
<script src="<?php echo base_url(); ?>assets/js/jquery-clockpicker.min.js"></script>
<style>
   fieldset{
   margin-left:30px;
   margin-top:15px;
   }
   select{width:270px;padding: 10px;
   border: 1px solid #E3E3E3;
 }
 .modal {
   text-align: center;
   padding: 0!important;
 }

 .modal:before {
   content: '';
   display: inline-block;
   height: 100%;
   vertical-align: middle;
   margin-right: -4px; /* Adjusts for spacing */
 }

 .modal-dialog {
   display: inline-block;
   text-align: left;
   vertical-align: middle;
 }
 .remove_field{
   float:right;
   margin-right: 130px;
   margin-top: -40px;
 }
 .clockpicker-popover {
 z-index: 999999 !important;
 }
 .modal-body{
   padding: 0px;
 }
 .table{
   border:2px solid #642160;
 }
 .box{
   background-color: red;
 }
 .period{
   background-color: #cecfcf;
 }
 .btn-day{
   border:1px solid;
 }
</style>
<div class="main-panel">
<div class="content">
<div class="card1">
   <?php if($this->session->flashdata('msg')): ?>
   <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
      ×</button> <?php echo $this->session->flashdata('msg'); ?>
   </div>
   <?php endif; ?>
</div>
<div class="content">
   <div class="col-md-12">
      <div class="card">
         <div class="header">
            <legend><h4 class="modal-title">Add Time table for <?php foreach($get_name_class as $rows){} echo $rows->class_name.'-'.$rows->sec_name;  ?></h4><span><button onclick="history.go(-1);" class="btn btn-wd btn-default pull-right" style="margin-top:-40px;">Go Back</button></span></legend>
         </div>
         <div class="content">
            <div class="row">
               <div class="col-md-12">
                 <div class="col-md-12">
               <?php foreach ($get_all_days as $rows) {  ?>
                 <a href="#myModal" data-toggle="modal" data-target="#myModal" data-day-id="<?php echo $rows->d_id; ?>"
                   class="btn btn-primary"
                    style="width:150px;margin-bottom:10px;margin-left:15px;border: 1px solid;" onclick="get_id(<?php echo $rows->d_id; ?>)"><?php echo $rows->list_day; ?></a>
             <?php      } ?>
               </div>
               </div>
             </div>
           </div>

    </div>
    <div class="card">
       <div class="header">
          <legend><h4 class="modal-title">Time table for <?php foreach($get_name_class as $rows){} echo $rows->class_name.'-'.$rows->sec_name;  ?></h4></legend>
       </div>
       <div class="content">
          <div class="row">
             <div class="col-md-12">
               <div class="col-md-4">
                 <center><span class="btn btn-primary btn-day">Monday</span></center>
                 <table id="" class="table" >
                    <thead>
                       <th data-field="Monday" class="text-center" data-sortable="true">Period</th>
                       <th data-field="Monday" class="text-center" data-sortable="true">From time -To time</th>
                       <th data-field="Monday" class="text-center" data-sortable="true">Subject /Staff</th>
                    </thead>
                    <tbody>
                       <?php
                       $i=1;
                          foreach($restime as $rows){
                            $day=$rows->day_id;
                            //echo $day;
                            if($day=="1"){
                              if($rows->is_break==1){
                                echo "<tr class='box'  style='color:#fff;'>";
                              } else{ ?>
                                <tr class="period">
                            <?php  }?>


                          <td>  <?php echo $i  ?></td>
                          <td>  <?php echo  date("g:i a", strtotime($rows->from_time)).'-'.date("g:i a", strtotime($rows->to_time));  ?></td>
                          <td>
                            <?php if($rows->is_break==1){
                            echo "Break";
                          } else{
                            echo $rows->subject_name; echo "<br>";
                            echo $rows->name;
                           }?></td>
                       </tr>
                       <?php  $i++; }else{
                          }

                          }
                          ?>
                    </tbody>
                 </table>
               </div>
               <div class="col-md-4">
                 <center><span class="btn btn-primary btn-day">Tuesday</span></center>
                 <table id="" class="table" >
                    <thead>
                       <th data-field="Monday" class="text-center" data-sortable="true">Period</th>
                       <th data-field="Monday" class="text-center" data-sortable="true">From time -To time</th>
                       <th data-field="Monday" class="text-center" data-sortable="true">Subject</th>
                    </thead>
                    <tbody>
                       <?php
                       $i=1;
                          foreach($restime as $rows){
                            $day=$rows->day_id;
                            //echo $day;
                            if($day=="2"){
                              if($rows->is_break==1){
                                echo "<tr class='box'  style='color:#fff;'>";
                              } else{ ?>
                                <tr  class="period">
                            <?php  }?>


                          <td>  <?php echo $i  ?></td>
                          <td>  <?php echo  date("g:i a", strtotime($rows->from_time)).'-'.date("g:i a", strtotime($rows->to_time));  ?></td>
                          <td>
                            <?php if($rows->is_break==1){
                            echo "Break";
                          } else{
                            echo $rows->subject_name;
                           }?></td>
                       </tr>
                       <?php  $i++; }else{
                          }

                          }
                          ?>
                    </tbody>
                 </table>
               </div>
               <div class="col-md-4">
                 <center><span class="btn btn-primary btn-day">Wednesday</span></center>
                 <table id="" class="table" >
                    <thead>
                       <th data-field="Monday" class="text-center" data-sortable="true">Period</th>
                       <th data-field="Monday" class="text-center" data-sortable="true">From time -To time</th>
                       <th data-field="Monday" class="text-center" data-sortable="true">Subject</th>
                    </thead>
                    <tbody>
                       <?php
                       $i=1;
                          foreach($restime as $rows){
                            $day=$rows->day_id;
                            //echo $day;
                            if($day=="3"){
                              if($rows->is_break==1){
                                echo "<tr class='box'  style='color:#fff;'>";
                              } else{ ?>
                                <tr  class="period">
                            <?php  }?>


                          <td>  <?php echo $i  ?></td>
                          <td>  <?php echo  date("g:i a", strtotime($rows->from_time)).'-'.date("g:i a", strtotime($rows->to_time));  ?></td>
                          <td>
                            <?php if($rows->is_break==1){
                            echo "Break";
                          } else{
                            echo $rows->subject_name;
                           }?></td>
                       </tr>
                       <?php  $i++; }else{
                          }

                          }
                          ?>
                    </tbody>
                 </table>
               </div>
               <div class="clearfix"></div>
               <div class="col-md-4">
                 <center><span class="btn btn-primary btn-day">Thursday</span></center>
                 <table id="" class="table" >
                    <thead>
                       <th data-field="Monday" class="text-center" data-sortable="true">Period</th>
                       <th data-field="Monday" class="text-center" data-sortable="true">From time -To time</th>
                       <th data-field="Monday" class="text-center" data-sortable="true">Subject</th>
                    </thead>
                    <tbody>
                       <?php
                       $i=1;
                          foreach($restime as $rows){
                            $day=$rows->day_id;
                            //echo $day;
                            if($day=="4"){
                              if($rows->is_break==1){
                                echo "<tr class='box'  style='color:#fff;'>";
                              } else{ ?>
                                <tr  class="period">
                            <?php  }?>


                          <td>  <?php echo $i  ?></td>
                          <td>  <?php echo  date("g:i a", strtotime($rows->from_time)).'-'.date("g:i a", strtotime($rows->to_time));  ?></td>
                          <td>
                            <?php if($rows->is_break==1){
                            echo "Break";
                          } else{
                            echo $rows->subject_name;
                           }?></td>
                       </tr>
                       <?php  $i++; }else{
                          }

                          }
                          ?>
                    </tbody>
                 </table>
               </div>
               <div class="col-md-4">
                 <center><span class="btn btn-primary btn-day">Friday</span></center>
                 <table id="" class="table" >
                    <thead>
                       <th data-field="Monday" class="text-center" data-sortable="true">Period</th>
                       <th data-field="Monday" class="text-center" data-sortable="true">From time -To time</th>
                       <th data-field="Monday" class="text-center" data-sortable="true">Subject</th>
                    </thead>
                    <tbody>
                       <?php
                       $i=1;
                          foreach($restime as $rows){
                            $day=$rows->day_id;
                            //echo $day;
                            if($day=="5"){
                              if($rows->is_break==1){
                                echo "<tr class='box'  style='color:#fff;'>";
                              } else{ ?>
                                <tr  class="period">
                            <?php  }?>


                          <td>  <?php echo $i  ?></td>
                          <td>  <?php echo  date("g:i a", strtotime($rows->from_time)).'-'.date("g:i a", strtotime($rows->to_time));  ?></td>
                          <td>
                            <?php if($rows->is_break==1){
                            echo "Break";
                          } else{
                            echo $rows->subject_name;
                           }?></td>
                       </tr>
                       <?php  $i++; }else{
                          }

                          }
                          ?>
                    </tbody>
                 </table>
               </div>
               <div class="col-md-4">
                 <center><span class="btn btn-primary btn-day">Saturday</span></center>
                 <table id="" class="table" >
                    <thead>
                       <th data-field="Monday" class="text-center" data-sortable="true">Period</th>
                       <th data-field="Monday" class="text-center" data-sortable="true">From time -To time</th>
                       <th data-field="Monday" class="text-center" data-sortable="true">Subject</th>
                    </thead>
                    <tbody>
                       <?php
                       $i=1;
                          foreach($restime as $rows){
                            $day=$rows->day_id;
                            //echo $day;
                            if($day=="6"){
                              if($rows->is_break==1){
                                echo "<tr class='box'  style='color:#fff;'>";
                              } else{ ?>
                                <tr  class="period">
                            <?php  }?>


                          <td>  <?php echo $i  ?></td>
                          <td>  <?php echo  date("g:i a", strtotime($rows->from_time)).'-'.date("g:i a", strtotime($rows->to_time));  ?></td>
                          <td>
                            <?php if($rows->is_break==1){
                            echo "Break";
                          } else{
                            echo $rows->subject_name;
                           }?></td>
                       </tr>
                       <?php  $i++; }else{
                          }

                          }
                          ?>
                    </tbody>
                 </table>
               </div>
             </div>
           </div>
         </div>

  </div>
   </div>
</div>
<div id="myModal" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">ADD Periods for <?php foreach($get_name_class as $rows){} echo $rows->class_name.'-'.$rows->sec_name;  ?></h4>
         </div>
         <div class="modal-body">
            <form action="" method="post" class="form-horizontal" id="add_periods">
              <fieldset>
              <div class="form-group">
                 <label class="col-sm-4 control-label">Current Year</label>
                 <div class="col-sm-6">
                    <?php  $status=$years['status']; if($status=="success"){
                       foreach($years['all_years'] as $rows){}
                         ?>
                    <input type="hidden" name="year_id"  value="<?php  echo $rows->year_id; ?>">

                    <input type="text" name="year_name"  class="form-control" value="<?php echo date('Y', strtotime($rows->from_month));  echo "-"; echo date('Y', strtotime( $rows->to_month));  ?>" readonly="">
                    <?php   }else{  ?>
                    <input type="text" name="year_id"  class="form-control" value="" readonly="">
                    <?php     } ?>
                    <input type="hidden" name="class_id"  value="<?php  echo  base64_decode($this->uri->segment(3))/9876; ?>">
                    <input type="hidden" name="term_id"  value="<?php  echo base64_decode($this->uri->segment(4))/9876; ?>">
                    <input type="hidden" name="day_id" id="day_id">
                 </div>

              </div>

                </fieldset>
               <fieldset>

                  <div class="form-group">
                      <input type="checkbox" class="" id="break_id"  name="is_break" value="1">Break
                     <label class="col-sm-4 control-label">From time </label>
                     <div class="col-sm-6 clockpicker">
                       <input type="text" class="form-control"  name="from_time" id="from_time"  placeholder="From Time" required>
                     </div>
                  </div>
                  <div class="form-group" >
                     <label class="col-sm-4 control-label">To Time</label>
                     <div class="col-sm-6 clockpicker">
                       <input type="text" class="form-control"  name="to_time" id="to_time"  placeholder="To Time" required>
                     </div>
                  </div>
                  <div class="form-group" id="subject_id_tab">
                     <label class="col-sm-4 control-label">Select Subject </label>
                     <div class="col-sm-6 clockpicker">
                      <select id="subject_id" name="subject_id" class="subject_id ">
                         <option value="">--Select Subject--</option>
                        <?php foreach($res_subject['res'] as $row_subject){ ?>
                          <option value="<?php echo $row_subject->subject_id; ?>"><?php echo $row_subject->subject_name; ?></option>
                       <?php } ?>
                      </select>
                     </div>
                  </div>
                  <div class="form-group" id="teacher_id_tab">
                     <label class="col-sm-4 control-label">Teacher  </label>
                     <div class="col-sm-6 clockpicker">
                         <select id="teacher_id" name="teacher_id" class="subject_id ">
                           <option value="">--Select Teacher--</option>
                           <?php foreach($res_teacher['res'] as $row_teacher){ ?>
                             <option value="<?php echo $row_teacher->teacher_id; ?>"><?php echo $row_teacher->name; ?></option>
                          <?php } ?>

                         </select>
                     </div>
                  </div>



                  <div class="form-group">
                     <label class="col-sm-4 control-label">&nbsp;</label>
                     <div class="col-sm-6">
                        <button type="submit" id="save" class="btn btn-info btn-fill center">Add Another Period </button>
                     </div>
                  </div>
               </fieldset>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
</div>
<style>
.remove_field{
  float:right;
  margin-right: 130px;
  margin-top: -40px;
}
</style>
<script type="text/javascript">
function get_id(day_id){
  $(".form-group #day_id").val(day_id);
}

$(document).ready(function() {

     function get_days(){
      var class_id=$('#class_id').val();
  	//alert(class_id);
      $.ajax({
         url:'<?php echo base_url(); ?>timetable/getsubject',
         method:"POST",
         data:{class_id:class_id},
         dataType: "JSON",
         cache: false,
         success:function(data)
         {
           var stat=data.status;
  		 //alert(stat);
             $(".subject_id").empty();
           if(stat=="success"){

             var res=data.res;
               var len=res.length;
                 $('<option>').val(" ").text("Select Subject").appendTo('.subject_id');
                 for (i = 0; i < len; i++) {
                   $('<option>').val(res[i].subject_id).text(res[i].subject_name).appendTo('.subject_id');
                 }

                getTeacher();
           }else{
         $(".subject_id").empty();
           }
         }
        });
     }


     function getTeacher(){
       var class_id=$('#class_id').val();
  	 //alert(class_id);
       $.ajax({
          url:'<?php echo base_url(); ?>timetable/getTeacher',
          method:"POST",
          data:{class_id:class_id},
          dataType: "JSON",
          cache: false,
          success:function(data)
          {
            var stat=data.status;
             //alert(stat);
             $(".teacher_id").empty();
           if(stat=="success"){

             var res=data.res;
             //alert(res.length);
             var len=res.length;
               $('<option>').val(" ").text("Select Teacher").appendTo('.teacher_id');

             for (i = 0; i < len; i++) {

             $('<option>').val(res[i].teacher_id).text(res[i].name).appendTo('.teacher_id');

             }

           }else{
               $("#teacher_id").empty();
           }


          }
         });
     }




     $('#timetablemenu').addClass('collapse in');
     $('#time').addClass('active');
     $('#time1').addClass('active');

     $('#add_periods').validate({ // initialize the plugin
        rules: {
        from_time:{required:true },
        to_time:{required:true },
        subject_id:{required:true },
        teacher_id:{required:true },

         },
         messages: {
               from_time:"Select From Time",
               to_time:"Select to time",
               subject_id:"Select Subject",
               teacher_id:"Select Teacher",
             },

       submitHandler: function(form) {

         $.ajax({
             url: "<?php echo base_url(); ?>timetable/create_timetable_for_class",
              type:'POST',
             data: $('#add_periods').serialize(),
             success: function(response) {
          // alert(response);
                 if(response=="success"){
                   swal({
                    title: "Success!",
                    text: "Period Added"

                  });
                    $('#add_periods')[0].reset();
                 }else if(response=="lesser"){
                    // swal("From Time Should be Greater Than Start time!")
                    swal("Error", "To Time Should be Greater Than Start time")

                 }else{
                   sweetAlert("Oops...", response, "error");
                 }
             }
         });


   }

     });

        });

        $('#from_time').clockpicker({ placement: 'top', align: 'left',twelvehour: true, donetext: 'Done'});
        $('#to_time').clockpicker({ placement: 'top', align: 'left',twelvehour: true, donetext: 'Done'});
        $('#break_id').on('change', function() {
            // From the other examples

            if(document.getElementById('break_id').checked) {
                $('#subject_id_tab').hide();
                $('#teacher_id_tab').hide();

            } else {

                $('#subject_id_tab').show();
                $('#teacher_id_tab').show();
            }
        });
</script>
