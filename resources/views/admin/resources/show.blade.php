@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.resource.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.resources.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.id') }}
                        </th>
                        <td>
                            {{ $resource->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.category') }}
                        </th>
                        <td>
                            @foreach($resource->categories as $key => $category)
                                <span class="badge badge-info">{{ $category->category_name  }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.country') }}
                        </th>
                        <td>
                            {{ $resource->country->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.state') }}
                        </th>
                        <td>
                            {{ $resource->state->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.city') }}
                        </th>
                        <td>
                            {{ $resource->city->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.name') }}
                        </th>
                        <td>
                            {{ $resource->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.phone_no') }}
                        </th>
                        <td>
                            {{ $resource->phone_no }}
                        </td>
                    </tr>
                    @if ($resource->email)

                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.email') }}
                        </th>
                        <td>
                            {{ $resource->email }}
                        </td>
                    </tr>
                    @endif
                    @if ($resource->address)

                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.address') }}
                        </th>
                        <td>
                            {{ $resource->address }}
                        </td>
                    </tr>
                    @endif
                    @if ($resource->details)

                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.details') }}
                        </th>
                        <td>
                            {{ $resource->details }}
                        </td>
                    </tr>
                    @endif
                    @if ($resource->note)

                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.note') }}
                        </th>
                        <td>
                            {{ $resource->note }}
                        </td>
                    </tr>
                    @endif
                    @if ($resource->url)

                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.url') }}
                        </th>
                        <td>
                            {{ $resource->url }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.up_vote') }}
                        </th>
                        <td>
                            {{ $resource->up_vote == '' ? 0 : $resource->up_vote  }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.down_vote') }}
                        </th>
                        <td>
                            {{ $resource->down_vote == '' ? 0 : $resource->down_vote }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.resources.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
