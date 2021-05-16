<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyNewReqRequest;
use App\Http\Requests\StoreNewReqRequest;
use App\Http\Requests\UpdateNewReqRequest;
use App\Models\NewReq;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class NewReqController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('new_req_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = NewReq::with(['email'])->select(sprintf('%s.*', (new NewReq())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'new_req_show';
                $editGate = 'new_req_edit';
                $deleteGate = 'new_req_delete';
                $crudRoutePart = 'new-reqs';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('model', function ($row) {
                return $row->model ? NewReq::MODEL_SELECT[$row->model] : '';
            });
            $table->addColumn('email_email', function ($row) {
                return $row->email ? $row->email->email : '';
            });

            $table->editColumn('email.email', function ($row) {
                return $row->email ? (is_string($row->email) ? $row->email : $row->email->email) : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? $row->status : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'email']);

            return $table->make(true);
        }

        $users = User::get();

        return view('admin.newReqs.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('new_req_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emails = User::all()->pluck('email', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.newReqs.create', compact('emails'));
    }

    public function store(StoreNewReqRequest $request)
    {
        $newReq = NewReq::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $newReq->id]);
        }

        return redirect()->route('admin.new-reqs.index');
    }

    public function edit(NewReq $newReq)
    {
        abort_if(Gate::denies('new_req_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emails = User::all()->pluck('email', 'id')->prepend(trans('global.pleaseSelect'), '');

        $newReq->load('email');

        return view('admin.newReqs.edit', compact('emails', 'newReq'));
    }

    public function update(UpdateNewReqRequest $request, NewReq $newReq)
    {
        $newReq->update($request->all());

        return redirect()->route('admin.new-reqs.index');
    }

    public function show(NewReq $newReq)
    {
        abort_if(Gate::denies('new_req_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $newReq->load('email');

        return view('admin.newReqs.show', compact('newReq'));
    }

    public function destroy(NewReq $newReq)
    {
        abort_if(Gate::denies('new_req_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $newReq->delete();

        return back();
    }

    public function massDestroy(MassDestroyNewReqRequest $request)
    {
        NewReq::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('new_req_create') && Gate::denies('new_req_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new NewReq();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
