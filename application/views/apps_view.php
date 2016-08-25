<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url('assets/favicon-32x32.png') ?>">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Apps Data</title>

        <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/navbar.css') ?>" rel="stylesheet">

    </head>
    <body>

        <div class="container">

            <!-- Static navbar -->
            <?php $this->load->view ('navbar'); ?>
            
            <h3>Apps Data</h3>
            <br />
            <button class="btn btn-success" onclick="add_apps()"><i class="glyphicon glyphicon-plus"></i> Add Apps</button>
            <br />
            <br />
            <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Apps Name</th>
                        <th>Apps URL</th>
                        <th>Bundle ID</th>
                        <th>Pem File</th>
                        <th style="width:150px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>

                <tfoot>
                    <tr>
                        <th>Apps Name</th>
                        <th>Apps URL</th>
                        <th>Bundle ID</th>
                        <th>Pem File</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <script src="<?php echo base_url('assets/jquery/jquery-2.2.2.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>

        <?php $class = strtolower($this->router->fetch_class()); ?>
        <script type="text/javascript">
                
                var save_method; //for save method string
                var table;
                $(document).ready(function () {
                    table = $('#table').DataTable({
                        "processing": true, //Feature control the processing indicator.
                        "serverSide": true, //Feature control DataTables' server-side processing mode.

                        // Load data for the table's content from an Ajax source
                        "ajax": {
                            "url": "<?php echo site_url($class.'/ajax_list') ?>",
                            "type": "POST"
                        },
                        //Set column definition initialisation properties.
                        "columnDefs": [
                            {
                                "targets": [-1], //last column
                                "orderable": false, //set not orderable
                            },
                        ],
                    });
                });

                function add_apps()
                {
                    save_method = 'add';
                    $('#form')[0].reset(); // reset form on modals
                    $('#modal_form').modal('show'); // show bootstrap modal
                    $('.modal-title').text('Add Apps'); // Set Title to Bootstrap modal title
                }

                function edit_apps(id)
                {
                    save_method = 'update';
                    $('#form')[0].reset(); // reset form on modals

                    //Ajax Load data from ajax
                    $.ajax({
                        url: "<?php echo site_url($class.'/ajax_edit/') ?>/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function (data)
                        {

                            $('[name="_id"]').val(data._id);
                            $('[name="apps_name"]').val(data.apps_name);
                            $('[name="apps_url"]').val(data.apps_url);
                            $('[name="bundle_id"]').val(data.bundle_id);
                            $('[name="pem_file"]').val(data.pem_file);

                            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                            $('.modal-title').text('Edit Apps'); // Set title to Bootstrap modal title

                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            alert('Error get data from ajax');
                        }
                    });
                }

                function reload_table()
                {
                    table.ajax.reload(null, false); //reload datatable ajax
                }

                function save()
                {
                    var url;
                    if (save_method == 'add')
                    {
                        url = "<?php echo site_url($class.'/ajax_add') ?>";
                    } else
                    {
                        url = "<?php echo site_url($class.'/ajax_update') ?>";
                    }

                    var formData = new FormData(); 
                    formData.append('_id', $('#form')[0]._id.value);
                    formData.append('apps_name', $('#form')[0].apps_name.value);
                    formData.append('apps_url', $('#form')[0].apps_url.value);
                    formData.append('bundle_id', $('#form')[0].bundle_id.value);
                    formData.append('pem_file', $('#form')[0].pem_file.files[0]);
                    
                    // ajax adding data to database
                    $.ajax({
                        url: url,
                        type: "POST",
                        //data: $('#form').serialize(),
                        //dataType: "JSON",
                        data: formData,
                        processData: false,
                        contentType: false,
                        enctype: 'multipart/form-data',
                        success: function (data)
                        {
                            //if success close modal and reload ajax table
                            $('#modal_form').modal('hide');
                            reload_table();
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            alert('Error adding / update data');
                        }
                    });
                }

                function delete_apps(id)
                {
                    if (confirm('Are you sure delete this data?'))
                    {
                        // ajax delete data to database
                        $.ajax({
                            url: "<?php echo site_url($class.'/ajax_delete') ?>/" + id,
                            type: "POST",
                            dataType: "JSON",
                            success: function (data)
                            {
                                //if success reload ajax table
                                $('#modal_form').modal('hide');
                                reload_table();
                            },
                            error: function (jqXHR, textStatus, errorThrown)
                            {
                                alert('Error delete data');
                            }
                        });

                    }
                }

        </script>

        <!-- Bootstrap modal -->
        <div class="modal fade" id="modal_form" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Apps Form</h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" id="form" class="form-horizontal">
                            <input type="hidden" value="" name="_id"/>
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Apps Name</label>
                                    <div class="col-md-9">
                                        <input name="apps_name" placeholder="Apps Name" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Apps URL</label>
                                    <div class="col-md-9">
                                        <select name="apps_url" class="form-control">
                                            <option value="api.push.apple.com">api.push.apple.com</option>
                                            <option value="api.development.push.apple.com">api.development.push.apple.com</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Bundle ID</label>
                                    <div class="col-md-9">
                                        <input name="bundle_id" placeholder="Bundle ID" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Pem File</label>
                                    <div class="col-md-9">
                                        <input type="file" class="form-control" name="pem_file">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- End Bootstrap modal -->
    </body>
</html>