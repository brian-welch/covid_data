

function decodeUrlSpaces(string) {
    return string.split("%20").join(" ");
}

function getParam(key) {
    let pramsArray = (window.location.search).substring(1).split("&");
    let paramJson = {};
    pramsArray.forEach(pair => {
        pair = pair.split("=");
        paramJson[pair[0]] = pair[1];
    });
    return paramJson[key];
}

function setPageName(){
    getParam('page') ? $("#page_name").html(decodeUrlSpaces(getParam('page')) + "<hr>") + $("#renderArea").html("") : null;
}

function buildArrayOfGraphs(all_initial_data) {
}

function addQueryString(uri, json = {}) {
    for (let name in json){
        let re = new RegExp("([?&])" + name + "=.*?(&|$)", "i");
        let separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            uri = uri.replace(re, '$1' + name + "=" + json[name] + '$2');
        } else {
        uri = uri + separator + name + "=" + json[name];
        }
    }
    return uri;
}

function loadGraphsFromURI() {
    if(window.location.search) {
        $.ajax({
            method: "GET",
            data: window.location.search.substr(1),//search_query,
            url: "../covid_data/lib/queries/QueryRouter.php",
        }).done(function( data ) {
            $("#renderArea").html("");
            data = JSON.parse(data);
            for (let country_id in data) {
                $("#renderArea").append(getCountryGraph(data[country_id]));
            }
        });
    }
}

function prettyPopulation(population){
    let integerArray = population.toString().split('');
    let populationString = '';
    let counter = integerArray.length;
    for (let i = 1; i <= counter; i++) {
        let temp = integerArray.pop();
        ((i % 3 === 0) && (integerArray.length > 0)) ? populationString = ',' + temp + populationString : populationString = (temp + populationString);
    }
    return populationString;
}

function getCountryGraph(countryObject) {
    return `<div class="col-xs-12 col-md-4 chart-container"><div class="chart-title">${countryObject.name} - Pop: ${prettyPopulation(countryObject.population)}</div><canvas id="myChart_${countryObject.htmlId}"  style="height: 200px;"></canvas></div><script> var chart_canvas = document.getElementById('myChart_${countryObject.htmlId}').getContext('2d'); var myChart_${countryObject.htmlId} = new Chart(chart_canvas, { type: 'line', data: { labels: [${countryObject.dates.join()}], datasets: [{ label: ' ', data: [${countryObject.dataPoints.join()}], borderColor: [ 'rgba(50, 25, 150, 1)', ], borderWidth: 1.5 }] }, options: { animation: { duration: 1000, delay: 0, }, scales: { yAxis: { beginAtZero: true, max: ${countryObject.yAxis_max}, ticks: { font: { size: 9, }, fontColor: 'rgba(50, 25, 250, 1)' }, }, xAxis: { ticks: { font: { size: 0.1, }, }, }, }, elements: { point: { pointStyle: '', radius: 0, pointBorderWidth: 2, pointBackgroundColor: ['rgba(50, 25, 150, 1)',], pointBorderColor: ['rgba(50, 25, 150, 1)',], }, line: { borderColor: [ 'rgba(0, 0, 0, 1)', ], borderJoinStyle: 'round', tension: 0, }, }, plugins: { legend: { display: false, }, }, } }); </script>`
}

function revealGraphs(graphCategory, howMany) {
    const graphArray = eval(graphCategory + "GraphArray");
    const count = (howMany === "all" ? graphArray.length : howMany);
    $('#renderArea').delay(300).queue(function (next) {
        $(this).html("");
        next();
    });
    for (let i = 0; i < count; i++){
        $('#renderArea').delay(0).queue(function (next) {
            $(this).append(graphArray[i]);
            next();
        });
    }
}

function initiateGraphRender(event){
    const graphCategory = event.currentTarget.id;
    const pageText = event.currentTarget.innerText;
    const count = 'all';
    $(this).blur();
    /* main menu query string builder*/
    let queryJson = {
        "page": pageText,
        "category": graphCategory,
        "country_ids": "all",
        "count":count,
        "id_range_start":1,
    };
    let newUrl = addQueryString(window.location.href, queryJson);
    window.history.pushState({}, '', newUrl);
    $("#page_name").html(pageText + "<hr>"), revealGraphs(graphCategory, count);
}

function loadInitialGraphData() {
    let count = 220;
    let resultsJson = {};
    let queryStringObjects = {
        casesPerMillion: `category=casesPerMillion&country_ids=all&count=${count}&id_range_start=1`,
        deathsPerMillion: `category=deathsPerMillion&country_ids=all&count=${count}&id_range_start=1`,
        mortalityRateByCases: `category=mortalityRateByCases&country_ids=all&count=${count}&id_range_start=1`,
    }
    for (let graphCategory in queryStringObjects) {
        resultsJson[graphCategory] = graphDataAjax(queryStringObjects[graphCategory]);
    }
    return resultsJson;
}

async function graphDataAjax (query_string) {
    let resultsJson = {};
    let resultsArray = [];
    const results = await $.ajax({
        method: "GET",
        data: query_string,
        url: "../covid_data/lib/queries/QueryRouter.php",
    });
    resultsJson = JSON.parse(results);
    for (const [key, value] of Object.entries(resultsJson)){
        resultsArray.push(value);
    }
    return resultsArray;
}

//            ******************
//            BELOW HERE - ASYNC
//            ******************

let initialGraphData = loadInitialGraphData();
let casesPerMillionGraphArray = [];
let deathsPerMillionGraphArray = [];
let mortalityRateByCasesGraphArray = [];

for (category in initialGraphData) {
    switch(category) {
        case 'casesPerMillion':
            initialGraphData[category].then((array) => {
                array.forEach(object => {
                    if (object['dataPoints'].length > 0) {
                        casesPerMillionGraphArray.push(getCountryGraph(object));
                    }
                });
                return casesPerMillionGraphArray;
            });
            break;
        case 'deathsPerMillion':
            initialGraphData[category].then((array) => {
                array.forEach(object => {
                    if (object['dataPoints'].length > 0) {
                        deathsPerMillionGraphArray.push(getCountryGraph(object));
                    }
                });
                return deathsPerMillionGraphArray;
            });
            break;
        case 'mortalityRateByCases':
            initialGraphData[category].then((array) => {
                array.forEach(object => {
                    if (object['dataPoints'].length > 0) {
                        mortalityRateByCasesGraphArray.push(getCountryGraph(object));
                    }
                });
                return mortalityRateByCasesGraphArray;
            })
            .then(() => console.log("Graphs Data Loaded!"))
            .then(() => {
                if(window.location.search) {
                    const category = getParam('category');
                    const count = getParam('count');
                    $("#renderArea").html("");
                    revealGraphs(category, count);
                    console.log('Graphs from URL Loaded!')
                }    
            });
            break;
        default:
            break;
    } 
}
