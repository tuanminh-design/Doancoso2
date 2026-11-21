<?php
namespace App\Helpers;

class Format
{
    public static function currency(float|int|string|null $amount): string
    {
        if ($amount === null || $amount === '') {
            return '0₫';
        }
        return number_format((float)$amount, 0, ',', '.') . '₫';
    }

    public static function discountPercent(float|int|string $originalPrice, float|int|string|null $salePrice): ?int
    {
        $original = (float)$originalPrice;
        $sale = (float)$salePrice;

        if ($sale > 0 && $sale < $original && $original > 0) {
            return (int)round((($original - $sale) / $original) * 100);
        }
        return null;
    }

    public static function date(?string $dateStr, string $format = 'd/m/Y'): string
    {
        if (empty($dateStr)) return '';
        $time = strtotime($dateStr);
        return $time ? date($format, $time) : $dateStr;
    }

    public static function datetime(?string $dateStr, string $format = 'd/m/Y H:i'): string
    {
        if (empty($dateStr)) return '';
        $time = strtotime($dateStr);
        return $time ? date($format, $time) : $dateStr;
    }

    public static function excerpt(?string $text, int $limit = 130): string
    {
        if (empty($text)) return '';
        $clean = strip_tags($text);
        if (mb_strlen($clean) <= $limit) {
            return $clean;
        }
        return mb_substr($clean, 0, $limit) . '...';
    }

    public static function slug(string $string): string
    {
        $search = [
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ',
            'À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ',
            'È', 'É', 'Ẹ', 'Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ',
            'Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ',
            'Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ',
            'Ù', 'Ú', 'Ụ', 'Ủ', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ',
            'Ỳ', 'Ý', 'Ỵ', 'Ỷ', 'Ỹ',
            'Đ'
        ];

        $replace = [
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd',
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd'
        ];

        $string = str_replace($search, $replace, $string);
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9-]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        return trim($string, '-');
    }

    public static function orderStatusText(string $status): string
    {
        return match ($status) {
            'pending'   => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'shipping'  => 'Đang giao hàng',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            default     => ucfirst($status),
        };
    }

    public static function orderStatusBadge(string $status): string
    {
        $class = match ($status) {
            'pending'   => 'badge-warning',
            'confirmed' => 'badge-info',
            'shipping'  => 'badge-primary',
            'completed' => 'badge-success',
            'cancelled' => 'badge-danger',
            default     => 'badge-secondary',
        };

        return sprintf('<span class="badge %s">%s</span>', $class, self::orderStatusText($status));
    }

    public static function paymentStatusBadge(string $status): string
    {
        if ($status === 'paid') {
            return '<span class="badge badge-success">Đã thanh toán</span>';
        }
        return '<span class="badge badge-warning">Chưa thanh toán</span>';
    }

    public static function paymentMethodText(string $method): string
    {
        return match ($method) {
            'cod'           => 'Thanh toán khi nhận hàng (COD)',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
            default         => strtoupper($method),
        };
    }
}
