<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Modules\Admin\Repositories\AdminPermissionModuleRepository;
use Modules\Admin\Entities\Admin;

class AdminController extends Controller
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
        $this->repository->setPageTitle("Admins");

        $this->repository->initDatatable(new Admin());
        $this->repository->viewData->tableTitle = "Admins";

        $this->repository->viewData->enableExport = true;

        $this->repository->setColumns("id", "name", "admin_role", "status", "created_at", "updated_at")
            ->setColumnLabel("name", "Admin")
            ->setColumnLabel("status", "Status")
            ->setColumnDisplay("status", array($this->repository, 'display_status_as'))
            ->setColumnDisplay("admin_role", array($this->repository, 'display_admin_role_as'))
            ->setColumnDisplay("created_at", array($this->repository, 'display_created_at_as'))

            ->setColumnVisibility("updated_at", false)

            ->setColumnFilterMethod("name", "text")
            ->setColumnFilterMethod("status", "select", [["id" =>"1", "name" =>"Enabled"], ["id" =>"0", "name" =>"Disabled"]])
            ->setColumnFilterMethod("admin_role", "select", URL::to("/academic/adminRole/searchData"))

            ->setColumnSearchability("created_at", false)
            ->setColumnSearchability("updated_at", false)

            ->setColumnDBField("admin_role", "admin_role_id")
            ->setColumnFKeyField("admin_role", "admin_role_id")
            ->setColumnRelation("admin_role", "adminRole", "role_name");

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

        $query = $query->with(["adminRole"]);

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
        $formMode = "add";
        $formSubmitUrl = "/".request()->path();
        $adminRole = null;

        return view('academic::admin.create', compact('formMode', 'formSubmitUrl', 'adminRole'));
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store()
    {
        $model = new Admin();

        $model = $this->repository->getValidatedData($model, [
            "admin_role_id" => "required|exists:admin_roles,admin_role_id",
            "name" => "required|min:3",
            "email" => "unique:Modules\Admin\Entities\Admin,email",
            "status" => "required|digits:1",
        ], [], ["admin_role_id" => "Faculty", "name" => "Admin name"]);

        //set status as 0 when inserting the record
        $model->status = 0;

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
        $model = Admin::find($id);

        if($model)
        {
            $record = $model;

            return view('academic::admin.view', compact('data', 'record'));
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
        $model = Admin::with(["adminRole"])->find($id);

        if($model)
        {
            $record = $model;
            $adminRole = $model->adminRole;
            $formMode = "edit";
            $formSubmitUrl = "/".request()->path();

            return view('academic::admin.create', compact('formMode', 'formSubmitUrl', 'record', 'adminRole'));
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
        $model = Admin::find($id);

        if($model)
        {
            $model = $this->repository->getValidatedData($model, [
                "admin_role_id" => "required|exists:admin_roles,admin_role_id",
                "name" => "required|min:3",
                "email" => [Rule::requiredIf(function () use ($model) { return $model->default_admin == "0";}), Rule::unique('admins', "email")->ignore($model->admin_id, $model->getKeyName())],
                "status" => "required|digits:1",
            ], [], ["admin_role_id" => "Admin role", "name" => "Admin name"]);

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
        $model = Admin::find($id);

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
        $model = Admin::withTrashed()->find($id);

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

            $query = Admin::query()
                ->select("admin_id", "name")
                ->where("status", "=", "1")
                ->orderBy("name")
                ->limit(10);

            if($searchText != "")
            {
                $query = $query->where("name", "LIKE", $searchText."%");
            }

            if($idNot != "")
            {
                $query = $query->whereNotIn("admin_id", [$idNot]);
            }

            $data = $query->get();

            return response()->json($data, 201);
        }

        abort("403", "You are not allowed to access this data");
    }
}
