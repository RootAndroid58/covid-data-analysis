@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.newReq.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.new-reqs.update", [$newReq->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required">{{ trans('cruds.newReq.fields.model') }}</label>
                <select class="form-control {{ $errors->has('model') ? 'is-invalid' : '' }}" name="model" id="model" required>
                    <option value disabled {{ old('model', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\NewReq::MODEL_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('model', $newReq->model) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('model'))
                    <span class="text-danger">{{ $errors->first('model') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.newReq.fields.model_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email_id">{{ trans('cruds.newReq.fields.email') }}</label>
                <select class="form-control select2 {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email_id" id="email_id" required>
                    @foreach($emails as $id => $entry)
                        <option value="{{ $id }}" {{ (old('email_id') ? old('email_id') : $newReq->email->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.newReq.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="data">{{ trans('cruds.newReq.fields.data') }}</label>
                <textarea class="form-control {{ $errors->has('data') ? 'is-invalid' : '' }}" name="data" id="data" required>{{ old('data', $newReq->data) }}</textarea>
                @if($errors->has('data'))
                    <span class="text-danger">{{ $errors->first('data') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.newReq.fields.data_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="message">{{ trans('cruds.newReq.fields.message') }}</label>
                <input class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}" type="text" name="message" id="message" value="{{ old('message', $newReq->message) }}">
                @if($errors->has('message'))
                    <span class="text-danger">{{ $errors->first('message') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.newReq.fields.message_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="status">{{ trans('cruds.newReq.fields.status') }}</label>
                <input class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" type="number" name="status" id="status" value="{{ old('status', $newReq->status) }}" step="1">
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.newReq.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="admin_message">{{ trans('cruds.newReq.fields.admin_message') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('admin_message') ? 'is-invalid' : '' }}" name="admin_message" id="admin_message">{!! old('admin_message', $newReq->admin_message) !!}</textarea>
                @if($errors->has('admin_message'))
                    <span class="text-danger">{{ $errors->first('admin_message') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.newReq.fields.admin_message_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.new-reqs.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $newReq->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

@endsection
