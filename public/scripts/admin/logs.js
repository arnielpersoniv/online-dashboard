

$(document).ready(function () {
    $('.currentpage').html('<a href="'+window.location+'" class="tip-bottom"><i class="fa fa-list"></i>Logs</a>')
    LOGS.load();
});

const LOGS = (() => {
    let this_logs = {}

    this_logs.load = () => {
        $("#loading").show();
        axios('logs/all').then(function (response) {
            $('#tbl_logs').DataTable().destroy();
            var table;
            var x = 1;
            response.data.data.forEach(val => {
                table += `<tr>
                        <td>${x}</td>
                        <td>${val.users.name}</td>
                        <td>${val.subject}</td>
                        <td>${val.action}</td>
                        <td>${val.status}</td>
                        <td>${val.ip_address}</td>
                        <td>${val.created_at}</td>
                     </tr>`;
                x++;
            });
            $('#tbl_logs tbody').html(table);
            datatables('tbl_logs');
            $("#loading").hide();
            toastr.success(response.data.message);
        }).catch(error => {
            toastr.error(error);
        });
    }

    return this_logs;
})()