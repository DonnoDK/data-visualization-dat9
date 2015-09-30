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
    <link href="css/app.css" rel="stylesheet">
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

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
                        <div id="selected-case" data-selected-person-id="-1" data-selected-person-selected-case="-1" class="nav navbar-nav" title="Currently selected test person">
                            <img src="images/none.png">
                            <span> None Selected </span>          
                        </div>
                    </li>
                    <li style="padding-left:60px;">&nbsp;</li>
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Test People <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <?php
                            foreach($testPersons as $person){
                                print '<li class="test-persons"><a href="#" data-person-id="'. $person->id .'" data-person-name="'. $person->name .'"><img class="test-person-image" src="images/'. $person->name .'.jpg">'. $person->name .'<span><i> ( '. count($person->test_case) .' )</i></span></a></li>';
                            }
                        ?>
                      </ul>
                    </li>
                    <li>
                        <a href="test/">Test</a>
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
    <div class="container-fluid" id='test-cases-ui'>
        <div class="row">
            <div class="col-lg-4 col-lg-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <img class="test-person-image" src="https://scontent-arn2-1.xx.fbcdn.net/hphotos-xta1/v/t1.0-9/5385_10201055577848636_1890990129_n.jpg?oh=7672a475f9c9b212e95e590a9806b27c&oe=569D8523">
                        Anders  
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Created at:</td>
                                    <td id="person-meta-created">29-09-2015</td>
                                </tr>                              
                                <tr>
                                    <td>Name:</td>
                                    <td id="person-meta-name">Anders</td>
                                </tr>
                                <tr>
                                    <td>Age:</td>
                                    <td id='person-meta-age'>24</td>
                                </tr>
                                <tr style="border-bottom: 0px;">
                                    <td>Occupation:</td>
                                    <td id='person-meta-occupation'>Student</td>
                                </tr>                              
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Tests
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <th>Date of test:</th>
                                <th>Duration</th>
                                <th></th>
                            </thead>
                            <tbody id="person-meta-tests">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>                                    
        </div>
    </div>
    <!-- Page Content -->
    <div class="container-fluid" style="margin: 10px; overflow-x: hidden;">
            <div class="row-fluid">
                <div class="col-lg-12">
                    <div class="col-lg-10">
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
                                <div id="eeg-chart" style="width: 100%;height:250px;">
                                </div>
                            </div>
                            <div id="tooltip" style="position: absolute; border: 1px solid rgb(255, 221, 221); padding: 2px; opacity: 0.8; top: 470px; left: 470px; display: none; background-color: rgb(255, 238, 238);">cos(x) of 3.00 = -0.99</div>
                        <!-- /.panel-body -->
                        </div>
                    </div>  
                    <div class="col-lg-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-bar-chart-o fa-fw"></i>Data Sets
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body" id="filters_body">
                                <div>
                                <table class="table" id="eeg_filters_table">
                                    <tr>
                                        <td>AF3</td>
                                        <td><input type="checkbox" name="AF3" value="1"></td>
                                    </tr>
                                    <tr>
                                        <td>AF4</td>
                                        <td><input type="checkbox" name="AF4" value="14"></td>
                                    </tr>
                                    <tr>
                                        <td>FC5</td>
                                        <td><input type="checkbox" name="FC5" value="4"></td>
                                    </tr>                                        
                                    <tr>
                                        <td>FC6</td>
                                        <td><input type="checkbox" name="FC6" value="11"></td>
                                    </tr>
                                    <tr>
                                        <td>F3</td>
                                        <td><input type="checkbox" name="F3" value="3"></td>
                                    </tr>
                                    <tr>
                                        <td>F4</td>
                                        <td><input type="checkbox" name="F4" value="12"></td>
                                    </tr>
                                    <tr>
                                        <td>F7</td>
                                        <td><input type="checkbox" name="F7" value="2"></td>
                                    </tr>
                                    <tr>
                                        <td>F8</td>
                                        <td><input type="checkbox" name="F8" value="13"></td>
                                    </tr>
                                    <tr>
                                        <td>T7</td>
                                        <td><input type="checkbox" name="T7" value="5"></td>
                                    </tr>
                                    <tr>
                                        <td>T8</td>
                                        <td><input type="checkbox" name="T8" value="10"></td>
                                    </tr>
                                    <tr>
                                        <td>P7</td>
                                        <td><input type="checkbox" name="P7" value="6"></td>
                                    </tr>
                                    <tr>
                                        <td>P8</td>
                                        <td><input type="checkbox" name="P8" value="9"></td>
                                    </tr>
                                    <tr>
                                        <td>O1</td>
                                        <td><input type="checkbox" name="O1" value="7"></td>
                                    </tr>
                                    <tr>
                                        <td>O2</td>
                                        <td><input type="checkbox" name="O2" value="8"></td>
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
                            <div class="pull-right">
                                <button type="button" class="btn btn-default btn-xs" id="btn-fft-graph">
                                    FFT Graph
                                </button>
                                <button type="button" class="btn btn-default btn-xs" id="btn-band-abs">
                                    Freq. Band (Abs)
                                </button>
                                <button type="button" class="btn btn-default btn-xs" id="btn-band-rel">
                                    Freq. Band (Rel)
                                </button>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body" id="fft">
                            <div id="fft-chart" style="width:100%;height:250px;"></div>
                            <div id="frequency-band-absolute-chart" style="width:100%;height:250px;"></div>
                            <div id="frequency-band-relative-chart" style="width:100%;height:250px;"></div>
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
                        <div class="panel-body" id="test-data">
                            <div id="test-data-chart" style="width:100%;height:250px;"></div>
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
    <script src="js/complex_array.js"></script>
    <script src="js/fft.js"></script>
    <script src="js/myFFT.js"></script>
    <script src="js/core.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.mCustomScrollbar.min.js"></script>
    <script src="js/jquery.mousewheel-3.0.6.min.js"></script>
    <script src="js/jquery.flot.axes.js"></script>
    <script>
    (function($){
        $(window).load(function(){
            $("#filters_body").mCustomScrollbar();
        });
    })(jQuery);
    </script>
</body>

</html>
