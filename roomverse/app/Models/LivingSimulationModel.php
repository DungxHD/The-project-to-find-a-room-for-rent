<?php

declare(strict_types=1);

/**
 * Model cho module "Sống thử trước khi thuê".
 *
 * Da sua:
 * - Đổi tên lớp từ `MoPhongSongThuModel` sang `LivingSimulationModel` để tên
 *   file/lớp phản ánh đúng vai trò sau khi gộp 2 dự án.
 * - Gộp dữ liệu từ database `mo_phong_songthu` cũ vào các bảng có tiền tố
 *   `living_simulation_*` để tránh xung đột với phần tìm phòng trọ.
 * - Chuẩn hóa truy vấn về prepared statement để an toàn và dễ bảo trì hơn.
 */
class LivingSimulationModel
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function getQuestionById(int $questionId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, profile_id, sequence_no, current_time, question_text, option_1, option_2, option_3
             FROM living_simulation_questions
             WHERE id = ?
             LIMIT 1'
        );
        $stmt->bind_param('i', $questionId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row) {
            return null;
        }

        $row['options'] = array_values(array_filter(
            [$row['option_1'], $row['option_2'], $row['option_3']],
            static fn(?string $option): bool => $option !== null && trim($option) !== ''
        ));

        return $row;
    }

    public function getScenarioByChoice(int $questionId, string $choice): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT
                s.id,
                s.transport,
                s.description,
                s.outcome,
                s.next_question_id,
                v.video_name,
                v.video_path
             FROM living_simulation_scenarios s
             LEFT JOIN living_simulation_videos v ON v.scenario_id = s.id
             WHERE s.question_id = ? AND s.option_label = ?
             LIMIT 1'
        );
        $stmt->bind_param('is', $questionId, $choice);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ?: null;
    }

    /**
     * Ưu tiên lấy đánh giá đã gắn với đúng phòng đang xem.
     * Nếu chưa có dữ liệu liên kết, fallback về 1 đánh giá mẫu để giao diện
     * kết thúc vẫn hoạt động ổn định.
     */
    public function getRoomReview(?int $roomId = null): ?array
    {
        if ($roomId !== null) {
            $row = $this->findLatestRoomReview($roomId);

            if ($row) {
                return $row;
            }
        }

        $result = $this->db->query($this->getRoomReviewSelectSql() . ' ORDER BY RAND() LIMIT 1');

        return $result ? ($result->fetch_assoc() ?: null) : null;
    }

    private function findLatestRoomReview(int $roomId): ?array
    {
        $stmt = $this->db->prepare(
            $this->getRoomReviewSelectSql() . '
             WHERE room_id = ?
             ORDER BY id DESC
             LIMIT 1'
        );
        $stmt->bind_param('i', $roomId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ?: null;
    }

    private function getRoomReviewSelectSql(): string
    {
        return 'SELECT
                    id, room_id, room_name, room_price, electricity_price, water_price,
                    deposit_amount, extra_utilities, overall_review
                FROM living_simulation_room_reviews';
    }
}
