<?php

namespace App\Http\Requests;

class TaskUpdateRequest extends TaskStoreRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'due_date' => 'required|date|after_or_equal:today',
        ]);
    }
}