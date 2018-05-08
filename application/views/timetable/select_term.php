
<style>
   fieldset{
   margin-left:30px;
   margin-top:15px;
   }
   select{width:160px;padding: 10px;
   border: 1px solid #E3E3E3;
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
            <legend>Time Table</legend>
         </div>
         <div class="content">
            <div class="row">
               <div class="col-md-12">
                 <div class="col-md-10">
                 <?php foreach ($resterms as $rows) {  ?>
                 <a href="<?php echo base_url(); ?>timetable/selectclass/<?php echo base64_encode($rows->term_id*9876); ?>" class="btn btn-primary" style="width:150px;"><?php echo $rows->term_name; ?></a>
             <?php      } ?>
               </div>
               </div>
             </div>
           </div>
         <div class="content">
            <div class="row">
               <div class="col-md-12">
                  <div class="card">
                     <form method="post" action="<?php echo base_url(); ?>timetable/add_timetable_class" class="form-horizontal" enctype="multipart/form-data" id="timetableform">
                        <div class="row">
                           <fieldset>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Current Year</label>
                                 <div class="col-sm-3">
                                    <?php  $status=$years['status']; if($status=="success"){
                                       foreach($years['all_years'] as $rows){}
                                         ?>
                                    <input type="hidden" name="year_id"  value="<?php  echo $rows->year_id; ?>">
                                    <input type="text" name="year_name"  class="form-control" value="<?php echo date('Y', strtotime($rows->from_month));  echo "-"; echo date('Y', strtotime( $rows->to_month));  ?>" readonly="">
                                    <?php   }else{  ?>
                                    <input type="text" name="year_id"  class="form-control" value="" readonly="">
                                    <?php     } ?>
                                 </div>
                              </div>
                           </fieldset>
                            <fieldset>
                              <div class="form-group">
                                <label class="col-sm-2 control-label">Select Term</label>
                              <div class="col-sm-3">
                                <select   name="term_id"  data-title="Select Term" class="selectpicker" data-style="btn-block"  data-menu-style="dropdown-blue">
                                  <?php foreach ($resterms as $rows) {  ?>
                                  <option value="<?php echo $rows->term_id; ?>"><?php echo $rows->term_name; ?></option>
                              <?php      } ?>
                                </select>
                              </div>

                              </div>
                              </fieldset>
                           <fieldset>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Select class</label>
                                 <div class="col-sm-3">
                                    <select   name="class_id" id="class_id"  data-title="Select Class" class="selectpicker" onchange="getSubject(this.value)"   data-style="btn-block"  data-menu-style="dropdown-blue">
                                       <?php foreach ($getall_class as $rows) {  ?>
                                       <option value="<?php echo $rows->class_sec_id; ?>"><?php echo $rows->class_name; ?>&nbsp; - &nbsp;<?php echo $rows->sec_name; ?></option>
                                       <?php      } ?>
                                    </select>
                                 </div>
                              </div>
                           </fieldset>
                           <fieldset>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">Select Day</label>
                                 <div class="col-sm-3">
                                    <select   name="day_id" id="day_id"  data-title="Select Days" class="selectpicker" data-style="btn-block"  data-menu-style="dropdown-blue">
                                       <?php foreach ($get_all_days as $rows_days) {  ?>
                                  <option value="<?php echo $rows_days->d_id; ?>"><?php echo $rows_days->list_day; ?></option>
                                       <?php      } ?>
                                    </select>
                                 </div>
                              </div>
                           </fieldset>
                           <fieldset>
                              <div class="form-group">
                                 <label class="col-sm-2 control-label">&nbsp;</label>
                                 <div class="col-sm-10">
                                    <center>   <button type="submit" class="btn btn-info btn-fill col-md-2" style="padding:10px;">Add Timetable here</button></center>
                                 </div>
                              </div>
                           </fieldset>
                          </div>


                     </form>
                  </div>
               </div>
            </div>
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

      $('#timetableform').validate({ // initialize the plugin
          rules: {

              period_id:{required:true },
              class_id:{required:true },
              term_id:{required:true },
              year_id:{required:true }
          },
          messages: {

                period_id: "Select Period",
                class_id: "Select Class",
                year_id: "Select Year",
                term_id: "Select Term"

              }
      });
        });



</script>
