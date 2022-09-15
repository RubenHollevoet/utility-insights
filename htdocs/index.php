<!doctype html>
<html lang="en">
<head>
    <?php include "_partials/headConfig.html";?>
    <title>Document</title>

    <style>
        .blink {
            width: 10px;
            height: 10px;
            display: inline-block;
            border-radius: 100%;
            opacity: 0.3;
        }

        .blink.blink-green {
            animation: blinker 2s linear infinite;
            border: 1px solid #198754;
            background-color: #198754;
        }

        .blink.blink-red {
            border: 1px solid #af1b1b;
            background-color: #af1b1b;
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
                                <h2 class="number-font text-danger" id="home-grid">? kW</h2>
                                <span class="text-secondary" id="home-solar">? kW Solar</span>
                                <br>
                                <span class="text-secondary text-sm"><span class="blink blink-green me-1"></span><span id="home-last-update">00:00:00</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row my-4">
            <h2>The swarm</h2>
            <div class="row" id="swarm-container"></diapi/getParticipants.phpv>
        </div>

    </div>
</div>

<script>
    function loadSwarmContainer() {
        const $swarmContainer = document.getElementById('swarm-container');
        const participantTemplate =
            `<div class="card">
                <div class="card-body card-bg" style="background-image: url('/assets/solar.svg')">
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <h5>__name__</h5>
                            <h2 class="number-font text-success">0 kW</h2>
                            <span class="text-secondary">0 kW Solar</span>
                            <br>
                            <span class="text-secondary text-sm"><span class="blink blink-red me-1"></span>00:00:00</span>
                        </div>
                    </div>
                </div>
            </div>`;

        fetch('/api/getParticipants.php')
            .then(response => response.json())
            .then(data => {
                data.forEach(function (participantData) {
                    const $participant = document.createElement('div');
                    $participant.classList.add('col-lg-4', 'col-sm-12');

                    html = participantTemplate;
                    html = html.replace('__name__', participantData.name)
                    $participant.innerHTML = html;

                    $swarmContainer.appendChild($participant);
                });
            });
    }

    var intervalId = window.setInterval(function(){
        fetch('/api/getLastMeasures.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('home-grid').innerText = data['grid']['power'] + ' kW';
                document.getElementById('home-solar').innerText = data['solar']['power'] + '  kW Solar';

                let today = new Date();
                today = today.getFullYear()+'-'+('0' + (today.getMonth()+1)).slice(-2)+'-'+('0' + today.getDate()).slice(-2);
                const lastUpdate = data['solar']['dateTime'];
                const dateDisplay = today === lastUpdate.split(' ')[0] ? lastUpdate.split(' ')[1] : lastUpdate;
                document.getElementById('home-last-update').innerText = dateDisplay;
            });
    }, 5000);

    loadSwarmContainer();
</script>

<?php
include "_partials/footer.html";
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>