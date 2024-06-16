$(document).ready(function () {
    let url = window.location;
    $('.currentpage').html('<a href="' + url + '" class="tip-bottom"><i class="fa fa-users"></i>Attendance</a>')
    ATTENDANCE.load($('#txt_month').val());

    var sum1 = 0;

    $("#tbl_attendance tr > td:nth-child(4)").each(
    (_,el) => sum1 += Number($(el).text()) || 0
    );

    $(".result").text(sum1);
});

const ATTENDANCE = (() => {
    let this_attendance = {}

    $('#btn_filter').on('click', function () {

        var month = $('#txt_month').val()
        if (month != "") {
            $('#btn_filter').empty();
            $('#btn_filter').append('<i class="fa fa-spinner fa-spin"></i> Loading...');
            $('#btn_filter').prop("disabled", true);
            this_attendance.load(month)
        }
        else
            toastr.warning("Select Month First");

    })

    this_attendance.load = (month) => {
        axios('../admin/attendance/all/' + month).then(function (response) {
            $("#tbl_attendance tbody").empty();
            if (response.data.status === 'success') {
                var labels = response.data.data.label;
                var tasks = response.data.data.users;
                var totalP = response.data.data.totalP;
                var totalA = response.data.data.totalA;
                var rate = response.data.data.rate;
                var totalcolP = response.data.data.totalcolP;
                var totalcoA = response.data.data.totalcolA;
                var totalcolRate = response.data.data.totalcolRate;
                $('#thead_users').empty();
                $('#tfoot_present').empty();
                $('#tfoot_absent').empty();
                $('#tfoot_rate').empty();
                $('#thead_users').append('<th>Month</th>');
                $('#tfoot_present').append('<td><b>Total Present</b></td>');
                $('#tfoot_absent').append('<td><b>Total Absent</b></td>');
                $('#tfoot_rate').append('<td><b>Attendance Rate</b></td>');
                for (let x = 0; x < labels.length; x++) {
                    var user = labels[x];
                    for (var b = 0; b < user.length; b++) {
                        $('#thead_users').append(
                            '<th>' + user[b] + '</th>'
                        );
                    }
                }
                var row = $("<tr>");
                for (var i = 0; i < tasks.length; i++) {
                    var task = tasks[i];
                    var row = $("<tr>");
                    for (var j = 0; j < task.length; j++) {
                        row.append('<td>' + task[j] + '</td>');
                    }
                    $("#tbl_attendance tbody").append(row);
                }

                for (var z = 0; z < totalP.length; z++) {
                    $('#tfoot_present').append(
                        '<td class="selected-th-bg"><b>' + totalP[z] + '</b></td>'
                    ).addClass('selected-th-bg');
                }

                for (var y = 0; y < totalA.length; y++) {
                    $('#tfoot_absent').append(
                        '<td class="selected-th-bg"><b>' + totalA[y] + '</b></td>'
                    ).addClass('selected-th-bg');
                }
                for (var y = 0; y < rate.length; y++) {
                    $('#tfoot_rate').append(
                        '<td class="selected-th-bg"><b>' + rate[y] + '</b></td>'
                    ).addClass('selected-th-bg');
                }
                $('#tfoot_present').append('<td rowspan="3" class="selected-td-bg"><b>' + totalcolP + '</b></td>')
                $('#tfoot_present').append('<td rowspan="3" class="selected-td-bg"><b>' + totalcoA + '</b></td>')
                $('#tfoot_present').append('<td rowspan="3" class="selected-td-bg"><b>' + totalcolRate + '</b></td>')
            }
            for (let a = 1; a <=3; a++) {
                $('#tbl_attendance td:nth-last-child('+a+')').addClass('selected-th-bg');
            }
            $('#btn_filter').empty();
            $('#btn_filter').append('<i class="fa fa-filter"></i> Filter');
            $('#btn_filter').prop("disabled", false);
            toastr.success(response.data.message);
        }).catch(error => {
            toastr.error(error);
        });

    }

    $('#btn_export').on('click', function () {
        var monthly = $('#txt_monthly').text();
        tablesToExcel(['tbl_attendance'], [monthly], 'CS Online Dashboard Report-Attendance.xls', 'Excel')
    })

    return this_attendance;
})()
