<?php

namespace App\Controllers\Api\Instructor;

use App\Controllers\BaseController;
use App\Models\Course;
use App\Traits\ApiResponses;
use App\Traits\Authorizable;
use App\Traits\CloudinaryTrait;
use App\Validation\AddNewCourse;
use App\Validation\UpdateCourse;
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

    public function updateCourse($courseId = null)
    {
        $db = db_connect();
        $db->transBegin();

        try {
            if (!$this->isAuthorize()) {
                return $this->respondWithError("You must be authorized to update a course");
            }

            if (!$courseId) {
                return $this->respondWithError("Course ID is required");
            }

            $existingCourse = $this->courseModel->where('id', $courseId)
                                              ->where('instructor_id', $this->user["id"])
                                              ->first();

            if (!$existingCourse) {
                return $this->respondWithError("Course not found or you don't have permission to update it");
            }

            $rules = new AddNewCourse(); 
            $validationRules = $rules->getRules();
            $validationMessages = $rules->getMessages();

            foreach ($validationRules as $field => $rule) {
                if (is_string($rule)) {
                    $validationRules[$field] = 'permit_empty|' . str_replace('required|', '', $rule);
                } elseif (is_array($rule) && isset($rule['rules'])) {
                    $validationRules[$field]['rules'] = 'permit_empty|' . str_replace('required|', '', $rule['rules']);
                }
            }

            $courseImage = $this->request->getFile("image_url");
            $courseVideo = $this->request->getFile("video_url");

            if (!$this->validate($validationRules, $validationMessages)) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ], 422);
            }

            $imageUpload = null;
            $videoUpload = null;

            // handle course image update
            if ($courseImage && $courseImage->isValid() && !$courseImage->hasMoved()) {
                $imageUpload = $this->updateFile($courseImage, $existingCourse['public_image_id'] ?? null, 'byway/courses/images');
                if (!$imageUpload) {
                    return $this->respondWithError("Upload Image failed. Please check file size (max 2MB) and format (JPG, JPEG, PNG)");
                }
            }

            // handle course video update
            if ($courseVideo && $courseVideo->isValid() && !$courseVideo->hasMoved()) {
                // Check video file size before upload
                $videoSize = $courseVideo->getSize();
                $maxVideoSize = 20 * 1024 * 1024; // 20MB

                if ($videoSize > $maxVideoSize) {
                    return $this->respondWithError("Video file too large. Maximum size allowed is 20MB");
                }

                $videoUpload = $this->updateFile($courseVideo, $existingCourse['public_video_id'] ?? null, 'byway/courses/videos');
                if (!$videoUpload) {
                    return $this->respondWithError("Upload Video failed. Please check file size (max 20MB) and format (MP4, MPEG, AVI, QuickTime). Check logs for more details.");
                }
            }

            $courseData = [];
            
            if ($this->request->getPost("title")) {
                $courseData["title"] = $this->request->getPost("title");
            }
            if ($this->request->getPost("description")) {
                $courseData["description"] = $this->request->getPost("description");
            }
            if ($this->request->getPost("price") !== null) {
                $courseData["price"] = $this->request->getPost("price");
            }
            if ($this->request->getPost("category_id")) {
                $courseData["category_id"] = $this->request->getPost("category_id");
            }
            if ($this->request->getPost("duration") !== null) {
                $courseData["duration"] = $this->request->getPost("duration");
            }

            if ($imageUpload) {
                $courseData["image_url"] = $imageUpload["url"];
                $courseData["public_image_id"] = $imageUpload["public_id"];
            }

            if ($videoUpload) {
                $courseData["video_url"] = $videoUpload["url"];
                $courseData["public_video_id"] = $videoUpload["public_id"];
            }

            if ($this->courseModel->update($courseId, $courseData)) {
                $course = $this->courseModel->find($courseId);

                $db->transCommit();
                return $this->respondWithSuccess($course, 'Course Updated Successfully', 200);
            } else {
                $db->transRollback();
                return $this->respondWithError('Failed to Update Course', 500);
            }

        } catch (\Cloudinary\Api\Exception\ApiError $e) {
            $db->transRollback();
            return $this->respondWithError("Cloudinary Error: " . $e->getMessage(), 500);
        } catch (\Throwable $th) {
            $db->transRollback();
            return $this->respondWithError($th->getMessage(), $th->getCode() ?: 500);
        }
    }

    public function deleteCourse($courseId = null)
    {
        try {
            if (!$this->isAuthorize()) {
                return $this->respondWithError("You must be authorized to delete a course");
            }

            if (!$courseId) {
                return $this->respondWithError("Course ID is required");
            }

            // Check if course exists and belongs to the instructor
            $existingCourse = $this->courseModel->where('id', $courseId)
                                              ->where('instructor_id', $this->user["id"])
                                              ->first();

            if (!$existingCourse) {
                return $this->respondWithError("Course not found or you don't have permission to delete it");
            }

            // Delete files from Cloudinary
            if (!empty($existingCourse['public_image_id'])) {
                $this->deleteFile($existingCourse['public_image_id']);
            }
            if (!empty($existingCourse['public_video_id'])) {
                $this->deleteFile($existingCourse['public_video_id']);
            }

            // Delete course from database
            if ($this->courseModel->delete($courseId)) {
                return $this->respondWithSuccess(null, 'Course deleted successfully', 200);
            } else {
                return $this->respondWithError('Failed to delete course', 500);
            }

        } catch (\Throwable $th) {
            return $this->respondWithError($th->getMessage(), $th->getCode() ?: 500);
        }
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
