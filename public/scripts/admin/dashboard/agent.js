

$(document).ready(function () {
    $('.currentpage').html('<a href="' + window.location + '" class="tip-bottom"><i class="fa fa-dashboard"></i>Agent</a>')
    $('#div_filter').hide();
    DASHBOARD.loadDaily($('#slct_filter').val(), 'all');
    DASHBOARD.loadWeekly($('#slct_filter').val(), 'all');
    DASHBOARD.loadMonthly($('#slct_filter').val(), 'all');
    DASHBOARD.loadYearly($('#slct_filter').val(), 'all');

});

const DASHBOARD = (() => {
    let this_dashboard = {}

    $('#slct_filter').on('change', function () {
        var select = $('#slct_filter option:selected').val()
        if (select == 'all') {
            $('#div_filter').empty();
            $('#div_filter').hide();
        } else if (select == 'daily') {
            $('#div_filter').show();
            $('#div_filter').empty();
            $('#div_filter').append('<input type="date" id="filter_option" required/>')
        } else if (select == 'weekly') {
            $('#div_filter').show();
            $('#div_filter').empty();
            $('#div_filter').append('<input type="week" id="filter_option" required/>')
        } else if (select == 'monthly') {
            $('#div_filter').show();
            $('#div_filter').empty();
            $('#div_filter').append('<input type="month" id="filter_option" required/>')
        } else if (select == 'yearly') {
            $('#div_filter').show();
            $('#div_filter').empty();
            $('#div_filter').append('<input type="number" placeholder="YYYY" min="2024" max="4000"  id="filter_option" required/>')
        }
    })

    $('#btn_filter').on('click', function () {
        if ($('#filter_option').val() != "") {
            $('#btn_filter').empty();
            $('#btn_filter').append('<i class="fa fa-spinner fa-spin"></i> Loading...');
            $('#btn_filter').prop("disabled", true);
            var select = $('#slct_filter option:selected').val()
            var data;
            if (select == 'all') {
                data = $('#slct_filter').val("all");
                this_dashboard.loadDaily(data, select)
                this_dashboard.loadWeekly(data, select)
                this_dashboard.loadMonthly(data, select)
                this_dashboard.loadYearly(data, select)
            } else if (select == 'daily') {
                data = $('#filter_option').val();
                this_dashboard.loadDaily(data, select)
                $('html,body').animate({scrollTop: $("#div_daily").offset().top},'slow');
            } else if (select == 'weekly') {
                data = $('#filter_option').val();
                this_dashboard.loadWeekly(data, select)
                $('html,body').animate({scrollTop: $("#div_weekly").offset().top},'slow');
            } else if (select == 'monthly') {
                data = $('#filter_option').val();
                this_dashboard.loadMonthly(data, select)
                $('html,body').animate({scrollTop: $("#div_monthly").offset().top},'slow');
            } else if (select == 'yearly') {
                data = $('#filter_option').val();
                this_dashboard.loadYearly(data, select)
                $('html,body').animate({scrollTop: $("#div_yearly").offset().top},'slow');

            }
        } 
        else
            toastr.warning("Do not leave blank");

    })

    this_dashboard.loadDaily = (data, select) => {
        var datas = {
            filter: select,
            date: data,
        }
        axios({
            method: 'post',
            url: `${APP_URL}/admin/agent/report/daily`,
            data: datas
        }).then(function (response) {
            $("#tbl_daily tbody").empty();
            if (response.data.status === 'success') {
                var labels = response.data.data[0];
                var tasks = response.data.data[1];
                $('#txt_daily').text(response.data.data[2]);
                //var total = response.data.data[2];
                // console.log(labels);
                $('#thead_daily').empty();
                $('#thead_daily').append('<th>Name</th>');
                var row = $("<tr>");
                for (let index = 0; index < labels.length; index++) {
                    $('#thead_daily').append(
                        '<th>' + labels[index] + '</th>'
                    );
                }
                for (var i = 0; i < tasks.length; i++) {
                    var task = tasks[i];
                    var row = $("<tr>");
                    for (var j = 0; j < task.length; j++) {
                        row.append('<td>' + task[j] + '</td>');
                    }
                    $("#tbl_daily tbody").append(row);
                }

            }

            (tasks[0].length > 0) ? $('#tbl_daily').tableTotal() : null;
            // var table = $('#tbl_daily').dataTable({
            //     "bJQueryUI": true,
            // });
            // $("#searchbox").keyup(function () {
            //     table.fnFilter(this.value);
            // });
            $('#btn_filter').empty();
            $('#btn_filter').append('<i class="fa fa-filter"></i> Filter');
            $('#btn_filter').prop("disabled", false);


        }).catch(error => {
            toastr.error(error);
        });
    }

    this_dashboard.loadWeekly = (data, select) => {
        var datas = {
            filter: select,
            date: data,
        }
        axios({
            method: 'post',
            url: `${APP_URL}/admin/agent/report/weekly`,
            data: datas
        }).then(function (response) {
            $("#tbl_weekly tbody").empty();
            if (response.data.status === 'success') {
                var labels = response.data.data[0];
                var tasks = response.data.data[1];
                $('#txt_weekly').text((datas.filter == 'all') ? '' : datas.date);
                $('#label_weekly').text(response.data.data[2]);
                // console.log(labels);
                $('#thead_weekly').empty();
                $('#thead_weekly').append('<th>Name</th>');
                for (let index = 0; index < labels.length; index++) {
                    $('#thead_weekly').append(
                        '<th class="th-bg">' + labels[index] + '</th>'
                    );
                }
                for (var i = 0; i < tasks.length; i++) {
                    var task = tasks[i];
                    var row = $("<tr>");
                    for (var j = 0; j < task.length; j++) {
                        row.append('<td class="td-align">' + task[j] + '</td>');
                    }
                    $("#tbl_weekly tbody").append(row);
                }
                //toastr.success(response.data.message);
            }
            (tasks[0].length > 0) ? $('#tbl_weekly').tableTotal() : null;
            $('#btn_filter').empty();
            $('#btn_filter').append('<i class="fa fa-filter"></i> Filter');
            $('#btn_filter').prop("disabled", false);

        }).catch(error => {
            toastr.error(error);
        });
    }

    this_dashboard.loadMonthly = (data, select) => {
        var datas = {
            filter: select,
            date: data,
        }
        axios({
            method: 'post',
            url: `${APP_URL}/admin/agent/report/monthly`,
            data: datas
        }).then(function (response) {
            $("#tbl_monthly tbody").empty();
            if (response.data.status === 'success') {
                var labels = response.data.data[0];
                var tasks = response.data.data[1];
                $('#txt_monthly').text(response.data.data[2]);
                // console.log(labels);
                $('#thead_monthly').empty();
                $('#thead_monthly').append('<th>Name</th>');
                for (let index = 0; index < labels.length; index++) {
                    $('#thead_monthly').append(
                        '<th class="th-bg">' + labels[index] + '</th>'
                    );
                }
                for (var i = 0; i < tasks.length; i++) {
                    var task = tasks[i];
                    var row = $("<tr>");
                    for (var j = 0; j < task.length; j++) {
                        row.append('<td class="td-align">' + task[j] + '</td>');
                    }
                    $("#tbl_monthly tbody").append(row);
                }
                //toastr.success(response.data.message);
            }

            (tasks[0].length > 0) ? $('#tbl_monthly').tableTotal() : null;
            $('#btn_filter').empty();
            $('#btn_filter').append('<i class="fa fa-filter"></i> Filter');
            $('#btn_filter').prop("disabled", false);
        }).catch(error => {
            toastr.error(error);
        });
    }

    this_dashboard.loadYearly = (data, select) => {
        var datas = {
            filter: select,
            date: data,
        }
        axios({
            method: 'post',
            url: `${APP_URL}/admin/agent/report/yearly`,
            data: datas
        }).then(function (response) {
            $("#tbl_yearly tbody").empty();
            if (response.data.status === 'success') {
                var labels = response.data.data[0];
                var tasks = response.data.data[1];
                $('#txt_yearly').text(response.data.data[2]);
                // console.log(labels);
                $('#thead_yearly').empty();
                $('#thead_yearly').append('<th>Name</th>');
                for (let index = 0; index < labels.length; index++) {
                    $('#thead_yearly').append(
                        '<th class="th-bg">' + labels[index] + '</th>'
                    );
                }
                for (var i = 0; i < tasks.length; i++) {
                    var task = tasks[i];
                    var row = $("<tr>");
                    for (var j = 0; j < task.length; j++) {
                        row.append('<td class="td-align">' + task[j] + '</td>');
                    }
                    $("#tbl_yearly tbody").append(row);
                }
                toastr.success(response.data.message);
            }
            (tasks[0].length > 0) ? $('#tbl_yearly').tableTotal() : null;
            $('#btn_filter').empty();
            $('#btn_filter').append('<i class="fa fa-filter"></i> Filter');
            $('#btn_filter').prop("disabled", false);

        }).catch(error => {
            toastr.error(error);
        });
    }

    $('#btn_export').on('click', function () {
        var daily = $('#txt_daily').text();
        var weekly = $('#label_weekly').text();
        var monthly = $('#txt_monthly').text();
        var yearly = $('#txt_yearly').text();
        tablesToExcel(['tbl_daily', 'tbl_weekly', 'tbl_monthly', 'tbl_yearly'], ['Daily-' + daily, 'Weekly-' + weekly, 'Monthly-' + monthly, 'Yearly-' + yearly], 'Online Dashboard Report-Agent.xls', 'Excel')
    })

    $('.btn_hide').on('click', function () {
        $('#hideTable' + this.id).toggle();
    });


    return this_dashboard;
})()
