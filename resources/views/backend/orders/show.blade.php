@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-18 mb-0">{{ translate('Order Details') }}</h1>
        </div>
        <div class="card-header">
            <div class="flex-grow-1 row">
                <div class="col-md mb-3">
                    <div>
                        <div class="fs-15 fw-600 mb-2">{{ translate('Customer info') }}</div>
                        <div><span class="opacity-80 mr-2 ml-0">{{ translate('Name') }}:</span> {{ $order->user->name ?? '' }}</div>
                        <div><span class="opacity-80 mr-2 ml-0">{{ translate('Email') }}:</span> {{ $order->user->email ?? '' }}</div>
                        <div><span class="opacity-80 mr-2 ml-0">{{ translate('Phone') }}:</span> {{ $order->user->phone ?? '' }}</div>
                    </div>
                </div>
                <div class="col-md-3 ml-auto mr-0 mb-3">
                    <label>{{translate('Payment Status')}}</label>
                    <select class="form-control aiz-selectpicker" id="update_payment_status" data-minimum-results-for-search="Infinity" data-selected="{{ $order->payment_status }}">
                        <option value="paid">{{translate('Paid')}}</option>
                        <option value="unpaid">{{translate('Unpaid')}}</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label>{{translate('Delivery Status')}}</label>
                    <select class="form-control aiz-selectpicker" id="update_delivery_status" data-minimum-results-for-search="Infinity" data-selected="{{ $order->delivery_status }}">
                        <option value="confirmed">{{translate('Confirmed')}}</option>
                        <option value="processed">{{translate('Processed')}}</option>
                        <option value="shipped">{{translate('Shipped')}}</option>
                        <option value="delivered">{{translate('Delivered')}}</option>
                        <option value="cancelled">{{translate('Cancel')}}</option>
                    </select>
                </div>
            </div>
		</div>
        <div class="card-header">
            <div class="flex-grow-1 row align-items-start">
                <div class="col-md-auto w-md-250px">
                    @php
                        $shipping_address = json_decode($order->shipping_address);
                    @endphp
                    <h5 class="fs-14 mb-3">{{ translate('Shipping address') }}</h5>
                    <address class="">
                        {{ $shipping_address->phone }}<br>
                        {{ $shipping_address->address }}<br>
                        {{ $shipping_address->city }}, {{ $shipping_address->postal_code }}<br>
                        {{ $shipping_address->state }}, {{ $shipping_address->country }}
                    </address>
                </div>
                <div class="col-md-auto w-md-250px">
                    @php
                        $billing_address = json_decode($order->billing_address);
                    @endphp
                    <h5 class="fs-14 mb-3">{{ translate('Billing address') }}</h5>
                    <address class="">
                        {{ $billing_address->phone }}<br>
                        {{ $billing_address->address }}<br>
                        {{ $billing_address->city }}, {{ $billing_address->postal_code }}<br>
                        {{ $billing_address->state }}, {{ $billing_address->country }}
                    </address>
                </div>
                <div class="col-md-4 col-xl-3 ml-auto mr-0">
                <table class="table table-borderless table-sm">
                    <tbody>
                        <tr>
                            <td class="">{{translate('Order code')}}</td>
                            <td class="text-right text-info fw-700">{{ $order->combined_order->code }}</td>
                        </tr>
                        <tr>
                            <td class="">{{translate('Order Date')}}</td>
                            <td class="text-right fw-700">{{ $order->created_at->format('d.m.Y') }}</td>
                        </tr>
                        <tr>
                            <td class="">{{translate('Delivery type')}}</td>
                            <td class="text-right fw-700">
                                {{ ucfirst(str_replace('_', ' ', $order->delivery_type)) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="">{{translate('Payment method')}}</td>
                            <td class="text-right fw-700">{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
		</div>

    	<div class="card-body">
            <table class="aiz-table table-bordered">
                <thead>
                    <tr class="">
                        <th class="text-center" width="5%" data-breakpoints="lg">#</th>
                        <th width="40%">{{translate('Product')}}</th>
                        <th class="text-center" data-breakpoints="lg">{{translate('Qty')}}</th>
                        <th class="text-center" data-breakpoints="lg">{{translate('Unit Price')}}</th>
                        <th class="text-center" data-breakpoints="lg">{{translate('Unit Tax')}}</th>
                        <th class="text-center" data-breakpoints="lg">{{translate('Total')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderDetails as $key => $orderDetail)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>
                                @if ($orderDetail->product != null)
                                <div class="media">
                                    <img src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}" class="size-60px mr-3">
                                    <div class="media-body">
                                        <h4 class="fs-14 fw-400">{{ $orderDetail->product->name }}</h4>
                                        @if($orderDetail->variation)
                                        <div>
                                            @foreach ($orderDetail->variation->combinations as $combination)
                                            <span class="mr-2">
                                                <span class="opacity-50">{{ $combination->attribute->name }}</span>:
                                                {{ $combination->attribute_value->name }}
                                            </span>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @else
                                    <strong>{{ translate('Product Unavailable') }}</strong>
                                @endif
                            </td>
                            <td class="text-center">{{ $orderDetail->quantity }}</td>
                            <td class="text-center">{{ format_price($orderDetail->price) }}</td>
                            <td class="text-center">{{ format_price($orderDetail->tax) }}</td>
                            <td class="text-center">{{ format_price($orderDetail->total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    		<div class="row">
                <div class="col-xl-4 col-md-6 ml-auto mr-0">
                    <table class="table">
                        <tbody>
                            @php
                                $totalTax = 0;
                                foreach($order->orderDetails as $item){
                                    $totalTax += $item->tax*$item->quantity;
                                }
                            @endphp
                            <tr>
                                <td><strong class="">{{translate('Sub Total')}} :</strong></td>
                                <td>
                                    {{ format_price($order->orderDetails->sum('total') - $totalTax) }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong class="">{{translate('Tax')}} :</strong></td>
                                <td>{{ format_price($totalTax) }}</td>
                            </tr>
                            <tr>
                                <td><strong class=""> {{translate('Shipping')}} :</strong></td>
                                <td>{{ format_price($order->shipping_cost) }}</td>
                            </tr>
                            <tr>
                                <td><strong class=""> {{translate('Coupon discount')}} :</strong></td>
                                <td>{{ format_price($order->coupon_discount) }}</td>
                            </tr>
                            <tr>
                                <td><strong class="">{{translate('TOTAL')}} :</strong></td>
                                <td class=" h4">
                                    {{ format_price($order->grand_total) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
    		</div>
    	</div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#update_delivery_status').on('change', function(){
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('orders.update_delivery_status') }}', {_token:'{{ @csrf_token() }}',order_id:order_id,status:status}, function(data){
                AIZ.plugins.notify('success', '{{ translate('Delivery status has been updated') }}');
            });
        });

        $('#update_payment_status').on('change', function(){
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('orders.update_payment_status') }}', {_token:'{{ @csrf_token() }}',order_id:order_id,status:status}, function(data){
                AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
            });
        });
    </script>
@endsection
