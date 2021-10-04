<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <!-- Styles -->
    <link href="/assets/css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="/assets/css/lib/themify-icons.css" rel="stylesheet">
    <link href="/assets/css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="/assets/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/lib/helper.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
    <body>
        <div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
            <div class="nano">
                <div class="nano-content">
                    <ul>
                        <div class="logo"><a href="index.html"><span>Focus</span></a></div>
                        <li class="label">Main</li>
                        <li><a href="/admin/dashboard"><i class="ti-home"></i> Dashboard </a></li>
                        <li>
                            <a class="sidebar-sub-toggle">
                                <i class="ti-home"></i> Users
                                <span class="sidebar-collapse-icon ti-angle-down"></span>
                            </a>
                            <ul>
                                <li><a href="/admin/users/create">Create</a></li>
                                <li><a href="/admin/users">List</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="sidebar-sub-toggle">
                                <i class="ti-home"></i> Tags
                                <span class="sidebar-collapse-icon ti-angle-down"></span>
                            </a>
                            <ul>
                                <li><a href="/admin/tags/create">Create</a></li>
                                <li><a href="/admin/tags">List</a></li>
                            </ul>
                        </li>
                        <li><a href="/logout"><i class="ti-close"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="float-left">
                            <div class="hamburger sidebar-toggle">
                                <span class="line"></span>
                                <span class="line"></span>
                                <span class="line"></span>
                            </div>
                        </div>
                        <div class="float-right">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-wrap">
            <div class="main">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8 p-r-0 title-margin-right">
                            
                        </div>
                        <!-- /# column -->
                        <div class="col-lg-4 p-l-0 title-margin-left">
                            <div class="page-header">
                                <div class="page-title">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Home</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <!-- /# column -->
                    </div>
                    <section id="main-content">
                        <?php echo $content; ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="footer">
                                    <p><?php echo date('Y'); ?> © Admin Board. - <a href="#">example.com</a></p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <!-- jquery vendor -->
        <script src="/assets/js/lib/jquery.min.js"></script>
        <script src="/assets/js/lib/jquery.nanoscroller.min.js"></script>
        <!-- nano scroller -->
        <script src="/assets/js/lib/menubar/sidebar.js"></script>
        <script src="/assets/js/lib/preloader/pace.min.js"></script>
        <!-- sidebar -->

        <script src="/assets/js/lib/bootstrap.min.js"></script>
        <script src="/assets/js/scripts.js"></script>
        <!-- bootstrap -->

        <script src="/assets/js/lib/circle-progress/circle-progress.min.js"></script>
        <script src="/assets/js/lib/circle-progress/circle-progress-init.js"></script>

    </body>
</html>