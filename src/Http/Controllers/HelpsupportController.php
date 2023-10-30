<?php

namespace Moeen\Helpsupport\Http\Controllers;

use App\Http\Controllers\Controller as ControllersController;
use CURLFile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Moeen\Helpsupport\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Alert;

class HelpsupportController extends ControllersController
{
    public function __construct()
    {
        $this->middleware('auth:coordinator');
        //$this->middleware('auth');
    }
    // to show the first page of system
    public function index()
    {

        return view('helpsupport::help');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    // to create a new  tickets
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

    // to store a newly created Tickets
    public function store(Request $request)
    {

        // Initialize cURL
        $url = config("helpsupport.base_url");
        $ch = curl_init("$url/api/submit_new_complain");
        curl_setopt($ch, CURLOPT_POST, true);


        // Create the request data
        if ($request->file("file1")) {
            //  $requestData = array_merge($request->all(), ['file1' => curl_file_create($request->file('file1')->getRealPath(), $request->file('file1')->getClientOriginalName())]);
            $originalName = $request->file('file1')->getClientOriginalName();
            $originalName = str_replace(' ', '_', $originalName); // Replace spaces with underscores
            $originalName = urlencode($originalName); // URL-encode the filename
            $file1 = new CURLFile(
                $request->file('file1')->getRealPath(),
                $request->file('file1')->getMimeType(),
                $originalName
            );
            $requestData = array_merge($request->all(), ['file1' => $file1]);
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


            $complains = json_decode($response);

            return redirect("ViewTicket/MyTickets");
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

    // to add a new reply for the specified ticket
    public function submit_response(Request $request)
    {


        // Initialize cURL
        $url = config("helpsupport.base_url");
        $ch = curl_init("$url/api/submit_response");
        curl_setopt($ch, CURLOPT_POST, true);


        // Create the request data
        if ($request->file("file1")) {
            // $requestData = array_merge($request->all(), ['respond_direction' => 'c2m', 'file1' => curl_file_create($request->file('file1')->getRealPath(), $request->file('file1')->getClientOriginalName())]);
            $originalName = $request->file('file1')->getClientOriginalName();
            $originalName = str_replace(' ', '_', $originalName); // Replace spaces with underscores
            $originalName = urlencode($originalName); // URL-encode the filename
            $file1 = new CURLFile(
                $request->file('file1')->getRealPath(),
                $request->file('file1')->getMimeType(),
                $originalName
            );
            $requestData = array_merge($request->all(), ['file1' => $file1, 'respond_direction' => 'c2m']);
        } else {
            $requestData = array_merge($request->all(), ['respond_direction' => 'c2m']);
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
            $client_id = config("helpsupport.client_id");
            $complain_id = request()['complain_id'];
            $url = config("helpsupport.base_url");
            curl_setopt($ch, CURLOPT_URL, "$url/api/list_response/$client_id/$complain_id");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Error: ' . curl_error($ch);
            }

            $complain = json_decode($response);

            return redirect()->route('showResponse', ['complain_id' => $complain_id]);


            curl_close($ch);
        } else {
            return back()->with('error', 'An error occurred while submitting your complaint. Please try again later.');
        }

        // Close cURL
        curl_close($ch);
    }

    //to show the details of the tickets
    public function show()
    {
        $ch = curl_init();
        $client_id = config("helpsupport.client_id");
        $url = config("helpsupport.base_url");
        $complain_id = request()->input('complain_id');
        curl_setopt($ch, CURLOPT_URL, "$url/api/list_response/$client_id/$complain_id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
            return; // Handle the cURL error here
        }

        curl_close($ch);

        $complain = json_decode($response);

        if ($complain === null) {
            return abort(404);
        }
        if (property_exists($complain, 'message') && $complain->message == 'Unauthorized action') {
            return abort(401);
        }
        return view('helpsupport::ViewResponse', compact("complain"));
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

    // to list the all tickets
    public function MyTickets()
    {


        $client_id = config("helpsupport.client_id");

        // dd( $client_id);
        $ch = curl_init();
        $url = config("helpsupport.base_url");

        curl_setopt($ch, CURLOPT_URL, "$url/api/list_complains/$client_id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }
        curl_close($ch);

        $complains = json_decode($response);

        return view('helpsupport::ViewTicket', compact("complains"));
    }

    // to check if the  ticket is there using complain id
    public function TicketTracking(Request $request)
    {
        $complain_id = $request->input('complain_id');
        $client_id = config("helpsupport.client_id");
        $ch = curl_init();
        $url = config("helpsupport.base_url");

        curl_setopt($ch, CURLOPT_URL, "$url/api/list_response/$client_id/$complain_id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        $complain = json_decode($response);

        if ($complain) {
            return response()->json(['complain_found' => true, 'complain_id' => $complain_id]);
        } else {
            return response()->json(['complain_found' => false]);
        }
    }



    // to ReOpen the tickets again
    public function updateTicketStatus($ticketId)
    {
        // Set cURL options
        $ch = curl_init();
        $url = config("helpsupport.base_url");

        // Construct the endpoint URL with the ticket ID
        $endpointUrl = "$url/api/update_ticket_status/$ticketId";

        curl_setopt($ch, CURLOPT_URL, $endpointUrl);
        curl_setopt($ch, CURLOPT_POST, true);

        // Define the data to be sent to the server
        $data = [
            'status' => 'Open', // Set the new status to "Open"
        ];

        // Set cURL options
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL session
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            // Handle cURL errors
            return "cURL Error: " . curl_error($ch);
        } else {
            // Assuming the response from the server indicates success
            // You can add more error handling as needed
            return back();
        }

        // Close cURL session
        curl_close($ch);
    }
}
