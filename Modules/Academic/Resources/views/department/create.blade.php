@extends('academic::layouts.master')

@section('page_content')
    <form action="javascript:;" id="create_form">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row m-0">
                            <div class="col-sm-6">
                                <?php
                                if($formMode == "add")
                                {
                                    ?>
                                    <h4 class="header-title">Add New Department</h4>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <h4 class="header-title">Edit Department</h4>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <div class="float-right">
                                    <?php
                                        if($formMode=="edit")
                                        {
                                            ?>
                                            <a href="/academic/department/create">
                                                <div class="btn btn-info btn-sm"><span class="fa fa-plus"></span> Add New</div>
                                            </a>
                                            <?php
                                        }
                                    ?>

                                    <a href="/academic/department">
                                        <div class="btn btn-info btn-sm"><span class="fa fa-list"></span> List Departments</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Select Faculty Name</label>
                                    <hr class="mt-1 mb-2">
                                    <input type="text" class="form-control" name="faculty" placeholder="Select Faculty">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Department Name</label>
                                    <hr class="mt-1 mb-2">
                                    <input type="text" class="form-control" name="dept_name" placeholder="Department Name" value="<?php echo $record["dept_name"]; ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Department Colour Code</label>
                                    <hr class="mt-1 mb-2">
                                    <div id="component-colorpicker" class="input-group">
                                        <input type="text" class="form-control" name="color_code" placeholder="#ff0000" value="<?php echo $record["color_code"]; ?>">
                                        <span class="input-group-append">
                                            <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-add-row">Save</button>
                                    <button class="btn btn-dark" type="reset">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="faculty_id" value="<?php echo $record["color_code"]; ?>">
    </form>

    <?php
    $faculty_id = [];
    if(isset($record["faculty"]))
    {
        $faculty_id[]=$record["faculty"];
    }
    ?>
    <script>
        let faculty_id_ms=null;
        window.onload = function()
        {
            submitCreateForm();

            faculty_id_ms = $("input[name='faculty']").magicSuggest({
                allowFreeEntries: false,
                maxSelection:1,
                data: "/academic/faculty/searchData",
                dataUrlParams:{"_token":"{{ csrf_token() }}"},
                value:<?php echo json_encode($faculty_id) ?>,
            });

            $(faculty_id_ms).on('selectionchange', function(e,m){
                let faculties = this.getValue();

                $("input[name='faculty_id']").val(faculties[0]);
            });

            $("#component-colorpicker").colorpicker({format:"auto"});
        };

        function isValidColor(color_code)
        {
            if (/^#[0-9a-f]{3}([0-9a-f]{3})?$/i.test(color_code))
            {
                return true;
            }
            else if(/^rgb\s*(\s*[012]?[0-9]{1,2}\s*,\s*[012]?[0-9]{1,2}\s*,\s*[012]?[0-9]{1,2}\s*)$/i.test(color_code))
            {
                return true;
            }

            return false;
        }

        function submitCreateForm()
        {
            $('#create_form').ajaxForm({
                url			: "<?php if(isset($formSubmitUrl)){ echo URL::to($formSubmitUrl); } ?>",
                type		: "POST",
                dataType	: "json",
                beforeSubmit: validateForm,
                success		: serverResponse,
                error		: onError
            });
        }

        function validateForm(formData, jqForm)
        {
            let form = jqForm[0];

            let errors=0;
            let errorText=[];

            let dept_name=form.dept_name.value;
            let color_code=form.color_code.value;

            errorText.push("<strong> <span class='glyphicon glyphicon-warning-sign'></span> Following errors occured while submitting the form</strong><br/>");

            if(faculty_id_ms.getValue() == "")
            {
                errors++;
                errorText.push('Faculty Name required.');
            }

            if(dept_name == "")
            {
                errors++;
                errorText.push('Department Name Required.');
            }

            if(!isValidColor(color_code))
            {
                errors++;
                errorText.push('Valid Color Code Required.');
            }

            if(errors > 0)
            {
                let errorData=[];
                errorData.status="warning";
                errorData.notify=errorText;

                showNotifications(errorData);

                return false;
            }
            else
            {
                //show preloader
                showPreloader($('#create_form'), true);

                //disable form submit
                $('#create_form button[type="submit"]').attr("disabled", "disabled");
                return true;
            }
        }

        function serverResponse(responseText)
        {
            //Hide preloader
            hidePreloader($('#create_form'));
            $('#create_form button[type="submit"]').removeAttr("disabled");

            <?php
            if($formMode === "add")
            {
                ?>
                if(responseText.notify.status && responseText.notify.status == "success")
                {
                    $("#create_form").trigger("reset");
                    faculty_id_ms.setValue([]);
                }
                <?php
            }
            ?>

            showNotifications(responseText.notify)
        }

        function onError()
        {
            //Hide preloader
            hidePreloader($("#create_form"));
            $('#create_form button[type="submit"]').removeAttr("disabled");

            let errorText=[];
            let errorData=[];

            errorText.push('Something went wrong. Please try again.');

            errorData.status="warning";
            errorData.notify=errorText;

            showNotifications(errorData)
        }
    </script>
@endsection

@section('page_css')
    <link href="{{ asset('assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
@endsection

@section('page_js')
    <script src="{{ asset('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
@endsection
