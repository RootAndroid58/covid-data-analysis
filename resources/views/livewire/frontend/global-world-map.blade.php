<div class="stats">
    <h3 class="text-center">
        Worldometer's World's Stats:


    </h3>
    <h5 class="text-center" style="margin-bottom:10px;">
        <select id="date" wire:model=date>
            <option value="today">Today</option>
            <option value="yesterday">Yesterday</option>
            <option value="yesterday2">2 days ago</option>
        </select>
        BY
        <select id="type" wire:model=type>
            <option value="active">Active</option>
            <option value="cases">Cases</option>
            <option value="deaths">Deaths</option>
            <option value="recovered">Recovered</option>
        </select>
    </h5>
    <div id="WorldMap_worldometer" style="width: 80%; min-width: 500px ; display:block; margin: 0 auto;" wire:init=worldMap></div>
</div>
<script>
    Livewire.on('WorldMap' , data => {
        drawworldometerMap(worldometerMap_data,data.date,'WorldMap_worldometer',data.type);
    })


</script>
