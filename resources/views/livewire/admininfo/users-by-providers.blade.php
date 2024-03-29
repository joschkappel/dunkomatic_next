<div>
    <div class="container mx-auto space-y-4 p-4 sm:p-0 mt-8">
        <div class="flex row space-y-4 sm:space-y-0 sm:space-x-4">
            <div class="shadow rounded p-4 border bg-white" style="height: 20rem;width: 20%">
                <livewire:livewire-pie-chart
                key="{{ $pieChartModel->reactiveKey() }}"
                :pie-chart-model="$pieChartModel"
                />
            </div>
            <div class="shadow rounded p-4 border bg-white" style="height: 20rem;width: 80%">
                <livewire:livewire-column-chart
                key="{{ $multiColumnChartModel->reactiveKey() }}"
                :column-chart-model="$multiColumnChartModel"
                />
            </div>
        </div>
    </div>
</div>
