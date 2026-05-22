<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Mail\HolidayMail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CompanyHoliday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CompanyHolidayController extends Controller
{
    public function holidaylist(){
        $holidays = CompanyHoliday::select('id','title','holiday_date')->get();
        return view('admin.holiday.company_holiday_list',['holidays'=>$holidays]);
    }
    public function holidayform(){
        return view('admin.holiday.company_holiday');
    }
    public function holidaycreate(Request $request){
        $validate = $request->validate([
            'title'=>'required',
            'holiday_date' =>'required',
            'reason'=>'nullable|string'
        ]);
        $validate['created_by'] = auth()->user()->id;
        $holiday = CompanyHoliday::create($validate);
        return redirect()->route('admin.dashboard');
    }
    public function sendHolidayPdf(){
        $users = User::select('full_name','email')->first();
        // dd($users->toArray());
        $holidays = CompanyHoliday::select('id','title','holiday_date')->get();
        $pdf = Pdf::loadView('pdf.holiday',compact('holidays'));
        $path=public_path('uploads/holiday.pdf');
        $pdf->save($path);
        // dd($pdf);
        // foreach($users as $user)
        Mail::to($users->email)->send(new HolidayMail($path,$users->full_name));
        return 'Mail Sent Successfully';
    }
}
