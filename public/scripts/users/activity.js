

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
    $('#btn_cancel').hide();
    $('.select2').select2({
        allowClear: true,
        placeholder: "Select Here",
    });
    ACTIVITY.load($('#filter_option').val(), $('#slct_filter').val());
    ACTIVITY.category();
    // Form Validation
    $("#form_activity").validate({
        rules: {
            order_no: {
                required: true
            },
            account_no: {
                required: true
            },
            status: {
                required: true
            },
            category_id: {
                required: true
            },
            task_id: {
                required: true
            }
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

    $("#form_hold").validate({
        rules: {
            hold_reason: {
                required: true
            }
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

const ACTIVITY = (() => {
    let this_activity = {}

    //store data
    $('#form_activity').on('submit', function (e) {
        e.preventDefault();
        var formdata = new FormData(this);
        $('#btn_save').empty();
        $('#btn_save').append('<i class="fa fa-spinner fa-spin"></i> Loading...');
        $('#btn_save').prop("disabled", true);
        $('#btn_cancel').prop("disabled", true);
        // Send a POST request
        axios({
            method: 'post',
            url: 'store',
            data: formdata
        }).then(function (response) {
            if (response.data.status === 'success') {
                toastr.success(response.data.message);
                $('#form_activity')[0].reset();
                $('#category_id').val(null).trigger('change')
                $('#status').val(null).trigger('change')
                $('#task_id').empty();
                $('#btn_cancel').hide();
                ACTIVITY.load($('#filter_option').val(), $('#slct_filter').val());
            } else if (response.data.status === 'warning') {
                Object.keys(response.data.error).forEach((key) => {
                    toastr.warning(response.data.error[key][0]);
                });
            }
            else if (response.data.status === 'validate') {
                toastr.warning(response.data.message);
                cxDialog(response.data.message);
            }
            $('#btn_save').empty();
            $('#btn_save').append('Submit');
            $('#btn_save').prop("disabled", false);
            $('#btn_cancel').prop("disabled", false);

        }).catch(error => {
            toastr.error(error);
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
                this_activity.load(data, select)
            } else if (select == 'weekly') {
                data = $('#filter_option').val();
                this_activity.load(data, select)
            } else if (select == 'monthly') {
                data = $('#filter_option').val();
                this_activity.load(data, select)
            } else if (select == 'yearly') {
                data = $('#filter_option').val();
                this_activity.load(data, select)

            }
        }else{
            toastr.warning("Select date first");
        }
    })

    this_activity.load = (data, select) => {

        $("#loading").show();
        $('#tbl_activity').DataTable().clear().destroy();
        var datas = {
            filter: select,
            data: data,
            params: 'agent',
        }
        // console.log(datas)
        axios({
            method: 'post',
            url: 'all',
            data: datas
        }).then(function (response) {
            console.log(response)
            $('#total_category').empty();
            $('#totalTask').empty();
            var table;
            var x = 1;
            var total = 0;
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
                var showResume = ''
                var showHold = ''
                var showCompleted = ''
                if (val.status == 'released') {
                    $('form *').prop('disabled', true);
                    showResume += `hidden`
                    showHold += `enabled`
                    showCompleted += `enabled`
                } else if (val.status == 'hold') {
                    $('form *').prop('disabled', true);
                    showResume += `enabled`
                    showHold += `hidden`
                    showCompleted += `hidden`
                } else {
                    $('form *').prop('disabled', false);
                    showResume += `hidden`
                    showHold += `hidden`
                    showCompleted += `hidden`
                }

                if (val.status == 'released' || val.status == 'hold') {
                    total++;
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
                        <td>${val.time_spent}</td>
                        <td>
                            <button class="btn btn-warning btn-mini ${showHold} btn_hold" id="${val.id}" data-id="${val.account_no}"><i class="icon-pause"></i>Hold</button>
                            <button class="btn btn-info btn-mini ${showResume} btn_resume" id="${val.id}"><i class="icon-play"></i> Resume</button>
                        </td>
                        <td><button class="btn btn-success btn-mini ${showCompleted} btn_complete" id="${val.id}"><i class="icon-thumbs-up"></i> Complete</button></td>
                        <td>
                            
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Action <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="#" class="btn_show" id="${val.id}"><i class="icon-edit"></i> Edit</a></li>
                                    <li><a href="#" class="btn_delete" id="${val.id}"><i class="icon-trash"></i> Delete</a></li>
                                </ul>
                            </div>
                        </td>
                     </tr>`;
                x++;
            });
            if (total > 0) {
                $('form *').prop('disabled', true)
                $('#category_id').prop("disabled", true);
                $('#hold_reason').prop('disabled', false)
            } else {
                $('form *').prop('disabled', false);
                $('#category_id').prop('disabled', false)
            }
            $('#tbl_activity tbody').html(table);
            datatables('tbl_activity');
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
    this_activity.category = () => {
        $('#category_id').prop("disabled", true);
        axios('../show/all').then(function (response) {
            var category_id = `<option value="">Select Here</option>`;
            $.each(response.data.data, function () {
                category_id += `<option value="${this.id}">${this.name}</option>`;
            });
            $('#category_id').append(category_id);
            $('#category_id').prop("disabled", false);
        }).catch(error => {
            toastr.error(error);
        });
    }
    $('#category_id').on('change', function () {
        $('#task_id').prop("disabled", true);
        var categoryid = $('#category_id').val();
        axios('../show/edit/' + categoryid).then(function (response) {
            var tasklist = ``;
            $.each(response.data, function () {
                tasklist += `<option value="${this.id}">${this.name}</option>`;
            });
            $('#task_id').html(tasklist);
            $('#task_id').prop("disabled", false);
        }).catch(error => {
            //toastr.error(error);
        });
    })
    $(document).on("click", ".btn_show", function (event) {
        var target = event.target;
        $('form *').prop('disabled', false)
        $('#status').prop('readonly', true)
        axios('show/' + target.id).then(function (response) {
            $('#btn_save').empty();
            $('#btn_save').append('Save changes');
            $('#btn_cancel').show();
            $('#edit_id').val(response.data.id)
            $('#order_no').val(response.data.order_no)
            $('#account_no').val(response.data.account_no)
            $('#category_id').val(response.data.category_id).trigger('change')
            $('#status').val(response.data.status).trigger('change')
            $('#task_id').val(response.data.task_id).trigger('change')
        }).catch(error => {
            toastr.error(error);
        });
    });

    $(document).on("click", ".btn_complete", function (event) {
        var target = event.target;
        var data = {
            action: 'completed',
            id: target.id,
        }
        cxDialog({
            info: 'Are you sure you want to complete?',
            ok: () => {
                axios({
                    method: 'post',
                    url: 'action',
                    data: data
                }).then(function (response) {
                    if (response.data.status === 'success') {
                        toastr.success(response.data.message);
                        ACTIVITY.load($('#filter_option').val(), $('#slct_filter').val());
                    }

                }).catch(error => {
                    toastr.error(error);
                });
            },
            no: () => { },
        });
    });

    $(document).on("click", ".btn_delete", function (event) {
        var target = event.target;
        cxDialog({
            info: 'Are you sure you want to delete?',
            ok: () => {
                axios({
                    method: 'post',
                    url: 'delete/' + target.id,
                }).then(function (response) {
                    if (response.data.status === 'success') {
                        toastr.success(response.data.message);
                        ACTIVITY.load($('#filter_option').val(), $('#slct_filter').val());
                    }

                }).catch(error => {
                    toastr.error(error);
                });
            },
            no: () => { },
        });
    });


    $(document).on("click", ".btn_hold", function (event) {
        var target = event.target;
        $('#hold_id').val(target.id)
        $('#modal_hold').modal('show')
    });


    $('#btn_cancel').on('click', () => {
        $('#btn_cancel').hide();
        $('#btn_save').empty();
        $('#btn_save').append('Save');
        $('#edit_id').val('');
        $('#form_activity')[0].reset();
        $('#category_id').val(null).trigger('change')
        $('#status').val(null).trigger('change')
        $('#task_id').val(null).trigger('change')
    })

    $('#btn_pause').on('click', () => {
        var data = {
            action: 'hold',
            id: $('#hold_id').val(),
            reason: $('#hold_reason').val()
        }
        if ($('#hold_reason').val() != '') {
            cxDialog({
                info: 'Are you sure you want to Hold?',
                ok: () => {
                    $('#btn_pause').empty();
                    $('#btn_pause').append('<i class="fa fa-spinner fa-spin"></i> Loading...');
                    $('#btn_pause').prop("disabled", true);
                    axios({
                        method: 'post',
                        url: 'action',
                        data: data
                    }).then(function (response) {
                        if (response.data.status === 'success') {
                            toastr.success(response.data.message);
                            var checkfilter = ($('#filter_option').val() == "") ? 'all' : $('#filter_option').val();
                            ACTIVITY.load($('#filter_option').val(), $('#slct_filter').val());
                        }
                        $('#btn_pause').empty();
                        $('#btn_pause').append('Save');
                        $('#btn_pause').prop("disabled", false);
                        $('#modal_hold').modal('hide')
                    }).catch(error => {
                        toastr.error(error);
                    });
                },
                no: () => { },
            });
        }
    })


    $(document).on("click", ".btn_resume", function (event) {
        var target = event.target;
        var data = {
            action: 'resume',
            id: target.id,
        }
        cxDialog({
            info: 'Are you sure you want to resume?',
            ok: () => {
                axios({
                    method: 'post',
                    url: 'action',
                    data: data
                }).then(function (response) {
                    if (response.data.status === 'success') {
                        toastr.success(response.data.message);
                        var checkfilter = ($('#filter_option').val() == "") ? 'all' : $('#filter_option').val();
                        ACTIVITY.load($('#filter_option').val(), $('#slct_filter').val());
                    }
                }).catch(error => {
                    toastr.error(error);
                });
            },
            no: () => { },
        });
    });

    return this_activity;
})()
