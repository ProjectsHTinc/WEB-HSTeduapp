<style>
   th{
   text-align: center;
   }
   td{
   text-align: center;
   }
   .subject-info-box-1,
   .subject-info-box-2 {
   float: left;
   width: 45%;
   padding-left: 30px;
   select {
   height: 200px;
   padding: 0;
   option {
   padding: 4px 10px 4px 10px;
   }
   option:hover {
   background: #EEEEEE;
   }
   }
   }
   .subject-info-arrows {
   float: left;
   width: 10%;
   input {
   width: 70%;
   margin-bottom: 5px;
   }
   }
   .modalheading{
   padding-left: 30px;
   }
   #lstBox1{
   height: 300px;
   }
   #lstBox12{
   height: 300px;
   }
   #lstBox2{
   height: 300px;
   }
   #lstBox22{
   height: 300px;
   }
</style>
<div class="main-panel">
   <div class="content">
      <?php if($this->session->flashdata('msg')): ?>
      <div class="alert alert-success">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
         ×</button> <?php echo $this->session->flashdata('msg'); ?>
      </div>
      <?php endif; ?>
      <div class="content">
         <div class="container-fluid">
            <div class="row">
                <div class="card">
              <div class="header">
                 <legend style="border-bottom:none;padding-bottom:10px;"><?php foreach ($res_group_name as $rows) {
                    } ?> <?php echo $rows->group_title; ?> - Group Members
                    <a rel="" href="#myModal" data-id="<?php echo $id; ?>" title="Add Memebers" class="open-AddBookDialog btn btn-simple  btn-fill btn-info  edit"  data-toggle="modal" data-target="#myModal"   >
                    Add Parents</a>
                    <a rel="" href="#staffmodal" data-id="<?php echo $id; ?>" title="Add Memebers" class="open-AddBookDialog btn btn-simple  btn-fill btn-info  edit"  data-toggle="modal" data-target="#staffmodal"   >
                    Add Staff</a>
                    <button onclick="history.go(-1);" class="btn btn-wd btn-default pull-right" style="margin-top:-10px;">Go Back</button>
                 </legend>
              </div>
                </div>
               <div class="col-md-6">
                  <div class="card">
                     <div class="content">
                         <p class="heading">List Of Student</p>
                        <div class="fresh-datatables1">
                           <table id="example1" class="table">
                              <thead>
                                 <th data-field="id">S.No</th>
                                 <th data-field="year"  data-sortable="true"> Name </th>
                                 <th data-field="no"  data-sortable="true">Class </th>
                                 <th data-field="Section" data-sortable="true">Action</th>
                              </thead>
                              <tbody>
                                 <?php
                                    $i=1;
                                    foreach ($res as $rows) {

                                    ?>
                                 <tr>
                                    <td><?php echo $i; ?></td>
                                    <td class="text-center"><?php echo $rows->name;?></td>
                                    <td><?php echo $rows->class_name; ?>-<?php echo $rows->sec_name; ?></td>
                                    <td>
                                       <a   onclick="delete_member(<?php echo $rows->id; ?>)" rel="tooltip" title="Remove" class="btn btn-simple btn-warning btn-icon edit"><i class="fa fa-times"></i></a>
                                    </td>
                                 </tr>
                                 <?php $i++;  }  ?>
                              </tbody>
                           </table>
                      </div>

                     </div>
                     <!-- end content-->
                  </div>
                  <!--  end card  -->
               </div>
               <div class="col-md-6">
                  <div class="card">
                     <div class="content">
                          <p class="heading">List Of Staff</p>
                          <div class="fresh-datatables">
                           <table id="example2" class="table">
                              <thead>
                                 <th data-field="id">S.No</th>
                                 <th data-field="year"  data-sortable="true"> Name </th>
                                 <th data-field="no"  data-sortable="true">Class </th>
                                 <th data-field="Section" data-sortable="true">Action</th>
                              </thead>
                              <tbody>
                                 <?php
                                    $i=1;
                                    foreach ($res_staff as $rows_staff) {

                                    ?>
                                 <tr>
                                    <td><?php echo $i; ?></td>
                                    <td class="text-center"><?php echo $rows_staff->name;?></td>
                                    <td><?php echo $rows_staff->role_name; ?></td>
                                    <td>
                                       <a   onclick="delete_member(<?php echo $rows_staff->id; ?>)" rel="tooltip" title="Remove" class="btn btn-simple btn-warning btn-icon edit"><i class="fa fa-times"></i></a>
                                    </td>
                                 </tr>
                                 <?php $i++;  }  ?>
                              </tbody>
                           </table>
                      </div>

                     </div>
                     <!-- end content-->
                  </div>
                  <!--  end card  -->
               </div>
               <div id="myModal" class="modal fade" role="dialog">
                  <div class="modal-dialog">
                     <!-- Modal content-->
                     <div class="modal-content">
                        <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal">&times;</button>
                           <h4 class="modal-title">Add Members To Group</h4>
                        </div>
                        <div class="modal-body">
                           <form action="" method="post" class="form-horizontal" id="members_adding_form">
                              <fieldset>
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label">Select Class </label>
                                    <div class="col-sm-6">
                                       <select  name="class_master_id" id="class_master_id"    data-title="Select Class" class="selectpicker" data-style=" btn-block" data-menu-style="dropdown-blue" onchange="get_student_list()">
                                          <?php foreach($res_class as $rows){ ?>
                                          <option value="<?php echo $rows->class_id; ?>"><?php echo $rows->class_name; ?>-<?php echo $rows->sec_name; ?></option>
                                          <?php    } ?>
                                       </select>
                                       <input type="hidden" name="group_id" id="group_id" class="form-control" value="">
                                       <input type="hidden" name="role_id" id="role_id" class="form-control" value="3">
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <center>List of Students to include </center>
                                    <div class="subject-info-box-1">
                                       <select multiple="multiple" id='lstBox1' class="form-control">
                                          <option value=""></option>
                                       </select>
                                    </div>
                                    <div class="subject-info-arrows text-center">
                                       <input type="button" id="btnAllRight" value=">>" class="btn btn-default" /><br />
                                       <input type="button" id="btnRight" value=">" class="btn btn-default" /><br />
                                       <input type="button" id="btnLeft" value="<" class="btn btn-default" /><br />
                                       <input type="button" id="btnAllLeft" value="<<" class="btn btn-default" />
                                    </div>
                                    <div class="subject-info-box-2">
                                       <select multiple="multiple" name="members_id[]" id='lstBox2' class="form-control">
                                       </select>
                                    </div>
                                 </div>
                                 <input type="button" id="select_all" class="pull-right" name="select_all" value="Select All">
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label">Select Status</label>
                                    <div class="col-sm-6">
                                       <select   name="status" id="status" class="form-control">
                                          <option value="Active">Active</option>
                                          <option value="Deactive">Deactive</option>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label">&nbsp;</label>
                                    <div class="col-sm-6">
                                       <button type="submit" id="save" class="btn btn-info btn-fill center">Save </button>
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
               <div id="staffmodal" class="modal fade" role="dialog">
                  <div class="modal-dialog">
                     <!-- Modal content-->
                     <div class="modal-content">
                        <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal">&times;</button>
                           <h4 class="modal-title">Add Staff To Group</h4>
                        </div>
                        <div class="modal-body">
                           <form action="" method="post" class="form-horizontal" id="adding_staff_form">
                              <fieldset>
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label">Select Role </label>
                                    <div class="col-sm-6">
                                       <select  name="role_id" id="staff_role_id"    data-title="Select Role" class="selectpicker" data-style=" btn-block" data-menu-style="dropdown-blue" onchange="get_staff_list()">
                                          <?php foreach($res_role as $rows_role){ ?>
                                          <option value="<?php echo $rows_role->role_id; ?>"><?php echo $rows_role->user_type_name; ?></option>
                                          <?php    } ?>
                                       </select>
                                       <input type="hidden" name="group_id" id="group_id" class="form-control" value="">
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <center>List of Staff to include </center>
                                    <div class="subject-info-box-1">
                                       <select multiple="multiple" id='lstBox12' class="form-control">
                                          <option value=""></option>
                                       </select>
                                    </div>
                                    <div class="subject-info-arrows text-center">
                                       <input type="button" id="btnAllRight2" value=">>" class="btn btn-default" /><br />
                                       <input type="button" id="btnRight2" value=">" class="btn btn-default" /><br />
                                       <input type="button" id="btnLeft2" value="<" class="btn btn-default" /><br />
                                       <input type="button" id="btnAllLeft2" value="<<" class="btn btn-default" />
                                    </div>
                                    <div class="subject-info-box-2">
                                       <select multiple="multiple" name="members_id[]" id='lstBox22' class="form-control">
                                       </select>
                                    </div>
                                 </div>
                                 <input type="button" id="select_all2" class="pull-right" name="select_all" value="Select All">
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label">Select Status</label>
                                    <div class="col-sm-6">
                                       <select   name="status" id="status" class="form-control">
                                          <option value="Active">Active</option>
                                          <option value="Deactive">Deactive</option>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label class="col-sm-4 control-label">&nbsp;</label>
                                    <div class="col-sm-6">
                                       <button type="submit" id="save" class="btn btn-info btn-fill center">Save </button>
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

               <!-- end col-md-12 -->

            </div>
            <!-- end row -->
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
jQuery('#groupingmenu').addClass('collapse in');
$('#grouping').addClass('active');
$('#group1').addClass('active');
   function delete_member(delete_id){
     var del_id=delete_id;
                swal({
                            title: "Are you sure?",
                            text: "You Want confirm  this form",
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
                 url: "<?php echo base_url(); ?>grouping/deleteing_member",
                  type:'POST',
                  data:{del_id:del_id},
                 success: function(response) {
                   //alert(response);
                     if(response=="success"){

                        swal({
                 title: "Wow!",
                 text: response,
                 type: "success"
             }, function() {
                location.reload();
             });
                     }else{
                       sweetAlert("Oops...",response, "error");
                     }
                 }
             });
           }else{
               swal("Cancelled", "Process Cancel :)", "error");
           }
         });
   }
      function get_student_list(){

           var class_master_id=$('#class_master_id').val();
           //alert(class_master_id);
           $.ajax({
           url:'<?php echo base_url(); ?>grouping/getListstudent',
           method:"POST",
           data:{class_master_id:class_master_id},
           dataType: "JSON",
           cache: false,
           success:function(data)
           {

           var stat=data.status;
           $("#lstBox1").empty();
           if(stat=="success"){
           var res=data.res;
           //alert(res.length);
           var len=res.length;

           for (i = 0; i < len; i++) {
           $('<option>').val(res[i].user_id).text(res[i].name).appendTo('#lstBox1');
           }

           }else{
           $("#lstBox1").empty();
           }
           }
           });
      }
      function get_staff_list(){

           var staff_role_id=$('#staff_role_id').val();
           //alert(class_master_id);
           $.ajax({
           url:'<?php echo base_url(); ?>grouping/get_staff_list',
           method:"POST",
           data:{staff_role_id:staff_role_id},
           dataType: "JSON",
           cache: false,
           success:function(data)
           {

           var stat=data.status;
           $("#lstBox12").empty();
           if(stat=="success"){
           var res=data.res;
           //alert(res.length);
           var len=res.length;

           for (i = 0; i < len; i++) {
           $('<option>').val(res[i].user_id).text(res[i].name).appendTo('#lstBox12');
           }

           }else{
           $("#lstBox12").empty();
           }
           }
           });
      }
        $('#select_all').click(function() {
               $('#lstBox2 option').prop('selected', true);
           });
      (function () {
          $('#btnRight').click(function (e) {
              var selectedOpts = $('#lstBox1 option:selected');
              if (selectedOpts.length == 0) {
                  alert("Nothing to move.");
                  e.preventDefault();
              }

              $('#lstBox2').append($(selectedOpts).clone());
              $(selectedOpts).remove();
              e.preventDefault();
          });

          $('#btnAllRight').click(function (e) {
              var selectedOpts = $('#lstBox1 option');
              if (selectedOpts.length == 0) {
                  alert("Nothing to move.");
                  e.preventDefault();
              }

              $('#lstBox2').append($(selectedOpts).clone());
                $(this).prop("selected", true);
              $(selectedOpts).remove();
              e.preventDefault();
          });

          $('#btnLeft').click(function (e) {
              var selectedOpts = $('#lstBox2 option:selected');
              if (selectedOpts.length == 0) {
                  alert("Nothing to move.");
                  e.preventDefault();
              }

              $('#lstBox1').append($(selectedOpts).clone());
              $(selectedOpts).remove();
              e.preventDefault();
          });

          $('#btnAllLeft').click(function (e) {
              var selectedOpts = $('#lstBox2 option');
              if (selectedOpts.length == 0) {
                  alert("Nothing to move.");
                  e.preventDefault();
              }

              $('#lstBox1').append($(selectedOpts).clone());
              $(selectedOpts).remove();
              e.preventDefault();
          });

      }(jQuery));


      $('#select_all2').click(function() {
             $('#lstBox22 option').prop('selected', true);
         });
    (function () {
        $('#btnRight2').click(function (e) {
            var selectedOpts = $('#lstBox12 option:selected');
            if (selectedOpts.length == 0) {
                alert("Nothing to move.");
                e.preventDefault();
            }

            $('#lstBox22').append($(selectedOpts).clone());
            $(selectedOpts).remove();
            e.preventDefault();
        });

        $('#btnAllRight2').click(function (e) {
            var selectedOpts = $('#lstBox12 option');
            if (selectedOpts.length == 0) {
                alert("Nothing to move.");
                e.preventDefault();
            }

            $('#lstBox22').append($(selectedOpts).clone());
              $(this).prop("selected", true);
            $(selectedOpts).remove();
            e.preventDefault();
        });

        $('#btnLeft2').click(function (e) {
            var selectedOpts = $('#lstBox22 option:selected');
            if (selectedOpts.length == 0) {
                alert("Nothing to move.");
                e.preventDefault();
            }

            $('#lstBox12').append($(selectedOpts).clone());
            $(selectedOpts).remove();
            e.preventDefault();
        });

        $('#btnAllLeft2').click(function (e) {
            var selectedOpts = $('#lstBox22 option');
            if (selectedOpts.length == 0) {
                alert("Nothing to move.");
                e.preventDefault();
            }

            $('#lstBox12').append($(selectedOpts).clone());
            $(selectedOpts).remove();
            e.preventDefault();
        });

    }(jQuery));

      $(document).on("click", ".open-AddBookDialog", function () {
           var eventId = $(this).data('id');
           $(".modal-body #group_id").val( eventId );
      });

      $('#members_adding_form').validate({ // initialize the plugin
        rules: {
            class_master_id:{required:true },
            "members_id[]":{required:true },
            status:{required:true},

        },
        messages: {
              class_master_id: "Select class",
              "members_id[]":"Select members",
              status:"Select Status"

            },
          submitHandler: function(form) {
            //alert("hi");

            swal({
                          title: "Are you sure?",
                          text: "You Want confirm  this form",
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
               url: "<?php echo base_url(); ?>grouping/adding_members_to_group",
                type:'POST',
               data: $('#members_adding_form').serialize(),
               success: function(response) {
                 //alert(response);
                   if(response=="success"){
                    //  swal("Success!", "Thanks for Your Note!", "success");
                      $('#members_adding_form')[0].reset();
                      swal({
               title: "Wow!",
               text: response,
               type: "success"
           }, function() {
              location.reload();
           });
                   }else{
                     sweetAlert("Oops...",response, "error");
                   }
               }
           });
         }else{
             swal("Cancelled", "Process Cancel :)", "error");
         }
       });
      }
      });

      $('#adding_staff_form').validate({ // initialize the plugin
        rules: {
            class_master_id:{required:true },
            "members_id[]":{required:true },
            status:{required:true},

        },
        messages: {
              class_master_id: "Select class",
              "members_id[]":"Select members",
              status:"Select Status"

            },
          submitHandler: function(form) {
            //alert("hi");

            swal({
                          title: "Are you sure?",
                          text: "You Want confirm  this form",
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
               url: "<?php echo base_url(); ?>grouping/adding_members_to_group",
                type:'POST',
               data: $('#adding_staff_form').serialize(),
               success: function(response) {
                 //alert(response);
                   if(response=="success"){
                    //  swal("Success!", "Thanks for Your Note!", "success");
                      $('#adding_staff_form')[0].reset();
                      swal({
               title: "Wow!",
               text: response,
               type: "success"
           }, function() {
              location.reload();
           });
                   }else{
                     sweetAlert("Oops...",response, "error");
                   }
               }
           });
         }else{
             swal("Cancelled", "Process Cancel :)", "error");
         }
       });
      }
      });
$('#example2').DataTable({	});
$('#example1').DataTable({	});


</script>
