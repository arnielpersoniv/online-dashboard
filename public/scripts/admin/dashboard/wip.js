

$(document).ready(function () {
    var now = new Date();
    var month = (now.getMonth() + 1);               
    var day = now.getDate();
    if (month < 10) 
        month = "0" + month;
    if (day < 10) 
        day = "0" + day;
    var today = now.getFullYear() + '-' + month + '-' + day;
    let url = window.location;
    $('.currentpage').html('<a href="' + url + '" class="tip-bottom"><i class="fa fa-dashboard"></i>Running Data</a>')
    $('#div_filter').append('<input type="date" value="'+today+'" id="filter_option"/>')
    DASHBOARD.loadAgent($('#filter_option').val(), $('#slct_filter').val());


});

const DASHBOARD = (() => {
    let this_dashboard = {}

    $('#slct_filter').on('change', function () {
        var select = $('#slct_filter option:selected').val()
        if (select == 'daily') {
            $('#div_filter').show();
            $('#div_filter').empty();
            $('#div_filter').append('<input type="date" id="filter_option"/>')
        } else if (select == 'weekly') {
            $('#div_filter').show();
            $('#div_filter').empty();
            $('#div_filter').append('<input type="week" id="filter_option"/>')
        } else if (select == 'monthly') {
            $('#div_filter').show();
            $('#div_filter').empty();
            $('#div_filter').append('<input type="month" id="filter_option"/>')
        } else if (select == 'yearly') {
            $('#div_filter').show();
            $('#div_filter').empty();
            $('#div_filter').append('<input type="number" placeholder="YYYY" min="2024" max="4000"  id="filter_option"/>')
        }
    })

    $('#btn_filter').on('click', function () {
        
        if($('#filter_option').val() != ""){
            var select = $('#slct_filter option:selected').val()
            $('#btn_filter').empty();
            $('#btn_filter').append('<i class="fa fa-spinner fa-spin"></i> Loading...');
            $('#btn_filter').prop("disabled", true);
            var data;
            if (select == 'daily') {
                data = $('#filter_option').val();
                this_dashboard.loadAgent(data, select)
            } else if (select == 'weekly') {
                data = $('#filter_option').val();
                this_dashboard.loadAgent(data, select)
            } else if (select == 'monthly') {
                data = $('#filter_option').val();
                this_dashboard.loadAgent(data, select)
            } else if (select == 'yearly') {
                data = $('#filter_option').val();
                this_dashboard.loadAgent(data, select)
            }
        }else{
            toastr.warning("Select date first");
        }
        
    })

    this_dashboard.loadAgent = (data, select) => {
        var datas = {
            filter: select,
            date: data,
        }
        axios({
            method: 'post',
            url: '../wip/report/load',
            data: datas
        }).then(function (response) {
            $("#tbl_agent tbody").empty();
            if (response.data.status === 'success') {
                var labels = response.data.data.agent.label;
                var tasks = response.data.data.agent.task;
                var filter = ''
                switch (datas.filter) {
                    case 'daily':
                        filter = 'DAILY - ' + response.data.data.task.date;
                        break;
                    case 'weekly':
                        filter = 'WEEKLY - ' + response.data.data.task.date;
                        break;
                    case 'monthly':
                        filter = 'MONTHLY - ' + response.data.data.task.date;
                        break;
                    case 'yearly':
                        filter = 'YEARLY - ' + response.data.data.task.date;
                        break;
                }
                $('#label_agent').text(filter);
                $('#label_task').text(filter);
                $('#label_agent2').text(filter);
                $('#label_task2').text(filter);
                $('#thead_agent').empty();
                $('#thead_agent').append('<th>Name</th>');
                for (let index = 0; index < labels.length; index++) {
                    $('#thead_agent').append(
                        '<th>' + labels[index] + '</th>'
                    );
                }

                for (var i = 0; i < tasks.length; i++) {
                    var task = tasks[i];
                    var row = $("<tr>");
                    for (var j = 0; j < task.length; j++) {
                        row.append('<td class="td-align">' + task[j] + '</td>');
                    }
                    $("#tbl_agent tbody").append(row);
                }

                this_dashboard.loadTask(response);
                this_dashboard.loadAgentTask(response);
                this_dashboard.loadAllTask(response);
            }
            (tasks.length > 0) ? $('#tbl_agent').tableTotal() : null;
            $('#btn_filter').empty();
            $('#btn_filter').append('<i class="fa fa-filter"></i> Filter');
            $('#btn_filter').prop("disabled", false);

        }).catch(error => {
            toastr.error(error);
        });
    }

    this_dashboard.loadTask = (response) => {
        $("#tbl_task tbody").empty();
        if (response.data.status === 'success') {
            var labels = response.data.data.task.label;
            var tasks = response.data.data.task.task;
            // $('.label_task').text(response.data.data.task.date);
            $('#thead_task').empty();
            $('#thead_task').append('<th>Task</th>');
            for (let index = 0; index < labels.length; index++) {
                $('#thead_task').append(
                    '<th>' + labels[index] + '</th>'
                );
            }

            for (var i = 0; i < tasks.length; i++) {
                var task = tasks[i];
                var row = $("<tr>");
                for (var j = 0; j < task.length; j++) {
                    row.append('<td class="td-align">' + task[j] + '</td>');
                }
                $("#tbl_task tbody").append(row);
            }
        }
        (tasks.length > 0) ? $('#tbl_task').tableTotal() : null;
    }

    this_dashboard.loadAgentTask = (response) => {
        $("#tbl_agenttask tbody").empty();
        var labels = response.data.data.agentask[0];
        var tasks = response.data.data.agentask[1];
        $('#label_agent2').text(response.data.data.agentask[2]);
        $('#thead_agenttask').empty();
        $('#thead_agenttask').append('<th>Name</th>');
        for (let index = 0; index < labels.length; index++) {
            $('#thead_agenttask').append(
                '<th>' + labels[index] + '</th>'
            );
        }

        for (var i = 0; i < tasks.length; i++) {
            var task = tasks[i];
            var row = $("<tr>");
            for (var j = 0; j < task.length; j++) {
                row.append('<td class="td-align">' + task[j] + '</td>');
            }
            $("#tbl_agenttask tbody").append(row);
        }
        (tasks[0].length > 0) ? $('#tbl_agenttask').tableTotal() : null;
    }

    this_dashboard.loadAllTask = (response) => {
        $("#tbl_task2 tbody").empty();
        var labels = response.data.data.alltask.label;
            var tasks = response.data.data.alltask.task;
        $('#label_task2').text(response.data.data.date);
        $('#thead_task2').empty();
        $('#thead_task2').append('<th>Task</th>');
        for (let index = 0; index < labels.length; index++) {
            $('#thead_task2').append(
                '<th>' + labels[index] + '</th>'
            );
        }

        for (var i = 0; i < tasks.length; i++) {
            var task = tasks[i];
            var row = $("<tr>");
            for (var j = 0; j < task.length; j++) {
                row.append('<td class="td-align">' + task[j] + '</td>');
            }
            $("#tbl_task2 tbody").append(row);
        }
        (tasks.length > 0) ? $('#tbl_task2').tableTotal() : null;
    }

    $('#btn_export').on('click', function () {
        var date2 = $('#label_agent2').text();
        var date = $('#label_agent').text();
        tablesToExcel(['tbl_agent','tbl_agenttask', 'tbl_task', 'tbl_task2'], [date + ' - (Agent)',date2 + ' - (Agent)', date + ' - (Task)', date + ' - (Task)'], 'Online Dashboard Report-Running Data.xls', 'Excel')
    })


    $('.btn_hide').on('click', function () {
        $('#hideTable' + this.id).toggle();
    });

    return this_dashboard;
})()
