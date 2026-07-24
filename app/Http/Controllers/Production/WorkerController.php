<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Employee::with('department')->where('status', 'active');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('full_name', 'like', "%{$search}%");
        }

        $workers = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('production.workers.index', compact('workers'));
    }
}
