<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\AdminRole;
use Modules\Admin\Repositories\AdminRoleRepository;

class AdminRoleController extends Controller
{
    private $repository = null;
    private $trash = false;

    public function __construct()
    {
        $this->repository = new AdminRoleRepository();
    }

    /**
     * Display a listing of the resource.
     * @return Factory|View
     */
    public function index()
    {
        $this->repository->setPageTitle("Admin Roles");

        $this->repository->initDatatable(new AdminRole());
        $this->repository->viewData->tableTitle = "Admin Roles";

        $this->repository->viewData->enableExport = true;

        $this->repository->setColumns("id", "role_name", "description", "role_status", "created_at")
            ->setColumnLabel("role_status", "Status")
            ->setColumnDisplay("role_status", array($this->repository, 'display_status_as'))
            ->setColumnDisplay("created_at", array($this->repository, 'display_created_at_as'))

            ->setColumnFilterMethod("role_name", "text")
            ->setColumnFilterMethod("role_status", "select", [["id" =>"1", "name" =>"Enabled"], ["id" =>"0", "name" =>"Disabled"]])

            ->setColumnSearchability("role_status", false)
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
        $formMode = "add";
        $formSubmitUrl = "/".request()->path();

        return view('admin::admin_role.create', compact('formMode', 'formSubmitUrl'));
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store()
    {
        $model = new AdminRole();

        $model = $this->repository->getValidatedData($model, [
            "role_name" => "required|min:3",
            "system_slug" => "required|min:3",
            "role_status" => "required|digits:1",
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
        $model = AdminRole::find($id);

        if($model)
        {
            $record = $model;

            return view('admin::admin_role.view', compact('data', 'record'));
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
        $model = AdminRole::find($id);

        //dd(get_class($model));

        if($model)
        {
            $record = $model;
            $formMode = "edit";
            $formSubmitUrl = "/".request()->path();

            return view('admin::admin_role.create', compact('formMode', 'formSubmitUrl', 'record'));
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
        $model = AdminRole::find($id);

        if($model)
        {
            $model = $this->repository->getValidatedData($model, [
                "role_name" => "required|min:3",
                "system_slug" => "required|min:3",
                "role_status" => "required|digits:1",
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
        $model = AdminRole::find($id);

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
        $model = AdminRole::withTrashed()->find($id);

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

            $query = AdminRole::query()
                ->select("admin_role_id", "role_name")
                ->where("role_status", "=", "1")
                ->orderBy("role_name")
                ->limit(10);

            if($searchText != "")
            {
                $query = $query->where("role_name", "LIKE", $searchText."%");
            }

            if($idNot != "")
            {
                $query = $query->whereNotIn("admin_role_id", [$idNot]);
            }

            $data = $query->get();

            return response()->json($data, 201);
        }

        abort("403", "You are not allowed to access this data");
    }
}
