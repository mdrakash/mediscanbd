<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnalysisResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'status' => $this->status,
            'language' => $this->language,
            'created_at' => $this->created_at,
            'upload' => new UploadResource($this->whenLoaded('upload')),
        ];

        // Prescription specific fields
        if ($this->upload && $this->upload->type === 'prescription') {
            $data = array_merge($data, [
                'medicines' => $this->medicines,
                'tests_advised' => $this->tests_advised,
                'diagnosis' => $this->when($this->resource->tests_advised, $this->resource->tests_advised),
                'general_advice' => $this->when($this->resource->advice, $this->resource->advice),
                'next_steps' => $this->next_steps,
                'lifestyle_tips' => $this->lifestyle_tips,
            ]);
        }

        // Test report specific fields
        if ($this->upload && $this->upload->type === 'test_report') {
            $data = array_merge($data, [
                'test_summary' => $this->test_summary,
                'parameters' => $this->parameters,
                'abnormal_findings' => $this->abnormal_findings,
                'report_type' => $this->report_type,
                'overall_status' => $this->when($this->resource->parameters, $this->getOverallStatus()),
                'advice' => $this->advice,
                'foods_to_eat' => $this->when($this->resource->raw_response, $this->getFoodsToEat()),
                'foods_to_avoid' => $this->when($this->resource->raw_response, $this->getFoodsToAvoid()),
                'when_to_see_doctor' => $this->when($this->resource->raw_response, $this->getWhenToSeeDoctor()),
                'next_steps' => $this->next_steps,
                'lifestyle_tips' => $this->lifestyle_tips,
            ]);
        }

        $data['disclaimer'] = $this->when($this->resource->raw_response, $this->getDisclaimer());

        return $data;
    }

    private function getOverallStatus(): ?string
    {
        if (! $this->resource->parameters) {
            return null;
        }

        $statuses = array_column($this->resource->parameters, 'status');
        if (in_array('critical', $statuses)) {
            return 'severely_abnormal';
        }
        if (in_array('high', $statuses) || in_array('low', $statuses)) {
            return 'moderately_abnormal';
        }

        return 'normal';
    }

    private function getFoodsToEat(): ?array
    {
        $raw = json_decode($this->resource->raw_response, true);

        return $raw['foods_to_eat'] ?? null;
    }

    private function getFoodsToAvoid(): ?array
    {
        $raw = json_decode($this->resource->raw_response, true);

        return $raw['foods_to_avoid'] ?? null;
    }

    private function getWhenToSeeDoctor(): ?string
    {
        $raw = json_decode($this->resource->raw_response, true);

        return $raw['when_to_see_doctor'] ?? null;
    }

    private function getDisclaimer(): ?string
    {
        $raw = json_decode($this->resource->raw_response, true);

        return $raw['disclaimer'] ?? 'এটি AI বিশ্লেষণ। সঠিক চিকিৎসার জন্য অবশ্যই রেজিস্টার্ড চিকিৎসকের পরামর্শ নিন।';
    }
}
