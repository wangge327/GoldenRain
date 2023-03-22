{{ view("includes/head", $data); }}

<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title ">
            <div class="pull-right page-actions lower">
                <button class="btn btn-primary" data-toggle="modal" data-target="#create_host" data-backdrop="static" data-keyboard="false"><i class="ion-plus-round"></i>Create Host</button>
            </div>
            <h3>Room Information</h3>
            <p>Building: {{$building->name}}</p>
            <p>Room: {{$room->name}}</p>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="light-card table-responsive p-b-3em">
                    <table class="table display companies-list" id="data-table">
                        <thead>
                            <tr>
                                <th class="text-center w-70"></th>
                                <th>Host Name</th>
                                <th>Host Description</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($hosts) > 0 )
                            @foreach ( $hosts as $index => $host )
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><strong>{{ $host->name }}</strong></td>
                                <td><strong>{{ $host->description }} </td>
                                <td><strong>{{ $host->student_name }} </td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <span class="company-action dropdown-toggle" data-toggle="dropdown"><i class="ion-ios-more"></i></span>
                                        <ul class="dropdown-menu" role="menu">
                                            <li role="presentation">
                                                <a class="fetch-display-click" data="hostid:{{ $host->id }}|csrf-token:{{ csrf_token() }}" url="<?= url("Room@updateViewHost"); ?>" holder=".update-holder" modal="#update-host" href="">Edit</a>
                                                <a class="send-to-server-click" data="hostid:{{ $host->id }}|csrf-token:{{ csrf_token() }}" url="<?= url("Room@deleteHost"); ?>" warning-title="Are you sure?" warning-message="This Host will be deleted." warning-button="Continue" loader="true" href="">Delete</a>
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

    <!--Create Host-->
    <div class="modal fade" id="create_host" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Host</h4>
                </div>
                <form class="simcy-form" id="create-user-form" action="<?= url("Room@createHost"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Please edit host information</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 ">
                                    <label>Host name</label>
                                    <input type="text" class="form-control" id="host_name" placeholder="Host name" data-parsley-required="true" name="name">
                                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                                    <input type="hidden" name="room_id" value="{{ $room->id }}" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 ">
                                    <label>Host description</label>
                                    <textarea class="form-control" id="host_description" placeholder="Host description" name="description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Host</button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <!-- Update Host -->
    <div class="modal fade" id="update-host" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Host</h4>
                </div>
                <form class="update-holder simcy-form" id="update-host-form" action="<?= url("Room@updateHost"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box">
                        <div class="circle-loader"></div>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}

    @if ( count($employers) > 0 )
    <script>
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
    @endif
</body>

</html>