

$(document).ready(function () {
    $('#btn_cancel').hide();
    STATUS.load();
    // Form Validation
    $("#form_status").validate({
        rules: {
            name: {
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

const STATUS = (() => {
    let this_status = {}

    //store data
    $('#form_status').on('submit', function (e) {
        e.preventDefault();
        var formdata = new FormData(this);
        $('#btn_save').empty();
        $('#btn_save').append('<i class="fa fa-spinner fa-spin"></i> Loading...');
        $('#btn_save').prop("disabled", true);
        $('#btn_cancel').prop("disabled", true);
        // Send a POST request
        axios({
            method: 'post',
            url: `${APP_URL}/status/store`,
            data: formdata
        }).then(function (response) {
            console.log(response)
            if (response.data.status === 'success') {
                toastr.success(response.data.message);
                $('#form_status')[0].reset();
                $('#btn_cancel').hide();
                STATUS.load();
            }else if (response.data.status === 'warning') {
                Object.keys(response.data.error).forEach((key) => {
                    toastr.warning(response.data.error[key][0]);
                });
            }
            $('#btn_save').empty();
            $('#btn_save').append('Submit');
            $('#btn_save').prop("disabled", false);
            $('#btn_cancel').prop("disabled", false);

        }).catch(error => {
            toastr.error(error);
        });
    });

    this_status.load = () => {
        axios(`${APP_URL}/status/all`).then(function (response) {
            $('#tbl_status').DataTable().destroy();
            var table;
            var x = 1;
            response.data.data.forEach(val => {
                table += `<tr>
                        <td>${x}</td>
                        <td>${val.name}</td>
                        <td>${val.createdby.name}</td>
                        <td>${val.created_at}</td>
                        <td>${val.updatedby != null ? val.updatedby.name : ''}</td>
                        <td>${val.updated_at}</td>
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
            $('#tbl_status tbody').html(table);
            $('#tbl_status').dataTable({
                "bJQueryUI": true,
                "sPaginationType": "full_numbers",
                "sDom": '<""l>t<"F"fp>',
                "bDestroy": true
            });
            toastr.success(response.data.message);
        }).catch(error => {
            toastr.error(error);
        });
    }

    document.addEventListener("click", function (event) {
        var target = event.target;
        if (this_status.isElement(target, 'btn_show')) {
            axios(`${APP_URL}/status/show/` + target.id).then(function (response) {
                $('#btn_save').empty();
                $('#btn_save').append('Save changes');
                $('#btn_cancel').show();
                $('#edit_id').val(response.data.id)
                $('#status_name').val(response.data.name)
            }).catch(error => {
                toastr.error(error);
            });
        }
    });

    document.addEventListener("click", function (event) {
        var target = event.target;
        if (this_status.isElement(target, 'btn_delete')) {
            cxDialog({
                info: 'Are you sure you want to delete?',
                ok: () => {
                    axios({
                        method: 'post',
                        url: `${APP_URL}/status/delete/` + target.id,
                    }).then(function (response) {
                        if (response.data.status === 'success') {
                            toastr.success(response.data.message);
                            STATUS.load();
                        }
    
                    }).catch(error => {
                        toastr.error(error);
                    });
                },
                no: () => {},
            });
        }
    });

    this_status.isElement = (element, className) => {
        return element.classList.contains(className) || element.closest(`.${className}`);
    }

    $('#btn_cancel').on('click', () => {
        $('#btn_cancel').hide();
        $('#btn_save').empty();
        $('#btn_save').append('Save');
        $('#edit_id').val('');
    })

    return this_status;
})()