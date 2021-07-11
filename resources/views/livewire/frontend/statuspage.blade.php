<div wire:init=init>
    @if ($data)
    <div class="container">
        <div class="text-center bg-light shadow">
            <h3>Note</h3>
            <p>
                <i class="fas fa-circle" style="color: green;"></i> - Available &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>
                <i class="fas fa-circle" style="color: red;"></i> - Not Available
            </p>
            <p><b class="text-danger">disabled</b> means we are no longer supporting that data may vary in future versions!</p>

        </div>
        @can('application_Control')

        <div class="row flex-row">
            @foreach ($data as  $item)
            @php
                $disabled = ($item['disabled']) ? 'disabled' : '';
            @endphp
            <div class="col-md-5 shadow ml-3 mb-3 mt-1">
                <h3 class="text-center text-dark text-capitalize">{{ $item['title'] }} <small class="text-danger">{{ $disabled }}</small></h3>
                <table class="table table-responsive table-hover">
                    <thead>
                      <tr>
                        <th style="min-width: 160px">name</th>
                        <th >temp</th>
                        <th>prod</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($item['keys'] as $name => $data)
                            @php
                                $temp = ($data['temp']) ? 'green' : 'red';
                                $prod = ($data['prod']) ?  'green' : 'red';
                            @endphp
                            <tr>
                                <th>{{ $name }}</th>
                                <th><i class="fas fa-circle" style="color: {{ $temp }};"></i></th>
                                <th><i class="fas fa-circle" style="color: {{ $prod }};"></i></th>
                            </tr>
                        @endforeach
                    </tbody>

                  </table>
            </div>
            {{-- {{ dd($title,$item) }} --}}
            @endforeach
        </div>
        @else
        <table class="table table-responsive shadow table-hover">
            <thead>
                <tr>
                  <th>Name</th>
                  <th>Raw Data Available</th>
                  <th>Prod Data Available</th>
                  <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as  $item)

                @php
                    $temp = ($item['temp_available']) ? 'green' : 'red';
                    $prod = ($item['prod_available']) ?  'green' : 'red';
                    $disabled = ($item['disabled']) ? 'disabled' : '';
                @endphp
                <tr>
                    <th>{{ $item['title'] }}</th>
                    <th><i class="fas fa-circle" style="color: {{ $temp }};" title="@if($temp == 'green') available @else not avaiable @endif"></i></th>
                    <th><i class="fas fa-circle" style="color: {{ $prod }};" title="@if($prod == 'green') available @else not avaiable @endif"></i></th>
                    <th>{{ $disabled }}</th>
                </tr>
                @endforeach
            </tbody>

        </table>
        @endcan
    </div>
    @else
    <div class="container text-center">
        <button class="btn" wire:click=init><img src="{{ asset('images/Spinner-1s-450px.svg') }}" alt="Loading" title="click to refresh data" srcset=""></button>
    </div>
    @endif
    {{-- {{ dd($this->data) }} --}}
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
</div>

<script>
    Livewire.on('status' , data => {
        console.log(data);

    })
</script>
