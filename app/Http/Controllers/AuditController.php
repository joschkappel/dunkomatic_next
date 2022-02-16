<?php

namespace App\Http\Controllers;

use App\Models\Region;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuditController extends Controller
{

    /**
     * view for audit imte list
     *
     * @param string $language
     * @param \App\Models\Region $region
     * @return \Illuminate\View\View
     *
     */
    public function index($language, Region $region): View
    {
        Log::info('showing audit trail list.');
        return view('audit/audit_list', ['region' => $region]);
    }

    /**
     * show and audit item
     *
     * @param string $language
     * @param \OwenIt\Auditing\Models\Audit $audit
     * @return \Illuminate\View\View
     *
     */
    public function show(string $language, Audit $audit): View
    {
        Log::info('showing audit trail item.');
        return view('audit/audit_show', ['audit' => $audit]);
    }

    /**
     * datatables.net with audit imtes
     *
     * @param string $language
     * @param \App\Models\Region $region
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function datatable(string $language, Region $region): JsonResponse
    {
        $audits = Audit::where('tags', 'like', '%(' . $region->code . ')%')->with('user')->orderBy('created_at')->get();
        Log::info('preparing audit trail list');
        $audittrail = datatables()::of($audits);

        return $audittrail
            ->addIndexColumn()
            ->rawColumns(['id'])
            ->editColumn('id', function ($a) use ($language) {
                return '<a href="' . route('audit.show', ['language' => $language, 'audit' => $a->id]) . '" >' . $a->id . '</a>';
            })
            ->editColumn('created_at', function ($a) use ($language) {
                return Carbon::parse($a->created_at)->locale($language)->isoFormat('l LTS');
            })
            ->editColumn('event', function ($a) {
                // TBD sorting
                return __($a->event);
            })
            ->editColumn('auditable_type', function ($a) {
                // TBD sorting  ( + check sorting for username)
                return __('audit.' . $a->auditable_type);
            })
            ->addColumn('mod_values', function ($a) {
                return Str::limit(json_encode($a->old_values) . ' -> ' . json_encode($a->new_values), 80);
            })
            ->make(true);
    }
}
