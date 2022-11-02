<div>
    <div class="container mx-auto space-y-4 p-4 sm:p-0 mt-8">
        <div class="flex row space-y-4 sm:space-y-0 sm:space-x-4">
            <div class="shadow rounded p-4 border bg-white" style="height: 20rem;width: 25%">
                <livewire:livewire-pie-chart
                key="{{ $pieChartModel->reactiveKey() }}"
                :pie-chart-model="$pieChartModel"
                />
            </div>
            <div class="shadow rounded p-4 border bg-white" style="height: 20rem;width: 75%">
                <livewire:livewire-line-chart
                key="{{ $multiLineChartModel->reactiveKey() }}"
                :line-chart-model="$multiLineChartModel"
                />
            </div>
        </div>
    </div>
</div>
