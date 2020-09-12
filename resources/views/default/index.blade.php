@if($extendViewPath!="")
    @extends($extendViewPath)
@endif

@section("page_css")
@endsection

@section("page_js")
    <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    @if($viewData->enableExport)
        <script src="https://cdn.datatables.net/select/1.2.6/js/dataTables.select.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
    @endif
@endsection

@section('page_content')
<?php
$columns=$viewData->columns;
$exportFormats = $viewData->exportFormats;
?>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="row m-0">
                    <div class="col-sm-7">
                        <h4 class="header-title mb-0"><?php echo $tableTitle; ?></h4>
                    </div>
                    <div class="col-sm-5">
                        <div class="float-right">
                            <?php
                            if(isset($viewData->more_buttons_before))
                            {
                            $more_buttons = $viewData->more_buttons_before;

                            if(is_array($more_buttons) && count($more_buttons)>0)
                            {
                            foreach($more_buttons as $button)
                            {
                            ?>
                            <a href="<?php echo $button["url"]; ?>">
                                <div class="btn btn-<?php echo $button["btn"]; ?> btn-sm">
                                    <?php
                                    if(isset($button["icon"]))
                                    {
                                    ?>
                                    <span class="<?php echo $button["icon"]; ?>"></span>
                                    <?php
                                    }
                                    ?>
                                    &nbsp;&nbsp;<?php echo $button["label"]; ?>
                                </div></a>
                            <?php
                            }
                            }
                            }
                            ?>
                            <?php if($viewData->enableAdd && isset($viewData->add_url)){ ?>
                            <a href="<?php echo $viewData->add_url; ?>">
                                <div class="btn btn-info btn-sm"><span class="<?php echo $viewData->add_url_icon; ?>"></span> &nbsp;&nbsp;<?php echo $viewData->add_url_label; ?></div></a>
                            <?php } ?>
                            <?php
                            if(isset($viewData->more_buttons_after))
                            {
                            $more_buttons = $viewData->more_buttons_after;

                            if(is_array($more_buttons) && count($more_buttons)>0)
                            {
                            foreach($more_buttons as $button)
                            {
                            ?>
                            <a href="<?php echo $button["url"]; ?>">
                                <div class="btn btn-<?php echo $button["btn"]; ?> btn-sm">
                                    <?php
                                    if(isset($button["icon"]))
                                    {
                                    ?>
                                    <span class="<?php echo $button["icon"]; ?>"></span>
                                    <?php
                                    }
                                    ?>
                                    &nbsp;&nbsp;<?php echo $button["label"]; ?>
                                </div></a>
                            <?php
                            }
                            }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="clearfix mb-2 mt-2">
                    <div class="col-sm-12 d-flex justify-content-center">
                        <div id="export"></div>
                    </div>
                </div>

                <div class="adv-table table-responsive dataTables_wrapper dt-bootstrap4 no-footer">
                    <table class="display table" id="results">
                        <thead>
                        <tr>
                            <th class="text-left" style="max-width:50px;">No</th>
                            <?php
                            if(is_array($columns) && count($columns) > 0)
                            {
                                foreach($columns as $column => $column_data)
                                {
                                    if($column != "id" && isset($column_data["visible"]) && $column_data["visible"])
                                    {
                                        $label=$column_data["label"];
                                        ?>
                                        <th class="text-left"><?php echo $label; ?></th>
                                        <?php
                                    }
                                }
                            }
                            ?>
                            <?php
                            if($viewData->enableView || $viewData->enableEdit || $viewData->enableDelete || $viewData->enableRestore)
                            {
                                ?>
                                <th class="text-center" style="min-width:180px;">Actions</th>
                                <?php
                            }
                            ?>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th class="text-left">No</th>
                            <?php
                            if(is_array($columns) && count($columns) > 0)
                            {
                                foreach($columns as $column => $column_data)
                                {
                                    if($column != "id" && isset($column_data["visible"]) && $column_data["visible"])
                                    {
                                        $label=$column_data["label"];
                                        ?>
                                        <th class="text-left"><?php echo $label; ?></th>
                                        <?php
                                    }
                                }
                            }
                            ?>
                            <?php
                            if($viewData->enableView || $viewData->enableEdit || $viewData->enableDelete || $viewData->enableRestore)
                            {
                                ?>
                                <th class="text-center" style="min-width:180px;">Actions</th>
                                <?php
                            }
                            ?>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script class="init">
    let table = null;
    let columns = [];
    let columnsVisible = [];

    <?php
    if(is_array($columns) && count($columns) > 0)
    {
    foreach($columns as $column => $column_data)
    {
    ?>
    columns.push(<?php echo json_encode($column_data); ?>);
    <?php
    if($column_data["visible"])
    {
    ?>
    columnsVisible.push(<?php echo json_encode($column_data); ?>);
    <?php
    }
    }
    }
    ?>
    window.onload = function()
    {

        let start=0;
        window.start=0;

        <?php
        $exportable = array();
        if(is_array($columns) && count($columns) > 0)
        {
            $columns_count=count($columns);

            $i=-1;
            foreach($columns as $column => $column_data)
            {
                if($column_data["visible"])
                {
                    $i++;

                    if($column_data["exportable"])
                    {
                        $exportable[]=$i;
                    }
                }
            }
        }
        ?>
        let exportableColumns = <?php echo json_encode($exportable); ?>;

        table = $('#results').DataTable({
            processing	: true,
            serverSide	: true,
            "autoWidth"	: true,
            "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
            ajax	: {
                url 	: "<?php echo $viewData->this_url; ?>",
                type	: "POST",
                data 	: function(data)
                {
                    window.start=data.start;
                    data.submit = "submit";
                    data._token = "{{ csrf_token() }}";
                },
            },
            order: [
                <?php
                if(is_array($columns) && count($columns) > 0)
                {
                $columns_count=count($columns);

                $i=-1;
                foreach($columns as $column => $column_data)
                {
                if($column_data["visible"])
                {
                $i++;

                if($column_data["orderable"])
                {
                ?>
                [ <?php echo $i; ?>, "<?php echo strtolower($column_data["order"]); ?>" ],
                <?php
                }
                }
                }
                }
                ?>
            ],
            columns: [
                <?php
                if(isset($columns["id"]) && $columns["id"]["visible"] )
                {
                if(isset($columns["id"]["orderable"]) && $columns["id"]["orderable"])
                {
                    $orderable="true";
                    $order=$columns["id"]["order"];
                }
                else
                {
                    $orderable="false";
                    $order=$columns["id"]["order"];
                }
                ?>
                {
                    data		: "id",
                    searchable	: false,
                    orderable	: <?php echo $orderable; ?>,
                    order		: "<?php echo $order; ?>",
                    render		: function ( data, type, full, meta )
                    {
                        return "";
                    }
                },
                <?php
                }
                ?>
                <?php
                if(is_array($columns) && count($columns) > 0)
                {
                $columns_count=count($columns);

                $i=0;
                foreach($columns as $column => $column_data)
                {
                $i++;

                if($column != "id")
                {
                $visible=$column_data["visible"];
                $searchable=$column_data["searchable"];
                $orderable=$column_data["orderable"];
                $order=$column_data["order"];
                $filterMethod=$column_data["filterMethod"];
                $filterOptions=$column_data["filterOptions"];

                //echo json_encode($column_data);

                if($visible)
                {
                    $visible="true";
                }
                else
                {
                    $visible="false";
                }

                if($searchable)
                {
                    $searchable="true";
                }
                else
                {
                    $searchable="false";
                }

                if($orderable)
                {
                    $orderable="true";
                }
                else
                {
                    $orderable="false";
                }

                $search = "";
                if($filterMethod == "date_between")
                {
                    $date_from = "";
                    $date_till = "";
                    if(is_array($filterOptions) && count($filterOptions)>0)
                    {
                        if(isset($filterOptions["date_from"]))
                        {
                            $date_from = $filterOptions["date_from"];
                        }

                        if(isset($filterOptions["date_till"]))
                        {
                            $date_till = $filterOptions["date_till"];
                        }

                        if(isset($filterOptions["max_dates"]))
                        {
                            $max_dates = $filterOptions["max_dates"];
                        }
                    }

                    if($date_from!="" && $date_till!="")
                    {
                        $search = array();
                        $search["type"] = "date_between";
                        $search["date_from"] = $date_from;
                        $search["date_till"] = $date_till;

                        $search = json_encode($search);
                    }
                }

                if(isset($column_data["render"]))
                {
                ?>
                {
                    data		: "<?php echo $column; ?>",
                    searchable	: <?php echo $searchable; ?>,
                    orderable	: <?php echo $orderable; ?>,
                    order		: "<?php echo $order; ?>",
                    search		: "<?php echo $search; ?>",
                    render		: function ( data, type, full, meta )
                    {
                        <?php echo $column_data["render"]; ?>
                    }
                },
                <?php
                }
                else if(isset($column_data["visible"]) && $column_data["visible"])
                {
                ?>
                {
                    data		: "<?php echo $column; ?>",
                    searchable	: <?php echo $searchable; ?>,
                    orderable	: <?php echo $orderable; ?>,
                    order		: "<?php echo $order; ?>",
                    search		: '<?php echo $search; ?>'
                },
                <?php
                }
                }
                }
                }
                ?>
                <?php
                if($viewData->enableView || $viewData->enableEdit || $viewData->enableDelete || $viewData->enableRestore)
                {
                if(isset($columns["id"]))
                {
                if(isset($columns["id"]["render"]))
                {
                ?>
                {
                    data		: "id",
                    orderable	: false,
                    searchable	: false,
                    render		: function ( data, type, full, meta )
                    {
                        <?php echo $columns["id"]["render"]; ?>
                    }
                }
                <?php
                }
                else
                {
                ?>
                {
                    data		: "id",
                    orderable	: false,
                    searchable	: false,
                    render		: function ( data, type, full, meta )
                    {
                        let uiText = "";
                        uiText+='<div class="index-actions pull-right d-flex justify-content-center">';

                        <?php if($viewData->enableView && isset($viewData->view_url)){ ?>
                        uiText+='<a href="<?php echo $viewData->view_url; ?>'+full["id"]+'">';
                        uiText+='<div class="btn btn-xs"><span class="<?php echo $viewData->view_url_icon; ?>"></span> <?php echo $viewData->view_url_label; ?></div>';
                        uiText+='</a>';
                        <?php } ?>

                        <?php if($viewData->enableEdit && isset($viewData->edit_url)){ ?>
                        uiText+='<a href="<?php echo $viewData->edit_url; ?>'+full["id"]+'">';
                        uiText+='<div class="btn btn-xs"><span class="<?php echo $viewData->edit_url_icon; ?>"></span> <?php echo $viewData->edit_url_label; ?></div>';
                        uiText+='</a>';
                        <?php } ?>

                        <?php if($viewData->enableDelete && isset($viewData->delete_url)){ ?>
                        uiText+='<a href="javascript:;" onclick="return deleteConfirm('+full["id"]+')">';
                        uiText+='<div class="btn btn-xs"><span class="<?php echo $viewData->delete_url_icon; ?>"></span> <?php echo $viewData->delete_url_label; ?></div>';
                        uiText+='</a>';
                        <?php } ?>

                        <?php if($viewData->enableRestore && isset($viewData->restore_url)){ ?>
                        uiText+='<a href="javascript:;" onclick="return restoreConfirm('+full["id"]+')">';
                        uiText+='<div class="btn btn-xs"><span class="<?php echo $viewData->restore_url_icon; ?>"></span> <?php echo $viewData->restore_url_label; ?></div>';
                        uiText+='</a>';
                        <?php } ?>
                        uiText+='</div>';
                        uiText+='<input type="hidden" value="'+data+'" class="row-id row-id-'+full["id"]+'">';

                        return uiText;
                    }
                }
                <?php
                }
                }
                }
                ?>
            ],
            initComplete: function () {

                let api = this.api();

                let incr = 0;
                api.columns().indexes().flatten().each( function ( i ){

                    let column = api.column(i);

                    if($(columnsVisible[i]).length > 0 && columnsVisible[i].filterable)
                    {
                        incr++;

                        if(incr == 1)
                        {
                            let uiTop = '<div class="clearfix"></div><div class="container-fluid" style="padding-left: 2px; padding-right: 2px;"><div class="row"><div class="col-lg-12">';
                            uiTop+='<h5>Filter Results</h5>';
                            uiTop+='<div class="row filter-bar"></div></div></div></div>';

                            $("#results").before(uiTop);
                        }

                        let label = columnsVisible[i].label;
                        let filterMethod = columnsVisible[i].filterMethod;

                        if(filterMethod == "select")
                        {
                            let filterOptions = columnsVisible[i].filterOptions;

                            let uiText = '<div class="col-lg-3 col-md-3 col-sm-3 filter-by-label">Filter By '+label+'<br><div class="form-group" id="dt-search-col-'+i+'"></div></div>';
                            $("#results_wrapper .filter-bar").append(uiText);

                            if(typeof filterOptions == 'object')
                            {
                                let select = $('<select class="custom-select form-control"><option value="">Filter By '+label+'</option></select>').appendTo($("#dt-search-col-"+i)).on("change", function () {

                                    let val = $(this).val();
                                    column.search( val ? $(this).val() : val, true, false ).draw();
                                });

                                let uiText = "";
                                if(typeof filterOptions == 'object')
                                {
                                    $(filterOptions).each(function(index, elem) {

                                        uiText += '<option value="'+elem.id+'">'+elem.name+'</option>';
                                    });
                                }
                                else
                                {
                                    uiText = filterOptions;
                                }

                                select.append(uiText);
                            }
                            else
                            {
                                let select = $('<input type="text" class="form-control" placeholder="Filter By '+label+'">').appendTo($("#dt-search-col-"+i));

                                let selectElem = $(select).magicSuggest({
                                    allowFreeEntries: false,
                                    maxSelection:999,
                                    resultAsString: true,
                                    data: filterOptions,
                                    valueField: 'id',
                                    data: filterOptions,
                                    dataUrlParams:{"_token":"{{ csrf_token() }}"},
                                    maxSelectionRenderer: function () {
                                        return "";
                                    }
                                });

                                $(selectElem).on('selectionchange', function(e,m){

                                    let val = this.getValue();
                                    val = val.join(",");
                                    column.search( val ? val : "", true, false ).draw();
                                });
                            }
                        }
                        else if(filterMethod == "date")
                        {
                            let format = "yyyy-mm-dd";

                            let uiText = '<div class="col-lg-3 col-md-3 col-sm-3 filter-by-label">Filter By '+label+'<br><div class="form-group" id="dt-search-col-'+i+'"></div></div>';
                            $("#results_wrapper .filter-bar").append(uiText);

                            let date = $('<input type="text" class="custom-select custom-select-sm form-control default-date-picker" data-format="'+format+'" placeholder="Filter By '+label+'">').appendTo($("#dt-search-col-"+i));

                            $(date).datepicker({
                                format	: format,
                                orientation: 'bottom'
                            }).on("blur", function(e){

                                let val = this.value;
                                column.search( val ? val : "", true, false ).draw();
                            });
                        }
                        else if(filterMethod == "date_between")
                        {
                            let filterOptions = columnsVisible[i].filterOptions;

                            let date_from = "";
                            let date_till = "";
                            let max_dates = false;

                            if(typeof filterOptions == 'object')
                            {
                                if(filterOptions.date_from)
                                {
                                    date_from = filterOptions.date_from;
                                }

                                if(filterOptions.date_till)
                                {
                                    date_till = filterOptions.date_till;
                                }

                                if(filterOptions.max_dates)
                                {
                                    max_dates = filterOptions.max_dates;
                                }
                            }

                            let format = "yyyy-mm-dd";

                            let uiText = '<div class="col-lg-6 col-md-6 col-sm-6 filter-by-label">Filter By '+label+' | Dates Between<br>';
                            uiText += '<div class="row">';
                            uiText += '<div class="col-lg-6"><div class="form-group" id="dt-search-col-'+i+'-1"></div></div>';
                            uiText += '<div class="col-lg-6"><div class="form-group" id="dt-search-col-'+i+'-2"></div></div>';
                            uiText += '</div>';
                            uiText += '</div>';

                            $("#results_wrapper .filter-bar").append(uiText);

                            let date1 = $('<input type="text" class="form-control default-date-picker" value="'+date_from+'" data-format="'+format+'" placeholder="Date From">').appendTo($("#dt-search-col-"+i+'-1'));
                            let date2 = $('<input type="text" class="form-control default-date-picker" value="'+date_till+'" data-format="'+format+'" placeholder="Date Till">').appendTo($("#dt-search-col-"+i+'-2'));

                            $(date1).datepicker({
                                format	: format,
                                orientation: 'bottom'
                            }).on("blur", function(e){

                                $(date2).val("");

                                let val_1 = $(date1).val();
                                let val_2 = $(date2).val();

                                if(val_1 != "" && val_2 != "")
                                {
                                    if(val_1<=val_2)
                                    {
                                        let val = {};
                                        val.type = "date_between";
                                        val.date_from = val_1;
                                        val.date_till = val_2;

                                        val = JSON.stringify(val);
                                        column.search( val, true, false ).draw();
                                    }
                                    else
                                    {
                                        alert("'Date From' should not be greater than 'Date Till'");
                                    }
                                }

                                if(max_dates)
                                {
                                    max_dates = parseInt(max_dates);

                                    let thisDate = $(date1).datepicker("getDate");
                                    let endDate = new Date(new Date(thisDate).setDate(new Date(thisDate).getDate() + max_dates));
                                    $(date2).datepicker("setStartDate", thisDate);
                                    $(date2).datepicker("setEndDate", endDate);

                                    console.log(endDate);
                                }
                            });

                            $(date2).datepicker({
                                format	: format,
                                orientation: 'bottom'
                            }).on("blur", function(e){

                                let val_1 = $(date1).val();
                                let val_2 = $(date2).val();

                                if(val_1 != "" && val_2 != "")
                                {
                                    if(val_1<=val_2)
                                    {
                                        let val = {};
                                        val.type = "date_between";
                                        val.date_from = val_1;
                                        val.date_till = val_2;

                                        val = JSON.stringify(val);
                                        column.search( val, true, false ).draw();
                                    }
                                    else
                                    {
                                        $(date2).val("");
                                        alert("'Date From' should not be greater than 'Date Till'");
                                    }
                                }
                            });

                            if(date_from!="" && date_till!="")
                            {
                                max_dates = parseInt(max_dates);

                                $(date2).datepicker("setStartDate", date_from);
                                $(date2).datepicker("setEndDate", new Date(new Date(date_from).setDate(new Date(date_from).getDate() + max_dates)));
                            }
                        }
                        else if(filterMethod == "text")
                        {
                            let uiText = '<div class="col-lg-3 col-md-3 col-sm-3 filter-by-label">Filter By '+label+'<br><div class="form-group" id="dt-search-col-'+i+'"></div></div>';
                            $("#results_wrapper .filter-bar").append(uiText);

                            $('<input type="text" class="form-control dt-search" placeholder="Filter By '+label+'" />').appendTo($("#dt-search-col-"+i)).on("blur", function () {

                                let val = $(this).val();
                                column.search( val ? $(this).val() : val, true, false ).draw();
                            });
                        }
                    }
                });
            }
        });

        <?php
        if($viewData->enableExport && count($viewData->exportFormats)>0)
        {
        ?>
        new $.fn.dataTable.Buttons(table, {
            "buttons": [
                <?php
                if(in_array("copy", $exportFormats))
                {
                ?>
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: exportableColumns,
                    },
                    className	: "btn btn-info btn-sm",
                },
                <?php
                }
                if(in_array("excel", $exportFormats))
                {
                ?>
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: exportableColumns,
                    },
                    className	: "btn btn-info btn-sm",
                },
                <?php
                }
                if(in_array("csv", $exportFormats))
                {
                ?>
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: exportableColumns,
                    },
                    className	: "btn btn-info btn-sm",
                },
                <?php
                }
                if(in_array("pdf", $exportFormats))
                {
                ?>
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: exportableColumns,
                    },
                    className	: "btn btn-info btn-sm",
                },
                <?php
                }
                if(in_array("print", $exportFormats))
                {
                ?>
                {
                    extend: 'print',
                    exportOptions: {
                        columns: exportableColumns,
                    },
                    className	: "btn btn-info btn-sm",
                },
                <?php
                }
                ?>
            ]
        }).container().appendTo($('#export'));

        <?php
        }
        ?>


        setTableRowNumbers();
        setLinkTooltips();
        callOnDraw();
    };

    function callOnDraw()
    {
        let callBack="onDrawCallBack";
        let callBackArgs="";
        let table = $('#results').DataTable();

        table.on( 'draw.dt order.dt search.dt', function () {

            if(typeof window[callBack] === 'function'){

                window[callBack].apply(undefined, callBackArgs.split(","))
            }
        });
    }

    function onDrawCallBack()
    {
        if($('.selectpicker') && $('.selectpicker').length>0)
        {
            $('.selectpicker').selectpicker('show');
        }
    }

    function setTableRowNumbers()
    {
        let table = $('#results').DataTable();

        table.on( 'draw.dt order.dt search.dt', function () {
            table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {

                i=i+window.start;

                if(i+1 < 10)
                {
                    cell.innerHTML = "0"+(i+1);
                }
                else
                {
                    cell.innerHTML = i+1;
                }
            });
        });
    }

    function setLinkTooltips()
    {
        let table = $('#results').DataTable();

        table.on( 'draw.dt', function () {

            //set tooltip
            $("table#results").find("a").each(function(index, element) {

                let href = $(this).attr("href");
                let titleText ="";
                if(!$(this).attr("title") || !($(this).attr("title") && $(this).attr("title").length > 0))
                {
                    titleText = "Go to "+$(this).text();

                    if(href.match("mailto:"))
                    {
                        titleText = "Send an email to "+href.split("mailto:").join("");
                    }

                    $(this).attr("title", titleText);
                }

                $(this).attr("data-placement", "top");
                $(this).attr("data-toggle", "tooltip");
            });

            $("table#results").find("a").tooltip();
        });
    }

    function deleteConfirm(id)
    {
        showConfirmation('', 'deleteRecord', id)
    }

    <?php if(isset($viewData->delete_url)){ ?>
    function deleteRecord(id)
    {
        showPreloader($("#results").parent(), true);
        $.ajax({
            url		: "<?php echo $viewData->delete_url; ?>"+id,
            dataType: "JSON",
            type	: "POST",
            data    :{"_token":"{{ csrf_token() }}"},
            success	: function(theResponse)
            {
                hidePreloader($("#results").parent());
                //check the reponse status
                if(theResponse.notify.status == "success")
                {
                    $(".row-id-"+id).parent().parent().remove();
                    setTableRowNumbers();
                }

                showNotifications(theResponse.notify);
            },
            error	: function()
            {
                hidePreloader($("#results").parent());
            }
        });
    }
    <?php
    }
    ?>

    function restoreConfirm(id)
    {
        showConfirmation('Are you sure you want to restore this record? Then confirm.', 'restoreRecord', id)
    }

    <?php if(isset($viewData->restore_url)){ ?>
    function restoreRecord(id)
    {
        showPreloader($("#results").parent(), true);
        $.ajax({
            url		: "<?php echo $viewData->restore_url; ?>"+id,
            dataType: "JSON",
            type	: "POST",
            data    :{"_token":"{{ csrf_token() }}"},
            success	: function(theResponse)
            {
                hidePreloader($("#results").parent());
                //check the reponse status
                if(theResponse.notify.status == "success")
                {
                    $(".row-id-"+id).parent().parent().remove();
                    setTableRowNumbers();
                }

                showNotifications(theResponse.notify);
            },
            error	: function()
            {
                hidePreloader($("#results").parent());
            }
        });
    }
    <?php
    }
    ?>
</script>

@endsection
