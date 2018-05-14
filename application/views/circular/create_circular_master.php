<div class="main-panel">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Create Circular Master</h4>
                        </div>
                        <div class="content">
                            <form method="post" action="<?php echo base_url(); ?>circular/add_circular_master" class="form-horizontal" enctype="multipart/form-data" id="circularmaster" name="circularmaster">
                                <fieldset>
                                    <div class="form-group">

                                        <?php  $status=$years['status']; if($status=="success"){
                                              foreach($years['all_years'] as $rows){}
                                                ?>
                                            <input type="hidden" name="year_id" value="<?php  echo $rows->year_id; ?>">
                                            <input type="hidden" name="year_name" class="form-control" value="<?php echo date('Y', strtotime($rows->from_month));  echo " - "; echo date('Y', strtotime( $rows->to_month));  ?>" readonly="">
                                            <?php   }?>

                                                <label class="col-sm-2 control-label">Circular Title</label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="ctitle" required class="form-control" />
                                                </div>
                                                <label class="col-sm-2 control-label">Status</label>
                                                <div class="col-sm-4">
                                                    <select name="status" class="selectpicker form-control" data-title="Status" data-style="btn-default btn-block" data-menu-style="dropdown-blue">
                                                        <option value="Active">Active</option>
                                                        <option value="Deactive">DeActive</option>
                                                    </select>
                                                </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"> Circular Document</label>
                                        <div class="col-sm-4">
                                          <input type="file" name="circular_doc"  class="form-control" />
                                        </div>

                                    </div>
                                </fieldset>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"> Description</label>
                                        <div class="col-sm-4">
                                            <textarea name="cdescription" MaxLength="500" placeholder="MaxLength 500" id="cdescription" class="form-control" rows="4" cols="80"></textarea>

                                        </div>

                                    </div>
                                </fieldset>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">&nbsp;</label>
                                    <div class="col-sm-4">
                                        <button type="submit" id="save" class="btn btn-info btn-fill center">Save </button>
                                    </div>
                                </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if($this->session->flashdata('msg')): ?>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    ×</button>
                <?php echo $this->session->flashdata('msg'); ?>
            </div>
            <?php endif; ?>
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="content">
                                        <div class="fresh-datatables">
                                            <table id="bootstrap-table" class="table">
                                                <thead>
                                                    <th>S.no</th>
                                                    <th>Circular Title</th>
                                                    <th style="width:700px;">Circular Description</th>
                                                    <th>Circular Doc</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </thead>
                                                <tbody>
                                                    <?php
                                    $i=1;
                                    foreach ($result as $rows) {
									$stu=$rows->status;
                                     ?>
                                                        <tr>
                                                            <td> <?php  echo $i; ?> </td>
                                                            <td><?php  echo $rows->circular_title; ?></td>
                                                            <td><?php  echo $rows->circular_description; ?></td>
                                                            <td><?php if(empty($rows->circular_doc)){

                                                            }else{ ?><a href="<?php echo base_url(); ?>assets/circular/<?php echo $rows->circular_doc; ?>" target="_blank">Download</a>

                                                          <?php   } ?></td>
                                                            <td>
                                                                <?php
									  if($stu=='Active'){?>
                                                                    <button class="btn btn-success btn-fill btn-wd">Active</button>
                                                                    <?php  }else{?>
                                                                        <button class="btn btn-danger btn-fill btn-wd">De-Active</button>
                                                                        <?php } ?>
                                                            </td>
                                                            <td>
                                                                <a href="<?php echo base_url();  ?>circular/edit_circular_master/<?php echo $rows->id; ?>" class="btn btn-simple btn-warning btn-icon edit">
                                                                    <i class="fa fa-edit"></i></a>
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
                            <!-- end col-md-12 -->
                        </div>
                        <!-- end row -->
                    </div>
                </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
      $('#communcicationmenu').addClass('collapse in');
      $('#communication').addClass('active');
      $('#communication3').addClass('active');
        $('#circularmaster').validate({ // initialize the plugin
            rules: {
                ctype: {
                    required: true
                },
                ctitle: {
                    required: true,
                    remote: {
                                 url: "<?php echo base_url(); ?>circular/check_circular_title_exist",
                                 type: "post"
                              }
                },
                cdescription: {
                    required: true
                },
                status: {
                    required: true
                },

            },
            messages: {
                ctype: "Select Circular Type",
                ctitle:{
                      required: "Please enter title.",
                      remote: "Title already exist!"
                },
                cdescription: "Enter Description",
                status: "Select Status",
            }
        });

    });


  $('#bootstrap-table').DataTable({	});
</script>
