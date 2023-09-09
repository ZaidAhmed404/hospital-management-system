<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\patient;
use App\Models\User;

use RealRashid\SweetAlert\Facades\Alert;
use App\Models\storemedicines;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try{
            $patients=patient::with(['prescription','notes','remarks','attendbee','attendcee'])->where('status','=','done')->get();
            $user =User::find(Auth::id());
            $fromDoctorPatients=patient::with(['prescription','notes','remarks'])->where('status','=','fromDoctor')->get();
            return view('home')->with(['Patients'=>$patients,'User'=>$user,'fromDoctorPatients'=>$fromDoctorPatients]);
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
        
    }
}
