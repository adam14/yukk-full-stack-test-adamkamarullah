<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Transaction;

use Carbon\Carbon;
use DataTables;
use DB;
use Exception;

class TransactionsController extends Controller
{
    public function index()
    {
        return view('transactions.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            if ($request->disable_date == 'true') {
                $start_date = Carbon::createFromFormat('Y-m-d', '2023-09-01');
                $end_date = Carbon::now()->endOfMOnth();
            } else {
                $start_date = $request->start_date != null ? Carbon::createFromFormat('Y-m-d', $request->start_date) : Carbon::now()->startOfMonth();
                $end_date = $request->end_date != null ? Carbon::createFromFormat('Y-m-d', $request->end_date) : Carbon::now()->endOfMOnth();
            }

            $data = Transaction::select('transactions.*')
                ->when($request->has('description'), function($query) use ($request) {
                    $query->where('transactions.description', 'LIKE', '%'.$request->description.'%');
                })
                ->when($request->type_transaction != '', function($query) use ($request) {
                    $query->where('transactions.type', '=', $request->type_transaction);
                })
                ->where('transactions.user_id', '=', Auth::user()->id)
                ->whereDate('transactions.created_at', '>=', $start_date)
                ->whereDate('transactions.created_at', '<=', $end_date)
                ->orderBy('id', 'desc');

            return DataTables::eloquent($data)
                ->toJson();
        }
    }

    public function processTransaction(Request $request)
    {
        if ($request->isMethod('post')) {
            DB::beginTransaction();
            try {
                $balance = Auth::user()->balance;
                $users = User::where('id', Auth::user()->id)->first();
                $request->validate([
                    'type' => 'required',
                    'amount' => 'required',
                    'description' => 'required'
                ]);

                if ($request->type === 'transaction' && $request->amount > $balance) {
                    throw new Exception('Transaction failed, your must topup balance.');
                }

                $transaction = new Transaction;
                $transaction->user_id = Auth::user()->id;
                $transaction->type = $request->type;
                $transaction->amount = $request->amount;
                $transaction->description = $request->description;

                if ($request->type === 'transaction') {
                    $users->balance = $users->balance - $request->amount;
                } else {
                    $users->balance = $users->balance + $request->amount;
                }

                $transaction->save();
                $users->save();

                DB::commit();

                return redirect()->route('home')->with(['success' => 'Transaction successfully.']);
            } catch (Exception $e) {
                return redirect()->back()->with(['error' => $e->getMessage()]);
            }
        }
    }
}