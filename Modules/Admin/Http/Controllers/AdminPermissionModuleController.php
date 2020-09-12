<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Modules\Admin\Entities\AdminPermissionModule;
use Modules\Admin\Repositories\AdminPermissionModuleRepository;

class AdminPermissionModuleController extends Controller
{
    private $repository = null;
    private $trash = false;

    public function __construct()
    {
        $this->repository = new AdminPermissionModuleRepository();
    }

    /**
     * Display a listing of the resource.
     * @return Factory|View
     */
    public function index()
    {
        $this->repository->setPageTitle("Admin Permission Modules");

        $this->repository->initDatatable(new AdminPermissionModule());
        $this->repository->viewData->tableTitle = "Admin Permission Modules";

        $this->repository->viewData->enableExport = true;

        $this->repository->setColumns("id", "module_name", "permission_system", "module_status", "created_at")
            ->setColumnLabel("module_name", "Module Name")
            ->setColumnLabel("module_status", "Status")
            ->setColumnDisplay("module_status", array($this->repository, 'display_status_as'))
            ->setColumnDisplay("permission_system", array($this->repository, 'display_permission_system_as'))
            ->setColumnDisplay("created_at", array($this->repository, 'display_created_at_as'))

            ->setColumnFilterMethod("module_name", "text")
            ->setColumnFilterMethod("module_status", "select", [["id" =>"1", "name" =>"Enabled"], ["id" =>"0", "name" =>"Disabled"]])
            ->setColumnFilterMethod("permission_system", "select", URL::to("/academic/adminPermissionSystem/searchData"))

            ->setColumnSearchability("created_at", false)
            ->setColumnSearchability("updated_at", false)

            ->setColumnDBField("permission_system", "admin_perm_system_id")
            ->setColumnFKeyField("permission_system", "admin_perm_system_id")
            ->setColumnRelation("permission_system", "permissionSystem", "system_name");

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

        $query = $query->with(["permissionSystem"]);

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
        $model = new AdminPermissionModule();
        $record = $model;

        $formMode = "add";
        $formSubmitUrl = "/".request()->path();

        return view('academic::admin_permission_module.create', compact('formMode', 'formSubmitUrl', 'record'));
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store()
    {
        $model = new AdminPermissionModule();

        $model = $this->repository->getValidatedData($model, [
            "admin_perm_system_id" => "required|exists:admin_permission_systems,admin_perm_system_id",
            "module_name" => "required|min:3",
            "module_status" => "required|digits:1",
            "remarks" => "",
        ], [], ["admin_perm_system_id" => "Permission System", "module_name" => "Module name"]);

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
        $model = AdminPermissionModule::find($id);

        if($model)
        {
            $record = $model;

            return view('academic::admin_permission_module.view', compact('data', 'record'));
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
        $model = AdminPermissionModule::with(["permissionSystem"])->find($id);

        if($model)
        {
            $record = $model;
            $formMode = "edit";
            $formSubmitUrl = "/".request()->path();

            return view('academic::admin_permission_module.create', compact('formMode', 'formSubmitUrl', 'record'));
        }
        else
        {
            abort(404, "Requested record does not exist.");
        }
    }

    /**
     * Update the specified resource in storage.
     * @param int $id
     * @return JsonResponse
     */
    public function update($id)
    {
        $model = AdminPermissionModule::find($id);

        if($model)
        {
            $model = $this->repository->getValidatedData($model, [
                "admin_perm_system_id" => "required|exists:admin_permission_systems,admin_perm_system_id",
                "module_name" => "required|min:3",
                "module_status" => "required|digits:1",
                "remarks" => "",
            ], [], ["admin_perm_system_id" => "Permission system", "module_name" => "Module name"]);

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
        $model = AdminPermissionModule::find($id);

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
        $model = AdminPermissionModule::withTrashed()->find($id);

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

            $query = AdminPermissionModule::query()
                ->select("module_id", "module_name")
                ->where("module_status", "=", "1")
                ->orderBy("module_name")
                ->limit(10);

            if($searchText != "")
            {
                $query = $query->where("module_name", "LIKE", $searchText."%");
            }

            if($idNot != "")
            {
                $query = $query->whereNotIn("module_id", [$idNot]);
            }

            $data = $query->get();

            return response()->json($data, 201);
        }

        abort("403", "You are not allowed to access this data");
    }
}
