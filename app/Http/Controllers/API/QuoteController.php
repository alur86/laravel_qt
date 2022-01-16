<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use App\Models\Quote;
use App\Http\Resources\Quote as QuoteResource;



   
class QuoteController extends BaseController
{

    public function index()
    {
        $quotes = Quote::all();
        return $this->sendResponse(QuoteResource::collection($quotes), 'Quotes listing');
    }

  
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'quote' => 'required|string',
            'source' => 'required|string|max:255'
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }

        $quote = Quote::create($input);

        return $this->sendResponse(new QuoteResource($quote), 'Post created.');
    }

   
    public function show($id)
    {
        $quote = Quote::find($id);

        if (is_null($quote)) {

            return $this->sendError('Quote does not find now');
        }

        return $this->sendResponse(new QuoteResource($quote), 'Quote now found');
    }
    

    public function update(Request $request, Quote $quote)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'quote' => 'required|string',
            'source' => 'required|string|max:255'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }

        $quote->quote = $input['quote'];
        $quote->source = $input['source'];
        $quote->save();
        
        return $this->sendResponse(new QuoteResource($quote), 'Quote was updated.');
    }



   
    public function destroy(Quote $quote)
    {
        $quote->delete();
        return $this->sendResponse([], 'Quote was deleted ok');
    }
}