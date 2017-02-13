face = {
    initFaceCharts: function(lab,ser,h){
        /* ----------==========     Daily Sales Chart initialization For Documentation    ==========---------- */

        dataDailySalesChart = {
            labels: lab,
            series: [
                ser
            ]
        };

        optionsDailySalesChart = {
            lineSmooth: Chartist.Interpolation.cardinal({
                tension: 0
            }),
            low: 0,
            high: h, // creative tim: we recommend you to set the high sa the biggest value + something for a better look
            chartPadding: { top: 0, right: 0, bottom: 0, left: 0},
        }

        var dailySalesChart = new Chartist.Line('#dailySalesChart', dataDailySalesChart, optionsDailySalesChart);

        md.startAnimationForLineChart(dailySalesChart);
    },
    updateCharts: function(lab,ser,h){
        /* ----------==========     Daily Sales Chart initialization For Documentation    ==========---------- */

        dataDailySalesChart = {
            labels: lab,
            series: [
                ser
            ]
        };

        optionsDailySalesChart = {
            lineSmooth: Chartist.Interpolation.cardinal({
                tension: 0
            }),
            low: 0,
            high: h, // creative tim: we recommend you to set the high sa the biggest value + something for a better look
            chartPadding: { top: 0, right: 0, bottom: 0, left: 0},
        }

        var dailySalesChart = new Chartist.Line('#dailySalesChart', dataDailySalesChart, optionsDailySalesChart);

    },
    initFaceDiv: function(arr,lab){
        enddiv = '<div class="card-footer"></div>';
        $('#face').html('');
        $.each(arr, function (i, v) {
            divid = 'faceimg' + i ;
            faceImgEle = '<div class="card-footer">\
                        <div class="stats">\
                            <span class="ctimes"><i class="material-icons">access_time</i> ' + lab[i] + '</span><br>' +
                            '<div id="' + divid + '">\
                            </div>\
                        </div>\
                    </div>';
            $('#face').prepend(faceImgEle).fadeIn(300);
            $.each(arr[i],function(j, x){
                 imgdiv = '<div style="width:15%;"><img class="img-responsive img-raised" src="'+ x['file_path'] + x['img_name'] +'" /></div>\n'
                 $('#'+divid).prepend(imgdiv).fadeIn(999);;
            });
        });
        $('#face').append(enddiv);
    },
    initFacePicDiv: function(arr){
        enddiv = '<div class="card-footer"></div>';
        divid = 'faceimg' + i ;
        faceImgEle = '<div class="card-footer">\
                            <div class="stats">\
                            <div id="face_img">\
                            </div>\
                        </div>\
                    </div>';
            $('#face').prepend(faceImgEle).fadeIn(300);
        $.each(arr[i],function(j, x){
            imgdiv = '<div style="width:15%;"><img class="img-responsive img-raised" src="'+ x['file_path'] + x['img_name'] +'" /></div>\n'
            $('#face_img').prepend(imgdiv).fadeIn(999);;
        });
        $('#face').append(enddiv);
    }
}