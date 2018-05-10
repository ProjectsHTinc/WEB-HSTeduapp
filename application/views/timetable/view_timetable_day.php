<style>
    td {
        text-align: center;
    }
    .box{
      background-color: red !important;
    }
</style>
<div class="main-panel">
    <div class="content">
        <div class="col-md-12">

            <div class="card">

                <?php if($this->session->flashdata('msg')): ?>
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                            ×</button>
                        <?php echo $this->session->flashdata('msg'); ?>
                    </div>

                    <?php endif; ?>

            </div>
        </div>
        <div class="content">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <legend>Time Table- view   <?php foreach($get_name_class as $rows){} echo $rows->class_name.'-'.$rows->sec_name;  ?> <button onclick="history.go(-1);" class="btn btn-wd btn-default pull-right" style="margin-top:-10px;">Go Back</button> </legend>

                    </div>
                    <center><a onclick="delte_time_table(<?php echo base64_decode($this->uri->segment(3))/9876; ?>,<?php echo base64_decode($this->uri->segment(4))/9876; ?>,<?php echo base64_decode($this->uri->segment(5)); ?>)" class=" btn btn-primary">Delete  All Periods Here</a></center>
                    <div class="content">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="example" class="table">
                                    <thead>

                                        <th data-field="id" class="text-center">S.No</th>
                                        <th data-field="name" class="text-center" data-sortable="true">From time - To time</th>
                                        <th data-field="name" class="text-center" data-sortable="true">Subject Name</th>
                                        <th data-field="name" class="text-center" data-sortable="true">Teacher Name</th>
                                        <th data-field="actions" class="td-actions text-center" data-events="operateEvents">Actions</th>
                                    </thead>
                                    <tbody>
                                        <?php $i=1; foreach ($res as $rows_time) {
                                          if($rows_time->is_break==1){
                                            echo "<tr class='box'  style='color:#fff;'>";
                                          } else{ ?>
                                            <tr class="period">
                                          <?php  } ?>

                                              <td><?php echo $i;  ?></td>
                                                <td> <?php echo  date("g:i a", strtotime($rows_time->from_time)).'-'.date("g:i a", strtotime($rows_time->to_time)); ?></td>

                                                <td> <?php if($rows_time->is_break==1){
                                                    echo "Break";
                                                    echo "<br>";
                                                    echo $rows_time->break_name;

                                                  } else{
                                                    echo $rows_time->subject_name; echo "<br>";}?></td>
                                                <td><?php echo $rows_time->name; ?>  </td>
                                                <td><a rel="tooltip" title="Edit" class="" href="<?php echo base_url(); ?>timetable/edit_time_table/<?php  echo base64_encode($rows_time->table_id*9876);  ?>/<?php echo $this->uri->segment(3); ?>/<?php echo $rows_time->list_day; ?>"><i class="fa fa-edit"></i></a></td>
                                            </tr>
                                            <?php $i++;  }  ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>

        <script type="text/javascript">
        function delte_time_table(class_id,term_id,day_id){
          swal({
                  title: "Are you sure?",
                  text: "You Want to Delete the this Timetable",
                  type: "warning",
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
                          type: "POST",
                          url: "<?php echo base_url(); ?>timetable/delete",
                          data: {
                              class_id: class_id,
                              term_id:term_id,
                              day_id:day_id
                          },
                          success: function(data) {
                              // alert(data)
                              if (data == 'success') {
                                  swal({
                                          title: "Good job",
                                          text: "Deleted Successfully!",
                                          type: "success"
                                      },
                                      function() {
                                          location.reload();
                                      }
                                  );
                              } else {
                                  sweetAlert("Oops...", "Something went wrong!", "error");
                              }
                          }
                      });

                  } else {
                      swal("Cancelled", "Process Cancel :)", "error");
                  }
              });

        }
            jQuery('#timetablemenu').addClass('collapse in');
            $('#time').addClass('active');
            $('#time2').addClass('active');
            $('#example').DataTable({

               dom: 'lBfrtip',
               buttons: [
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                        columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                        columns: ':visible'
                        }
                    },
                    'colvis'
                ],
                "pagingType": "full_numbers",
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                responsive: true,
                language: {
                search: "_INPUT_",
                searchPlaceholder: "Search records",
                }
            });


        </script>
