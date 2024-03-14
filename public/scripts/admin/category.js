

$(document).ready(function () {
    $('.currentpage').html('<a href="'+window.location+'" class="tip-bottom"><i class="fa fa-list"></i>Category</a>')
    $('#btn_cancel').hide();
    CATEGORY.load();
    // Form Validation
    $("#form_category").validate({
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

const CATEGORY = (() => {
    let this_category = {}

    //store data
    $('#form_category').on('submit', function (e) {
        e.preventDefault();
        var formdata = new FormData(this);
        $('#btn_save').empty();
        $('#btn_save').append('<i class="fa fa-spinner fa-spin"></i> Loading...');
        $('#btn_save').prop("disabled", true);
        $('#btn_cancel').prop("disabled", true);
        // Send a POST request
        axios({
            method: 'post',
            url: 'category/store',
            data: formdata
        }).then(function (response) {
            console.log(response)
            if (response.data.status === 'success') {
                toastr.success(response.data.message);
                $('#edit_id').val(null);
                $('#form_category')[0].reset();
                $('#btn_cancel').hide();
                CATEGORY.load();
            } else if (response.data.status === 'warning') {
                Object.keys(response.data.error).forEach((key) => {
                    toastr.warning(response.data.error[key][0]);
                });
            }
            $('#btn_save').empty();
            $('#btn_save').append('Save');
            $('#btn_save').prop("disabled", false);
            $('#btn_cancel').prop("disabled", false);


        }).catch(error => {
            toastr.error(error);
        });
    });

    this_category.load = () => {
        $("#loading").show();
        axios('../show/all').then(function (response) {
            $('#tbl_category').DataTable().destroy();
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
            $('#tbl_category tbody').html(table);
            datatables('tbl_category');
            $("#loading").hide();
            toastr.success(response.data.message);
        }).catch(error => {
            toastr.error(error);
        });
    }

    $(document).on("click", ".btn_show", function (event) {
        var target = event.target;
        axios('category/show/' + target.id).then(function (response) {
            $('#btn_save').empty();
            $('#btn_save').append('Save changes');
            $('#btn_cancel').show();
            $('#category_name').val(response.data.name)
            $('#edit_id').val(response.data.id)
        }).catch(error => {
            toastr.error(error);
        });
    });


    $(document).on("click", ".btn_delete", function (event) {
        var target = event.target;
        cxDialog({
            info: 'Are you sure you want to delete?',
            ok: () => {
                axios({
                    method: 'post',
                    url: 'category/delete/' + target.id,
                }).then(function (response) {
                    if (response.data.status === 'success') {
                        toastr.success(response.data.message);
                        CATEGORY.load();
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
    return this_category;
})()