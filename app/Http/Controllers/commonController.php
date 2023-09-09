<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\storemedicines;
use App\Models\prescription;
use App\Models\remarks;
use App\Models\notes;
use App\Models\patient;
use App\Models\User;
use App\Models\voucher;
use App\Models\vouchermedicine;
use App\Models\attendbee;
use App\Models\attendcee;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
class commonController extends Controller
{
    //showing new medicine page
    public function viewAddMedcinePage(){
        try{    
            $user =User::find(Auth::id());
            return view("medicine/viewAddMedcinePage")->with(['User'=>$user]);
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    //adding new medicine in database
    public function addNewMedicine(Request $request){
        try{    
            $Medicine = storeMedicines::where('name',$request->get('name'))
            ->where('medicineType',$request->get('medicineType'))
            ->first();
            if($Medicine==null){
                $medicine=new storemedicines;
                $medicine->name= $request->get('name');
                $medicine->purchaseType= $request->get('purchaseType');
                $medicine->medicineType= $request->get('medicineType');
                $medicine->quantity= $request->get('quantity');
                $medicine->save();
                Alert::success('Success','New Medicine Successfully Added');
                return redirect()->back();
            }
            else{
                $Medicine->quantity=(int)$Medicine->quantity + (int)$request->get('quantity');
                $Medicine->save();
                Alert::success('Success','Medicine Successfully Added');
                return redirect()->back();
            }   
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
        
    }

    //showing all medicine
    public function showingMedicines(){
        try{
            $medicines=storeMedicines::all();
            $user =User::find(Auth::id());
            return view("medicine/showingMedicines")->with([
                'Medicines' => $medicines,'User'=>$user
            ]);
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }


    //showing new User page
    public function viewAddUserPage (){
        try{       
            $user =User::find(Auth::id());
            return view("user/viewAddUserPage")->with(['User'=>$user]);
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    //adding new user in database
    public function addNewUser(Request $request){
        try{
            $user=new User;
            $user->name= $request->get('name');
            $user->email= $request->get('email');
            $user->role= $request->get('role');
            $user->password=Hash::make($request->get('password'));
            $user->save();
            Alert::success('Success','New User Successfully Added');
            return redirect()->back();
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }  
    }

    public function showingUser(){
        try{
            $users=User::all();
            $user =User::find(Auth::id());
            return view("user/showingUser")->with([
                'Users' => $users,'User'=>$user
            ]);
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }
    
    public function viewAddPatientPage (){
        try{       
            $user =User::find(Auth::id());
            return view("patient/viewAddPatientPage")->with(['User'=>$user]);
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    public function addNewPatient(Request $request){
        try{
            foreach ($request->prescribedMedicine as $medicine) {
                if($medicine['prescribedMedicine']!=null && $medicine['quantity']!=null && $medicine['type']!=null){
                    $Medicine = storeMedicines::where('name','=',$medicine['prescribedMedicine'])
                    ->where('medicineType','=',$medicine['type'])
                    ->first();
                    if($Medicine==null){
                        Alert::error('Error','Medicine not Found in Store');
                        return redirect()->back();
                    }
                    if((int)($medicine['quantity'])>(int)($Medicine->quantity)){
                        Alert::error('Error','Not Enough Quantity in Store');
                        return redirect()->back();
                    }
                }
            }
            $patient=new patient;
            $patient->name= $request->get('name');
            $patient->regtNumber= $request->get('regtNumber');
            $patient->unit= $request->get('unit');
            $patient->rank= $request->get('rank');
            $patient->diagnosis= $request->get('diagnosis');
            $patient->doctorId=Auth::id();
            $patient->status= 'fromDoctor';
            $patient->save();
            foreach ($request->prescribedMedicine as $medicine) {
                if($medicine['prescribedMedicine']!=null && $medicine['quantity']!=null  && $medicine['type']!=null){
                    $Medicine = storeMedicines::where('name',$medicine['prescribedMedicine'])
                    ->where('medicineType',$medicine['type'])
                    ->first();
                    $Medicine->quantity=(int)($Medicine->quantity)-(int)($medicine['quantity']);
                    $Medicine->save();
                    $prescribedMedicine=new prescription;
                    $prescribedMedicine->medicineId=$Medicine->id;
                    $prescribedMedicine->isIssued=false;
                    $prescribedMedicine->quantity=$medicine['quantity'];
                    $prescribedMedicine->patientId=$patient->id;
                    $prescribedMedicine->save();
                }
            }
            foreach ($request->attendbee as $attendbee) {
                if($attendbee['to']!=null && $attendbee['from']!=null){
                    $Attendbee=new attendbee;
                    $Attendbee->to=$attendbee['to'];
                    $Attendbee->from=$attendbee['from'];
                    $Attendbee->patientId=$patient->id;
                    $Attendbee->save();
                }
            }
            foreach ($request->attendcee as $attendcee) {
                if($attendcee['to']!=null && $attendcee['from']!=null){
                    $Attendcee=new attendcee;
                    $Attendcee->to=$attendcee['to'];
                    $Attendcee->from=$attendcee['from'];
                    $Attendcee->patientId=$patient->id;
                    $Attendcee->save();
                }
            }
            
            foreach ($request->remark as $remark) {
                if($remark['speciality']!='null' && $remark['hospital']!='null'){
                    $Remarks=new remarks;
                    $Remarks->referredTo=$remark['hospital'].'  '.$remark['speciality'];
                    $Remarks->patientId=$patient->id;
                    $Remarks->save();
                }
            }
            if($request->note!=[null]){
                foreach ($request->note as $note) {
                    $Note=new notes;
                    $Note->note=$note;
                    $Note->patientId=$patient->id;
                    $Note->save();
                }
            }  
            Alert::success('Success','Patient Checked Successfully');
            return redirect()->route('patient.check');
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    public function showingSinlgePatient ($id){
        try{    
            $user =User::find(Auth::id());
            $patient=patient::where('id','=',$id)->with(['prescription.medicine','notes','remarks','attendbee','attendcee'])->first();
            return view("patient/singlePatientData")->with(['Patient'=>$patient,'User'=>$user]);    
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    public function downloadpdf($id){
        try{
            $patient=patient::where('id','=',$id)->with(['prescription.medicine','notes','remarks','attendbee','attendcee'])->first();
            $user =User::find($patient->doctorId);
            $prescription='';
            $index=1;
            foreach ($patient->prescription as $prep){
                $prescription.='
                <tr>

                <td style="
                text-align: left;
                padding: 10px;">'.$index.'</td>
                <td style="
                text-align: left;
                padding: 10px;">'.$prep->medicine->name.'</td>

                <td style="
                text-align: left;
                padding: 10px;">'.$prep->medicine->medicineType.'</td>
                <td style="
                text-align: left;
                padding: 10px;">'.$prep->quantity.'</td>';
                if(($prep->isIssued)==true){
                    $prescription.='<td style="
                    text-align: left;
                    padding: 10px;">true</td>';
                }
                else{
                    $prescription.='<td style="
                    text-align: left;
                    padding: 10px;">false</td>';
                }
                $prescription.='</tr>';
                $index++;
            }
            $remarks='';
            foreach ($patient->remarks as $rem){
                $remarks.='
                <tr>
                <td style="
                text-align: left;
                padding: 10px;">'.$rem->referredTo.'</td>
                </tr>';
            }
            $attendbees='';
            foreach ($patient->attendbee as $attendbee){
                $attendbees.='
                <tr>
                <td style="
                text-align: left;
                padding: 10px;">'.$attendbee->from.'</td>
                <td style="
                text-align: left;
                padding: 10px;">'.$attendbee->to.'</td>
                </tr>';
            }
            $attendcees='';
            foreach ($patient->attendcee as $attendcee){
                $attendcees.='
                <tr>
                <td style="
                text-align: left;
                padding: 10px;">'.$attendcee->from.'</td>
                <td style="
                text-align: left;
                padding: 10px;">'.$attendcee->to.'</td>
                </tr>';
            }
            $notes='';
            foreach ($patient->notes as $note){
                $notes.='
                <tr>
                <td style="
                text-align: left;
                padding: 10px;">'.$note->note.'</td>
                </tr>';
            }
            $mpdf = new \Mpdf\Mpdf();
            $html1='
                <h2 style="text-align:center">Treatment CHIT - Ghazaband Scouts MRC</h2>        
                <h2 style="text-align:center">Treated by - '.$user->name.'</h2>
                <hr>
                <div style="
                width:100%;
                margin-left:-5px;
                margin-right:-5px;box-sizing: border-box;">
                <div style="
                float: left;
                width: 100%;
                padding: 2px;box-sizing: border-box;">

                <table style="border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;box-sizing: border-box;">

                    <tr>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Regt Number</td>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Name</td>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Date</td>
                    
                    </tr>
                    <tr>
                    <td style="
                    text-align: left;
                    padding: 10px;" >'.$patient->regtNumber.'</td>
                    <td style="
                    text-align: left;
                    padding: 10px;" >'.$patient->name.'</td>
                    <td style="
                    text-align: left;
                    padding: 10px;" >'.$patient->created_at.'</td>
                    </tr>
                    <tr>
                </table>
                <br>
                <table style="border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;box-sizing: border-box;">
                    <tr>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Rank</td>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Unit</td>
                    </tr>
                    <tr>
                    <td style="
                    text-align: left;
                    padding: 10px;">'.$patient->rank.'</td>
                    <td style="
                    text-align: left;
                    padding: 10px;">'.$patient->unit.'</td>
                    </tr>
                    <tr>
                </table>
                <br>
                <table style="border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;box-sizing: border-box;">
                    <tr>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Dignosis</td>
                    </tr>
                    <tr>
                    <td style="
                    text-align: left;
                    padding: 10px;">'.$patient->diagnosis.'</td>
                    </tr>
                    <tr>
                </table>
                <br>
                <table style="border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;box-sizing: border-box;">
                    <tr>

                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">No.</td>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Preciption</td>

                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Type</td>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Quantity</td>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Issued</td>
                    </tr>
                    '.$prescription.'
                </table>
                <br>
                <table style="border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;box-sizing: border-box;">
                    <tr>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Referred To</td>
                    </tr>
                    '.$remarks.'
                </table>
                <br>
                Attend BEE
                <table style="border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;box-sizing: border-box;">
                    <tr>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">From</td>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">To</td>
                    </tr>
                    '.$attendbees.'
                </table>
                <br>Attend CEE
                <table style="border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;box-sizing: border-box;">
                    <tr>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">From</td>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">To</td>
                    </tr>
                    '.$attendcees.'
                </table>
                <br>
                <table style="border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;box-sizing: border-box;">
                    <tr>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Physician Notes</td>
                    </tr>
                    '.$notes.'
                </table>
                </div>
                </div>
                <hr>';
            $mpdf->WriteHTML($html1);
            
            $mpdf->Output('patient '.$patient->id.'.pdf', 'D');
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }
    public function onlyAddNewPatient(Request $request){
        try{
            $patient=new patient;
            $patient->name= $request->get('name');
            $patient->regtNumber= $request->get('regtNumber');
            $patient->unit= $request->get('unit');
            $patient->rank= $request->get('rank');
            $patient->status= 'toDoctor';
            $patient->save();
            Alert::success('Success','New Patient Successfully Added');
            return redirect()->back();
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    public function showingCheckingPatient(){
        try{
            $patients=patient::with(['prescription','notes','remarks'])->where('status','=','toDoctor')->get();
            $user =User::find(Auth::id());
            return view('patient/showingCheckingPatient')->with(['Patients'=>$patients,'User'=>$user]);    
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

   public function checkPatient($id){
        try{
            $patient=patient::where('id','=',$id)->with(['prescription.medicine','notes','remarks'])->first();
            $user =User::find(Auth::id());
            return view("patient/checkPatient")->with(['Patient'=>$patient,'User'=>$user]);
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    public function checkingPatient(Request $request,$id){
        try{
            foreach ($request->prescribedMedicine as $medicine) {
                if($medicine['prescribedMedicine']!=null && $medicine['quantity']!=null && $medicine['type']!=null){
                    $Medicine = storeMedicines::where('name','=',$medicine['prescribedMedicine'])
                    ->where('medicineType','=',$medicine['type'])
                    ->first();
                    if($Medicine==null){
                        Alert::error('Error','Medicine not Found in Store');
                        return redirect()->back();
                    }
                    if((int)($medicine['quantity'])>(int)($Medicine->quantity)){
                        Alert::error('Error','Not Enough Quantity in Store');
                        return redirect()->back();
                    }
                }
            }
            $patient=patient::find($id);
            $patient->name= $request->get('name');
            $patient->regtNumber= $request->get('regtNumber');
            $patient->unit= $request->get('unit');
            $patient->rank= $request->get('rank');
            $patient->diagnosis= $request->get('diagnosis');
            $patient->doctorId=Auth::id();
            $patient->status= 'fromDoctor';
            $patient->save();
            foreach ($request->prescribedMedicine as $medicine) {
                if($medicine['prescribedMedicine']!=null && $medicine['quantity']!=null && $medicine['type']!=null){
                    $Medicine = storeMedicines::where('name',$medicine['prescribedMedicine'])
                    ->where('medicineType',$medicine['type'])
                    ->first();
                    $Medicine->quantity=(int)($Medicine->quantity)-(int)($medicine['quantity']);
                    $Medicine->save();
                    $prescribedMedicine=new prescription;
                    $prescribedMedicine->medicineId=$Medicine->id;
                    $prescribedMedicine->isIssued=false;
                    $prescribedMedicine->quantity=$medicine['quantity'];
                    $prescribedMedicine->patientId=$patient->id;
                    $prescribedMedicine->save();
                }
            }
            foreach ($request->attendbee as $attendbee) {
                if($attendbee['to']!=null && $attendbee['from']!=null){
                    $Attendbee=new attendbee;
                    $Attendbee->to=$attendbee['to'];
                    $Attendbee->from=$attendbee['from'];
                    $Attendbee->patientId=$patient->id;
                    $Attendbee->save();
                }
            }
            foreach ($request->attendcee as $attendcee) {
                if($attendcee['to']!=null && $attendcee['from']!=null){
                    $Attendcee=new attendcee;
                    $Attendcee->to=$attendcee['to'];
                    $Attendcee->from=$attendcee['from'];
                    $Attendcee->patientId=$patient->id;
                    $Attendcee->save();
                }
            }
            foreach ($request->remark as $remark) {
                if($remark['speciality']!='null' && $remark['hospital']!='null'){
                    $Remarks=new remarks;
                    $Remarks->referredTo=$remark['hospital'].'  '.$remark['speciality'];
                    $Remarks->patientId=$patient->id;
                    $Remarks->save();
                }
            }
            if($request->note!=[null]){
                foreach ($request->note as $note) {
                    $Note=new notes;
                    $Note->note=$note;
                    $Note->patientId=$patient->id;
                    $Note->save();
                }
            }  
            Alert::success('Success','Patient Checked Successfully');
            return redirect()->route('patient.check');
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    public function deletePatient($id){
        try{
            patient::destroy($id);
            Alert::success('Success','Patient SuccessFully Deleted');
            return redirect()->back();
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    public function deleteUser($id){
        try{
            User::destroy($id);
            Alert::success('Success','User Successfully Deleted');
            return redirect()->back();
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }
    public function deleteMedicine($id){
        try{
            storemedicines::destroy($id);
            Alert::success('Success','Medicine Successfully Deleted');
            return redirect()->back();
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    public function editMedicinePage($id){
        try{
            $medicine=storemedicines::find($id);
            $user =User::find(Auth::id());
            return view("medicine/editMedicineData")->with(['Medicine'=>$medicine,'User'=>$user]);
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    public function editMedicine(Request $request,$id){
        try{
            $medicine=storemedicines::find($id);
            $medicine->name= $request->get('name');
            $medicine->purchaseType= $request->get('purchaseType');
            $medicine->medicineType= $request->get('medicineType');
            $medicine->quantity= $request->get('quantity');
            $medicine->save();
            Alert::success('Success','Medicine Data Edited Successfully');
            return redirect()->back();
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    public function giveMedicinePage($patientId){
        try{
            $patient=patient::where('id','=',$patientId)->with(['prescription.medicine','notes','remarks','attendbee','attendcee'])->first();
            $user =User::find(Auth::id());
            return  view("medicine/giveMedicine")->with(['Patient'=>$patient,'User'=>$user]);
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    public function giveMedicine(Request $request,$patientId){
        try{
            $found=false;
            if($request->medicine!=null){
                foreach ($request->medicine as $medicine) {
                    $prescription=  prescription::find(array_keys($medicine)[0]);
                    if(array_values($medicine)[0]=='false'){
                        $prescription->isIssued=false;
                        $found=true;
                    }
                    else{
                        $prescription->isIssued=true;
                    }
                    $prescription->save();
                }
            }
            if($found==false){
                Alert::success('Success','Medicine Cleared');
            }
            else{
                Alert::alert('Alert','Some Medicines not Issued');
            }
            return redirect()->route('home');
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    public function fromDoctorPatients(){
        try{
            $patients=patient::with(['prescription','notes','remarks','attendbee','attendcee'])->where('status','=','done')->get();
            $user =User::find(Auth::id());
            $fromDoctorPatients=patient::with(['prescription','notes','remarks'])->where('status','=','fromDoctor')->get();
            return view('patient/fromDoctorPatients')->with(['Patients'=>$patients,'User'=>$user,'fromDoctorPatients'=>$fromDoctorPatients]);
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    public function clearTreatmentChit($id){
        try{
            $patient=patient::where('id','=',$id)->with(['prescription','notes','remarks','attendbee','attendcee'])->first();
            foreach($patient->prescription as $prescription){
                if($prescription->isIssued==false){
                    Alert::success('Alert','Treatment CHIT can\'t be Cleared');
                    return redirect()->route('home');
                }
            }
            $patient->status='done';
            $patient->save();
            Alert::success('Success','Treatment CHIT Cleared');
            return redirect()->route('home');
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    public function addingVoucherPage(){
        try{
            $user =User::find(Auth::id());
            return view('medicine/addingVoucherPage')->with(['User'=>$user]);
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    public function addingVoucher(Request $request){
        try{
            foreach ($request->prescribedMedicine as $medicine) {
                if($medicine['prescribedMedicine']!=null && $medicine['quantity']!=null && $medicine['type']!=null){
                    $Medicine = storeMedicines::where('name',$medicine['prescribedMedicine'])
                    ->where('medicineType',$medicine['type'])
                    ->first();
                    if($Medicine==null){
                        Alert::error('Error','Medicine not Found in Store');
                        return redirect()->back();
                    }
                    if((int)($medicine['quantity'])>(int)($Medicine->quantity)){
                        Alert::error('Error','Not Enough Quantity in Store');
                        return redirect()->back();
                    }
                }
            }
            $voucher = new voucher;
            $voucher->date = $request->get('date');
            $voucher->wing = $request->get('wing');
            $voucher->unit = $request->get('unit');
            $voucher->station = $request->get('station');
            $voucher->issuedBy = $request->get('issuedBy');
            $voucher->issuingOffer = $request->get('IssuingOffer');
            $voucher->save();
            foreach ($request->prescribedMedicine as $medicine) {
                if($medicine['prescribedMedicine']!=null && $medicine['quantity']!=null && $medicine['type']!=null){
                    $Medicine = storeMedicines::where('name',$medicine['prescribedMedicine'])
                    ->where('medicineType',$medicine['type'])
                    ->first();
                    $Medicine->quantity=(int)($Medicine->quantity)-(int)($medicine['quantity']);
                    $Medicine->save();
                    $vouchermedicine=new vouchermedicine;
                    $vouchermedicine->medicineId=$Medicine->id;
                    $vouchermedicine->quantity=$medicine['quantity'];
                    $vouchermedicine->voucherId=$voucher->id;
                    $vouchermedicine->save();
                }
            }
            Alert::success('Success','Voucher Successfully Entered');
            return redirect()->route('home');
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    public function allVoucher(){
        try{
            $user =User::find(Auth::id());
            $vouchers=voucher::with(['medicines'])->get();
            return view('medicine/allVoucher')->with(['User'=>$user,'Vouchers'=>$vouchers]);
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    public function voucherPdf($id){
        try{
            $voucher=voucher::where('id','=',$id)->with(['medicines.medicinename'])->first();
            $medicines='';
            $index=1;
            foreach ($voucher->medicines as $med){
                $medicines.='
                <tr>
                <td style="
                text-align: left;
                padding: 10px;">'.$index.'</td>
                
                <td style="
                text-align: left;
                padding: 10px;">'.$med->medicinename->name.'</td>
                
                <td style="
                text-align: left;
                padding: 10px;">'.$med->medicinename->medicineType.'</td>
                <td style="
                text-align: left;
                padding: 10px;">'.$med->quantity.'</td>
                </tr>';
                $index++;
            }
            $mpdf = new \Mpdf\Mpdf();
            $html1='
                <h2 style="text-align:center">Issue Receipt Voucher - Medicine</h2>    
                <p>Date : '.$voucher->date.'</p>
                <p>Unit : '.$voucher->unit.'</p>
                <p>Station : '.$voucher->station.'</p>    
                <h2 style="text-align:center">Medicine For The '.date("F Y", strtotime($voucher->date)).' Of '.$voucher->wing.'</h2>
                <hr>
                <div class="row">
                <div class="column">
                <table style="border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;box-sizing: border-box;">
                    <tr>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">No.</td>
                    
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Medicine</td>

                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Type</td>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Quantity</td>
                    </tr>
                    '.$medicines.'
                </table>
                <br>
                </div>
                </div>
                <hr>
                <div class="row">
                <div class="column">
                <table style="border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;box-sizing: border-box;">
                    <tr>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Issued By_____________________</td>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Received By_____________________</td>
                    </tr>
                    <tr>
                    <td style="
                    text-align: left;
                    padding: 10px;">'.$voucher->issuedBy.'</td>
                    <td style="
                    text-align: left;
                    padding: 10px;"></td>
                    </tr>
                </table>
                <br>
                <br>
                <br>
                <br>
                </div>
                </div>
                <div class="row">
                <div class="column">
                <table style="border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;box-sizing: border-box;">
                    <tr>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Issuing Offer_____________________</td>
                    <td style="
                    text-align: left;
                    padding: 10px;
                    background-color: #e0e0e0;">Receiving Offer_____________________</td>
                    </tr>
                    <tr>
                    <td style="
                    text-align: left;
                    padding: 10px;">'.$voucher->issuingOffer.'</td>
                    <td style="
                    text-align: left;
                    padding: 10px;"></td>
                    </tr>
                </table>
                </div>
                </div>';
            $mpdf->WriteHTML($html1);
            
            $mpdf->Output('voucher '.$voucher->id.'.pdf', 'D');
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }

    public function downloadingPatientExcel(){
        try{
            $patients=patient::with(['prescription','notes','remarks','attendbee','attendcee'])->get();
            $export = fopen("backup.csv", "w");
            fputcsv($export, ['Date',"Regt.Number","Name","Rank","Unit","Diagnosis"]);
            foreach ($patients as $patient) {
                fputcsv($export, [$patient->created_at,$patient->regtNumber,$patient->name,$patient->rank,$patient->unit,$patient->diagnosis]);
            }
            fclose($export);
            return response()->download(public_path('backup.csv'));
        }
        catch(\Exception $e){
            Alert::success('Error',$e);
            return redirect()->back();
        }
    }
}
