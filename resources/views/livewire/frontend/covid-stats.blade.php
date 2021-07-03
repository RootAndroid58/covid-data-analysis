<div class="row" wire:init=loaded>
    <div class="col-lg-4">
        <div class="data">
            <span class="icon text-primary">
                <span class="flaticon-virus"></span>
            </span>
            <strong class="d-block number counter">{{ $active_cases }}</strong>
            <span class="label">Active Cases</span>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="data">
            <span class="icon text-primary">
                <span class="flaticon-virus"></span>
            </span>
            <strong class="d-block number counter">{{ $deaths }}</strong>
            <span class="label">Deaths</span>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="data">
            <span class="icon text-primary">
                <span class="flaticon-virus"></span>
            </span>
            <strong class="d-block number counter">{{ $recovered }}</strong>
            <span class="label">Recovered Cases</span>
        </div>
    </div>
</div>
