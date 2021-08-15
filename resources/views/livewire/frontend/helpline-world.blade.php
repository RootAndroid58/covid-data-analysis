<div wire:init=country class="container">
    <div class="form-group">
        <label for=""></label>
        <select class="form-control" wire:model="form.country" id="country">
            <option value="Select country">Select country</option>
            @foreach ($country as $item)
            <option value="{{ $item->code }}">{{ $item->name }}</option>
            @endforeach
      </select>
    </div>
    <div class="form-group">
      <label for="search">Search by city</label>
      <input type="text" name="search" id="search" class="form-control" placeholder="Search by city" wire:model="form.search" aria-describedby="helpId">
      <small id="helpId" class="text-muted">ex: Nagpur, Mumbai</small>
      @if ($search)

      <ul class="list-group">
          @foreach ($search as $item)

          @endforeach
          <li class="list-group-item">
            {{ $item->name }}
          </li>

      </ul>
      @endif
    </div>
    <div class="input-group mb-3">
      <label for=""></label>
    <div wire:loading wire:target="form.country" class="input-group-prepend">
      <span class="input-group-text h-100"><i class="fa fa-spinner fa-spin"></i></span>
    </div>
      <select class="form-control" wire:model.lazy="form.state" id="state" wire:target="form.country" wire:loading.attr="disabled">
          <option value="Select state">Select state</option>
          @foreach ($state as $item)
          <option value="{{ $item->state_code }}">{{ $item->name }}</option>
          @endforeach
      </select>
    </div>
    <div class="input-group mb-3">
      <label for=""></label>
      <div wire:loading wire:target="form.state" class="input-group-prepend">
        <span class="input-group-text h-100"><i class="fa fa-spinner fa-spin"></i></span>
      </div>
      <select class="form-control" wire:model="form.city" id="city" wire:target="form.state" wire:loading.attr="disabled">
          <option value="Select city">Select city</option>
          @foreach ($city as $item)
          <option value="{{ $item->state_code }}">{{ $item->name }}</option>
          @endforeach
      </select>
    </div>

</div>
