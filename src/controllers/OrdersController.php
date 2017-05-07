<?php

namespace LaraMod\Admin\Orders\Controllers;

use App\Http\Controllers\Controller;
use LaraMod\Admin\Orders\Models\Orders;
use Illuminate\Http\Request;
use LaraMod\Admin\Orders\Models\OrdersItems;
use Yajra\Datatables\Datatables;

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
        $this->data['item'] = ($request->has('id') ? Orders::with([
            'items.product',
            'history.product',
        ])->find($request->get('id')) : new Orders());
        if ($request->wantsJson()) {
            return response()->json($this->data);
        }

        return view('adminorders::form', $this->data);
    }

    public function postForm(Request $request)
    {

        $order = Orders::firstOrCreate(['id' => $request->get('id')]);
        try {
            $order->update(array_filter($request->only($order->getFillable()), function($key) use ($request, $order){
                return in_array($key, array_keys($request->all())) || @$order->getCasts()[$key]=='boolean';
            }, ARRAY_FILTER_USE_KEY));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['errors' => $e->getMessage()]);
        }

        return redirect()->route('admin.orders')->with('message', [
            'type' => 'success',
            'text' => 'Order saved.',
        ]);
    }

    public function delete(Request $request)
    {
        if (!$request->has('id')) {
            return redirect()->route('admin.orders')->with('message', [
                'type' => 'danger',
                'text' => 'No ID provided!',
            ]);
        }
        try {
            Orders::find($request->get('id'))->delete();
        } catch (\Exception $e) {
            return redirect()->route('admin.orders')->with('message', [
                'type' => 'danger',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.orders')->with('message', [
            'type' => 'success',
            'text' => 'Order moved to trash.',
        ]);
    }

    public function deleteItem(Request $request)
    {
        if (!$request->has('id')) {
            return abort(400, 'ID of item missing');
        }
        try {
            $item = OrdersItems::find($request->get('id'));
            $order_id = $item->order_id;
            $item->delete();
        } catch (\Exception $e) {
            return abort($e->getCode(), $e->getMessage());
        }

        return response()->json(['item' => Orders::with(['items.product', 'history.product'])->find($order_id)]);
    }

    public function restoreItem(Request $request)
    {
        if (!$request->has('id')) {
            return abort(400, 'ID of item missing');
        }
        $item = OrdersItems::withTrashed()->find($request->get('id'));
        if (!$item) {
            return abort(404, 'Item not found');
        }
        try {
            $item->restore();
        } catch (\Exception $e) {
            return abort($e->getCode(), $e->getMessage());
        }

        return response()->json(['item' => Orders::with(['items.product', 'history.product'])->find($item->order_id)]);
    }

    public function updateItem(Request $request)
    {
        try {
            $item = OrdersItems::firstOrCreate(['id' => $request->get('id')]);
            $item->update(array_filter($request->only($item->getFillable()), function($key) use ($request, $item){
                return in_array($key, array_keys($request->all())) || @$item->getCasts()[$key]=='boolean';
            }, ARRAY_FILTER_USE_KEY));
        } catch (\Exception $e) {
            return abort($e->getCode(), $e->getMessage());
        }

        return response()->json(['item' => Orders::with(['items.product', 'history.product'])->find($item->order_id)]);
    }

    public function getItems(Request $request)
    {
        if ($request->has('id')) {
            return response()->json(OrdersItems::find($request->get('id')));
        }
        $data = new OrdersItems();
        if ($request->has('order_id')) {
            $data->where('order_id', $request->get('order_id'));
        }

        return response()->json($data->get());
    }

    public function dataTable()
    {
        $items = Orders::select(['id', 'names', 'status', 'created_at', 'seen']);

        return DataTables::of($items)
            ->addColumn('action', function ($item) {
                return '<button type="button" class="btn btn-primary btn-xs" title="Preview" data-item="' . $item->id . '"><i class="fa fa-eye"></i></button>'
                    . '<a href="' . route('admin.orders.form',
                        ['id' => $item->id]) . '" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>'
                    . '<a href="' . route('admin.orders.delete',
                        ['id' => $item->id]) . '" class="btn btn-danger btn-xs require-confirm"><i class="fa fa-trash"></i></a>';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('d.m.Y H:i');
            })
            ->editColumn('status', function ($item) {
                switch ($item->status) {
                    case 'in_progress':
                        return 'In progress';
                        break;
                    case 'new':
                        return 'New';
                        break;
                    case 'finished':
                        return 'Finished';
                        break;
                    case 'canceled':
                        return 'Canceled';
                    default:
                        return $item->status;
                        break;
                }
            })
            ->orderColumn('status $1', 'created_at $1')
            ->make('true');
    }

    public function ordersWidget(){
        config()->set('admincore.menu.orders.active', false);
        return view('adminorders::widget', [
            'orders_count' => Orders::where('status', 'new')->count()
        ])->render();
    }

}