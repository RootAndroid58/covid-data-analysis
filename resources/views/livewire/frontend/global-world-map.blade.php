<div class="stats">
    <h3 class="text-center">
        Worldometer's World's Stats:
        <select id="date" wire:model=date>
            <option value="today">Today</option>
            <option value="yesterday">Yesterday</option>
            <option value="yesterday2">2 days ago</option>
        </select>
    </h3>
    <div id="WorldMap_worldometer" style="width: 80%; display:block; margin: 0 auto;" wire:init=worldMap></div>
</div>
<script>
    Livewire.on('WorldMap' , data => {
        drawworldometerMap(data.data,data.date,'WorldMap_worldometer');
    })

</script>
