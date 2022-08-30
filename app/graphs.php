<!doctype html>
<html lang="en">
<head>
    <title>Graphs</title>
    <?php include "_partials/headConfig.html";?>
</head>
<body>

<?php include "_partials/header.html";?>

<div class="content">
    <div class="container mt-4">
        <h1>Graphs</h1>
        <form class="form-inline">
            <div class="d-flex">
                <div class="btn-group me-2">
                    <a href="?period=day" class="btn btn-outline-primary js-period-day">Dag</a>
                    <a href="?period=week" class="btn btn-outline-primary js-period-week">Week</a>
                </div>
                <div class="">
                    <input type="date" class="form-control js-date">
                </div>
            </div>
        </form>

        <div class="row mt-5">
            <div class="col-12 col-sm-12">

            </div>
        </div>

    </div>

    <figure class="highcharts-figure">
        <div id="container"></div>
    </figure>
</div>


<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/boost.js"></script>

<script>
    const searchParams = new URLSearchParams(window.location.href.substring(window.location.href.indexOf("?")+1));

    // helpers
    const formatDate = (date) => {
        let d = new Date(date);
        let month = (d.getMonth() + 1).toString();
        let day = d.getDate().toString();
        let year = d.getFullYear();
        if (month.length < 2) {
            month = '0' + month;
        }
        if (day.length < 2) {
            day = '0' + day;
        }
        return [year, month, day].join('-');
    }

    //private functions
    function updateGraph() {
        console.log(document.querySelector('.js-date').value);
        const startDate = new Date(document.querySelector('.js-date').value);
        let endDate = new Date(document.querySelector('.js-date').value);

        console.log('startDate: ', startDate);

        if('week' === searchParams.get('period')) {
            endDate.setDate(endDate.getDate() + 6);
        }

        console.log('startDate: ', startDate);
        console.log('endDate: ', endDate);
        // console.log('endDate: ', endDate.toISOStr0ing().split('T')[0]);

        urlParam = '';
        urlParam += 'from=' + formatDate(startDate) + ' 00:00:00';
        urlParam += '&to=' + formatDate(endDate) + ' 23:59:59';
        urlParam += '&maxSamples=5000';

        fetch('/api/readSampleData.php?' + urlParam)
            .then(response => response.json())
            .then(data => {
                loadGraph(data)
            });
    }

    function refreshInpuFields() {
        let date;

        if('week' === searchParams.get('period')) {
            document.querySelector('.js-period-day').classList.remove('active');
            document.querySelector('.js-period-week').classList.add('active');
            date = new Date(Date.now() - 6 * 24 * 60 * 60 * 1000)
        }
        else {
            document.querySelector('.js-period-day').classList.add('active');
            document.querySelector('.js-period-week').classList.remove('active');
            date = new Date();
        }

        document.querySelector('.js-date').valueAsDate = date;
    }

    function loadGraph(data) {
        console.log(data);
        dataPointsCount = Object.keys(data.solar).length + Object.keys(data.production).length + Object.keys(data.consumption).length;

        Highcharts.chart('container', {

            chart: {
                zoomType: 'x'
            },

            title: {
                text: 'drawing ' + dataPointsCount + ' points'
            },

            subtitle: {
                text: 'Using the Boost module'
            },

            accessibility: {
                screenReaderSection: {
                    beforeChartFormat: '<{headingTagName}>{chartTitle}</{headingTagName}><div>{chartSubtitle}</div><div>{chartLongdesc}</div><div>{xAxisDescription}</div><div>{yAxisDescription}</div>'
                }
            },

            tooltip: {
                valueDecimals: 2
            },

            xAxis: {
                type: 'datetime'
            },

            series: [
                {
                    data: data.solar,
                    lineWidth: 0.5,
                    name: 'Zonnepanelen'
                },
                {
                    data: data.production,
                    lineWidth: 0.5,
                    name: 'Injectie'
                },
                {
                    data: data.consumption,
                    lineWidth: 0.5,
                    name: 'Verbruik',
                    // visible: false,
                },
            ]
        });
    }

    refreshInpuFields();
    updateGraph();

    document.querySelector('.js-date').addEventListener('change', function   () {
        updateGraph();
    });

    document.querySelector('.js-period-day').addEventListener('click', function() {
        refreshInpuFields();
    });

    document.querySelector('.js-period-week').addEventListener('click', function() {
        refreshInpuFields();
    });

</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

<?php include "_partials/footer.html"; ?>

</body>
</html>