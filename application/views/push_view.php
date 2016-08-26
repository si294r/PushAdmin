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

        <title>Push Data</title>

        <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/navbar.css') ?>" rel="stylesheet">

        <style>
            .form-group-view { }
        </style>
    </head>
    <body>

        <div class="container">

            <!-- Static navbar -->
            <?php $this->load->view('navbar'); ?>

            <h3>Push Data</h3>
            <br />
            <button class="btn btn-success" onclick="add_push()"><i class="glyphicon glyphicon-plus"></i> Add Push</button>
            <br />
            <br />
            <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Apps Name</th>
                        <th>Message</th>
                        <th>Message Date</th>
                        <th>Sent Status</th>
                        <th>Sent Date</th>
                        <th style="width:150px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>

                <tfoot>
                    <tr>
                        <th>Apps Name</th>
                        <th>Message</th>
                        <th>Message Date</th>
                        <th>Sent Status</th>
                        <th>Sent Date</th>
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
                            "url": "<?php echo site_url($class . '/ajax_list') ?>",
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

                function add_push()
                {
                    save_method = 'add';
                    $('#form')[0].reset(); // reset form on modals
                    $('#modal_form').modal('show'); // show bootstrap modal
                    $('.modal-title').text('Add Push'); // Set Title to Bootstrap modal title
                    $('.form-control').attr('readonly', false);
                    $('.form-group-view').hide();
                    $('#btnSave').show();
                }

                function view_push(id)
                {
                    save_method = 'update';
                    $('#form')[0].reset(); // reset form on modals

                    //Ajax Load data from ajax
                    $.ajax({
                        url: "<?php echo site_url($class . '/ajax_edit/') ?>/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function (data)
                        {

                            $('[name="_id"]').val(data._id);
                            $('[name="apps_name"]').val(data.apps_name);
                            $('[name="device_token"]').val(data.device_token);
                            $('[name="message"]').val(data.message);
                            $('[name="message_date"]').val(data.message_date);
                            $('[name="sent_status"]').val(data.sent_status);
                            $('[name="sent_date"]').val(data.sent_date);
                            $('[name="sent_by"]').val(data.sent_by);
                            $('[name="sent_result"]').val(data.sent_result);

                            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                            $('.modal-title').text('View Push'); // Set title to Bootstrap modal title
                            $('.form-control').attr('readonly', true);
                            $('.form-group-view').show();
                            $('#btnSave').hide();

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
                        url = "<?php echo site_url($class . '/ajax_add') ?>";
                    } else
                    {
                        url = "<?php echo site_url($class . '/ajax_update') ?>";
                    }

                    // ajax adding data to database
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: $('#form').serialize(),
                        dataType: "JSON",
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

                function readd_push(id)
                {
                    $.ajax({
                        url: "<?php echo site_url($class . '/ajax_readd') ?>/" + id,
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
                            alert('Error re-adding data');
                        }
                    });
                }
                
                function delete_push(id)
                {
                    if (confirm('Are you sure delete this data?'))
                    {
                        // ajax delete data to database
                        $.ajax({
                            url: "<?php echo site_url($class . '/ajax_delete') ?>/" + id,
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
                        <h3 class="modal-title">Push Form</h3>
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
                                    <label class="control-label col-md-3">Device Token</label>
                                    <div class="col-md-9">
                                        <input name="device_token" placeholder="Device Token" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Message</label>
                                    <div class="col-md-9">
                                        <input name="message" placeholder="Message" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="form-group form-group-view">
                                    <label class="control-label col-md-3">Message Date</label>
                                    <div class="col-md-9">
                                        <input name="message_date" placeholder="Message Date" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="form-group form-group-view">
                                    <label class="control-label col-md-3">Sent Status</label>
                                    <div class="col-md-9">
                                        <input name="sent_status" placeholder="Sent Status" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="form-group form-group-view">
                                    <label class="control-label col-md-3">Sent Date</label>
                                    <div class="col-md-9">
                                        <input name="sent_date" placeholder="Sent Date" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="form-group form-group-view">
                                    <label class="control-label col-md-3">Sent By</label>
                                    <div class="col-md-9">
                                        <input name="sent_by" placeholder="Sent By" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="form-group form-group-view">
                                    <label class="control-label col-md-3">Sent Result</label>
                                    <div class="col-md-9">
                                        <textarea name="sent_result" style="height:100px" placeholder="Sent Result"class="form-control"></textarea>
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