<?php

namespace App\Http\Requests;

use App\Constants\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'userId'       => 'required|exists:users,id',
            'title'        => 'required|string|max:255|min:10',
            'description'  =>'required|string|max:1000|min:50',
            'due_date'     => 'required|date',
            'status'       => ['required', Rule::in([TaskStatus::PENDING, TaskStatus::COMPLETE])]
        ];
    }
}
