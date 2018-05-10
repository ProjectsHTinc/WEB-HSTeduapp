<style>
   .tablwidth{
   margin-right: 16px;
   border:1px solid grey;
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
</style>
<div class="main-panel">
   <div class="content">
      <div class="col-md-12">
         <?php if($this->session->flashdata('msg')): ?>
         <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
            ×</button> <?php echo $this->session->flashdata('msg'); ?>
         </div>
         <?php endif; ?>
      </div>

      <div class="card">
        <div class="header">
           <legend>View  Class Time table </legend>
        </div>

      <div class="content">
         <div class="row">
            <div class="col-md-12">
              <div class="col-md-4">
                <center><span class="btn btn-primary btn-day">Monday</span></center>
                <table id="" class="table" >
                   <thead>
                      <th data-field="Monday" class="text-center" data-sortable="true">Class</th>
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


                         <td>  <?php echo $i; ?>  </td>
                         <td>  <?php echo  date("g:i a", strtotime($rows->from_time)).'-'.date("g:i a", strtotime($rows->to_time));  ?></td>
                         <td>
                           <?php if($rows->is_break==1){
                           echo "Break";
                           echo "<br>";
                           echo $rows->break_name;
                         } else{
                           echo $rows->subject_name; echo "<br>";

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


                      <td>  <?php echo $i; ?>  </td>
                         <td>  <?php echo  date("g:i a", strtotime($rows->from_time)).'-'.date("g:i a", strtotime($rows->to_time));  ?></td>
                         <td>
                           <?php if($rows->is_break==1){
                           echo "Break";
                           echo "<br>";
                           echo $rows->break_name;
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


                      <td>  <?php echo $i; ?>  </td>
                         <td>  <?php echo  date("g:i a", strtotime($rows->from_time)).'-'.date("g:i a", strtotime($rows->to_time));  ?></td>
                         <td>
                           <?php if($rows->is_break==1){
                           echo "Break";
                           echo "<br>";
                           echo $rows->break_name;
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


                         <td>  <?php echo $i; ?>  </td>
                         <td>  <?php echo  date("g:i a", strtotime($rows->from_time)).'-'.date("g:i a", strtotime($rows->to_time));  ?></td>
                         <td>
                           <?php if($rows->is_break==1){
                           echo "Break";
                           echo "<br>";
                           echo $rows->break_name;
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


                         <td>  <?php echo $i; ?>  </td>
                         <td>  <?php echo  date("g:i a", strtotime($rows->from_time)).'-'.date("g:i a", strtotime($rows->to_time));  ?></td>
                         <td>
                           <?php if($rows->is_break==1){
                           echo "Break";
                           echo "<br>";
                           echo $rows->break_name;
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


                         <td>  <?php echo $i; ?>  </td>
                         <td>  <?php echo  date("g:i a", strtotime($rows->from_time)).'-'.date("g:i a", strtotime($rows->to_time));  ?></td>
                         <td>
                           <?php if($rows->is_break==1){
                           echo "Break";
                           echo "<br>";
                           echo $rows->break_name;
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
<script>
   $('#timetablemenu').addClass('collapse in');
   $('#timetable').addClass('active');
   $('#timetable2').addClass('active');

</script>
