

$(document).ready(function () {
    $('.currentpage').html('<a href="' + window.location + '" class="tip-bottom"><i class="fa fa-list"></i>My Task</a>')
    var now = new Date();
    var month = (now.getMonth() + 1);
    var day = now.getDate();
    if (month < 10)
        month = "0" + month;
    if (day < 10)
        day = "0" + day;
    var today = now.getFullYear() + '-' + month + '-' + day;
    $('#div_filter').append('<input type="date" value="' + today + '" id="filter_option"/>')
    $('#modal_hold').modal({ backdrop: 'static', keyboard: false, show: false });
    //$('#btn_cancel').hide();
    $('.select2').select2({
        allowClear: true,
        placeholder: "Select Here",
    });
    AGENT_TASK.load($('#filter_option').val(), $('#slct_filter').val());
    AGENT_TASK.loadRunningData($('#filter_option').val(), $('#slct_filter').val());
    $('#label_startend').text("Time Start :");
    $('#div_adhoc').hide();
    // Form Validation
    $("#form_task").validate({
        rules: {
            lid_no: {
                required: true
            },
            category: {
                required: true
            },

            task: {
                required: true
            },
            status: {
                required: true
            },

        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            $(element).parents('.control-group').addClass('success');
        }
    });
});

const AGENT_TASK = (() => {
    let this_agent_task = {}

    //store data
    $('#form_task').on('submit', function (e) {
        e.preventDefault();
        cxDialog({
            info: 'Are you sure you want to submit?',
            ok: () => {
                var formdata = new FormData(this);
                $('#btn_save').empty();
                $('#btn_save').append('<i class="fa fa-spinner fa-spin"></i> Loading...');
                $('#btn_save').prop("disabled", true);
                $('#btn_cancel').prop("disabled", true);
                // Send a POST request
                axios({
                    method: 'post',
                    url: 'agent/store',
                    data: formdata
                }).then(function (response) {
                    if (response.data.status === 'success' && response.data.action == "store") {
                        if(response.data.data.status == "Pending"){
                            $('#label_startend').empty();
                            $('#btn_save').empty();
                            $('#btn_save').append('Complete');
                            $('#btn_save').prop("disabled", false);
                            $('#btn_cancel').prop("disabled", false);
                            $('#label_startend').text("Time End :");
                            $('#edit_id').val(response.data.data.id)
                            $('#lid_no').val(response.data.data.lid_no)
                            $('#category').val(response.data.data.category).trigger('change')
                            $('#task').val(response.data.data.task).trigger('change')
                            $('#status').val(response.data.data.status).trigger('change')
                            $('#adhoc').val(response.data.data.adhoc)
                        }
                        else {
                            $('#label_startend').empty();
                            $('#btn_save').empty();
                            $('#btn_save').append('Submit');
                            $('#btn_save').prop("disabled", false);
                            $('#btn_cancel').prop("disabled", false);
                            $('#label_startend').text("Time Start :");
                            $('#form_task')[0].reset();
                            $('#category').val(null).trigger('change')
                            $('#task').val(null).trigger('change')
                            $('#status').val(null).trigger('change')
                        }
                        AGENT_TASK.load($('#filter_option').val(), $('#slct_filter').val());
                        AGENT_TASK.loadRunningData($('#filter_option').val(), $('#slct_filter').val())
                        toastr.success(response.data.message);
                        
                    } else if (response.data.status === 'success' && response.data.action == "update") {
                        toastr.success(response.data.message);
                        $('#label_startend').empty();
                        $('#btn_save').empty();
                        $('#btn_save').append('Submit');
                        $('#btn_save').prop("disabled", false);
                        $('#btn_cancel').prop("disabled", false);
                        $('#label_startend').text("Time Start :");
                        $('#form_task')[0].reset();
                        $('#category').val(null).trigger('change')
                        $('#task').val(null).trigger('change')
                        $('#status').val(null).trigger('change')
                        AGENT_TASK.load($('#filter_option').val(), $('#slct_filter').val());
                        AGENT_TASK.loadRunningData($('#filter_option').val(), $('#slct_filter').val());
                    }
                    else if (response.data.status === 'warning') {
                        Object.keys(response.data.error).forEach((key) => {
                            toastr.warning(response.data.error[key][0]);
                        });
                        $('#btn_save').empty();
                        $('#btn_save').append('Submit');
                        $('#btn_save').prop("disabled", false);
                        $('#btn_cancel').prop("disabled", false);
                    }
                    else if (response.data.status === 'validate') {
                        //toastr.warning(response.data.message);
                        cxDialog(response.data.message);
                        $('#btn_save').empty();
                        $('#btn_save').append('Submit');
                        $('#btn_save').prop("disabled", false);
                        $('#btn_cancel').prop("disabled", false);
                    }

                }).catch(error => {
                    toastr.error(error);
                });
            },
            no: () => { },
        });
    });

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
        if ($('#filter_option').val() != "") {
            var select = $('#slct_filter option:selected').val()
            $('#btn_filter').empty();
            $('#btn_filter').append('<i class="fa fa-spinner fa-spin"></i> Loading...');
            $('#btn_filter').prop("disabled", true);
            var data;
            if (select == 'daily') {
                data = $('#filter_option').val();
                this_agent_task.load(data, select)
                this_agent_task.loadRunningData(data, select)
            } else if (select == 'weekly') {
                data = $('#filter_option').val();
                this_agent_task.load(data, select)
                this_agent_task.loadRunningData(data, select)
            } else if (select == 'monthly') {
                data = $('#filter_option').val();
                this_agent_task.load(data, select)
                this_agent_task.loadRunningData(data, select)
            } else if (select == 'yearly') {
                data = $('#filter_option').val();
                this_agent_task.load(data, select)
                this_agent_task.loadRunningData(data, select)

            }
        } else {
            toastr.warning("Select date first");
        }
    })

    this_agent_task.load = (data, select) => {
        $("#loading").show();
        $('#tbl_agenttasks').DataTable().clear().destroy();
        var datas = {
            filter: select,
            data: data,
            params: 'agent',
        }
        // console.log(datas)
        axios({
            method: 'post',
            url: 'agent-all',
            data: datas
        }).then(function (response) {
            $('#total_category').empty();
            $('#totalTask').empty();
            var table;
            var x = 1;
            response.data.data.details.forEach(val => {
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
                        <td>${val.task}</td>
                        <td>${status}</td>
                        <td>${val.adhoc}</td>
                        <td>${val.time_spent}</td>
                        <td><button class="btn btn-danger btn-mini btn_delete" data-id="${val.id}"><i class="icon-trash"></i> Delete</button></td>
                     </tr>`;
                x++;
            });
            $('#tbl_agenttasks tbody').html(table);
            datatables('tbl_agenttasks');
            $("#loading").hide();
            $('#btn_filter').empty();
            $('#btn_filter').append('<i class="fa fa-filter"></i> Filter');
            $('#btn_filter').prop("disabled", false);
            response.data.data.total_count.forEach(val => {
                $('#total_category').append(`<li><h1>${val.total}</h1> ${val.name}</li>`);
            })
            $('#totalTask').append(`<li><a href="#"> <h1>${response.data.data.total}</h1> Total</a></li>`)
            toastr.success(response.data.message);
        }).catch(error => {
            toastr.error(error);
        });
    }

    this_agent_task.loadRunningData = (data, select) => {
        var datas = {
            filter: select,
            date: data,
        }
        axios({
            method: 'post',
            url: 'agent/running-data',
            data: datas
        }).then(function (response) {
            $("#tbl_agentperformance tbody").empty();
            if (response.data.status === 'success') {
                var labels = response.data.data[0];
                var tasks = response.data.data[1];
                $('#b_label').text(response.data.data[2]);
                $('#thead_agentperformance').empty();
                $('#thead_agentperformance').append('<th>Date</th>');
                var row = $("<tr>");
                for (let index = 0; index < labels.length; index++) {
                    $('#thead_agentperformance').append(
                        '<th>' + labels[index] + '</th>'
                    );
                }
                for (var i = 0; i < tasks.length; i++) {
                    var task = tasks[i];
                    var row = $("<tr>");
                    for (var j = 0; j < task.length; j++) {
                        row.append('<td>' + task[j] + '</td>');
                    }
                    $("#tbl_agentperformance tbody").append(row);
                }

            }
            (tasks[0].length > 0) ? $('#tbl_agentperformance').tableTotal() : null;
            $('#btn_filter').empty();
            $('#btn_filter').append('<i class="fa fa-filter"></i> Filter');
            $('#btn_filter').prop("disabled", false);


        }).catch(error => {
            toastr.error(error);
        });
    }

    $('#btn_export').on('click', function () {
        var labels = $('#b_label').text();
        var filter = $('#slct_filter option:selected').text();
        tablesToExcel(['tbl_agentperformance'], [filter + ' - ' + labels], 'Online Dashboard Agent Running Data.xls', 'Excel')
    })

    $("#lid_no").on("change", function () {
        var lid_no = $('#lid_no').val();
        $('#category').prop("disabled", true)
        axios('show/task/' + lid_no).then(function (response) {
            console.log(response.data)
            if (response.data != 0) {
                $('#category').prop("disabled", false)
                $('#label_startend').empty();
                $('#btn_save').empty();
                $('#btn_save').append('Complete');
                $('#btn_save').prop("disabled", false);
                $('#btn_cancel').prop("disabled", false);
                $('#label_startend').text("Time End :");
                $('#edit_id').val(response.data.id)
                $('#lid_no').val(response.data.lid_no)
                $('#category').val(response.data.category).trigger('change')
                $('#task').val(response.data.task).trigger('change')
                $('#status').val(response.data.status).trigger('change')
                $('#adhoc').val(response.data.adhoc)
            } else {
                $('#category').prop("disabled", false)
                $('#label_startend').empty();
                $('#btn_save').empty();
                $('#btn_save').append('Submit');
                $('#btn_save').prop("disabled", false);
                $('#btn_cancel').prop("disabled", false);
                $('#label_startend').text("Time Start :");
                $('#lid_no').val(lid_no)
                $('#category').val(null).trigger('change')
                $('#task').val(null).trigger('change')
                $('#status').val(null).trigger('change')
                $('#adhoc').val(null)
            }


        }).catch(error => {
            toastr.error(error);
        });
    })

    $(document).on("click", ".btn_delete", function (event) {
        var id = $(this).attr("data-id");
        cxDialog({
            info: 'Are you sure you want to delete?',
            ok: () => {
                axios({
                    method: 'post',
                    url: 'agent-delete/' + id,
                }).then(function (response) {
                    if (response.data.status === 'success') {
                        toastr.success(response.data.message);
                        AGENT_TASK.load($('#filter_option').val(), $('#slct_filter').val());
                    }

                }).catch(error => {
                    toastr.error(error);
                });
            },
            no: () => { },
        });
    });

    $('#task').on('change', () => {
        var task = $("#task option:selected").val();
        if (task == "ADHOC")
            $("#div_adhoc").show();
        else
            $("#div_adhoc").hide();
    })

    $('#btn_cancel').on('click', () => {
        $('#btn_save').empty();
        $('#btn_save').append('Submit');
        $('#form_task')[0].reset();
        $('#category').val(null).trigger('change')
        $('#status').val(null).trigger('change')
        $('#task').val(null).trigger('change')
    })

    return this_agent_task;
})()
