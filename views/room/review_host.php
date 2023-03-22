{{ view("includes/head", $data); }}

<body>
    {{ view("includes/header", $data); }}
    {{ view("includes/sidebar", $data); }}

    <div class="content">
        <div class="page-title">
            <h3>Review Host</h3>
            <p>You can watch screen of this PC : <span style="color:red">{{$host->name}}</span></p>
        </div>

        <div class="row">

            <div class="col-md-12">
                <div class="light-card table-responsive p-b-3em">
                    <img src="" id="screen_pc" style="width: 80%">
                </div>
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

        setInterval(get_latest_file, 500);
        //get_latest_file();
        function get_latest_file() {
            $.ajax({
                url: '/room/get_lastest_host_file_ajax', // sending ajax request to this url
                type: 'post',
                data: {
                    'csrf-token': '{{csrf_token()}}',
                    'host_name': '{{$host->name}}'
                },
                success: function(response) {
                    console.log(response);
                    $("#screen_pc").attr("src", response);
                }
            });
        }

        let baseUrl = '<?= url(""); ?>';
        let csrf = '<?= csrf_token(); ?>';
    </script>
    <script src="<?= url(""); ?>assets/js/room.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
</body>

</html>