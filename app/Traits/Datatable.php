<?php
namespace App\Traits;

use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

trait Datatable
{
    public $model = null;

    private $tableColumns = NULL;
    private $columns = array();
    private $primaryKey = "";

    public $viewData = null;
    public $viewPath = "default.index";
    public $extendViewPath = null;
    public $tableTitle = "";

    /**
     * @param $model
     */
    public function initDatatable($model)
    {
        $this->model = $model;

        //set variable, values
        $this->tableColumns=$this->getTableColumns();

        $this->viewData = (object) array();

        $this->viewData->enableAdd=true;
        $this->viewData->enableEdit=true;
        $this->viewData->enableView=true;
        $this->viewData->enableDelete=true;
        $this->viewData->enableRestore=false;
        $this->viewData->enableList=true;
        $this->viewData->enableExport=false;
        $this->viewData->exportFormats = array("copy", "csv", "excel", "pdf", "print");
    }

    /**
     * @param mixed $model
     * @return array
     */
    public function getTableColumns($model=false)
    {
        if(!$model)
        {
            $model = $this->model;
        }

        return $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
    }

    /**
     * Setting which columns should be showed in view
     * @param array $newColumns
     * @return Datatable
     */
    public function setColumns($newColumns=array())
    {
        $columns=$this->columns;

        if(is_array($newColumns) && count($newColumns) > 0)
        {
            foreach($newColumns as $column)
            {
                $columns[$column] = $this->buildDefaultColumn($column);
            }
        }
        else
        {
            $noa = func_num_args(); // number of argument passed,(Number of columns)

            for ($i=0; $i<$noa; $i++)
            {
                $column=func_get_arg($i); // get each argument passed

                $columns[$column] = $this->buildDefaultColumn($column);
            }
        }
        $this->columns=$columns;

        return $this;
    }

    /**
     * @param string $column
     * @return array
     */
    private function buildDefaultColumn($column)
    {
        $defaultColumn = [];

        $explode_del="_";
        $implode_del=" ";
        $column_label=ucwords(implode($implode_del, explode($explode_del, $column)));

        //set field and field's label
        $defaultColumn["label"]=$column_label;
        $defaultColumn["visible"]=true;
        $defaultColumn["filterMethod"]="normal";
        $defaultColumn["orderable"]=true;
        $defaultColumn["filterable"]=false;
        $defaultColumn["exportable"]=true;
        $defaultColumn["filterOptions"]=array();
        $defaultColumn["relation"]=""; //for fields which is having ORM relationships
        $defaultColumn["relation_field"]=""; //for fields which is having ORM relationships

        if($column == "id")
        {
            $this->primaryKey = $this->model->getKeyName();
            $defaultColumn["db_field"]=$this->primaryKey;
            $defaultColumn["searchable"]=false;
            $defaultColumn["order"]="DESC";
        }
        else
        {
            $defaultColumn["searchable"]=true;
            $defaultColumn["order"]="ASC";

            $defaultColumn["db_field"]=$column;
            $defaultColumn["fkey_field"]=$column;
        }

        return $defaultColumn;
    }

    /**
     * Setting field labels to pass to the view
     * @param array $unsetColumns
     * @return Datatable
     */
    public function unsetColumns($unsetColumns=array())
    {
        $columns=$this->columns;

        if(is_array($unsetColumns) && count($unsetColumns) > 0)
        {
            foreach($unsetColumns as $column)
            {
                if($column != "" && isset($columns[$column]))
                {
                    unset($columns[$column]);
                }
            }
        }
        else
        {
            $noa = func_num_args(); // number of argument passed,(Number of columns)

            for ($i=0; $i<$noa; $i++)
            {
                $column=func_get_arg($i); // get each argument passed

                if($column != "" && isset($columns[$column]))
                {
                    unset($columns[$column]);
                }
            }
        }

        $this->columns=$columns;

        return $this;
    }

    /**
     * Setting field labels to pass to the view
     * @param string $column
     * @param string $label
     * @return Datatable
     */
    public function setColumnLabel($column="", $label="")
    {
        $columns=$this->columns;
        if($column != "" && $label != "" && isset($columns[$column]))
        {
            //set field and field's label
            $columns[$column]["label"]=$label;
        }

        $this->columns=$columns;

        return $this;
    }

