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
            <div class="row-fluid">
                <div class="col-lg-12">
                    <div class="col-lg-10">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-bar-chart-o fa-fw"></i>EEG data
                                <div class="pull-right">
                                    <div type="button" class="btn btn-default btn-xs" id="fftBtn">
                                        Compute FFT
                                    </div>
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
                                <div id="eeg-chart" style="width: 100%;height:250px;"></div>
                            </div>
                        <!-- /.panel-body -->
                        </div>
                    </div>  
                    <div class="col-lg-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-bar-chart-o fa-fw"></i>Data Sets
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body" id="chartbody">
                                <a href="#" id="eeg_filters">Filters </a>
                                <div>
                                <table class="table" id="eeg_filters_table">
                                    <tr>
                                        <td>AF3</td>
                                        <td><input type="checkbox" name="AF3" value="4"></td>
                                    </tr>
                                    <tr>
                                        <td>AF4</td>
                                        <td><input type="checkbox" name="AF4" value="17"></td>
                                    </tr>
                                    <tr>
                                        <td>FC5</td>
                                        <td><input type="checkbox" name="FC5" value="7"></td>
                                    </tr>                                        
                                    <tr>
                                        <td>FC6</td>
                                        <td><input type="checkbox" name="FC6" value="14"></td>
                                    </tr>
                                    <tr>
                                        <td>F3</td>
                                        <td><input type="checkbox" name="F3" value="6"></td>
                                    </tr>
                                    <tr>
                                        <td>F4</td>
                                        <td><input type="checkbox" name="F4" value="15"></td>
                                    </tr>
                                    <tr>
                                        <td>F7</td>
                                        <td><input type="checkbox" name="F7" value="5"></td>
                                    </tr>
                                    <tr>
                                        <td>F8</td>
                                        <td><input type="checkbox" name="F8" value="16"></td>
                                    </tr>
                                    <tr>
                                        <td>T7</td>
                                        <td><input type="checkbox" name="T7" value="8"></td>
                                    </tr>
                                    <tr>
                                        <td>T8</td>
                                        <td><input type="checkbox" name="T8" value="13"></td>
                                    </tr>
                                    <tr>
                                        <td>P7</td>
                                        <td><input type="checkbox" name="P7" value="9"></td>
                                    </tr>
                                    <tr>
                                        <td>P8</td>
                                        <td><input type="checkbox" name="P8" value="12"></td>
                                    </tr>
                                    <tr>
                                        <td>O1</td>
                                        <td><input type="checkbox" name="O1" value="10"></td>
                                    </tr>
                                    <tr>
                                        <td>O2</td>
                                        <td><input type="checkbox" name="O2" value="11"></td>
                                    </tr>
                                </table>
                                </div>
                            </div>
                        <!-- /.panel-body -->
                        </div>
                    </div>                         
                </div>
            </div>
            <div class="row-fluid">
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i>GSR
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="gsr-chart" style="width: 100%;height:250px;"></div>
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
                            <div id="fft-chart" style="width:800px;height:250px;"></div>
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
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>
    <script src="js/flot-data.js"></script>
    <script src="js/jquery.flot.js"></script>
    <script src="js/jquery.flot.navigate.js"></script>
    <script src="js/core.js"></script>
    <script src="js/complex_array.js"></script>
    <script src="js/fft.js"></script>
    <script src="js/myFFT.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
