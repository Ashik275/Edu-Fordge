<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Mail\NotificationMail;
use App\Models\Classes;
use App\Models\Meeting;
use App\Models\Student;
use App\Models\Subjects;
use App\Models\Teacher;
use App\Models\ZoomAccessToken;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ZoomMeetingController extends Controller
{
    //
    public function create()
    {
        $teacher = Auth::guard('teacher')->user();
        $subjectIds = explode(',', $teacher->subject_id);
        $classIds = explode(',', $teacher->class_id);

        $meetings = Meeting::where('teacher_id', $teacher->id)->latest()->get();



        // Retrieve subjects based on the IDs associated with the teacher
        $subjects = Subjects::whereIn('id', $subjectIds)->latest()->get();
        $classes = Classes::whereIn('id', $classIds)->latest()->get();
        return view('teacher.meeting.createmeeting', [
            'classes' => $classes,
            'subjects' => $subjects,
            'meetings' => $meetings,
        ]);
    }
    // public function createMeeting(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'class_id' => 'required',
    //         'subject_id' => 'required',
    //     ]);
    //     if ($validator->passes()) {
    //         $teacher = Auth::guard('teacher')->user();

    //         $zoom_access  = ZoomAccessToken::latest()->get();
    //         if ($zoom_access->count() == '0') {
    //             $this->insertAccessToken();
    //         } else {
    //             $zoom_access_for_update  = ZoomAccessToken::latest()->first();
    //             $expire_time = $zoom_access_for_update->expires_at;
    //             if (Carbon::now()->greaterThan($expire_time)) {
    //                 $this->updateAccessToken($zoom_access_for_update->id);
    //             }
    //         }

    //         $zoom_access_new  = ZoomAccessToken::latest()->first();
    //         $accessToken = $zoom_access_new->access_token;


    //         $client = new Client();

    //         // Create Zoom Meeting
    //         $response = $client->post('https://api.zoom.us/v2/users/me/meetings', [
    //             'json' => [
    //                 'topic' => $request->input('topic'),
    //                 'type' => 2,
    //                 'start_time' => now()->addHour()->format('Y-m-d\TH:i:s'),
    //                 'schedule_for' => "arahmankarzon@gmail.com",
    //                 'settings' => [
    //                     'join_before_host' => true,
    //                     'host_video' => false,
    //                     'participant_video' => true,
    //                     'mute_upon_entry' => false,
    //                 ]
    //                 // Add other necessary data
    //             ],
    //             'headers' => [
    //                 'Authorization' => 'Bearer ' . $accessToken,
    //             ]
    //         ]);
    //         // dd($response);
    //         $meetingLink = json_decode($response->getBody(), true)['join_url'];
    //         $zoom_meeting_id = json_decode($response->getBody(), true)['id'];

    //         $meeting = new Meeting();
    //         $meeting->class_id = $request->class_id;
    //         $meeting->subject_id = $request->subject_id;
    //         $meeting->teacher_id = $teacher->id;
    //         $meeting->link = $meetingLink;
    //         $meeting->zoom_meeting_id = $zoom_meeting_id;
    //         $meeting->save();

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Category Updated successfully'
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => false,
    //             'errors' => $validator->errors()
    //         ]);
    //     }
    // }

    public function createMeeting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required',
            'subject_id' => 'required',
        ]);

        if ($validator->passes()) {
            $teacher = Auth::guard('teacher')->user();

            $zoomAccessToken = ZoomAccessToken::latest()->first();

            if (!$zoomAccessToken || Carbon::now()->greaterThan($zoomAccessToken->expires_at)) {
                $this->insertAccessToken();
                $zoomAccessToken = ZoomAccessToken::latest()->first();
            }

            $accessToken = $zoomAccessToken->access_token;

            $client = new Client();
            // Get the start time from the request
            $startTimeInput = $request->start_time_input;

            // Convert the start time to a Carbon instance
            $startTime = Carbon::parse($startTimeInput);

            $formattedStartTime = $startTime->format('Y-m-d\TH:i:s');

            // Create Zoom Meeting with "Join Before Host" enabled https://api.zoom.us/v2/metrics/meetings/72067743955/participants?type=past
            $response = $client->post('https://api.zoom.us/v2/users/me/meetings', [
                'json' => [
                    'topic' => $request->input('topic'),
                    'type' => 2,
                    'start_time' => $formattedStartTime,
                    // 'start_time' => now()->addHour()->format('Y-m-d\TH:i:s'),
                    'settings' => [
                        'join_before_host' => false,
                        'host_video' => true,
                        'participant_video' => true,
                        'mute_upon_entry' => false,
                    ]
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);
            $meetingLink = $responseData['join_url'];
            $zoomMeetingId = $responseData['id'];

            // Save meeting details to the database
            $meeting = new Meeting();
            $meeting->class_id = $request->class_id;
            $meeting->subject_id = $request->subject_id;
            $meeting->meeting_time = $request->start_time_input;
            $meeting->teacher_id = $teacher->id;
            $meeting->link = $meetingLink;
            $meeting->zoom_meeting_id = $zoomMeetingId;
            $meeting->save();

            $this->sendMeetingCreatedEmail($teacher->id, $request->class_id, $request->subject_id, $request->start_time_input, $meetingLink);
            return response()->json([
                'status' => true,
                'message' => 'Meeting created successfully.',
                'meeting_link' => $meetingLink,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    private function sendMeetingCreatedEmail($teacher, $classId, $subject, $meeting_time, $meetingLink)
    {

        $teacher = Teacher::find($teacher);
        $sub_name = Subjects::find($subject);
        $class = Classes::find($classId);

        $students = Student::where('class_id', $classId)->get();

        foreach ($students as $student) {
            $mailData = [
                'title' => 'Mail From ' . $teacher->name,
                'class' => $class->class_name,
                'subject' =>  $sub_name->sub_name,
                'meeting_time' => $meeting_time,
                'meeting_link' => $meetingLink,
            ];
            Mail::to($student->email)->send(new NotificationMail($mailData));
            
        }
        // dd("Email Sent Successfully");
    }

    public function deleteMeeting($id)
    {
        // Find the meeting by its ID
        $meeting = Meeting::findOrFail($id);

        // Delete the meeting from the Zoom server using the Zoom API
        $client = new \GuzzleHttp\Client();

        $zoom_access  = ZoomAccessToken::latest()->get();
        if ($zoom_access->count() == '0') {
            $this->insertAccessToken();
        } else {
            $zoom_access_for_update  = ZoomAccessToken::latest()->first();
            $expire_time = $zoom_access_for_update->expires_at;
            if (Carbon::now()->greaterThan($expire_time)) {
                $this->updateAccessToken($zoom_access_for_update->id);
            }
        }

        $zoom_access_new  = ZoomAccessToken::latest()->first();
        $accessToken = $zoom_access_new->access_token;
        try {
            $response = $client->delete("https://api.zoom.us/v2/meetings/{$meeting->zoom_meeting_id}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);

            // Check if the meeting was successfully deleted from Zoom server
            if ($response->getStatusCode() === 204) {
                // Delete the meeting from your database
                $meeting->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Meeting deleted successfully',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to delete meeting from Zoom server',
                ], 500);
            }
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the deletion process
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete meeting: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function insertAccessToken()
    {
        $clientId = env('ZOOM_CLIENT_ID');
        $clientSecret = env('ZOOM_CLIENT_SECRET');

        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://zoom.us/oauth/token', [
            'form_params' => [
                'grant_type' => 'account_credentials',
                'account_id' => '_ENaUg-7SKaD-86NRTQDyA',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);
        // return $responseData;

        // Update the access token in environment variables or configuration storage
        if (isset($responseData['access_token'])) {
            $zoomToken = new ZoomAccessToken();
            $zoomToken->access_token = $responseData['access_token'];

            $zoomToken->save();
            // Retrieve the latest updated_at timestamp from the zoom_access_tokens table
            $latestUpdatedTimestamp = ZoomAccessToken::latest('updated_at')->value('updated_at');

            // Calculate expiry time 1 hour after the latest updated_at timestamp
            $expiryTimestamp = $latestUpdatedTimestamp->addHour(); // Add 1 hour
            // Update the expires_at field after saving
            $zoomToken->expires_at = $expiryTimestamp;
            $zoomToken->save(); // Update the model
            return response()->json([
                'status' => true,
                'message' => 'Access Token Added successfully'
            ]);
        }
    }
    public function updateAccessToken($id)
    {
        $clientId = env('ZOOM_CLIENT_ID');
        $clientSecret = env('ZOOM_CLIENT_SECRET');

        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://zoom.us/oauth/token', [
            'form_params' => [
                'grant_type' => 'account_credentials',
                'account_id' => '_ENaUg-7SKaD-86NRTQDyA',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);
        // return $responseData;

        // Update the access token in environment variables or configuration storage
        if (isset($responseData['access_token'])) {
            $zoomAccessToken = ZoomAccessToken::find($id);
            if ($zoomAccessToken) {
                $zoomAccessToken->access_token = $responseData['access_token'];
                $zoomAccessToken->save();
                $latestUpdatedTimestamp = ZoomAccessToken::latest('updated_at')->value('updated_at');

                // Calculate expiry time 1 hour after the latest updated_at timestamp
                $expiryTimestamp = $latestUpdatedTimestamp->addHour(); // Add 1 hour
                // Update the expires_at field after saving
                $zoomAccessToken->expires_at = $expiryTimestamp;
                $zoomAccessToken->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Access Token Updated successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Zoom Access Token not found'
                ], 404);
            }
        }
    }
    public function startMeeting($id)
    {
        $meeting = Meeting::findOrFail($id);
        $meeting->is_started = true;
        $meeting->save();
        return redirect()->away($meeting->link);
    }
    // public function startMeeting($id)
    // {
    //     $meeting = Meeting::findOrFail($id);

    //     // Delete the meeting from the Zoom server using the Zoom API
    //     $client = new \GuzzleHttp\Client();

    //     $zoom_access  = ZoomAccessToken::latest()->get();
    //     if ($zoom_access->count() == '0') {
    //         $this->insertAccessToken();
    //     } else {
    //         $zoom_access_for_update  = ZoomAccessToken::latest()->first();
    //         $expire_time = $zoom_access_for_update->expires_at;
    //         if (Carbon::now()->greaterThan($expire_time)) {
    //             $this->updateAccessToken($zoom_access_for_update->id);
    //         }
    //     }

    //     $zoom_access_new  = ZoomAccessToken::latest()->first();
    //     $accessToken = $zoom_access_new->access_token;

    //     $response = $client->patch("https://api.zoom.us/v2/meetings/{$meeting->zoom_meeting_id}", [
    //         'json' => [
    //             'settings' => [
    //                 'schedule_for_email' => 'arahmankarzon@gmail.com',
    //             ],
    //         ],
    //         'headers' => [
    //             'Authorization' => 'Bearer ' . $accessToken,
    //         ],
    //     ]);
    //     $responseData = json_decode($response->getBody(), true);
    //     return $responseData;
    //     if ($response->getStatusCode() == 204) {
    //         // Update local meeting status
    //         $meeting->is_started = true;
    //         $meeting->save();

    //         // Redirect to the Zoom meeting link
    //         return redirect()->away($meeting->link);
    //     } else {
    //         // Handle error when updating meeting settings
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Failed to update meeting settings.',
    //         ], $response->getStatusCode());
    //     }
    // }
}
