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
                  <div class="card">
                     <form method="post" action="" class="form-horizontal" enctype="multipart/form-data" id="timetableform">
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
                                    <input type="hidden" name="class_id"  value="<?php  echo $class_id; ?>">
                                    <input type="hidden" name="term_id"  value="<?php  echo $term_id; ?>">
                                    <input type="hidden" name="day_id"  value="<?php  echo $day_id; ?>">
                                 </div>

                              </div>
                           </fieldset>
                           <fieldset>
                               <div class="form-group">
                                  <label class="col-sm-2 control-label">Selected Day</label>
                                 <div class="col-sm-3">
                                     <input type="text" name="day_name" class="form-control"   value="<?php  echo $day_name; ?>" readonly="">
                                 </div>
                               </div>

                           </fieldset>


                          </div>
                        <div id="addrows"></div>
                      <div class="input_fields_wrap" id="input_fields_wrap">

                      <br>
                      </div>

                        <fieldset>
                           <div class="form-group">
                              <label class="col-sm-2 control-label">&nbsp;</label>
                              <div class="col-sm-10">
                                 <center>    <a href="#myModal" data-toggle="modal" data-target="#myModal" style="border: 1px solid;">Add More Periods</a></center>
                              </div>
                           </div>
                        </fieldset>
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
</style>
<script type="text/javascript">
function check_from_to_time(sel){
  var startTime = $('#from_time'+sel+'').val();
  var endTime = $('#to_time'+sel+'').val();
 alert(endTime);

        var st = minFromMidnight(startTime);
         var et = minFromMidnight(endTime);
         if (st > et) {
             alert('End time always greater then start time.');
             return false;
         }

         function minFromMidnight(tm) {
             var ampm = tm.substr(-2);
             var clk;
             if (tm.length <= 6) {
                 clk = tm.substr(0, 4);
             } else {
                 clk = tm.substr(0, 5);
             }
             var m = parseInt(clk.match(/\d+$/)[0], 10);
             var h = parseInt(clk.match(/^\d+/)[0], 10);
             h += (ampm.match(/pm/i)) ? 12 : 0;
             return h * 60 + m;
         }
}

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
$(document).ready(function() {




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
           //alert("hi");
           swal({
                         title: "Are you sure?",
                         text: "You Want Confirm this form",
                         type: "success",
                         showCancelButton: true,
                         confirmButtonColor: '#DD6B55',
                         confirmButtonText: 'Yes, I am sure!',
                         cancelButtonText: "No, cancel it!",
                         closeOnConfirm: false,
                         closeOnCancel: false
                     },
                     function(isConfirm) {
                         if (isConfirm) {
          $.ajax({
              url: "<?php echo base_url(); ?>timetable/create_timetable_for_class",
               type:'POST',
              data: $('#add_periods').serialize(),
              success: function(response) {
           alert(response);
                  if(response=="success"){
                     location.reload();
                     $("#myModal").modal('show');
                   //  swal("Success!", "Thanks for Your Note!", "success");
                     $('#add_periods')[0].reset();

                  }else{
                    sweetAlert("Oops...", "Something went wrong!", "error");
                  }
              }
          });
        }else{
            swal("Cancelled", "Process Cancel :)", "error");
        }
      });
   }

     });


 var max_fields      = 10; //maximum input boxes allowed
 var wrapper         = $(".input_fields_wrap"); //Fields wrapper
 var add_button      = $(".add_field_button"); //Add button ID

 var x = 0; //initlal text box count
 $(add_button).click(function(e){ //on add input button click
     e.preventDefault();
     if(x < max_fields){ //max input box allowed
         x++; //text box increment


  // $(wrapper).append('<fieldset><div class="form-group"><label class="col-sm-1 control-label">Period</label><div class="col-md-1"><input type="checkbox" class="" id="break_id'+x+'" onclick="setbreak('+x+')" name="is_break" value="1">Break </div><div class="col-sm-2 clockpicker" data-placement="left" data-align="top" data-autoclose="true"><input type="text" class="form-control"  name="from_time[]" id="from_time'+x+'"  placeholder="From Time" required></div><div class="col-sm-2"><input type="text" class="form-control " name="to_time['+x+']" id="to_time'+x+'"  placeholder="To time" onblur="check_from_to_time('+x+')"></div><div class="col-sm-2"><select id="subject_id'+x+'" name="subject_id['+x+']" class="subject_id selectpicker"></select></div><div class="col-sm-2"><select id="teacher_id'+x+'" name="teacher_id['+x+']" class="teacher_id selectpicker"></select></div></div><a href="#" class="remove_field">Remove</a></fieldset>'); //add input box


     }
 });

 $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
     e.preventDefault(); $(this).parent('fieldset').remove(); x--;
     })

});
function setbreak(sel){
if(document.getElementById('break_id'+sel+'').checked) {
    $('#subject_id'+sel+'').hide();
    $('#teacher_id'+sel+'').hide();
} else {
    $('#subject_id'+sel+'').show();
    $('#teacher_id'+sel+'').show();
}


}
$('#from_time').clockpicker({ placement: 'top', align: 'left',twelvehour: true, donetext: 'Done'});
$('#to_time').clockpicker({ placement: 'top', align: 'left',twelvehour: true, donetext: 'Done'});

   function getSubject(){
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





</script>
