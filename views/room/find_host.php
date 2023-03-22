{{ view("includes/head", $data); }}

<body>
    {{ view("includes/header", $data); }}
    {{ view("includes/sidebar", $data); }}

    <div class="content">
        <div class="page-title">
            <h3>Find hosts automatically</h3>
            <p>Registered host would be excepted.</p>
        </div>

        <div class="row">

            <div class="col-md-12">
                <div class="light-card table-responsive p-b-3em">


                    <table class="table display" id="data-table">
                        <thead>
                            <tr>
                                <th>Host Name</th>
                                <th style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($dirs) > 0 )
                            @foreach ( $dirs as $index => $item )
                            <tr class="room-record">
                                <td><strong>{{ $item}}</strong></td>
                                <td>
                                    <a class="btn btn-success fetch-display-click" data="hostname:{{ $item }}|csrf-token:{{ csrf_token() }}" url="<?= url("Room@findAddViewHost"); ?>" holder=".update-holder" modal="#find-add-host" href="">Add Host</a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="9" class="text-center">It's empty here</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="find-add-host" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Host</h4>
                </div>
                <form class="update-holder simcy-form" id="update-host-form" action="<?= url("Room@createHost"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box">
                        <div class="circle-loader"></div>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}
    <?php $url_para = explode("/", $_SERVER['REQUEST_URI']); ?>
    <script>
        var controller_name = "<?php echo $url_para[1] ?>";
        if (controller_name == "room") {
            $(".rh-submenu").addClass("pushy-submenu-open");
        }


        $(document).ready(function() {

            $('#data-table').DataTable({
                dom: 'Bfrtip',
                "bSort": true,
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5'
                ]
            });


        });
        let baseUrl = '<?= url(""); ?>';
        let csrf = '<?= csrf_token(); ?>';
    </script>
    <script src="<?= url(""); ?>assets/js/room.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
</body>

</html>