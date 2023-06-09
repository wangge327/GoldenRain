<?php

use Simcify\Database;

$reports_items = Database::table("report")->get();
?>
<link href="<?= url("/"); ?>assets/css/pushy.css" rel="stylesheet">
<div class="left-bar">
    <div class="slimscroll-menu">
        <li>
            <a href="<?= url("Dashboard@get"); ?>">
                <label class="menu-icon"><i class="ion-ios-speedometer"></i> </label><span class="text">仪表板</span>
            </a>
        </li>
        @if ( $user->role != "user" )
        <li><a href="<?= url("Customer@get"); ?>">
                <label class="menu-icon"><i class="ion-ios-people"></i> </label><span class="text">Members</span>
            </a>
        </li>
        <li hidden><a href="<?= url("Member@get"); ?>">
                <label class="menu-icon"><i class="ion-ios-personadd"></i> </label><span class="text">Import Errors</span>
            </a>
        </li>
        <li class="pushy-submenu rh-submenu">
            <a>
                <label class="menu-icon"><i class="ion-ios-list"></i> </label>
                <span class="text">Rooms & Hosts</span>
            </a>
            <ul>
                <li class="pushy-link">
                    <a href="<?= url("Room@getRoomList"); ?>">
                        <span class="text">Rooms</span>
                    </a>
                </li>
                <li class="pushy-link">
                    <a href="<?= url("Room@findHosts"); ?>">
                        <span class="text">Find Hosts</span>
                    </a>
                </li>
            </ul>
        </li>


        @if (env("SITE_Portal"))
        <li><a href="<?= url("Company@get"); ?>">
                <label class="menu-icon"><i class="ion-ios-flower"></i> </label><span class="text">Sponsors</span>
            </a>
        </li>
        @endif
        <li><a href="<?= url("Settings@actionLog"); ?>">
                <label class="menu-icon"><i class="ion-ios-list"></i> </label><span class="text">Action Logs</span>
            </a>
        </li>
        <li>
            <a href="<?= url("EmailTemplate@get"); ?>">
                <label class="menu-icon"><i class="ion-ios-list"></i> </label><span class="text">Email Template</span>
            </a>
        </li>

        @endif


        @if ( $user->role == "user" )



        @endif

        <li><a href="<?= url("Settings@get"); ?>">
                <label class="menu-icon"><i class="ion-gear-a"></i> </label><span class="text">设置</span>
            </a>
        </li>
    </div>
</div>