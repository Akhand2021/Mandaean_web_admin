<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;

class ContainerController extends Controller
{
    public function countries()
    {
        $data = Country::select('id','name','code')->get();
        return response([
            'status'=>true,
            'message'=>'Countries data has been fetched.',
            'data'=>$data
        ],201);
    }

    public function countriesInsert()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://restcountries.com/v2/all',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $country = json_decode($response,true);

        Country::truncate();
        foreach($country as $data)
        {
            Country::insert([
                'name'=>$data['name'],
                'code'=>$data['callingCodes'][0],
                'sortname'=>$data['alpha2Code'],
                'nationality'=>$data['demonym'],
                'created_at'=> now(),
                'updated_at'=>now()
            ]);
        }

        return 'country list has been updated';
    }
}
