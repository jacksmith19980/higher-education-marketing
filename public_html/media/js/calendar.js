 // added js for calendar
 //    this script grabs --color-accent from the css. We dont need to pass the color variable on JS
 //    Just pass the color accent via CSS variable


    // convert hex to rgba
    function hexToRgb(hex, alpha) {
        hex   = hex.replace('#', '');
        var r = parseInt(hex.length == 3 ? hex.slice(0, 1).repeat(2) : hex.slice(0, 2), 16);
        var g = parseInt(hex.length == 3 ? hex.slice(1, 2).repeat(2) : hex.slice(2, 4), 16);
        var b = parseInt(hex.length == 3 ? hex.slice(2, 3).repeat(2) : hex.slice(4, 6), 16);
        if ( alpha ) {
            return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + alpha + ')';
        }
        else {
            return 'rgb(' + r + ', ' + g + ', ' + b + ')';
        }
    }

// hex to hsl
function hexToHSL(hex, alpha) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    r = parseInt(result[1], 16);
    g = parseInt(result[2], 16);
    b = parseInt(result[3], 16);
    r /= 255, g /= 255, b /= 255;
    var max = Math.max(r, g, b), min = Math.min(r, g, b);
    var h, s, l = (max + min) / 2;



    if(max == min){
        h = s = 0; // achromatic
    }else{
        var d = max - min;
        s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
        switch(max){
            case r: h = (g - b) / d + (g < b ? 6 : 0); break;
            case g: h = (b - r) / d + 2; break;
            case b: h = (r - g) / d + 4; break;
        }
        h /= 6;
    }
    // console.log(alpha);
    var nl = l - (alpha * .02);
    return [Math.round(h * 360), Math.round(s * 100), Math.round(nl * 100)];
}

//genearate Alpha
function getAlpha(num){
    let alphaNum;
    // num = parseInt(num) + 1;
    num = parseInt(num);
    if(num == 0){
        return 1;
    }else if((num >= 1) && (num <= 5)){
        return 1 - (num * .10);
    }else if((num >= 6) && (num <= 10)){
        return 1.5 - (num * .10);
    }else if((num >= 11) && (num <= 15)){
        return 2 - (num * .10);
    }else if((num >= 16) && (num <= 20)){
        return 2.5 - (num * .10);
    }

}

// on document load
function onLoadFunction(e){
    console.log('loaded');
    var nextAllBtn = document.querySelectorAll('.fc-next-button');

    nextAllBtn.forEach(function(e){
        this.addEventListener('click',function(){
            console.log('next is cliked');
            generateDynamicBG();
        });
    });

    //genearate dynamic BG color fade effect
    const generateDynamicBG = () => {
        //replace with the accentcolor variable
        var accentColor =  getComputedStyle(document.documentElement).getPropertyValue('--color-accent');

        var allWeekRow = document.querySelectorAll('.fc-day-grid .fc-week');
        allWeekRow.forEach(function(week,index){
            var allWeekTr = week.querySelectorAll('.fc-content-skeleton tbody tr');

            allWeekTr.forEach(function(tr,index){

                var allEvent = week.querySelectorAll('.fc-day-grid-event');
                allEvent.forEach(function(evnt,index){
                    var alpha = getAlpha(index);
                    var bgColor = hexToRgb(accentColor,alpha);
                    var hslColor = hexToHSL(accentColor,index);
                    // evnt.setAttribute( 'style', 'background-color:hsl'+'('+hslColor[0]+','+hslColor[1]+'%,'+hslColor[2]+'%'+')'+' !important;color:#fff;' );
                    //  evnt.setAttribute( 'style', 'background-color:'+bgColor+' !important;color:#fff;' );
                });

            });
        });
    }
    generateDynamicBG();
}

window.addEventListener("load", onLoadFunction);

// end added js for calendar