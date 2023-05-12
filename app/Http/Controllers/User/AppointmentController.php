<?php

namespace App\Http\Controllers\User;

use Svg\Tag\Rect;
use Carbon\Carbon;
use App\Models\User\UserDay;
use Illuminate\Http\Request;
use App\Models\User\Category;
use App\Models\User\Language;
use App\Models\User\UserTimeSlot;
use App\Models\User\UserHoliday;
use App\Models\User\BasicSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User\AppointmentBooking;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function setting()
    {
        return view('user.appointment.settings');
    }

    public function resetSerial()
    {
        $data = BasicSetting::where('user_id', Auth::guard('web')->user()->id)->first();
        $data->serial_reset = 0;
        $data->save();
        Session::flash('success', toastrMsg('Serial_reset_successfully!'));
        return back();
    }
    public function updateSetting(Request $request)
    {
        $data = BasicSetting::where('user_id', Auth::guard('web')->user()->id)->first();
        $data->appointment_category  = $request->appointment_category;
        $data->appointment_price  = $request->appointment_price;
        $data->full_payment  = $request->full_payment;
        $data->advance_percentage  = $request->advance_percentage;
        $data->guest_checkout  = $request->guest_checkout;
        $data->save();
        Session::flash('success', toastrMsg('Updated_successfully!'));
        return "success";
    }
    public function timeSlot()
    {
        $data['days'] = UserDay::where('user_id', Auth::guard('web')->user()->id)->get();
        return view('user.appointment.timeSlot', $data);
    }


    public function makeWeekend(Request $request)
    {

        UserDay::where('id', $request->day_id)->update([
            'weekend' => $request->status
        ]);
        Session::flash('success', toastrMsg('Updated_successfully!'));
        return back();
    }


    public function timeslotManagement(Request $request)
    {
        $data['timeslots'] = UserTimeSlot::where('day', $request->day)->where('user_id', Auth::guard('web')->user()->id)->get();
        return view('user.appointment.timeslot_management', $data);
    }
    public function timeslotStore(Request $request)
    {
        $rules = [
            'start' => 'required',
            'end' => 'required',
            'max_booking' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $tf = new UserTimeSlot();
        $tf->user_id = Auth::guard('web')->user()->id;
        $tf->day = $request->day_name;
        $tf->start = $request->start;
        $tf->end = $request->end;
        $tf->max_booking = $request->max_booking;
        $tf->save();
        Session::flash('success', toastrMsg('Store_successfully!'));
        return "success";
    }
    public function timeslotUpdate(Request $request)
    {
        $rules = [
            'start' => 'required',
            'end' => 'required',
            'max_booking' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $tf = UserTimeSlot::find($request->timeslot_id);
        $tf->user_id = Auth::guard('web')->user()->id;
        $tf->start = $request->start;
        $tf->end = $request->end;
        $tf->max_booking = $request->max_booking;
        $tf->save();
        Session::flash('success', toastrMsg('Updated_successfully!'));
        return back();
    }
    public function timeslotDelete(Request $request)
    {
        $tf = UserTimeSlot::findOrFail($request->timeslot_id);
        $tf->delete();
        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }
    public function appointments(Request $request)
    {
        if ($request->routeIs('user.bookedAppointment')) {
            $status = '';
            $data['route'] = route('user.bookedAppointment');
        } elseif ($request->routeIs('user.pendingAppointment')) {
            $data['route'] = route('user.pendingAppointment');
            $status = 1;
        } elseif ($request->routeIs('user.approvedAppointment')) {
            $data['route'] = route('user.approvedAppointment');
            $status = 2;
        } elseif ($request->routeIs('user.completedAppointment')) {
            $data['route'] = route('user.completedAppointment');
            $status = 3;
        } elseif ($request->routeIs('user.rejectedAppointment')) {
            $data['route'] = route('user.rejectedAppointment');
            $status = 4;
        }
        $sl_no = $request->sl_no;
        $date = '';
        if ($request->date) {
            $dt = Carbon::parse($request->date);
            $date = $dt->format('Y-m-d');
        }
        $t_id = $request->t_id;
        $name = $request->name;

        $data['appointments'] = AppointmentBooking::where('user_id', Auth::guard('web')->user()->id)
            ->with('customer')
            ->when($sl_no, function ($query, $sl_no) {
                $query->where('serial_number', 'LIKE', '%' .  $sl_no . '%');
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($date, function ($query, $date) {
                $query->where('date', $date);
            })
            ->when($t_id, function ($query, $t_id) {
                $query->where('transaction_id', 'LIKE', '%' . $t_id . '%');
            })
            ->when($name, function ($query, $name) {
                $query->where('name', 'LIKE', '%' . $name . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(15);
        return view('user.appointment.appointments', $data);
    }
    public function chnageStatus(Request $request)
    {
        AppointmentBooking::where('id', $request->appointment_id)->update([
            'status' => $request->status
        ]);
        Session::flash('success', toastrMsg('Updated_successfully!'));
        return back();
    }
    public function chnagePaymentStatus(Request $request)
    {
        $appointment = AppointmentBooking::where('id', $request->appointment_id)->first();
        $appointment->payment_status =  $request->payment_status;
        if ($request->payment_status == '2') {
            $appointment->due_amount = null;
        } elseif ($request->payment_status == '1' || $request->payment_status == '3') {
            if (($appointment->amount != $appointment->total_amount) && ($appointment->due_amount == null)) {
                $appointment->due_amount = $appointment->total_amount - $appointment->amount;
            }
        }
        $appointment->save();



        Session::flash('success', toastrMsg('Updated_successfully!'));
        return back();
    }
    public function deleteAppointment(AppointmentBooking $appointment,  Request $request)
    {
        $appointment->delete();
        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }
    public function appointmentBulkDestroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $appointment = AppointmentBooking::findOrFail($id);
            $appointment->delete();
        }
        Session::flash('success',  toastrMsg('Bulk_Deleted_successfully!'));
        return 'success';
    }
    public function appointmentDetails(AppointmentBooking $appointment)
    {
        $data['appointment'] = $appointment;

        return view('user.appointment.details', $data);
    }
    public function appointmentEdit(AppointmentBooking $appointment)
    {
        $data['appointment'] = $appointment;
        $language = Language::where('code', request('language'))->where('user_id', Auth::guard('web')->user()->id)->first();
        $data['language'] = $language;
        $data['categories'] = Category::where('user_id', Auth::guard('web')->user()->id)->where('language_id', $language->id)->get();
        $day = strtolower(Carbon::parse($appointment->date)->format('l'));
        $data['timeSlots'] = UserTimeSlot::where('user_id', Auth::guard('web')->user()->id)->where('day', $day)->get();
        return view('user.appointment.edit', $data);
    }

    public function appointmentUpdate(Request $request, AppointmentBooking $appointment)
    {
        $rules = [
            'name' => 'required',
            'date' => 'required',
            'slot' => 'required',
            'amount' => 'required',
            'category_id' => $appointment->category_id !== null ? 'required': 'null',
        ];
        $message = [
            'amount.required' => 'The paid fee is required',
            'name.required' => 'The name is required',
            'date.required' => 'Plase select a date',
            'slot.required' => 'Plase confirm a time slot',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $appointment->name = $request->name;
        $appointment->date = $request->date;
        $appointment->time = $request->slot;
        $appointment->amount = $request->amount;
        $appointment->status = $request->status;
        $appointment->payment_status = $request->payment_status;
        $appointment->total_amount = $request->total_amount;
        $appointment->due_amount = $request->due_amount;
        $appointment->save();
        Session::flash('success',  toastrMsg('Updated_successfully!'));
        return 'success';
    }
    public function holidays()
    {
        $data['holidays'] = UserHoliday::where('user_id', Auth::guard('web')->user()->id)->get();
        return view('user.appointment.holidays', $data);
    }

    public function holidayStore(Request $request)
    {
        $rules = [
            'date' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $checck_data = UserHoliday::where('user_id', Auth::guard('web')->user()->id)->where('date', $request->date)->first();
        if (empty($checck_data)) {
            $tf = new UserHoliday();
            $tf->user_id = Auth::guard('web')->user()->id;
            $tf->date = $request->date;
            $tf->save();
            Session::flash('success', toastrMsg('Store_successfully!'));
            return "success";
        } else {
            Session::flash('warning', toastrMsg('This_date_already_taken!'));
            return "success";
        }
    }

    public function holidayDelete(Request $request)
    {
        $data = UserHoliday::findOrFail($request->holiday_id);
        $data->delete();
        Session::flash('success', toastrMsg('Deleted_successfully!'));
        return back();
    }
}
