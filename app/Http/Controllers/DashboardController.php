<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;
use DB;
use Exception;

class DashboardController extends Controller
{
    public function index()
    {
        $start_date = Carbon::now()->startOfMonth();
        $end_date = Carbon::now()->endOfMonth();

        return view('dashboard.index', compact('start_date', 'end_date'));
    }
}