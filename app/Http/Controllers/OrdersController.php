<?php

namespace App\Http\Controllers;

use PDF;
use Excel;
use Mail;
use Config;
use App\User;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\DataTables\OrderDataTable;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Auth;
use App\DataTables\OrderListDataTable;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->id;
        // $orders = Order::all()->sortByDesc("created_at");
        // $confirmed = Order::where('owner', $user)->whereNotNull('confirmed')->sum('amount');
        // $notConfirmed = Order::where('owner', $user)->whereNull('confirmed')->sum('amount');
        return view('orders.orderlist');
    }
    public function getOrders(OrderListDataTable $dataTable)
    {
        $user = Auth::user()->id;
        $confirmed = Order::where('owner', $user)->whereNotNull('confirmed')->sum('amount');
        $notConfirmed = Order::where('owner', $user)->whereNull('confirmed')->sum('amount');
        return $dataTable->render('orders.orderlist', ['confirmed' => $confirmed, 'notConfirmed' => $notConfirmed]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('orders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        $user = Auth::user()->email;
        $name = Auth::user()->name;
        if ($request['amount'] == null) {
            $amount = 0;
        }else {
            $amount = $request['amount'];
        }
        $order = Order::create([
            'bill_number' => $request['bill_number'],
            'date_of_invoice' => $request['date_of_invoice'],
            'object' => $request['object'],
            'amount' => $amount,
            'owner' => Auth::user()->id,
        ]);
        $order->save();
        Mail::send('emails.order-message', [
            'bill_number' => $request['bill_number'],
            'date_of_invoice' => $request['date_of_invoice'],
            'object' => $request['object'],
            'amount' => $request['amount'],
        ], function ($mail) use ($user, $name) {
            $mail->from($user, $name);
            $mail->to($user)->subject('Order');
        });

        Mail::send('emails.order-message-admin', [
            'bill_number' => $request['bill_number'],
            'date_of_invoice' => $request['date_of_invoice'],
            'object' => $request['object'],
            'amount' => $request['amount'],
            'id' => $order->id,
        ], function ($mail) use ($user, $name) {
            $mail->from($user, $name);
            $mail->to(Config::get('administrator.admins'))->subject('Order Confirmation');
        });

        if($order){
            return redirect('/orders/create')->with('orderCreate', 'Your order is being processed');
        }
        return redirect('/orders/create')->with('orderCreateFail', 'Order was not created. Please try again');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $user = User::where('id', $order->owner)->first();
        return view('orders.editOrder', ['order' => $order, 'user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrderRequest $request, $id)
    {
        $order = Order::where('id', $id)->update([
            'bill_number' => $request['bill_number'],
            'date_of_invoice' => $request['date_of_invoice'],
            'object' => $request['object'],
            'amount' => $request['amount'],
        ]);
        $orderOwner = Order::where('id', $id)->first();
        $user = User::where('id', $orderOwner->owner)->first();
        if($order){
            return redirect('admin/users/' . $user->id)->with('orderUpdate', 'Order updated successfully');
        }
        return redirect('admin/users/' . $user->id)->with('orderUpdateFail', 'Order was not updated. Please try again');
    }

    public function editFromOrders(Order $order)
    {
        return view('orders.editOrderFromOrders', ['order' => $order]);
    }

    public function updateFromOrders(OrderRequest $request, $id)
    {
        $order = Order::where('id', $id)->update([
            'bill_number' => $request['bill_number'],
            'date_of_invoice' => $request['date_of_invoice'],
            'object' => $request['object'],
            'amount' => $request['amount'],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Order::findOrFail($id)->delete();
    }
    public function multipleDelete($ids)
    {
        $order = explode(',', $ids);
        Order::whereIn('id',$order)->delete();
    }

    public function confirm(Order $order)
    {
        $user = User::where('id', $order->owner)->first();
        if ($order->confirmed == null) {
            $order->confirmed = Carbon::now();
            $order->save();
            Mail::send('emails.order-confirmed', [
                'msg',
            ], function ($mail) use ($user) {
                $mail->from($user->email, $user->name);
                $mail->to($user->email)->subject('Order');
            });
        } else {
            return redirect('/admin/orders')->with('orderIsConfirmed', 'Order is already confirmed.');
        }
    }

    public function allOrders()
    {
        return view('orders.allOrders');
    }

    public function getAllOrders(OrderDataTable $dataTable)
    {
        return $dataTable->render('orders.allOrders');
    }

    public function pdf()
    {
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($this->convert_orders_data_to_html());
        return $pdf->stream('orders.pdf');
    }
    public function convert_orders_data_to_html()
    {
        $order_data = Order::all();
        $output = '
        <h3 align="center">Orders</h3>
        <table width="100%" style="border-collapse: collapse; border: 0px;">
        <tr>
            <th style="border: 1px solid; padding:5px;" width="20%">Rechnungsnummer</th>
            <th style="border: 1px solid; padding:5px;" width="20%">Rechnungsdatum</th>
            <th style="border: 1px solid; padding:5px;" width="15%">Objekt</th>
            <th style="border: 1px solid; padding:5px;" width="15%">Betrag exkl. MwSt</th>
            <th style="border: 1px solid; padding:5px;" width="15%">Confirmed</th>
            <th style="border: 1px solid; padding:5px;" width="15%">Created At</th>
        </tr>
        ';
        foreach($order_data as $order)
        {
            $output .= '
            <tr>
            <td style="border: 1px solid; padding:5px;">'.$order->bill_number.'</td>
            <td style="border: 1px solid; padding:5px;">'.$order->date_of_invoice.'</td>
            <td style="border: 1px solid; padding:5px;">'.$order->object.'</td>
            <td style="border: 1px solid; padding:5px;">'.$order->amount.' CHF</td>
            <td style="border: 1px solid; padding:5px;">'.$order->confirmed.'</td>
            <td style="border: 1px solid; padding:5px;">'.$order->created_at.'</td>
            </tr>
            ';
        }
        $output .= '</table>';
        return $output;
    }
    function excel()
    {
        $order_data = DB::table('orders')->get()->toArray();
        $order_array[] = array('Rechnungsnummer', 'Rechnungsdatum', 'Objekt', 'Betrag exkl. MwSt', 'Confirmed', 'Created At');
        foreach($order_data as $order)
        {
            $order_array[] = array(
                'Rechnungsnummer'  => $order->bill_number,
                'Rechnungsdatum'   => $order->date_of_invoice,
                'Objekt'    => $order->object,
                'Betrag exkl. MwSt'  => $order->amount.' CHF',
                'Confirmed'   => $order->confirmed,
                'Created At'   => $order->created_at
            );
        }
        Excel::create('Orders', function($excel) use ($order_array){
            $excel->setTitle('Orders');
            $excel->sheet('Orders', function($sheet) use ($order_array){
            $sheet->fromArray($order_array, null, 'A1', false, false);
            });
        })->download('xlsx');
    }
}
