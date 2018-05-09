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
            <legend><h4 class="modal-title">View the Day for <?php foreach($get_name_class as $rows){} echo $rows->class_name.'-'.$rows->sec_name;  ?></h4><span><button onclick="history.go(-1);" class="btn btn-wd btn-default pull-right" style="margin-top:-40px;">Go Back</button></span></legend>
         </div>
         <div class="content">
            <div class="row">
               <div class="col-md-12">
                 <div class="col-md-12">
               <?php foreach ($get_all_days as $rows) {  ?>
                 <a href="<?php echo base_url(); ?>timetable/view_timetable_day/<?php echo $this->uri->segment(3); ?>/<?php  echo $term_id=$this->uri->segment(4);   ?>/<?php echo base64_encode($rows->d_id); ?>" class="btn btn-primary"
                    style="width:150px;margin-bottom:10px;margin-left:15px;border: 1px solid;" ><?php echo $rows->list_day; ?></a>
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
     $('#timetablemenu').addClass('collapse in');
     $('#time').addClass('active');
     $('#time2').addClass('active');
</script>
