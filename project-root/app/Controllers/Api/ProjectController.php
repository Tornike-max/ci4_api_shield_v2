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
    }

    public function deleteProject($project_id)
    {
    }
}
