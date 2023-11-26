const regex = /^#?([0-9a-fA-F]{3}){1,2}\b$/;
let rColor, gColor, bColor, getHexColor, hexColor;

function onSuccess(response, time)
{
    var x;
    if (time === 'day') {
        x = {
            show: true,
            type: 'timeseries'
        };
    } else {
        x = {
            show: true
        };
    }



    c3.generate({
        bindto: "#spline-chart",
        size: {
            height: 400
        },
        point: {
            r: 4
        },
        color: {
            pattern: ["#2962FF", "#4fc3f7"]
        },
        data: {
            json: response,
            keys: {
                x: 'name',
                value: ['Complete', 'Incomplete'],
            },
            type: "spline"
        },
        axis: {
            x: x,
            y: {
                tick: {
                    format: function (d) {
                        return (parseInt(d) == d) ? d : null;
                    }
                }
            }
        }
    });
}

function applicationGraph(range, time)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        type: "GET",
        url: "submissions/completions",
        data: {
            range: range,
            time: time,
        },
        contentType: "application/json",
        dataType: "json",
        async: "true",
        cache: "false",
        success: function (result) {
            onSuccess(result.extra.series, time);
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
}

function applications()
{

    $.ajax({
        type: "GET",
        url: "submissions/totalCompletions",
        data: "{}",
        contentType: "application/json",
        dataType: "json",
        async: "true",
        cache: "false",
        success: function (result) {
            $('.progress-complete').css({
                'width': result.extra.complete_percentage + '%'
            });
            $('.progress-incomplete').css({
                'width': result.extra.incomplete_percentage + '%'
            });
            $('#complete').text(result.extra.complete);
            $('#incomplete').text(result.extra.incomplete);
            $('#total_completions').text(result.extra.total);
        },
        error: function (xhr, status, error) {

            console.log(error);
        }
    });
}

function timeSelected()
{
    if ($('#day').parent().hasClass("active")) {
        return 'day';
    } else if ($('#Month').parent().hasClass("active")) {
        return 'month';
    } else if ($('#Year').parent().hasClass("active")) {
        return 'year';
    }
}

function timeClicked(time)
{
    var date_range = $('#calendarRanges').val();
    console.log("date_range");
    console.log(date_range);
    applicationGraph(date_range, time);
}

function trueInput(val) {
    let check = regex.test(val);

    return check;
}

function hexToRGB(hexvalue) {

    if(trueInput(hexvalue)){
        hexColor = hexvalue.replace(/#/g, "");
        //console.log(hexColor);
        if (hexColor.length === 6 || hexColor.length === 3 ) {
            if (hexColor.length === 3) {
                rColor = parseInt(hexColor.substring(0, 1).repeat(2),16);
                gColor = parseInt(hexColor.substring(1, 2).repeat(2),16);
                bColor = parseInt(hexColor.substring(2, 3).repeat(2),16);
                return +rColor+','+gColor+','+bColor;
                //  document.getElementById('rgbColor').value = 'rgb('+rColor+','+gColor+','+bColor+')';

            } else {
                rColor = parseInt(hexColor.substring(0, 2),16);
                gColor = parseInt(hexColor.substring(2, 4),16);
                bColor = parseInt(hexColor.substring(4, 6),16);
                //   document.getElementById('rgbColor').value = 'rgb('+rColor+','+gColor+','+bColor+')';
                return rColor+','+gColor+','+bColor;

            }
        }
    }
}

function graphDonut(response)
{
    var themeHexColor = "#2c77a7";
    //convert Hex to RGBA
    var primaryColor = hexToRGB(themeHexColor);

    let setprimaryColor = (rgbColor,counter) => {
        let rgbaColor = "rgba("+rgbColor+","+counter+")";
        return rgbaColor;
    };

    var colorCounter = 1;
    var maxColor = 5;
    var colorInit = 1;
    var colorArray = [];
    var styleStr ='';

    while (maxColor >= colorInit) {
        var namevar = "pipeline-color-"+colorInit;
        var percentColorNum = 1 - ((maxColor - colorInit) * .18);
        var percentColorRgba = setprimaryColor(primaryColor,percentColorNum);
        colorArray[colorInit - 1] = percentColorRgba;
        colorInit++;
    }

    colorArray.forEach (function(value,key) {
        var valArray = key+1;
        styleStr += ".pipeline-color-"+valArray+"{ color:"+value+"; } ";
    });


    //reverse color sequence
    colorArray.reverse();



    // Add style tag into header
    var style = document.createElement('style');
    style.innerText = styleStr;
    document.head.appendChild(style);

    var chart = c3.generate({
        bindto: '#pipeline',
        data: {
            json: response,

            type: 'donut',
            tooltip: {
                show: true
            }
        },
        donut: {
            label: {
                show: false
            },
            title: "",
            width: 35,

        },

        legend: {
            hide: true
            //or hide: 'data1'
            //or hide: ['data1', 'data2']

        },
        color: {
            pattern: colorArray
        }
    });
}

function percentageApplicationDonutGraph()
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        type: "GET",
        url: "submissions/percentageCompletions",
        contentType: "application/json",
        dataType: "json",
        async: "true",
        cache: "false",
        success: function (result) {
            graphDonut(result.extra);
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
}

$(document).ready(function () {

    var date_range = $('#calendarRanges').val();
    applicationGraph(date_range, timeSelected());

    applications();

    $('.calendarRanges').on('apply.daterangepicker', function () {
        var date_range = $('#calendarRanges').val();
        console.log(timeSelected());

        if ($('.calendarRanges').length > 0 && date_range !== '') {
            applicationGraph(date_range, timeSelected());
        }
    });


    percentageApplicationDonutGraph();




});
