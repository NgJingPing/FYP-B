<?php if ($role == "Super admin" || $role == "Admin" || $role == "Security") { ?>
        <div class="navigation_bar">
            <div class="logo_container"> 
                <img src="../images/naim.png" class="naim_logo"></img>
                <div class="logo"><span class="logo_initial">V</span><span>ISION</span></div> 
                <div class="logo_tail"><span>ANPR</span></div> 
            </div>
            <div class="navigation_links_container">
                <div class="navigation_links"><a href="index.php" <?php if ($page == 'Dashboard') { echo 'class="active_page"'; } ?>><i class="fa-solid fa-house"></i>Dashboard</a></div>
                <?php if ($role == "Super admin" || $role == "Admin") { ?>
                    <div class="navigation_links"><a href="register_vehicle.php" <?php if ($page == 'Registration') { echo 'class="active_page"'; } ?>><i class="fa-solid fa-person-circle-plus"></i>Registration</a></div>
                <?php } ?>  
                <div class="navigation_links"><a href="view_vehicle.php" <?php if ($page == 'Database') { echo 'class="active_page"'; } ?>><i class="fa-solid fa-table"></i>Database</a></div>
                <div class="navigation_links drop_down_btn"><a href="#" <?php if ($page == 'Log') { echo 'class="active_page"'; } ?>><i class="fa-solid fa-clipboard-list"></i>Log<i class="fa-solid fa-angle-right"></i></a></div>
                    <div class="sub_menu">
                        <div class="navigation_links"><a href="report.php" <?php if ($subpage == 'Report') { echo 'class="active_page"'; } ?>></i>Report</a></div>
                        <div class="navigation_links"><a href="entry_log.php" <?php if ($subpage == 'Entry Log') { echo 'class="active_page"'; } ?>></i>Entry Log</a></div>
                        <div class="navigation_links"><a href="exit_log.php" <?php if ($subpage == 'Exit Log') { echo 'class="active_page"'; } ?>></i>Exit Log</a></div>
                        <div class="navigation_links"><a href="denied_access.php" <?php if ($subpage == 'Denial Log') { echo 'class="active_page"'; } ?>></i>Denial Log</a></div>
                    </div>
                <div class="navigation_links"><a href="analytic.php" <?php if ($page == 'Analytics') { echo 'class="active_page"'; } ?>><i class="fa fa-line-chart"></i>Analytics</a></div>
                <?php if($role == "Super admin") { ?>
                    <div class="navigation_links drop_down_btn"><a href="#" <?php if ($page == 'Management') { echo 'class="active_page"'; } ?>><i class="fa fa-users"></i>Management<i class="fa-solid fa-angle-right" style="margin-left: 0px; padding-left:8px;"></i></a></div>
                    <div class="sub_menu">
                        <div class="navigation_links"><a href="register_user.php" <?php if ($subpage == 'Add User') { echo 'class="active_page"'; } ?>></i>Add User</a></div>
                        <div class="navigation_links"><a href="manage_user.php" <?php if ($subpage == 'View User') { echo 'class="active_page"'; } ?>></i>View User</a></div>
                    </div>
                <?php } ?>  
                <div class="navigation_links"><a href="profile.php" <?php if ($page == 'Profile') { echo 'class="active_page"'; } ?>><i class="fa-solid fa-user"></i>Profile</a></div>
                <div class="navigation_links" id="last_nav_link"><a href="../login.php" id="last_nav_link"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></div>
            </div>
        </div>

<?php } ?>