    /**
     * Setting field visibility to pass to the view
     * @param string $column
     * @param bool $visibility
     * @return Datatable
     */
    public function setColumnVisibility($column="", $visibility=true)
    {
        $columns=$this->columns;

        if($column != "" && isset($columns[$column]))
        {
            //set field and field's label
            $columns[$column]["visible"]=$visibility;
        }

        $this->columns=$columns;

        return $this;
    }

    /**
     * Setting field searchable to pass to the view
     * @param string $column
     * @param bool $searchable
     * @return Datatable
     */
    public function setColumnSearchability($column="", $searchable=true)
    {
        $columns=$this->columns;

        if($column != "" && isset($columns[$column]))
        {
            //set field and field's label
            $columns[$column]["searchable"]=$searchable;
        }

        $this->columns=$columns;

        return $this;
    }

    /**
     * Setting field search type to pass to the view
     * @param string $column
     * @param string $filterMethod
     * @param array $filterOptions
     * @return Datatable
     */
    public function setColumnFilterMethod($column="", $filterMethod="text", $filterOptions = array())
    {
        $columns=$this->columns;

        if($column != "" && isset($columns[$column]))
        {
            //set field and field's label
            $columns[$column]["filterable"]=true;
            $columns[$column]["filterMethod"]=$filterMethod;
            $columns[$column]["filterOptions"]=$filterOptions;

            //filterMethod can be : text, select
        }

        $this->columns=$columns;

        return $this;
    }

    /**
     * Setting field orderable to pass to the view
     * @param string $column
     * @param bool $orderable
     * @param string $order
     * @return Datatable
     */
    public function setColumnOrderability($column="", $orderable=true, $order = "ASC")
    {
        $columns=$this->columns;

        if($column != "" && isset($columns[$column]))
        {
            //set field and field's label
            $columns[$column]["orderable"]=$orderable;
            $columns[$column]["order"]=$order;
        }

        $this->columns=$columns;

        return $this;
    }

    /**
     * Setting field exportable to pass to the view
     * @param string $column
     * @param bool $exportable
     * @return Datatable
     */
    public function setColumnExportability($column="", $exportable=true)
    {
        $columns=$this->columns;

        if($column != "" && isset($columns[$column]))
        {
            //set field and field's label
            $columns[$column]["exportable"]=$exportable;
        }

        $this->columns=$columns;

        return $this;
    }

    /**
     * Setting field orderable to pass to the view
     * @param string $column
     * @param string $db_field
     * @return Datatable
     */
    public function setColumnDBField($column="", $db_field="")
    {
        $columns=$this->columns;

        if($column != "" && $db_field != "" && isset($columns[$column]))
        {
            //set field and field's label
            $columns[$column]["db_field"]=$db_field;
        }

        $this->columns=$columns;

        return $this;
    }

    /**
     * Setting field orderable to pass to the view
     * @param string $column
     * @param string $fkey_field
     * @return Datatable
     */
    public function setColumnFKeyField($column="", $fkey_field="")
    {
        $columns=$this->columns;

        if($column != "" && $fkey_field != "" && isset($columns[$column]))
        {
            //set field and field's label
            $columns[$column]["fkey_field"]=$fkey_field;
        }

        $this->columns=$columns;

        return $this;
    }

    /**
     * Setting field relation to match with the ORM
     * @param string $column
     * @param string $relation
     * @param string $relation_field
     * @return Datatable
     */
    public function setColumnRelation($column="", $relation, $relation_field)
    {
        $columns=$this->columns;

        if($column != "" && $relation != "" && $relation_field != "" && isset($columns[$column]))
        {
            //set field and field's label
            $columns[$column]["relation"]=$relation;
            $columns[$column]["relation_field"]=$relation_field;
        }

        $this->columns=$columns;

        return $this;
    }

    /**
     * Setting fields, how to display in the output
     * @param string $column
     * @param $call_back
     * @param array $params
     * @return Datatable
     */
    public function setColumnDisplay($column="", $call_back, $params=array())
    {
        $columns=$this->columns;

        if($column != "" && isset($columns[$column]))
        {
            //set fields display settings
            $columns[$column]["render"]=call_user_func_array($call_back, $params);
        }

        $this->columns=$columns;

        return $this;
    }

    /**
     * Getting which fields to be filtered from the select query, that'll be executing
     * @return array
     */
    public function getColumns()
    {
        //check columns has been not set from controller
        if(count($this->columns) == 0)
        {
            //then set default table's columns
            $tableColumns=$this->tableColumns;

            $this->setColumns($tableColumns);
        }

        return $this->columns;
    }

