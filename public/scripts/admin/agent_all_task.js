

$(document).ready(function () {
    $('.currentpage').html('<a href="' + window.location + '" class="tip-bottom"><i class="fa fa-list"></i>All Tasks</a>')
    var now = new Date();
    var month = (now.getMonth() + 1);
    var day = now.getDate();
    if (month < 10)
        month = "0" + month;
    if (day < 10)
        day = "0" + day;
    var today = now.getFullYear() + '-' + month + '-' + day;
    $('#div_filter').append('<input type="date" value="' + today + '" id="filter_option"/>')
    AGENT_ALL_TASK.load($('#filter_option').val(), $('#slct_filter').val());
});

const AGENT_ALL_TASK = (() => {
    let this_agent_all_task = {}

    $('#slct_filter').on('change', function () {
        var select = $('#slct_filter option:selected').val()
        if (select == 'daily') {
            $('#div_filter').show();
            $('#div_filter').empty();
            $('#div_filter').append('<input type="date" id="filter_option" />')
        } else if (select == 'weekly') {
            $('#div_filter').show();
            $('#div_filter').empty();
            $('#div_filter').append('<input type="week" id="filter_option" />')
        } else if (select == 'monthly') {
            $('#div_filter').show();
            $('#div_filter').empty();
            $('#div_filter').append('<input type="month" id="filter_option" />')
        } else if (select == 'yearly') {
            $('#div_filter').show();
            $('#div_filter').empty();
            $('#div_filter').append('<input type="number" placeholder="YYYY" min="2024" max="4000"  id="filter_option" />')
        }
    })

    $('#btn_filter').on('click', function () {
        if ($('#filter_option').val() != "") {
            var select = $('#slct_filter option:selected').val()
            $('#btn_filter').empty();
            $('#btn_filter').append('<i class="fa fa-spinner fa-spin"></i> Loading...');
            $('#btn_filter').prop("disabled", true);
            var data;
            if (select == 'daily') {
                data = $('#filter_option').val();
                this_agent_all_task.load(data, select)
            } else if (select == 'weekly') {
                data = $('#filter_option').val();
                this_agent_all_task.load(data, select)
            } else if (select == 'monthly') {
                data = $('#filter_option').val();
                this_agent_all_task.load(data, select)
            } else if (select == 'yearly') {
                data = $('#filter_option').val();
                this_agent_all_task.load(data, select)

            }
        }else{
            toastr.warning("Do not leave blank");
        }
    })

    this_agent_all_task.load = (data, select) => {
        $("#loading").show();
        var datas = {
            filter: select,
            data: data,
            params: 'admin',
        }
        axios({
            method: 'post',
            url: '../../task/agent-all',
            data: datas
        }).then(function (response) {
            $("#loading").show();
            $('#tbl_agentalltask').DataTable().clear().destroy();
            $('#total_category').empty();
            $('#totalTask').empty();
            var table;
            var x = 1;
            response.data.data.details.forEach(val => {
                var profile = (val.profile != null) ? `<img src="../../storage/profiles/${val.profile}" alt="profile" class="small-profile"/>
                            <i class="typcn typcn-download btn-icon-append"></i>
                        </a>` : '<img src="themes/images/faces/avatar.png" alt="profile" class="small-profile"/>'
                var status = ''
                switch (val.status) {
                    case 'Done':
                        status = `<span class="label label-success">Done</span>`;
                        break;
                    case 'Pending':
                        status = `<span class="label label-warning">Pending</span>`;
                        break;
                }
                table += `<tr>
                        <td>${x}</td>
                        <td>${val.created_at}</td>
                        <td>${val.lid_no}</td>
                        <td>${val.category}</td>
                        <td>${val.adhoc_category}</td>
                        <td>${val.task}</td>
                        <td>${val.adhoc_task}</td>
                        <td>${status}</td>
                        <td>${val.agent}</td>
                        <td>${val.time_spent}</td>
                     </tr>`;
                x++;
            });
            $('#tbl_agentalltask tbody').html(table);
            datatables('tbl_agentalltask');
            $("#loading").hide();
            $('#btn_filter').empty();
            $('#btn_filter').append('<i class="fa fa-filter"></i> Filter');
            $('#btn_filter').prop("disabled", false);
            response.data.data.total_count.forEach(val => {
                $('#total_category').append(`<li><h1>${val.total}</h1> ${val.name}</li>`);
            })
            if (response.data.data.details.lenght > 0) {
                $('#totalTask').append(`<li><a href="#"> <h1>${response.data.data.total}</h1> Total</a></li>`)
            }
            toastr.success(response.data.message);
        }).catch(error => {
            toastr.error(error);
        });
    }


    return this_agent_all_task;
})()
