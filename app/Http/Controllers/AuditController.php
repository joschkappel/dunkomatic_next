<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuditController extends Controller
{
  public function index($language)
      {
          return view('admin/audit_list');
      }
}
