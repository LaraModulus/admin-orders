@extends('admincore::layouts.dashboard')

@section('content')
    <div id="page-wrapper" data-ng-app="App" data-ng-controller="ordersController">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Order #{{$item->id}}</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        @if(count($errors))
            <div class="row">
                <div class="col-xs-12">
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    <!-- /.row -->
        <form action="{{route('admin.orders.form', ['id' => $item->id])}}" method="post" role="form">
            <div class="row">
                <div class="col-md-9">
                    <div class="tab-content">
                            <div class="panel panel-default">

                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <address>
                                                <p>
                                                    <input type="text" name="names" id="names" class="form-control"
                                                           value="{{old('names', $item->names)}}" placeholder="Client names" title="Name">

                                                </p>
                                                <p>
                                                    <input type="text" name="phone" id="phone" class="form-control"
                                                           value="{{old('phone', $item->phone)}}" placeholder="Client phone" title="Phone">
                                                </p>
                                                <p>
                                                    <input type="text" name="email" id="email" class="form-control"
                                                           value="{{old('email', $item->email)}}" placeholder="Client email" title="Email">
                                                </p>

                                            </address>
                                        </div>
                                        <div class="col-md-4">
                                            <p>
                                                <input type="text" name="city" id="city" class="form-control"
                                                       value="{{old('city', $item->city)}}" placeholder="Delivery city" title="Delivery city">

                                            </p>
                                            <p>
                                                <input type="text" name="country" id="country" class="form-control"
                                                       value="{{old('country', $item->country)}}" placeholder="Delivery country" title="Delivery country">
                                            </p>
                                            <p>
                                                <textarea class="form-control" name="address" id="address" title="Delivery address" placeholder="Delivery address">{{old('address', $item->address)}}</textarea>

                                            </p>

                                        </div>
                                        <div class="col-md-4">
                                            <p>
                                                <select name="payment_method" id="payment_method" class="form-control" title="Payment method">
                                                    <option value="on_delivery" @if($item->payment_method=='on_delivery') selected @endif>On delivery</option>
                                                    <option value="bank" @if($item->payment_method=='bank') selected @endif>Bank payment</option>
                                                    <option value="card" @if($item->payment_method=='card') selected @endif>Card payment</option>
                                                </select>
                                            </p>
                                            <p>
                                                Date: {{$item->created_at->format('d.m.Y H:i')}}
                                            </p>
                                            <p data-ng-if="order.notes">
                                                Notes:<br/>
                                                {{nl2br($item->notes)}}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row" >
                                        <div class="col-xs-12">
                                            <button type="button" class="btn btn-primary btn-sm" data-ng-click="openItemModal()">Add item</button>
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th><i class="fa fa-cogs"></i></th>
                                                    <th>ID</th>
                                                    <th>Product</th>
                                                    <th>Price</th>
                                                    <th>QTY</th>
                                                    <th>TOTAL</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr data-ng-repeat="item in order.items">
                                                    <td><button type="button" class="btn btn-xs btn-danger" data-ng-click="deleteItem(item.id)"><i class="fa fa-trash"></i></button></td>
                                                    <td>@{{ item.product_id }}</td>
                                                    <td>@{{ item.product_name }}</td>
                                                    <td>@{{ item.price | currency }}</td>
                                                    <td>@{{ item.qty | number:3 }}</td>
                                                    <td>@{{ item.qty * item.price | currency}}</td>
                                                </tr>


                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th colspan="5" class="text-right">Discount</th>
                                                    <td>@{{ -0 | currency }}</td>
                                                </tr>
                                                <tr>
                                                    <th colspan="5" class="text-right">Delivery</th>
                                                    <td>@{{ 0 | currency }}</td>
                                                </tr>
                                                <tr>
                                                    <th colspan="5" class="text-right">Total</th>
                                                    <td data-ng-bind="orderTotal() | currency "></td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row" data-ng-if="order.history.length">
                                        <div class="col-xs-12">
                                            <label>Items History</label>
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th><i class="fa fa-cogs"></i></th>
                                                    <th>ID</th>
                                                    <th>Product</th>
                                                    <th>Price</th>
                                                    <th>QTY</th>
                                                    <th>TOTAL</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr data-ng-repeat="item in order.history">
                                                    <td><button type="button" class="btn btn-xs btn-success" data-ng-click="restoreItem(item.id)"><i class="fa fa-undo"></i></button></td>
                                                    <td>@{{ item.product_id }}</td>
                                                    <td>@{{ item.product_name }}</td>
                                                    <td>@{{ item.price | currency }}</td>
                                                    <td>@{{ item.qty | number:3 }}</td>
                                                    <td>@{{ item.qty * item.price | currency}}</td>
                                                </tr>


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <textarea class="form-control" name="admin_note" id="admin_note" title="Admin notes" placeholder="Admin notes (visible only from admins)">{{old('admin_note',$item->admin_note)}}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                    </div>


                </div>
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="new" @if($item->status=='new') selected @endif>New</option>
                                    <option value="in_progress" @if($item->status=='in_progress') selected @endif>In Progress</option>
                                    <option value="finished" @if($item->status=='finished') selected @endif>Finished</option>
                                    <option value="canceled" @if($item->status=='canceled') selected @endif>Canceled</option>
                                </select>
                            </div>
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="modal fade" id="itemModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add/update item</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="product_id">Product</label>
                            <select name="product_id" id="product_id" class="form-control" data-ng-model="item.product_id" data-ng-change="changeItemProduct()">

                                <option value="@{{ p.id }}" data-ng-repeat="p in products" data-ng-selected="p.id == item.product_id">@{{ p.title }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="product_name">Product name</label>
                            <input type="text" name="product_name" id="product_name" class="form-control" data-ng-model="item.product_name" title="">
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="product_price">Product price</label>
                                    <input type="text" name="product_price" id="product_price" class="form-control" data-ng-model="item.price" title="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="qty">Quantity</label>
                                    <input type="text" name="qty" id="qty" class="form-control" data-ng-model="item.qty" title="">
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="weight">Weight</label>
                            <input type="text" name="weight" id="weight" class="form-control" data-ng-model="item.weight" title="">
                        </div>
                        <div class="form-group">
                            <label for="selected_options">Selected options</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" data-ng-click="saveItem()">Save changes</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <script>

        app.controller('ordersController', function($scope, $http, SweetAlert, CSRF_TOKEN, $window) {
            $scope.order = {};

            $scope.orderTotal = function(){
                var total = 0;
                angular.forEach($scope.order.items, function(item, $index){
                    total+=item.price*item.qty;
                });
                return total;
            };

            $scope.reloadOrder = function(){
                $http.get(window.location.href)
                    .then(function(response){
                        $scope.order = response.data.item;
                    });
            };

            $scope.reloadOrder();



            $scope.deleteItem = function(item_id){
                $http.delete('{{route('admin.orders.items')}}', {
                    params: {
                        id: item_id
                    }
                }).then(function(response){
                   $scope.order = response.data.item;
                });
            };

            $scope.restoreItem = function(item_id){
                $http.get('{{route('admin.orders.items.restore')}}', {
                    params: {
                        id: item_id
                    }
                }).then(function(response){
                    $scope.order = response.data.item;
                })
            };

            $scope.saveItem = function(){
                $http.post('{{route('admin.orders.items')}}', $scope.item)
                    .then(function(response){
                        $scope.order = response.data.item;
                        $('#itemModal').modal('hide');
                    });
            };

            $scope.openItemModal = function(item_id){
                $scope.item = {};
                $scope.products = [];
                if(item_id!==undefined){
                    $http.get('{{route('admin.orders.items')}}', {
                        param: {
                            id: item_id
                        }
                    }).then(function(response){
                       $scope.item = response.data;

                    });
                }
                $http.get('{{route('admin.products.items')}}').then(function(response){
                    $scope.products = response.data.items.data;
                });
                $('#itemModal').modal('show');
            }

            $scope.changeItemProduct = function(){
                $scope.item = {};
                $http.get('{{route('admin.products.items.form')}}', {
                    params: {
                        id: $('#itemModal #product_id').val()
                    }
                }).then(function(response){
                    var product = response.data.item;
                    $scope.item = {
                        product_id: product.id,
                        product_name: product.title,
                        price: product.price_final,
                        qty: 1,
                        weight: product.weight,
                        order_id: $scope.order.id
                    }
                });
            }



        });
    </script>
@stop
@section('css')
    <script>
        var app = angular.module('App', ['ui.bootstrap', 'ngSanitize', 'oitozero.ngSweetAlert']);
        app.run(['$http', 'CSRF_TOKEN', function($http, CSRF_TOKEN) {
            $http.defaults.headers.common['X-Csrf-Token'] = CSRF_TOKEN;
        }]);
    </script>
@stop