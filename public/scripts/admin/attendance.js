$(document).ready(function () {
    let url = window.location;
    $('.currentpage').html('<a href="' + url + '" class="tip-bottom"><i class="fa fa-users"></i>Attendance</a>')
    ATTENDANCE.load($('#txt_month').val());
    // variables
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
            console.log(response)
            $("#tbl_attendance tbody").empty();
            if (response.data.status === 'success') {
                var labels = response.data.data.label;
                var tasks = response.data.data.users;
                $('#thead_users').empty();
                $('#thead_users').append('<th>Month</th>');
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
            }
            $('#tbl_attendance td:nth-child(n+5)').addClass('selected-th-bg');
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
