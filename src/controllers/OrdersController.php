<?php
namespace LaraMod\AdminOrders\Controllers;

use App\Http\Controllers\Controller;
use LaraMod\AdminOrders\Models\Orders;
use Illuminate\Http\Request;

class OrdersController extends Controller
{

    private $data = [];
    public function __construct()
    {
        config()->set('admincore.menu.products.active', true);
    }

    public function index()
    {
        $this->data['items'] = Orders::paginate(20);
        return view('adminorders::orders.list', $this->data);
    }

    public function getForm(Request $request)
    {
        $this->data['item'] = ($request->has('id') ? Orders::find($request->get('id')) : new Orders());
        if($request->wantsJson()){
            return response()->json($this->data);
        }
        return view('adminorders::orders.form', $this->data);
    }

    public function postForm(Request $request)
    {

        $order = $request->has('id') ? Orders::find($request->get('id')) : new Orders();
        try{
            foreach(config('app.locales', [config('app.fallback_locale', 'en')]) as $locale){
                $order->{'title_'.$locale} = $request->get('title_'.$locale);
                $order->{'sub_title_'.$locale} = $request->get('sub_title_'.$locale);
                $order->{'description_'.$locale} = $request->get('description_'.$locale);
                $order->{'meta_title_'.$locale} = $request->get('meta_title_'.$locale);
                $order->{'meta_description_'.$locale} = $request->get('meta_description_'.$locale);
                $order->{'meta_keywords_'.$locale} = $request->get('meta_keywords_'.$locale);
            }
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


}