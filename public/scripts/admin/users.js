

$(document).ready(function () {
    $('.currentpage').html('<a href="'+window.location+'" class="tip-bottom"><i class="fa fa-users"></i>Users</a>')
    $('#btn_cancel').hide();
    USER.load();
    // Form Validation
    $("#form_user").validate({
        rules: {
            emp_id: {
                required: true
            },
            name: {
                required: true
            },
            email: {
                required: true
            },
            role: {
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

const USER = (() => {
    let this_user = {}

    //store data
    $('#form_user').on('submit', function (e) {
        e.preventDefault();
        var formdata = new FormData(this);
        $('#btn_save').empty();
        $('#btn_save').append('<i class="fa fa-spinner fa-spin"></i> Loading...');
        $('#btn_save').prop("disabled", true);
        $('#btn_cancel').prop("disabled", true);
        // Send a POST request
        axios({
            method: 'post',
            url: 'user/store',
            data: formdata
        }).then(function (response) {
            if (response.data.status === 'success') {
                toastr.success(response.data.message);
                $('#form_user')[0].reset();
                $('#edit_id').val('');
                $('#btn_cancel').hide();
                USER.load();
            } else if (response.data.status === 'warning') {
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

    this_user.load = () => {
        $("#loading").show();
        axios('user/all').then(function (response) {
            $('#tbl_users').DataTable().destroy();
            var table;
            var x = 1;
            response.data.data.forEach(val => {
                var profile = (val.profile != null) ? `<img src="../storage/profiles/${val.profile}" alt="profile" class="small-profile"/>
                            <i class="typcn typcn-download btn-icon-append"></i>
                        </a>` : '<img src="../themes/images/faces/avatar.png" alt="profile" class="small-profile"/>'
                table += `<tr>
                        <td>${x}</td>
                        <td>${profile}</td>
                        <td>${val.emp_id}</td>
                        <td>${val.name}</td>
                        <td>${val.email}</td>
                        <td>${val.role}</td>
                        <td>${val.createdby.name}</td>
                        <td>${val.created_at}</td>
                        <td>${val.updatedby != null ? val.updatedby.name : ''}</td>
                        <td>${val.updated_at}</td>
                        <td>
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Action <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="#" class="btn_show" data-id="${val.id}"><i class="icon-edit"></i> Edit</a></li>
                                    <li><a href="#" class="btn_delete" data-id="${val.id}"><i class="icon-trash"></i> Delete</a></li>
                                </ul>
                            </div>
                        </td>
                     </tr>`;
                x++;
            });
            $('#tbl_users tbody').html(table);
            datatables('tbl_users');
            $("#loading").hide();
            toastr.success(response.data.message);
        }).catch(error => {
            toastr.error(error);
        });
    }

    $(document).on("click", ".btn_show", function (event) {
        var id = $(this).attr("data-id");
        axios('user/show/' + id).then(function (response) {
            $('#btn_save').empty();
            $('#btn_save').append('Save changes');
            $('#btn_cancel').show();
            $('#emp_id').val(response.data.emp_id)
            $('#fullname').val(response.data.name)
            $('#email').val(response.data.email)
            $('#role').val(response.data.role)
            $('#edit_id').val(response.data.id)
        }).catch(error => {
            toastr.error(error);
        });
    });

    $(document).on("click", ".btn_delete", function (event) {
        var id = $(this).attr("data-id");
        cxDialog({
            info: 'Are you sure you want to delete?',
            ok: () => {
                axios({
                    method: 'post',
                    url: 'user/delete/' + id,
                }).then(function (response) {
                    if (response.data.status === 'success') {
                        toastr.success(response.data.message);
                        USER.load();
                    }

                }).catch(error => {
                    toastr.error(error);
                });
            },
            no: () => { },
        });
    });

    $('#btn_cancel').on('click', () => {
        $('#btn_cancel').hide();
        $('#btn_save').empty();
        $('#btn_save').append('Save');
        $('#edit_id').val('');
    })

    return this_user;
})()