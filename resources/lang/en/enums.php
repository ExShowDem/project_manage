<?php
use \App\Enums\CensorSubContractorType;
use \App\Enums\CommonStatus;
use \App\Enums\PaymentOrderType;
use \App\Enums\PaymentOrderStatus;
use App\Enums\TaskStatus;
return [
    CensorSubContractorType::class => [
        CensorSubContractorType::APPROVE_VALUE => 'Phê duyệt giá',
        CensorSubContractorType::CONTRACT_PAYMENT => 'Hồ sơ thanh toán',
        CensorSubContractorType::CONTRACT => 'Hợp đồng',
        CensorSubContractorType::DOCUMENT_IN => 'Công văn đến',
        CensorSubContractorType::DOCUMENT_OUT => 'Công văn đi',
        CensorSubContractorType::DRAWING => 'Bản vẽ',
        CensorSubContractorType::OTHERS => 'Khác',
    ],
    CommonStatus::class => [
        CommonStatus::CREATING => 'Đang tạo',
        CommonStatus::CREATED => 'Đã tạo',
        CommonStatus::FORWARDED => 'Chuyển tiếp',
        CommonStatus::APPROVED => 'Đã duyệt',
        CommonStatus::REJECTED => 'Đã từ chối',
        CommonStatus::CANCELED => 'Đã hủy',
    ],
    PaymentOrderType::class => [
        PaymentOrderType::SETTLEMENT => 'Quyết toán',
        PaymentOrderType::PAY => 'Thanh toán',
        PaymentOrderType::ADVANCE => 'Tạm ứng',
    ],
    PaymentOrderStatus::class => [
        PaymentOrderStatus::NOT_APPROVED => 'Chưa duyệt',
        PaymentOrderStatus::UNPAID => 'Chưa thanh toán',
        PaymentOrderStatus::PAID => 'Đã thanh toán',
        PaymentOrderStatus::REFUSE => 'Từ chối',
        PaymentOrderStatus::CANCELED => 'Hủy bỏ',
    ],
    TaskStatus::class => [
        TaskStatus::CREATED => 'Chờ duyệt',
        TaskStatus::APPROVED => 'Đã duyệt',
        TaskStatus::FORWARDED => 'Chuyển xử lý',
        TaskStatus::REJECTED => 'Trả về',
        TaskStatus::CANCELED => 'Hủy bỏ',
    ]
];
