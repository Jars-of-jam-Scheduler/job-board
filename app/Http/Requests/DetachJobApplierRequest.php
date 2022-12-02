<?php

namespace App\Http\Requests;

use App\Models\Job;

use Illuminate\Foundation\Http\FormRequest;

class DetachJobApplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return $this->user()->can('detach-job', $this->route()->parameter('job'));
    }
}
