<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Modules\Admin\Entities\AdminSystemPermission;
use Modules\Admin\Repositories\AdminSystemPermissionRepository;

class AdminSystemPermissionController extends Controller
{
    private $repository = null;
    private $trash = false;

    public function __construct()
    {
        $this->repository = new AdminSystemPermissionRepository();
    }

    /**
     * Display a listing of the resource.
     * @param int $admin_perm_group_id
     * @return Factory|View
     */
    public function index($admin_perm_group_id)
    {
        $this->repository->setPageTitle("Admin System Permissions");

        $this->repository->initDatatable(new AdminSystemPermission());
        $this->repository->viewData->tableTitle = "Admin System Permissions";

        $this->repository->viewData->enableExport = true;

        $this->repository->setColumns("id", "permission_title", "permission_action", "permission_status", "disabled_reason", "created_at")
            ->setColumnLabel("permission_status", "Status")
            ->setColumnDisplay("permission_status", array($this->repository, 'display_status_as'))
            ->setColumnDisplay("created_at", array($this->repository, 'display_created_at_as'))

            ->setColumnFilterMethod("permission_status", "select", [["id" =>"1", "name" =>"Enabled"], ["id" =>"0", "name" =>"Disabled"]])

            ->setColumnSearchability("created_at", false)
            ->setColumnSearchability("updated_at", false);

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

        $query->where("admin_perm_group_id", "=", $admin_perm_group_id);

        return $this->repository->render("academic::layouts.master")->index($query);
    }

    /**
     * Display a listing of the resource.
     * @param $admin_perm_group_id
     * @return Factory|View
     */
    public function trash($admin_perm_group_id)
    {
        $this->trash = true;
        return $this->index($admin_perm_group_id);
    }

    /**
     * Show the form for creating a new resource.
     * @return Factory|View
     */
    public function create()
    {
        $model = new AdminSystemPermission();
        $record = $model;

        $formMode = "add";
        $formSubmitUrl = "/".request()->path();

        return view('academic::admin_system_permission.create', compact('formMode', 'formSubmitUrl', 'record'));
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store()
    {
        $model = new AdminSystemPermission();

        $model = $this->repository->getValidatedData($model, [
            "admin_perm_group_id" => "required|exists:admin_permission_groups,admin_perm_group_id",
            "permission_title" => "required|min:3",
            "permission_action" => "required|min:3",
            "permission_status" => "required|digits:1",
        ], [], ["admin_perm_group_id" => "Group name", "permission_title" => "Permission title", "permission_action" => "Permission action"]);

        $model->permission_key = $this->repository->generatePermissionHash($model->permission_action);

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
        $model = AdminSystemPermission::find($id);

        if($model)
        {
            $record = $model;

            return view('academic::admin_system_permission.view', compact('data', 'record'));
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
        $model = AdminSystemPermission::with(["permissionGroup"])->find($id);

        if($model)
        {
            $record = $model;
            $formMode = "edit";
            $formSubmitUrl = "/".request()->path();

            return view('academic::admin_system_permission.create', compact('formMode', 'formSubmitUrl', 'record'));
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
        $model = AdminSystemPermission::find($id);

        if($model)
        {
            $model = $this->repository->getValidatedData($model, [
                "admin_perm_group_id" => "required|exists:admin_permission_groups,admin_perm_group_id",
                "permission_title" => "required|min:3",
                "permission_action" => "required|min:3",
                "permission_status" => "required|digits:1",
            ], [], ["admin_perm_group_id" => "Group name", "permission_title" => "Permission title", "permission_action" => "Permission action"]);

            $model->permission_key = $this->repository->generatePermissionHash($model->permission_action);

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
        $model = AdminSystemPermission::find($id);

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
        $model = AdminSystemPermission::withTrashed()->find($id);

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

            $query = AdminSystemPermission::query()
                ->select("group_id", "permission_title")
                ->where("permission_status", "=", "1")
                ->orderBy("permission_title")
                ->limit(10);

            if($searchText != "")
            {
                $query = $query->where("permission_title", "LIKE", $searchText."%");
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
