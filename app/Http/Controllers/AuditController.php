<?php

namespace App\Http\Controllers;

use App\Models\Region;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
USE Illuminate\Support\Str;

class AuditController extends Controller
{
    public function index($language, Region $region)
    {
        Log::info('showing audit trail list.');
        return view('audit/audit_list', ['region' => $region]);
    }

    public function show($language, Audit $audit)
    {
        Log::info('showing audit trail item.');
        return view('audit/audit_show', ['audit' => $audit]);
    }

    public function datatable($language, Region $region)
    {
        $audits = Audit::where('tags', 'like', '%(' . $region->code . ')%')->with('user')->orderBy('created_at')->get();
        Log::info('preparing audit trail list');
        $audittrail = datatables()::of($audits);

        return $audittrail
                ->addIndexColumn()
                ->rawColumns(['id'])
                ->editColumn('id', function ($a) use ($language){
                    return '<a href="'.route('audit.show',['language'=>$language, 'audit'=>$a->id]).'" >'.$a->id.'</a>';
                })
                ->editColumn('created_at', function ($a) use ($language) {
                    return Carbon::parse($a->created_at)->locale($language)->isoFormat('l LTS');
                })
                ->editColumn('event', function ($a) {
                    return __($a->event);
                })
                ->editColumn('auditable_type', function ($a) {
                    return __('audit.'.$a->auditable_type);
                })
                ->addColumn('mod_values', function ($a) {
                    return Str::limit( $a->getModified(true) , 80);
                })
                ->make(true);

    }
}
