<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class TicketImportController extends BaseController
{
    public function create(Request $request, $invoiceId)
    {
        return view('admin.ticket-import.form', compact('invoiceId'));
    }
}
