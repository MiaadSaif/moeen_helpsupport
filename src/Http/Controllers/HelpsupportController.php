<?php

namespace Moeen\Helpsupport\Http\Controllers;

use App\Http\Controllers\Controller as ControllersController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Moeen\Helpsupport\Http\Controllers\Controller;

class HelpsupportController extends ControllersController
{
    public function __construct()
    {
       // $this->middleware('auth:coordinator');
        $this->middleware('auth');
    }

    public function index()
    {

        return view('helpsupport::help');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $type = request()->type;

        return view('helpsupport::NewTicket', compact("type"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Initialize cURL
        $url = config("helpsupport.base_url");
        $ch = curl_init("$url/api/submit_new_complain");
        curl_setopt($ch, CURLOPT_POST, true);


        // Create the request data
        if ($request->file("file1")) {
            $requestData = array_merge($request->all(), ['file1' => curl_file_create($request->file('file1')->getRealPath(), $request->file('file1')->getClientOriginalName())]);
        } else {
            $requestData = $request->all();
        }

        // Set the request data and content type
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the request
        $response = curl_exec($ch);

        // Check if response is successful
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200 && json_decode($response)->message == 'successful') {
            $client_id = json_decode($response)->complains->client_id;
            $complain_id = json_decode($response)->complains->id;

            $ch = curl_init();
            $url = config("helpsupport.base_url");
            $client_id = config("helpsupport.client_id");
            curl_setopt($ch, CURLOPT_URL, " $url/api/list_complains/$client_id");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error: ' . curl_error($ch);
            }
            curl_close($ch);
            //dd(json_decode($response));
            $complains = json_decode($response);

            return redirect("ViewTicket/MyTickets");
            //dd($complains);

            //   return view('maf.help_support.ViewTicket', compact("complains"));
        } else {
            return back()->with('error', 'An error occurred while submitting your complaint. Please try again later.');
        }

        // Close cURL
        curl_close($ch);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function submit_response(Request $request)
    {


        // Initialize cURL
        $url = config("helpsupport.base_url");
        $ch = curl_init("$url/api/submit_response");
        curl_setopt($ch, CURLOPT_POST, true);


        // Create the request data
        if ($request->file("file1")) {
            $requestData = array_merge($request->all(), ['respond_direction' => 'c2m', 'file1' => curl_file_create($request->file('file1')->getRealPath(), $request->file('file1')->getClientOriginalName())]);
        } else {
            $requestData = array_merge($request->all(), ['respond_direction' => 'c2m']);
        }

        // Set the request data and content type
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the request
        $response = curl_exec($ch);
        //dd(json_decode($response));
        // Check if response is successful
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200 && json_decode($response)->message == 'successful') {
            $client_id = json_decode($response)->complains->client_id;
            $complain_id = json_decode($response)->complains->id;

            $ch = curl_init();
            $client_id = config("helpsupport.client_id");
            $complain_id = request()['complain_id'];
            $url = config("helpsupport.base_url");
            curl_setopt($ch, CURLOPT_URL, "$url/api/list_response/$client_id/$complain_id");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Error: ' . curl_error($ch);
            }
            //dd($response);
            // dd(json_decode($response));
            $complain = json_decode($response);

            return view('helpsupport::ViewResponse', compact("complain"));

            curl_close($ch);
        } else {
            return back()->with('error', 'An error occurred while submitting your complaint. Please try again later.');
        }

        // Close cURL
        curl_close($ch);
    }
    public function show()
    {


        $ch = curl_init();
        $client_id = config("helpsupport.client_id");
        $url = config("helpsupport.base_url");
        $complain_id = request()['complain_id'];
        curl_setopt($ch, CURLOPT_URL, "$url/api/list_response/$client_id/$complain_id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        //dd(json_decode($response));
        $complain = json_decode($response);
        // dd($complains, request()['complain_id']);
        // dd($complain);
        curl_close($ch);
        return view('helpsupport::ViewResponse', compact("complain"));
        // return view('maf.help_support.ViewResponse');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function MyTickets()
    {


        $client_id = config("helpsupport.client_id");

        // dd( $client_id);
        $ch = curl_init();
        $url = config("helpsupport.base_url");

        curl_setopt($ch, CURLOPT_URL, "$url/api/list_complains/$client_id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        // Log::info($response);
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }
        curl_close($ch);
        //dd(json_decode($response));
        $complains = json_decode($response);
        //dd($complains);
        return view('helpsupport::ViewTicket', compact("complains"));
    }
    public function TicketTracking(Request $request)
    {


        $complain_id = $request->input('complain_id');

        $client_id = config("helpsupport.client_id");
        $ch = curl_init();
        $url = config("helpsupport.base_url");
        //$complain_id = request()['complain_id'];
        curl_setopt($ch, CURLOPT_URL, "$url/api/list_response/$client_id/$complain_id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        try {
            // cURL request code
            $response = curl_exec($ch);
        } catch (Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        // dd(json_decode($response));
        $complain = json_decode($response);

       // dd($complain);
        if (!$complain || !$complain->complain) {
            return redirect()->back()->with("error", "Ticket Number Not Found");
        }
        return view('helpsupport::ViewResponse', compact("complain"));

        curl_close($ch);
    }



}
