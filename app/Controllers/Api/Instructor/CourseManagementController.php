<?php

namespace App\Controllers\Api\Instructor;

use App\Controllers\BaseController;
use App\Models\Course;
use App\Traits\ApiResponses;
use App\Traits\Authorizable;
use App\Traits\CloudinaryTrait;
use App\Validation\AddNewCourse;
use CodeIgniter\HTTP\ResponseInterface;

class CourseManagementController extends BaseController
{
    use Authorizable, ApiResponses, CloudinaryTrait;

    protected $user;
    protected $courseModel;

    public function __construct()
    {
        $this->courseModel = new Course();
    }

    public function isAuthorize(): bool
    {
        $this->user = $this->request->user;
        if($this->user && $this->isInstructor($this->user)) {
            return true;
        }

        return false;
    }

    public function addCourse()
    {
        $db = db_connect();
        $db->transBegin();

        try {
            if (!$this->isAuthorize()) {
                return $this->respondWithError("You must be authorized to add a new course");
            }

            $rules = new AddNewCourse();
            $validationRules = $rules->getRules();
            $validationMessages = $rules->getMessages();

            $courseImage = $this->request->getFile("image_url");
            if (!$courseImage || !$courseImage->isValid() || $courseImage->hasMoved()) {
                $validationRules['image_url']['rules'] = 'required|' . $validationRules['image_url']['rules'];
            }

            $courseVideo = $this->request->getFile("video_url");
            if (!$courseVideo || !$courseVideo->isValid() || $courseVideo->hasMoved()) {
                $validationRules['video_url']['rules'] = 'required|' . $validationRules['video_url']['rules'];
            }

            if (!$this->validate($validationRules, $validationMessages)) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ], 422);
            }

            $imageUpload = null;
            $videoUpload = null;

            // handle course image
            if ($courseImage && $courseImage->isValid() && !$courseImage->hasMoved()) {
                $imageUpload = $this->uploadFile($courseImage, 'byway/courses/images');
                if (!$imageUpload) {
                    return $this->respondWithError("Upload Image failed. Please check file size (max 2MB) and format (JPG, JPEG, PNG)");
                }
            }

            // handle course video
            if ($courseVideo && $courseVideo->isValid() && !$courseVideo->hasMoved()) {
                // Check video file size before upload
                $videoSize = $courseVideo->getSize();
                $maxVideoSize = 20 * 1024 * 1024; // 20MB
                
                if ($videoSize > $maxVideoSize) {
                    return $this->respondWithError("Video file too large. Maximum size allowed is 20MB");
                }
                
                $videoUpload = $this->uploadFile($courseVideo, 'byway/courses/videos');
                if (!$videoUpload) {
                    return $this->respondWithError("Upload Video failed. Please check file size (max 20MB) and format (MP4, MPEG, AVI, QuickTime). Check logs for more details.");
                }
            }

            $courseData = [
                "title" => $this->request->getPost("title"),
                "description" => $this->request->getPost("description"),
                "price" => $this->request->getPost("price"),

                "image_url" => $imageUpload ? $imageUpload["url"] : null,
                "public_image_id" => $imageUpload ? $imageUpload["public_id"] : null,

                "video_url" => $videoUpload ? $videoUpload["url"] : null,
                "public_video_id" => $videoUpload ? $videoUpload["public_id"] : null,

                "instructor_id" => $this->user["id"],
                "category_id" => $this->request->getPost("category_id"),
                "status" => "pending_approval",
                "duration" => $this->request->getPost("duration"),
            ];

            if ($this->courseModel->save($courseData)) {
                $course = $this->courseModel->find($this->courseModel->getInsertID());

                $db->transCommit();
                return $this->respondWithSuccess($course, 'Course Created Successfully', 201);
            } else {
                $db->transRollback();
                return $this->respondWithError('Failed to Create Course', 500);
            }

        } catch (\Cloudinary\Api\Exception\ApiError $e) {
            return $this->respondWithError("Cloudinary Error: " . $e->getMessage(), 500);
        } catch (\Throwable $th) {
            $db->transRollback();
            return $this->respondWithError($th->getMessage(), $th->getCode() ?: 500);
        }
    }

    public function updateCourse()
    {
        //
    }

    public function deleteCourse()
    {
        //
    }

    public function getCourses()
    {
        //
    }

    public function getCourseDetails(string $id)
    {
        //
    }
}
