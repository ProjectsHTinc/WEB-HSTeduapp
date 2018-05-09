
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
            <legend>View  Class Time table  <button onclick="history.go(-1);" class="btn btn-wd btn-default pull-right" style="margin-top:-10px;">Go Back</button> </legend>
         </div>
         <div class="content">
            <div class="row">
               <div class="col-md-12">
                 <div class="col-md-12">
               <?php foreach ($getall_class as $rows) {  ?>
                 <a href="<?php echo base_url(); ?>timetable/view_day/<?php echo base64_encode($rows->class_sec_id*9876); ?>/<?php  echo $term_id=$this->uri->segment(3);   ?>"
                   class="btn btn-primary" style="width:150px;margin-bottom:10px;margin-left:15px;"><?php echo $rows->class_name; ?>&nbsp; - &nbsp;<?php echo $rows->sec_name; ?></a>
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