    public function render($path)
    {
        $this->extendViewPath = $path;

        return $this;
    }

    /**
     * Generate columns to pass to the UI or read and serve data according to the request
     * @param $qBuilder
     * @return mixed
     */
    public function index($qBuilder)
    {
        if(isset($_POST["submit"]))
        {
            //create an instance of Form library
            $request = request();

            $draw=$request->post("draw");
            $start=$request->post("start");
            $length=$request->post("length");
            $columns=$request->post("columns");
            $order=$request->post("order");
            $search_get=$request->post("search");
            $model_columns=$this->getColumns();

            $main_search_value = "";
            if(isset($search_get) && is_array($search_get) && count($search_get) > 0)
            {
                $main_search_value=$search_get["value"];
            }

            if(isset($columns) && is_array($columns) && count($columns) > 0)
            {
                $searchFieldCount = 0;
                $defaultSearchFields = [];
                foreach($columns as $key => $column)
                {
                    $field=$column["data"];

                    $db_field=$model_columns[$field]["db_field"];
                    $filterable=$model_columns[$field]["filterable"];

                    $fkey_field=$field;
                    if(isset($model_columns[$field]["fkey_field"]))
                    {
                        $fkey_field=$model_columns[$field]["fkey_field"];
                    }

                    if($filterable)
                    {
                        $search=$column["search"];
                        $search_value=rawurldecode($search["value"]);

                        if($search_value != "")
                        {
                            $search_value_arr = @json_decode($search_value, true);
                            if(is_array($search_value_arr))
                            {
                                if(isset($search_value_arr["type"]))
                                {
                                    if($search_value_arr["type"] == "date_between")
                                    {
                                        $date_from = $search_value_arr["date_from"];
                                        $date_till = $search_value_arr["date_till"];

                                        $qBuilder->whereBetween("LEFT(".$db_field.",10)", [$date_from, $date_till]);
                                    }
                                }
                            }
                            else if(isset($model_columns[$field]["filterMethod"]) && $model_columns[$field]["filterMethod"] == "select")
                            {
                                $sv_exp_del = ",";
                                $search_value_exp = explode($sv_exp_del, $search_value);

                                if(is_array($search_value_exp) && count($search_value_exp)>1)
                                {
                                    $qBuilder->whereIn($fkey_field, $search_value_exp);
                                }
                                else
                                {
                                    $qBuilder->where($fkey_field, "=", $search_value);
                                }
                            }
                            else
                            {
                                $qBuilder->where($db_field, "LIKE", "%".$search_value."%");
                            }
                        }
                        else
                        {
                            $defaultSearchFields[] = $field;
                        }
                    }
                }

                //check if it has been set a default value for search
                if($main_search_value != "")
                {
                    if(count($defaultSearchFields)>0)
                    {
                        $qBuilder->where(function ($qBuilder) use($defaultSearchFields, $main_search_value, $model_columns){

                            foreach ($defaultSearchFields as $field)
                            {
                                $column = $model_columns[$field];

                                if($column["relation"] != "" && $column["relation_field"] != "")
                                {
                                    $relation=$column["relation"];
                                    $relation_field=$column["relation_field"];

                                    $qBuilder->orWhereHas($relation, function ($query) use ($relation_field, $main_search_value) {

                                        $query->where($relation_field, 'LIKE', '%'. $main_search_value .'%');
                                    });
                                }
                                else
                                {
                                    $db_field=$column["db_field"];
                                    $qBuilder->orWhere($db_field, "LIKE", "%".$main_search_value."%");
                                }
                            }
                        });
                    }
                }
            }

            //get count from sql query, because laravel is getting the count once after retrieving all the records according to the conditions
            //which has passed to the query builder. It consumes more memory and it's an unwanted operation
            //instead of that we will get the count of records directly from the database using db query
            $qBAll = $qBuilder->select(DB::raw("COUNT(DISTINCT ".$this->primaryKey.") AS count"))->first();
            $all_count = $qBAll["count"];

            //changing select, because query builder only knows above select, but not what we want
            $qBuilder->select("*");

            if(isset($order) && is_array($order) && count($order) > 0)
            {
                foreach($order as $key => $order_value)
                {
                    $field_order=$order_value["dir"];
                    $field_index=$order_value["column"];

                    $field=$columns[$field_index]["data"];

                    $db_field=$field;
                    if(isset($model_columns[$field]["db_field"]))
                    {
                        $db_field=$model_columns[$field]["db_field"];
                    }

                    $qBuilder->orderBy($db_field, $field_order);
                }
            }

            $results=$qBuilder->limit($length)->offset($start)->get();

            $data_output=[];

            if($results)
            {
                $filtered_count=count($results);

                $data_output["draw"]=$draw;
                $data_output["recordsTotal"]=$all_count;
                $data_output["recordsFiltered"]=$filtered_count;
                $data_output["data"]=$results;
            }
            else
            {
                $data_output["draw"]=$draw;
                $data_output["recordsTotal"]=0;
                $data_output["recordsFiltered"]=0;
                $data_output["data"]=[];
            }

            echo json_encode($data_output);
        }
        else
        {
            $this->viewData->columns=$this->getColumns();
            $this->set_index_urls();

            $viewData = $this->viewData;
            $extendViewPath = config("academic.datatable_template");

            if($this->extendViewPath != "")
            {
                $extendViewPath = $this->extendViewPath;
            }
            $tableTitle = $this->viewData->tableTitle;

            return view($this->viewPath, compact("tableTitle","extendViewPath", "viewData"));
        }
    }

