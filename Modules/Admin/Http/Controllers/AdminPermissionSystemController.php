<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\Admin\Entities\AdminPermissionSystem;
use Modules\Admin\Repositories\AdminPermissionSystemRepository;

class AdminPermissionSystemController extends Controller
{
    private $repository = null;
    private $trash = false;

    public function __construct()
    {
        $this->repository = new AdminPermissionSystemRepository();
    }

    /**
     * Display a listing of the resource.
     * @return Factory|View
     */
    public function index()
    {
        $this->repository->setPageTitle("Admin Permission Systems");

        $this->repository->initDatatable(new AdminPermissionSystem());
        $this->repository->viewData->tableTitle = "Admin Permission Systems";

        $this->repository->viewData->enableExport = true;

        $this->repository->setColumns("id", "system_name", "system_slug", "system_status", "created_at")
            ->setColumnLabel("system_slug", "System Short Code")
            ->setColumnLabel("system_status", "Status")
            ->setColumnDisplay("system_status", array($this->repository, 'display_status_as'))
            ->setColumnDisplay("created_at", array($this->repository, 'display_created_at_as'))

            ->setColumnFilterMethod("system_name", "text")
            ->setColumnFilterMethod("system_status", "select", [["id" =>"1", "name" =>"Enabled"], ["id" =>"0", "name" =>"Disabled"]])

            ->setColumnSearchability("system_status", false)
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

        $query = $query->with([]);

        return $this->repository->render("admin::layouts.master")->index($query);
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
        $model = new AdminPermissionSystem();
        $record = $model;

        $formMode = "add";
        $formSubmitUrl = "/".request()->path();

        return view('admin::admin_perm_system.create', compact('formMode', 'formSubmitUrl', 'record'));
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store()
    {
        $model = new AdminPermissionSystem();

        $model = $this->repository->getValidatedData($model, [
            "system_name" => "required|min:3",
            "system_slug" => "required|min:3",
            "system_status" => "required|digits:1",
            "remarks" => "",
        ]);

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
        $model = AdminPermissionSystem::find($id);

        if($model)
        {
            $record = $model;

            return view('admin::admin_perm_system.view', compact('data', 'record'));
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
        $model = AdminPermissionSystem::find($id);

        //dd(get_class($model));

        if($model)
        {
            $record = $model;
            $formMode = "edit";
            $formSubmitUrl = "/".request()->path();

            return view('admin::admin_perm_system.create', compact('formMode', 'formSubmitUrl', 'record'));
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
        $model = AdminPermissionSystem::find($id);

        if($model)
        {
            $model = $this->repository->getValidatedData($model, [
                "system_name" => "required|min:3",
                "system_slug" => "required|min:3",
                "system_status" => "required|digits:1",
                "remarks" => "",
            ]);

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
        $model = AdminPermissionSystem::find($id);

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
        $model = AdminPermissionSystem::withTrashed()->find($id);

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

            $query = AdminPermissionSystem::query()
                ->select("admin_perm_system_id", "system_name")
                ->where("system_status", "=", "1")
                ->orderBy("system_name")
                ->limit(10);

            if($searchText != "")
            {
                $query = $query->where("system_name", "LIKE", $searchText."%");
            }

            if($idNot != "")
            {
                $query = $query->whereNotIn("admin_perm_system_id", [$idNot]);
            }

            $data = $query->get();

            return response()->json($data, 201);
        }

        abort("403", "You are not allowed to access this data");
    }
}
