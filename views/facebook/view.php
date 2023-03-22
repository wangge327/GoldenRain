{{ view("includes/head", $data); }}

<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title">
            <div class="pull-right page-actions lower">
                @if ( count($fb_user) > 0 )
                <label class="color-red">You have already set Facebook account for posting.</label><br>
                <label class="color-red">If you set another account please remove current facebook account.</label>
                @else
                <a class="btn btn-primary" onclick="fb_login()">
                    <i class="ion-plus-round"></i> Add Room
                </a>
                @endif
            </div>
            <h3>Room Mangement</h3>
            <p>Please review Rooms</p>
        </div>
        <div class="row margin-0">
            <div class="col-md-12" style="padding:0">
                <div class="light-card table-responsive p-b-3em">
                    <table class="table display companies-list" id="data-table">
                        <thead>
                            <tr>
                                <th>Room ID</th>
                                <th>Room Alias</th>
                                <th>Created</th>
                                <th class="text-center w-70">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($fb_user) > 0 )
                            @foreach ( $fb_user as $index => $each_fb_user )
                            <tr>
                                <td><strong>{{ $each_fb_user->fb_id }}</strong> </td>
                                <td><strong>{{ $each_fb_user->fb_name }}</strong> </td>
                                <td><strong>{{ $each_fb_user->fb_email }}</strong> </td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <span class="company-action dropdown-toggle" data-toggle="dropdown"><i class="ion-ios-more"></i></span>
                                        <ul class="dropdown-menu" role="menu">
                                            <li role="presentation">
                                                <a class="fetch-display-click" data="fineid:{{ $fine_fee->id }}|csrf-token:{{ csrf_token() }}" url="<?= url("Facebook@updateview"); ?>" holder=".update-holder" modal="#update" href="">Edit</a>
                                                <a class="send-to-server-click" data="tbid:{{ $each_fb_user->id }}|csrf-token:{{ csrf_token() }}" url="<?= url("Facebook@delete"); ?>" warning-title="Delete Room" warning-message="Are you sure delete" warning-button="Continue" loader="true" href="">Delete</a>
                                            </li>
                                        </ul>
                                    </div>
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


    <!-- footer -->
    {{ view("includes/footer"); }}

    <?php $url_para = explode("/", $_SERVER['REQUEST_URI']); ?>
    <script>
        var controller_name = "<?php echo $url_para[1] ?>";
        if (controller_name == "facebook") {
            $(".fb-submenu").addClass("pushy-submenu-open");
        }

        $(document).ready(function() {
            $('#data-table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5'
                ]
            });
        });
    </script>


</body>

</html>