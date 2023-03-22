<?php

use Simcify\Router;
use Simcify\Exceptions\Handler;
use Simcify\Middleware\Authenticate;
use Simcify\Middleware\RedirectIfAuthenticated;
use Pecee\Http\Middleware\BaseCsrfVerifier;

/**
 * ,------,
 * | NOTE | CSRF Tokens are checked on all PUT, POST and GET requests. It
 * '------' should be passed in a hidden field named "csrf-token" or a header
 * Should be passed in a hidden field named "csrf-
 *          (in the case of AJAX without credentials) called "X-CSRF-TOKEN"
 *  */
Router::csrfVerifier(new BaseCsrfVerifier());

// Router::group(['prefix' => '/signer'], function() {

Router::group(['exceptionHandler' => Handler::class], function () {

    Router::group(['middleware' => [Simcify\Middleware\Authenticate::class, \Simcify\Middleware\FilterRequestParameters::class]], function () {

        /**
         *  login Required pages
         **/

        Router::get('/', 'Dashboard@get');

        //db test
        Router::get('/db/get_balance', 'DbControl@GetBalance');
        Router::get('/db/add_order_and_balancehistory/{user_id}', 'DbControl@addOrderAndBalancehistory');
        Router::get('/db/remove_user/{user_id}', 'DbControl@removeUser');

        // Notifications
        Router::get('/notifications', 'Notification@get');
        Router::post('/notifications/read', 'Notification@read');
        Router::post('/notifications/count', 'Notification@count');
        Router::post('/notifications/delete', 'Notification@delete');

        // Documents
        Router::get('/documents', 'Document@get');
        Router::get('/document/{docId}/download', 'Document@download', ['as' => 'docId']);
        Router::get('/document/{document_key}', 'Document@open');
        Router::post('/documents/sign', 'Document@sign');
        Router::post('/documents/send', 'Document@send');
        Router::post('/documents/fetch', 'Document@fetch');
        Router::post('/documents/delete', 'Document@delete');
        Router::post('/documents/restore', 'Document@restore');

        Router::post('/documents/protect', 'Document@protect');
        Router::post('/documents/replace', 'Document@replace');
        Router::post('/documents/relocate', 'Document@relocate');
        Router::post('/documents/duplicate', 'Document@duplicate');
        Router::post('/documents/upload/file', 'Document@uploadfile');
        Router::post('/documents/update/file', 'Document@updatefile');
        Router::post('/documents/update/file/access', 'Document@updatefileaccess');
        Router::post('/documents/update/file/acess/view', 'Document@updatefileaccessview');
        Router::post('/documents/delete/file', 'Document@deletefile');
        Router::post('/documents/create/folder', 'Document@createfolder');
        Router::post('/documents/update/folder', 'Document@updatefolder');
        Router::post('/documents/update/folder/access', 'Document@updatefolderaccess');
        Router::post('/documents/update/folder/access/view', 'Document@updatefolderaccessview');
        Router::post('/documents/update/folder/protect', 'Document@updatefolderprotect');
        Router::post('/documents/update/folder/protect/view', 'Document@updatefolderprotectview');
        Router::post('/documents/delete/folder', 'Document@deletefolder');


        Router::post('/documents/update/permissions', 'Document@permissions');

        // Templates
        Router::get('/templates', 'Template@get');
        Router::post('/templates/fetch', 'Template@fetch');
        Router::post('/templates/create', 'Template@create');
        Router::post('/templates/upload/file', 'Template@uploadfile');

        // Chat
        Router::post('/chat/post', 'Chat@post');
        Router::post('/chat/fetch', 'Chat@fetch');

        // Fields
        Router::post('/field/save', 'Field@save');
        Router::post('/field/delete', 'Field@delete');

        // Requests
        Router::get('/requests', 'Request@get');
        Router::post('/requests/send', 'Request@send');
        Router::post('/requests/delete', 'Request@delete');
        Router::post('/requests/cancel', 'Request@cancel');
        Router::post('/requests/remind', 'Request@remind');
        Router::post('/requests/decline', 'Request@decline');

        // Chat
        Router::post('/signature/save', 'Signature@save');
        Router::post('/signature/save/upload', 'Signature@upload');
        Router::post('/signature/save/draw', 'Signature@draw');

        // customers
        Router::get('/members', 'Customer@get');
        Router::get('/members/{user_id}', 'Customer@profile');
        Router::post('/members/create', 'Customer@create');
        Router::post('/members/create_employer', 'Customer@create_employer');
        Router::post('/members/create_password', 'Customer@createPassword');
        Router::post('/students/update', 'Customer@update');
        Router::post('/members/saveNote', 'Member@saveNote');

        Router::post('/members/update_specific_weekly_rate', 'Customer@updateSpecificWeeklyRate');
        Router::post('/members/update_lease_start', 'Customer@updateLeaseStartDate');
        Router::post('/members/add_amount_to_balance', 'Customer@addAmountToBalance');
        Router::post('/members/update_dashboard', 'Member@updateDashboardUser');
        Router::post('/members/update/view', 'Customer@updateview');
        Router::post('/members/room/update', 'Customer@updateRoom');
        Router::post('/members/room/view', 'Customer@assign_room');
        Router::post('/members/cancel_lease', 'Customer@cancel_lease');
        Router::post('/members/update_cancel_lease', 'Customer@updateCancelLease');

        Router::post('/members/add_fine', 'Customer@add_fine');
        Router::post('/members/fine_history', 'Customer@fine_history');

        Router::get('/members/checkout_history', 'Checkout@getCheckoutHistory');
        Router::post('/members/update_checkout', 'Checkout@finalCheckout');

        Router::post('/members/delete', 'Customer@delete');
        Router::post('/members/makeTester', 'Customer@makeTester');
        Router::post('/members/changeStatus', 'Customer@changeStatus');
        Router::post('/send_checkin', 'Customer@sendCheckin');
        Router::post('/set_arrived', 'Customer@set_arrived');
        Router::post('/resendDocusign', 'Customer@resendDocusign');
        Router::post('/send_custom_email', 'Customer@sendCustomEmail');
        Router::post('/send_all_email', 'Customer@sendAllEmail');
        Router::get('/students/remaining_balance/{user_id}', 'Checkout@remaining_balance');
        Router::post('/members/update_remaining_balance', 'Checkout@refundRemainingBalance');
        Router::post('/members/addtional_payment', 'Checkout@addtionalPayment');
        Router::post('/members/update_addtional_payment', 'Checkout@updateAddtionalPayment');
        Router::post('/members/addtional_payment_credit_card', 'Checkout@addtionalPaymentCreditCard');

        Router::get('/take_picture/{user_id}', 'Member@takePicture');
        Router::get('/take_picture_id/{user_id}', 'Member@takePictureID');
        Router::get('/upload_lease/{user_id}', 'Customer@uploadLease');
        Router::post('/update_upload_lease', 'Customer@updateUploadLease');

        //Print
        Router::get('/student/id_print/{user_id}', 'PrintController@idPrint');
        Router::get('/proof_of_address/{user_id}', 'PrintController@proofOfAddress');
        Router::get('/receipt_print/{user_id}', 'PrintController@receiptPrint');
        Router::get('/invoice_print/{invoice_id}', 'PrintController@invoicePrint');
        Router::get('/balance_history_print/{user_id}', 'PrintController@balanceHistoryPrint');

        //ZK Security
        Router::get('/zk/{user_id}', 'Zk@index');
        Router::post('/zk/{user_id}', 'Zk@index');

        // room management
        Router::get('/room/room_list', 'Room@getRoomList');
        Router::get('/room/find_hosts', 'Room@findHosts');
        Router::get('/room/review/{room_id}', 'Room@reviewRoom');
        Router::get('/room/review_host/{host_id}', 'Room@reviewHost');
        Router::post('/room/get_lastest_host_file_ajax', 'Room@getLastestHostFileAjax');
        Router::post('/room/delete', 'Room@delete');
        Router::post('/room/create', 'Room@create');
        Router::post('/room/host/change_status', 'Room@ChangeBedStatus');
        Router::post('/room/host/create', 'Room@createHost');
        Router::post('/room/host/delete', 'Room@deleteHost');
        Router::post('/room/update_host', 'Room@updateHost');
        Router::post('/room/update_view_host', 'Room@updateViewHost');
        Router::post('/room/find_add_view_host', 'Room@findAddViewHost');
        Router::get('/room/room_host_status', 'Room@roomHostStatus');
        Router::get('/room/block-list', 'Room@getBlockList');



        // Facebook
        Router::get('/facebook', 'Facebook@get');
        Router::get('/facebook/callback', 'Facebook@callback');
        Router::post('/facebook/add_fb_account', 'Facebook@addFbAccount');
        Router::post('/facebook/publish_post_view', 'Facebook@publishPostView');
        Router::post('/facebook/update', 'Facebook@update');
        Router::post('/facebook/update/view', 'Facebook@updateview');
        Router::post('/facebook/delete', 'Facebook@delete');
        Router::post('/facebook/create', 'Facebook@create');
        Router::get('/facebook/addPage', 'Facebook@addPage');
        Router::post('/facebook/addPageDB', 'Facebook@addPageDB');


        // EmailTemplate
        Router::get('/email/template', 'EmailTemplate@get');
        Router::post('/email/template/update', 'EmailTemplate@update');
        Router::post('/email/template/update/view', 'EmailTemplate@updateview');
        Router::post('/email/template/delete', 'EmailTemplate@delete');
        Router::post('/email/template/create', 'EmailTemplate@create');

        //excel
        Router::post('/students/excel', 'Member@Excel');
        Router::post('/students/importWithUnit', 'Member@importWithUnit');
        Router::get('/students_import', 'Member@get_errors');
        Router::post('/students_import_all', 'Member@ImportAll');

        // image_upload
        Router::get('/webcam', 'Customer@imageUpload');


        // Companies
        Router::get('/companies', 'Company@get');
        Router::post('/companies/update', 'Company@update');
        Router::post('/companies/update/view', 'Company@updateview');
        Router::post('/companies/delete', 'Company@delete');
        Router::post('/companies/create', 'Company@create');

        // settings
        Router::get('/settings', 'Settings@get');

        Router::get('/action_log', 'Settings@actionLog');
        Router::post('/settings/update/profile', 'Settings@updateprofile');
        Router::post('/settings/update/company', 'Settings@updatecompany');
        Router::post('/settings/update/reminders', 'Settings@updatereminders');
        Router::post('/settings/update/paymentreminders', 'Settings@updatepaymentreminders');
        Router::post('/settings/update/password', 'Settings@updatepassword');
        Router::post('/settings/synchronizeTimezone', 'Settings@synchronizeTimezone');

        // Auth
        Router::get('/signout', 'Auth@signout');
    });

    Router::group(['middleware' => [Simcify\Middleware\RedirectIfAuthenticated::class, \Simcify\Middleware\FilterRequestParameters::class]], function () {

        /**
         * No login Required pages
         **/
        Router::get('/signin', 'Auth@get');
        //            Router::get('../admin', 'Auth@get');
        Router::post('/signin/validate', 'Auth@signin');
        Router::post('/forgot', 'Auth@forgot');
        Router::get('/reset/{token}', 'Auth@getreset', ['as' => 'token']);
        Router::post('/signup', 'Auth@signup');
    });

    Router::get('/404', function () {
        response()->httpCode(404);
        echo view();
    });

    Router::get('/mailopen', 'Guest@mailopen');
    Router::get('/view/{signingKey}', 'Guest@open');

    Router::post('/guest/decline', 'Guest@decline');
    Router::post('/guest/sign', 'Guest@sign');

    Router::post('/createPassword', 'Payment@createPassword');
    Router::post('/reset_post', 'Auth@reset');

    Router::post('/requests/sendAgreement', 'Request@sendAgreement');

    Router::post('/room/getRooms', 'Room@getRooms');
    Router::post('/room/getBeds', 'Room@getBeds');

    Router::post('/settings/updateAvatar', 'Settings@updateAvatar');
    Router::post('/student/updateIDPassport', 'Member@updateIDPassport');
});
// });
