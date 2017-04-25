<?php
namespace LaraMod\Admin\Orders\Controllers;

use App\Http\Controllers\Controller;
use LaraMod\Admin\Orders\Models\Orders;
use Illuminate\Http\Request;
use LaraMod\Admin\Orders\Models\OrdersItems;

class OrdersController extends Controller
{

    private $data = [];
    public function __construct()
    {
        config()->set('admincore.menu.orders.active', true);
    }

    public function index()
    {
        $this->data['items'] = Orders::paginate(20);
        return view('adminorders::list', $this->data);
    }

    public function getForm(Request $request)
    {
        $this->data['item'] = ($request->has('id') ? Orders::with(['items.product', 'history.product'])->find($request->get('id')) : new Orders());
        if($request->wantsJson()){
            return response()->json($this->data);
        }
        return view('adminorders::form', $this->data);
    }

    public function postForm(Request $request)
    {

        $order = $request->has('id') ? Orders::find($request->get('id')) : new Orders();
        try{
            $order->update($request->all());
            $order->save();
        }catch (\Exception $e){
            return redirect()->back()->withInput()->withErrors(['errors' => $e->getMessage()]);
        }

        return redirect()->route('admin.orders')->with('message', [
            'type' => 'success',
            'text' => 'Order saved.'
        ]);
    }

    public function delete(Request $request){
        if(!$request->has('id')){
            return redirect()->route('admin.orders')->with('message', [
                'type' => 'danger',
                'text' => 'No ID provided!'
            ]);
        }
        try {
            Orders::find($request->get('id'))->delete();
        }catch (\Exception $e){
            return redirect()->route('admin.orders')->with('message', [
                'type' => 'danger',
                'text' => $e->getMessage()
            ]);
        }

        return redirect()->route('admin.orders')->with('message', [
            'type' => 'success',
            'text' => 'Order moved to trash.'
        ]);
    }

    public function deleteItem(Request $request){
        if(!$request->has('id')) return abort(400, 'ID of item missing');
        try {
            $item = OrdersItems::find($request->get('id'));
            $order_id = $item->order_id;
            $item->delete();
        }catch (\Exception $e){
            return abort($e->getCode(), $e->getMessage());
        }

        return response()->json(['item' => Orders::with(['items.product', 'history.product'])->find($order_id)]);
    }

    public function restoreItem(Request $request){
        if(!$request->has('id')) return abort(400, 'ID of item missing');
        $item = OrdersItems::withTrashed()->find($request->get('id'));
        if(!$item) return abort(404, 'Item not found');
        try {
            $item->restore();
        }catch (\Exception $e){
            return abort($e->getCode(), $e->getMessage());
        }

        return response()->json(['item' => Orders::with(['items.product', 'history.product'])->find($item->order_id)]);
    }

    public function updateItem(Request $request){
        try{
            $item = $request->has('id') ? OrdersItems::find($request->get('id'))->update($request->all()) : OrdersItems::create($request->all());
        }catch (\Exception $e){
            return abort($e->getCode(), $e->getMessage());
        }
        return response()->json(['item' => Orders::with(['items.product', 'history.product'])->find($item->order_id)]);
    }

    public function getItems(Request $request){
        if($request->has('id')){
            return response()->json(OrdersItems::find($request->get('id')));
        }
        $data = new OrdersItems();
        if($request->has('order_id')){
            $data->where('order_id', $request->get('order_id'));
        }
        return response()->json($data->get());
    }

}