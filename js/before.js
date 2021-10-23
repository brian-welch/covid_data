function decodeUrlSpaces(string = '') {
    return string.split("%20").join(" ");
}

function returnToHere(x = 0) {
    window.scrollTo({
        top: x, left: 0, behavior: 'smooth'
    });
}

function getParam(key, queryString = '') {
    
    let pramsArray = queryString.length < 1 ? (window.location.search).substring(1).split("&") : queryString.substring(1).split("&");
    let paramJson = {};
    pramsArray.forEach(pair => {
        pair = pair.split("=");
        paramJson[pair[0]] = decodeUrlSpaces(pair[1]);
    });
    return paramJson[key];
}

function setPageName(){

    getParam('page') ? $("#page_name").html(decodeUrlSpaces(getParam('page')) + "<span></span>") + $("#renderArea").html("") : null;
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

function getCountryGraph(countryObject, index) {
    const graphCounter = index + 1;
    return `<div id="chartContainer_${countryObject.htmlId}" data-population="${countryObject.population}" data-healthcare-efficiency="${countryObject.healthcareEfficiencyRank}" data-country-name="${countryObject.name}" data-highest-peak="${Math.max.apply(null,countryObject.dataPoints)}" data-sum-cummulative-data="${countryObject.dataPoints.reduce(function(a,b){return a+b;},0)}" data-render-number="${graphCounter}" class="col-xs-12 col-md-4 chart-container-outer "><span class="compare-country-toggle-switch hideMe" data-is-checked="false"><span class="toggle-switch-inner"><span class="toggle"></span></span></span><span class="healthcare-rank-badge" title="WHO Healthcare Efficiency Rank">${countryObject.healthcareEfficiencyRank}</span><div class="chart-container"><div class="canvas-outer"><canvas class="covid-graph" id="myChart_${countryObject.htmlId}"></canvas></div><div class="chart-title" title="Country & Population">${countryObject.name}: ${prettyPopulation(countryObject.population)}</div></div><script> var chart_canvas = document.getElementById('myChart_${countryObject.htmlId}').getContext('2d'); var myChart_${countryObject.htmlId} = new Chart(chart_canvas, {type: 'line', data: {  labels: [${countryObject.dates.join()}], datasets: [{ label: '${countryObject.datasetLabel}', data: [${countryObject.dataPoints.join()}], fill: true, borderColor: [ 'rgba(50, 25, 150, 1)', ], borderWidth: 1 }] }, options: {animation: { duration: 0, delay: 0, }, scales: { yAxis: { beginAtZero: true, max: ${countryObject.yAxis_max}, ticks: { font: { size: 9, }, fontColor: 'rgba(50, 25, 250, 1)' }, }, xAxis: { ticks: { font: { size: 0.1, }, }, }, }, elements: { point: { pointStyle: '', radius: 0, pointBorderWidth: 2, pointBackgroundColor: ['rgba(50, 25, 150, 1)',], pointBorderColor: ['rgba(50, 25, 150, 1)',], }, line: { borderColor: [ 'rgba(0, 0, 0, 1)', ], borderJoinStyle: 'round', tension: 0, }, }, plugins: {tooltip:{displayColors:false,bodyAlign:'center',titleFont:{weight:'400',}, titleColor:['#999999'], bodyColor:['rgba(207, 168, 214, 0.99)'], bodyFont:{lineHeight:0.5,weight:'400',},callbacks:{label: function(context) {return context.parsed.y + ' ' + context.dataset.label;}, }, }, legend: { display: false, }, }, } }); </script><!--<span class="rank-order-splash"></span>--></div>`
}

let clickConfirmCountdown;
let countryFilterCounter = 0;

function hideCountryFilterClickConfirm(){
    clickConfirmCountdown = setTimeout(()=>{
        $(".country-selected-message").removeClass("country-filter-click-confirm");
    }, 1000);
}

function revealCountryClickConfirmation(toggle) {
    $(".country-selected-message").addClass("country-filter-click-confirm");
    $(toggle).hasClass("toggle-toggle") ? countryFilterCounter++ : countryFilterCounter--;
    $(".country-selected-message").html(countryFilterCounter + ` ${countryFilterCounter === 1 ? "country" : "countries"} Selected`);
    countryFilterCounter === 0 ? $(".country-filter-message").addClass("hideMe") : $(".country-filter-message").removeClass("hideMe");
    hideCountryFilterClickConfirm();
}

function layoutSelectorRevealAndDeal() {
    $(".layout-selector").removeClass("hideMe");
    $(".layout-selector-title > i").on('click', function(){
        if (!$(".layout-selector").hasClass("layout-selector-out")) {
            $(".layout-selector").addClass("layout-selector-out");
        } else {
            $(".layout-selector").removeClass("layout-selector-out");
        }
    });
    $(".layout-selector-button").on('click', function(){
        const columnSpan = $(this).attr("data-col-md");
        $(".loading-graphs-wheel").removeClass("hideMe");
        $(".layout-selector").removeClass("layout-selector-out").delay(600).queue(function(next){
            $(".chart-container-outer").removeClass("col-md-3").removeClass("col-md-4").removeClass("col-md-6").addClass("col-md-" + columnSpan.toString());
            next();
            console.log("queue done?");
        });
        $(".loading-graphs-wheel").addClass("hideMe");
    });
}

function loadAsynchronousEventListeners() {
    console.log("All Graphs Rendered.");
    $(".loading-graphs-wheel").addClass("hideMe");
    $(".filter-menu-button-outer").removeClass("hideMe");
    $(".layout-selector").removeClass("hideMe");
    $(".compare-country-toggle-switch").removeClass("hideMe");

    layoutSelectorRevealAndDeal();

    $(".toggle").on('click', function(){
        $(this).toggleClass('toggle-toggle');
        $(this).parent().toggleClass('toggle-on');
        clearTimeout(clickConfirmCountdown);
        revealCountryClickConfirmation(this);
    });
    $(".deselect-countries-button").on('click', () => {
        countryFilterCounter = 0;
        $(".toggle").removeClass("toggle-toggle").parent().removeClass('toggle-on');
        $(".country-filter-message").addClass('hideMe');

    });
}

function graphIteratorRender(graphArray, is_sorted = false){
    let count = graphArray.length;
    
    $('#renderArea').delay(300).queue(function (next) {
        $(this).html("");
        next();
    });
    for (let i = 0; i < count; i++){
        $('#renderArea').delay(1).queue(function (next) {
            $(this).append(graphArray[i]);
            if (is_sorted) {
                $("#renderArea > div:last-child")
                .append(`<span class="rank-order-splash" title="Rank Order With This Sorting">${(i+1)}</span>`);
            }
            if (i === (count - 1)) {
                loadAsynchronousEventListeners();
            }
            next();
        });
    }
}

function getCountryIds() {
    const countryIdParam = getParam('country_ids');
    if (countryIdParam === 'all') {
        return 'all';
    } else {
        return getParam('country_ids').split(",").map(countryIdsToInt);
    }
}

function countryIdsToInt(value) {
    return parseInt(value);
}

function getCount(array){
    return array.sort()[0];
    // if (Array.isArray(countryIds)) {
    //     return countryIds.length;
    // } else {
    //     return howMany;
    // }
}


function getQueryJson(page, category, countryIdString) {
    return {
        'page': page,
        'category': category,
        'country_ids': countryIdString,
    }
}


function buildGraphArray(countryIds, incomingArray) {
    let tempArray = [];
    const countryIdArray = countryIds.split(",");

    let newUrl = addQueryString(window.location.href, getQueryJson(getParam('page'), getParam('category'), countryIds));
    window.history.pushState({}, '', newUrl);

    if (typeof(incomingArray[0]) === 'string') {
        countryIdArray.forEach(numbStr => {
            if (countryIdArray[0] === 'all') {
                tempArray = incomingArray;
            } else {
                tempArray.push(incomingArray[parseInt(numbStr)-1]);
            }
        });
    } else {
        incomingArray.forEach(object => {
            if (countryIdArray[0] === 'all') {
                tempArray.push(object.outerHTML);
            }
            if (countryIdArray.indexOf((object.dataset.renderNumber)) !== -1) {
                tempArray.push(object.outerHTML);
            }
        });
    }
    return tempArray;
}

function revealGraphs(category, countryIdString, graphJsonObjArray = [], is_sorted ){
    let graphArray = [];
    if (category === "filtered") {
        $(".filter-menu-button-outer").addClass("hideMe");
        // const count = getCount([countryIdString.split(",").length, graphJsonObjArray.length, parseInt(countIn)]);
        graphArray = buildGraphArray(countryIdString, graphJsonObjArray);
        graphIteratorRender(graphArray, is_sorted);
    } else {
        // const count = getCount([countryIdString.split(",").length, graphArray.length, parseInt(countIn)]);
        graphArray = buildGraphArray(countryIdString, eval(category + "GraphArray"));
        graphIteratorRender(graphArray);
    }

}

function resetFilters() {
    $(".sort-button").removeClass("selected");
    $(".sort-button").attr("data-sort-direction","asc");
    $("#sortingText").html('');
}

function initiateGraphRender(event, queryString = '', countryIdString = ''){
    $(".loading-graphs-wheel").removeClass("hideMe");

    let page = event.currentTarget.innerText;
    let category = event.currentTarget.id;
    let country_ids = (getParam('country_ids') ? getParam('country_ids') : 'all');

    resetFilters();

    // let newUrl = addQueryString(window.location.href, queryJson);
    let newUrl = addQueryString(window.location.href, getQueryJson(page, category, country_ids));
    window.history.pushState({}, '', newUrl);
    $("#page_name").html(page + "<span></span>"), revealGraphs(category, country_ids);
}

function loadInitialGraphData() {
    let resultsJson = {};
    let queryStringObjects = {
        casesPerMillion: `category=casesPerMillion&country_ids=all`,
        deathsPerMillion: `category=deathsPerMillion&country_ids=all`,
        mortalityRateByCases: `category=mortalityRateByCases&country_ids=all`,
    }
    for (let graphCategory in queryStringObjects) {
        resultsJson[graphCategory] = graphDataAjax(queryStringObjects[graphCategory]);
    }
    return resultsJson;
}

async function graphDataAjax(query_string) {
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

function sortingDescription(sortDirection,sortParam) {
    let temp = '';
    switch (sortParam) {
        case "countryName":
            if (sortDirection==="asc") {
                temp = "[A <span class='sortTextArrow'>→</span> Z]"
            } else {
                temp = "[Z <span class='sortTextArrow'>→</span> A]"
            }
            break;
        case "population":
            if (sortDirection==="asc") {
                temp = "[Smallest Population <span class='sortTextArrow'>→</span> Largest]"
            } else {
                temp = "[Largest Population <span class='sortTextArrow'>→</span> Smallest]"
            }
            break;
        case "healthcareEfficiency":
            if (sortDirection==="asc") {
                temp = "[Best Access to Healthcare <span class='sortTextArrow'>→</span> Worst]"
            } else {
                temp = "[Worst Access to Healthcare <span class='sortTextArrow'>→</span> Best]"
            }
            break;
        case "highestPeak":
            if (sortDirection==="asc") {
                temp = "[Lowest Reported Peak <span class='sortTextArrow'>→</span> Highest]"
            } else {
                temp = "[Highest Reported Peak <span class='sortTextArrow'>→</span> Lowest]"
            }
            break;
        case "sumCummulativeData":
            if (sortDirection==="asc") {
                temp = "[Best Cummulative History/M <span class='sortTextArrow'>→</span> Worst]"
            } else {
                temp = "[Worst Cummulative History/M <span class='sortTextArrow'>→</span> Best]"
            }
            break;
        default:
            break;
    }
    return temp;
}

function sortingByBtnActions(btnObj){
    if ($(btnObj).hasClass("selected")) {
        if ($(btnObj).attr("data-sort-direction") === 'asc') {
            $(btnObj).attr("data-sort-direction","desc");
            $("#sortingText").html(sortingDescription(btnObj.dataset.sortDirection,btnObj.dataset.sortParam));
        } else {
            $(btnObj).attr("data-sort-direction","asc");
            $("#sortingText").html('');
            $(btnObj).toggleClass('selected');
        }
    } else {
        $(".sort-button").removeClass("selected");
        $(btnObj).toggleClass('selected');
        $("#sortingText").html(sortingDescription(btnObj.dataset.sortDirection,btnObj.dataset.sortParam));

    }
};


function sortByDatasetProperty(property){
    return function(a,b){
        if (parseInt(a.dataset[property]) + 1){
            if(parseInt(a.dataset[property]) > parseInt(b.dataset[property]))
                return 1;
            else if(parseInt(a.dataset[property]) < parseInt(b.dataset[property]))
                return -1;
            return 0;
        } else {
            if(a.dataset[property] > b.dataset[property])
                return 1;
            else if(a.dataset[property] < b.dataset[property])
                return -1;
            return 0;
        }
    }  
}


function reloadAllcountries() {
    $(".select-all-countries").addClass('hideMe');
    returnToHere(0);
    $(".loading-graphs-wheel").removeClass("hideMe");
    $(".layout-selector").addClass("hideMe");
    $("#renderArea").html("");
    revealGraphs(getParam('category'), 'all');
    
}

function applyFilters(){
    returnToHere(0);

    let sorting = false;
    let sortingController = '';
    let countryIdArray = [];

    const chartsJsonArr = $.makeArray($(".chart-container-outer"));
    const sortbuttonJsonArr = $.makeArray($(".sort-button"));



    if ($(".country-filter-message").hasClass("hideMe") === false) {

        $.makeArray($(".toggle-toggle")).forEach((object, index) => {
            countryIdArray.push($(object).parents()[2].dataset.renderNumber)
        });

        // initiateGraphRender(false, window.location.search, countryIdArray.join());
    } else {
        countryIdArray.length === 0 ? countryIdArray.push(getParam('country_ids')) : null;
        // countryIdArray.push(getParam('country_ids'));
    }


    // SORTING
    let sort_direction = '';
    let sort_param = '';
    let is_sorted = false;

    sortbuttonJsonArr.forEach(buttonObj => {

        if ($(buttonObj).hasClass('selected')) {
            sorting = true;
            sortingController = buttonObj;
        }
    });
    if (sorting) {
        is_sorted = true;
        sort_param = sortingController.dataset.sortParam;
        sort_direction = sortingController.dataset.sortDirection;
        chartsJsonArr.sort(sortByDatasetProperty(sort_param));
        if (sort_direction === "desc") {
            chartsJsonArr.reverse();
        }
    }

    $(".sort-button").removeClass("selected").attr("data-sort-direction","asc")
    $("#sortingText").html('');
    countryFilterCounter = 0;
    $(".toggle").removeClass("toggle-toggle").parent().removeClass('toggle-on');
    $(".country-filter-message").addClass('hideMe');

    $("#page_name > span").html(`<button class="select-all-countries" data-sort-direction="${sort_direction}" data-sort-param="${sort_param}">Show All Countries</button>`);

    $(".select-all-countries").on('click', function(){
        reloadAllcountries();
    });
    
    revealGraphs('filtered', countryIdArray.toString(), chartsJsonArr, is_sorted );
}




//            ******************
//            BELOW HERE - ASYNC
//            ******************

let initialGraphData = loadInitialGraphData();
let casesPerMillionGraphArray = [];
let casesPerMillionDataArray = [];
let deathsPerMillionGraphArray = [];
let mortalityRateByCasesGraphArray = [];

for (category in initialGraphData) {
    switch(category) {
        case 'casesPerMillion':
            initialGraphData[category].then((array) => {
                array.forEach((object, index) => {
                    if (object['dataPoints'].length > 0) {
                        casesPerMillionGraphArray.push(getCountryGraph(object, index));
                        casesPerMillionDataArray.push(JSON.stringify(object));
                    }
                });
                return casesPerMillionGraphArray;
            });
            break;
        case 'deathsPerMillion':
            initialGraphData[category].then((array) => {
                array.forEach((object, index) => {
                    if (object['dataPoints'].length > 0) {
                        deathsPerMillionGraphArray.push(getCountryGraph(object, index));
                    }
                });
                return deathsPerMillionGraphArray;
            });
            break;
        case 'mortalityRateByCases':
            initialGraphData[category].then((array) => {
                array.forEach((object, index) => {
                    if (object['dataPoints'].length > 0) {
                        mortalityRateByCasesGraphArray.push(getCountryGraph(object, index));
                    }
                });
                return mortalityRateByCasesGraphArray;
            })
            .then(() => console.log("All Data Loaded!"))
            .then(() => {
                if(window.location.search) {
                    returnToHere(0);
                    $(".loading-graphs-wheel").removeClass("hideMe");
                    $(".layout-selector").addClass("hideMe");
                    const category = getParam('category');
                    const countryIdString = getParam('country_ids');
                    $("#renderArea").html("");
                    revealGraphs(category, countryIdString);
                    console.log('Graphs from URL Loaded!')
                }    
            });
            break;
        default:
            break;
    } 
}
