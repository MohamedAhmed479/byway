<?php

namespace App\Controllers\Api\Instructor;

use App\Controllers\BaseController;
use App\Models\Course;
use App\Models\Lesson;
use App\Traits\ApiResponses;
use App\Traits\Authorizable;
use App\Traits\CloudinaryTrait;
use App\Validation\AddNewCourse;
use App\Validation\AddNewLesson;
use App\Validation\UpdateLesson;
use CodeIgniter\HTTP\ResponseInterface;

class LessonsManagementController extends BaseController
{
    use Authorizable, ApiResponses, CloudinaryTrait;

    protected $user;
    protected $courseModel;
    protected $lessonModel;

    public function __construct()
    {
        $this->courseModel = new Course();
        $this->lessonModel = new Lesson();

    }

    public function isAuthorize(): bool
    {
        $this->user = $this->request->user;
        if($this->user && $this->isInstructor($this->user)) {
            return true;
        }

        return false;
    }

    public function getLessons()
    {
        if (!$this->isAuthorize()) {
            return $this->respondWithError("You must be authorized to view your lessons");
        }

        $perPage = $this->request->getVar('per_page') ?? 10;
        $page    = $this->request->getVar('page') ?? 1;
        $courseId = $this->request->getVar('courseId');

        $paginator = $this->lessonModel
            ->where('course_id', $courseId)
            ->paginate($perPage, 'default', $page);

        $pager = $this->lessonModel->pager;

        return $this->respondWithPagination($paginator, $pager);
    }

    public function getLessonDetails(string $courseId, string $lessonId)
    {
        if (!$this->isAuthorize()) {
            return $this->respondWithError("You must be authorized to view your lessons");
        }

        $course = $this->courseModel->where("instructor_id", $this->user["id"])->find($courseId)                              ;
        if (!$course) {
            return $this->respondWithError("Course not found or you don't have permission to view this lesson");
        }

        $lesson = $this->lessonModel->where('course_id', $courseId)->find($lessonId);
        if (!$lesson) {
            return $this->respondWithError("Lesson not found");
        }

        return $this->respondWithSuccess($lesson);
    }

