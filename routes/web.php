<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('addingNewMedicine','App\\Http\\Controllers\\commonController@viewAddMedcinePage')->name('medicine.view');

Route::post('addingNewMedicine','App\\Http\\Controllers\\commonController@addNewMedicine')->name("medicine.add");

Route::get('showingMedicines','App\\Http\\Controllers\\commonController@showingMedicines')->name('medicines.show');

Route::get('addingNewUser','App\\Http\\Controllers\\commonController@viewAddUserPage')->name('user.view');

Route::post('addingNewUser','App\\Http\\Controllers\\commonController@addNewUser')->name("user.add");

Route::get('showingUser','App\\Http\\Controllers\\commonController@showingUser')->name('users.show');

Route::get('addingNewPatient','App\\Http\\Controllers\\commonController@viewAddPatientPage')->name('patient.view');

Route::post('addingNewPatient','App\\Http\\Controllers\\commonController@addNewPatient')->name("patient.add");

Route::post('onlyAddingNewPatient','App\\Http\\Controllers\\commonController@onlyAddNewPatient')->name("onlyPatient.add");

Route::get('showingPatient','App\\Http\\Controllers\\commonController@showingPatient')->name('patient.show');

Route::get('showingSinlgePatient/{id}','App\\Http\\Controllers\\commonController@showingSinlgePatient')->name('singlePatient.show');

Route::get('showingCheckingPatient','App\\Http\\Controllers\\commonController@showingCheckingPatient')->name('patient.check');

Route::get('checkPatient/{id}','App\\Http\\Controllers\\commonController@checkPatient')->name('checkPatient');

Route::post('checkPatient/{id}','App\\Http\\Controllers\\commonController@checkingPatient')->name('checkPatient.post');

Route::get('deletePatient/{id}','App\\Http\\Controllers\\commonController@deletePatient')->name('patient.delete');

Route::get('deleteUser/{id}','App\\Http\\Controllers\\commonController@deleteUser')->name('user.delete');

Route::get('deleteMedicine/{id}','App\\Http\\Controllers\\commonController@deleteMedicine')->name('medicine.delete');

Route::get('editMedicine/{id}','App\\Http\\Controllers\\commonController@editMedicinePage')->name('medicineEditPage');

Route::post('editMedicine/{id}','App\\Http\\Controllers\\commonController@editMedicine')->name('medicine.edit');

Route::get('giveMedicine/{id}','App\\Http\\Controllers\\commonController@giveMedicinePage')->name('giveMedicinePage');

Route::post('giveMedicineToPatient/{id}','App\\Http\\Controllers\\commonController@giveMedicine')->name('medicine.give');

Route::get('downloadpdf/{id}','App\\Http\\Controllers\\commonController@downloadpdf')->name('downloadpdf');

Route::get('fromDoctorPatients','App\\Http\\Controllers\\commonController@fromDoctorPatients')->name('fromDoctorPatients');

Route::get('clearTreatmentChit/{id}','App\\Http\\Controllers\\commonController@clearTreatmentChit')->name('clearTreatmentChit');

Route::get('addingVoucher','App\\Http\\Controllers\\commonController@addingVoucherPage')->name('voucher.view');

Route::post('addingVoucher','App\\Http\\Controllers\\commonController@addingVoucher')->name("voucher.add");

Route::get('allVoucher','App\\Http\\Controllers\\commonController@allVoucher')->name('allVoucher');

Route::get('voucherPdf/{id}','App\\Http\\Controllers\\commonController@voucherPdf')->name('voucherPdf');

Route::get('downloadingPatientExcel','App\\Http\\Controllers\\commonController@downloadingPatientExcel')->name('downloadingPatientExcel');
