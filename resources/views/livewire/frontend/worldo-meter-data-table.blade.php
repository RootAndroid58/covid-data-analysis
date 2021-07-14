<div wire:init=datatableInit>
    <div class="row ml-5">
        <div class="col">
            <h3 class="text-center mt-5">WorldoMeter Data</h3>
        </div>

    </div>
    <div class="row ml-3 mr-3">
        <div class="col mt-1 bg-white shadow">
            <div class="mt-2">
                <div class="text-center">
                    <button data-target="today" class="btn btn-primary show_data active">Today</button>
                    <button data-target="yesterday" class="btn btn-primary show_data">Yesterday</button>
                    <button data-target="yesterday2" class="btn btn-primary show_data">2 days ago</button>
                </div>
                <div id="today" class="data_hide table-responsive" style="display: block;"></div>
                <div id="yesterday" class="data_hide table-responsive"></div>
                <div id="yesterday2" class="data_hide table-responsive"></div>
            </div>
        </div>
    </div>
</div>
@push('style')
<style>
    .data_hide{
        display: none;
    }
</style>
@endpush
@push('scripts')
<script>
    $(window).on('load', function() {
        var $divs = $('.data_hide');
        $(".show_data").click(function() {
            $('.show_data').removeClass('active');
            $(this).addClass('active')
            var $target = $('#' + $(this).data('target')).show();
            $divs.not($target).hide();
        });
     });
    Livewire.on('dataTable' , (data,today,yesterday,yesterday2) => {
        for (const [key, value] of Object.entries(data)) {
            createTable(key,value);
        }
    })

    function createTable(id,data) {
        var tablearea = document.getElementById(id);

        var table = document.createElement('table');
        table.setAttribute('id',id+"_table");

        var thead = document.createElement('thead');

        var tbody = document.createElement('tbody');



        var tr = document.createElement('tr');


        let j = 0;

        for(var key in data[0]){
            tr.appendChild( document.createElement('td') )
            tr.cells[j].appendChild( document.createTextNode(key) );
            j++;
        }
        thead.appendChild(tr);
        table.appendChild(thead);

        var html = "";
        for (let i = 0; i < data.length; i++) {
            html += "<tr>"
            // td = document.createElement('tr');
            for(let key in data[i]){
                html += "<td>" +data[i][key] +  "</td>"
                // td.appendChild( document.createElement('td') );
                // td.cells[i].appendChild(document.createTextNode(data[i][key]));
            }
            html += "</tr>"
        }
        tbody.innerHTML = html;

        // console.log(data[0]);

        // $('#'.id).append(table);
        table.classList.add('table');
        table.classList.add('table-striped');
        table.classList.add('table-bordered');
        table.classList.add('table-hover');

        table.appendChild(tbody);

        tablearea.appendChild(table);
        $('#'+id).next('tbody').html(html);

        $('#'+id+"_table").DataTable({
            responsive:true,
            processing:true,
            paging: true,
            stateSave:false,
        });

    }
</script>
@endpush
