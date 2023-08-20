<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Mentor;
use App\Models\MyCourse;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::query();

        $q = $request->query('q');
        $status = $request->query('status');

        $courses->when('q', function($query) use ($q)
        {
            return $query->whereRaw("name LIKE '%" . strtolower($q) . "%'");
        });

        $courses->when('status', function($query) use ($status)
        {
            return $query->whereRaw("status LIKE '%" . strtolower($status) . "%'");
        });

        return response()->json([
            'status' => 'success',
            'data' => $courses->paginate(10)
        ]);
    }

    public function show($id)
    {
        $course = Course::with('chapters.lessons', 'mentor', 'images')->find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found!'
            ], 404);
        }

        $reviews = Review::where('course_id', '=', $id)->get()->toArray();
        if (count($reviews) > 0) {
            $userIds = array_column($reviews, 'user_id');
            $users = getUserByIds($userIds);
            // var_dump($users);
            if ($users['status'] == 'error') {
                $reviews = [];
            } else {
                foreach($reviews as $key => $review)
                {
                    $userIndex = array_search($review['user_id'], array_column($users['message'], 'id'));
                    $reviews[$key]['users'] = $users['message'][$userIndex];
                }
            }
        }
        $totalStudents = MyCourse::where('course_id', '=', $id)->count();
        $totalVideos = Chapter::where('course_id', '=', $id)->withCount('lessons')->get()->toArray();
        $finalTotalVideos = array_sum(array_column($totalVideos, 'lessons_count'));

        $course['reviews'] = $reviews;
        $course['total_students'] = $totalStudents;
        $course['total_videos'] = $finalTotalVideos;
        
        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'certificate' => 'required|boolean',
            'thumbnaii' => 'string|url',
            'type' => 'required|in:FREE,PREMIUM',
            'status' => 'required|in:DRAFT,PUBLISHED',
            'price' => 'integer',
            'level' => 'required|in:ALL,BEGINNER,INTERMEDIATE,ADVANCE',
            'mentor_id' => 'required|integer',
            'description' => 'string'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $mentorId = $request->input('mentor_id');
        $mentor = Mentor::find($mentorId);

        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mentor not found!'
            ], 404);
        }

        $course = Course::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'string',
            'certificate' => 'boolean',
            'thumbnaii' => 'string|url',
            'type' => 'in:FREE,PREMIUM',
            'status' => 'in:DRAFT,PUBLISHED',
            'price' => 'integer',
            'level' => 'in:ALL,BEGINNER,INTERMEDIATE,ADVANCE',
            'mentor_id' => 'integer',
            'description' => 'string'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }
        
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found!'
            ], 404);
        }

        $mentorId = $request->input('mentor_id');
        
        if ($mentorId) {
            $mentor = Mentor::find($mentorId);

            if (!$mentor) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mentor not found!'
                ], 404);
            }
        }

        $course->fill($data);
        $course->save();

        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found!'
            ]);
        }
        
        $course->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Course deleted!'
        ]);
    }
}