    public function addLesson(string $courseId)
    {
        $db = db_connect();
        $db->transBegin();

        try {
            if (!$this->isAuthorize()) {
                return $this->respondWithError("You must be authorized to add lesson");
            }

            $course = $this->courseModel->where("instructor_id", $this->user["id"])->find($courseId)                              ;
            if (!$course) {
                return $this->respondWithError("Course not found or you don't have permission to add a lesson");
            }

            $rules = new AddNewLesson();
            $validationRules = $rules->getRules();
            $validationMessages = $rules->getMessages();

            $lessonVideo = $this->request->getFile("video_url");
            if (!$lessonVideo || !$lessonVideo->isValid() || $lessonVideo->hasMoved()) {
                $validationRules['video_url']['rules'] = 'required|' . $validationRules['video_url']['rules'];
            }

            if (!$this->validate($validationRules, $validationMessages)) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ], 422);
            }

            $videoUpload = null;
            if ($lessonVideo && $lessonVideo->isValid() && !$lessonVideo->hasMoved()) {
                $videoSize = $lessonVideo->getSize();
                $maxVideoSize = 20 * 1024 * 1024;

                if ($videoSize > $maxVideoSize) {
                    return $this->respondWithError("Video file too large. Maximum size allowed is 20MB");
                }

                $videoUpload = $this->uploadFile($lessonVideo, 'byway/courses/lessons');
                if (!$videoUpload) {
                    return $this->respondWithError("Upload Video failed. Please check file size (max 20MB) and format (MP4, MPEG, AVI, QuickTime). Check logs for more details.");
                }

            }

            $lessonData  = [
                "course_id" => $courseId,
                "title" => $this->request->getPost("title"),
                "video_url" => $videoUpload ? $videoUpload["url"] : null,
                "public_video_id" => $videoUpload ? $videoUpload["public_id"] : null,
                "duration" => $this->request->getPost("duration"),
                "order" => $this->request->getPost("order"),
            ];

            if ($this->lessonModel->save($lessonData)) {
                $lesson = $this->lessonModel->find($this->lessonModel->getInsertID());

                $db->transCommit();
                return $this->respondWithSuccess($lesson, 'Lesson Added Successfully', 201);
            } else {
                $db->transRollback();
                return $this->respondWithError('Failed to Add Lesson', 500);
            }
        } catch (\Cloudinary\Api\Exception\ApiError $e) {
            return $this->respondWithError("Cloudinary Error: " . $e->getMessage(), 500);
        } catch (\Throwable $th) {
            $db->transRollback();
            return $this->respondWithError($th->getMessage(), $th->getCode() ?: 500);
        }
    }


    public function updateLesson(string $courseId, string $lessonId)
    {
        $db = db_connect();
        $db->transBegin();

        try {
            if (!$this->isAuthorize()) {
                return $this->respondWithError("You must be authorized to update this lesson");
            }

            $course = $this->courseModel->where("instructor_id", $this->user["id"])->find($courseId);
            if (!$course) {
                return $this->respondWithError("Course not found or you don't have permission to update a lesson");
            }

            $existingLesson = $this->lessonModel
                ->where('course_id', $courseId)
                ->find($lessonId);
            if (!$existingLesson) {
                return $this->respondWithError("Lesson not found");
            }
            
            $oldPublicVideoId = $existingLesson['public_video_id'] ?? null;

            $rules = new UpdateLesson();
            $validationRules = $rules->getRules();
            $validationMessages = $rules->getMessages();

            foreach ($validationRules as $field => $rule) {
                if (is_string($rule)) {
                    $validationRules[$field] = 'permit_empty|' . str_replace('required|', '', $rule);
                } elseif (is_array($rule) && isset($rule['rules'])) {
                    $validationRules[$field]['rules'] = 'permit_empty|' . str_replace('required|', '', $rule['rules']);
                }
            }

            $lessonVideo = $this->request->getFile('video_url');
            if (!$lessonVideo || !$lessonVideo->isValid() || $lessonVideo->hasMoved()) {
                if (isset($validationRules['video_url']['rules'])) {
                    $validationRules['video_url']['rules'] = str_replace('required|', '', $validationRules['video_url']['rules']);
                }
            }

            if (!$this->validate($validationRules, $validationMessages)) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ], 422);
            }

            $videoUpload = null;
            if ($lessonVideo && $lessonVideo->isValid() && !$lessonVideo->hasMoved()) {
                $videoSize = $lessonVideo->getSize();
                $maxVideoSize = 20 * 1024 * 1024;

                if ($videoSize > $maxVideoSize) {
                    return $this->respondWithError("Video file too large. Maximum size allowed is 20MB");
                }

                $videoUpload = $this->uploadFile($lessonVideo, 'byway/courses/lessons');
                if (!$videoUpload) {
                    return $this->respondWithError("Upload Video failed. Please check file size (max 20MB) and format (MP4, MPEG, AVI, QuickTime). Check logs for more details.");
                }

            }

            $updateData = [];
            if ($this->request->getPost('title') !== null) {
                $updateData['title'] = $this->request->getPost('title');
            }
            if ($this->request->getPost('duration') !== null) {
                $updateData['duration'] = $this->request->getPost('duration');
            }
            if ($this->request->getPost('order') !== null) {
                $updateData['order'] = $this->request->getPost('order');
            }
            if ($videoUpload) {
                $updateData['video_url'] = $videoUpload['url'];
                $updateData['public_video_id'] = $videoUpload['public_id'];
            }

            if (empty($updateData)) {
                return $this->respondWithError('No fields to update', 422);
            }

            if ($this->lessonModel->update($lessonId, $updateData)) {
                if ($videoUpload && !empty($oldPublicVideoId)) {
                    $this->deleteFile($oldPublicVideoId);
                }
                $lesson = $this->lessonModel->find($lessonId);
                $db->transCommit();
                return $this->respondWithSuccess($lesson, 'Lesson Updated Successfully', 200);
            } else {
                $db->transRollback();
                return $this->respondWithError('Failed to Update Lesson', 500);
            }
        } catch (\Cloudinary\Api\Exception\ApiError $e) {
            return $this->respondWithError("Cloudinary Error: " . $e->getMessage(), 500);
        } catch (\Throwable $th) {
            $db->transRollback();
            return $this->respondWithError($th->getMessage(), $th->getCode() ?: 500);
        }
    }

    public function deleteLesson(string $courseId, string $lessonId)
    {
        try {
            if (!$this->isAuthorize()) {
                return $this->respondWithError("You must be authorized to delete this lesson");
            }

            $course = $this->courseModel->where("instructor_id", $this->user["id"])->find($courseId);
            if (!$course) {
                return $this->respondWithError("Course not found or you don't have permission to delete a lesson");
            }

            $lesson = $this->lessonModel->where('course_id', $courseId)->find($lessonId);
            if (!$lesson) {
                return $this->respondWithError("Lesson not found");
            }

            if (!empty($lesson['public_video_id'])) {
                $this->deleteFile($lesson['public_video_id']);
            }

            if ($this->lessonModel->delete($lessonId)) {
                return $this->respondWithSuccess(null, 'Lesson deleted successfully', 200);
            }

            return $this->respondWithError('Failed to delete lesson', 500);
        } catch (\Throwable $th) {
            return $this->respondWithError($th->getMessage(), $th->getCode() ?: 500);
        }
    }

}




