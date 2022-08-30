<!doctype html>
<html lang="en">
<head>
    <?php include "_partials/headConfig.html";?>
    <title>Document</title>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>