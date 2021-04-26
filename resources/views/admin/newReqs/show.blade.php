@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.newReq.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.new-reqs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.newReq.fields.id') }}
                        </th>
                        <td>
                            {{ $newReq->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newReq.fields.catogary') }}
                        </th>
                        <td>
                            {{ $newReq->catogary }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newReq.fields.country') }}
                        </th>
                        <td>
                            {{ $newReq->country }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newReq.fields.state') }}
                        </th>
                        <td>
                            {{ $newReq->state }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newReq.fields.city') }}
                        </th>
                        <td>
                            {{ $newReq->city }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newReq.fields.extra') }}
                        </th>
                        <td>
                            {{ $newReq->extra }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.newReq.fields.status') }}
                        </th>
                        <td>
                            {{ $newReq->status }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.new-reqs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
