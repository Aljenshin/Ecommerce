<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PayrollItem;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        $payrollItems = PayrollItem::where('user_id', auth()->id())
            ->with('payrollRun')
            ->latest()
            ->paginate(15);

        return view('staff.payroll.index', compact('payrollItems'));
    }

    public function show(PayrollItem $payrollItem)
    {
        if ($payrollItem->user_id !== auth()->id()) {
            abort(403);
        }

        $payrollItem->load(['payrollRun', 'user']);

        return view('staff.payroll.show', compact('payrollItem'));
    }
}
