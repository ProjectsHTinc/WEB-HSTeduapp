
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
