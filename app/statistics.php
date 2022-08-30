<!doctype html>
<html lang="en">
<head>
    <?php include "_partials/headConfig.html";?>
    <title>Statistics</title>
</head>
<body>

<?php include "_partials/header.html"; ?>

<div class="content">
    <div class="container mt-4">
        <h1>Statistics</h1>
        <div class="row">
            <div class="col-md-6 col-sm-12 mb-2">
                <div class="btn-group">
                    <a href="?period=day" class="btn btn-outline-primary active">Dag</a>
                    <a href="?period=week" class="btn btn-outline-primary">Week</a>
                    <a href="?period=month" class="btn btn-outline-primary">Maand</a>
                    <a href="?period=jear" class="btn btn-outline-primary">Jaar</a>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-4 col-12 d-flex align-items-center">
                    <input type="date" class="form-control">
                    <span class="px-2">tot</span>
                    <input type="date" class="form-control">
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-6 col-sm-12">
                <h2>Verbruik</h2>
                <div>
                    <div id="legend-container-consumption"></div>
                    <canvas id="consumptionChart"></canvas>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <h2>Zonnepanelen</h2>
                <div>
                    <div id="legend-container-solar"></div>
                    <canvas id="solarChart"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

<script>
    const DATA_COUNT = 2;
    const NUMBER_CFG = {count: DATA_COUNT, min: 0, max: 100};

    const dataSolar = {
        labels: ['% eigen productie', '% net stroom'],
        datasets: [
            {
                data: [70,30],
                backgroundColor: Object.values(['green', 'orange']),
            }
        ]
    };

    const dataConsumption = {
        labels: [' % eigen verbruik', ' % geinjecteerd'],
        datasets: [
            {
                data: [55,45],
                backgroundColor: Object.values(['green', 'orange']),
            }
        ]
    };

    const centerTextPlugin = {
        id: 'add_center_text',
        beforeDraw: function(chart) {
            var width = chart.width,
                height = chart.height,
                ctx = chart.ctx;

            ctx.restore();
            var fontSize = (height / 114).toFixed(2);
            ctx.font = fontSize + "em sans-serif";
            ctx.textBaseline = "middle";

            var text = "46Kw",
                textX = Math.round((width - ctx.measureText(text).width) / 2),
                textY = height / 2;

            ctx.fillText(text, textX, textY);
            ctx.save();
        }
    };

    const getOrCreateLegendList = (chart, id) => {
        const legendContainer = document.getElementById(id);
        let listContainer = legendContainer.querySelector('ul');

        if (!listContainer) {
            listContainer = document.createElement('ul');
            listContainer.style.display = 'flex';
            listContainer.style.flexDirection = 'row';
            listContainer.style.margin = 0;
            listContainer.style.padding = 0;

            legendContainer.appendChild(listContainer);
        }

        return listContainer;
    };

    const htmlLegendPlugin = {
        id: 'htmlLegend',
        afterUpdate(chart, args, options) {
            const ul = getOrCreateLegendList(chart, options.containerID);

            // Remove old legend items
            while (ul.firstChild) {
                ul.firstChild.remove();
            }

            // Reuse the built-in legendItems generator
            const items = chart.options.plugins.legend.labels.generateLabels(chart);

            items.forEach(item => {
                const li = document.createElement('li');
                li.style.alignItems = 'center';
                li.style.cursor = 'pointer';
                li.style.display = 'flex';
                li.style.flexDirection = 'row';
                li.style.marginLeft = '10px';

                li.onclick = () => {
                    const {type} = chart.config;
                    if (type === 'pie' || type === 'doughnut') {
                        // Pie and doughnut charts only have a single dataset and visibility is per item
                        chart.toggleDataVisibility(item.index);
                    } else {
                        chart.setDatasetVisibility(item.datasetIndex, !chart.isDatasetVisible(item.datasetIndex));
                    }
                    chart.update();
                };

                // Color box
                const boxSpan = document.createElement('span');
                boxSpan.style.background = item.fillStyle;
                boxSpan.style.borderColor = item.strokeStyle;
                boxSpan.style.borderWidth = item.lineWidth + 'px';
                boxSpan.style.display = 'inline-block';
                boxSpan.style.height = '20px';
                boxSpan.style.marginRight = '10px';
                boxSpan.style.width = '20px';

                // Textparticipant-remove-name
                const textContainer = document.createElement('p');
                textContainer.style.color = item.fontColor;
                textContainer.style.margin = 0;
                textContainer.style.padding = 0;
                textContainer.style.textDecoration = item.hidden ? 'line-through' : '';

                const text = document.createTextNode(item.text);
                textContainer.appendChild(text);

                li.appendChild(boxSpan);
                li.appendChild(textContainer);
                ul.appendChild(li);
            });
        }
    };

    const configConsumption = {
        type: 'doughnut',
        data: dataConsumption,
        options: {
            responsive: true,
            plugins: {
                htmlLegend: {
                    containerID: 'legend-container-consumption',
                },
                legend: {
                    display: false,
                }
            }
        },
        plugins: [htmlLegendPlugin, centerTextPlugin]
    };

    const configSolar = {
        type: 'doughnut',
        data: dataSolar,
        options: {
            responsive: true,
            plugins: {
                htmlLegend: {
                    containerID: 'legend-container-solar',
                },
                legend: {
                    display: false,
                }
            }
        },
        plugins: [htmlLegendPlugin, centerTextPlugin]
    };

    const solarChart = new Chart(
        document.getElementById('solarChart'),
        configConsumption
    );

    const consumptionChart = new Chart(
        document.getElementById('consumptionChart'),
        configSolar
    );
</script>

<?php include "_partials/footer.html";  ?>
</body>
</html>