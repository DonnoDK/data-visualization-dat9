
$(document).ready(function () {
    Array.max = function( array ){
        return Math.max.apply( Math, array );
    };

    $.ajax({
        //This will retrieve the contents of the folder if the folder is configured as 'browsable'
        url: "data/fft-data.json",
        dataType: "json",
        success: function (response) {
            var dx = [];
            var dy = [];

            response.data.forEach(function(item) {
                dx.push(item[0]);
                dy.push(item[1]);
            });
            var options = {
                series: { lines: { show: true }, shadowSize: 0 },
                xaxis: { zoomRange: null, panRange: [0, Array.max(dx) + 100] },
                yaxis: { zoomRange: null, panRange: [0, Array.max(dy) + 100] },
                zoom: {
                    interactive: true
                },
                pan: {
                    interactive: true
                }
            };
            var el = $("#eeg-chart");
           $.plot(el, [response], options);
            
        }     
    });
});