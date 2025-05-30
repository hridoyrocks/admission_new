<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimeCondition;
use App\Models\ClassSession;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TimeConditionController extends Controller
{
    public function index()
    {
        $conditions = TimeCondition::orderBy('profession')->get();
        $activeBatch = Batch::where('is_active', true)->with('classSessions')->first();
        $availableTimes = $this->getAvailableTimesFromSessions();
        
        // If no conditions exist, create default ones
        if ($conditions->isEmpty()) {
            $this->createDefaultConditions();
            $conditions = TimeCondition::orderBy('profession')->get();
        }
        
        return view('admin.time-conditions', compact('conditions', 'activeBatch', 'availableTimes'));
    }

    public function update(Request $request, TimeCondition $condition)
    {
        $validatedData = $this->validateTimeCondition($request);
        
        DB::beginTransaction();
        
        try {
            if ($validatedData['is_fixed']) {
                $timeExists = $this->ensureTimeExistsInSessions($validatedData['fixed_time']);
                
                $condition->update([
                    'is_fixed' => true,
                    'fixed_time' => $validatedData['fixed_time'],
                    'score_rules' => null
                ]);
                
                $message = $timeExists ? 
                    'Time condition updated and synced with existing session.' : 
                    'Time condition updated and new session created.';
                    
            } else {
                // Validate and sort score rules
                $scoreRules = $this->processScoreRules($validatedData['score_rules']);
                
                // Ensure all times exist in sessions
                foreach ($scoreRules as $rule) {
                    $this->ensureTimeExistsInSessions($rule['time']);
                }
                
                $condition->update([
                    'is_fixed' => false,
                    'fixed_time' => null,
                    'score_rules' => $scoreRules
                ]);
                
                $message = 'Score-based time condition updated and all times synced with sessions.';
            }
            
            DB::commit();
            
            Log::info('Time condition updated and synced', [
                'condition_id' => $condition->id,
                'profession' => $condition->profession,
                'is_fixed' => $condition->is_fixed
            ]);
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Time condition update failed', [
                'condition_id' => $condition->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Failed to update: ' . $e->getMessage());
        }
    }

    /**
     * Get all available times from existing sessions
     */
    private function getAvailableTimesFromSessions()
    {
        $activeBatch = Batch::where('is_active', true)->first();
        
        if (!$activeBatch) {
            return $this->getDefaultTimes();
        }
        
        $sessions = ClassSession::where('batch_id', $activeBatch->id)
            ->select('time', 'days', 'session_name')
            ->get();
            
        if ($sessions->isEmpty()) {
            return $this->getDefaultTimes();
        }
        
        return $sessions->map(function($session) {
            return [
                'time' => $session->time,
                'days' => $session->days,
                'name' => $session->session_name,
                'display' => $session->time . ' (' . $session->days . ')'
            ];
        })->toArray();
    }

    /**
     * Get default time options
     */
    private function getDefaultTimes()
    {
        return [
            [
                'time' => 'Morning 8:00 AM',
                'days' => 'Sunday, Tuesday, Thursday',
                'name' => 'Morning Session',
                'display' => 'Morning 8:00 AM (Sun, Tue, Thu)'
            ],
            [
                'time' => 'Morning 10:00 AM',
                'days' => 'Monday, Wednesday, Friday',
                'name' => 'Housewife Morning Session',
                'display' => 'Morning 10:00 AM (Mon, Wed, Fri)'
            ],
            [
                'time' => 'Evening 6:00 PM',
                'days' => 'Sunday, Tuesday, Thursday',
                'name' => 'Evening Session A',
                'display' => 'Evening 6:00 PM (Sun, Tue, Thu)'
            ],
            [
                'time' => 'Evening 7:00 PM',
                'days' => 'Sunday, Tuesday, Thursday',
                'name' => 'Evening Session B',
                'display' => 'Evening 7:00 PM (Sun, Tue, Thu)'
            ],
            [
                'time' => 'Evening 8:00 PM',
                'days' => 'Sunday, Tuesday, Thursday',
                'name' => 'Evening Session C',
                'display' => 'Evening 8:00 PM (Sun, Tue, Thu)'
            ]
        ];
    }

    /**
     * Ensure time exists in sessions, create if not
     */
    private function ensureTimeExistsInSessions($time)
    {
        $activeBatch = Batch::where('is_active', true)->first();
        
        if (!$activeBatch) {
            Log::warning('No active batch found, cannot sync time with sessions');
            return false;
        }
        
        // Check if session with this time already exists
        $existingSession = ClassSession::where('batch_id', $activeBatch->id)
            ->where('time', $time)
            ->first();
            
        if ($existingSession) {
            return true; // Already exists
        }
        
        // Create new session
        $sessionData = $this->generateSessionData($time, $activeBatch->id);
        
        ClassSession::create($sessionData);
        
        Log::info('New session created for time sync', [
            'time' => $time,
            'batch_id' => $activeBatch->id,
            'session_name' => $sessionData['session_name']
        ]);
        
        return false; // Was created new
    }

    /**
     * Generate session data based on time
     */
    private function generateSessionData($time, $batchId)
    {
        // Determine session type based on time
        $timeUpper = strtoupper($time);
        
        if (strpos($timeUpper, 'MORNING') !== false || strpos($timeUpper, 'AM') !== false) {
            if (strpos($timeUpper, '8:00') !== false || strpos($timeUpper, '8AM') !== false) {
                return [
                    'batch_id' => $batchId,
                    'session_name' => 'Student Morning Session',
                    'time' => $time,
                    'days' => 'Sunday, Tuesday, Thursday',
                    'current_count' => 0
                ];
            } else {
                return [
                    'batch_id' => $batchId,
                    'session_name' => 'Housewife Morning Session',
                    'time' => $time,
                    'days' => 'Monday, Wednesday, Friday',
                    'current_count' => 0
                ];
            }
        } else {
            // Evening sessions
            $sessionName = 'Evening Session';
            if (strpos($timeUpper, '6:00') !== false || strpos($timeUpper, '6PM') !== false) {
                $sessionName = 'Evening Session A (Beginners)';
            } elseif (strpos($timeUpper, '7:00') !== false || strpos($timeUpper, '7PM') !== false) {
                $sessionName = 'Evening Session B (Intermediate)';
            } elseif (strpos($timeUpper, '8:00') !== false || strpos($timeUpper, '8PM') !== false) {
                $sessionName = 'Evening Session C (Advanced)';
            }
            
            return [
                'batch_id' => $batchId,
                'session_name' => $sessionName,
                'time' => $time,
                'days' => 'Sunday, Tuesday, Thursday',
                'current_count' => 0
            ];
        }
    }

    /**
     * Sync all existing time conditions with sessions
     */
    public function syncAllTimesWithSessions()
    {
        $activeBatch = Batch::where('is_active', true)->first();
        
        if (!$activeBatch) {
            return redirect()->back()->with('error', 'No active batch found to sync with.');
        }
        
        $conditions = TimeCondition::all();
        $syncedTimes = [];
        
        DB::beginTransaction();
        
        try {
            foreach ($conditions as $condition) {
                if ($condition->is_fixed && $condition->fixed_time) {
                    $this->ensureTimeExistsInSessions($condition->fixed_time);
                    $syncedTimes[] = $condition->fixed_time;
                } elseif ($condition->score_rules) {
                    foreach ($condition->score_rules as $rule) {
                        if (isset($rule['time'])) {
                            $this->ensureTimeExistsInSessions($rule['time']);
                            $syncedTimes[] = $rule['time'];
                        }
                    }
                }
            }
            
            DB::commit();
            
            $uniqueTimes = array_unique($syncedTimes);
            $message = 'Successfully synced ' . count($uniqueTimes) . ' time slots with class sessions.';
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Sync failed: ' . $e->getMessage());
        }
    }

    /**
     * Validate time condition request
     */
    private function validateTimeCondition(Request $request)
    {
        if ($request->input('is_fixed')) {
            return $request->validate([
                'is_fixed' => 'boolean',
                'fixed_time' => 'required|string|max:255'
            ]);
        } else {
            return $request->validate([
                'is_fixed' => 'boolean',
                'score_rules' => 'required|array|min:1',
                'score_rules.*.min_score' => 'required|integer|min:0|max:40',
                'score_rules.*.max_score' => 'required|integer|min:0|max:40',
                'score_rules.*.time' => 'required|string|max:255'
            ]);
        }
    }

    /**
     * Process and validate score rules
     */
    private function processScoreRules($scoreRules)
    {
        $processedRules = [];
        
        foreach ($scoreRules as $rule) {
            // Skip empty rules
            if (empty($rule['min_score']) && empty($rule['max_score']) && empty($rule['time'])) {
                continue;
            }
            
            $minScore = (int) $rule['min_score'];
            $maxScore = (int) $rule['max_score'];
            
            // Validate score range
            if ($minScore > $maxScore) {
                throw new \Exception("Minimum score cannot be greater than maximum score (Rule: {$minScore}-{$maxScore})");
            }
            
            // Check for overlapping ranges
            foreach ($processedRules as $existingRule) {
                if ($this->rangesOverlap($minScore, $maxScore, $existingRule['min_score'], $existingRule['max_score'])) {
                    throw new \Exception("Score ranges cannot overlap. Range {$minScore}-{$maxScore} overlaps with {$existingRule['min_score']}-{$existingRule['max_score']}");
                }
            }
            
            $processedRules[] = [
                'min_score' => $minScore,
                'max_score' => $maxScore,
                'time' => trim($rule['time'])
            ];
        }
        
        if (empty($processedRules)) {
            throw new \Exception("At least one valid score rule is required");
        }
        
        // Sort rules by min_score
        usort($processedRules, function($a, $b) {
            return $a['min_score'] <=> $b['min_score'];
        });
        
        return $processedRules;
    }

    /**
     * Check if two score ranges overlap
     */
    private function rangesOverlap($min1, $max1, $min2, $max2)
    {
        return !($max1 < $min2 || $max2 < $min1);
    }

    /**
     * Create default time conditions
     */
    private function createDefaultConditions()
    {
        $defaultConditions = [
            [
                'profession' => 'student',
                'is_fixed' => true,
                'fixed_time' => 'Morning 8:00 AM',
                'score_rules' => null
            ],
            [
                'profession' => 'job_holder',
                'is_fixed' => false,
                'fixed_time' => null,
                'score_rules' => [
                    ['min_score' => 0, 'max_score' => 15, 'time' => 'Evening 6:00 PM'],
                    ['min_score' => 16, 'max_score' => 25, 'time' => 'Evening 7:00 PM'],
                    ['min_score' => 26, 'max_score' => 40, 'time' => 'Evening 8:00 PM']
                ]
            ],
            [
                'profession' => 'housewife',
                'is_fixed' => true,
                'fixed_time' => 'Morning 10:00 AM',
                'score_rules' => null
            ]
        ];

        foreach ($defaultConditions as $condition) {
            TimeCondition::create($condition);
        }
        
        // Sync these default times with sessions
        $this->syncAllTimesWithSessions();
    }
}