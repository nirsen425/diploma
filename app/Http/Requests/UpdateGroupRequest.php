<?php

namespace App\Http\Requests;

use App\Group;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $groupId= $this->route('group');
        $group = Group::where('id', '=', $groupId)->first();
        $groupStoryId = $group->groupStories()->where('year_history', '=', $group->year)->first()->id;
        return [
            'direction' => ['sometimes', 'nullable', 'digits_between:1,2'],
            'students' => ['sometimes','nullable', 'mimes:csv,txt', 'encoding:utf-8'],
            'name' => ['required', Rule::unique('groups')->ignore($groupId),
                Rule::unique('group_stories')->ignore($groupStoryId)],
            'course' => ['sometimes', 'nullable', 'digits_between:1,4']
        ];
    }
}
