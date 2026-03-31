<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnalyzeRequest;
use App\Http\Resources\AnalysisResource;
use App\Models\Analysis;
use App\Models\Upload;
use App\Services\FileStorageService;
use App\Services\OpenAIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class AnalysisController extends Controller
{
    public function __construct(
        private OpenAIService $openAI,
        private FileStorageService $storage
    ) {}

    // POST /api/analyze
    public function store(AnalyzeRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();

            // 1. File store করো (FileStorageService দিয়ে)
            $storedFile = $this->storage->storeUpload(
                $request->file('file'),
                $user->id,
                $request->type
            );

            // 2. Upload model create করো (status: processing)
            $upload = Upload::create([
                'user_id' => $user->id,
                'type' => $request->type,
                'original_filename' => $storedFile['original_name'],
                'storage_path' => $storedFile['path'],
                'mime_type' => $storedFile['mime'],
                'file_size' => $storedFile['size'],
                'language' => $request->language,
            ]);

            // 3. Analysis model create করো (status: processing)
            $analysis = Analysis::create([
                'upload_id' => $upload->id,
                'user_id' => $user->id,
                'status' => 'processing',
                'language' => $request->language,
                'raw_response' => null,
            ]);

            // 4. OpenAI call করো
            $fullPath = $this->storage->getFullPath($storedFile['path']);
            $response = $this->openAI->analyze($fullPath, $request->type, $request->language);

            // 5. Response দিয়ে Analysis update করো (status: completed)
            $analysis->update([
                'status' => 'completed',
                'medicines' => $response['medicines'] ?? null,
                'test_summary' => $response['summary'] ?? null,
                'parameters' => $response['parameters'] ?? null,
                'abnormal_findings' => $response['abnormal_findings'] ?? null,
                'advice' => $response['recommended_actions'] ?? $response['general_advice'] ?? null,
                'next_steps' => $response['next_steps'] ?? null,
                'lifestyle_tips' => $response['lifestyle_tips'] ?? null,
                'report_type' => $response['report_type'] ?? null,
                'tests_advised' => $response['tests_advised'] ?? null,
                'raw_response' => json_encode($response),
            ]);

            DB::commit();

            // 7. Return: AnalysisResource
            return new AnalysisResource($analysis->load('upload'));

        } catch (Throwable $th) {

            DB::rollBack();

            report($th);

            // 6. Error হলে status: failed, error_message save করো
            if (isset($analysis)) {
                $analysis->update([
                    'status' => 'failed',
                    'error_message' => $th->getMessage(),
                ]);
            }

            return response()->json([
                'message' => 'Analysis failed: '.$th->getMessage(),
            ], 500);
        }
    }

    // GET /api/history
    public function index(Request $request)
    {
        $query = Auth::user()
            ->analyses()
            ->with(['upload'])
            ->latest();

        // Filter by type if provided
        if ($request->has('type') && in_array($request->type, ['prescription', 'test_report'])) {
            $query->whereHas('upload', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        $analyses = $query->paginate(10);

        return AnalysisResource::collection($analyses);
    }

    // GET /api/analyses/{id}
    public function show(Analysis $analysis)
    {
        // Gate check: analysis টি current user এর কিনা
        Gate::authorize('view', $analysis);

        // with(['upload']) load করো
        $analysis->load('upload');

        // Return AnalysisResource
        return new AnalysisResource($analysis);
    }

    // DELETE /api/analyses/{id}
    public function destroy(Analysis $analysis): JsonResponse
    {
        // Gate check
        Gate::authorize('delete', $analysis);

        // Storage থেকে file delete করো
        $this->storage->delete($analysis->upload->storage_path);

        // Upload এবং Analysis delete করো (cascade)
        $analysis->upload->delete();
        $analysis->delete();

        // Return 204
        return response()->json(null, 204);
    }
}
