<?php

use App\Enums\Report;
use App\Models\Region;
use App\Models\ReportDownload;
use App\Models\ReportJob;
use App\Models\User;
use App\Traits\ReportJobStatus;

it('does not find outdated reports', function () {
    // Arrange
    // add 2 reports to the DB
    $rv = new class
    {
        use ReportJobStatus;
    };
    $user = User::first();
    $region = Region::first();
    $rpt_1 = Report::RegionGames();
    $rpt_2 = Report::LeagueBook();

    $rj_1 = $rv->job_starting($region, $rpt_1);
    $rv->job_finished($region, $rpt_1);
    $rj_2 = $rv->job_starting($region, $rpt_2);
    $rv->job_finished($region, $rpt_2);

    $rd_1 = ReportDownload::updateOrCreate(
        ['user_id' => $user->id, 'report_id' => $rpt_1,
            'model_class' => Region::class, 'model_id' => $region->id, ],
        ['updated_at' => now(), 'version' => $rj_1->version]
    );

    $rd_2 = ReportDownload::updateOrCreate(
        ['user_id' => $user->id, 'report_id' => $rpt_2,
            'model_class' => Region::class, 'model_id' => $region->id, ],
        ['updated_at' => now(), 'version' => $rj_2->version]
    );

    // Act
    $result = $rv->get_outdated_downloads($user);

    // Assert
    $this->assertDatabaseHas('report_jobs', ['id' => $rj_1->id]);
    $this->assertDatabaseHas('report_jobs', ['id' => $rj_2->id]);
    $this->assertDatabaseHas('report_downloads', ['id' => $rd_1->id]);
    $this->assertDatabaseHas('report_downloads', ['id' => $rd_2->id]);
    $this->assertCount(0, $result);

    // remove
    ReportDownload::whereNotNull('id')->delete();
    ReportJob::whereNotNull('id')->delete();
});
it('finds one outdated reports', function () {
    // Arrange
    // add 2 reports to the DB
    $rv = new class
    {
        use ReportJobStatus;
    };
    $user = User::first();
    $region = Region::first();
    $rpt_1 = Report::RegionGames();
    $rpt_2 = Report::LeagueBook();

    $rj_1 = $rv->job_starting($region, $rpt_1);
    $rv->job_finished($region, $rpt_1);

    $this->travel(5)->days();
    $rj_2 = $rv->job_starting($region, $rpt_2);
    $rv->job_finished($region, $rpt_2);

    $rd_1 = ReportDownload::updateOrCreate(
        ['user_id' => $user->id, 'report_id' => $rpt_1,
            'model_class' => Region::class, 'model_id' => $region->id, ],
        ['updated_at' => now(), 'version' => $rj_1->version]
    );

    // use outdated version for 2nd job.
    $rd_2 = ReportDownload::updateOrCreate(
        ['user_id' => $user->id, 'report_id' => $rpt_2,
            'model_class' => Region::class, 'model_id' => $region->id, ],
        ['updated_at' => now(), 'version' => $rj_1->version]
    );

    // Act
    $result = $rv->get_outdated_downloads($user);

    // Assert
    $this->assertDatabaseHas('report_jobs', ['id' => $rj_1->id]);
    $this->assertDatabaseHas('report_jobs', ['id' => $rj_2->id]);
    $this->assertDatabaseHas('report_downloads', ['id' => $rd_1->id]);
    $this->assertDatabaseHas('report_downloads', ['id' => $rd_2->id]);
    $this->assertCount(1, $result);

    // remove
    ReportDownload::whereNotNull('id')->delete();
    ReportJob::whereNotNull('id')->delete();
});
