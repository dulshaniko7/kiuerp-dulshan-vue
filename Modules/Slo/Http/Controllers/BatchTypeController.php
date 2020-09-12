<?php

namespace Modules\Slo\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Slo\Entities\BatchType;
use Modules\Slo\Transformers\BatchTypeResource;
use Symfony\Component\HttpFoundation\Response;

class BatchTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // return view('slo::index');
        //return BatchTypeResource::collection(BatchType::withoutTrashed()->get());
        //return BatchTypeResource::collection(BatchType::all()->get());
        $types = BatchTypeResource::collection(BatchType::all());
        return response()->json(['types' => $types]);

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        //return view('slo::create');

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        BatchType::create($request->all());
        return response('Created', Response::HTTP_CREATED);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('slo::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('slo::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
