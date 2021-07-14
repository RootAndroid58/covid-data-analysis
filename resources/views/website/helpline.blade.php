@extends('layouts.frontend')

@section('content')
<div class="site-section ">
    <div class="table-responsive ml-4 mr-2 pl-2 pr-2 pt-3 pb-2 bg-white shadow">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Resource">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.resource.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.resource.fields.category') }}
                    </th>
                    <th>
                        {{ trans('cruds.resource.fields.city') }}
                    </th>
                    <th>
                        {{ trans('cruds.resource.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.resource.fields.phone_no') }}
                    </th>
                    <th>
                        {{ trans('cruds.resource.fields.email') }}
                    </th>
                    <th>
                        {{ trans('cruds.resource.fields.details') }}
                    </th>
                    <th>
                        {{ trans('cruds.resource.fields.up_vote') }}
                    </th>
                    <th>
                        {{ trans('cruds.resource.fields.down_vote') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search" style="width: -webkit-fill-available;">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($categories as $key => $item)
                                <option value="{{ $item->category_name }}">{{ $item->category_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</div>


@endsection

@section('scripts')
<script>
    $(function () {
      let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
      let deleteButtonTrans = 'Delete selected';
      let deleteButton = {
        text: deleteButtonTrans,
        url: "{{ route('admin.resources.massDestroy') }}",
        className: 'btn-danger',
        action: function (e, dt, node, config) {
          var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
              return entry.id
          });

          if (ids.length === 0) {
            alert('No rows selected')

            return
          }

          if (confirm('Are you sure?')) {
            $.ajax({
              headers: {'x-csrf-token': _token},
              method: 'POST',
              url: config.url,
              data: { ids: ids, _method: 'DELETE' }})
              .done(function () { location.reload() })
          }
        }
      }
      dtButtons.push(deleteButton)

      let dtOverrideGlobals = {
        buttons: dtButtons,
        processing: true,
        serverSide: true,
        retrieve: true,
        aaSorting: [],
        ajax: "{{ route('helpline') }}",
        columns: [


    { data: 'placeholder', name: 'placeholder' },
    { data: 'id', name: 'id',"width": "7%" },
    { data: 'category_category_name', name: 'categories.category_name',"width": "13%" },
    { data: 'city_name', name: 'city.name',"width": "10%" },
    { data: 'name', name: 'name',"width": "10%" },
    { data: 'phone_no', name: 'phone_no',"width": "11%" },
    { data: 'email', name: 'email',"visible": false ,"width": "15%" },
    { data: 'details', name: 'details',"width": "17%" },
    { data: 'up_vote', name: 'up_vote',"width": "7%" },
    { data: 'down_vote', name: 'down_vote',"width": "9%" },
    { data: 'actions', name: '{{ trans('global.actions') }}' }



        ],
        orderCellsTop: true,
        order: [[ 1, 'desc' ]],
        pageLength: 100,
      };
      let table = $('.datatable-Resource').DataTable(dtOverrideGlobals);
      $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
          $($.fn.dataTable.tables(true)).DataTable()
              .columns.adjust();
      });

    let visibleColumnsIndexes = null;
    $('.datatable thead').on('input', '.search', function () {
          let strict = $(this).attr('strict') || false
          let value = strict && this.value ? "^" + this.value + "$" : this.value

          let index = $(this).parent().index()
          if (visibleColumnsIndexes !== null) {
            index = visibleColumnsIndexes[index]
          }

          table
            .column(index)
            .search(value, strict)
            .draw()
      });
    table.on('column-visibility.dt', function(e, settings, column, state) {
          visibleColumnsIndexes = []
          table.columns(":visible").every(function(colIdx) {
              visibleColumnsIndexes.push(colIdx);
          });
      })
    });
    </script>
@endsection
