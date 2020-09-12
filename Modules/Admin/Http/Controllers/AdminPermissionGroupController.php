<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Modules\Admin\Entities\AdminPermissionGroup;
use Modules\Admin\Repositories\AdminPermissionGroupRepository;

class AdminPermissionGroupController extends Controller
{
    private $repository = null;
    private $trash = false;

    public function __construct()
    {
        $this->repository = new AdminPermissionGroupRepository();
    }

    /**
     * Display a listing of the resource.
     * @return Factory|View
     */
    public function index()
    {
        $this->repository->setPageTitle("Admin Permission Groups");

        $this->repository->initDatatable(new AdminPermissionGroup());
        $this->repository->viewData->tableTitle = "Admin Permission Groups";

        $this->repository->viewData->enableExport = true;

        $this->repository->setColumns("id", "group_name", "permission_module", "group_status", "created_at")
            ->setColumnLabel("group_name", "Module Name")
            ->setColumnLabel("group_status", "Status")
            ->setColumnDisplay("group_status", array($this->repository, 'display_status_as'))
            ->setColumnDisplay("permission_module", array($this->repository, 'display_permission_module_as'))
            ->setColumnDisplay("created_at", array($this->repository, 'display_created_at_as'))

            ->setColumnFilterMethod("group_name", "text")
            ->setColumnFilterMethod("group_status", "select", [["id" =>"1", "name" =>"Enabled"], ["id" =>"0", "name" =>"Disabled"]])
            ->setColumnFilterMethod("permission_module", "select", URL::to("/academic/adminPermissionModule/searchData"))

            ->setColumnSearchability("created_at", false)
            ->setColumnSearchability("updated_at", false)

            ->setColumnDBField("permission_module", "admin_perm_module_id")
            ->setColumnFKeyField("permission_module", "admin_perm_module_id")
            ->setColumnRelation("permission_module", "permissionModule", "system_name");

        if($this->trash)
        {
            $query = $this->repository->model::onlyTrashed();

            $this->repository->viewData->enableRestore = true;
            $this->repository->viewData->enableView= false;
            $this->repository->viewData->enableEdit = false;
            $this->repository->viewData->enableDelete = false;
        }
        else
        {
            $query = $this->repository->model;
        }

        $query = $query->with(["permissionModule"]);

        return $this->repository->render("academic::layouts.master")->index($query);
    }

    /**
     * Display a listing of the resource.
     * @return Factory|View
     */
    public function trash()
    {
        $this->trash = true;
        return $this->index();
    }

    /**
     * Show the form for creating a new resource.
     * @return Factory|View
     */
    public function create()
    {
        $model = new AdminPermissionGroup();
        $record = $model;

        $formMode = "add";
        $formSubmitUrl = "/".request()->path();

        return view('academic::admin_permission_group.create', compact('formMode', 'formSubmitUrl', 'record'));
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store()
    {
        $model = new AdminPermissionGroup();

        $model = $this->repository->getValidatedData($model, [
            "admin_perm_module_id" => "required|exists:admin_permission_modules,admin_perm_module_id",
            "group_name" => "required|min:3",
            "group_status" => "required|digits:1",
            "remarks" => "",
        ], [], ["admin_perm_module_id" => "Module name", "group_name" => "Group name"]);

        $dataResponse = $this->repository->saveModel($model);

        return $this->repository->handleResponse($dataResponse);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Factory|View
     */
    public function show($id)
    {
        $model = AdminPermissionGroup::find($id);

        if($model)
        {
            $record = $model;

            return view('academic::admin_permission_group.view', compact('data', 'record'));
        }
        else
        {
            abort(404, "Requested record does not exist.");
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Factory|View
     */
    public function edit($id)
    {
        $model = AdminPermissionGroup::with(["permissionModule"])->find($id);

        if($model)
        {
            $record = $model;
            $formMode = "edit";
            $formSubmitUrl = "/".request()->path();

            return view('academic::admin_permission_group.create', compact('formMode', 'formSubmitUrl', 'record'));
        }
        else
        {
            abort(404, "Requested record does not exist.");
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update($id)
    {
        $model = AdminPermissionGroup::find($id);

        if($model)
        {
            $model = $this->repository->getValidatedData($model, [
                "admin_perm_module_id" => "required|exists:admin_permission_modules,admin_perm_module_id",
                "group_name" => "required|min:3",
                "group_status" => "required|digits:1",
                "remarks" => "",
            ], [], ["admin_perm_module_id" => "Module name", "group_name" => "Group name"]);

            $dataResponse = $this->repository->saveModel($model);
        }
        else
        {
            $notify = array();
            $notify["status"]="failed";
            $notify["notify"][]="Details saving was failed. Requested record does not exist.";

            $dataResponse["notify"]=$notify;
        }

        return $this->repository->handleResponse($dataResponse);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse|RedirectResponse
     */
    public function destroy($id)
    {
        $model = AdminPermissionGroup::find($id);

        if($model)
        {
            if($model->delete())
            {
                $notify = array();
                $notify["status"]="success";
                $notify["notify"][]="Successfully moved the record to trash.";

                $dataResponse["notify"]=$notify;
            }
            else
            {
                $notify = array();
                $notify["status"]="failed";
                $notify["notify"][]="Details moving to trash was failed. Unknown error occurred.";

                $dataResponse["notify"]=$notify;
            }
        }
        else
        {
            $notify = array();
            $notify["status"]="failed";
            $notify["notify"][]="Details moving to trash was failed. Requested record does not exist.";

            $dataResponse["notify"]=$notify;
        }

        return $this->repository->handleResponse($dataResponse);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse|RedirectResponse
     */
    public function restore($id)
    {
        $model = AdminPermissionGroup::withTrashed()->find($id);

        if($model)
        {
            if($model->restore())
            {
                $notify = array();
                $notify["status"]="success";
                $notify["notify"][]="Successfully restored the record from trash.";

                $dataResponse["notify"]=$notify;
            }
            else
            {
                $notify = array();
                $notify["status"]="failed";
                $notify["notify"][]="Details restoring from trash was failed. Unknown error occurred.";

                $dataResponse["notify"]=$notify;
            }
        }
        else
        {
            $notify = array();
            $notify["status"]="failed";
            $notify["notify"][]="Details restoring from trash was failed. Requested record does not exist.";

            $dataResponse["notify"]=$notify;
        }

        return $this->repository->handleResponse($dataResponse);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function searchData(Request $request)
    {
        if($request->expectsJson())
        {
            $searchText = $request->post("searchText");
            $idNot = $request->post("idNot");

            $query = AdminPermissionGroup::query()
                ->select("group_id", "group_name")
                ->where("group_status", "=", "1")
                ->orderBy("group_name")
                ->limit(10);

            if($searchText != "")
            {
                $query = $query->where("group_name", "LIKE", $searchText."%");
            }

            if($idNot != "")
            {
                $query = $query->whereNotIn("group_id", [$idNot]);
            }

            $data = $query->get();

            return response()->json($data, 201);
        }

        abort("403", "You are not allowed to access this data");
    }
}
