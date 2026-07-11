<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/LivingSimulationModel.php';

class SimulationController
{
    /**
     * Da sua:
     * - Đổi sang `LivingSimulationModel` để tên class/file đồng nhất hơn sau
     *   khi hợp nhất codebase.
     * - Giữ nguyên các key JSON kiểu cũ để frontend hiện tại không bị vỡ.
     */
    private LivingSimulationModel $simulationModel;

    public function __construct(mysqli $db)
    {
        $this->simulationModel = new LivingSimulationModel($db);
    }

    public function getQuestion(int $questionId): array
    {
        $question = $this->simulationModel->getQuestionById($questionId);

        if (!$question) {
            return ['found' => false];
        }

        return [
            'found' => true,
            'id_ai_hoi' => (int) $question['id'],
            'stt' => (int) $question['sequence_no'],
            'thoi_gian' => $question['current_time'],
            'noi_dung_hoi' => $question['question_text'],
            'lua_chon' => $question['options'],
        ];
    }

    public function submitChoice(int $questionId, string $choice): array
    {
        $scenario = $this->simulationModel->getScenarioByChoice($questionId, $choice);

        if (!$scenario) {
            return ['found' => false];
        }

        return [
            'found' => true,
            'phuong_tien' => $scenario['transport'],
            'mo_ta' => $scenario['description'],
            'diem_cuoi' => $scenario['outcome'],
            'video' => $scenario['video_path'],
            'ten_video' => $scenario['video_name'],
            'next_id' => $scenario['next_question_id'] !== null ? (int) $scenario['next_question_id'] : null,
        ];
    }

    public function getRoomReview(?int $roomId = null): array
    {
        $review = $this->simulationModel->getRoomReview($roomId);

        if (!$review) {
            return ['found' => false];
        }

        return [
            'found' => true,
            'ten_phong_tro' => $review['room_name'],
            'gia_phong' => $review['room_price'] !== null ? (int) $review['room_price'] : null,
            'gia_dien' => $review['electricity_price'] !== null ? (int) $review['electricity_price'] : null,
            'gia_nuoc' => $review['water_price'] !== null ? (int) $review['water_price'] : null,
            'tien_coc' => $review['deposit_amount'] !== null ? (int) $review['deposit_amount'] : null,
            'tien_ich_khac' => $review['extra_utilities'],
            'danh_gia_chung' => $review['overall_review'],
        ];
    }
}
