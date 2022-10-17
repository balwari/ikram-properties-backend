<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Image;
use File;

class PropertyController extends Controller
{
    public function get(Request $request){

        $validator = Validator::make($request->all(), [
            'search_query' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->errors()->all(), 'data' => []], 422);
        }

        $per_page = 10;

        try {
            $properties_query = Property::where('id','!=',null);

            if (isset($request->search_query) && $request->search_query != null) {
                $keyword = $request->search_query;
        
                $properties_query->where(function ($query) use($keyword) {
                    $query->where('county', 'like', '%' . $keyword . '%')
                            ->orWhere('country', 'like', '%' . $keyword . '%')
                            ->orWhere('town', 'like', '%' . $keyword . '%')
                            ->orWhere('description', 'like', '%' . $keyword . '%')
                            ->orWhere('type', 'like', '%' . $keyword . '%');
                });
            }

            $properties = $properties_query->orderBy('id', 'desc')->paginate($per_page);

            $response_message = [];
            $response_message[] = "Properties fetched Successfully";

            return response()->json(['error' => false, 'message' => $response_message, 'data' => $properties], 200);    
        } catch (Exception $e) {
            $response_message = [];
            $response_message[] = $e->getMessage();
            return response()->json(['error' => true, 'message' => $response_message, 'data' => []], 422);
        }
    }

    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'county' => 'required|string',
            'country' => 'required|string',
            'town' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:10048',
            'address' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'num_bedrooms' => 'required|integer',
            'num_bathrooms' => 'required|integer',
            'price' => 'required|string',
            'property_type_id' => 'required|integer',
            'type' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->errors()->all(), 'data' => []], 422);
        }


        try {
            $property = new Property();

            $property->county = $request->county;
            $property->country = $request->country;
            $property->town = $request->town;
            $property->description = $request->description;
            $property->address = $request->address;

            $image = $request->file('image');
            $image_name = time().'.'.$image->extension();
         
            $filePath = public_path('/thumbnails');

            if (!File::isDirectory($filePath)) {
                File::makeDirectory($filePath, 0777, true, true);
            }

            $img = Image::make($image->path());

            $img->resize(110, 110, function ($const) {
                $const->aspectRatio();
            })->save($filePath.'/'.$image_name);
       
            $full_image_name = time().'-full.'.$image->extension();

            $filePath = public_path('/images');

            if (!File::isDirectory($filePath)) {
                File::makeDirectory($filePath, 0777, true, true);
            }

            $image->move($filePath, $full_image_name);
    
            $property->image_full = 'images/' . $full_image_name;
            $property->image_thumbnail = 'thumbnails/' . $image_name;
            $property->latitude = $request->latitude;
            $property->longitude = $request->longitude;
            $property->num_bedrooms = $request->num_bedrooms;
            $property->num_bathrooms = $request->num_bathrooms;
            $property->price = $request->price;
            $property->property_type_id = $request->property_type_id;
            $property->type = $request->type;

            $random_string = substr(str_shuffle(str_repeat('123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(1,10))), 1, 20);

            $properties = Property::where('uuid',$random_string)->get();

            while(count($properties) > 0){
                $random_string = substr(str_shuffle(str_repeat('123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(1,10))), 1, 20);
                $properties = Property::where('uuid',$random_string)->get();
            }
            $property->uuid = $random_string;
            $property->save();

            $response_message = [];
            $response_message[] = "Property Created Successfully";

            return response()->json(['error' => false, 'message' => $response_message, 'data' => $properties], 200);    
        } catch (Exception $e) {
            $response_message = [];
            $response_message[] = $e->getMessage();
            return response()->json(['error' => true, 'message' => $response_message, 'data' => []], 422);
        }
    }


    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:properties',
            'county' => 'required|string',
            'country' => 'required|string',
            'town' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:10048',
            'address' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'num_bedrooms' => 'required|integer',
            'num_bathrooms' => 'required|integer',
            'price' => 'required|string',
            'property_type_id' => 'required|integer',
            'type' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->errors()->all(), 'data' => []], 422);
        }

        try {

            $property = Property::where('id',$request->id)->first();
            $property->county = $request->county;
            $property->country = $request->country;
            $property->town = $request->town;
            $property->description = $request->description;
            $property->address = $request->address;

            $image = $request->file('image');
            $image_name = time().'.'.$image->extension();
         
            $filePath = public_path('/thumbnails');

            if (!File::isDirectory($filePath)) {
                File::makeDirectory($filePath, 0777, true, true);
            }

            $img = Image::make($image->path());

            $img->resize(110, 110, function ($const) {
                $const->aspectRatio();
            })->save($filePath.'/'.$image_name);
       
            $full_image_name = time().'-full.'.$image->extension();

            $filePath = public_path('/images');

            if (!File::isDirectory($filePath)) {
                File::makeDirectory($filePath, 0777, true, true);
            }

            $image->move($filePath, $full_image_name);
    
            $property->image_full = 'http://localhost:8009/images/' . $full_image_name;
            $property->image_thumbnail = 'http://localhost:8009/thumbnails/' . $image_name;
            $property->latitude = $request->latitude;
            $property->longitude = $request->longitude;
            $property->num_bedrooms = $request->num_bedrooms;
            $property->num_bathrooms = $request->num_bathrooms;
            $property->price = $request->price;
            $property->property_type_id = $request->property_type_id;
            $property->type = $request->type;

            $property->save();

            $response_message = [];
            $response_message[] = "Property Updated Successfully";

            return response()->json(['error' => false, 'message' => $response_message, 'data' => []], 200);    
        } catch (Exception $e) {
            $response_message = [];
            $response_message[] = $e->getMessage();
            return response()->json(['error' => true, 'message' => $response_message, 'data' => []], 422);
        }
    }

    public function delete(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:properties'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->errors()->all(), 'data' => []], 422);
        }

        try {
            $property = Property::find($request->id);

            $property->delete();
            $response_message = [];
            $response_message[] = "Property Deleted Successfully";

            return response()->json(['error' => false, 'message' => $response_message, 'data' => []], 200);    
        } catch (Exception $e) {
            $response_message = [];
            $response_message[] = $e->getMessage();
            return response()->json(['error' => true, 'message' => $response_message, 'data' => []], 422);
        }
    }
}
