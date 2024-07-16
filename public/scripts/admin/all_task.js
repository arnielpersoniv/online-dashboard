

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
    ALL_TASK.load($('#slct_filter').val(), $('#slct_filter').val());
});

const ALL_TASK = (() => {
    let this_all_task = {}

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
                this_all_task.load(data, select)
            } else if (select == 'weekly') {
                data = $('#filter_option').val();
                this_all_task.load(data, select)
            } else if (select == 'monthly') {
                data = $('#filter_option').val();
                this_all_task.load(data, select)
            } else if (select == 'yearly') {
                data = $('#filter_option').val();
                this_all_task.load(data, select)

            }
        }else{
            toastr.warning("Do not leave blank");
        }
    })

    this_all_task.load = (data, select) => {
        $("#loading").show();
        var datas = {
            filter: select,
            data: data,
            params: 'admin',
        }
        axios({
            method: 'post',
            url: `${APP_URL}/task/all`,
            data: datas
        }).then(function (response) {
            $("#loading").show();
            $('#tbl_alltask').DataTable().clear().destroy();
            $('#total_category').empty();
            $('#totalTask').empty();
            var table;
            var x = 1;
            response.data.data.details.forEach(val => {
                var status = ''
                switch (val.status) {
                    case 'released':
                        status = `<span class="in-progress">Released</span>`;
                        break;
                    case 'hold':
                        status = `<span class="pending">Hold</span>`;
                        break;
                    case 'completed':
                        status = `<span class="done">Completed</span>`;
                        break;
                }
                var holdreason = '';
                if (val.status == 'hold') {
                    holdreason = `enabled`
                    $('#txt_reason').attr('hidden', false)

                } else {
                    holdreason += `hidden`
                    $('#txt_reason').attr('hidden', false)
                }

                table += `<tr>
                        <td>${x}</td>
                        <td>${val.created_at}</td>
                        <td>${val.order_no}</td>
                        <td>${val.account_no}</td>
                        <td>${val.category}</td>
                        <td>${val.task}</td>
                        <td>${val.releasedby}</td>
                        <td>${status}</td>
                        <td><span ${holdreason}>${val.hold_reason}</span></td>
                        <td>${val.time_spent}</td>
                     </tr>`;
                x++;
            });
            $('#tbl_alltask tbody').html(table);
            datatables('tbl_alltask');
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


    return this_all_task;
})()
