<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewReqRequest;
use App\Http\Requests\UpdateNewReqRequest;
use App\Http\Resources\Admin\NewReqResource;
use App\Models\NewReq;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NewReqApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('new_req_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NewReqResource(NewReq::all());
    }

    public function store(StoreNewReqRequest $request)
    {
        $newReq = NewReq::create($request->all());

        return (new NewReqResource($newReq))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(NewReq $newReq)
    {
        abort_if(Gate::denies('new_req_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new NewReqResource($newReq);
    }

    public function update(UpdateNewReqRequest $request, NewReq $newReq)
    {
        $newReq->update($request->all());

        return (new NewReqResource($newReq))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(NewReq $newReq)
    {
        abort_if(Gate::denies('new_req_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $newReq->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
