<?php

use App\Models\customerModel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\customercontroller;
use App\Http\Controllers\employeecontroller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\invoicecontroller;
use App\Http\Controllers\jobCardcontroller;
use App\Http\Controllers\notificationcontroller;
use App\Models\jobCard;
use App\Models\invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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

Route::get('/', function (Request $request) {
    $a = DB::table('customer_models')->count();
    $b = DB::table('employees')->count();
    $invoice = DB::table('invoices')->count();
    
    $count = array("daily" => 0, "weekly" => 0, "monthly" => 0, "yearly" => 0);
    

    /** Job Card */
    /** Counting number of new jobcards daily */
    $count['daily'] = jobCard::where('created_at','>=',Carbon::today())->count();
    $newjobcardtoday = $count['daily'];

    /** Counting number of new jobcards monthly */
    $count['monthly'] = jobCard::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
    $newjobcardmonthly = $count['monthly'];
    //return dd($newjobcardmonthly);

    /** Customer */
    /** Counting number of new customers daily */
    $count['daily'] = customerModel::where('created_at','>=',Carbon::today())->count();
    $newcustomertoday = $count['daily'];

    /** Counting number of new customers monthly */
    $count['monthly'] = customerModel::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
    $newcustomermonthly = $count['monthly'];
    /** Counting number of new customers yearly */
    //return dd($newcustomeryearly);


    /** The profit */
    /** The Daily profit */
    $count['daily'] = invoice::where('created_at','>=',Carbon::today())->sum('totalPaid');
    $newprofittoday = $count['daily'];

    /**The Monthly profit */
    $count['monthly'] = invoice::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('totalPaid');
    $newprofitmonthly = $count['monthly'];
    //return dd($newprofitmonthly);

    /**Chart Queries to show the invoices*/
    $users = invoice::select(DB::raw("COUNT(*) as count"))->whereYear("created_at",date('Y'))->groupBy(DB::raw("Month(created_at)"))->pluck('count');

    $inwhichmonth = invoice::select(DB::raw("Month(created_at) as month"))->whereYear("created_at",date('Y'))->groupBy(DB::raw("Month(created_at)"))->pluck('month');


    /** Number of customers yearly to the chart */
    $usersyearly = customerModel::select(DB::raw("COUNT(*) as count"))->groupBy(DB::raw("Year(created_at)"))->pluck('count');
    $inwhichyear = customerModel::select(DB::raw("Year(created_at) as month"))->groupBy(DB::raw("Year(created_at)"))->pluck('month');
    

    
    //return dd($usersyearly, $inwhichyear, $datesyearly);


    $dates = array(0,0,0,0,0,0,0,0,0,0,0,0);
    foreach ($inwhichmonth as $key => $value) {
        $dates[$value] = $users[$key];
    }
    //return dd($datesyearly, $dates, $usersyearly);

    $income = invoice::select(DB::raw("sum(totalPaid) as count"))->whereYear("created_at",date('Y'))->groupBy(DB::raw("Month(created_at)"))->pluck('count');
    //return dd($income);

    $inwhichmonthincome = invoice::select(DB::raw("Month(created_at) as month"))->whereYear("created_at",date('Y'))->groupBy(DB::raw("Month(created_at)"))->pluck('month');

    
    //return dd($inwhichmonthincome);
    $incomes[0][0] = null;

    
    if($income) {
        foreach($income as $key => $values){
            $s = $key;
            for($j = $s; $j<=$key; $j++){
                $incomes[$j] = intval($values);
            }
        }
    } 

    $incomeineachmonth = array(0,0,0,0,0,0,0,0,0,0,0,0);
    foreach ($inwhichmonthincome as $key => $value) {
        $incomeineachmonth[$value] = $incomes[$key];
    }

    //$newincomeineachmonth = array_shift($incomeineachmonth);
    //return dd($income, $inwhichmonthincome, $incomeineachmonth);

     /** Get the current date */
    $now = Carbon::now();
    // $now->year;
    $thisMonth = $now->format('F');
    $thisDay = $now->format('l');
    //return dd($thisDay);
    //return dd($thisMonth, $incomes);
    /** change them to kurdish font */

    if($thisMonth == "January") {
        $thisMonth = '???????????? ??????????	';
    }elseif($thisMonth == 'February') {
        $thisMonth = '??????????';
    } elseif($thisMonth == "March") {
        $thisMonth = '??????????';
    }elseif($thisMonth == 'April') {
        $thisMonth = '??????????';
    }elseif($thisMonth == 'May') {
        $thisMonth = '????????';
    }elseif($thisMonth == 'June') {
        $thisMonth = '??????????';
    }elseif($thisMonth == 'July') {
        $thisMonth = '????????????????';
    }elseif($thisMonth == 'Augest') {
        $thisMonth = '??????';
    }elseif($thisMonth == 'September') {
        $thisMonth = '??????????????';
    }elseif($thisMonth == 'October') {
        $thisMonth = '???????????? ??????????';
    }elseif($thisMonth == 'November') {
        $thisMonth = '???????????? ??????????';
    }elseif($thisMonth == 'December') {
        $thisMonth = '???????????? ??????????';
    }

    if($thisDay == 'Saturday') {
        $thisDay = ' ????????????????';
    } elseif($thisDay == 'Sunday') {
        $thisDay = '????????? ????????????????';
    } elseif($thisDay == 'Monday') {
        $thisDay = ' ?????? ????????????????';
    }elseif($thisDay == 'Tuesday') {
        $thisDay = '???? ????????????????';
    }elseif($thisDay == 'Wednesday') {
        $thisDay = '???????? ????????????????';
    }elseif($thisDay == 'Thursday') {
        $thisDay = '???????? ????????????????';
    }elseif($thisDay == 'Friday') {
        $thisDay = '?????????????';
    }

    // $now->weekOfYear; 

    //return dd($thisMonth, $retVal);
    //return dd($income, $incomes);
    return view('welcome', compact('a', 'b', 'invoice', 'newjobcardtoday', 'newjobcardmonthly', 'newcustomertoday', 
    'newcustomermonthly', 'newprofittoday', 'newprofitmonthly', 'dates', 'incomeineachmonth', 'thisMonth', 'thisDay', 'usersyearly', 'inwhichyear'));
})->middleware('auth');

//Customer
Route::resource('customer', customercontroller::class);
Route::get('search', [customercontroller::class, 'search']);
Route::get('showall', [customercontroller::class, 'showall'])->name('showall');

//Employee
Route::resource('employee', employeecontroller::class);
//we are creating action here, so the form should be <form action="searchemployee" method="get"> because we have used 'get' here.
Route::get('searchemployee', [employeecontroller::class, 'search']);
Route::get('showallemployee', [employeecontroller::class, 'showall'])->name('showallemployee');
//Route::get('searchemployee', [employeecontroller::class, 'search']);
//Route::get('showallstatus', [employeecontroller::class, 'showallstatus'])->name('showallstatus');


//Invoice
Route::get('searchinvoice', [invoicecontroller::class, 'search']);
Route::resource('invoice', invoicecontroller::class);
Route::get('showallinvoice', [customercontroller::class, 'showallinvoice'])->name('showallinvoice');

//Jobcard
Route::get('searchjobcard', [jobCardcontroller::class, 'search']);
Route::resource('jobcard', jobCardcontroller::class);

//Notification
Route::get('searchnotification', [notificationcontroller::class, 'search']);
Route::resource('notification', notificationcontroller::class);
Route::get('showallnotification', [customercontroller::class, 'showallnotification'])->name('showallnotification');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
