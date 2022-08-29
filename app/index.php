<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        .blink_me {
            animation: blinker 2s linear infinite;
            width: 10px;
            height: 10px;
            display: inline-block;
            border: 1px solid #198754;
            background-color: #198754;
            border-radius: 100%;
            opacity: 0.3;
        }

        @keyframes blinker {
            50% {
                opacity: 0.1;
            }
        }

        .card-bg {
            background-image: url(/assets/grid.svg);
            background-repeat: no-repeat;
            background-size: 55%;
            background-position: center;
            background-position-x: 120%;
        }
    </style>
</head>
<body>
<!--<canvas id="myChart" width="400" height="400"></canvas>-->

<?php
include "_partials/header.html";
?>
<div class="content">
    <div class="container">
        <div class="row my-4">
            <h2>Home</h2>
            <div class="col-lg-4 col-sm-12">
                <div class="card">
                    <div class="card-body card-bg" style="background-image: url('/assets/grid.svg')">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5>Home</h5>
                                <h2 class="number-font text-danger">0.87 kW</h2>
                                <span class="text-secondary">0 kW Solar</span>
                                <br>
                                <span class="text-secondary text-sm"><span class="blink_me me-1"></span>16:47:45</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row my-4">
            <h2>The swarm</h2>

            <div class="col-lg-4 col-sm-12">
                <div class="card">
                    <div class="card-body card-bg" style="background-image: url('/assets/solar.svg')">
                        <div class="d-flex justify-content-between">
                            <div class="">
                                <h5>Eikenstraat</h5>
                                <h2 class="number-font text-success">0.87 kW</h2>
                                <span class="text-secondary">0 kW Solar</span>
                                <br>
                                <span class="text-secondary text-sm"><span class="blink_me me-1"></span>16:47:45</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>





    </div>
</div>

<?php
include "_partials/footer.html";
?>
</body>
</html>