    /**
     * Build viewData variable to pass to the UI
     * @return void
     */
    private function set_index_urls()
    {
        if(!isset($this->viewData->this_url))
        {
            $uri = request()->path();

            $route = collect(\Route::getRoutes())->first(function($route) use($uri){

                $method = request()->method();
                return $route->matches(request()->create($uri, $method));
            });

            $uri = $route->uri;
            $this->viewData->this_url=URL::to($uri);
        }

        if(!isset($this->viewData->add_url) && $this->viewData->enableAdd)
        {
            $this->viewData->add_url=$this->viewData->this_url."/create";
        }
        if(!isset($this->viewData->add_url_label))
        {
            $this->viewData->add_url_label="Add New";
        }
        if(!isset($this->viewData->add_url_icon))
        {
            $this->viewData->add_url_icon="Add New";
        }
        //----

        if(!isset($this->viewData->edit_url) && $this->viewData->enableEdit)
        {
            $this->viewData->edit_url=$this->viewData->this_url."/edit/";
        }
        if(!isset($this->viewData->edit_url_label))
        {
            $this->viewData->edit_url_label="Edit";
        }
        if(!isset($this->viewData->edit_url_icon))
        {
            $this->viewData->edit_url_icon="fa fa-edit";
        }
        //----

        if(!isset($this->viewData->view_url) && $this->viewData->enableView)
        {
            $this->viewData->view_url=$this->viewData->this_url."/view/";
        }
        if(!isset($this->viewData->view_url_label))
        {
            $this->viewData->view_url_label="view";
        }
        if(!isset($this->viewData->view_url_icon))
        {
            $this->viewData->view_url_icon="fa fa-list";
        }
        //----

        if(!isset($this->viewData->delete_url) && $this->viewData->enableDelete)
        {
            $this->viewData->delete_url=$this->viewData->this_url."/delete/";
        }
        if(!isset($this->viewData->delete_url_label))
        {
            $this->viewData->delete_url_label="Delete";
        }
        if(!isset($this->viewData->delete_url_icon))
        {
            $this->viewData->delete_url_icon="fa fa-trash";
        }
        //----

        if(!isset($this->viewData->restore_url) && $this->viewData->enableRestore)
        {
            $this->viewData->restore_url = str_replace(["/trash", "/trash/"], ["/restore/"], $this->viewData->this_url);
        }
        if(!isset($this->viewData->restore_url_label))
        {
            $this->viewData->restore_url_label="Restore";
        }
        if(!isset($this->viewData->restore_url_icon))
        {
            $this->viewData->restore_url_icon="fas fa-trash-restore";
        }
        //----
    }

    /**
     * Show the ui for displaying record modified details
     * @return Factory|View
     */
    public function display_created_at_as()
    {
        return view("default.common.created_modified_ui");
    }

    /**
     * @param $states
     * @return Factory|View
     */
    public function display_status_as($states=array())
    {
        if(!is_array($states) || count($states)==0)
        {
            //state value, state name (Option), css class for label
            $states = array();
            $states[]=array("id"=>"0", "name"=>"Disabled", "label"=>"danger");
            $states[]=array("id"=>"1", "name"=>"Enabled", "label"=>"success");
        }

        return view("default.common.status_ui", compact('states'));
    }
}
