<div class="col-md-8">
    <div class="card card-outline card-dark" >
        <x-card-header title="{{ __('club.homegames.bydate') }}" icon="far fa-clock fa-lg"  :count="$team_total_cnt">
        </x-card-header>
        <div class="card-body py-2 px-2">
            <div style="width: 100%" style="height: 25%">
                <canvas id="myChart" height="120" width="200"></canvas>
            </div>
        </div>
</div>
