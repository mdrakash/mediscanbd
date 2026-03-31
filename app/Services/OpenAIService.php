<?php

namespace App\Services;

use App\Exceptions\OpenAIAnalysisException;
use Exception;
use Illuminate\Support\Facades\Log;
use OpenAI\Client;

class OpenAIService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function analyze(string $imagePath, string $type, string $language): array
    {
        try {
            // 1. Image file read করে base64 করো
            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath);

            // 2. Appropriate prompt select করো
            $prompt = $this->buildPrompt($type, $language);

            // 3. OpenAI API call করো
            $response = $this->client->chat()->create([
                'model' => config('openai.model', 'gpt-4o'),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => $prompt,
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => "data:{$mimeType};base64,{$imageData}",
                                ],
                            ],
                        ],
                    ],
                ],
                'max_tokens' => 4000,
            ]);

            $content = $response['choices'][0]['message']['content'] ?? '';

            // 4. Response parse করো
            return $this->parseResponse($content);

        } catch (Exception $e) {
            Log::error('OpenAI Analysis Error: '.$e->getMessage());
            throw new OpenAIAnalysisException('Failed to analyze image: '.$e->getMessage(), $e->getCode(), $e);
        }
    }

    private function buildPrompt(string $type, string $language): string
    {
        // type: 'prescription' | 'test_report'
        // language: 'bn' | 'en'

        if ($type === 'prescription') {
            if ($language === 'bn') {
                return 'তুমি একজন অভিজ্ঞ বাংলাদেশী ফার্মাসিস্ট এবং চিকিৎসা বিশেষজ্ঞ।
এই প্রেসক্রিপশনের ছবি বিশ্লেষণ করো এবং শুধুমাত্র নিচের JSON format-এ উত্তর দাও।
অন্য কোনো text লিখবে না, শুধু valid JSON দাও।

{
  "medicines": [
    {
      "name": "ওষুধের নাম",
      "generic_name": "generic নাম",
      "dosage": "ডোজ (যেমন: ৫০০mg)",
      "frequency": "কতবার (যেমন: দিনে ৩ বার)",
      "duration": "কতদিন",
      "purpose": "এই ওষুধ কী কাজ করে — বাংলাদেশের প্রেক্ষাপটে সাধারণ ব্যবহার বলো",
      "bd_brands": ["বাংলাদেশে পাওয়া যায় এমন brand নাম"],
      "instructions": "খাওয়ার নিয়ম",
      "side_effects": ["পার্শ্বপ্রতিক্রিয়া ১", "পার্শ্বপ্রতিক্রিয়া ২"],
      "warnings": "বিশেষ সতর্কতা",
      "price_range": "আনুমানিক দাম বাংলাদেশে (যদি জানা থাকে)"
    }
  ],
  "tests_advised": ["কোনো পরীক্ষা দিলে নাম"],
  "diagnosis": "সম্ভাব্য রোগ বা সমস্যা (যদি প্রেসক্রিপশনে বোঝা যায়)",
  "general_advice": "ডাক্তারের সাধারণ পরামর্শ",
  "next_visit": "পরবর্তী ভিজিটের তথ্য",
  "lifestyle_tips": "বাংলাদেশের প্রেক্ষাপটে জীবনযাত্রার পরামর্শ",
  "important_notes": "গুরুত্বপূর্ণ যেকোনো বিষয়",
  "disclaimer": "এটি AI বিশ্লেষণ। সঠিক চিকিৎসার জন্য অবশ্যই রেজিস্টার্ড চিকিৎসকের পরামর্শ নিন।"
}';
            } else {
                return "You are an experienced pharmacist and medical expert familiar with Bangladesh's 
healthcare system, medicine market, and local brands (Square, Beximco, Incepta, 
ACI, Eskayef, Renata, etc.).

Analyze this prescription image and respond ONLY with valid JSON. No other text.

{
  \"medicines\": [
    {
      \"name\": \"Medicine name as written\",
      \"generic_name\": \"Generic/INN name\",
      \"dosage\": \"e.g. 500mg\",
      \"frequency\": \"e.g. 3 times daily\",
      \"duration\": \"e.g. 7 days\",
      \"purpose\": \"What this medicine treats and why it may be prescribed\",
      \"bd_brands\": [\"Available brands in Bangladesh\"],
      \"instructions\": \"e.g. Take after meals with water\",
      \"side_effects\": [\"Common side effect 1\", \"side effect 2\"],
      \"warnings\": \"Special warnings if any\",
      \"price_range\": \"Approximate price in Bangladesh if known\"
    }
  ],
  \"tests_advised\": [\"Any tests prescribed\"],
  \"diagnosis\": \"Likely condition being treated if determinable\",
  \"general_advice\": \"Doctor's general advice from prescription\",
  \"next_visit\": \"Follow-up information if mentioned\",
  \"lifestyle_tips\": \"Lifestyle recommendations relevant to Bangladesh\",
  \"important_notes\": \"Any critical information\",
  \"disclaimer\": \"This is AI analysis only. Always consult a registered physician.\"
}";
            }
        } elseif ($type === 'test_report') {
            if ($language === 'bn') {
                return 'তুমি একজন অভিজ্ঞ বাংলাদেশী চিকিৎসক যিনি রোগীদের মেডিকেল রিপোর্ট সহজ ভাষায় 
বোঝাতে পারদর্শী। এই মেডিকেল টেস্ট রিপোর্টটি বিশ্লেষণ করো।
শুধু valid JSON দাও, অন্য কিছু লিখবে না।

{
  "report_type": "রিপোর্টের ধরন (যেমন: CBC, LFT, Lipid Profile, Urine R/E)",
  "lab_name": "ল্যাব এর নাম (যদি দেখা যায়)",
  "test_date": "পরীক্ষার তারিখ (যদি দেখা যায়)",
  "patient_info": "রোগীর তথ্য (নাম/বয়স যদি দেখা যায়, sensitive info বাদ দাও)",
  "summary": "পুরো রিপোর্টের সহজ বাংলায় সারসংক্ষেপ — একজন সাধারণ মানুষ বুঝবে এভাবে",
  "parameters": [
    {
      "name": "পরীক্ষার নাম",
      "value": "ফলাফল",
      "unit": "একক (যেমন: mg/dL)",
      "normal_range": "স্বাভাবিক মান",
      "status": "normal|high|low|critical",
      "plain_explanation": "এই মান কী মানে সহজ বাংলায় — ভয় না দিয়ে সহজে বোঝাও"
    }
  ],
  "abnormal_findings": ["অস্বাভাবিক ফলাফলের তালিকা"],
  "overall_status": "normal|mildly_abnormal|moderately_abnormal|severely_abnormal",
  "possible_implications": "এই ফলাফল কী ইঙ্গিত করতে পারে (ভয় না দিয়ে তথ্য দাও)",
  "recommended_actions": "কী করা উচিত",
  "foods_to_eat": ["কী খাওয়া ভালো — বাংলাদেশে সহজলভ্য খাবার"],
  "foods_to_avoid": ["কী খাওয়া এড়ানো উচিত"],
  "next_steps": "পরবর্তী পদক্ষেপ",
  "when_to_see_doctor": "কখন তাড়াতাড়ি ডাক্তার দেখাতে হবে",
  "disclaimer": "এটি AI বিশ্লেষণ। সঠিক ব্যাখ্যার জন্য অবশ্যই রেজিস্টার্ড চিকিৎসকের পরামর্শ নিন।"
}';
            } else {
                return "You are an experienced medical professional helping patients understand their 
test reports in simple language. You are familiar with Bangladesh's healthcare 
context (common labs: Popular, Ibn Sina, Lab Aid, Shahabuddin, Dhaka Lab Aid, 
Delta, etc.).

Analyze this medical test report image and respond ONLY with valid JSON.

{
  \"report_type\": \"e.g. CBC, LFT, Lipid Profile, Urine R/E\",
  \"lab_name\": \"Lab name if visible\",
  \"test_date\": \"Test date if visible\",
  \"patient_info\": \"Patient info if visible (omit sensitive identifiers)\",
  \"summary\": \"Complete plain-language summary of the report for a layperson\",
  \"parameters\": [
    {
      \"name\": \"Test parameter name\",
      \"value\": \"Result value\",
      \"unit\": \"Unit e.g. mg/dL\",
      \"normal_range\": \"Normal reference range\",
      \"status\": \"normal|high|low|critical\",
      \"plain_explanation\": \"What this value means in simple terms\"
    }
  ],
  \"abnormal_findings\": [\"List of abnormal results\"],
  \"overall_status\": \"normal|mildly_abnormal|moderately_abnormal|severely_abnormal\",
  \"possible_implications\": \"What these results might indicate\",
  \"recommended_actions\": \"What should be done\",
  \"foods_to_eat\": [\"Recommended foods available in Bangladesh\"],
  \"foods_to_avoid\": [\"Foods to avoid\"],
  \"next_steps\": \"Suggested follow-up actions\",
  \"when_to_see_doctor\": \"Warning signs requiring immediate medical attention\",
  \"disclaimer\": \"This is AI analysis only. Always consult a registered physician.\"
}";
            }
        }

        throw new Exception('Invalid type or language');
    }

    private function parseResponse(string $content): array
    {
        // JSON decode করো
        $content = trim($content);

        // ```json``` markdown block থাকলে strip করো
        if (preg_match('/```json\s*(.*?)\s*```/s', $content, $matches)) {
            $content = $matches[1];
        }

        $decoded = json_decode($content, true);

        // Invalid JSON হলে ['error' => true, 'raw' => $content] return করো
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['error' => true, 'raw' => $content];
        }

        return $decoded;
    }
}
