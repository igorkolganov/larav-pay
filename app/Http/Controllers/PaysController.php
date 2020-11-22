<?php

namespace App\Http\Controllers;

use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as Validate;
use Illuminate\Support\Facades\Log;
use App\Logging\ChangePan;
use App\Logging\Formatter;
use MarvinLabs\Luhn\Algorithm\LuhnAlgorithm;


class PaysController extends Controller
{
    public function store(Request $request){

        $messages = [
            'pan.required' => (Formatter::formatOutput(ChangePan::changeNumber($request->pan), 'pan.required')),
            'pan.digits' => (Formatter::formatOutput(ChangePan::changeNumber($request->pan), 'pan.digits')),
            'pan.luhn' => (Formatter::formatOutput(ChangePan::changeNumber($request->pan), 'pan.luhn')),
            'cvc.required' => (Formatter::formatOutput(ChangePan::changeNumber($request->pan), 'cvc.required')),
            'cvc.digits' => (Formatter::formatOutput(ChangePan::changeNumber($request->pan), 'cvc.digits')),
            'cardholder.required' => (Formatter::formatOutput(ChangePan::changeNumber($request->pan), 'cardholder.required')),
            'cardholder.regex' => (Formatter::formatOutput(ChangePan::changeNumber($request->pan), 'cardholder.digits')),
            'expire.required' => (Formatter::formatOutput(ChangePan::changeNumber($request->pan), 'expire.required')),
            'expire.regex' => (Formatter::formatOutput(ChangePan::changeNumber($request->pan), 'expire.digits'))
        ];

        $validator = Validate::make($request->all(), [
            'pan'=> 'required|digits:16|luhn',
            'cvc'=> 'required|digits:3',
            'cardholder'=> 'required|regex:/^[a-zA-Z]+ [a-zA-Z]+$/',
            'expire'=> 'required|regex:/^[0-9]{2}\/[0-9]{2}$/'
        ], $messages);


        if($validator->fails()){
            Log::error($validator->errors()->first());
            Log::channel('errorPayCard')->error($validator->errors()->first());
            return $validator->errors()->first();
        }

        return $request;
    }
}
