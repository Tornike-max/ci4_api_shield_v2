<?php

namespace App\Controllers\Api;

use App\Models\ProjectModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class ProjectController extends ResourceController
{
    public function addProject()
    {
        $rules = [
            'name' => 'required',
            'budget' => 'required'
        ];

        if (!$this->validate($rules)) {
            $response = [
                'status' => false,
                'message' => $this->validator->getErrors(),
                'data' => []
            ];
        } else {
            $validatedData = $this->validator->getValidated();
            $user_id = auth()->id();

            $validatedData['user_id'] = $user_id;

            $projectObject = model(ProjectModel::class);
            if ($projectObject->insert($validatedData)) {
                $response = [
                    'status' => true,
                    'message' => 'Project created',
                    'data' => []
                ];
            }
        }

        return $this->respondCreated($response);
    }

    public function listProjects()
    {
        $user_id = auth()->id();

        $projectObject = model(ProjectModel::class);

        $projects = $projectObject->where('user_id', $user_id)->findAll();

        if (!isset($projects)) {
            $response = [
                'status' => false,
                'message' => 'No projects Available',
                'data' => []
            ];
        } else {
            $response = [
                'status' => true,
                'message' => 'success',
                'data' => $projects
            ];
        }

        return $this->respondCreated($response);
    }

    public function deleteProject($project_id)
    {
        if (empty($project_id)) {
            $response = [
                'status' => false,
                'message' => 'Project id is required',
                'data' => []
            ];
        }

        $user_id = auth()->id();

        $projectObject = model(ProjectModel::class);

        $project = $projectObject->where([
            'id' => $project_id,
            'user_id' => $user_id
        ])->first();

        if ($project) {
            $projectObject->delete($project_id);
            $response = [
                'status' => true,
                'message' => 'Project deleted',
                'data' => []
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Project not found',
                'data' => []
            ];
        }
        return $this->respondCreated($response);
    }
}
