<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Biometric Data Analysis Suite</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">


</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Biometric Data Analysis Suite</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li>
                        <a href="#">Test</a>
                    </li>
                    <li>   
                        <a href="#" id="version">Last Updated: NA</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container-fluid" style="margin: 10px; overflow-x: hidden;">

        <div class="row" id="Content" style="">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fa fa-bar-chart-o fa-fw"></i>EEG data
                                    <div class="pull-right">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                Actions
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu pull-right" role="menu">
                                                <li>
                                                    <a href="#" id="fft-start-poll">Start Polling(60s)</a>
                                                </li>
                                                <li>
                                                    <a href="#" id="fft-force">Force Update</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body" id="chartbody">
                                    <div id="eeg-chart" style="width:800px;height:250px;"></div>
                                </div>
                            <!-- /.panel-body -->
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i>GSR
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body" id="chartbody">
                            <div id="eeg-chart" style="width:800px;height:250px;"></div>
                        </div>
                    <!-- /.panel-body -->
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i>FFT For Point
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body" id="chartbody">
                            <div id="eeg-chart" style="width:800px;height:250px;"></div>
                        </div>
                    <!-- /.panel-body -->
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i>Test Data
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body" id="chartbody">
                            <div id="eeg-chart" style="width:800px;height:250px;"></div>
                        </div>
                    <!-- /.panel-body -->
                    </div>
                </div>    
            </div>
        </div>
        <!-- /.row -->

    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>
    <script src="js/flot-data.js"></script>
    <script src="js/jquery.flot.js"></script>
    <script src="js/jquery.flot.navigate.js"></script>
    <script src="js/core.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
