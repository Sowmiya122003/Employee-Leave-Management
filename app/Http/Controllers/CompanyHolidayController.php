<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Mail\HolidayMail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CompanyHoliday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;

class CompanyHolidayController extends Controller
{
    public function holidayList(Request $request)
    {
        $holidays = CompanyHoliday::select('id', 'title', 'holiday_date');
        if ($request->ajax()) {
            return DataTables::of($holidays)
                ->addColumn('Action', function ($row) {
                    \Log::info($row);
                    return "<a href = '" .
                        route('admin.edit.companyholiday', $row->id) .
                        "'><i class='bi bi-pencil'></i></a>
                    <a href='" .
                        route('admin.delete.companyholiday', $row->id) .
                        "' onclick=\"return confirm('Do you want to delete?')\">
                        <i class='bi bi-trash'></i></a>";
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="employee-checkbox" value="' . $row->id . '">';
                })
                ->rawColumns(['Action', 'checkbox'])
                ->toJson();
        }
        return view('admin.holiday.company_holiday_list', ['holidays' => $holidays]);
    }
    public function holidayForm()
    {
        if (auth()->user()->id == 1) {
            return view('admin.holiday.company_holiday');
        }
        return redirect()->route('manager.holiday.list')->with('error', 'Access Denied');
    }
    public function holidayCreate(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required',
            'holiday_date' => 'required',
            'reason' => 'nullable|string',
        ]);
        $validate['created_by'] = auth()->user()->id;
        $holiday = CompanyHoliday::create($validate);
        return redirect()->route('manager.holiday.list')->with('success', 'Holiday Added Successfully!');
    }
    public function sendHolidayPdf(Request $request)
    {
        if (auth()->user()->role_id == 1) {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:users,id',
            ]);
            $users = User::whereIn('id', $request->ids)->select('full_name', 'email')->get();
            $holidays = CompanyHoliday::select('id', 'title', 'holiday_date')->get();
            $pdf = Pdf::loadView('pdf.holiday', compact('holidays'));
            $path = public_path('uploads/holiday.pdf');
            $pdf->save($path);
            foreach ($users as $user) {
                Mail::to($user->email)->queue(new HolidayMail($path, $user->full_name));
            }
            return response()->json([
                'message' => 'Mail sent successfully',
            ]);
        }
        return redirect()->back()->with('error', 'Access Denied !');
    }
    public function editCompanyHoliday(string $id)
    {
        if (auth()->user()->role_id == 1) {
            $holiday = CompanyHoliday::where('id', $id)->firstOrFail();
            return view('admin.holiday.edit-companyholiday', compact('holiday'));
        }
        return redirect()->back()->with('error','Access Denied !');
    }
    public function updateCompanyHoliday(Request $request, string $id)
    {
        if (auth()->user()->role_id == 1) {
            $holiday = CompanyHoliday::findOrFail($id);
            $holiday->update([
                'title' => $request->title,
                'holiday_date' => $request->holiday_date,
                'reason' => $request->reason,
            ]);
            return redirect()->route('manager.holiday.list')->with('success', 'Company Holiday Updated Successfully!');
        }
        return redirect()->route('manager.holiday.list')->with('error', 'Access Denied !');
    }
    public function destroyCompanyHoliday(string $id)
    {
        if (auth()->user()->role_id == 1) {
            $holiday = CompanyHoliday::findOrFail($id);
            $holiday->delete();
            return redirect()->route('manager.holiday.list')->with('success', 'Company Holiday Deleted Successfully!');
        }
        return redirect()->route('manager.holiday.list')->with('error', 'Access Denied !');
    }

    public function bulkDelete(Request $request)
    {
        if (auth()->user()->role_id == 1) {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:company_holidays,id',
            ]);

            CompanyHoliday::whereIn('id', $request->ids)->delete();

            return response()->json([
                'message' => 'Selected holidays deleted successfully',
            ]);
        }
        return redirect()->back()->with('error', 'Access Denied !');
    }
}
