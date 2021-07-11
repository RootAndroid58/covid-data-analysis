<div class="stats">
    <h3 class="text-center">
        Worldometer's World's Stats:
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

    </h3>
    <div id="WorldMap_worldometer" style="width: 80%; display:block; margin: 0 auto;" wire:init=worldMap></div>
</div>
<script>
    Livewire.on('WorldMap' , data => {
        drawworldometerMap(window.worldometerMap_data,data.date,'WorldMap_worldometer',data.type);
        console.log(worldometerMap_data);
    })
    Livewire.on('WorldMapVerSet' , data => {
        window.worldometerMap_data = data;
    })


</script>
