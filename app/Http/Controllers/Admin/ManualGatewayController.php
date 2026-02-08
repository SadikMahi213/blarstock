<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\Gateway;
use App\Models\GatewayCurrency;

class ManualGatewayController extends Controller
{
    function index() {
        $pageTitle = 'Manual Gateways';
        $methods   = Gateway::manual()->with('singleCurrency')->latest()->get();
        
        return view('admin.gateways.manual.index', compact('pageTitle', 'methods'));
    }

    function new() {
        $pageTitle   = 'Add Manual Gateway';
        $method      = '';
        $form        = '';
        $formHeading = 'User Information';
        $actionRoute = route('admin.gateway.manual.store');
        $backRoute   = route('admin.gateway.manual.index');

        return view('admin.gateways.basicMethod', compact('pageTitle', 'method', 'form', 'formHeading', 'actionRoute', 'backRoute'));
    }

    function store($id = 0) {
        $formProcessor = new FormProcessor();
        $this->validation(request(), $formProcessor);

        if ($id) {
            $method          = Gateway::manual()->where('id', $id)->firstOrFail();
            $gatewayCurrency = $method->singleCurrency;
            $generate        = $formProcessor->generate('manual_deposit', true, 'id', $method->form_id);
            $message         = ' manual gateway update success';

        } else {
            $method          = new Gateway();
            $gatewayCurrency = new GatewayCurrency();
            $generate        = $formProcessor->generate('manual_deposit');
            $lastMethod      = Gateway::manual()->orderBy('id', 'desc')->first();
            $methodCode      = 1000;
    
            if ($lastMethod) {
                $methodCode = $lastMethod->code + 1;
            }

            $method->code = $methodCode;
            $gatewayCurrency->method_code = $methodCode;
            $message = ' manual gateway add success';
        }

        $method->form_id              = $generate?->id ?? 0;
        $method->name                 = request('name');
        $method->alias                = strtolower(trim(str_replace(' ','_',request('name'))));
        $method->status               = ManageStatus::ACTIVE;
        $method->gateway_parameters   = json_encode([]);
        $method->supported_currencies = [];
        $method->crypto               = ManageStatus::INACTIVE;
        $method->guideline            = request('guideline');
        $method->save();

        $gatewayCurrency->name           = request('name');
        $gatewayCurrency->gateway_alias  = strtolower(trim(str_replace(' ','_',request('name'))));
        $gatewayCurrency->currency       = request('currency');
        $gatewayCurrency->symbol         = '';
        $gatewayCurrency->min_amount     = request('min_amount');
        $gatewayCurrency->max_amount     = request('max_amount');
        $gatewayCurrency->fixed_charge   = request('fixed_charge');
        $gatewayCurrency->percent_charge = request('percent_charge');
        $gatewayCurrency->rate           = request('rate');
        $gatewayCurrency->save();

        $toast[] = ['success', $method->name . $message];
        return back()->withToasts($toast);
    }

    function edit($id) {
        $method         = Gateway::manual()->with('singleCurrency')->where('id', $id)->firstOrFail();
        $pageTitle      = $method->name . ' - Update';
        $methodRelation = $method->singleCurrency; 
        $form           = $method->form;
        $formHeading    = 'User Information';
        $actionRoute    = route('admin.gateway.manual.store', $method->id);
        $backRoute      = route('admin.gateway.manual.index');

        return view('admin.gateways.basicMethod', compact('pageTitle', 'method', 'methodRelation', 'form', 'formHeading', 'actionRoute', 'backRoute'));
    }

    function status($id) {
        return Gateway::changeStatus($id);
    }

    private function validation($request, $formProcessor) {
        $validation = [
            'name'           => 'required|max:40',
            'rate'           => 'required|numeric|gt:0',
            'currency'       => 'required|max:40',
            'min_amount'     => 'required|numeric|gt:0',
            'max_amount'     => 'required|numeric|gt:min_amount',
            'fixed_charge'   => 'required|numeric|gte:0',
            'percent_charge' => 'required|numeric|gte:0|regex:/^\d+(\.\d{1,2})?$/'
        ];

        $generatorValidation = $formProcessor->generatorValidation();
        $validation = array_merge($validation, $generatorValidation['rules']);

        $request->validate($validation, $generatorValidation['messages']);
    }
}
