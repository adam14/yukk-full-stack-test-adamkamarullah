<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use Carbon\Carbon;
use DB;
use Exception;

class UsersController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('users.user');
    }

    public function accessLogin(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                $request->validate([
                    'username' => 'required',
                    'password' => 'required',
                ]);

                if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
                    return redirect()->route('home');
                }

                throw new Exception('Wrong username or password');
            } catch (Exception $e) {
                return redirect()->route('index')->with(['error' => $e->getMessage()]);
            }
        }
    }

    public function register(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('users.register');
    }

    public function registerSave(Request $request)
    {
        if ($request->isMethod('post')) {
            DB::beginTransaction();
            try {
                $request->validate([
                    'name' => 'required',
                    'username' => 'required|unique:users',
                    'email' => 'required|email|unique:users',
                    'password' => 'required',
                    'confirm_password' => 'required|same:password'
                ]);

                $user = new User([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'balance' => 0.00
                ]);
                $user->save();

                DB::commit();

                return redirect()->route('index')->with(['success' => 'Registration success. Please login!']);
            } catch (Exception $e) {
                DB::rollback();

                return redirect()->route('register')->with(['error' => $e->getMessage()]);
            }
        }
    }

    public function logout()
    {
        Auth::logout();
        
        return redirect()->route('index')->with(['success' => 'Logout successfully']);
    }
}